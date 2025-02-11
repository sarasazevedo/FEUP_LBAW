<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    public $timestamps = false;

    protected $fillable = ['content', 'viewed', 'user_id', 'datetime'];

    protected $casts = [
        'datetime' => 'datetime'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function generalNotification()
    {
        return $this->hasOne(GeneralNotification::class, 'id', 'id'); // Ensure the local key and foreign key are set
    }

    public function requestNotification()
    {
        return $this->hasOne(RequestNotification::class, 'id', 'id');
    }

    public function likeNotification()
    {
        return $this->hasOne(LikeNotification::class, 'id', 'id');
    }

    public function commentNotification()
    {
        return $this->hasOne(CommentNotification::class, 'id', 'id');
    }

    public function appealUnblockNotification()
    {
        return $this->hasOne(AppealUnblockNotification::class, 'id', 'id');
    }


    public function castToSubClass()
    {

        $notification = $this->refresh()->load([
        'generalNotification',
        'likeNotification',
        'commentNotification',
        'requestNotification',
        'appealUnblockNotification'
    ]);

        if ($notification->generalNotification) {
            return $notification->generalNotification->castToSubClass();
        } 
        if ($notification->requestNotification) {
            return $notification->requestNotification->castToSubClass();
        } 
        if ($notification->likeNotification) {
            return $notification->likeNotification->castToSubClass();
        } 
        if ($notification->commentNotification) {
            return $notification->commentNotification;
        }
         if ($notification->appealUnblockNotification) {
            return $notification->appealUnblockNotification;
        }


        return $this;
    }
}