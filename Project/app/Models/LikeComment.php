<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeComment extends Model
{
    use HasFactory;

    protected $table = 'like_comment';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'comment_id',
        'datetime',
    ];

    // Specify the primary key
    protected $primaryKey = null;

    // Get the primary key name
    public function getKeyName()
    {
        return ['user_id', 'comment_id'];
    }
}