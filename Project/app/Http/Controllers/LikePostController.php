<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Models\LikePost;
use App\Models\LikePostNotification;
use App\Models\LikeNotification;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikePostController extends Controller
{
    /**
     * @OA\Post(
     *     path="/like-post/{id}",
     *     summary="Like or unlike a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully liked or unliked the post",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="liked", type="boolean", nullable=true),
     *             @OA\Property(property="unliked", type="boolean", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function likePost($id)
    {
        // Get the currently authenticated user
        $user = Auth::user();
    
        // Find the post by ID or return a JSON response if not found
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found.']);
        }
    
        // Authorize the user for the like action on the LikePost model
        $this->authorize('like', [LikePost::class, $post]);
    
        // Check if the like already exists
        $like = LikePost::where('user_id', $user->id)->where('post_id', $id)->first();
    
        if ($like) {
            // If the like exists, delete it (unlike the post)
            $like->delete();
    
            // Return a JSON response indicating the post was unliked
            return response()->json(['success' => true, 'unliked' => true]);
        } else {
            // If the like does not exist, create a new like (like the post)
            LikePost::create([
                'user_id' => $user->id,
                'post_id' => $id,
                'datetime' => now(),
            ]);
    
            // Check if a notification exists for the like
            $notification = LikePostNotification::where('user_id', $user->id)->where('post_id', $post->id)->first();
    
            // If a notification exists, trigger the NotificationEvent
            if ($notification) {
                event(new NotificationEvent($notification->notification->notification->content, $notification->notification->notification->user_id));
            }
    
            // Return a JSON response indicating the post was liked
            return response()->json(['success' => true, 'liked' => true]);
        }
    }



    /**
     * @OA\Get(
     *     path="/likes/{postId}",
     *     summary="Get likes by post ID",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved likes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/LikePost"))
     *     )
     * )
     */
    public function getLikesByPostId($postId)
    {
        // Get the number of likes that a given post has
        $likes = LikePost::where('post_id', $postId)->get();
        return response()->json($likes);
    }
}