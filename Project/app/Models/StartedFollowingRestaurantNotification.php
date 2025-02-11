<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartedFollowingRestaurantNotification extends GeneralNotification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'started_following_restaurant_notification';

    protected $fillable = ['id', 'sender_id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function notification() : BelongsTo
    {
        return $this->belongsTo(GeneralNotification::class, 'id');
    }
}