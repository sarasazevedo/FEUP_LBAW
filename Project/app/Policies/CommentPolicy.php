<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use App\Models\ReviewPost;
use App\Models\InformationalPost;
use App\Models\Group;
use App\Models\Client;
use App\Models\Comment;

class CommentPolicy
{

    public function edit(User $user, Comment $comment): bool
    {
        return $user->is_admin || $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->is_admin || $user->id === $comment->user_id;
    }

    
}