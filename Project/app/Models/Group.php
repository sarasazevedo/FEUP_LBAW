<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';

    protected $fillable = [
        'name',
        'description',
        'is_public',
        'owner_id',
    ];

    public $timestamps = false; // Disable timestamps


    /**
     * Get the owner of the group.
     */
    public function owner()
    {
        return $this->belongsTo(Client::class, 'owner_id');
    }

    /**
     * Get the members of the group.
     */
    public function members()
    {
        return $this->belongsToMany(Client::class, 'group_member', 'group_id', 'client_id');
    }

    public function posts()
    {
        return $this->hasMany(ReviewPost::class, 'group_id');
    }

    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class, 'group_id');
    }

}