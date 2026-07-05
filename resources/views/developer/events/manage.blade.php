<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Monitoring Center: {{ $event->name }} - Both Corner</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body { font-family: 'Instrument Sans', sans-serif; }
    h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('developer.sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0">
      <!-- Header -->
      <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between shrink-0">
        <div class="min-w-0">
          <h1 class="text-xl font-extrabold text-slate-900 truncate">Event Monitoring Center</h1>
          <p class="text-xs text-slate-500 mt-1">Pantau owner, perangkat, sesi, media, dan galeri public. Capture experience dikonfigurasi dari booth app.</p>
        </div>
        <a href="{{ route('developer.events.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
          ← Kembali ke Event List
        </a>
      </header>

      <div class="p-8 space-y-8">
        
        <!-- 1. Event Overview & Metrics -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 md:p-8 space-y-6">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <span class="px-2.5 py-0.5 border rounded-full text-[10px] font-bold uppercase {{ $event->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                {{ $event->status }}
              </span>
              <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-2">{{ $event->name }}</h2>
              <p class="text-xs text-slate-500 mt-1">Client Owner: <span class="font-bold text-slate-700">{{ $event->user->name }}</span> ({{ $event->user->email }})</p>
            </div>
            <div class="text-xs text-slate-500 space-y-1 font-medium text-left md:text-right">
              @if($event->event_date)
                <div>📅 Tanggal: {{ $event->event_date->format('d M Y') }}</div>
              @endif
              @if($event->location)
                <div>📍 Lokasi: {{ $event->location }}</div>
              @endif
              <div>🔒 Visibilitas Galeri: <span class="font-bold capitalize">{{ $event->gallery_visibility }}</span></div>
            </div>
          </div>

          <!-- Metrics Grid -->
          <div class="grid grid-cols-2 md:grid-cols-5 gap-4 pt-4 border-t border-slate-100">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/50">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Capture</span>
              <span class="text-2xl font-extrabold text-slate-800 block mt-2">{{ $totalPhotosCount }}</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/50">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Sessions</span>
              <span class="text-2xl font-extrabold text-slate-800 block mt-2">{{ $totalSessionsCount }}</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/50">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Final Outputs</span>
              <span class="text-2xl font-extrabold text-emerald-600 block mt-2">{{ $totalFinalPhotosCount }}</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/50">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Raw Snapshots</span>
              <span class="text-2xl font-extrabold text-indigo-600 block mt-2">{{ $totalRawPhotosCount }}</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/50 col-span-2 md:col-span-1">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Assigned Devices</span>
              <span class="text-2xl font-extrabold text-slate-800 block mt-2">{{ $assignedDevices->count() }}</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- 2. Capture Modes Section -->
          <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <span>⚙️</span> Capture Modes Snapshot
              </h3>
              <span class="text-[11px] font-bold text-slate-400">Read-only from Booth App</span>
            </div>
            
            @php
              $enabledModeTypes = $event->eventCaptureModes->where('is_enabled', true)->pluck('mode_type')->toArray();
              if(empty($enabledModeTypes)) { $enabledModeTypes = ['photo']; }
            @endphp
            <div class="grid grid-cols-2 gap-3 pt-2">
              @foreach(['photo' => '📸 Photo Mode', 'gif' => '🎞️ GIF Mode', 'boomerang' => '🔁 Boomerang Mode', 'video' => '🎥 Video Mode'] as $mKey => $mLabel)
                @php
                  $isEnabled = in_array($mKey, $enabledModeTypes);
                @endphp
                <div class="p-3.5 border rounded-2xl flex items-center justify-between {{ $isEnabled ? 'border-emerald-100 bg-emerald-50/20 text-emerald-800' : 'border-slate-100 bg-slate-50 text-slate-400' }}">
                  <span class="text-xs font-bold">{{ $mLabel }}</span>
                  <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded border {{ $isEnabled ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-200/50 border-slate-200 text-slate-400' }}">
                    {{ $isEnabled ? 'Active' : 'Off' }}
                  </span>
                </div>
              @endforeach
            </div>
          </div>

          <!-- 5. Public Sharing Section -->
          <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 space-y-4">
            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
              <span>🔗</span> Public Sharing Link
            </h3>
            <p class="text-xs text-slate-500">Tautan galeri publik untuk diakses oleh pengunjung atau dicetak menjadi QR code pada banner/booth.</p>

            <div class="pt-2 space-y-3">
              <div class="flex items-center gap-2">
                <input type="text" readonly value="{{ url('/e/' . $event->slug) }}" id="publicUrlInput" class="bg-slate-50 border border-slate-200 text-slate-600 rounded-xl px-4 py-2.5 outline-none font-mono text-xs flex-1">
                <button onclick="copyPublicUrl()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shrink-0 transition-colors">
                  Copy
                </button>
              </div>

              @if($event->gallery_visibility === 'private')
                <div class="p-3 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-xs font-semibold flex items-center gap-2">
                  <span>⚠️</span> Galeri disetel privat. Pengunjung tidak dapat membuka link ini kecuali status diubah ke public.
                </div>
              @else
                <div class="p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold flex items-center gap-2">
                  <span>✓</span> Galeri online dan siap diakses secara publik.
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- 3. Templates Section -->
          <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <span>🎨</span> Event Template Snapshot
              </h3>
              <span class="text-[11px] font-bold text-slate-400">Read-only from Booth App</span>
            </div>

            <div class="space-y-3 divide-y divide-slate-100 max-h-[320px] overflow-y-auto pr-2">
              @forelse($event->eventTemplates as $et)
                @php $tmpl = $et->template; @endphp
                @if($tmpl)
                  <div class="pt-3 first:pt-0 flex items-center justify-between gap-4">
                    <div>
                      <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-900">{{ $tmpl->name }}</span>
                        @if($et->is_default)
                          <span class="px-1.5 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded text-[9px] font-extrabold">DEFAULT</span>
                        @endif
                      </div>
                      <div class="text-[10px] text-slate-500 font-medium mt-1 uppercase tracking-wider">
                        {{ str_replace('_', ' ', $tmpl->template_type) }} &bull; {{ $tmpl->canvas_width }}x{{ $tmpl->canvas_height }}px
                      </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                      @if($tmpl->overlay_path)
                        <a href="{{ Storage::disk('public')->url($tmpl->overlay_path) }}" target="_blank" class="px-2 py-1 rounded bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">Overlay</a>
                      @endif
                      <a href="{{ route('developer.templates.edit', $tmpl) }}" class="px-2 py-1 rounded bg-indigo-50 hover:bg-indigo-100 text-[10px] font-bold text-indigo-600">Open Preset</a>
                    </div>
                  </div>
                @endif
              @empty
                <div class="py-6 text-center text-slate-400 text-xs">
                  Belum ada template aktif terhubung ke event ini.
                </div>
              @endforelse
            </div>
          </div>

          <!-- 4. Devices Section -->
          <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                <span>💻</span> Assigned Devices
              </h3>
              <a href="{{ route('developer.devices.index') }}" class="text-[11px] font-bold text-indigo-600 hover:underline">
                Kelola Device
              </a>
            </div>

            <div class="space-y-3 divide-y divide-slate-100 max-h-[320px] overflow-y-auto pr-2">
              @forelse($assignedDevices as $dev)
                @php $online = $dev->isOnline(); @endphp
                <div class="pt-3 first:pt-0 flex items-center justify-between gap-4">
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="text-xs font-bold text-slate-900">{{ $dev->device_name }}</span>
                      <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase border {{ $online ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-slate-100 border-slate-200 text-slate-400' }}">
                        {{ $online ? 'Online' : 'Offline' }}
                      </span>
                    </div>
                    <div class="text-[10px] text-slate-500 font-medium mt-1">
                      OS: {{ $dev->platform }} &bull; App Version: {{ $dev->app_version ?? '-' }}
                    </div>
                  </div>

                  <div class="text-[10px] text-slate-400 font-medium text-right">
                    Last active:<br>
                    <span class="font-mono text-[9px] text-slate-500">{{ $dev->last_heartbeat_at ? $dev->last_heartbeat_at->format('d M H:i') : 'Never' }}</span>
                  </div>
                </div>
              @empty
                <div class="py-6 text-center text-slate-400 text-xs">
                  Belum ada device assigned ke event ini.
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- 6. Recent Sessions Section -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100">
            <h3 class="font-extrabold text-slate-900 text-base">Recent Sessions (10 Terakhir)</h3>
            <p class="text-xs text-slate-500 mt-1">Sesi jepretan booth aktif dari device.</p>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4 pl-6">Session Code</th>
                  <th class="p-4">Template Layout</th>
                  <th class="p-4">Mode</th>
                  <th class="p-4">Status</th>
                  <th class="p-4">Jepretan</th>
                  <th class="p-4">Completed At</th>
                  <th class="p-4 text-right pr-6">Public Share Link</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($event->boothSessions as $session)
                  @php
                    $finalCount = $session->photos->where('photo_type', 'final')->count();
                    $rawCount = $session->photos->where('photo_type', 'raw')->count();
                    $statusColor = match($session->status) {
                      'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                      'started', 'processing' => 'bg-amber-50 text-amber-700 border-amber-100',
                      default => 'bg-rose-50 text-rose-700 border-rose-100'
                    };
                  @endphp
                  <tr class="hover:bg-slate-50/60 align-middle">
                    <td class="p-4 pl-6 font-bold text-slate-950 font-mono">{{ $session->session_code }}</td>
                    <td class="p-4 text-slate-700 font-semibold">{{ $session->template->name ?? '-' }}</td>
                    <td class="p-4 capitalize text-slate-600 font-medium">{{ $session->mode_type }}</td>
                    <td class="p-4">
                      <span class="inline-flex px-2 py-0.5 rounded border font-bold text-[9px] uppercase {{ $statusColor }}">
                        {{ $session->status }}
                      </span>
                    </td>
                    <td class="p-4 text-slate-500 font-medium">
                      🖼️ {{ $finalCount }} Final &bull; 📸 {{ $rawCount }} Raw
                    </td>
                    <td class="p-4 font-mono text-slate-500 text-[10px]">
                      {{ $session->completed_at ? $session->completed_at->format('d M Y H:i') : '-' }}
                    </td>
                    <td class="p-4 text-right pr-6">
                      <a href="{{ route('public.session-result', $session->public_token) }}" target="_blank" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 transition-colors">
                        View Sharing Page
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="p-12 text-center text-slate-400">
                      Sesi jepretan belum terekam di server.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- 7. Recent Photos Grid Section -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 space-y-4">
          <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
            <span>🖼️</span> Live Photos Strip (12 Terbaru)
          </h3>

          <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 pt-2">
            @forelse($event->photos as $p)
              @php
                $url = Storage::disk('public')->url($p->file_path);
                $isFinal = $p->photo_type === 'final' || is_null($p->photo_type);
              @endphp
              <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden p-2 flex flex-col justify-between group shadow-sm hover:shadow transition-all">
                <a href="{{ $url }}" target="_blank" class="block aspect-square rounded-xl overflow-hidden bg-slate-100 relative">
                  <img src="{{ $url }}" alt="Capture snapshot" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </a>
                <div class="mt-2.5 flex items-center justify-between">
                  <span class="px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase {{ $isFinal ? 'bg-emerald-50 border border-emerald-100 text-emerald-700' : 'bg-indigo-50 border border-indigo-100 text-indigo-700' }}">
                    {{ $isFinal ? 'Final' : 'Raw' }}
                  </span>
                  @if($p->boothSession)
                    <a href="{{ route('public.session-result', $p->boothSession->public_token) }}" target="_blank" class="text-[9px] font-bold text-indigo-600 hover:underline">
                      Session
                    </a>
                  @endif
                </div>
              </div>
            @empty
              <div class="py-12 col-span-full text-center text-slate-400 text-xs">
                Belum ada foto terunggah.
              </div>
            @endforelse
          </div>
        </div>

        <!-- 8. Guest Flow Preview Section -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 space-y-6">
          <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
            <span>🚀</span> Booth App Capture Flow Preview
          </h3>
          
          <div class="grid grid-cols-2 md:grid-cols-7 gap-4 pt-2 text-center text-xs">
            <!-- Step 1 -->
            <div class="p-4 border border-slate-200 bg-slate-50/50 rounded-2xl flex flex-col justify-between">
              <span class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold mx-auto text-xs">1</span>
              <p class="font-bold text-slate-950 mt-3">Choose Mode</p>
              <p class="text-[10px] text-slate-500 mt-1">Photo, GIF, atau Boomerang.</p>
            </div>
            <!-- Step 2 -->
            <div class="p-4 border border-slate-200 bg-slate-50/50 rounded-2xl flex flex-col justify-between">
              <span class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold mx-auto text-xs">2</span>
              <p class="font-bold text-slate-950 mt-3">Choose Template</p>
              <p class="text-[10px] text-slate-500 mt-1">Tamu memilih bingkai foto cetak.</p>
            </div>
            <!-- Step 3 -->
            <div class="p-4 border border-slate-200 bg-slate-50/50 rounded-2xl flex flex-col justify-between">
              <span class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold mx-auto text-xs">3</span>
              <p class="font-bold text-slate-950 mt-3">Countdown</p>
              <p class="text-[10px] text-slate-500 mt-1">Tampilan timer delay sebelum berpose.</p>
            </div>
            <!-- Step 4 -->
            <div class="p-4 border border-slate-200 bg-slate-50/50 rounded-2xl flex flex-col justify-between">
              <span class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold mx-auto text-xs">4</span>
              <p class="font-bold text-slate-950 mt-3">Capture</p>
              <p class="text-[10px] text-slate-500 mt-1">Kamera mengambil snapshot.</p>
            </div>
            <!-- Step 5 -->
            <div class="p-4 border border-slate-200 bg-slate-50/50 rounded-2xl flex flex-col justify-between">
              <span class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold mx-auto text-xs">5</span>
              <p class="font-bold text-slate-950 mt-3">Preview / Retake</p>
              <p class="text-[10px] text-slate-500 mt-1">Tinjau jepretan atau ulang foto.</p>
            </div>
            <!-- Step 6 -->
            <div class="p-4 border border-slate-200 bg-slate-50/50 rounded-2xl flex flex-col justify-between">
              <span class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold mx-auto text-xs">6</span>
              <p class="font-bold text-slate-950 mt-3">Final Result</p>
              <p class="text-[10px] text-slate-500 mt-1">Render overlay transparan & background.</p>
            </div>
            <!-- Step 7 -->
            <div class="p-4 border border-slate-200 bg-slate-50/50 rounded-2xl flex flex-col justify-between col-span-2 md:col-span-1">
              <span class="w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold mx-auto text-xs">7</span>
              <p class="font-bold text-indigo-700 mt-3">Scan QR</p>
              <p class="text-[10px] text-indigo-600 mt-1">Tamu scan QR untuk unduh file.</p>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>

  <script>
    function copyPublicUrl() {
      var copyText = document.getElementById("publicUrlInput");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(copyText.value);
      alert("Tautan galeri publik berhasil disalin!");
    }
  </script>
</body>
</html>
