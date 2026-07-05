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

        $event = PhotoboothEvent::with('setting')->find($device->current_event_id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'No active event assigned',
            ]);
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
