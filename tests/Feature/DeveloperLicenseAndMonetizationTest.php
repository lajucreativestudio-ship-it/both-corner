<?php

namespace Tests\Feature;

use App\Models\ClientDevice;
use App\Models\MonetizationSetting;
use App\Models\PhotoboothEvent;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperLicenseAndMonetizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $clientUser;
    protected SubscriptionPlan $freePlan;
    protected SubscriptionPlan $proPlan;
    protected PhotoboothEvent $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'developer@bothcorner.com',
            'role' => 'admin',
        ]);

        $this->clientUser = User::factory()->create([
            'role' => 'user',
        ]);

        // Seed default plans using LicenseService
        app(\App\Services\LicenseService::class)->initializeDefaultPlans();
        
        $this->freePlan = SubscriptionPlan::where('code', 'free')->first();
        $this->proPlan = SubscriptionPlan::where('code', 'pro')->first();

        // Default client to free plan
        $this->clientUser->update([
            'current_plan_id' => $this->freePlan->id,
            'subscription_status' => 'free',
        ]);

        $this->event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Monetization Gala',
            'slug' => 'monetization-gala',
            'status' => 'active',
            'gallery_visibility' => 'public',
        ]);

        $this->event->setting()->create([
            'layout_type' => 'classic',
            'countdown_seconds' => 5,
            'capture_count' => 3,
        ]);
    }

    /**
     * Non-admin user should be blocked from managing licenses and monetization.
     */
    public function test_non_admin_cannot_access_license_pages(): void
    {
        $this->actingAs($this->clientUser)->get('/developer/licenses')->assertStatus(403);
        $this->actingAs($this->clientUser)->get('/developer/licenses/plans')->assertStatus(403);
        $this->actingAs($this->clientUser)->get('/developer/licenses/users')->assertStatus(403);
        $this->actingAs($this->clientUser)->get('/developer/monetization')->assertStatus(403);
    }

    /**
     * Admin can view all overview, plan list, user lists, and monetization parameters.
     */
    public function test_admin_can_access_license_and_monetization_pages(): void
    {
        $this->actingAs($this->adminUser)->get('/developer/licenses')
            ->assertStatus(200)
            ->assertSee('Active Paid Subscriptions');

        $this->actingAs($this->adminUser)->get('/developer/licenses/plans')
            ->assertStatus(200)
            ->assertSee('Free Plan')
            ->assertSee('Pro Plan');

        $this->actingAs($this->adminUser)->get('/developer/licenses/users')
            ->assertStatus(200)
            ->assertSee($this->clientUser->name)
            ->assertSee('Override Plan');

        $this->actingAs($this->adminUser)->get('/developer/monetization')
            ->assertStatus(200)
            ->assertSee('Google AdSense Client ID')
            ->assertSee('AdMob App ID');
    }

    /**
     * Admin can manually assign a plan override to a user.
     */
    public function test_admin_can_override_user_plan(): void
    {
        $response = $this->actingAs($this->adminUser)->post("/developer/licenses/users/{$this->clientUser->id}/assign-plan", [
            'subscription_plan_id' => $this->proPlan->id,
            'status' => 'active',
        ]);

        $response->assertStatus(302);
        
        $this->clientUser->refresh();
        $this->assertEquals($this->proPlan->id, $this->clientUser->current_plan_id);
        $this->assertEquals('active', $this->clientUser->subscription_status);
        
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $this->clientUser->id,
            'subscription_plan_id' => $this->proPlan->id,
            'status' => 'active',
            'source' => 'manual',
        ]);
    }

    /**
     * Admin can save monetization configuration parameters.
     */
    public function test_admin_can_save_monetization_parameters(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/developer/monetization', [
            'public_share_domain' => 'mybooth.me',
            'adsense_enabled' => '1',
            'adsense_client_id' => 'ca-pub-test1234',
            'default_watermark_text' => 'My Watermark',
        ]);

        $response->assertStatus(302);

        $this->assertEquals('mybooth.me', MonetizationSetting::getByKey('public_share_domain'));
        $this->assertEquals('ca-pub-test1234', MonetizationSetting::getByKey('adsense_client_id'));
    }

    /**
     * Public pages display ad placeholders and watermark badges for Free plans, but hide them for Paid.
     */
    public function test_public_pages_ads_and_watermark_visibility(): void
    {
        // 1. Visited with free plan
        $this->clientUser->update([
            'current_plan_id' => $this->freePlan->id,
            'subscription_status' => 'free',
        ]);

        $session = $this->event->boothSessions()->create([
            'session_code' => 'SESS-FREE',
            'public_token' => 'free-token-123',
            'status' => 'completed',
            'mode_type' => 'photo',
        ]);

        $this->get("/e/{$this->event->slug}")
            ->assertStatus(200)
            ->assertSee('Google AdSense Banner Placeholder');

        $this->get("/s/free-token-123")
            ->assertStatus(200)
            ->assertSee('Google AdSense Banner Placeholder')
            ->assertSee('Powered by <span class="text-indigo-600 font-extrabold font-display">BothCorner</span>', false);

        // 2. Visited with pro plan
        $this->clientUser->update([
            'current_plan_id' => $this->proPlan->id,
            'subscription_status' => 'active',
        ]);

        $this->get("/e/{$this->event->slug}")
            ->assertStatus(200)
            ->assertDontSee('Google AdSense Banner Placeholder');

        $this->get("/s/free-token-123")
            ->assertStatus(200)
            ->assertDontSee('Google AdSense Banner Placeholder')
            ->assertDontSee('Powered by <span class="text-indigo-600 font-extrabold font-display">BothCorner</span>', false);
    }

    /**
     * Settings API payload maps license options correctly.
     */
    public function test_settings_api_includes_license_payload(): void
    {
        $device = ClientDevice::create([
            'user_id' => $this->clientUser->id,
            'device_name' => 'Camera Device A',
            'pairing_code' => 'PAIRAA',
            'platform' => 'android',
            'current_event_id' => $this->event->id,
            'api_token_hash' => hash('sha256', 'my_token'),
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer my_token'])
            ->getJson('/api/v1/devices/event-settings');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'event' => ['id', 'name', 'slug'],
            'license' => [
                'plan',
                'subscription_status',
                'features' => [
                    'custom_template_upload',
                    'watermark_enabled',
                    'ads_enabled',
                ],
                'limits' => [
                    'max_events',
                    'max_devices',
                ]
            ]
        ]);
        
        $this->assertEquals('free', $response->json('license.plan'));
    }
}
