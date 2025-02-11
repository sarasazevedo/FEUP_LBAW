<?php

namespace App\Policies;

use App\Models\User;

class NotificationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user)
    {
        // Check if it is logged
        return $user !== null;
    }
}
