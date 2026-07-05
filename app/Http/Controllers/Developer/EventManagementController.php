<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ClientDevice;
use App\Models\EventSetting;
use App\Models\PhotoboothTemplate;
use App\Models\PhotoboothEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class EventManagementController extends Controller
{
    /**
     * Enforce developer/admin role checks.
     */
    private function ensureDeveloperAccess(): void
    {
        if (!auth()->check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if ($user->email === 'developer@bothcorner.com') {
            return;
        }

        if (in_array($user->role, ['admin', 'team'], true)) {
            return;
        }

        if ($user->customRole && in_array('view_summary', $user->customRole->permissions ?? [], true)) {
            return;
        }

        abort(403, 'Akses ditolak.');
    }

    /**
     * Display a listing of all photobooth events.
     */
    public function index()
    {
        $this->ensureDeveloperAccess();

        $events = PhotoboothEvent::with('user')
            ->withCount('photos')
            ->latest('event_date')
            ->get();

        // Efficiently aggregate assigned devices count for each event
        $deviceCounts = ClientDevice::selectRaw('current_event_id, count(*) as count')
            ->whereNotNull('current_event_id')
            ->groupBy('current_event_id')
            ->pluck('count', 'current_event_id');

        return view('developer.events.index', compact('events', 'deviceCounts'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $this->ensureDeveloperAccess();

        // Show client / non-admin users
        $users = User::where('role', '!=', 'admin')->get();
        $templates = $this->globalTemplatesQuery()->get();

        return view('developer.events.create', compact('users', 'templates'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,active,completed'],
            'gallery_visibility' => ['required', 'in:private,public'],
            'layout_type' => ['required', 'string', 'max:100'],
            'capture_count' => ['required', 'integer', 'min:1', 'max:10'],
            'initial_countdown' => ['required', 'integer', 'min:1', 'max:60'],
            'between_capture_delay' => ['required', 'integer', 'min:0', 'max:60'],
            'preview_duration' => ['required', 'integer', 'min:1', 'max:120'],
            'retake_timeout' => ['required', 'integer', 'min:1', 'max:300'],
            'final_preview_duration' => ['required', 'integer', 'min:1', 'max:300'],
            'idle_timeout' => ['required', 'integer', 'min:5', 'max:1800'],
            'retake_enabled' => ['nullable', 'boolean'],
            'print_enabled' => ['nullable', 'boolean'],
            'watermark_enabled' => ['nullable', 'boolean'],
            'templates' => ['required', 'array', 'min:1'],
            'templates.*' => ['exists:photobooth_templates,id'],
            'default_template_id' => ['required', 'exists:photobooth_templates,id'],
            'capture_modes' => ['required', 'array', 'min:1'],
            'capture_modes.*' => ['string', 'in:photo,gif,boomerang'],
        ]);

        $this->ensureDefaultTemplateIsSelected($request);
        $this->ensureSelectedTemplatesAreGlobal($request);

        $slug = $validated['slug'] ?: Str::slug($validated['name']);

        // Check if slug is unique for this client owner
        $exists = PhotoboothEvent::where('user_id', $validated['user_id'])
            ->where('slug', $slug)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'slug' => 'Slug sudah digunakan untuk klien ini. Gunakan nama atau slug lain.',
            ]);
        }

        $event = PhotoboothEvent::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'event_date' => $request->input('event_date'),
            'location' => $request->input('location'),
            'status' => $validated['status'],
            'gallery_visibility' => $validated['gallery_visibility'],
        ]);

        $this->syncEventSetup($request, $event, $validated);

        return redirect()->route('developer.events.edit', $event)->with('success', 'Event berhasil dibuat beserta template, mode, dan timing.');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(PhotoboothEvent $event)
    {
        $this->ensureDeveloperAccess();

        $event->load(['user', 'eventTemplates.template', 'eventCaptureModes']);
        $setting = $event->setting;

        $users = User::where('role', '!=', 'admin')->get();

        // Fetch active/non-revoked devices to assign
        $devices = ClientDevice::whereNull('revoked_at')->with('currentEvent')->get();

        // Fetch active global templates from the master preset library.
        $templates = $this->globalTemplatesQuery()->get();

        return view('developer.events.edit', compact('event', 'setting', 'users', 'devices', 'templates'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, PhotoboothEvent $event)
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,active,completed'],
            'gallery_visibility' => ['required', 'in:private,public'],

            // settings validation
            'layout_type' => ['required', 'string', 'max:100'],
            'capture_count' => ['required', 'integer', 'min:1', 'max:10'],
            'initial_countdown' => ['required', 'integer', 'min:1', 'max:60'],
            'between_capture_delay' => ['required', 'integer', 'min:0', 'max:60'],
            'preview_duration' => ['required', 'integer', 'min:1', 'max:120'],
            'retake_timeout' => ['required', 'integer', 'min:1', 'max:300'],
            'final_preview_duration' => ['required', 'integer', 'min:1', 'max:300'],
            'idle_timeout' => ['required', 'integer', 'min:5', 'max:1800'],
            'overlay_path' => ['nullable', 'string', 'max:255'],
            'background_path' => ['nullable', 'string', 'max:255'],

            // templates & modes validation
            'templates' => ['required', 'array', 'min:1'],
            'templates.*' => ['exists:photobooth_templates,id'],
            'default_template_id' => ['required', 'exists:photobooth_templates,id'],
            'capture_modes' => ['required', 'array', 'min:1'],
            'capture_modes.*' => ['string', 'in:photo,gif,boomerang'],
        ]);

        $this->ensureDefaultTemplateIsSelected($request);
        $this->ensureSelectedTemplatesAreGlobal($request);

        $slug = $validated['slug'] ?: Str::slug($validated['name']);

        // Check if slug is unique for this client owner (ignoring current event)
        $exists = PhotoboothEvent::where('user_id', $validated['user_id'])
            ->where('slug', $slug)
            ->where('id', '!=', $event->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'slug' => 'Slug sudah digunakan untuk klien ini. Gunakan nama atau slug lain.',
            ]);
        }

        $event->update([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'event_date' => $request->input('event_date'),
            'location' => $request->input('location'),
            'status' => $validated['status'],
            'gallery_visibility' => $validated['gallery_visibility'],
        ]);

        $this->syncEventSetup($request, $event, $validated);

        return redirect()->route('developer.events.edit', $event)->with('success', 'Detail event, capture modes, dan template berhasil diperbarui.');
    }

    private function syncEventSetup(Request $request, PhotoboothEvent $event, array $validated): void
    {
        $timing = $this->timingPayload($validated);

        $event->setting()->updateOrCreate(
            ['photobooth_event_id' => $event->id],
            [
                'layout_type' => $validated['layout_type'],
                'countdown_seconds' => $timing['initial_countdown'],
                'capture_count' => $validated['capture_count'],
                'retake_enabled' => $request->boolean('retake_enabled'),
                'print_enabled' => $request->boolean('print_enabled'),
                'watermark_enabled' => $request->boolean('watermark_enabled'),
                'overlay_path' => $request->input('overlay_path'),
                'background_path' => $request->input('background_path'),
                'template_id' => $request->input('default_template_id'),
                'config_json' => [
                    'timing' => $timing,
                    'sharing' => [
                        'gallery_visibility' => $validated['gallery_visibility'],
                    ],
                ],
            ]
        );

        $captureModes = $request->input('capture_modes', []);
        foreach (['photo', 'gif', 'boomerang', 'video'] as $index => $mode) {
            $event->eventCaptureModes()->updateOrCreate(
                ['mode_type' => $mode],
                [
                    'is_enabled' => $mode !== 'video' && in_array($mode, $captureModes, true),
                    'sort_order' => $index,
                ]
            );
        }

        $selectedTemplates = array_map('intval', $request->input('templates', []));
        $event->eventTemplates()->whereNotIn('photobooth_template_id', $selectedTemplates)->delete();

        foreach (array_values($selectedTemplates) as $index => $templateId) {
            $event->eventTemplates()->updateOrCreate(
                ['photobooth_template_id' => $templateId],
                [
                    'is_default' => $templateId === (int)$request->input('default_template_id'),
                    'mode_type' => 'photo',
                    'sort_order' => $index,
                    'status' => 'active',
                ]
            );
        }
    }

    private function timingPayload(array $validated): array
    {
        return [
            'initial_countdown' => (int)$validated['initial_countdown'],
            'between_capture_delay' => (int)$validated['between_capture_delay'],
            'preview_duration' => (int)$validated['preview_duration'],
            'retake_timeout' => (int)$validated['retake_timeout'],
            'final_preview_duration' => (int)$validated['final_preview_duration'],
            'idle_timeout' => (int)$validated['idle_timeout'],
        ];
    }

    private function ensureDefaultTemplateIsSelected(Request $request): void
    {
        $selectedTemplates = array_map('intval', $request->input('templates', []));
        $defaultTemplateId = (int)$request->input('default_template_id');

        if (!in_array($defaultTemplateId, $selectedTemplates, true)) {
            throw ValidationException::withMessages([
                'default_template_id' => 'Default template harus termasuk dalam template yang dipilih.',
            ]);
        }
    }

    private function ensureSelectedTemplatesAreGlobal(Request $request): void
    {
        $selectedTemplates = array_map('intval', $request->input('templates', []));
        $globalTemplateCount = $this->globalTemplatesQuery()
            ->whereIn('id', $selectedTemplates)
            ->count();

        if (count($selectedTemplates) !== $globalTemplateCount) {
            throw ValidationException::withMessages([
                'templates' => 'Template event harus dipilih dari Global Template Library yang aktif.',
            ]);
        }
    }

    private function globalTemplatesQuery()
    {
        return PhotoboothTemplate::where('status', 'active')
            ->where(function ($query) {
                $query->where('is_global', true)
                    ->orWhereNull('user_id');
            })
            ->orderBy('name');
    }

    /**
     * Assign a device to this event.
     */
    public function assignDevice(Request $request, PhotoboothEvent $event)
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'device_id' => ['required', 'exists:client_devices,id'],
        ]);

        $device = ClientDevice::findOrFail($validated['device_id']);

        if ($device->isRevoked()) {
            return back()->withErrors(['device_id' => 'Device ini telah di-revoke dan tidak dapat di-assign.']);
        }

        $device->update([
            'current_event_id' => $event->id,
        ]);

        return redirect()->route('developer.events.manage', $event)->with('success', "Device {$device->device_name} berhasil di-assign ke event.");
    }

    /**
     * Unassign a device from this event.
     */
    public function unassignDevice(Request $request, PhotoboothEvent $event)
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'device_id' => ['required', 'exists:client_devices,id'],
        ]);

        $device = ClientDevice::findOrFail($validated['device_id']);

        if ((int)$device->current_event_id === (int)$event->id) {
            $device->update([
                'current_event_id' => null,
            ]);
        }

        return redirect()->route('developer.events.manage', $event)->with('success', "Device {$device->device_name} dilepas dari event.");
    }

    /**
     * Manage the experience for the specified event.
     */
    public function manage(PhotoboothEvent $event)
    {
        $this->ensureDeveloperAccess();

        $event->load([
            'user',
            'setting.template',
            'eventCaptureModes',
            'eventTemplates.template.steps',
            'boothSessions' => function ($query) {
                $query->latest()->take(10);
            },
            'boothSessions.template',
            'boothSessions.photos',
            'photos' => function ($query) {
                $query->latest()->take(12);
            },
            'photos.boothSession',
        ]);

        $totalPhotosCount = $event->photos()->count();
        $totalSessionsCount = $event->boothSessions()->count();
        $totalFinalPhotosCount = $event->photos()->where('photo_type', 'final')->count();
        $totalRawPhotosCount = $event->photos()->where('photo_type', 'raw')->count();

        // Fetch assigned devices
        $assignedDevices = ClientDevice::where('current_event_id', $event->id)
            ->whereNull('revoked_at')
            ->get();

        return view('developer.events.manage', compact(
            'event',
            'totalPhotosCount',
            'totalSessionsCount',
            'totalFinalPhotosCount',
            'totalRawPhotosCount',
            'assignedDevices'
        ));
    }
}
