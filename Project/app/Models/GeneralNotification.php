<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralNotification extends Notification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'general_notification';

    protected $fillable = ['id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';


    public function notification() : BelongsTo
    {
        return $this->belongsTo(Notification::class, 'id');
    }

    public function adminNotification()
    {
        return $this->hasOne(AdminNotification::class, 'id');
    }

    public function groupNotification()
    {
        return $this->hasOne(GroupNotification::class, 'id');
    }

    public function startedFollowingClientNotification()
    {
        return $this->hasOne(StartedFollowingClientNotification::class, 'id');
    }

    public function startedFollowingRestaurantNotification()
    {
        return $this->hasOne(StartedFollowingRestaurantNotification::class, 'id');
    }

        public function castToSubClass()
    {
        $notification = $this->load([
            'adminNotification',
            'groupNotification',
            'startedFollowingClientNotification',
            'startedFollowingRestaurantNotification'
        ]);
    
    
        if ($notification->adminNotification) {
            return $notification->adminNotification;
        } elseif ($notification->groupNotification) {
            return $notification->groupNotification;
        } elseif ($notification->startedFollowingClientNotification) {
            return $notification->startedFollowingClientNotification;
        } elseif ($notification->startedFollowingRestaurantNotification) {
            return $notification->startedFollowingRestaurantNotification;
        }
    
        return $this;
    }
}