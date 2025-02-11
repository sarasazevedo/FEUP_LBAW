<?php

namespace App\Policies;

use App\Models\User;

class ManageUsersPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function show(User $user)
    {
        // Check if user is admin
        return $user->is_admin;
    }

    public function index(User $user)
    {
        // Check if user is admin
        return $user->is_admin;
    }

    public function block(User $user)
    {
        // Check if user is admin
        return $user->is_admin;
    }

    public function delete(User $user)
    {
        // Check if user is admin
        return $user->is_admin;
    }
}
