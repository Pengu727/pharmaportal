<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Admin extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $connection = 'mongodb';
    protected $collection = 'admins'; // Points purely to the separate collection
    protected $guarded = [];

    protected $hidden = [
        'password',
    ];
}