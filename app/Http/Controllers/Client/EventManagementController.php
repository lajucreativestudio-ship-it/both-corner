<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PhotoboothEvent;
use App\Models\PhotoboothTemplate;
use App\Models\TemplateStep;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EventManagementController extends Controller
{
    public function __construct(private readonly LicenseService $licenseService)
    {
    }

    public function create()
    {
        $user = Auth::user();
        $this->seedGlobalTemplatesIfEmpty();

        return view('client.events.create', [
            'templates' => $this->availableTemplatesQuery($user->id)->get(),
            'features' => $this->licenseService->getFeatureFlagsForUser($user),
            'limits' => $this->licenseService->getUsageLimits($user),
            'eventCount' => $user->photoboothEvents()->count(),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $limits = $this->licenseService->getUsageLimits($user);

        if ($limits['max_events'] !== null && $user->photoboothEvents()->count() >= $limits['max_events']) {
            throw ValidationException::withMessages([
                'name' => 'Event limit plan Anda sudah tercapai. Upgrade subscription untuk membuat event baru.',
            ]);
        }

        $validated = $this->validateEventSetup($request);
        $customTemplate = $this->createCustomTemplateIfAllowed($request, $validated, $user->id);
        $selectedTemplateIds = $this->selectedTemplateIds($request, $customTemplate);
        $defaultTemplateId = $this->defaultTemplateId($request, $customTemplate);
        $this->ensureTemplateSelectionIsValid($user->id, $selectedTemplateIds, $defaultTemplateId);

        $slug = $this->uniqueSlug($validated['name'], $user->id);

        $event = PhotoboothEvent::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'event_date' => $request->input('event_date'),
            'location' => $request->input('location'),
            'status' => $validated['status'],
            'gallery_visibility' => $validated['gallery_visibility'],
        ]);

        $this->syncEventSetup($request, $event, $validated, $selectedTemplateIds, $defaultTemplateId);

        return redirect()->route('client.events.show', $event)->with('success', 'Event berhasil dibuat.');
    }

    public function edit(PhotoboothEvent $event)
    {
        $this->ensureOwnsEvent($event);
        $user = Auth::user();
        $event->load(['eventTemplates.template', 'eventCaptureModes', 'setting']);
        $this->seedGlobalTemplatesIfEmpty();

        return view('client.events.edit', [
            'event' => $event,
            'setting' => $event->setting,
            'templates' => $this->availableTemplatesQuery($user->id)->get(),
            'features' => $this->licenseService->getFeatureFlagsForUser($user),
            'limits' => $this->licenseService->getUsageLimits($user),
            'eventCount' => $user->photoboothEvents()->count(),
        ]);
    }

    public function update(Request $request, PhotoboothEvent $event)
    {
        $this->ensureOwnsEvent($event);
        $user = Auth::user();

        $validated = $this->validateEventSetup($request);
        $customTemplate = $this->createCustomTemplateIfAllowed($request, $validated, $user->id);
        $selectedTemplateIds = $this->selectedTemplateIds($request, $customTemplate);
        $defaultTemplateId = $this->defaultTemplateId($request, $customTemplate);
        $this->ensureTemplateSelectionIsValid($user->id, $selectedTemplateIds, $defaultTemplateId);

        $event->update([
            'name' => $validated['name'],
            'event_date' => $request->input('event_date'),
            'location' => $request->input('location'),
            'status' => $validated['status'],
            'gallery_visibility' => $validated['gallery_visibility'],
        ]);

        $this->syncEventSetup($request, $event, $validated, $selectedTemplateIds, $defaultTemplateId);

        return redirect()->route('client.events.edit', $event)->with('success', 'Event berhasil diperbarui.');
    }

    private function validateEventSetup(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,active,completed'],
            'gallery_visibility' => ['required', 'in:private,public'],
            'layout_type' => ['required', 'string', 'max:100'],
            'template_type' => ['required', 'string', 'max:255'],
            'orientation' => ['required', 'in:portrait,landscape'],
            'canvas_width' => ['required', 'integer', 'min:100', 'max:10000'],
            'canvas_height' => ['required', 'integer', 'min:100', 'max:10000'],
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
            'capture_modes' => ['required', 'array', 'min:1'],
            'capture_modes.*' => ['string', 'in:photo,gif,boomerang'],
            'templates' => ['nullable', 'array'],
            'templates.*' => ['exists:photobooth_templates,id'],
            'default_template_id' => ['nullable'],
            'custom_template_name' => ['nullable', 'string', 'max:255'],
            'overlay_file' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'background_file' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);
    }

    private function createCustomTemplateIfAllowed(Request $request, array $validated, int $userId): ?PhotoboothTemplate
    {
        if (!$request->hasFile('overlay_file') && !$request->hasFile('background_file')) {
            return null;
        }

        $user = Auth::user();
        if (!$this->licenseService->canUploadCustomTemplate($user)) {
            throw ValidationException::withMessages([
                'overlay_file' => 'Custom template upload requires subscription.',
            ]);
        }

        $limits = $this->licenseService->getUsageLimits($user);
        $customCount = PhotoboothTemplate::where('user_id', $userId)->count();
        if ($limits['max_templates'] !== null && $customCount >= $limits['max_templates']) {
            throw ValidationException::withMessages([
                'overlay_file' => 'Custom template limit plan Anda sudah tercapai.',
            ]);
        }

        $overlayPath = $request->hasFile('overlay_file')
            ? $request->file('overlay_file')->store('templates', 'public')
            : null;
        $backgroundPath = $request->hasFile('background_file')
            ? $request->file('background_file')->store('templates', 'public')
            : null;

        $timing = $this->timingPayload($validated);
        $template = PhotoboothTemplate::create([
            'user_id' => $userId,
            'name' => $request->input('custom_template_name') ?: $validated['name'] . ' Custom Layout',
            'template_type' => $validated['template_type'],
            'orientation' => $validated['orientation'],
            'canvas_width' => $validated['canvas_width'],
            'canvas_height' => $validated['canvas_height'],
            'capture_count' => $validated['capture_count'],
            'overlay_path' => $overlayPath,
            'background_path' => $backgroundPath,
            'photo_slots_json' => $this->generatePhotoSlots(
                $validated['template_type'],
                (int)$validated['capture_count'],
                (int)$validated['canvas_width'],
                (int)$validated['canvas_height']
            ),
            'timing_json' => $timing,
            'is_global' => false,
            'status' => 'active',
        ]);

        $this->syncTemplateSteps($template);

        return $template;
    }

    private function syncEventSetup(Request $request, PhotoboothEvent $event, array $validated, array $selectedTemplateIds, int $defaultTemplateId): void
    {
        $timing = $this->timingPayload($validated);

        $event->setting()->updateOrCreate(
            ['photobooth_event_id' => $event->id],
            [
                'template_id' => $defaultTemplateId,
                'layout_type' => $validated['layout_type'],
                'countdown_seconds' => $timing['initial_countdown'],
                'capture_count' => $validated['capture_count'],
                'retake_enabled' => $request->boolean('retake_enabled'),
                'print_enabled' => $request->boolean('print_enabled'),
                'watermark_enabled' => $request->boolean('watermark_enabled'),
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

        $event->eventTemplates()->whereNotIn('photobooth_template_id', $selectedTemplateIds)->delete();
        foreach (array_values($selectedTemplateIds) as $index => $templateId) {
            $event->eventTemplates()->updateOrCreate(
                ['photobooth_template_id' => $templateId],
                [
                    'is_default' => $templateId === $defaultTemplateId,
                    'mode_type' => 'photo',
                    'sort_order' => $index,
                    'status' => 'active',
                ]
            );
        }
    }

    private function selectedTemplateIds(Request $request, ?PhotoboothTemplate $customTemplate): array
    {
        $selected = array_map('intval', $request->input('templates', []));
        if ($customTemplate) {
            $selected[] = $customTemplate->id;
        }

        return array_values(array_unique($selected));
    }

    private function defaultTemplateId(Request $request, ?PhotoboothTemplate $customTemplate): int
    {
        if ($request->input('default_template_id') === 'custom' && $customTemplate) {
            return $customTemplate->id;
        }

        return (int)$request->input('default_template_id');
    }

    private function ensureTemplateSelectionIsValid(int $userId, array $selectedTemplateIds, int $defaultTemplateId): void
    {
        if (empty($selectedTemplateIds)) {
            throw ValidationException::withMessages([
                'templates' => 'Pilih minimal satu template/layout.',
            ]);
        }

        if (!in_array($defaultTemplateId, $selectedTemplateIds, true)) {
            throw ValidationException::withMessages([
                'default_template_id' => 'Default template harus termasuk dalam template yang dipilih.',
            ]);
        }

        $validCount = $this->availableTemplatesQuery($userId)
            ->whereIn('id', $selectedTemplateIds)
            ->count();

        if ($validCount !== count($selectedTemplateIds)) {
            throw ValidationException::withMessages([
                'templates' => 'Template harus berasal dari template global atau template milik Anda.',
            ]);
        }
    }

    private function availableTemplatesQuery(int $userId)
    {
        return PhotoboothTemplate::where('status', 'active')
            ->where(function ($query) use ($userId) {
                $query->where('is_global', true)
                    ->orWhereNull('user_id')
                    ->orWhere('user_id', $userId);
            })
            ->orderByDesc('is_global')
            ->orderBy('name');
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

    private function uniqueSlug(string $name, int $userId): string
    {
        $base = Str::slug($name) ?: 'event';
        $slug = $base;
        $counter = 2;

        while (PhotoboothEvent::where('user_id', $userId)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function ensureOwnsEvent(PhotoboothEvent $event): void
    {
        if ((int)$event->user_id !== (int)Auth::id()) {
            abort(403, 'Unauthorized access to this event.');
        }
    }

    private function seedGlobalTemplatesIfEmpty(): void
    {
        if (PhotoboothTemplate::where('is_global', true)->exists()) {
            return;
        }

        PhotoboothTemplate::create([
            'name' => '4x6 Portrait Single',
            'template_type' => 'photo_4x6_portrait',
            'orientation' => 'portrait',
            'canvas_width' => 1200,
            'canvas_height' => 1800,
            'capture_count' => 1,
            'photo_slots_json' => [['slot_number' => 1, 'x' => 50, 'y' => 50, 'width' => 1100, 'height' => 1466]],
            'timing_json' => ['initial_countdown' => 5, 'between_capture_delay' => 2, 'preview_duration' => 3, 'retake_timeout' => 10, 'final_preview_duration' => 8, 'idle_timeout' => 30],
            'is_global' => true,
            'status' => 'active',
        ]);
    }

    private function generatePhotoSlots(string $type, int $count, int $width, int $height): array
    {
        if ($type === 'photo_4x6_portrait' && $count === 1) {
            return [['slot_number' => 1, 'x' => 50, 'y' => 50, 'width' => $width - 100, 'height' => (int)(($width - 100) * 4 / 3)]];
        }

        $slots = [];
        $gap = 20;
        $slotHeight = (int)(($height - 150 - (($count - 1) * $gap)) / $count);
        for ($i = 1; $i <= $count; $i++) {
            $slots[] = [
                'slot_number' => $i,
                'x' => 50,
                'y' => 50 + (($i - 1) * ($slotHeight + $gap)),
                'width' => $width - 100,
                'height' => $slotHeight,
            ];
        }

        return $slots;
    }

    private function syncTemplateSteps(PhotoboothTemplate $template): void
    {
        for ($i = 1; $i <= $template->capture_count; $i++) {
            TemplateStep::updateOrCreate(
                ['photobooth_template_id' => $template->id, 'step_number' => $i],
                [
                    'slot_number' => $i,
                    'countdown_seconds' => (int)($template->timing_json['initial_countdown'] ?? 5),
                    'preview_seconds' => (int)($template->timing_json['preview_duration'] ?? 3),
                    'instruction_text' => "Persiapkan Pose ke-{$i}!",
                ]
            );
        }
    }
}
