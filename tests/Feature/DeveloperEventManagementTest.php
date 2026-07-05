<?php

namespace Tests\Feature;

use App\Models\ClientDevice;
use App\Models\PhotoboothEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperEventManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $clientUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed admin user
        $this->adminUser = User::factory()->create([
            'email' => 'developer@bothcorner.com',
            'role' => 'admin',
        ]);

        // Seed client user
        $this->clientUser = User::factory()->create([
            'role' => 'user',
        ]);

    }

    /**
     * Test admin can access event index and list events.
     */
    public function test_admin_can_access_event_index(): void
    {
        PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Event A',
            'slug' => 'event-a',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/developer/events');

        $response->assertStatus(200);
        $response->assertSee('Event A');
        $response->assertSee('Event Monitoring');
        $response->assertDontSee('Buat Event Baru');
    }

    /**
     * Test normal client/user cannot access developer events page.
     */
    public function test_normal_user_cannot_access_developer_events(): void
    {
        $response = $this->actingAs($this->clientUser)->get('/developer/events');
        $response->assertStatus(403);
    }

    /**
     * Developer event setup routes are removed from the primary web flow.
     */
    public function test_developer_event_setup_routes_are_not_available(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Route Disabled Event',
            'slug' => 'route-disabled-event',
            'status' => 'draft',
        ]);

        $this->actingAs($this->adminUser)->get('/developer/events/create')->assertStatus(404);
        $this->actingAs($this->adminUser)->post('/developer/events', [])->assertStatus(405);
        $this->actingAs($this->adminUser)->get("/developer/events/{$event->id}/edit")->assertStatus(404);
        $this->actingAs($this->adminUser)->put("/developer/events/{$event->id}", [])->assertStatus(404);
    }

    /**
     * Test admin can assign and unassign a device.
     */
    public function test_admin_can_assign_and_unassign_device(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Company Gathering',
            'slug' => 'company-gathering',
            'status' => 'active',
        ]);

        $device = ClientDevice::create([
            'device_uuid' => 'test-device-uuid-123',
            'device_name' => 'iPad Booth Pro',
            'platform' => 'iOS',
            'user_id' => $this->clientUser->id,
        ]);

        // 1. Assign device
        $response = $this->actingAs($this->adminUser)->post("/developer/events/{$event->id}/assign-device", [
            'device_id' => $device->id,
        ]);

        $response->assertRedirect("/developer/events/{$event->id}/manage");
        $this->assertDatabaseHas('client_devices', [
            'id' => $device->id,
            'current_event_id' => $event->id,
        ]);

        // 2. Unassign device
        $response = $this->actingAs($this->adminUser)->post("/developer/events/{$event->id}/unassign-device", [
            'device_id' => $device->id,
        ]);

        $response->assertRedirect("/developer/events/{$event->id}/manage");
        $this->assertDatabaseHas('client_devices', [
            'id' => $device->id,
            'current_event_id' => null,
        ]);
    }
}
