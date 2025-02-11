<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FollowsRestaurant extends Pivot
{
    protected $table = 'follows_restaurant';
    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'restaurant_id'
    ];
}