<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LikePostNotification extends LikeNotification
{
    use HasFactory;

    protected $table = 'like_post_notification';
    public $timestamps = false;

    protected $fillable = ['id', 'user_id', 'post_id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function notification(): BelongsTo
    {
        return $this->belongsTo(LikeNotification::class, 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function userThatLiked()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
