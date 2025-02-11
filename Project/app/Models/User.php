<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'username',
        'password',
        'description',
        'email',
        'image',
        'is_blocked',
        'is_admin',
        'is_deleted',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'is_admin' => 'boolean',
        'is_deleted' => 'boolean',
        'password' => 'hashed',
    ];

    
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows_client', 'followed_client_id', 'sender_client_id')
                    ->using(FollowsClient::class);
    }

    public function followedClients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'follows_client', 'sender_client_id', 'followed_client_id')
                    ->using(FollowsClient::class);
    }

    public function followedRestaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'follows_restaurant', 'client_id', 'restaurant_id')
                    ->using(FollowsRestaurant::class);
    }

    public function followedCount(): int
    {
        return ($this->followedClients ? $this->followedClients->count() : 0) + 
               ($this->followedRestaurants ? $this->followedRestaurants->count() : 0);
    }


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function castToSubclass()
    {
        if (Restaurant::find($this->id)) {
            return Restaurant::find($this->id);
        } elseif (Client::find($this->id)) {
            return Client::find($this->id);
        }
        return $this;
    }
}