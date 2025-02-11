<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use App\Models\JoinRequest;
use App\Models\Group;
use App\Models\GroupInvitation;

class GroupsPolicy
{
        public function index(User $user): bool
    {
        // Check if the user is a Client
        $subclassUser = $user->castToSubclass();
        return $subclassUser instanceof Client;
    }
    
    public function store(User $user): bool
    {
        // Check if the user is a Client
        return $user->castToSubclass() instanceof Client;
    }
    
    public function show(User $user): bool
    {
        // Check if the user is a Client
        return $user->castToSubclass() instanceof Client;
    }
    
    public function getMembers(User $user): bool
    {
        // Check if the user is a Client
        return $user->castToSubclass() instanceof Client;
    }
    
    public function joinGroup(User $user, Group $group): bool
    {
        // Check if the user is a Client and not already a member of the group
        $client = $user->castToSubclass();
        return $client instanceof Client && !$group->members->contains($client->id);
    }
    
    public function leaveGroup(User $user, Group $group): bool
    {
        // Check if the user is a Client and a member of the group
        $client = $user->castToSubclass();
        return $client instanceof Client && $group->members->contains($client->id);
    }
    
    public function requestJoinGroup(User $user, Group $group): bool
    {
        // Check if the user is a Client, not already a member of the group, and the group is not public
        $client = $user->castToSubclass();
        return $client instanceof Client && !$group->members->contains($client->id) && !$group->is_public;
    }
    
    public function acceptJoinRequest(User $user, Group $group, Client $client): bool
    {
        // Check if the user is the owner of the group and the join request exists
        return $group->owner_id == $user->id && JoinRequest::where('client_id', $client->id)->where('group_id', $group->id)->exists();
    }
    
    public function declineJoinRequest(User $user, Group $group, Client $client): bool
    {
        // Check if the user is the owner of the group and the join request exists
        return $group->owner_id == $user->id && JoinRequest::where('client_id', $client->id)->where('group_id', $group->id)->exists();
    }
    
    public function getPaginatedUsers(User $user, Group $group): bool
    {
        // Check if the user is the owner of the group
        return $group->owner_id == $user->id;
    }
    
    public function inviteUser(User $user, Group $group, Client $client): bool
    {
        // Check if the user is the owner of the group and the client is not already a member
        return $group->owner_id == $user->id && !$group->members->contains($client->id);
    }
    
    public function cancelInviteUser(User $user, Group $group, Client $client): bool
    {
        // Check if the user is the owner of the group and the invitation exists
        return $group->owner_id == $user->id && GroupInvitation::where('client_id', $client->id)->where('group_id', $group->id)->exists();
    }
    
    public function acceptInvitation(User $user, Group $group): bool
    {
        // Check if the user is a Client and the invitation exists
        $client = $user->castToSubclass();
        return $client instanceof Client && GroupInvitation::where('client_id', $client->id)->where('group_id', $group->id)->exists();
    }
    
    public function rejectInvitation(User $user, Group $group): bool
    {
        // Check if the user is a Client and the invitation exists
        $client = $user->castToSubclass();
        return $client instanceof Client && GroupInvitation::where('client_id', $client->id)->where('group_id', $group->id)->exists();
    }
    
    public function getInvitations(User $user): bool
    {
        // Check if the user is a Client
        return $user->castToSubclass() instanceof Client;
    }
    
    public function updateDescription(User $user, Group $group): bool
    {
        // Check if the user is the owner of the group
        return $group->owner_id == $user->id;
    }
    
    public function removeMember(User $user, Group $group, Client $client): bool
    {
        // Check if the user is the owner of the group and the client is a member
        return $group->owner_id == $user->id && $group->members->contains($client->id);
    }
}
