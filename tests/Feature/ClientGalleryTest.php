<?php

namespace Tests\Feature;

use App\Models\EventPhoto;
use App\Models\EventSetting;
use App\Models\PhotoboothEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientGalleryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test client can see their list of photobooth events.
     */
    public function test_client_can_view_events_list(): void
    {
        $user = User::factory()->create();
        
        $event1 = PhotoboothEvent::create([
            'user_id' => $user->id,
            'name' => 'Event A',
            'slug' => 'event-a',
            'status' => 'active',
        ]);

        $event2 = PhotoboothEvent::create([
            'user_id' => $user->id,
            'name' => 'Event B',
            'slug' => 'event-b',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->get('/dashboard/events');

        $response->assertStatus(200);
        $response->assertSee('Event A');
        $response->assertSee('Event B');
    }

    /**
     * Test client can view their specific event details.
     */
    public function test_client_can_view_event_details(): void
    {
        $user = User::factory()->create();

        $event = PhotoboothEvent::create([
            'user_id' => $user->id,
            'name' => 'My Private Wedding',
            'slug' => 'my-private-wedding',
            'status' => 'active',
        ]);

        $setting = EventSetting::create([
            'photobooth_event_id' => $event->id,
            'layout_type' => 'strip',
            'countdown_seconds' => 7,
            'capture_count' => 3,
            'retake_enabled' => true,
            'print_enabled' => false,
            'watermark_enabled' => true,
        ]);

        $response = $this->actingAs($user)->get("/dashboard/events/{$event->id}");

        $response->assertStatus(200);
        $response->assertSee('My Private Wedding');
        $response->assertSee('strip');
        $response->assertSee('7 Detik');
        $response->assertSee('3 Kali');
    }

    /**
     * Test client can view their event's photo gallery with pagination.
     */
    public function test_client_can_view_event_gallery(): void
    {
        $user = User::factory()->create();

        $event = PhotoboothEvent::create([
            'user_id' => $user->id,
            'name' => 'My Gallery Event',
            'slug' => 'my-gallery-event',
            'status' => 'active',
        ]);

        // Create a photo
        EventPhoto::create([
            'photobooth_event_id' => $event->id,
            'user_id' => $user->id,
            'file_path' => 'events/' . $event->id . '/photos/photo_test_1.jpg',
            'original_filename' => 'photo_test_1.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 102400,
            'uploaded_at' => now(),
        ]);

        $response = $this->actingAs($user)->get("/dashboard/events/{$event->id}/gallery");

        $response->assertStatus(200);
        $response->assertSee('photo_test_1.jpg');
    }

    public function test_client_can_manage_event_cloud_gallery(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $event = PhotoboothEvent::create([
            'user_id' => $user->id,
            'name' => 'Cloud Managed Event',
            'slug' => 'cloud-managed-event',
            'status' => 'active',
            'gallery_visibility' => 'public',
        ]);

        EventPhoto::create([
            'photobooth_event_id' => $event->id,
            'user_id' => $user->id,
            'file_path' => 'events/' . $event->id . '/photos/final_photo.jpg',
            'photo_type' => 'final',
            'original_filename' => 'final_photo.jpg',
            'mime_type' => 'image/jpeg',
            'uploaded_at' => now(),
        ]);

        $response = $this->actingAs($user)->get("/dashboard/events/{$event->id}/manage");

        $response->assertStatus(200);
        $response->assertSee('Cloud Managed Event');
        $response->assertSee('Gallery Settings');
        $response->assertSee('Media Manager');
        $response->assertSee('/e/cloud-managed-event');
        $response->assertSee('Configure design in Booth App');
        $response->assertSee('final_photo.jpg');
    }

    public function test_client_can_update_cloud_gallery_settings(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $event = PhotoboothEvent::create([
            'user_id' => $user->id,
            'name' => 'Original Cloud Event',
            'slug' => 'original-cloud-event',
            'status' => 'active',
            'gallery_visibility' => 'private',
        ]);

        $response = $this->actingAs($user)->put("/dashboard/events/{$event->id}/cloud-settings", [
            'name' => 'Updated Cloud Event',
            'event_date' => '2026-07-10',
            'gallery_visibility' => 'public',
            'show_event_date' => '1',
            'link_sharing_enabled' => '1',
            'guest_access_enabled' => '1',
            'download_all_enabled' => '1',
            'password' => 'guest123',
            'website' => 'https://example.com',
        ]);

        $response->assertRedirect("/dashboard/events/{$event->id}/manage");
        $this->assertDatabaseHas('photobooth_events', [
            'id' => $event->id,
            'name' => 'Updated Cloud Event',
            'gallery_visibility' => 'public',
        ]);

        $cloudSettings = $event->setting()->first()->config_json['cloud'];
        $this->assertTrue($cloudSettings['download_all_enabled']);
        $this->assertSame('guest123', $cloudSettings['password']);
        $this->assertSame('https://example.com', $cloudSettings['website']);
    }

    /**
     * Test client cannot access another user's events.
     */
    public function test_client_cannot_access_other_users_event(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $eventB = PhotoboothEvent::create([
            'user_id' => $userB->id,
            'name' => 'User B Private Event',
            'slug' => 'user-b-private-event',
            'status' => 'active',
        ]);

        // Accessing other user's event detail
        $responseDetail = $this->actingAs($userA)->get("/dashboard/events/{$eventB->id}");
        $responseDetail->assertStatus(403);

        // Accessing other user's event gallery
        $responseGallery = $this->actingAs($userA)->get("/dashboard/events/{$eventB->id}/gallery");
        $responseGallery->assertStatus(403);

        $responseManage = $this->actingAs($userA)->get("/dashboard/events/{$eventB->id}/manage");
        $responseManage->assertStatus(403);

        $responseSettings = $this->actingAs($userA)->put("/dashboard/events/{$eventB->id}/cloud-settings", [
            'name' => 'Nope',
            'gallery_visibility' => 'public',
        ]);
        $responseSettings->assertStatus(403);
    }
}
