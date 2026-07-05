<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Device Management - Both Corner</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body { font-family: 'Instrument Sans', sans-serif; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">
  <div class="flex min-h-screen">
    @include('developer.sidebar')

    <main class="flex-1 flex flex-col min-w-0">
      <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between shrink-0">
        <div>
          <h1 class="text-xl font-extrabold text-slate-900">Device Management</h1>
          <p class="text-xs text-slate-500 mt-1">Pantau device pairing, heartbeat, status event aktif, dan akses token device.</p>
        </div>
        <a href="{{ route('developer.dashboard') }}" class="text-xs font-bold text-indigo-600 hover:underline">Kembali ke Developer Dashboard →</a>
      </header>

      <div class="p-8">
        @if(session('success'))
          <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold mb-6 flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
          </div>
        @endif

        @if(session('generated_pairing_code'))
          <div class="bg-indigo-950 text-white rounded-2xl border border-indigo-800 p-6 mb-6 shadow-lg shadow-indigo-950/10">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-300">Pairing Code Baru</p>
            <div class="mt-3 flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div>
                <div class="font-mono text-4xl font-black tracking-[0.28em]">{{ session('generated_pairing_code') }}</div>
                <p class="text-xs text-indigo-200 mt-2">Masukkan code ini di aplikasi Android/Windows client. Code hanya bisa dipakai satu kali.</p>
              </div>
              <span class="px-3 py-1 rounded-full bg-emerald-400/10 text-emerald-200 border border-emerald-300/20 text-xs font-bold">Available</span>
            </div>
          </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
          <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Total Device</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $devices->count() }}</p>
          </div>
          <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Online</p>
            <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $devices->filter->isOnline()->count() }}</p>
          </div>
          <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Revoked</p>
            <p class="text-3xl font-extrabold text-rose-600 mt-2">{{ $devices->filter->isRevoked()->count() }}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-[420px_1fr] gap-6 mb-6">
          <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h2 class="font-bold text-slate-900">Generate Pairing Code</h2>
              <p class="text-xs text-slate-500 mt-1">Buat code untuk pairing device Android atau Windows ke akun developer saat ini.</p>
            </div>
            <form action="{{ route('developer.devices.pairing-code.store') }}" method="POST" class="p-6 space-y-4">
              @csrf
              <div>
                <label for="device_name" class="block text-xs font-bold text-slate-600 mb-2">Device Name</label>
                <input id="device_name" name="device_name" type="text" value="{{ old('device_name') }}" placeholder="Booth Android 01" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                @error('device_name')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="platform" class="block text-xs font-bold text-slate-600 mb-2">Platform</label>
                  <select id="platform" name="platform" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @foreach(['android' => 'Android', 'windows' => 'Windows', 'unknown' => 'Unknown'] as $value => $label)
                      <option value="{{ $value }}" @selected(old('platform', 'unknown') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  @error('platform')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label for="expires_in_minutes" class="block text-xs font-bold text-slate-600 mb-2">Expired Dalam</label>
                  <input id="expires_in_minutes" name="expires_in_minutes" type="number" min="1" max="1440" value="{{ old('expires_in_minutes', 30) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  <p class="text-[11px] text-slate-400 mt-1">Menit, default 30.</p>
                  @error('expires_in_minutes')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <button type="submit" class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-4 py-3 shadow-lg shadow-indigo-600/15 transition-colors">Generate Pairing Code</button>
            </form>
          </div>

          <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
              <div>
                <h2 class="font-bold text-slate-900">Pairing Code Terbaru</h2>
                <p class="text-xs text-slate-500 mt-1">Code terbaru untuk pairing device. Token device tidak ditampilkan di UI.</p>
              </div>
              <span class="text-[10px] text-slate-500 bg-slate-50 border border-slate-200 font-bold px-2 py-0.5 rounded-full">{{ $pairingCodes->count() }} code</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4">Code</th>
                    <th class="p-4">Device</th>
                    <th class="p-4">Platform</th>
                    <th class="p-4">Expires</th>
                    <th class="p-4">Used</th>
                    <th class="p-4">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @forelse($pairingCodes as $pairingCode)
                    @php
                      $isUsed = filled($pairingCode->used_at);
                      $isExpired = !$isUsed && filled($pairingCode->expires_at) && $pairingCode->expires_at->isPast();
                      $statusLabel = $isUsed ? 'used' : ($isExpired ? 'expired' : 'available');
                      $statusClass = $isUsed
                          ? 'bg-slate-100 text-slate-600 border-slate-200'
                          : ($isExpired ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100');
                    @endphp
                    <tr class="hover:bg-slate-50/60">
                      <td class="p-4 font-mono font-black tracking-[0.16em] text-slate-900">{{ $pairingCode->code }}</td>
                      <td class="p-4 text-slate-700">{{ $pairingCode->device_name ?? '-' }}</td>
                      <td class="p-4">
                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $pairingCode->platform === 'android' ? 'bg-green-50 text-green-700' : ($pairingCode->platform === 'windows' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                          {{ $pairingCode->platform ?? 'unknown' }}
                        </span>
                      </td>
                      <td class="p-4 text-slate-500">
                        <div>{{ optional($pairingCode->expires_at)->format('Y-m-d H:i:s') ?? '-' }}</div>
                        <div class="text-[11px] text-slate-400">{{ optional($pairingCode->expires_at)->diffForHumans() ?? '' }}</div>
                      </td>
                      <td class="p-4 text-slate-500">
                        <div>{{ optional($pairingCode->used_at)->format('Y-m-d H:i:s') ?? '-' }}</div>
                        <div class="text-[11px] text-slate-400">{{ optional($pairingCode->used_at)->diffForHumans() ?? '' }}</div>
                      </td>
                      <td class="p-4">
                        <span class="inline-flex px-2.5 py-1 rounded-full border font-bold text-[10px] {{ $statusClass }}">{{ $statusLabel }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="p-10 text-center text-slate-500">
                        <p class="font-bold text-slate-800">Belum ada pairing code.</p>
                        <p class="text-xs mt-1">Generate code pertama dari form di sebelah kiri.</p>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
              <h2 class="font-bold text-slate-900">Daftar Device Pairing</h2>
              <p class="text-xs text-slate-500 mt-1">Status online dihitung dari heartbeat kurang dari 2 menit dan device belum revoked.</p>
            </div>
            <span class="text-[10px] text-indigo-600 bg-indigo-50 border border-indigo-100 font-bold px-2 py-0.5 rounded-full">Localhost monitoring</span>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4">Device</th>
                  <th class="p-4">Platform</th>
                  <th class="p-4">App / OS</th>
                  <th class="p-4">IP</th>
                  <th class="p-4">Event Aktif</th>
                  <th class="p-4">Heartbeat</th>
                  <th class="p-4">Status</th>
                  <th class="p-4 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($devices as $device)
                  <tr class="hover:bg-slate-50/60 align-top">
                    <td class="p-4 min-w-64">
                      <div class="font-bold text-slate-900">{{ $device->device_name }}</div>
                      <div class="font-mono text-[11px] text-slate-400 mt-1 break-all">{{ $device->device_uuid ?? 'UUID belum tersedia' }}</div>
                    </td>
                    <td class="p-4">
                      <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ strtolower($device->platform) === 'android' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }}">
                        {{ $device->platform ?? 'unknown' }}
                      </span>
                    </td>
                    <td class="p-4 text-slate-600 min-w-36">
                      <div>{{ $device->app_version ?? '-' }}</div>
                      <div class="text-xs text-slate-400">{{ $device->os_version ?? '-' }}</div>
                    </td>
                    <td class="p-4 text-slate-500 font-mono text-xs">{{ $device->ip_address ?? '-' }}</td>
                    <td class="p-4 min-w-48">
                      @if($device->currentEvent)
                        <div class="font-bold text-slate-800">{{ $device->currentEvent->name }}</div>
                        <div class="text-xs text-slate-400">ID: {{ $device->current_event_id }}</div>
                      @else
                        <span class="text-slate-400">Belum ada event</span>
                      @endif
                    </td>
                    <td class="p-4 text-slate-500 min-w-40">
                      <div>{{ optional($device->last_heartbeat_at)->format('Y-m-d H:i:s') ?? '-' }}</div>
                      <div class="text-xs text-slate-400">{{ optional($device->last_heartbeat_at)->diffForHumans() ?? 'Belum pernah heartbeat' }}</div>
                    </td>
                    <td class="p-4">
                      <div class="flex flex-col gap-2">
                        @if($device->isOnline())
                          <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 font-bold text-[10px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                          </span>
                        @else
                          <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 font-bold text-[10px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Offline
                          </span>
                        @endif

                        @if($device->isRevoked())
                          <span class="inline-flex w-fit px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100 font-bold text-[10px]">Revoked</span>
                        @else
                          <span class="inline-flex w-fit px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 font-bold text-[10px]">Active</span>
                        @endif
                      </div>
                    </td>
                    <td class="p-4 text-right">
                      @if($device->isRevoked())
                        <form action="{{ route('developer.devices.reactivate', $device) }}" method="POST">
                          @csrf
                          <button type="submit" class="px-3 py-2 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 transition-colors">Reactivate</button>
                        </form>
                      @else
                        <form action="{{ route('developer.devices.revoke', $device) }}" method="POST">
                          @csrf
                          <button type="submit" class="px-3 py-2 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-100 transition-colors">Revoke</button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="p-12 text-center text-slate-500">
                      <div class="text-4xl mb-3">💻</div>
                      <p class="font-bold text-slate-800">Belum ada device yang pairing.</p>
                      <p class="text-xs mt-1">Gunakan Device Pairing API untuk menghubungkan Android/Windows app pertama.</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
