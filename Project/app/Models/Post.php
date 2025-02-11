<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';
    public $timestamps  = false;
  
    protected $fillable = [
      'content', 
      'images'
    ];

    protected $casts = [
        'images' => 'array',
        'datetime' => 'datetime'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(LikePost::class, 'post_id');
    }
   
    public function isLikedByUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }


    public function reviewPost()
    {
        return $this->hasOne(ReviewPost::class, 'id');
    }

    public function informationalPost()
    {
        return $this->hasOne(InformationalPost::class, 'id');
    }

    public function castToSubclass()
    {
        if (ReviewPost::find($this->id)) {
            return ReviewPost::find($this->id);
        } elseif (InformationalPost::find($this->id)) {
            return InformationalPost::find($this->id);
        }
        return $this;
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function author()
    {
        return $this->belongsTo(Client::class, 'author_id');
    }

    public function owner()
    {
        $subclass = $this->castToSubclass();
        if ($subclass instanceof ReviewPost) {
            return $subclass->client;
        } elseif ($subclass instanceof InformationalPost) {
            return $subclass->restaurant;
        }
        return null;
    }
}