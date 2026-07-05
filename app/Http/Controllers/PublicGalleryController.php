<?php

namespace App\Http\Controllers;

use App\Models\BoothSession;
use App\Models\PhotoboothEvent;
use Illuminate\Http\Request;

class PublicGalleryController extends Controller
{
    /**
     * Display a public grid of final event photos.
     */
    public function eventGallery(string $slug)
    {
        $event = PhotoboothEvent::where('slug', $slug)->firstOrFail();

        if ($event->gallery_visibility === 'private') {
            abort(403, 'Galeri ini bersifat privat.');
        }

        $photos = $event->photos()
            ->where(function ($query) {
                $query->where('photo_type', 'final')
                      ->orWhereNull('photo_type');
            })
            ->where('public_visibility', 'visible')
            ->with('boothSession')
            ->latest()
            ->paginate(24);

        $licenseService = app(\App\Services\LicenseService::class);
        $showAds = $licenseService->shouldShowAds($event->user);
        $showWatermark = $licenseService->shouldShowWatermark($event->user);

        return view('public.event-gallery', compact('event', 'photos', 'showAds', 'showWatermark'));
    }

    /**
     * Display the result page for a specific booth capture session.
     */
    public function sessionResult(string $publicToken)
    {
        $session = BoothSession::where('public_token', $publicToken)
            ->with(['event.user', 'photos'])
            ->firstOrFail();

        $event = $session->event;

        $finalPhotos = $session->photos->filter(function ($p) {
            return $p->photo_type === 'final' || is_null($p->photo_type);
        });

        $rawPhotos = $session->photos->filter(function ($p) {
            return $p->photo_type === 'raw';
        });

        $licenseService = app(\App\Services\LicenseService::class);
        $showAds = $licenseService->shouldShowAds($event->user);
        $showWatermark = $licenseService->shouldShowWatermark($event->user);

        return view('public.session-result', compact('session', 'event', 'finalPhotos', 'rawPhotos', 'showAds', 'showWatermark'));
    }
}
