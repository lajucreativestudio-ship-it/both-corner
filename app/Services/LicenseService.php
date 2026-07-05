<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\User;

class LicenseService
{
    /**
     * Get the current plan for a user, defaulting to the free plan.
     */
    public function getPlanForUser(User $user): SubscriptionPlan
    {
        // Self-healing database check
        $this->initializeDefaultPlans();

        if ($user->currentPlan) {
            return $user->currentPlan;
        }

        $freePlan = SubscriptionPlan::where('code', 'free')->first();
        if ($freePlan) {
            $user->update([
                'current_plan_id' => $freePlan->id,
                'subscription_status' => 'free',
            ]);
            $user->load('currentPlan');
            return $freePlan;
        }

        throw new \Exception('Default Free subscription plan could not be found.');
    }

    /**
     * Get all active feature flags for a user.
     */
    public function getFeatureFlagsForUser(User $user): array
    {
        $plan = $this->getPlanForUser($user);

        return [
            'custom_template_upload' => (bool)$plan->custom_template_upload,
            'watermark_enabled' => (bool)$plan->watermark_enabled,
            'ads_enabled' => (bool)$plan->ads_enabled,
            'admob_enabled' => (bool)$plan->admob_enabled,
            'adsense_enabled' => (bool)$plan->adsense_enabled,
            'custom_branding' => (bool)$plan->custom_branding,
            'raw_download_enabled' => (bool)$plan->raw_download_enabled,
            'public_gallery_enabled' => (bool)$plan->public_gallery_enabled,
        ];
    }

    /**
     * Determine if user has permission to upload custom layouts.
     */
    public function canUploadCustomTemplate(User $user): bool
    {
        return (bool)$this->getPlanForUser($user)->custom_template_upload;
    }

    /**
     * Determine if advertising placeholders should be served to visitors.
     */
    public function shouldShowAds(User $user): bool
    {
        return (bool)$this->getPlanForUser($user)->ads_enabled;
    }

    /**
     * Determine if the guest sessions show watermarks.
     */
    public function shouldShowWatermark(User $user): bool
    {
        return (bool)$this->getPlanForUser($user)->watermark_enabled;
    }

    /**
     * Retrieve the user limit rules for database queries.
     */
    public function getUsageLimits(User $user): array
    {
        $plan = $this->getPlanForUser($user);

        return [
            'max_events' => $plan->max_events,
            'max_devices' => $plan->max_devices,
            'max_templates' => $plan->max_templates,
        ];
    }

    /**
     * Package user licensing parameters into an entitlement array.
     */
    public function getPublicGalleryEntitlements(User $user): array
    {
        $plan = $this->getPlanForUser($user);

        return [
            'plan' => $plan->code,
            'subscription_status' => $user->subscription_status ?? 'free',
            'features' => $this->getFeatureFlagsForUser($user),
            'limits' => $this->getUsageLimits($user),
        ];
    }

    /**
     * Self-healing presets database populator.
     */
    public function initializeDefaultPlans(): void
    {
        if (SubscriptionPlan::count() === 0) {
            SubscriptionPlan::create([
                'code' => 'free',
                'name' => 'Free Plan',
                'description' => 'Paket gratis untuk kebutuhan dasar.',
                'price_monthly' => 0.00,
                'price_yearly' => 0.00,
                'max_events' => 1,
                'max_devices' => 1,
                'max_templates' => 0,
                'custom_template_upload' => false,
                'watermark_enabled' => true,
                'ads_enabled' => true,
                'admob_enabled' => true,
                'adsense_enabled' => true,
                'custom_branding' => false,
                'raw_download_enabled' => true,
                'public_gallery_enabled' => true,
                'status' => 'active',
                'sort_order' => 0,
            ]);

            SubscriptionPlan::create([
                'code' => 'pro',
                'name' => 'Pro Plan',
                'description' => 'Paket profesional untuk bisnis photobooth.',
                'price_monthly' => 299000.00,
                'price_yearly' => 2999000.00,
                'max_events' => 20,
                'max_devices' => 3,
                'max_templates' => 20,
                'custom_template_upload' => true,
                'watermark_enabled' => false,
                'ads_enabled' => false,
                'admob_enabled' => false,
                'adsense_enabled' => false,
                'custom_branding' => true,
                'raw_download_enabled' => true,
                'public_gallery_enabled' => true,
                'status' => 'active',
                'sort_order' => 1,
            ]);

            SubscriptionPlan::create([
                'code' => 'business',
                'name' => 'Business Plan',
                'description' => 'Paket enterprise untuk skala besar.',
                'price_monthly' => 799000.00,
                'price_yearly' => 7999000.00,
                'max_events' => null,
                'max_devices' => 20,
                'max_templates' => null,
                'custom_template_upload' => true,
                'watermark_enabled' => false,
                'ads_enabled' => false,
                'admob_enabled' => false,
                'adsense_enabled' => false,
                'custom_branding' => true,
                'raw_download_enabled' => true,
                'public_gallery_enabled' => true,
                'status' => 'active',
                'sort_order' => 2,
            ]);
        }
    }
}
