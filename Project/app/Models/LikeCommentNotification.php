<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class LikeCommentNotification extends LikeNotification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'like_comment_notification';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'user_id', 'comment_id'];

    public function userThatLiked() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment() : BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function notification() : BelongsTo
    {
        return $this->belongsTo(LikeNotification::class, 'id', 'id');
    }
}