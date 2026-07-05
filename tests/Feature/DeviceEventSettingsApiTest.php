<?php

namespace Tests\Feature;

use App\Models\ClientDevice;
use App\Models\EventCaptureMode;
use App\Models\EventTemplate;
use App\Models\PhotoboothEvent;
use App\Models\PhotoboothTemplate;
use App\Models\TemplateStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeviceEventSettingsApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $clientUser;
    protected ClientDevice $device;
    protected string $rawToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientUser = User::factory()->create(['role' => 'user']);
        $this->rawToken = 'dev_tok_12345';

        // Pair a device
        $this->device = ClientDevice::create([
            'user_id' => $this->clientUser->id,
            'device_name' => 'iPad Pro 1',
            'pairing_code' => 'PAIR12',
            'platform' => 'ios',
            'api_token_hash' => hash('sha256', $this->rawToken),
            'paired_at' => now(),
        ]);
    }

    /**
     * Unauthorized device should return 401.
     */
    public function test_event_settings_unauthorized_token(): void
    {
        $response = $this->getJson('/api/v1/devices/event-settings');
        $response->assertStatus(401);

        $responseWithWrongToken = $this->getJson('/api/v1/devices/event-settings', [
            'Authorization' => 'Bearer WRONG_TOKEN',
        ]);
        $responseWithWrongToken->assertStatus(401);
    }

    /**
     * Revoked device should return 403.
     */
    public function test_event_settings_revoked_device(): void
    {
        $this->device->update(['revoked_at' => now()]);

        $response = $this->getJson('/api/v1/devices/event-settings', [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);
        $response->assertStatus(403);
    }

    /**
     * Valid token but no event assigned should return success false.
     */
    public function test_event_settings_no_assigned_event(): void
    {
        $response = $this->getJson('/api/v1/devices/event-settings', [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'message' => 'No active event assigned',
        ]);
    }

    /**
     * Assigned event but no templates or capture modes assigned. Should return fallback defaults.
     */
    public function test_event_settings_assigned_event_with_fallback_defaults(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Fallback Event Test',
            'slug' => 'fallback-event-test',
            'status' => 'active',
        ]);
        $event->setting()->create([
            'layout_type' => 'classic',
            'countdown_seconds' => 5,
            'capture_count' => 3,
        ]);

        $this->device->update(['current_event_id' => $event->id]);

        $response = $this->getJson('/api/v1/devices/event-settings', [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'event' => [
                'name' => 'Fallback Event Test',
            ],
            'settings' => [
                'layout_type' => 'classic',
                'countdown_seconds' => 5,
                'capture_count' => 3,
            ],
            'capture_modes' => [
                [
                    'mode_type' => 'photo',
                    'is_enabled' => true,
                    'sort_order' => 0,
                ]
            ],
            'templates' => [],
            'default_template' => null,
        ]);

        // Assert heartbeat updated
        $this->device->refresh();
        $this->assertNotNull($this->device->last_heartbeat_at);
        $this->assertTrue($this->device->is_online);
    }

    /**
     * Assigned event with capture modes, templates, steps, timings, default template.
     */
    public function test_event_settings_assigned_event_full_payload(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Gala Event',
            'slug' => 'gala-event',
            'status' => 'active',
        ]);

        $setting = $event->setting()->create([
            'layout_type' => 'custom',
            'countdown_seconds' => 4,
            'capture_count' => 3,
            'config_json' => [
                'timing' => [
                    'initial_countdown' => 7,
                    'between_capture_delay' => 3,
                    'preview_duration' => 4,
                    'retake_timeout' => 12,
                    'final_preview_duration' => 9,
                    'idle_timeout' => 40,
                ],
            ],
        ]);

        $this->device->update(['current_event_id' => $event->id]);

        // Enable capture modes
        EventCaptureMode::create([
            'photobooth_event_id' => $event->id,
            'mode_type' => 'photo',
            'is_enabled' => true,
            'sort_order' => 0,
        ]);
        EventCaptureMode::create([
            'photobooth_event_id' => $event->id,
            'mode_type' => 'gif',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);
        EventCaptureMode::create([
            'photobooth_event_id' => $event->id,
            'mode_type' => 'boomerang',
            'is_enabled' => false,
            'sort_order' => 2,
        ]);

        // Create active templates
        $tmpl1 = PhotoboothTemplate::create([
            'name' => 'Main Template',
            'template_type' => 'photo_4x6_portrait',
            'orientation' => 'portrait',
            'canvas_width' => 1200,
            'canvas_height' => 1800,
            'capture_count' => 3,
            'photo_slots_json' => [['x' => 10, 'y' => 20]],
            'timing_json' => [
                'initial_countdown' => 6,
                'between_capture_delay' => 2,
                'preview_duration' => 3,
                'retake_timeout' => 10,
                'final_preview_duration' => 8,
                'idle_timeout' => 30
            ],
            'status' => 'active',
        ]);

        // Add step to template
        TemplateStep::create([
            'photobooth_template_id' => $tmpl1->id,
            'step_number' => 1,
            'slot_number' => 1,
            'countdown_seconds' => 6,
            'preview_seconds' => 3,
            'instruction_text' => 'Get ready!',
            'config_json' => ['flash' => true],
        ]);

        // Map templates to event (tmpl1 is default)
        EventTemplate::create([
            'photobooth_event_id' => $event->id,
            'photobooth_template_id' => $tmpl1->id,
            'is_default' => true,
            'mode_type' => 'photo',
            'sort_order' => 0,
        ]);

        $response = $this->getJson('/api/v1/devices/event-settings', [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response->assertStatus(200);

        // Verify JSON response elements
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('event.name', 'Gala Event');

        // Verify capture modes has photo and gif, but not boomerang because boomerang is not enabled
        $response->assertJsonCount(2, 'capture_modes');
        $response->assertJsonPath('capture_modes.0.mode_type', 'photo');
        $response->assertJsonPath('capture_modes.1.mode_type', 'gif');

        // Verify templates list
        $response->assertJsonCount(1, 'templates');
        $response->assertJsonPath('templates.0.name', 'Main Template');
        $response->assertJsonPath('templates.0.canvas_width', 1200);
        $response->assertJsonPath('templates.0.photo_slots.0.x', 10);
        $response->assertJsonPath('templates.0.timing.initial_countdown', 7);
        $response->assertJsonPath('templates.0.timing.idle_timeout', 40);

        // Verify steps
        $response->assertJsonCount(1, 'templates.0.steps');
        $response->assertJsonPath('templates.0.steps.0.instruction_text', 'Get ready!');
        $response->assertJsonPath('templates.0.steps.0.config.flash', true);

        // Verify default template matches Main Template basic info
        $response->assertJsonPath('default_template.id', $tmpl1->id);
        $response->assertJsonPath('default_template.name', 'Main Template');
        $response->assertJsonPath('default_template.capture_count', 3);
    }

    /**
     * Verify fallback to settings.template_id when no default is explicitly checked.
     */
    public function test_event_settings_fallback_to_settings_template_id(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Gala Event',
            'slug' => 'gala-event',
            'status' => 'active',
        ]);

        $tmpl1 = PhotoboothTemplate::create([
            'name' => 'T1',
            'template_type' => 'strip_2x6',
            'orientation' => 'portrait',
            'canvas_width' => 600,
            'canvas_height' => 1800,
            'capture_count' => 3,
            'status' => 'active',
        ]);

        // Explicitly map settings.template_id to T1
        $setting = $event->setting()->create([
            'layout_type' => 'strip',
            'countdown_seconds' => 5,
            'capture_count' => 3,
            'template_id' => $tmpl1->id,
        ]);

        $this->device->update(['current_event_id' => $event->id]);

        // Assign templates, but with is_default = false
        EventTemplate::create([
            'photobooth_event_id' => $event->id,
            'photobooth_template_id' => $tmpl1->id,
            'is_default' => false,
            'mode_type' => 'photo',
            'sort_order' => 0,
        ]);

        $response = $this->getJson('/api/v1/devices/event-settings', [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('default_template.id', $tmpl1->id);
        $response->assertJsonPath('default_template.name', 'T1');
    }

    /**
     * Verify fallback to first assigned template when neither is default and settings.template_id is null.
     */
    public function test_event_settings_fallback_to_first_assigned(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Gala Event',
            'slug' => 'gala-event',
            'status' => 'active',
        ]);

        $tmpl1 = PhotoboothTemplate::create([
            'name' => 'T1 First Assigned',
            'template_type' => 'strip_2x6',
            'orientation' => 'portrait',
            'canvas_width' => 600,
            'canvas_height' => 1800,
            'capture_count' => 3,
            'status' => 'active',
        ]);

        $tmpl2 = PhotoboothTemplate::create([
            'name' => 'T2 Second Assigned',
            'template_type' => 'strip_2x6',
            'orientation' => 'portrait',
            'canvas_width' => 600,
            'canvas_height' => 1800,
            'capture_count' => 3,
            'status' => 'active',
        ]);

        $setting = $event->setting()->create([
            'layout_type' => 'strip',
            'countdown_seconds' => 5,
            'capture_count' => 3,
            'template_id' => null, // null template_id in settings
        ]);

        $this->device->update(['current_event_id' => $event->id]);

        // Assign templates, but neither has is_default = true
        EventTemplate::create([
            'photobooth_event_id' => $event->id,
            'photobooth_template_id' => $tmpl1->id,
            'is_default' => false,
            'mode_type' => 'photo',
            'sort_order' => 0,
        ]);
        EventTemplate::create([
            'photobooth_event_id' => $event->id,
            'photobooth_template_id' => $tmpl2->id,
            'is_default' => false,
            'mode_type' => 'photo',
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/v1/devices/event-settings', [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response->assertStatus(200);
        // Should fallback to the first template (T1)
        $response->assertJsonPath('default_template.id', $tmpl1->id);
        $response->assertJsonPath('default_template.name', 'T1 First Assigned');
    }
}
