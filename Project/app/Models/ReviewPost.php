<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewPost extends Post
{
    use HasFactory;

    protected $table = 'review_post';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'rating',
        'client_id',
        'restaurant_id',
        'group_id'
    ];

    public function postDetails() : BelongsTo
    {
        return $this->belongsTo(Post::class, 'id');
    }

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function restaurant() : BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}