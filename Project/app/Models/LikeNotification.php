<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LikeNotification extends Notification
{
    use HasFactory;

    protected $table = 'like_notification';
    public $timestamps = false;

    protected $fillable = ['id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'id');
    }

    public function likePostNotification()
    {
        return $this->hasOne(LikePostNotification::class, 'id');
    }

    public function likeCommentNotification()
    {
        return $this->hasOne(LikeCommentNotification::class, 'id');
    }

        public function castToSubClass()
    {
        
    
        $notification = $this->load([
            'likePostNotification',
            'likeCommentNotification',
        ]);

    
        if ($notification->likePostNotification) {
            
            return $notification->likePostNotification;
        } elseif ($notification->likeCommentNotification) {
            
            return $notification->likeCommentNotification;
        }
    

        return $this;
    }
}
