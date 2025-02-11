<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupInvitation extends GeneralNotification
{
    use HasFactory;

    protected $table = "group_invitations";

    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'group_id',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}