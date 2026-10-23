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
        'role_profile',
        'pharmacy_id',
    ];
    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'date_naissance' => 'date', 
        'is_verified' => 'boolean', 
        'role_profile' => 'array',
    ];

    /**
     * Get the pharmacy this user belongs to (owner or seller).
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * Get reservations made by this user (client).
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }
}
