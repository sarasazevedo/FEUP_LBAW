<?php

namespace App\Http\Controllers;

use App\Models\LikeCommentNotification;
use Illuminate\Http\Request;
use App\Models\LikeComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\NotificationEvent;

class LikeCommentController extends Controller
{
    public function likeComment($id)
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::id();
        $commentId = $id;
    
        // Enable query logging for debugging purposes
        DB::enableQueryLog();
    
        try {
            // Check if the user has already liked the comment
            $like_comment = LikeComment::where('user_id', $userId)
                ->where('comment_id', $commentId)
                ->first();
    
            if ($like_comment) {
                // If the like exists, delete it (unlike the comment)
                DB::table('like_comment')
                    ->where('user_id', $userId)
                    ->where('comment_id', $commentId)
                    ->delete();
            } else {
                // If the like does not exist, insert a new like (like the comment)
                DB::table('like_comment')->insert([
                    'user_id' => $userId,
                    'comment_id' => $commentId,
                    'datetime' => now()
                ]);
    
                // Check if a notification exists for the like
                $like_comment_notification = LikeCommentNotification::where('user_id', $userId)->where('comment_id', $commentId)->first();
    
                // If a notification exists, trigger the NotificationEvent
                if ($like_comment_notification) {
                    event(new NotificationEvent($like_comment_notification->notification->notification->content, $like_comment_notification->notification->notification->user_id));
                }
            }
    
            // Calculate the like count for the comment
            $likeCount = LikeComment::where('comment_id', $commentId)->count();
    
            // Get the executed queries for debugging purposes
            $queries = DB::getQueryLog();
    
            // Return a JSON response with the success status and like count
            return response()->json([
                'success' => true,
                'liked' => !$like_comment,
                'like_count' => $likeCount
            ]);
        } catch (\Exception $e) {
            // Return a JSON response indicating an error occurred
            return response()->json(['success' => false, 'message' => 'An error occurred while liking/unliking the comment.'], 500);
        }
    }
}