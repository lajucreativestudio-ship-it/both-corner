<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ClientDevice;
use App\Models\DevicePairingCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DeviceManagementController extends Controller
{
    public function index(): View
    {
        $this->ensureDeveloperAccess();

        $devices = ClientDevice::with(['user', 'currentEvent'])
            ->latest('last_heartbeat_at')
            ->latest('updated_at')
            ->get();

        $pairingCodes = DevicePairingCode::latest()
            ->take(12)
            ->get();

        return view('developer.devices.index', compact('devices', 'pairingCodes'));
    }

    public function storePairingCode(Request $request): RedirectResponse
    {
        $this->ensureDeveloperAccess();

        $validated = $request->validate([
            'device_name' => ['nullable', 'string', 'max:255'],
            'platform' => ['nullable', 'string', 'in:android,windows,unknown'],
            'expires_in_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
        ]);

        $pairingCode = DevicePairingCode::create([
            'user_id' => auth()->id(),
            'code' => $this->generateUniquePairingCode(),
            'device_name' => $validated['device_name'] ?? null,
            'platform' => $validated['platform'] ?? 'unknown',
            'expires_at' => now()->addMinutes((int) $validated['expires_in_minutes']),
        ]);

        return back()
            ->with('success', 'Pairing code berhasil dibuat.')
            ->with('generated_pairing_code', $pairingCode->code);
    }

    public function revoke(ClientDevice $device): RedirectResponse
    {
        $this->ensureDeveloperAccess();

        $device->forceFill([
            'revoked_at' => now(),
            'is_online' => false,
        ])->save();

        return back()->with('success', 'Device berhasil dinonaktifkan.');
    }

    public function reactivate(ClientDevice $device): RedirectResponse
    {
        $this->ensureDeveloperAccess();

        $device->forceFill([
            'revoked_at' => null,
        ])->save();

        return back()->with('success', 'Device berhasil diaktifkan kembali.');
    }

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

    private function generateUniquePairingCode(): string
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (DevicePairingCode::where('code', $code)->exists());

        return $code;
    }
}
