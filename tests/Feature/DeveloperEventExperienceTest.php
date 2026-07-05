<?php

namespace Tests\Feature;

use App\Models\ClientDevice;
use App\Models\PhotoboothEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperEventExperienceTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $clientUser;
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

        $this->event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Control Center Gala',
            'slug' => 'control-center-gala',
            'status' => 'active',
            'location' => 'Bandung',
        ]);

        $this->event->setting()->create([
            'layout_type' => 'classic',
            'countdown_seconds' => 5,
            'capture_count' => 3,
        ]);
    }

    /**
     * Non-admin user should be blocked from managing the event experience.
     */
    public function test_non_admin_cannot_access_event_manage_experience(): void
    {
        $response = $this->actingAs($this->clientUser)->get("/developer/events/{$this->event->id}/manage");
        $response->assertStatus(403);
    }

    /**
     * Admin can access and see statistics and sections.
     */
    public function test_admin_can_access_event_manage_experience_with_metrics(): void
    {
        // Link a device to event
        ClientDevice::create([
            'user_id' => $this->clientUser->id,
            'device_name' => 'Tablet Admin',
            'pairing_code' => 'PAIR00',
            'platform' => 'android',
            'current_event_id' => $this->event->id,
        ]);

        $response = $this->actingAs($this->adminUser)->get("/developer/events/{$this->event->id}/manage");

        $response->assertStatus(200);
        $response->assertSee('Event Monitoring Center');
        $response->assertSee('Control Center Gala');
        $response->assertSee('Tablet Admin');
        $response->assertSee('Bandung');
        $response->assertSee('Booth App Capture Flow Preview');
    }
}
