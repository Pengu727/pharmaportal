<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    // 1. Tell Laravel that MongoDB uses '_id' as its main key, not 'id'
    protected $primaryKey = '_id';

    // 2. Specify that your primary key is an alphanumeric string, not an integer
    protected $keyType = 'string';

    protected $fillable = [
        'pharmacy_id',
        'name',
        'description',
        'category',      
        'is_public',     
        'price',
        'stock',
        'metadata'
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'int',
        'is_public' => 'boolean', 
        'metadata' => 'array',
    ];

    /**
     * 3. This method is used by Laravel's route() helper to pull the parameter value.
     * Turning the MongoDB ObjectId object wrapper into a string eliminates the 404.
     */
    public function getRouteKey()
    {
        return (string) $this->getAttribute('_id');
    }
}