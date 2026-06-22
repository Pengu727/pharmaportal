<?php

namespace App\Models;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $connection = 'mongodb';
    protected $collection = 'users';
    protected $fillable = [
        'nom', 
        'prenom', 
        'email', 
        'password', 
        'num_tel', 
        'wilaya', 
        'commune', 
        'date_naissance', 
        'role', 
        'is_verified', 
        'role_profile'
    ];
    protected $casts = [
        'date_naissance' => 'date', 
        'is_verified' => 'boolean', 
        'role_profile' => 'array'];
}
