<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FollowsClient extends Pivot
{
    protected $table = 'follows_client';
    public $timestamps = false;

    protected $fillable = [
        'sender_client_id',
        'followed_client_id'
    ];
}