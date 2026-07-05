<?php

namespace Tests\Feature;

use App\Models\PhotoboothEvent;
use App\Models\PhotoboothTemplate;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientEventManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private PhotoboothTemplate $templateA;
    private PhotoboothTemplate $templateB;

    protected function setUp(): void
    {
        parent::setUp();

        $plan = SubscriptionPlan::create([
            'code' => 'business',
            'name' => 'Business Plan',
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
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'current_plan_id' => $plan->id,
            'subscription_status' => 'active',
        ]);

        $this->templateA = PhotoboothTemplate::create([
            'name' => 'Global Classic',
            'template_type' => 'photo_4x6_portrait',
            'is_global' => true,
            'status' => 'active',
        ]);

        $this->templateB = PhotoboothTemplate::create([
            'name' => 'Global Strip',
            'template_type' => 'strip_2x6',
            'is_global' => true,
            'status' => 'active',
        ]);
    }

    public function test_client_can_create_event_with_templates_modes_and_timing(): void
    {
        $response = $this->actingAs($this->user)->post('/dashboard/events', $this->payload([
            'name' => 'Client Wedding',
            'templates' => [$this->templateA->id, $this->templateB->id],
            'default_template_id' => $this->templateB->id,
            'capture_modes' => ['photo', 'gif', 'boomerang'],
            'idle_timeout' => 55,
        ]));

        $event = PhotoboothEvent::where('name', 'Client Wedding')->first();

        $response->assertRedirect("/dashboard/events/{$event->id}");
        $this->assertSame($this->user->id, $event->user_id);
        $this->assertDatabaseHas('event_settings', [
            'photobooth_event_id' => $event->id,
            'template_id' => $this->templateB->id,
            'countdown_seconds' => 6,
        ]);
        $this->assertDatabaseHas('event_templates', [
            'photobooth_event_id' => $event->id,
            'photobooth_template_id' => $this->templateB->id,
            'is_default' => true,
        ]);
        $this->assertDatabaseHas('event_capture_modes', [
            'photobooth_event_id' => $event->id,
            'mode_type' => 'boomerang',
            'is_enabled' => true,
        ]);
        $this->assertSame(55, $event->setting()->first()->config_json['timing']['idle_timeout']);
    }

    public function test_client_can_update_own_event_setup(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->user->id,
            'name' => 'Original Event',
            'slug' => 'original-event',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user)->put("/dashboard/events/{$event->id}", $this->payload([
            'name' => 'Updated Event',
            'templates' => [$this->templateA->id],
            'default_template_id' => $this->templateA->id,
            'capture_modes' => ['photo', 'gif'],
            'gallery_visibility' => 'public',
            'idle_timeout' => 70,
        ]));

        $response->assertRedirect("/dashboard/events/{$event->id}/edit");
        $this->assertDatabaseHas('photobooth_events', [
            'id' => $event->id,
            'name' => 'Updated Event',
            'gallery_visibility' => 'public',
        ]);
        $this->assertDatabaseHas('event_capture_modes', [
            'photobooth_event_id' => $event->id,
            'mode_type' => 'gif',
            'is_enabled' => true,
        ]);
        $this->assertSame(70, $event->setting()->first()->config_json['timing']['idle_timeout']);
    }

    public function test_client_can_open_edit_event_form(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->user->id,
            'name' => 'Editable Event',
            'slug' => 'editable-event',
            'status' => 'draft',
        ]);

        $event->setting()->create([
            'template_id' => $this->templateA->id,
            'layout_type' => 'custom',
            'countdown_seconds' => 5,
            'capture_count' => 3,
            'config_json' => [
                'timing' => [
                    'initial_countdown' => 5,
                    'between_capture_delay' => 2,
                    'preview_duration' => 3,
                    'retake_timeout' => 10,
                    'final_preview_duration' => 8,
                    'idle_timeout' => 30,
                ],
            ],
        ]);

        $event->eventTemplates()->create([
            'photobooth_template_id' => $this->templateA->id,
            'is_default' => true,
            'mode_type' => 'photo',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get("/dashboard/events/{$event->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Event: Editable Event');
        $response->assertSee('Global Classic');
    }

    public function test_client_cannot_edit_other_users_event(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);
        $event = PhotoboothEvent::create([
            'user_id' => $otherUser->id,
            'name' => 'Other Event',
            'slug' => 'other-event',
            'status' => 'active',
        ]);

        $this->actingAs($this->user)->get("/dashboard/events/{$event->id}/edit")->assertStatus(403);
        $this->actingAs($this->user)->put("/dashboard/events/{$event->id}", $this->payload())->assertStatus(403);
    }

    public function test_free_user_cannot_upload_custom_template(): void
    {
        Storage::fake('public');
        $freePlan = SubscriptionPlan::create([
            'code' => 'free',
            'name' => 'Free Plan',
            'max_events' => 10,
            'max_devices' => 1,
            'max_templates' => 0,
            'custom_template_upload' => false,
            'status' => 'active',
        ]);
        $freeUser = User::factory()->create([
            'role' => 'user',
            'current_plan_id' => $freePlan->id,
            'subscription_status' => 'free',
        ]);

        $response = $this->actingAs($freeUser)->post('/dashboard/events', $this->payload([
            'templates' => [$this->templateA->id],
            'default_template_id' => $this->templateA->id,
            'overlay_file' => UploadedFile::fake()->image('overlay.png'),
        ]));

        $response->assertSessionHasErrors('overlay_file');
        $this->assertDatabaseMissing('photobooth_templates', [
            'user_id' => $freeUser->id,
        ]);
    }

    public function test_paid_user_can_upload_custom_template(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post('/dashboard/events', $this->payload([
            'name' => 'Custom Template Event',
            'templates' => [$this->templateA->id],
            'default_template_id' => 'custom',
            'overlay_file' => UploadedFile::fake()->image('overlay.png'),
        ]));

        $event = PhotoboothEvent::where('name', 'Custom Template Event')->first();
        $customTemplate = PhotoboothTemplate::where('user_id', $this->user->id)->first();

        $response->assertRedirect("/dashboard/events/{$event->id}");
        $this->assertNotNull($customTemplate);
        Storage::disk('public')->assertExists($customTemplate->overlay_path);
        $this->assertDatabaseHas('event_settings', [
            'photobooth_event_id' => $event->id,
            'template_id' => $customTemplate->id,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Client Event',
            'event_date' => now()->addDay()->format('Y-m-d'),
            'location' => 'Jakarta',
            'status' => 'draft',
            'gallery_visibility' => 'private',
            'layout_type' => 'custom',
            'template_type' => 'photo_4x6_portrait',
            'orientation' => 'portrait',
            'canvas_width' => 1200,
            'canvas_height' => 1800,
            'capture_count' => 3,
            'initial_countdown' => 6,
            'between_capture_delay' => 2,
            'preview_duration' => 4,
            'retake_timeout' => 12,
            'final_preview_duration' => 8,
            'idle_timeout' => 40,
            'retake_enabled' => '1',
            'capture_modes' => ['photo'],
            'templates' => [$this->templateA->id],
            'default_template_id' => $this->templateA->id,
        ], $overrides);
    }
}
