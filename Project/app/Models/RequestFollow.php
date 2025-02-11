<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RequestFollow extends Model
{
    use HasFactory;

    protected $table = 'request_follow';
    public $timestamps = false;

    protected $fillable = [
        'datetime',
        'requester_client_id',
        'receiver_client_id',
    ];
    protected $primaryKey = ['requester_client_id', 'receiver_client_id'];
    public $incrementing = false;

    public function getKeyName()
    {
        return ['requester_client_id', 'receiver_client_id'];
    }

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }

    public function delete()
    {
        $query = $this->newQueryWithoutScopes();

        foreach ($this->primaryKey as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query->delete();
    }

    public function followed(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'follows_client', 'sender_client_id', 'followed_client_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'requester_client_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'receiver_client_id');
    }
}