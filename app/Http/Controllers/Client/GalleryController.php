<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PhotoboothEvent;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function __construct(private readonly LicenseService $licenseService)
    {
    }

    /**
     * Display a listing of photobooth events for the logged-in user.
     */
    public function index(Request $request)
    {
        $events = PhotoboothEvent::where('user_id', Auth::id())
            ->with(['photos' => function ($query) {
                $query->where('photo_type', 'final')
                    ->latest('uploaded_at');
            }])
            ->withCount('photos')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->latest('event_date')
            ->get();

        return view('client.events.index', compact('events'));
    }

    /**
     * Display the specified photobooth event details.
     */
    public function show(PhotoboothEvent $event)
    {
        if ((int) $event->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this event.');
        }

        $event->loadCount('photos');
        $setting = $event->setting; // hasOne relation

        return view('client.events.show', compact('event', 'setting'));
    }

    /**
     * Display the photo gallery for the specified event.
     */
    public function gallery(PhotoboothEvent $event)
    {
        if ((int) $event->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this event.');
        }

        $photos = $event->photos()
            ->with('boothSession')
            ->latest('uploaded_at')
            ->paginate(24);

        return view('client.events.gallery', compact('event', 'photos'));
    }

    /**
     * Cloud-style event media manager.
     */
    public function manage(PhotoboothEvent $event)
    {
        $this->ensureOwnsEvent($event);

        $event->load(['setting', 'photos.boothSession'])->loadCount('photos');
        $photos = $event->photos()
            ->with('boothSession')
            ->orderByRaw("case when photo_type = 'final' then 0 else 1 end")
            ->latest('uploaded_at')
            ->paginate(30);

        $setting = $event->setting;
        $cloudSettings = $setting->config_json['cloud'] ?? [];
        $license = $this->licenseService->getPublicGalleryEntitlements($event->user);

        return view('client.events.manage', compact('event', 'photos', 'setting', 'cloudSettings', 'license'));
    }

    /**
     * Update cloud gallery settings only. Booth design stays in booth app.
     */
    public function updateCloudSettings(Request $request, PhotoboothEvent $event)
    {
        $this->ensureOwnsEvent($event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'gallery_visibility' => ['required', 'in:private,public'],
            'show_event_date' => ['nullable', 'boolean'],
            'link_sharing_enabled' => ['nullable', 'boolean'],
            'guest_access_enabled' => ['nullable', 'boolean'],
            'download_all_enabled' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        $event->update([
            'name' => $validated['name'],
            'event_date' => $request->input('event_date'),
            'gallery_visibility' => $validated['gallery_visibility'],
        ]);

        $setting = $event->setting()->firstOrCreate(['photobooth_event_id' => $event->id]);
        $config = $setting->config_json ?? [];
        $config['cloud'] = [
            'show_event_date' => $request->boolean('show_event_date'),
            'link_sharing_enabled' => $request->boolean('link_sharing_enabled'),
            'guest_access_enabled' => $request->boolean('guest_access_enabled'),
            'download_all_enabled' => $request->boolean('download_all_enabled'),
            'password' => $request->input('password'),
            'website' => $request->input('website'),
        ];

        $setting->update(['config_json' => $config]);

        return redirect()->route('client.events.manage', $event)->with('success', 'Cloud gallery settings berhasil disimpan.');
    }

    private function ensureOwnsEvent(PhotoboothEvent $event): void
    {
        if ((int) $event->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this event.');
        }
    }
}
