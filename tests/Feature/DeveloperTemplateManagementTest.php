<?php

namespace Tests\Feature;

use App\Models\PhotoboothEvent;
use App\Models\PhotoboothTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeveloperTemplateManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $clientUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed users
        $this->adminUser = User::factory()->create([
            'email' => 'developer@bothcorner.com',
            'role' => 'admin',
        ]);

        $this->clientUser = User::factory()->create([
            'role' => 'user',
        ]);
    }

    /**
     * Test admin can access templates index.
     */
    public function test_admin_can_access_templates_index(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/developer/templates');
        
        $response->assertStatus(200);
        $response->assertSee('4x6 Portrait Single'); // seeded automatically by self-healing logic
        $response->assertSee('Global Template Library');
    }

    /**
     * Test non-admin user is rejected from accessing templates.
     */
    public function test_non_admin_cannot_access_templates(): void
    {
        $response = $this->actingAs($this->clientUser)->get('/developer/templates');
        $response->assertStatus(403);
    }

    /**
     * Test admin can store template.
     */
    public function test_admin_can_create_template_with_timing_and_slots(): void
    {
        Storage::fake('public');

        $overlay = UploadedFile::fake()->image('test_overlay.png');
        $background = UploadedFile::fake()->image('test_bg.jpg');

        $response = $this->actingAs($this->adminUser)->post('/developer/templates', [
            'name' => 'Custom Birthday Strip',
            'template_type' => 'strip_2x6',
            'orientation' => 'portrait',
            'canvas_width' => 600,
            'canvas_height' => 1800,
            'capture_count' => 3,
            'overlay_file' => $overlay,
            'background_file' => $background,
            'initial_countdown' => 7,
            'between_capture_delay' => 3,
            'preview_duration' => 4,
            'retake_timeout' => 12,
            'final_preview_duration' => 10,
            'idle_timeout' => 40,
            'is_global' => '1',
            'status' => 'active',
        ]);

        $response->assertRedirect('/developer/templates');

        $template = PhotoboothTemplate::where('name', 'Custom Birthday Strip')->first();
        $this->assertNotNull($template);
        $this->assertTrue($template->is_global);

        // Verify files in storage
        Storage::disk('public')->assertExists($template->overlay_path);
        Storage::disk('public')->assertExists($template->background_path);

        // Verify photo slots count
        $this->assertCount(3, $template->photo_slots_json);

        // Verify timings
        $this->assertEquals(7, $template->timing_json['initial_countdown']);
        $this->assertEquals(40, $template->timing_json['idle_timeout']);

        // Verify steps auto generation
        $this->assertDatabaseHas('template_steps', [
            'photobooth_template_id' => $template->id,
            'step_number' => 1,
            'countdown_seconds' => 7,
        ]);
        $this->assertDatabaseHas('template_steps', [
            'photobooth_template_id' => $template->id,
            'step_number' => 3,
        ]);
    }

    /**
     * Template management remains a global master preset library, not event setup UI.
     */
    public function test_template_management_is_global_master_preset_library(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Original Gala Event',
            'slug' => 'original-gala-event',
            'status' => 'active',
        ]);

        $template1 = PhotoboothTemplate::create([
            'name' => 'T1',
            'template_type' => 'classic',
            'canvas_width' => 1200,
            'canvas_height' => 1800,
            'capture_count' => 1,
            'is_global' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->get('/developer/templates');

        $response->assertStatus(200);
        $response->assertSee('Global Template Library');
        $response->assertSee('Master Template Presets');
        $response->assertSee('T1');

        $this->actingAs($this->adminUser)->put("/developer/events/{$event->id}", [])->assertStatus(404);
    }
}
