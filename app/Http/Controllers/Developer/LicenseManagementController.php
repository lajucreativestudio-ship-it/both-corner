<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\MonetizationSetting;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class LicenseManagementController extends Controller
{
    /**
     * Enforce developer/admin role checks.
     */
    private function ensureDeveloperAccess(): void
    {
        if (!auth()->check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if ($user->email === 'developer@bothcorner.com') {
            return;
        }

        if (in_array($user->role, ['admin', 'team'], true)) {
            return;
        }

        abort(403, 'Akses ditolak.');
    }

    /**
     * Display general license dashboard metrics.
     */
    public function index()
    {
        $this->ensureDeveloperAccess();

        // Self-healing default plans check
        app(\App\Services\LicenseService::class)->initializeDefaultPlans();

        $totalFreeUsers = User::where('subscription_status', 'free')
            ->orWhereNull('subscription_status')
            ->count();

        $totalActivePaidUsers = User::whereIn('subscription_status', ['trial', 'active'])->count();
        $totalPlans = SubscriptionPlan::count();

        return view('developer.licenses.index', compact('totalFreeUsers', 'totalActivePaidUsers', 'totalPlans'));
    }

    /**
     * List all available subscription plans.
     */
    public function plans()
    {
        $this->ensureDeveloperAccess();

        $plans = SubscriptionPlan::orderBy('sort_order')->get();

        return view('developer.licenses.plans', compact('plans'));
    }

    /**
     * List registered users and enable plan overrides.
     */
    public function users()
    {
        $this->ensureDeveloperAccess();

        $users = User::with('currentPlan')->paginate(20);
        $plans = SubscriptionPlan::orderBy('sort_order')->get();

        return view('developer.licenses.users', compact('users', 'plans'));
    }

    /**
     * Assign a plan override manually to a user.
     */
    public function assignPlan(Request $request, User $user)
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'status' => ['required', 'in:active,trial,expired,cancelled'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);

        $user->update([
            'current_plan_id' => $plan->id,
            'subscription_status' => $validated['status'],
        ]);

        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'source' => 'manual',
            'status' => $validated['status'],
            'started_at' => now(),
            'expires_at' => ($validated['expires_at'] ?? null) ? \Carbon\Carbon::parse($validated['expires_at']) : null,
        ]);

        return back()->with('success', "Lisensi user {$user->name} berhasil diperbarui ke paket {$plan->name}.");
    }

    /**
     * Display monetization and placeholder configs.
     */
    public function monetization()
    {
        $this->ensureDeveloperAccess();

        $keys = [
            'public_share_domain',
            'adsense_enabled',
            'adsense_client_id',
            'adsense_download_slot_id',
            'admob_enabled',
            'admob_app_id',
            'admob_banner_unit_id',
            'admob_interstitial_unit_id',
            'analytics_enabled',
            'firebase_measurement_id',
            'default_watermark_text',
            'default_branding_text',
        ];

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = MonetizationSetting::getByKey($key, '');
        }

        return view('developer.monetization.index', compact('settings'));
    }

    /**
     * Save monetization configuration params.
     */
    public function saveMonetization(Request $request)
    {
        $this->ensureDeveloperAccess();

        $keys = [
            'public_share_domain',
            'adsense_enabled',
            'adsense_client_id',
            'adsense_download_slot_id',
            'admob_enabled',
            'admob_app_id',
            'admob_banner_unit_id',
            'admob_interstitial_unit_id',
            'analytics_enabled',
            'firebase_measurement_id',
            'default_watermark_text',
            'default_branding_text',
        ];

        foreach ($keys as $key) {
            MonetizationSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $request->input($key),
                    'is_enabled' => true,
                    'group' => str_contains($key, 'adsense') || str_contains($key, 'admob') ? 'ads' : 'general',
                ]
            );
        }

        return back()->with('success', 'Konfigurasi monetisasi berhasil disimpan.');
    }
}
