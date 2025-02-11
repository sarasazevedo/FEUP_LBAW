<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowNotification extends RequestNotification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'follow_notification';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'sender_client_id', 'receiver_client_id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'sender_client_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'receiver_client_id');
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(RequestNotification::class, 'id', 'id');
    }
}