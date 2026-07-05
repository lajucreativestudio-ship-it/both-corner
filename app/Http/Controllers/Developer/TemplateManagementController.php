<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\PhotoboothTemplate;
use App\Models\TemplateStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateManagementController extends Controller
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
     * Automatically seed presets if empty to keep environment self-healing.
     */
    private function seedPresetsIfEmpty(): void
    {
        if (PhotoboothTemplate::where('is_global', true)->count() === 0) {
            $presets = [
                [
                    'name' => '4x6 Portrait Single',
                    'template_type' => 'photo_4x6_portrait',
                    'orientation' => 'portrait',
                    'canvas_width' => 1200,
                    'canvas_height' => 1800,
                    'capture_count' => 1,
                    'is_global' => true,
                    'status' => 'active',
                    'timing_json' => [
                        'initial_countdown' => 5,
                        'between_capture_delay' => 2,
                        'preview_duration' => 3,
                        'retake_timeout' => 10,
                        'final_preview_duration' => 8,
                        'idle_timeout' => 30
                    ],
                    'photo_slots_json' => [
                        ['slot_number' => 1, 'x' => 50, 'y' => 50, 'width' => 1100, 'height' => 1466]
                    ]
                ],
                [
                    'name' => '4x6 Portrait 3 Photos',
                    'template_type' => 'photo_4x6_portrait',
                    'orientation' => 'portrait',
                    'canvas_width' => 1200,
                    'canvas_height' => 1800,
                    'capture_count' => 3,
                    'is_global' => true,
                    'status' => 'active',
                    'timing_json' => [
                        'initial_countdown' => 5,
                        'between_capture_delay' => 2,
                        'preview_duration' => 3,
                        'retake_timeout' => 10,
                        'final_preview_duration' => 8,
                        'idle_timeout' => 30
                    ],
                    'photo_slots_json' => [
                        ['slot_number' => 1, 'x' => 100, 'y' => 80, 'width' => 1000, 'height' => 450],
                        ['slot_number' => 2, 'x' => 100, 'y' => 580, 'width' => 1000, 'height' => 450],
                        ['slot_number' => 3, 'x' => 100, 'y' => 1080, 'width' => 1000, 'height' => 450]
                    ]
                ],
                [
                    'name' => '2x6 Strip 3 Photos',
                    'template_type' => 'strip_2x6',
                    'orientation' => 'portrait',
                    'canvas_width' => 600,
                    'canvas_height' => 1800,
                    'capture_count' => 3,
                    'is_global' => true,
                    'status' => 'active',
                    'timing_json' => [
                        'initial_countdown' => 5,
                        'between_capture_delay' => 2,
                        'preview_duration' => 3,
                        'retake_timeout' => 10,
                        'final_preview_duration' => 8,
                        'idle_timeout' => 30
                    ],
                    'photo_slots_json' => [
                        ['slot_number' => 1, 'x' => 50, 'y' => 80, 'width' => 500, 'height' => 375],
                        ['slot_number' => 2, 'x' => 50, 'y' => 500, 'width' => 500, 'height' => 375],
                        ['slot_number' => 3, 'x' => 50, 'y' => 920, 'width' => 500, 'height' => 375]
                    ]
                ],
                [
                    'name' => '2x6 Strip 4 Photos',
                    'template_type' => 'strip_2x6',
                    'orientation' => 'portrait',
                    'canvas_width' => 600,
                    'canvas_height' => 1800,
                    'capture_count' => 4,
                    'is_global' => true,
                    'status' => 'active',
                    'timing_json' => [
                        'initial_countdown' => 5,
                        'between_capture_delay' => 2,
                        'preview_duration' => 3,
                        'retake_timeout' => 10,
                        'final_preview_duration' => 8,
                        'idle_timeout' => 30
                    ],
                    'photo_slots_json' => [
                        ['slot_number' => 1, 'x' => 50, 'y' => 60, 'width' => 500, 'height' => 333],
                        ['slot_number' => 2, 'x' => 50, 'y' => 430, 'width' => 500, 'height' => 333],
                        ['slot_number' => 3, 'x' => 50, 'y' => 800, 'width' => 500, 'height' => 333],
                        ['slot_number' => 4, 'x' => 50, 'y' => 1170, 'width' => 500, 'height' => 333]
                    ]
                ]
            ];

            foreach ($presets as $preset) {
                PhotoboothTemplate::create($preset);
            }
        }
    }

    /**
     * Display a listing of templates.
     */
    public function index()
    {
        $this->ensureDeveloperAccess();
        $this->seedPresetsIfEmpty();

        $templates = PhotoboothTemplate::with('user')->latest()->get();

        return view('developer.templates.index', compact('templates'));
    }

    /**
     * Show form to create new template.
     */
    public function create()
    {
        $this->ensureDeveloperAccess();

        return view('developer.templates.create');
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'template_type' => ['required', 'string', 'max:255'],
            'orientation' => ['required', 'in:portrait,landscape'],
            'canvas_width' => ['required', 'integer', 'min:100', 'max:10000'],
            'canvas_height' => ['required', 'integer', 'min:100', 'max:10000'],
            'capture_count' => ['required', 'integer', 'min:1', 'max:10'],
            'overlay_file' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'background_file' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'initial_countdown' => ['required', 'integer', 'min:1', 'max:60'],
            'between_capture_delay' => ['required', 'integer', 'min:0', 'max:60'],
            'preview_duration' => ['required', 'integer', 'min:0', 'max:60'],
            'retake_timeout' => ['required', 'integer', 'min:0', 'max:120'],
            'final_preview_duration' => ['required', 'integer', 'min:0', 'max:120'],
            'idle_timeout' => ['required', 'integer', 'min:1', 'max:3600'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $overlayPath = null;
        if ($request->hasFile('overlay_file')) {
            $overlayPath = $request->file('overlay_file')->store('templates', 'public');
        }

        $backgroundPath = null;
        if ($request->hasFile('background_file')) {
            $backgroundPath = $request->file('background_file')->store('templates', 'public');
        }

        // Auto-generate photo slots based on presets/dimensions
        $slots = $this->generatePhotoSlots(
            $validated['template_type'],
            $validated['capture_count'],
            $validated['canvas_width'],
            $validated['canvas_height']
        );

        $timing = [
            'initial_countdown' => intval($validated['initial_countdown']),
            'between_capture_delay' => intval($validated['between_capture_delay']),
            'preview_duration' => intval($validated['preview_duration']),
            'retake_timeout' => intval($validated['retake_timeout']),
            'final_preview_duration' => intval($validated['final_preview_duration']),
            'idle_timeout' => intval($validated['idle_timeout']),
        ];

        $template = PhotoboothTemplate::create([
            'user_id' => null, // created by admin
            'name' => $validated['name'],
            'template_type' => $validated['template_type'],
            'orientation' => $validated['orientation'],
            'canvas_width' => $validated['canvas_width'],
            'canvas_height' => $validated['canvas_height'],
            'capture_count' => $validated['capture_count'],
            'overlay_path' => $overlayPath,
            'background_path' => $backgroundPath,
            'photo_slots_json' => $slots,
            'timing_json' => $timing,
            'is_global' => $request->has('is_global'),
            'status' => $validated['status'],
        ]);

        // Generate template steps automatically
        $this->syncTemplateSteps($template);

        return redirect()->route('developer.templates.index')->with('success', 'Template baru berhasil dibuat.');
    }

    /**
     * Show edit form.
     */
    public function edit(PhotoboothTemplate $template)
    {
        $this->ensureDeveloperAccess();

        return view('developer.templates.edit', compact('template'));
    }

    /**
     * Update an existing template.
     */
    public function update(Request $request, PhotoboothTemplate $template)
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'template_type' => ['required', 'string', 'max:255'],
            'orientation' => ['required', 'in:portrait,landscape'],
            'canvas_width' => ['required', 'integer', 'min:100', 'max:10000'],
            'canvas_height' => ['required', 'integer', 'min:100', 'max:10000'],
            'capture_count' => ['required', 'integer', 'min:1', 'max:10'],
            'overlay_file' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'background_file' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'initial_countdown' => ['required', 'integer', 'min:1', 'max:60'],
            'between_capture_delay' => ['required', 'integer', 'min:0', 'max:60'],
            'preview_duration' => ['required', 'integer', 'min:0', 'max:60'],
            'retake_timeout' => ['required', 'integer', 'min:0', 'max:120'],
            'final_preview_duration' => ['required', 'integer', 'min:0', 'max:120'],
            'idle_timeout' => ['required', 'integer', 'min:1', 'max:3600'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $overlayPath = $template->overlay_path;
        if ($request->hasFile('overlay_file')) {
            $overlayPath = $request->file('overlay_file')->store('templates', 'public');
        }

        $backgroundPath = $template->background_path;
        if ($request->hasFile('background_file')) {
            $backgroundPath = $request->file('background_file')->store('templates', 'public');
        }

        $slots = $this->generatePhotoSlots(
            $validated['template_type'],
            $validated['capture_count'],
            $validated['canvas_width'],
            $validated['canvas_height']
        );

        $timing = [
            'initial_countdown' => intval($validated['initial_countdown']),
            'between_capture_delay' => intval($validated['between_capture_delay']),
            'preview_duration' => intval($validated['preview_duration']),
            'retake_timeout' => intval($validated['retake_timeout']),
            'final_preview_duration' => intval($validated['final_preview_duration']),
            'idle_timeout' => intval($validated['idle_timeout']),
        ];

        $template->update([
            'name' => $validated['name'],
            'template_type' => $validated['template_type'],
            'orientation' => $validated['orientation'],
            'canvas_width' => $validated['canvas_width'],
            'canvas_height' => $validated['canvas_height'],
            'capture_count' => $validated['capture_count'],
            'overlay_path' => $overlayPath,
            'background_path' => $backgroundPath,
            'photo_slots_json' => $slots,
            'timing_json' => $timing,
            'is_global' => $request->has('is_global'),
            'status' => $validated['status'],
        ]);

        $this->syncTemplateSteps($template);

        return redirect()->route('developer.templates.index')->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Generate layout grid configuration.
     */
    private function generatePhotoSlots(string $type, int $count, int $width, int $height): array
    {
        if ($type === 'photo_4x6_portrait' && $count === 1) {
            return [['slot_number' => 1, 'x' => 50, 'y' => 50, 'width' => $width - 100, 'height' => intval(($width - 100) * 4 / 3)]];
        }

        if ($type === 'photo_4x6_portrait' && $count === 3) {
            $slotHeight = intval(($height - 200) / 3);
            return [
                ['slot_number' => 1, 'x' => 100, 'y' => 80, 'width' => $width - 200, 'height' => $slotHeight],
                ['slot_number' => 2, 'x' => 100, 'y' => 80 + $slotHeight + 50, 'width' => $width - 200, 'height' => $slotHeight],
                ['slot_number' => 3, 'x' => 100, 'y' => 80 + ($slotHeight * 2) + 100, 'width' => $width - 200, 'height' => $slotHeight]
            ];
        }

        if ($type === 'strip_2x6' && $count === 3) {
            $slotHeight = intval(($height - 200) / 3);
            return [
                ['slot_number' => 1, 'x' => 50, 'y' => 80, 'width' => $width - 100, 'height' => $slotHeight],
                ['slot_number' => 2, 'x' => 50, 'y' => 80 + $slotHeight + 45, 'width' => $width - 100, 'height' => $slotHeight],
                ['slot_number' => 3, 'x' => 50, 'y' => 80 + ($slotHeight * 2) + 90, 'width' => $width - 100, 'height' => $slotHeight]
            ];
        }

        if ($type === 'strip_2x6' && $count === 4) {
            $slotHeight = intval(($height - 200) / 4);
            return [
                ['slot_number' => 1, 'x' => 50, 'y' => 60, 'width' => $width - 100, 'height' => $slotHeight],
                ['slot_number' => 2, 'x' => 50, 'y' => 60 + $slotHeight + 40, 'width' => $width - 100, 'height' => $slotHeight],
                ['slot_number' => 3, 'x' => 50, 'y' => 60 + ($slotHeight * 2) + 80, 'width' => $width - 100, 'height' => $slotHeight],
                ['slot_number' => 4, 'x' => 50, 'y' => 60 + ($slotHeight * 3) + 120, 'width' => $width - 100, 'height' => $slotHeight]
            ];
        }

        // Generic vertical grid slot builder
        $slots = [];
        $gap = 20;
        $totalGaps = ($count - 1) * $gap;
        $slotHeight = intval(($height - 150 - $totalGaps) / $count);
        for ($i = 1; $i <= $count; $i++) {
            $slots[] = [
                'slot_number' => $i,
                'x' => 50,
                'y' => 50 + (($i - 1) * ($slotHeight + $gap)),
                'width' => $width - 100,
                'height' => $slotHeight
            ];
        }
        return $slots;
    }

    /**
     * Synchronize steps associated with the template's capture count.
     */
    private function syncTemplateSteps(PhotoboothTemplate $template): void
    {
        $existingSteps = $template->steps()->count();
        $targetSteps = $template->capture_count;

        if ($existingSteps > $targetSteps) {
            $template->steps()->where('step_number', '>', $targetSteps)->delete();
        }

        for ($i = 1; $i <= $targetSteps; $i++) {
            $template->steps()->updateOrCreate(
                ['step_number' => $i],
                [
                    'slot_number' => $i,
                    'countdown_seconds' => intval(optional($template->timing_json)['initial_countdown'] ?? 5),
                    'preview_seconds' => intval(optional($template->timing_json)['preview_duration'] ?? 3),
                    'overlay_path' => null,
                    'instruction_text' => "Persiapankan Pose ke-{$i}!",
                ]
            );
        }
    }
}
