<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentNotification extends Notification
{

    use HasFactory;

    public $timestamps = false;
    protected $table = 'comment_notification';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'comment_id'];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
    
    public function notification() : BelongsTo
    {
        return $this->belongsTo(Notification::class, 'id');
    }
}