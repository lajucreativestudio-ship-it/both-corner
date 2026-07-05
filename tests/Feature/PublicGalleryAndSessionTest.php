<?php

namespace Tests\Feature;

use App\Models\BoothSession;
use App\Models\ClientDevice;
use App\Models\EventPhoto;
use App\Models\PhotoboothEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicGalleryAndSessionTest extends TestCase
{
    use RefreshDatabase;

    protected User $clientUser;
    protected ClientDevice $device;
    protected string $rawToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientUser = User::factory()->create(['role' => 'user']);
        $this->rawToken = 'dev_tok_session_123';

        $this->device = ClientDevice::create([
            'user_id' => $this->clientUser->id,
            'device_name' => 'iPad Booth',
            'pairing_code' => 'PAIR99',
            'platform' => 'ios',
            'api_token_hash' => hash('sha256', $this->rawToken),
            'paired_at' => now(),
        ]);
    }

    /**
     * Test upload photo without session_code (backward compatibility check).
     */
    public function test_upload_photo_without_session_code_is_compatible(): void
    {
        Storage::fake('public');

        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Compatible Event',
            'slug' => 'compatible-event',
            'status' => 'active',
        ]);
        $this->device->update(['current_event_id' => $event->id]);

        $file = UploadedFile::fake()->image('pic.jpg');

        $response = $this->postJson("/api/v1/events/{$event->id}/photos", [
            'photo_file' => $file,
        ], [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        
        $this->assertDatabaseHas('event_photos', [
            'photobooth_event_id' => $event->id,
            'photo_type' => 'final',
            'booth_session_id' => null,
        ]);
    }

    /**
     * Test uploading photos with session_code creates session and maps correctly.
     */
    public function test_upload_photo_with_session_code_creates_booth_session(): void
    {
        Storage::fake('public');

        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Boothing Event',
            'slug' => 'boothing-event',
            'status' => 'active',
        ]);
        $this->device->update(['current_event_id' => $event->id]);

        // 1. Upload raw snap photo
        $file1 = UploadedFile::fake()->image('raw1.jpg');
        $response1 = $this->postJson("/api/v1/events/{$event->id}/photos", [
            'photo_file' => $file1,
            'session_code' => 'sess_xyz_123',
            'photo_type' => 'raw',
            'step_number' => 1,
        ], [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response1->assertStatus(200);
        $response1->assertJsonPath('success', true);
        $this->assertNotNull($response1->json('session_public_url'));

        // Verify BoothSession is created
        $session = BoothSession::where('session_code', 'sess_xyz_123')->first();
        $this->assertNotNull($session);
        $this->assertEquals($event->id, $session->photobooth_event_id);

        $this->assertDatabaseHas('event_photos', [
            'photobooth_event_id' => $event->id,
            'booth_session_id' => $session->id,
            'photo_type' => 'raw',
            'step_number' => 1,
        ]);

        // 2. Upload final templated photo to the same session
        $file2 = UploadedFile::fake()->image('final_output.jpg');
        $response2 = $this->postJson("/api/v1/events/{$event->id}/photos", [
            'photo_file' => $file2,
            'session_code' => 'sess_xyz_123',
            'photo_type' => 'final',
        ], [
            'Authorization' => 'Bearer ' . $this->rawToken,
        ]);

        $response2->assertStatus(200);
        
        $this->assertDatabaseHas('event_photos', [
            'photobooth_event_id' => $event->id,
            'booth_session_id' => $session->id,
            'photo_type' => 'final',
        ]);

        // Check overall counts
        $this->assertEquals(2, $session->photos()->count());
    }

    /**
     * Test public event gallery visibility rules.
     */
    public function test_public_event_gallery_page(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Public Wedding Gown',
            'slug' => 'public-wedding-gown',
            'status' => 'active',
            'gallery_visibility' => 'public',
        ]);

        // Access public gallery
        $response = $this->get("/e/{$event->slug}");
        $response->assertStatus(200);
        $response->assertSee('Public Wedding Gown');

        // Make it private
        $event->update(['gallery_visibility' => 'private']);

        $responsePrivate = $this->get("/e/{$event->slug}");
        $responsePrivate->assertStatus(403);
    }

    /**
     * Test public session landing page shows both raw and final snaps.
     */
    public function test_public_session_landing_page(): void
    {
        $event = PhotoboothEvent::create([
            'user_id' => $this->clientUser->id,
            'name' => 'Booth Fun Event',
            'slug' => 'booth-fun-event',
            'status' => 'active',
        ]);

        $session = BoothSession::create([
            'photobooth_event_id' => $event->id,
            'session_code' => 'sess_999_888',
            'public_token' => 'pub_token_abcdef',
            'status' => 'completed',
        ]);

        // Create one final photo and one raw photo linked to session
        EventPhoto::create([
            'photobooth_event_id' => $event->id,
            'user_id' => $this->clientUser->id,
            'booth_session_id' => $session->id,
            'file_path' => 'photos/final.jpg',
            'photo_type' => 'final',
            'uploaded_at' => now(),
        ]);

        EventPhoto::create([
            'photobooth_event_id' => $event->id,
            'user_id' => $this->clientUser->id,
            'booth_session_id' => $session->id,
            'file_path' => 'photos/raw1.jpg',
            'photo_type' => 'raw',
            'step_number' => 1,
            'uploaded_at' => now(),
        ]);

        $response = $this->get("/s/{$session->public_token}");
        $response->assertStatus(200);
        $response->assertSee('sess_999_888');
        $response->assertSee('Booth Fun Event');
        $response->assertSee('final.jpg');
        $response->assertSee('raw1.jpg');
    }
}
