<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppealUnblockNotification extends Notification
{

    use HasFactory;

    public $timestamps = false;
    protected $table = 'appeal_unblock_notification';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'user_blocked_id'];

    public function userBlocked()
    {
        return $this->belongsTo(User::class, 'user_blocked_id');
    }
    
    public function notification() : BelongsTo
    {
        return $this->belongsTo(Notification::class, 'id');
    }
}