<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\RequestFollow;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationEvent;


class RequestFollowController extends Controller
{
    public function showRequests()
    {
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Retrieve follow requests where the authenticated user is the receiver
        $followRequests = RequestFollow::where('receiver_client_id', $client->id)
            ->with('requester.userDetails')
            ->get();
    
        // Return the requests view with the follow requests data
        return view('pages.requests', compact('followRequests'));
    }
    
    public function acceptRequest($requesterId)
    {
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Find the follow request between the requester and the authenticated user
        $requestFollow = RequestFollow::where('requester_client_id', $requesterId)
            ->where('receiver_client_id', $client->id)
            ->first();
    
        if ($requestFollow) {
            // Add the requester to the followers of the authenticated user
            $client->followers()->attach($requesterId);
    
            // Remove the follow request from the request_follow table
            $requestFollow->delete();
    
            // Trigger a notification event for the accepted follow request
            event(new NotificationEvent(Auth::user()->name . ' accepted your follow request', $requesterId));
    
            // Return a JSON response indicating success
            return response()->json(['success' => true]);
        }
    
        // Return a JSON response indicating failure
        return response()->json(['success' => false], 400);
    }
    
    public function rejectRequest($requesterId)
    {
        // Get the currently authenticated user
        $client = Auth::user();
    
        // Find the follow request between the requester and the authenticated user
        $requestFollow = RequestFollow::where('requester_client_id', $requesterId)
            ->where('receiver_client_id', $client->id)
            ->first();
    
        if ($requestFollow) {
            // Remove the follow request from the request_follow table
            $requestFollow->delete();
    
            // Return a JSON response indicating success
            return response()->json(['success' => true]);
        }
    
        // Return a JSON response indicating failure
        return response()->json(['success' => false], 400);
    }
}