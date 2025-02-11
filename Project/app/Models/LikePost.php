<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikePost extends Model
{
    use HasFactory;

    protected $table = 'like_post';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'datetime',
        'user_id',
        'post_id'
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    // Override the primary key type
    protected $keyType = 'string';

    // Define the primary key
    protected $primaryKey = null;

    // Override the save method to handle composite keys
    public function save(array $options = [])
    {
        if (!$this->exists) {
            $this->setKeysForSaveQuery($this->newQuery())->insert($this->attributes);
            $this->exists = true;
            return true;
        }

        return parent::save($options);
    }

    // Set the keys for the save query
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $key) {
            $query->where($key, '=', $this->getKeyForSaveQuery($key));
        }

        return $query;
    }

    // Get the key for the save query
    protected function getKeyForSaveQuery($key = null)
    {
        if (is_null($key)) {
            $key = $this->getKeyName();
        }

        return $this->original[$key] ?? $this->getAttribute($key);
    }

    // Get the primary key name
    public function getKeyName()
    {
        return ['user_id', 'post_id'];
    }
}