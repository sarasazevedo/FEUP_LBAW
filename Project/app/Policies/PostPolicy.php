<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth; 

class PostPolicy
{
    public function comment(User $user, Post $post): bool
    {
        // Cast the user to the appropriate subclass
        $subclassUser = $user->castToSubclass();
    
        // Check if the user is a Restaurant
        if ($subclassUser instanceof \App\Models\Restaurant) {
            // Allow commenting if the post is a ReviewPost and belongs to the restaurant, and is not part of a group
            if ($post->castToSubclass() instanceof ReviewPost) {
                return $post->castToSubclass()->restaurant_id === $subclassUser->id && is_null($post->castToSubclass()->group_id);
            }
            return true;
        } elseif ($subclassUser instanceof \App\Models\Client) {
            // Allow commenting if the post is a ReviewPost and the client is a member of the group
            if ($post->castToSubclass() instanceof ReviewPost && !is_null($post->castToSubclass()->group_id)) {
                $group = \App\Models\Group::find($post->castToSubclass()->group_id);
                return $group && $group->members->contains($subclassUser->id);
            }
            return true;
        }
    
        // Deny commenting for other cases
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, Post $post): bool
    {
        // Allow access if the user is an admin
        if ($user->is_admin) return true;
    
        // Cast the post to the appropriate subclass
        $subclassPost = $post->castToSubclass();
    
        // Allow access if the post is an InformationalPost
        if ($subclassPost instanceof \App\Models\InformationalPost) {
            return true;
        } elseif ($subclassPost instanceof \App\Models\ReviewPost) {
            // Cast the user to the appropriate subclass
            $subclassUser = $user->castToSubclass();
    
            // Allow access if the user is a Restaurant and owns the post
            if ($subclassUser instanceof \App\Models\Restaurant) {
                return $subclassUser->id === $subclassPost->restaurant_id;
            } elseif ($subclassUser instanceof \App\Models\Client) {
                // Allow access if the user is the client who created the post
                if ($user->id === $subclassPost->client_id) return true;
    
                // Allow access if the post belongs to a group and the user is a member of the group
                if ($subclassPost->group_id) {
                    $group = \App\Models\Group::find($subclassPost->group_id);
                    if ($group && !$group->is_public) {
                        return $group->members->contains($subclassUser->id);
                    }
                }
    
                // Allow access if the user follows the client who created the post
                return $subclassUser->followed->contains($subclassPost->client_id);
            }
        }
    
        // Deny access for other cases
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
        public function create(User $user, ?int $groupId = null): bool
    {
        // Check if a group ID is provided
        if ($groupId) {
            // Find the group by ID
            $group = \App\Models\Group::find($groupId);
            if (!$group) {
                // Return false if the group does not exist
                return false;
            }
            // Check if the user is a member of the group
            return $group->members->contains($user->castToSubclass()->id);
        }
        // Allow creation if the user is the authenticated user
        return $user->id === Auth::user()->id;
    }

    /**
     * Determine whether the user can update the model.
     */
        public function update(User $user, Post $post): bool
    {
        // Allow update if the user is an admin
        if($user->is_admin) {
            return true;
        }
    
        // Cast the post to the appropriate subclass
        $subclass = $post->castToSubclass();
    
        // Allow update if the post is a ReviewPost and the user is the client who created it
        if ($subclass instanceof \App\Models\ReviewPost) {
            return $subclass->client_id === $user->id;
        } elseif ($subclass instanceof \App\Models\InformationalPost) {
            // Allow update if the post is an InformationalPost and the user is the restaurant who created it
            return $subclass->restaurant_id === $user->id;
        }
    
        // Deny update for other cases
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
        public function delete(User $user, Post $post): bool
    {
        // Allow delete if the user is an admin
        if($user->is_admin) {
            return true;
        }
    
        // Cast the post to the appropriate subclass
        $subclass = $post->castToSubclass();
    
        // Allow delete if the post is a ReviewPost and the user is the client who created it
        if ($subclass instanceof \App\Models\ReviewPost) {
            return $subclass->client_id === $user->id;
        } elseif ($subclass instanceof \App\Models\InformationalPost) {
            // Allow delete if the post is an InformationalPost and the user is the restaurant who created it
            return $subclass->restaurant_id === $user->id;
        }
    
        // Deny delete for other cases
        return false;
    }

    /**
    * Determine whether the user can pin the post.
    */
        public function pinOrUnpin(User $user, Post $post): bool
    {
        // Allow pin or unpin if the user is an admin
        if ($user->is_admin) {
            return true;
        }
    
        // Cast the post to the appropriate subclass
        $subclassPost = $post->castToSubclass();
    
        // Allow pin or unpin if the post is an InformationalPost and the user is the restaurant who created it
        if ($subclassPost instanceof \App\Models\InformationalPost) {
            return $subclassPost->restaurant_id === $user->id;
        } elseif ($subclassPost instanceof \App\Models\ReviewPost) {
            // Allow pin or unpin if the post is a ReviewPost and the user is the client who created it
            return $subclassPost->client_id === $user->id;
        }
    
        // Deny pin or unpin for other cases
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        //
    }
}
