<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pharmacy extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pharmacies';
    protected $guarded = [];

    /**
     * Get the owner User of this pharmacy.
     */
    public function owner()
    {
        return $this->hasOne(User::class, 'pharmacy_id')->where('role', 'owner');
    }

    /**
     * Get all sellers assigned to this pharmacy.
     */
    public function sellers()
    {
        return $this->hasMany(User::class, 'pharmacy_id')->where('role', 'seller');
    }

    /**
     * Get all products in this pharmacy.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'pharmacy_id');
    }

    /**
     * Get all reservations for this pharmacy.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'pharmacy_id');
    }

    /**
     * Helper: generate a proper Google Maps link from stored coordinates.
     */
    public function getGoogleMapsUrlAttribute()
    {
        if (isset($this->location['coordinates']) 
            && is_array($this->location['coordinates']) 
            && count($this->location['coordinates']) >= 2
            && $this->location['coordinates'][0] != 0) {
            $lat = $this->location['coordinates'][1];
            $lng = $this->location['coordinates'][0];
            return "https://www.google.com/maps?q={$lat},{$lng}";
        }
        return null;
    }
}