<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestNotification extends Notification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'request_notification';

    protected $fillable = ['id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function notification() : BelongsTo
    {
        return $this->belongsTo(Notification::class, 'id');
    }

    public function joinGroupNotification()
    {
        return $this->hasOne(JoinGroupNotification::class, 'id', 'id');
    }

    public function followNotification()
    {
        return $this->hasOne(FollowNotification::class, 'id', 'id');
    }


        public function castToSubClass()
    {
    
        $notification = $this->refresh()->load([
            'followNotification',
            'joinGroupNotification',
        ]);
    
    
        if ($notification->followNotification) {
            return $notification->followNotification;
        } elseif ($notification->joinGroupNotification) {
            return $notification->joinGroupNotification;
        }
    
        return $this;
    }
}
