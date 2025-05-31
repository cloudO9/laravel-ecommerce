<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PersonalAccessToken extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'tokenable_type',
        'tokenable_id',
    ];
    
    protected $casts = [
        'abilities' => 'array',
        'expires_at' => 'datetime',
    ];
    
    public function tokenable()
    {
        return $this->morphTo();
    }
    
    // Static method to find token (used by Sanctum middleware)
    public static function findToken($token)
    {
        if (strpos($token, '|') === false) {
            return static::where('token', hash('sha256', $token))->first();
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = static::find($id)) {
            return hash_equals($instance->token, hash('sha256', $token)) ? $instance : null;
        }

        return null;
    }
    
    // Add method to check if token can perform ability (required by Sanctum)
    public function can($ability)
    {
        return in_array('*', $this->abilities) || in_array($ability, $this->abilities);
    }
    
    // Add method to check if token cannot perform ability
    public function cant($ability)
    {
        return !$this->can($ability);
    }
    
    // Override the getAttribute method to handle 'id' requests
    public function getAttribute($key)
    {
        if ($key === 'id') {
            return $this->_id;
        }
        
        return parent::getAttribute($key);
    }
    
    // Add method to get the primary key value
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }
    
    public function getKeyName()
    {
        return '_id';
    }
}