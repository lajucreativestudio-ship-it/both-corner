<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price_monthly',
        'price_yearly',
        'currency',
        'max_events',
        'max_devices',
        'max_templates',
        'custom_template_upload',
        'watermark_enabled',
        'ads_enabled',
        'admob_enabled',
        'adsense_enabled',
        'custom_branding',
        'raw_download_enabled',
        'public_gallery_enabled',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'max_events' => 'integer',
        'max_devices' => 'integer',
        'max_templates' => 'integer',
        'custom_template_upload' => 'boolean',
        'watermark_enabled' => 'boolean',
        'ads_enabled' => 'boolean',
        'admob_enabled' => 'boolean',
        'adsense_enabled' => 'boolean',
        'custom_branding' => 'boolean',
        'raw_download_enabled' => 'boolean',
        'public_gallery_enabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'current_plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'subscription_plan_id');
    }

    public function featureFlags()
    {
        return $this->hasMany(FeatureFlag::class, 'subscription_plan_id');
    }
}
