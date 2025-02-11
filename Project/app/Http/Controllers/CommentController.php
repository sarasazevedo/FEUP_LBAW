<?php

namespace App\Http\Controllers;

use App\Models\CommentNotification;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationEvent;

class CommentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/post/{id}/commen t",
     *     summary="Add a comment to a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="comment", ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function addComment(Request $request, $postId)
    {
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post = Post::findOrFail($postId);
        
        // Authorize the user for the comment action on the Post model
        $this->authorize('comment', $post);

        // Create a new comment instance
        $comment = new Comment();
        $comment->content = strip_tags($request->input('content')); // Sanitize the content
        $comment->post_id = $postId; // Set the post ID
        $comment->user_id = Auth::id(); // Set the user ID to the currently authenticated user
        $comment->datetime = now(); // Set the current datetime
        $comment->save(); // Save the comment to the database

        // Check if a notification exists for the comment
        $notification = CommentNotification::where('comment_id', $comment->id)->first();

        // If a notification exists, trigger the NotificationEvent
        if ($notification) {
            event(new NotificationEvent($notification->notification->content, $notification->notification->user_id));
        }

        // Return a JSON response with the success status and the comment data
        return response()->json([
            'success' => true,
            'comment' => $comment->load('user')
        ]);
    }

    /**
     * @OA\Get(
     *     path="/post/{id}/comments",
     *     summary="Load comments for a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=0)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comments loaded successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Comment"))
     *     )
     * )
     */
    public function loadComments(Request $request, $postId)
    {
        // Get the offset and limit from the request, with default values
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $userId = Auth::id(); // Get the ID of the currently authenticated user
    
        // Retrieve comments for the specified post ID with pagination
        $comments = Comment::where('post_id', $postId)
            ->with('user') // Eager load the user relationship
            ->orderBy('datetime', 'asc') // Order comments by datetime in ascending order
            ->skip($offset) // Skip the specified number of comments
            ->take($limit) // Take the specified number of comments
            ->get() // Get the comments
            ->map(function ($comment) use ($userId) {
                // Add additional data to each comment
                $comment->likes_count = $comment->likes()->count(); // Count the number of likes
                $comment->liked_by_user = $comment->likes()->where('user_id', $userId)->exists(); // Check if the comment is liked by the current user
                $comment->is_owner = $comment->user_id === $userId; // Check if the current user is the owner of the comment
                $comment->is_admin = Auth::user()->is_admin; // Check if the current user is an admin
                $comment->datetime; // Ensure the datetime attribute is included
                return $comment; // Return the modified comment
            });
    
        // Return the comments as a JSON response
        return response()->json($comments);
    }
    /**
     * Edit a comment.
     *
     * @OA\Post(
     *     path="/comment/{id}/edit",
     *     summary="Edit a comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment edited successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="comment", ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function editComment(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:255',
        ]);
    
        // Find the comment by ID or fail if not found
        $comment = Comment::findOrFail($id);

        // Authorize the user for the edit action on the Comment model
        $this->authorize('edit', $comment);
    
        // Ensure the authenticated user is the owner of the comment
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403); // Return a 403 Forbidden response if the user is not authorized
        }
    
        // Update the comment content and sanitize it
        $comment->content = strip_tags($request->input('content'));
        $comment->datetime; // Ensure the datetime attribute is included
        $comment->save(); // Save the updated comment to the database
    
        // Return a JSON response with the success status and the updated comment data
        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    /**
     * Delete a comment.
     *
     * @OA\Delete(
     *     path="/comment/{id}/delete",
     *     summary="Delete a comment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean")
     *         )
     *     )
     * )
     */
    public function deleteComment(Request $request, $id)
    {
        // Find the comment by ID or fail if not found
        $comment = Comment::findOrFail($id);

        // Authorize the user for the delete action on the Comment model
        $this->authorize('delete', $comment);
    
        // Ensure the authenticated user is the owner of the comment
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403); // Return a 403 Forbidden response if the user is not authorized
        }
    
        // Delete the comment from the database
        $comment->delete();
    
        // Return a JSON response with the success status and a message
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }

    public function getCommentsCount($postId)
    {
        // Count the number of comments for the specified post ID
        $count = Comment::where('post_id', $postId)->count();

        // Return the count as a JSON response
        return response()->json(['count' => $count]);
    }
}