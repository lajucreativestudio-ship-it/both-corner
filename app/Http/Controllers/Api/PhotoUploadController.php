<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientDevice;
use App\Models\EventPhoto;
use App\Models\PhotoboothEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoUploadController extends Controller
{
    public function store(Request $request, PhotoboothEvent $event): JsonResponse
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

        if ((int) $device->current_event_id !== (int) $event->id) {
            return response()->json([
                'success' => false,
                'message' => 'Device is not assigned to this event',
            ], 403);
        }

        $validated = $request->validate([
            'photo_file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'photo_type' => ['nullable', 'string', 'max:100'],
            'session_code' => ['nullable', 'string', 'max:255'],
            'metadata_json' => ['nullable', 'string'],
        ]);

        $metadata = $this->metadataFromRequest($validated);
        if ($metadata === false) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid metadata_json',
            ], 422);
        }

        $file = $request->file('photo_file');
        $directory = "events/{$event->id}/photos";
        $extension = $file->extension() ?: $file->guessExtension() ?: 'jpg';
        $filename = Str::uuid() . '.' . $extension;
        $filePath = $file->storeAs($directory, $filename, 'public');

        $photo = EventPhoto::create([
            'photobooth_event_id' => $event->id,
            'client_device_id' => $device->id,
            'user_id' => $event->user_id,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'metadata_json' => $metadata,
            'uploaded_at' => now(),
        ]);

        $device->forceFill([
            'last_heartbeat_at' => now(),
            'last_active_at' => now(),
            'is_online' => true,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Photo uploaded successfully',
            'photo' => [
                'id' => $photo->id,
                'event_id' => $event->id,
                'file_path' => $photo->file_path,
                'file_url' => $this->publicFileUrl($request, $photo->file_path),
                'uploaded_at' => optional($photo->uploaded_at)->format('Y-m-d H:i:s'),
            ],
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

    private function metadataFromRequest(array $validated): array|false
    {
        $metadata = [];

        if (!empty($validated['metadata_json'])) {
            $decoded = json_decode($validated['metadata_json'], true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return false;
            }

            $metadata = $decoded;
        }

        $metadata['photo_type'] = $validated['photo_type'] ?? 'final';

        if (!empty($validated['session_code'])) {
            $metadata['session_code'] = $validated['session_code'];
        }

        return $metadata;
    }

    private function publicFileUrl(Request $request, string $filePath): string
    {
        return $request->getSchemeAndHttpHost() . '/storage/' . ltrim($filePath, '/');
    }
}
