<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends GeneralNotification
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'admin_notification';

    
    protected $fillable = ['id'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public function notification() : BelongsTo
    {
        return $this->belongsTo(GeneralNotification::class, 'id');
    }
}