<?php

namespace App\Providers;
use App\Models\Notification;
use App\Policies\NotificationPolicy;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Group;
use App\Policies\GroupsPolicy;
use App\Policies\ManageUsersPolicy;
use App\Models\User;
use App\Policies\GroupPolicy;
use App\Models\LikePost;
use App\Policies\LikePostPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        User::class => ManageUsersPolicy::class,
        Notification::class => NotificationPolicy::class,
        Group::class => GroupsPolicy::class,
        LikePost::class => LikePostPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
