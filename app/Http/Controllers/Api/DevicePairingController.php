<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientDevice;
use App\Models\DevicePairingCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DevicePairingController extends Controller
{
    public function pair(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'platform' => ['nullable', 'string', 'max:255'],
            'device_uuid' => ['nullable', 'string', 'max:255'],
        ]);

        $pairingCode = DevicePairingCode::where('code', $validated['code'])->first();

        if (!$pairingCode) {
            return $this->failed('Invalid pairing code');
        }

        if ($pairingCode->expires_at && $pairingCode->expires_at->isPast()) {
            return $this->failed('Pairing code expired');
        }

        if ($pairingCode->used_at) {
            return $this->failed('Pairing code already used');
        }

        $deviceToken = Str::random(80);
        $deviceTokenHash = hash('sha256', $deviceToken);
        $deviceUuid = $validated['device_uuid'] ?? null;

        $device = $deviceUuid
            ? ClientDevice::where('device_uuid', $deviceUuid)->first()
            : null;

        if (!$device) {
            $device = new ClientDevice();
        }

        $device->fill([
            'user_id' => $pairingCode->user_id,
            'device_name' => $validated['device_name'] ?? $pairingCode->device_name ?? 'Unnamed Device',
            'platform' => $validated['platform'] ?? $pairingCode->platform ?? 'unknown',
            'device_uuid' => $deviceUuid,
            'api_token_hash' => $deviceTokenHash,
            'pairing_code_id' => $pairingCode->id,
            'is_online' => true,
            'last_active_at' => now(),
            'last_heartbeat_at' => now(),
            'revoked_at' => null,
        ]);
        $device->save();

        $pairingCode->update([
            'used_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device paired successfully',
            'device_token' => $deviceToken,
            'device' => [
                'id' => $device->id,
                'device_name' => $device->device_name,
                'platform' => $device->platform,
            ],
        ]);
    }

    private function failed(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 422);
    }
}
