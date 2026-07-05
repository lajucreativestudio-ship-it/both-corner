<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientDevice;
use App\Models\PhotoboothEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    public function heartbeat(Request $request): JsonResponse
    {
        $device = $this->deviceFromBearerToken($request);

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized device',
            ], 401);
        }

        if ($device->isRevoked()) {
            return response()->json([
                'success' => false,
                'message' => 'Device revoked',
            ], 403);
        }

        $validated = $request->validate([
            'app_version' => ['nullable', 'string', 'max:255'],
            'os_version' => ['nullable', 'string', 'max:255'],
            'battery_level' => ['nullable', 'integer', 'min:0', 'max:100'],
            'storage_free_mb' => ['nullable', 'integer', 'min:0'],
            'camera_status' => ['nullable', 'string', 'max:255'],
            'current_screen' => ['nullable', 'string', 'max:255'],
        ]);

        $updates = [
            'last_heartbeat_at' => now(),
        ];

        if (Schema::hasColumn('client_devices', 'last_active_at')) {
            $updates['last_active_at'] = now();
        }

        if (Schema::hasColumn('client_devices', 'is_online')) {
            $updates['is_online'] = true;
        }

        foreach (['app_version', 'os_version', 'camera_status', 'battery_level', 'storage_free_mb', 'current_screen'] as $field) {
            if (array_key_exists($field, $validated) && Schema::hasColumn('client_devices', $field)) {
                $updates[$field] = $validated[$field];
            }
        }

        if (Schema::hasColumn('client_devices', 'ip_address')) {
            $updates['ip_address'] = $request->ip();
        }

        $device->forceFill($updates)->save();

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received',
            'device' => [
                'id' => $device->id,
                'device_name' => $device->device_name,
                'platform' => $device->platform,
                'online' => $device->isOnline(),
                'last_heartbeat_at' => optional($device->last_heartbeat_at)->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function eventSettings(Request $request): JsonResponse
    {
        $device = $this->deviceFromBearerToken($request);

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized device',
            ], 401);
        }

        if ($device->isRevoked()) {
            return response()->json([
                'success' => false,
                'message' => 'Device revoked',
            ], 403);
        }

        $device->forceFill([
            'last_heartbeat_at' => now(),
            'last_active_at' => now(),
            'is_online' => true,
        ])->save();

        if (!$device->current_event_id) {
            return response()->json([
                'success' => false,
                'message' => 'No active event assigned',
            ]);
        }

        $event = PhotoboothEvent::with(['setting.template', 'eventCaptureModes', 'eventTemplates.template.steps'])
            ->find($device->current_event_id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'No active event assigned',
            ]);
        }

        // 1. Format Capture Modes (only active ones; fallback to photo if empty)
        $captureModes = [];
        $activeModes = $event->eventCaptureModes->where('is_enabled', true);
        if ($activeModes->isEmpty()) {
            $captureModes[] = [
                'mode_type' => 'photo',
                'is_enabled' => true,
                'sort_order' => 0,
                'config' => (object)[],
            ];
        } else {
            foreach ($activeModes as $cm) {
                $captureModes[] = [
                    'mode_type' => $cm->mode_type,
                    'is_enabled' => true,
                    'sort_order' => $cm->sort_order,
                    'config' => $cm->config_json ?? (object)[],
                ];
            }
        }

        // 2. Format Templates
        $templatesPayload = [];
        $defaultTemplatePayload = null;
        $defaultTemplateCandidate = null;
        $eventTiming = optional($event->setting)->config_json['timing'] ?? null;

        foreach ($event->eventTemplates as $et) {
            $tmpl = $et->template;
            if (!$tmpl || $tmpl->status !== 'active') {
                continue;
            }

            // Steps formatting
            $steps = [];
            foreach ($tmpl->steps->sortBy('step_number') as $step) {
                $steps[] = [
                    'step_number' => (int)$step->step_number,
                    'slot_number' => (int)$step->slot_number,
                    'countdown_seconds' => (int)($step->countdown_seconds ?? 5),
                    'preview_seconds' => (int)($step->preview_seconds ?? 3),
                    'overlay_url' => $this->publicStorageUrl($step->overlay_path),
                    'instruction_text' => $step->instruction_text ?? '',
                    'config' => $step->config_json ?? (object)[],
                ];
            }

            $timing = $eventTiming ?? $tmpl->timing_json ?? [
                'initial_countdown' => 5,
                'between_capture_delay' => 2,
                'preview_duration' => 3,
                'retake_timeout' => 10,
                'final_preview_duration' => 8,
                'idle_timeout' => 30,
            ];

            $templatesPayload[] = [
                'id' => $tmpl->id,
                'name' => $tmpl->name,
                'template_type' => $tmpl->template_type,
                'orientation' => $tmpl->orientation,
                'canvas_width' => (int)$tmpl->canvas_width,
                'canvas_height' => (int)$tmpl->canvas_height,
                'capture_count' => (int)$tmpl->capture_count,
                'mode_type' => $et->mode_type ?? 'photo',
                'is_default' => (bool)$et->is_default,
                'sort_order' => (int)$et->sort_order,
                'overlay_url' => $this->publicStorageUrl($tmpl->overlay_path),
                'background_url' => $this->publicStorageUrl($tmpl->background_path),
                'photo_slots' => $tmpl->photo_slots_json ?? [],
                'timing' => $timing,
                'steps' => $steps,
            ];

            if ($et->is_default) {
                $defaultTemplateCandidate = $tmpl;
            }
        }

        // Default template fallback chain:
        // 1. Explicit toggled in event_templates (handled above)
        // 2. Fallback to event_settings.template_id
        if (!$defaultTemplateCandidate && optional($event->setting)->template_id) {
            $fallbackTmpl = \App\Models\PhotoboothTemplate::find($event->setting->template_id);
            if ($fallbackTmpl && $fallbackTmpl->status === 'active') {
                $defaultTemplateCandidate = $fallbackTmpl;
            }
        }

        // 3. Fallback to first assigned active template
        if (!$defaultTemplateCandidate && count($templatesPayload) > 0) {
            $firstTmplId = $templatesPayload[0]['id'];
            $defaultTemplateCandidate = \App\Models\PhotoboothTemplate::find($firstTmplId);
        }

        if ($defaultTemplateCandidate) {
            $defaultTemplatePayload = [
                'id' => $defaultTemplateCandidate->id,
                'name' => $defaultTemplateCandidate->name,
                'template_type' => $defaultTemplateCandidate->template_type,
                'orientation' => $defaultTemplateCandidate->orientation,
                'canvas_width' => (int)$defaultTemplateCandidate->canvas_width,
                'canvas_height' => (int)$defaultTemplateCandidate->canvas_height,
                'capture_count' => (int)$defaultTemplateCandidate->capture_count,
            ];
        }

        return response()->json([
            'success' => true,
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'slug' => $event->slug,
                'event_date' => optional($event->event_date)->format('Y-m-d'),
                'location' => $event->location,
                'status' => $event->status,
            ],
            'settings' => $this->settingsPayload($event),
            'capture_modes' => $captureModes,
            'templates' => $templatesPayload,
            'default_template' => $defaultTemplatePayload,
            'license' => app(\App\Services\LicenseService::class)->getPublicGalleryEntitlements($event->user),
        ]);
    }

    private function deviceFromBearerToken(Request $request): ?ClientDevice
    {
        $token = $request->bearerToken();

        if (!$token) {
            return null;
        }

        return ClientDevice::where('api_token_hash', hash('sha256', $token))->first();
    }

    private function settingsPayload(PhotoboothEvent $event): array
    {
        $setting = $event->setting;
        $config = $setting->config_json ?? null;

        return [
            'layout_type' => $setting->layout_type ?? 'classic',
            'countdown_seconds' => $setting->countdown_seconds ?? 5,
            'capture_count' => $setting->capture_count ?? 3,
            'retake_enabled' => $setting->retake_enabled ?? true,
            'print_enabled' => $setting->print_enabled ?? false,
            'watermark_enabled' => $setting->watermark_enabled ?? false,
            'overlay_url' => $this->publicStorageUrl($setting->overlay_path ?? null),
            'background_url' => $this->publicStorageUrl($setting->background_path ?? null),
            'config' => empty($config) ? (object) [] : $config,
        ];
    }

    private function publicStorageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
