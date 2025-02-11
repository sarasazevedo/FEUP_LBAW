<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\AppealUnblockNotification;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();
    
        // Retrieve the user's unread notifications, ordered by datetime in descending order
        $notifications = $user->notifications()
                              ->where('viewed', false)
                              ->orderBy('datetime', 'desc')
                              ->get();
    
        // Return the notifications view with the notifications data
        return view('pages.notifications', compact('notifications'));
    }
    
    public function markAsViewed($id)
    {
        // Find the notification by ID or fail if not found
        $notification = Notification::findOrFail($id);
    
        // Mark the notification as viewed
        $notification->viewed = true;
        $notification->save();
    
        // Return a JSON response indicating success
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount($userId)
    {
        // Count the number of unread notifications for the specified user ID
        $count = Notification::where('user_id', $userId)
                            ->where('viewed', false)
                            ->count();
    
    
        // Return the unread count as a JSON response
        return response()->json(['count' => $count]);
    }
    
    public function appealUnblock(Request $request)
    {
        // Validate the request data
        $request->validate([
            'message' => 'required|string|max:50',
        ]);
    
        // Get the blocked user ID from the session
        $userBlockedId = $request->session()->get('blocked_user_id');
    
        // Check if the blocked user ID is present in the session
        if (!$userBlockedId) {
            return response()->json(['success' => false, 'message' => 'No blocked user ID found in session.'], 400);
        }
    
        // Get the message from the request
        $message = $request->input('message');
    
        // Find the user by the blocked user ID
        $user = User::find($userBlockedId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No User found with that id'], 400);
        }
    
        // Check if the user is blocked
        if (!$user->is_blocked) {
            return response()->json(['success' => false, 'message' => 'The user is not blocked'], 400);
        }
    
        // Retrieve all admin users
        $admins = User::where('is_admin', true)->get();
    
        // Create a notification for each admin
        foreach ($admins as $admin) {
            $notification = Notification::create([
                'user_id' => $admin->id,
                'content' => $message,
                'viewed' => false,
            ]);
    
            // Create an appeal unblock notification
            AppealUnblockNotification::create([
                'id' => $notification->id,
                'user_blocked_id' => $userBlockedId,
            ]);
        }
    
        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Appeal unblock request sent successfully.']);
    }
}