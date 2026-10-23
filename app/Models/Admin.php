<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Admin extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $connection = 'mongodb';
    protected $collection = 'admins';
    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    /**
     * Admin always has role 'admin'. This accessor allows the CheckRole
     * middleware to verify admin role even though it's not stored in DB.
     */
    public function getRoleAttribute()
    {
        return 'admin';
    }
}