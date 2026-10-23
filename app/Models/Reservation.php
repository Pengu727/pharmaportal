<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reservation extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reservations';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * The seller who finalized/completed this reservation.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
