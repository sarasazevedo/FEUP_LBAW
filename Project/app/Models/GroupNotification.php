<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupNotification extends GeneralNotification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'group_notification';

    protected $fillable = ['id', 'group_id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function notification() : BelongsTo
    {
        return $this->belongsTo(GeneralNotification::class, 'id');
    }
}