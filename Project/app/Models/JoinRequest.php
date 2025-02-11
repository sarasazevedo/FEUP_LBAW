<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    use HasFactory;

    protected $table = 'join_requests';

    protected $fillable = [
        'client_id',
        'group_id',
    ];

    protected $primaryKey = ['client_id', 'group_id'];
    public $incrementing = false;
    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getKeyName()
    {
        return ['client_id', 'group_id'];
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
        $this->setKeysForSaveQuery($query)->delete();
    }
}