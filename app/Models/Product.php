<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $primaryKey = '_id';
    protected $keyType = 'string';

    protected $fillable = [
        'pharmacy_id',
        'name',
        'description',
        'category',
        'dosage',
        'laboratory',
        'is_public',
        'visibility_status',
        'price',
        'stock',
        'min_threshold',
        'expiration_date',
        'sku',
        'metadata',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'int',
        'min_threshold' => 'int',
        'is_public' => 'boolean',
        'metadata' => 'array',
        'expiration_date' => 'date',
    ];

    /**
     * Computed product status based on stock + expiration rules.
     *
     * Priority: EXPIRED > EXPIRING_SOON > LOW_STOCK > ACTIVE
     */
    public function getStatusAttribute(): string
    {
        // Check expiration first (highest priority)
        if ($this->expiration_date) {
            $expDate = $this->expiration_date instanceof Carbon
                ? $this->expiration_date
                : Carbon::parse($this->expiration_date);

            if ($expDate->isPast()) {
                return 'EXPIRED';
            }

            if ($expDate->diffInDays(Carbon::today(), false) >= -30) {
                return 'EXPIRING_SOON';
            }
        }

        // Check stock threshold
        $threshold = $this->min_threshold ?? 10;
        if ($this->stock <= $threshold) {
            return 'LOW_STOCK';
        }

        return 'ACTIVE';
    }

    /**
     * Backward-compatible accessor: derives is_public from visibility_status.
     * If visibility_status is set, use it. Otherwise fall back to the raw is_public field.
     */
    public function getIsVisibleAttribute(): bool
    {
        if ($this->visibility_status) {
            return $this->visibility_status === 'public';
        }

        return (bool) $this->getAttributeValue('is_public');
    }

    /**
     * Get the pharmacy that owns this product.
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * Used by Laravel's route() helper to pull the parameter value.
     */
    public function getRouteKey()
    {
        return (string) $this->getAttribute('_id');
    }
}