<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InformationalPost;
use App\Models\Restaurant;
use App\Models\ReviewPost;
use App\Models\Group;
use App\Models\Client;
use App\Models\Post;

class LikePostPolicy
{
    public function like(User $user, Post $post): bool
    {
        // Cast the user to the appropriate subclass
        $subclassUser = $user->castToSubclass();
    
        // Check if the user is a Restaurant
        if ($subclassUser instanceof \App\Models\Restaurant) {
            // Allow liking if the post is a ReviewPost and belongs to the restaurant, and is not part of a group
            if ($post instanceof ReviewPost) {
                return $post->restaurant_id === $subclassUser->id && is_null($post->group_id);
            }
            return true;
        } elseif ($subclassUser instanceof \App\Models\Client) {
            // Allow liking if the post is a ReviewPost and the client is a member of the group
            if ($post instanceof ReviewPost && !is_null($post->group_id)) {
                $group = \App\Models\Group::find($post->group_id);
                return $group && $group->members->contains($subclassUser->id);
            }
            return true;
        }
    
        // Deny liking for other cases
        return false;
    }
}
