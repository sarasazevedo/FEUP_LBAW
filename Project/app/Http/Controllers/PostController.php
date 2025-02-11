<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ReviewPost;
use App\Models\InformationalPost;
use App\Models\Client;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/post/{id}",
     *     summary="Show the post for a given id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function show($id)
    {
        // Find the post by ID or fail if not found
        $post = Post::findOrFail($id);
    
        // Authorize the user for the show action on the Post model
        $this->authorize('show', $post);
    
        // Check if the post is a ReviewPost
        if (ReviewPost::find($id)) {
            // Retrieve the ReviewPost with its details and comment count
            $post = ReviewPost::with('postDetails')->withCount('comments')->findOrFail($id);
        } elseif (InformationalPost::find($id)) {
            // Retrieve the InformationalPost with its details and comment count
            $post = InformationalPost::with('postDetails')->withCount('comments')->findOrFail($id);
        }
    
        // Return the post view with the post data
        return view('pages.post', ['post' => $post]);
    }

    /**
     * @OA\Get(
     *     path="/posts",
     *     summary="Show the feed of posts made by followed users and restaurants",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Post"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function list(Request $request)
    {
        $perPage = 10;
        $offset = $request->query('offset', 0);
    
        // Check if the user is not authenticated
        if (!Auth::check()) {
            // Retrieve informational posts for unauthenticated users
            $postsQuery = InformationalPost::select('informational_post.*')
                ->join('post', 'informational_post.id', '=', 'post.id')
                ->orderBy('post.datetime', 'desc')
                ->offset($offset)
                ->limit($perPage);
    
            $posts = $postsQuery->get();
    
            // Handle AJAX request for posts
            if ($request->ajax()) {
                $postHtml = $posts->map(function ($post) {
                    if ($post instanceof \App\Models\ReviewPost) {
                        return view('partials.review_post', ['post' => $post])->render();
                    } elseif ($post instanceof \App\Models\InformationalPost) {
                        return view('partials.informational_post', ['post' => $post])->render();
                    }
                });
    
                return response()->json([
                    'posts' => $postHtml
                ]);
            }
    
            // Return the feed view with the posts data
            return view('pages.feed', [
                'posts' => $posts,
                'offset' => $offset + $perPage
            ]);
    
        } else {
            // Get the currently authenticated user
            $user = Auth::user();
            $posts = collect();
    
            // Check if the user is an admin
            if ($user->is_admin) {
                // Retrieve posts for admin users
                $postsQuery = Post::select('post.*')
                    ->leftJoin('review_post', 'post.id', '=', 'review_post.id')
                    ->leftJoin('informational_post', 'post.id', '=', 'informational_post.id')
                    ->where(function ($query) use ($user) {
                        $query->where(function ($subQuery) use ($user) {
                            $subQuery->where('review_post.client_id', '!=', $user->id)
                                     ->whereNull('review_post.group_id');
                        })
                        ->orWhere(function ($subQuery) use ($user) {
                            $subQuery->where('informational_post.restaurant_id', '!=', $user->id);
                        });
                    })
                    ->orderBy('post.datetime', 'desc')
                    ->offset($offset)
                    ->limit($perPage);
    
                $posts = $postsQuery->get()->map(function ($post) {
                    return $post->castToSubclass();
                });
            } else {
                // Cast the user to the appropriate subclass (Client or Restaurant)
                $user = $user->castToSubclass();
    
                if ($user instanceof Client) {
                    // Retrieve posts for client users
                    $postsQuery = Post::select('post.*')
                        ->leftJoin('review_post', 'post.id', '=', 'review_post.id')
                        ->whereNull('review_post.group_id')
                        ->leftJoin('informational_post', 'post.id', '=', 'informational_post.id')
                        ->leftJoin('follows_client', function ($join) use ($user) {
                            $join->on('review_post.client_id', '=', 'follows_client.followed_client_id')
                                 ->where('follows_client.sender_client_id', '=', $user->id);
                        })
                        ->leftJoin('follows_restaurant', function ($join) use ($user) {
                            $join->on('informational_post.restaurant_id', '=', 'follows_restaurant.restaurant_id')
                                 ->where('follows_restaurant.client_id', '=', $user->id);
                        })
                        ->where(function ($query) {
                            $query->whereNotNull('follows_client.followed_client_id')
                                  ->orWhereNotNull('follows_restaurant.restaurant_id');
                        })
                        ->orderBy('post.datetime', 'desc')
                        ->offset($offset)
                        ->limit($perPage);
    
                    $posts = $postsQuery->get()->map(function ($post) {
                        return $post->castToSubclass();
                    });
    
                    // If no posts are found and offset is 0, retrieve informational posts
                    if ($posts->isEmpty() && $offset == 0) {
                        $postsQuery = InformationalPost::select('informational_post.*')
                            ->join('post', 'informational_post.id', '=', 'post.id')
                            ->orderBy('post.datetime', 'desc')
                            ->offset($offset)
                            ->limit($perPage);
                        $posts = $postsQuery->get();
                    }
                } elseif ($user instanceof Restaurant) {
                    // Retrieve posts for restaurant users
                    $restaurantId = $user->id;
                    $postsQuery = Post::select('post.*')
                        ->leftJoin('informational_post', 'post.id', '=', 'informational_post.id')
                        ->leftJoin('review_post', 'post.id', '=', 'review_post.id')
                        ->whereNull('review_post.group_id')
                        ->where(function ($query) use ($restaurantId) {
                            $query->where('informational_post.restaurant_id', '!=', $restaurantId)
                                  ->orWhere('review_post.restaurant_id', '=', $restaurantId);
                        })
                        ->orderBy('post.datetime', 'desc')
                        ->offset($offset)
                        ->limit($perPage)
                        ->get();
    
                    $posts = $postsQuery->map(function ($post) {
                        return $post->castToSubclass();
                    });
                }
            }
    
            // Handle AJAX request for posts
            if ($request->ajax()) {
                $postHtml = $posts->map(function ($post) {
                    if ($post instanceof \App\Models\ReviewPost) {
                        return view('partials.review_post', ['post' => $post])->render();
                    } elseif ($post instanceof \App\Models\InformationalPost) {
                        return view('partials.informational_post', ['post' => $post])->render();
                    }
                });
    
                return response()->json([
                    'posts' => $postHtml,
                ]);
            }
    
            // Return the feed view with the posts data
            return view('pages.feed', [
                'posts' => $posts,
                'offset' => $offset + $perPage
            ]);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/posts/create",
     *     summary="Create a new post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="images", type="array", @OA\Items(type="string", format="binary")),
     *             @OA\Property(property="rating", type="integer", nullable=true),
     *             @OA\Property(property="restaurant_id", type="integer", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function create(Request $request)
    {
        // Get the group ID from the request
        $groupId = $request->input('group_id');
    
        // Authorize the user for the create action on the Post model
        $this->authorize('create', [Post::class, $groupId]);
    
        // Strip HTML tags from the content
        $contentFound = strip_tags($request->input('content'));
    
        // Check if the content is empty and no images are uploaded
        if (!isset($contentFound) && !$request->hasFile('images')) {
            return response()->json(['success' => false, 'errors' =>  ['post' => ['You cannot create an empty post']] ], 400);
        }
    
        // Validate the uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    return response()->json(['success' => false, 'errors' => ['image' => ['File format not supported']]], 400);
                }
                if ($image->getSize() > 10485760) {
                    return response()->json(['success' => false, 'errors' => ['image' => ['File size exceeds 10MB']]], 400);
                }
            }
        }
    
        // Validate the request data
        try {
            $validatedData = $request->validate([
                'content' => 'nullable|string',
                'images' => 'required|array|min:1|max:8',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'rating' => 'nullable|integer|min:1|max:5',
                'restaurant_id' => 'required_if:user_type,client|exists:restaurant,id',
                'group_id' => 'nullable|exists:group,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }
    
        // Check if the user is a Client and the restaurant ID is not provided
        if (Auth::user()->castToSubclass() instanceof Client && !$request->has('restaurant_id')) {
            return response()->json(['success' => false, 'errors' => ['restaurant_id' => ['The given restaurant was not found']]], 400);
        }
    
        // Create a new post instance
        $post = new Post();
        $post->content = $contentFound;
        $post->save();
    
        // Handle the uploaded images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $filename = $post->id . '_' . ($index + 1) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images/postImages', $filename, 'public');
                $images[] = $path;
            }
        }
    
        // Save the images to the post
        $post->images = $images;
        $post->save();
    
        // Handle the post creation for Client users
        if (Auth::user()->castToSubclass() instanceof Client) {
            $reviewPost = new ReviewPost();
            $reviewPost->id = $post->id;
            $reviewPost->rating = $request->input('rating');
            $reviewPost->client_id = Auth::id();
            $reviewPost->restaurant_id = $request->input('restaurant_id');
            if ($request->has('group_id')) {
                $reviewPost->group_id = $request->input('group_id');
            }
            $reviewPost->save();
          
            return response()->json(['success' => true, 'post' => $post, 'reviewPost' => $reviewPost]);
        } elseif (Auth::user()->castToSubclass() instanceof Restaurant) {
            // Handle the post creation for Restaurant users
            $informationalPost = new InformationalPost();
            $informationalPost->id = $post->id;
            $informationalPost->restaurant_id = Auth::id();
            $informationalPost->save();
         
            return response()->json(['success' => true, 'post' => $post, 'informationalPost' => $informationalPost]);
        }
    
        // Return a JSON response indicating success
        return response()->json(['success' => true, 'post' => $post]);
    }
    /**
     * @OA\Delete(
     *     path="/post/{id}",
     *     summary="Delete a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['success' => 'Post deleted successfully']);
    }

    /**
     * @OA\Put(
     *     path="/posts/{id}",
     *     summary="Update a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="images", type="array", @OA\Items(type="string", format="binary")),
     *             @OA\Property(property="rating", type="integer", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the post by ID or fail if not found
            $post = Post::findOrFail($id);
    
            // Authorize the user for the update action on the Post model
            $this->authorize('update', $post);
    
            // Validate the request data
            $validatedData = $request->validate([
                'content' => 'nullable|string',
                'images' => 'nullable|array|max:6',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'rating' => 'nullable|integer|min:1|max:5',
            ]);
    
            // Update the content if provided
            if ($request->has('content')) {
                $post->content = strip_tags($request->input('content'));
            }
    
            // Handle the uploaded images
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $index => $image) {
                    $filename = $post->id . '_' . ($index + 1) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('images/postImages', $filename, 'public');
                    $images[] = $path;
                }
                $post->images = $images;
            }
    
            // Update the post's datetime
            $post->datetime = now();
            $post->save();
    
            // Handle the update for Client users
            if (Auth::user()->castToSubclass() instanceof Client) {
                $reviewPost = ReviewPost::findOrFail($id);
                if ($request->has('rating')) {
                    $reviewPost->rating = $request->input('rating');
                }
                $reviewPost->save();
               
                return response()->json(['success' => true, 'post' => $post, 'reviewPost' => $reviewPost]);
            }
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'post' => $post]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a JSON response indicating validation errors
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Return a JSON response indicating an unexpected error occurred
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    public function pin($id)
    {
        // Find the post by ID or fail if not found
        $post = Post::findOrFail($id);
    
        // Authorize the user for the pinOrUnpin action on the Post model
        $this->authorize('pinOrUnpin', $post);
    
        // Cast the post to the appropriate subclass
        $post = $post->castToSubclass();
    
        // Check if the post is an InformationalPost
        if ($post instanceof \App\Models\InformationalPost) {
            // Pin the post
            $post->pinned = true;
            $post->save();
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Post pinned successfully.']);
        }
    
        // Return a JSON response indicating failure to pin the post
        return response()->json(['success' => false, 'message' => 'Post wasn\'t pinned successfully.']);
    }
    
    public function unpin($id)
    {
        // Find the post by ID or fail if not found
        $post = Post::findOrFail($id);
    
        // Authorize the user for the pinOrUnpin action on the Post model
        $this->authorize('pinOrUnpin', $post);
    
        // Cast the post to the appropriate subclass
        $post = $post->castToSubclass();
    
        // Check if the post is an InformationalPost
        if ($post instanceof \App\Models\InformationalPost) {
            // Unpin the post
            $post->pinned = false;
            $post->save();
        }
    
        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Post unpinned successfully.']);
    }
}