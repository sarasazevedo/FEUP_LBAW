<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\JoinRequest;
use App\Models\GroupInvitation;
use App\Models\ReviewPost;

use App\Events\NotificationEvent;
use App\Models\Notification;
use App\Models\GeneralNotification;
use App\Models\GroupNotification;


class GroupController extends Controller
{
    public function index()
    {
        // Authorize the user for the index action on the Group model
        $this->authorize('index', Group::class);
    
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Cast the user to the appropriate subclass (Client or Restaurant)
        $client = $user->castToSubclass();
    
        // Check if the user is a Client
        if ($client instanceof Client) {
            // Fetch groups created by the client
            $createdGroups = Group::where('owner_id', $client->id)->get();
            // Fetch groups joined by the client through the Client model
            $joinedGroups = $client->groups;
            // Fetch all public groups
            $publicGroups = Group::where('is_public', true)->get();
            // Fetch all private groups
            $privateGroups = Group::where('is_public', false)->get();
    
            // Return the groups view with the fetched groups data
            return view('pages.groups', compact('createdGroups', 'joinedGroups', 'publicGroups', 'privateGroups'));
        }
    
        // Redirect to the home page with an error message if the user is not a Client
        return redirect()->route('home')->with('error', 'Unable to load groups.');
    }
    
    public function store(Request $request)
    {
        // Authorize the user for the store action on the Group model
        $this->authorize('store', Group::class);
    
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'is_public' => 'required|boolean',
        ]);
    
        // Create a new group instance
        $group = new Group();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->is_public = $request->is_public;
        $group->owner_id = Auth::id(); // Set the owner ID to the currently authenticated user
        $group->save(); // Save the group to the database
    
        // Return a JSON response with the success status and the created group data
        return response()->json(['success' => true, 'message' => 'Group created successfully.', 'group' => $group]);
    }

    public function show($id, Request $request)
    {
        // Authorize the user for the show action on the Group model
        $this->authorize('show', Group::class);
    
        // Retrieve the group with its members, owner details, and join requests
        $group = Group::with('members', 'owner.userDetails', 'joinRequests.client.userDetails')->findOrFail($id);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $user = Auth::user()->castToSubclass();
        $hasRequestedToJoin = false;
    
        // Set pagination parameters
        $perPage = 10;
        $offset = $request->query('offset', 0);
    
        // Retrieve the posts for the group with pagination
        $postsQuery = ReviewPost::select('review_post.*')
            ->leftJoin('post', 'post.id', '=', 'review_post.id')
            ->where('review_post.group_id', $id)
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
                'posts' => $postHtml,
            ]);
        }
    
        // Check if the user is a Client and has requested to join the group
        if ($user instanceof Client) {
            $hasRequestedToJoin = $group->joinRequests()->where('client_id', $user->id)->exists();
        }
    
        // Get the IDs of the group members
        $members = $group->members()->pluck('id')->toArray();
        // Retrieve all clients who are not members of the group
        $allUsers = Client::whereNotIn('id', $members)->paginate(5);
    
        // Return the group view with the fetched data
        return view('pages.group', [
            'group' => $group,
            'members' => $group->members()->with('userDetails')->paginate(5),
            'allUsers' => $allUsers,
            'hasRequestedToJoin' => $hasRequestedToJoin,
            'posts' => $posts,
            'offset' => $offset + $perPage
        ]);
    }
    
    public function getMembers($id, Request $request)
    {
        // Authorize the user for the getMembers action on the Group model
        $this->authorize('getMembers', Group::class);
    
        // Retrieve the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Retrieve the members of the group with their user details, paginated
        $members = $group->members()->with('userDetails')->paginate(5, ['*'], 'page', $request->page);
    
        // Return the members as a JSON response
        return response()->json([
            'members' => $members->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'userDetails' => $member->userDetails ? [
                        'name' => $member->userDetails->name,
                        'email' => $member->userDetails->email,
                    ] : null,
                ];
            }),
            'currentPage' => $members->currentPage(),
            'nextPageUrl' => $members->nextPageUrl(),
            'previousPageUrl' => $members->previousPageUrl(),
        ]);
    }

    public function joinGroup($id)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Authorize the user for the joinGroup action on the Group model
        $this->authorize('joinGroup', $group);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
        
        // Check if the user is a Client
        if ($client instanceof Client) {
            // Check if the client is not already a member of the group
            if (!$group->members->contains($client->id)) {
                // Attach the client to the group's members
                $group->members()->attach($client->id);
    
                // Prepare the new member data
                $newMember = [
                    'id' => $client->id,
                    'name' => $client->userDetails->name,
                    'email' => $client->userDetails->email,
                ];
    
                // Return a JSON response indicating success
                return response()->json(['success' => true, 'message' => 'Joined group successfully.', 'newMember' => $newMember, 'isOwner' => $group->owner_id == Auth::id()]);
            } else {
                // Return a JSON response indicating the client is already a member
                return response()->json(['success' => false, 'message' => 'You are already a member of this group.']);
            }
        } else {
            // Return a JSON response indicating only clients can join groups
            return response()->json(['success' => false, 'message' => 'Only clients can join groups.']);
        }
    }
    
    public function leaveGroup($id)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Authorize the user for the leaveGroup action on the Group model
        $this->authorize('leaveGroup', $group);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user is a Client
        if ($client instanceof Client) {
            // Check if the client is a member of the group
            if ($group->members->contains($client->id)) {
                // Detach the client from the group's members
                $group->members()->detach($client->id);
    
                // Return a JSON response indicating success
                return response()->json([
                    'success' => true,
                    'message' => 'Left group successfully.',
                    'memberId' => $client->id,
                ]);
            } else {
                // Return a JSON response indicating the client is not a member
                return response()->json(['success' => false, 'message' => 'You are not a member of this group.']);
            }
        }
    
        // Return a JSON response indicating failure to leave the group
        return response()->json(['success' => false, 'message' => 'Unable to leave group.'], 400);
    }

    public function requestJoinGroup($id)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Authorize the user for the requestJoinGroup action on the Group model
        $this->authorize('requestJoinGroup', $group);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user is a Client
        if ($client instanceof Client) {
            // Check if the client is not already a member of the group
            if (!$group->members->contains($client->id)) {
                // Create a new join request
                $joinRequest = new JoinRequest();
                $joinRequest->client_id = $client->id;
                $joinRequest->group_id = $group->id;
                $joinRequest->save();
    
                // Trigger a notification event for the group owner
                event(new NotificationEvent($client->userDetails->name . ' requested to join group ' . $group->name, $group->owner_id));
    
                // Return a JSON response indicating success
                return response()->json(['success' => true, 'message' => 'Request to join group sent successfully.']);
            } else {
                // Return a JSON response indicating the client is already a member
                return response()->json(['success' => false, 'message' => 'You are already a member of this group.']);
            }
        }
    
        // Return a JSON response indicating failure to request to join the group
        return response()->json(['success' => false, 'message' => 'Unable to request to join group.'], 400);
    }
    
    public function cancelJoinRequest($id)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user is a Client
        if ($client instanceof Client) {
            // Find the join request for the client and group
            $joinRequest = JoinRequest::where('client_id', $client->id)
                ->where('group_id', $group->id)
                ->first();
    
            // Check if the join request exists
            if ($joinRequest) {
                // Delete the join request
                $joinRequest->delete();
    
                // Return a JSON response indicating success
                return response()->json(['success' => true, 'message' => 'Join request canceled successfully.']);
            }
    
            // Return a JSON response indicating the join request was not found
            return response()->json(['success' => false, 'message' => 'Join request not found.'], 404);
        }
    
        // Return a JSON response indicating unauthorized access
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    public function acceptJoinRequest($id, $clientId)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Find the client by ID or fail if not found
        $client = Client::findOrFail($clientId);
    
        // Authorize the user for the acceptJoinRequest action on the Group model
        $this->authorize('acceptJoinRequest', [$group, $client]);
    
        // Check if the currently authenticated user is the owner of the group
        if ($group->owner_id == Auth::id()) {
            // Attach the client to the group's members
            $group->members()->attach($client->id);
    
            // Delete the join request
            JoinRequest::where('client_id', $client->id)->where('group_id', $group->id)->delete();
    
            // Trigger a notification event for the client
            event(new NotificationEvent($group->owner->userDetails->name . ' accepted your join request to group ' . $group->name, $clientId));
    
            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'message' => 'Join request accepted successfully.',
                'member' => [
                    'name' => $client->userDetails->name,
                    'email' => $client->userDetails->email,
                ],
            ]);
        }
    
        // Return a JSON response indicating failure to accept the join request
        return response()->json(['success' => false, 'message' => 'Unable to accept join request.'], 400);
    }
    
    public function declineJoinRequest($id, $clientId)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Find the client by ID or fail if not found
        $client = Client::findOrFail($clientId);
    
        // Authorize the user for the declineJoinRequest action on the Group model
        $this->authorize('declineJoinRequest', [$group, $client]);
    
        // Check if the currently authenticated user is the owner of the group
        if ($group->owner_id == Auth::id()) {
            // Delete the join request
            JoinRequest::where('client_id', $client->id)->where('group_id', $group->id)->delete();
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Join request declined successfully.']);
        }
    
        // Return a JSON response indicating failure to decline the join request
        return response()->json(['success' => false, 'message' => 'Unable to decline join request.'], 400);
    }
    
    public function getPaginatedUsers(Request $request, $groupId)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($groupId);
    
        // Authorize the user for the getPaginatedUsers action on the Group model
        $this->authorize('getPaginatedUsers', $group);
    
        // Get the current page from the request, default to 1
        $page = $request->input('page', 1);
    
        // Get the IDs of the group members
        $members = $group->members()->pluck('id')->toArray();
    
        // Retrieve all clients who are not members of the group, with their user details, paginated
        $users = Client::whereNotIn('id', $members)->with('userDetails')->paginate(5, ['*'], 'page', $page);
    
        // Map the users to include their invitation status
        $usersWithInvitationStatus = $users->map(function ($user) use ($group) {
            $invitation = GroupInvitation::where('group_id', $group->id)
                ->where('client_id', $user->id)
                ->where('status', 'pending')
                ->first();
    
            return [
                'id' => $user->id,
                'name' => $user->name,
                'userDetails' => $user->userDetails ? [
                    'name' => $user->userDetails->name,
                    'email' => $user->userDetails->email,
                ] : null,
                'invitationStatus' => $invitation ? 'pending' : 'none',
            ];
        });
    
        // Return the users with invitation status as a JSON response
        return response()->json([
            'users' => $usersWithInvitationStatus,
            'currentPage' => $users->currentPage(),
            'nextPageUrl' => $users->nextPageUrl(),
            'previousPageUrl' => $users->previousPageUrl(),
        ]);
    }

    public function inviteUser($groupId, $userId)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($groupId);
    
        // Find the client by ID or fail if not found
        $client = Client::findOrFail($userId);
    
        // Authorize the user for the inviteUser action on the Group model
        $this->authorize('inviteUser', [$group, $client]);
    
        // Check if the currently authenticated user is the owner of the group and the client is not already a member
        if ($group->owner_id == Auth::id() && !$group->members->contains($client->id)) {
            // Create a new group invitation
            $invitation = new GroupInvitation();
            $invitation->client_id = $client->id;
            $invitation->group_id = $group->id;
            $invitation->status = 'pending';
            $invitation->save();
    
            // Trigger a notification event for the client
            event(new NotificationEvent($group->owner->userDetails->name . ' invited you to join group ' . $group->name, $userId));
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Invitation sent successfully.']);
        }
    
        // Return a JSON response indicating failure to send the invitation
        return response()->json(['success' => false, 'message' => 'Unable to send invitation.'], 400);
    }
    
    public function cancelInviteUser($groupId, $userId)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($groupId);
    
        // Find the client by ID or fail if not found
        $client = Client::findOrFail($userId);
    
        // Authorize the user for the cancelInviteUser action on the Group model
        $this->authorize('cancelInviteUser', [$group, $client]);
    
        // Check if the currently authenticated user is the owner of the group
        if ($group->owner_id == Auth::id()) {
            // Find the pending invitation for the client and group
            $invitation = GroupInvitation::where('group_id', $groupId)
                ->where('client_id', $userId)
                ->where('status', 'pending')
                ->first();
    
            // Check if the invitation exists
            if ($invitation) {
                // Delete the invitation
                $invitation->delete();
    
                // Return a JSON response indicating success
                return response()->json(['success' => true, 'message' => 'Invitation canceled successfully.']);
            }
    
            // Return a JSON response indicating the invitation was not found
            return response()->json(['success' => false, 'message' => 'Invitation not found.'], 404);
        }
    
        // Return a JSON response indicating unauthorized access
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }
    
    public function acceptInvitation($invitationId)
    {
        // Find the invitation by ID or fail if not found
        $invitation = GroupInvitation::findOrFail($invitationId);
    
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($invitation->group_id);
    
        // Authorize the user for the acceptInvitation action on the Group model
        $this->authorize('acceptInvitation', $group);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user is a Client and the invitation is for the authenticated client
        if ($client instanceof Client && $client->id == $invitation->client_id) {
            // Attach the client to the group's members
            $group->members()->attach($client->id);
    
            // Update the invitation status to accepted
            $invitation->status = 'accepted';
            $invitation->save();
    
            // Trigger a notification event for the group owner
            event(new NotificationEvent($client->userDetails->name . ' accepted your invitation to join group ' . $group->name, $group->owner_id));
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Invitation accepted successfully.']);
        }
    
        // Return a JSON response indicating failure to accept the invitation
        return response()->json(['success' => false, 'message' => 'Unable to accept invitation.'], 400);
    }

    public function rejectInvitation($invitationId)
    {
        // Find the invitation by ID or fail if not found
        $invitation = GroupInvitation::findOrFail($invitationId);
    
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($invitation->group_id);
    
        // Authorize the user for the rejectInvitation action on the Group model
        $this->authorize('rejectInvitation', $group);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user is a Client and the invitation is for the authenticated client
        if ($client instanceof Client && $client->id == $invitation->client_id) {
            // Update the invitation status to rejected
            $invitation->status = 'rejected';
            $invitation->save();
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Invitation rejected successfully.']);
        }
    
        // Return a JSON response indicating failure to reject the invitation
        return response()->json(['success' => false, 'message' => 'Unable to reject invitation.'], 400);
    }
    
    public function getInvitations()
    {
        // Authorize the user for the getInvitations action on the Group model
        $this->authorize('getInvitations', Group::class);
    
        // Get the currently authenticated user and cast to the appropriate subclass
        $client = Auth::user()->castToSubclass();
    
        // Check if the user is a Client
        if ($client instanceof Client) {
            // Retrieve pending invitations for the client
            $invitations = GroupInvitation::with('group')
                ->where('client_id', $client->id)
                ->where('status', 'pending')
                ->get();
    
            // Return a JSON response with the invitations
            return response()->json(['success' => true, 'invitations' => $invitations]);
        }
    
        // Return a JSON response indicating failure to load invitations
        return response()->json(['success' => false, 'message' => 'Unable to load invitations.'], 400);
    }
    
    public function updateDescription(Request $request, $id)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($id);
    
        // Authorize the user for the updateDescription action on the Group model
        $this->authorize('updateDescription', $group);
        
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);
    
        // Check if the currently authenticated user is the owner of the group
        if ($group->owner_id == Auth::id()) {
            // Update the group's name and description
            $group->name = $request->name;
            $group->description = $request->description;
            $group->save();
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Group updated successfully.']);
        }
    
        // Return a JSON response indicating unauthorized access
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }
    
    public function removeMember(Request $request, $groupId, $memberId)
    {
        // Find the group by ID or fail if not found
        $group = Group::findOrFail($groupId);
    
        // Find the client by ID or fail if not found
        $client = Client::findOrFail($memberId);
    
        // Authorize the user for the removeMember action on the Group model
        $this->authorize('removeMember', [$group, $client]);
    
        // Check if the currently authenticated user is the owner of the group
        if ($group->owner_id != Auth::id()) {
            // Return a JSON response indicating unauthorized access
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
    
        // Detach the client from the group's members
        $group->members()->detach($memberId);
    
        // Create a notification for the removed member
        $notification = Notification::create([
            'user_id' => $memberId,
            'content' => 'You have been removed from the group ' . $group->name,
            'viewed' => false,
        ]);
    
        // Create a general notification
        $generalNotification = GeneralNotification::create([
            'id' => $notification->id,
        ]);
    
        // Create a group notification
        $groupNotification = GroupNotification::create([
            'id' => $notification->id,
            'group_id' => $group->id,
        ]);
    
        // Trigger a notification event for the removed member
        event(new NotificationEvent('You have been removed from the group ' . $group->name, $memberId));
    
        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Member removed successfully.']);
    }
}