<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReviewPost;
use App\Models\InformationalPost;
use App\Models\Group;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/search",
     *     summary="Handles the search functionality for users and posts",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", enum={"users", "posts"})
     *     ),
     *     @OA\Parameter(
     *         name="searchType",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum={"full-text", "exact-match"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function search(Request $request)
    {
        // Validate the request data
        $request->validate([
            'query' => 'required|string|max:255',
            'type' => 'required|in:users,posts,groups,comments',
            'searchType' => 'nullable|in:full-text,exact-match',
        ]);
    
        // Sanitize and retrieve the search query and type
        $query = strip_tags(strtolower($request->input('query')));
        $type = $request->input('type');
        $searchType = $request->input('searchType', 'exact-match');
        $results = [];
    
        try {
            // Perform the search based on the type
            if ($type === 'users') {
                $results = $this->searchUsers($query, $searchType);
            } elseif ($type === 'posts') {
                $results = $this->searchPosts($query, $searchType);
            } elseif ($type === 'groups') {
                $results = $this->searchGroups($query, $searchType);
            } elseif ($type === 'comments') {
                $results = $this->searchComments($query, $searchType);
            }
    
            // Return the search results as a JSON response
            return response()->json($results, 200);
        } catch (\Exception $e) {
            // Return a JSON response indicating an error occurred
            return response()->json(['error' => 'An error occurred while searching.'], 500);
        }
    }

    private function searchComments(string $query, string $searchType): array
    {
        // Perform a full-text search if the search type is 'full-text'
        if ($searchType === 'full-text') {
            $comments = Comment::whereRaw("to_tsvector('english', content) @@ plainto_tsquery('english', ?)", [$query])
                ->with('user')
                ->take(6)
                ->get(['id', 'content', 'post_id', 'user_id']);
        } else {
            // Perform a case-insensitive search if the search type is 'exact-match'
            $comments = Comment::whereRaw('LOWER(content) LIKE ?', ["%{$query}%"])
                ->with('user')
                ->take(6)
                ->get(['id', 'content', 'post_id', 'user_id']);
        }
    
        // Map the comments to the desired format
        $results = $comments->map(function ($comment) {
            return [
                'url' => route('posts.show', $comment->post_id),
                'content' => $comment->content,
                'author' => $comment->user->name,
                'post_id' => $comment->post_id,
            ];
        })->toArray();
    
        // Return the search results
        return $results;
    }

    private function searchGroups(string $query, string $searchType): array
    {
        // Perform a full-text search if the search type is 'full-text'
        if ($searchType === 'full-text') {
            $groups = Group::whereRaw("to_tsvector('english', name || ' ' || description) @@ plainto_tsquery('english', ?)", [$query])
                ->take(6)
                ->get(['id', 'name', 'description']);
        } else {
            // Perform a case-insensitive search if the search type is 'exact-match'
            $groups = Group::where(function ($queryBuilder) use ($query) {
                $queryBuilder->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$query}%"]);
            })
                ->take(6)
                ->get(['id', 'name', 'description']);
        }
    
        // Map the groups to the desired format
        $results = $groups->map(function ($group) {
            return [
                'url' => route('groups.show', $group->id),
                'name' => $group->name,
                'description' => $group->description,
            ];
        })->toArray();
    
        // Return the search results
        return $results;
    }
    
    private function searchUsers(string $query, string $searchType): array
    {
        // Perform a full-text search if the search type is 'full-text'
        if ($searchType === 'full-text') {
            $users = User::whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
                ->where('is_deleted', false)
                ->take(6)
                ->get(['id', 'name', 'description', 'image']);
        } else {
            // Perform a case-insensitive search if the search type is 'exact-match'
            $users = User::where(function ($queryBuilder) use ($query) {
                $queryBuilder->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$query}%"])
                    ->orWhereRaw('LOWER(username) LIKE ?', ["%{$query}%"]);
            })
                ->where('is_deleted', false)
                ->take(6)
                ->get(['id', 'name', 'description', 'image']);
        }
    
        // Map the users to the desired format
        return $users->map(function ($user) {
            return [
                'url' => route('profile.show', $user->id),
                'name' => $user->name,
                'image' => $user->image,
            ];
        })->toArray();
    }

    private function searchPosts(string $query, string $searchType): array
    {
        // Initialize an empty collection for results
        $results = collect();
    
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the currently authenticated user and cast to the appropriate subclass
            $user = Auth::user()->castToSubclass();
    
            // Perform the search based on the user type
            if ($user->is_admin) {
                $results = $this->searchAllPosts($query, $searchType);
            } elseif ($user instanceof \App\Models\Restaurant) {
                $results = $this->searchPostsForRestaurant($user, $query, $searchType);
            } elseif ($user instanceof \App\Models\Client) {
                $results = $this->searchPostsForClient($user, $query, $searchType);
            }
        } else {
            // Perform the search for public posts if the user is not authenticated
            $results = $this->searchPublicPosts($query, $searchType);
        }
    
        // Return the search results as an array
        return $results->toArray();
    }

    private function searchPostsForRestaurant($restaurant, string $query, string $searchType)
    {
        // Search for review posts related to the restaurant
        $reviewPosts = $this->searchReviewPostsForRestaurant($restaurant->userDetails->id, $query, $searchType);
    
        // Search for informational posts
        $informationalPosts = $this->searchInformationalPosts($query, $searchType);
    
        // Merge and return the results
        return $reviewPosts->merge($informationalPosts);
    }
    
    private function searchPostsForClient($client, string $query, string $searchType)
    {
        // Get the IDs of the clients followed by the authenticated client
        $followedIds = $client->followed->pluck('id')->toArray();
    
        // Search for review posts related to the followed clients
        $reviewPosts = collect($this->searchReviewPostsForClient($followedIds, $query, $searchType));
    
        // Search for informational posts
        $informationalPosts = collect($this->searchInformationalPosts($query, $searchType));
    
        // Merge and return the results
        return $reviewPosts->merge($informationalPosts);
    }
    
    private function searchPublicPosts(string $query, string $searchType)
    {
        // Search for informational posts
        return $this->searchInformationalPosts($query, $searchType);
    }

    private function searchAllPosts(string $query, string $searchType)
    {
        // Search for informational posts
        $informationalPosts = collect($this->searchInformationalPosts($query, $searchType));
        $reviewPosts = collect();
    
        // Perform a full-text search if the search type is 'full-text'
        if ($searchType === 'full-text') {
            $reviewPosts = ReviewPost::with(['postDetails', 'client.userDetails', 'restaurant.userDetails'])
                ->where(function ($q) use ($query) {
                    $q->whereHas('postDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    })
                    ->orWhereHas('client.userDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    })
                    ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    });
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'client');
                });
        } else {
            // Perform a case-insensitive search if the search type is 'exact-match'
            $reviewPosts = ReviewPost::with(['postDetails', 'client.userDetails', 'restaurant.userDetails'])
                ->where(function ($q) use ($query) {
                    $q->whereHas('postDetails', function ($q) use ($query) {
                        $q->whereRaw('content LIKE ?', ["%{$query}%"]);
                    })
                    ->orWhereHas('client.userDetails', function ($q) use ($query) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"]);
                    })
                    ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"]);
                    });
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'client');
                });
        }
    
        // Merge and return the results
        return $informationalPosts->merge($reviewPosts);
    }
    
    // Restaurants can search reviews in which the restaurant is the reviewed
    private function searchReviewPostsForRestaurant(int $restaurantId, string $query, string $searchType)
    {
        // Perform a full-text search if the search type is 'full-text'
        if ($searchType === 'full-text') {
            return ReviewPost::with(['postDetails', 'client.userDetails', 'restaurant.userDetails'])
                ->whereHas('restaurant.userDetails', function ($q) use ($restaurantId) {
                    $q->where('id', $restaurantId);
                })
                ->where(function ($q) use ($query) {
                    $q->whereHas('postDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    })
                    ->orWhereHas('client.userDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    })
                    ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    });
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'client');
                });
        } else {
            // Perform a case-insensitive search if the search type is 'exact-match'
            return ReviewPost::with(['postDetails', 'client.userDetails', 'restaurant.userDetails'])
                ->whereHas('restaurant.userDetails', function ($q) use ($restaurantId) {
                    $q->where('id', $restaurantId);
                })
                ->where(function ($q) use ($query) {
                    $q->whereHas('postDetails', function ($q) use ($query) {
                        $q->whereRaw('content LIKE ?', ["%{$query}%"]);
                    })
                    ->orWhereHas('client.userDetails', function ($q) use ($query) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"]);
                    })
                    ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"]);
                    });
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'client');
                });
        }
    }

    // Clients can view reviews from the clients they follow
    private function searchReviewPostsForClient(array $followedIds, string $query, string $searchType)
    {
        // Perform a full-text search if the search type is 'full-text'
        if ($searchType === 'full-text') {
            return ReviewPost::with(['postDetails', 'client.userDetails', 'restaurant.userDetails'])
                ->whereHas('client.userDetails', function ($q) use ($followedIds) {
                    $q->whereIn('id', $followedIds);
                })
                ->where(function ($q) use ($query) {
                    $q->whereHas('postDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    })
                    ->orWhereHas('client.userDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    })
                    ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                        $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                    });
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'client');
                });
        } else {
            // Perform a case-insensitive search if the search type is 'exact-match'
            return ReviewPost::with(['postDetails', 'client.userDetails', 'restaurant.userDetails'])
                ->whereHas('client.userDetails', function ($q) use ($followedIds) {
                    $q->whereIn('id', $followedIds);
                })
                ->where(function ($q) use ($query) {
                    $q->whereHas('postDetails', function ($q) use ($query) {
                        $q->whereRaw('content LIKE ?', ["%{$query}%"]);
                    })
                    ->orWhereHas('client.userDetails', function ($q) use ($query) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"]);
                    })
                    ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"]);
                    });
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'client');
                });
        }
    }
    

    private function searchInformationalPosts(string $query, string $searchType)
    {
        // Perform a full-text search if the search type is 'full-text'
        if ($searchType === 'full-text') {
            return InformationalPost::with(['postDetails', 'restaurant.userDetails'])
                ->whereHas('postDetails', function ($q) use ($query) {
                    $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                })
                ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                    $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query]);
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'restaurant');
                });
        } else {
            // Perform a case-insensitive search if the search type is 'exact-match'
            return InformationalPost::with(['postDetails', 'restaurant.userDetails'])
                ->whereHas('postDetails', function ($q) use ($query) {
                    $q->whereRaw('content ILIKE ?', ["%{$query}%"]);
                })
                ->orWhereHas('restaurant.userDetails', function ($q) use ($query) {
                    $q->whereRaw('LOWER(name) ILIKE ?', ["%{$query}%"]);
                })
                ->take(6)
                ->get()
                ->map(function ($post) {
                    return $this->formatPostResult($post, 'restaurant');
                });
        }
    }
    
    private function formatPostResult($post, string $type)
    {
        // Format the post result to the desired structure
        return [
            'url' => route('posts.show', $post->id),
            'name' => $post->postDetails->content,
            'author' => optional(optional($post->$type)->userDetails)->name,
        ];
    }
}