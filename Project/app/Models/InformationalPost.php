<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformationalPost extends Post
{
    use HasFactory;

    protected $table = 'informational_post';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'pinned'
    ];


    public function postDetails()
    {
        return $this->belongsTo(Post::class, 'id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}