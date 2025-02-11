<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Notification;
use App\Models\User;
use App\Models\Post;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestFollow;
use Illuminate\Support\Facades\Storage;
use App\Events\NotificationEvent;


class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/profile/{id}",
     *     summary="Show the profile for a given id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function show($id, Request $request)
    {
        // Retrieve the user as a Client with related details or as a Restaurant if not found as a Client
        $user = Client::with(['userDetails', 'followers', 'followed'])->find($id);
    
        if (!$user) {
            $user = Restaurant::with(['userDetails', 'followers', 'type'])->findOrFail($id);
        }
    
        $perPage = 10;
        $offset = $request->query('offset', 0);
    
        // Retrieve posts for the user based on their type (Client or Restaurant)
        if ($user instanceof Client) {
            $postsQuery = Post::select('post.*')
                ->leftJoin('review_post', 'post.id', '=', 'review_post.id')
                ->where('review_post.client_id', '=', $user->id)
                ->whereNull('review_post.group_id')
                ->orderBy('post.datetime', 'desc')
                ->offset($offset)
                ->limit($perPage);
        } elseif ($user instanceof Restaurant) {
            $postsQuery = Post::select('post.*')
                ->leftJoin('informational_post', 'post.id', '=', 'informational_post.id')
                ->leftJoin('review_post', 'post.id', '=', 'review_post.id')
                ->whereNull('review_post.group_id')
                ->where(function ($query) use ($user) {
                    $query->where('informational_post.restaurant_id', '=', $user->id)
                          ->orWhere('review_post.restaurant_id', '=', $user->id);
                })
                ->orderByRaw('informational_post.pinned DESC, post.datetime DESC')
                ->offset($offset)
                ->limit($perPage);
        }
    
        // Retrieve and cast posts to their appropriate subclasses
        $posts = $postsQuery->get()->map(function ($post) {
            return $post->castToSubclass();
        });
    
        $offset += $posts->count();
    
        // Check if a follow request is pending for the authenticated client user
        $followRequestPending = false;
        if (Auth::check() && Auth::user()->castToSubclass() instanceof Client) {
            $client = Auth::user()->castToSubclass();
            $followRequestPending = RequestFollow::where('requester_client_id', $client->id)
                ->where('receiver_client_id', $user->id)
                ->exists();
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
    
        // Return the profile view with the user, follow request status, posts, and offset data
        return view('pages.profile', compact('user', 'followRequestPending', 'posts', 'offset'));
    }

    /**
     * @OA\Post(
     *     path="/profile/{id}/follow",
     *     summary="Follow a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully followed the user",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function follow($id)
    {
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user to be followed is a Client
        if ($user->castToSubclass() instanceof Client) {
            // Create a follow request
            RequestFollow::create([
                'requester_client_id' => $client->id,
                'receiver_client_id' => $user->id,
                'datetime' => now()
            ]);
    
            // Trigger a notification event for the follow request
            event(new NotificationEvent(Auth::user()->name . " sent you a follow request!", $id));
    
            // Return a JSON response indicating success and user type
            return response()->json(['success' => true, 'userType' => 'client']);
        } elseif ($user->castToSubclass() instanceof Restaurant) {
            // Attach the client to the followed restaurants
            $client->followedRestaurants()->attach($user->id);
    
            // Trigger a notification event for the follow
            event(new NotificationEvent(Auth::user()->name . " started following you!", $id));
    
            // Return a JSON response indicating success and user type
            return response()->json(['success' => true, 'userType' => 'restaurant']);
        }
    
        // Return a JSON response indicating failure
        return response()->json(['success' => false]);
    }

    /**
     * @OA\Post(
     *     path="/profile/{id}/unfollow",
     *     summary="Unfollow a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully unfollowed the user",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function unfollow($id)
    {
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user to be unfollowed is a Client
        if ($user->castToSubclass() instanceof Client) {
            // Detach the client from the followed clients
            $client->followed()->detach($user->id);
        } elseif ($user->castToSubclass() instanceof Restaurant) {
            // Detach the client from the followed restaurants
            $client->followedRestaurants()->detach($user->id);
        }
    
        // Return a JSON response indicating success
        return response()->json(['success' => true]);
    }

    /**
     * @OA\Get(
     *     path="/profile/{id}/edit",
     *     summary="Edit the profile for a given id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function edit($id)
    {
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
    
        // Check if the currently authenticated user is not the user to be edited
        if (Auth::id() !== $user->id) {
            // Redirect to the profile show page if the user is not authorized to edit
            return redirect()->route('profile.show', ['id' => $user->id]);
        }
    
        // Return the edit profile view with the user data
        return view('pages.edit-profile', compact('user'));
    }

    /**
     * @OA\Post(
     *     path="/profile/update",
     *     summary="Update the profile",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="image", type="string", format="binary", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            // Get the currently authenticated user
            $user = Auth::user();
            $user->name = $validatedData['name'];
            $user->description = $validatedData['description'];
    
            // Handle the uploaded image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/profile', 'public');
                $user->image = $imagePath;
            }
    
            // Save the updated user data
            $user->save();
    
            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'user' => [
                    'name' => $user->name,
                    'description' => $user->description,
                    'image' => asset('storage/' . $user->image),
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a JSON response indicating validation errors
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Return a JSON response indicating an unexpected error occurred
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function checkFollowRequest($id)
    {
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if a follow request exists between the authenticated client and the specified user
        $followRequest = RequestFollow::where('requester_client_id', $client->id)
            ->where('receiver_client_id', $id)
            ->first();
    
        // Return true if a follow request exists, otherwise false
        return $followRequest ? true : false;
    }
    
    public function cancelFollowRequest($id)
    {
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Find the follow request between the authenticated client and the specified user
        $followRequest = RequestFollow::where('requester_client_id', $client->id)
            ->where('receiver_client_id', $id)
            ->first();
    
        // Delete the follow request if it exists
        if ($followRequest) {
            $followRequest->delete();
            return response()->json(['success' => true, 'followRequestCancelled' => true]);
        }
    
        // Return a JSON response indicating failure
        return response()->json(['success' => false], 400);
    }
    
    public function destroy($id)
    {
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
    
        // Ensure the authenticated user is deleting their own profile
        if (Auth::id() !== $user->id) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to delete this profile.'], 403);
        }
    
        // Anonymize the user's details
        $user->username = 'anonymous_' . $user->username;
        $user->email = 'anonymous_' . $user->email;
        $user->description = 'This user has been anonymized.';
        $user->password = bcrypt('anonymous_' . $user->username);
        $user->is_deleted = true;
    
        // Save the changes
        $user->save();
    
        // Log out the user
        Auth::logout();
    
        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Profile deleted successfully.']);
    }
    
    public function getFollowers($id)
    {
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
    
        // Retrieve the followers of the user
        $followers = $user->followers;
    
        // Return the followers list view with the followers data
        return view('partials.followers_list', compact('followers'));
    }
    
    public function getFollowing($id)
    {
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);
    
        // Retrieve the clients and restaurants followed by the user
        $followingClients = $user->followedClients;
        $followingRestaurants = $user->followedRestaurants;
    
        // Return the following list view with the followed clients and restaurants data
        return view('partials.following_list', compact('followingClients', 'followingRestaurants'));
    }


}