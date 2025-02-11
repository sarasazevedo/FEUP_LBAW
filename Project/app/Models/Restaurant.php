<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends User
{
    use HasFactory;

    protected $table = 'restaurant';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'rating_average',
        'type_id',
        'capacity'
    ];

    public function userDetails() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function posts() : HasMany
    {
        return $this->hasMany(InformationalPost::class, 'restaurant_id')
        ->join('post', 'informational_post.id', '=', 'post.id')
        ->orderBy('post.datetime', 'desc')
        ->select('informational_post.*');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(RestaurantType::class, 'type_id');
    }
}