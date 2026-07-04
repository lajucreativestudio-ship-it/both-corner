<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'billing_period',
        'is_internal',
        'payment_method',
        'features',
    ];

    /**
     * Decode features to array
     */
    public function getFeaturesListAttribute()
    {
        return json_decode($this->features, true) ?: [];
    }
}
