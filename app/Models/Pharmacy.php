<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pharmacy extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pharmacies';
    protected $guarded = [];

    // Cast nested array attributes cleanly for ease of management
    protected $casts = [
        'is_verified' => 'boolean',
        'role_profile' => 'array',
    ];
}