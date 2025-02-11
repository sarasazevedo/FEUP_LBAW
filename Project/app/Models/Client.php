<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends User
{
    use HasFactory;

    protected $table = 'client';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function posts()
    {
        return $this->hasMany(ReviewPost::class, 'client_id')
                    ->join('post', 'review_post.id', '=', 'post.id')
                    ->whereNull('review_post.group_id')
                    ->orderBy('post.datetime', 'desc')
                    ->select('review_post.*');
    }

    public function followed()
    {
        return $this->belongsToMany(Client::class, 'follows_client', 'sender_client_id', 'followed_client_id');
    }


    public function followRequests()
    {
        return $this->hasMany(RequestFollow::class, 'requester_client_id');
    }

    public function receivedFollowRequests()
    {
        return $this->hasMany(RequestFollow::class, 'receiver_client_id');
    }

    public function ownedGroups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_member', 'client_id', 'group_id');
    }

    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class, 'client_id');
    }

}