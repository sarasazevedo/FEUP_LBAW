<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JoinGroupNotification extends RequestNotification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'join_group_notification';

    protected $fillable = ['id', 'client_id', 'group_id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

        public function notification() : BelongsTo
    {
        return $this->belongsTo(RequestNotification::class, 'id');
    }
}