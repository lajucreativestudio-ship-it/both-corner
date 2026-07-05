<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Event - Both Corner</title>

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
    <!-- Sidebar -->
    <aside class="w-72 bg-[#0f172a] text-slate-400 flex flex-col justify-between shrink-0 border-r border-slate-800 select-none">
      <div>
        <div class="h-20 px-6 border-b border-slate-800/70 flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md">B</div>
          <div class="leading-tight">
            <span class="block text-base font-extrabold text-white tracking-tight">Both<span class="text-indigo-400">Dev</span></span>
            <span class="text-[10px] uppercase tracking-[0.22em] text-slate-500 font-bold">Admin Console</span>
          </div>
        </div>

        <nav class="p-4">
          <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] px-3 mb-3">Developer Tools</p>
          <ul class="space-y-1.5">
            <li>
              <a href="{{ route('developer.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">📊</span>
                <span>Ringkasan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('developer.devices.index') }}" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">💻</span>
                <span>Device Management</span>
              </a>
            </li>
            <li>
              <a href="{{ route('developer.events.index') }}" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20 transition-all duration-200">
                <span class="w-8 h-8 rounded-lg bg-indigo-500/15 text-indigo-300 flex items-center justify-center">📅</span>
                <span>Event Management</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>

      <div class="p-4 border-t border-slate-800 bg-slate-950/20">
        <div class="flex items-center gap-3 p-1.5 rounded-xl">
          <div class="w-10 h-10 rounded-full bg-indigo-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md">DV</div>
          <div class="flex flex-col min-w-0">
            <span class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Developer' }}</span>
            <span class="text-[10px] text-slate-500 truncate">Administrator</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0">
      <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between shrink-0">
        <div class="min-w-0">
          <h1 class="text-xl font-extrabold text-slate-900 truncate">Edit Event: {{ $event->name }}</h1>
          <p class="text-xs text-slate-500 mt-1">Ubah data event, kelola setting basic, dan pasangkan device klien.</p>
        </div>
        <a href="{{ route('developer.events.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
          ← Kembali ke Event Management
        </a>
      </header>

      <div class="p-8">
        @if(session('success'))
          <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold mb-6 flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
          </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-[1fr_400px] gap-8 align-start">
          
          <!-- Left Column: Event & Settings Form -->
          <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h2 class="font-bold text-slate-900">Ubah Detail Event & Settings</h2>
              <p class="text-xs text-slate-500 mt-1">Dibuat oleh: <strong class="text-slate-700">{{ $event->user->name }} ({{ $event->user->email }})</strong></p>
            </div>

            <form action="{{ route('developer.events.update', $event) }}" method="POST" class="p-6 space-y-6">
              @csrf
              @method('PUT')

              @php
                $config = optional($setting)->config_json ?? [];
                $timing = $config['timing'] ?? [];
              @endphp

              <!-- Event Basic Info Section -->
              <div class="space-y-4">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">1. Data Dasar Event</h3>

                <div>
                  <label for="user_id" class="block text-xs font-bold text-slate-600 mb-2">Client Owner</label>
                  <select id="user_id" name="user_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                    @foreach($users as $user)
                      <option value="{{ $user->id }}" @selected((int) old('user_id', $event->user_id) === (int) $user->id)>
                        {{ $user->name }} ({{ $user->email }})
                      </option>
                    @endforeach
                  </select>
                  @error('user_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="name" class="block text-xs font-bold text-slate-600 mb-2">Nama Event (Event Name)</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $event->name) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('name')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <div>
                    <label for="slug" class="block text-xs font-bold text-slate-600 mb-2">Slug URL (Slug)</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $event->slug) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('slug')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="event_date" class="block text-xs font-bold text-slate-600 mb-2">Tanggal Event</label>
                    <input id="event_date" name="event_date" type="date" value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('event_date')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <div>
                    <label for="location" class="block text-xs font-bold text-slate-600 mb-2">Lokasi Event</label>
                    <input id="location" name="location" type="text" value="{{ old('location', $event->location) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('location')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="status" class="block text-xs font-bold text-slate-600 mb-2">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                      <option value="draft" @selected(old('status', $event->status) === 'draft')>Draft</option>
                      <option value="active" @selected(old('status', $event->status) === 'active')>Active</option>
                      <option value="completed" @selected(old('status', $event->status) === 'completed')>Completed</option>
                    </select>
                    @error('status')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <div>
                    <label for="gallery_visibility" class="block text-xs font-bold text-slate-600 mb-2">Gallery Visibility</label>
                    <select id="gallery_visibility" name="gallery_visibility" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                      <option value="private" @selected(old('gallery_visibility', $event->gallery_visibility) === 'private')>Private</option>
                      <option value="public" @selected(old('gallery_visibility', $event->gallery_visibility) === 'public')>Public</option>
                    </select>
                    @error('gallery_visibility')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              </div>

              <!-- Event Settings Section -->
              <div class="space-y-4 pt-4">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">2. Konfigurasi Photobooth (Event Settings)</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="layout_type" class="block text-xs font-bold text-slate-600 mb-2">Layout Type</label>
                    <select id="layout_type" name="layout_type" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                      @foreach(['classic' => 'Classic (Single Photo)', 'strip' => 'Strip (Multi Photos Grid)', 'custom' => 'Custom Layout'] as $val => $lbl)
                        <option value="{{ $val }}" @selected(old('layout_type', optional($setting)->layout_type) === $val)>{{ $lbl }}</option>
                      @endforeach
                    </select>
                    @error('layout_type')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <div>
                    <label for="capture_count" class="block text-xs font-bold text-slate-600 mb-2">Capture Count</label>
                    <input id="capture_count" name="capture_count" type="number" min="1" max="10" value="{{ old('capture_count', optional($setting)->capture_count ?? 3) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('capture_count')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="overlay_path" class="block text-xs font-bold text-slate-600 mb-2">Overlay Path (Watermark File)</label>
                    <input id="overlay_path" name="overlay_path" type="text" value="{{ old('overlay_path', optional($setting)->overlay_path) }}" placeholder="Contoh: overlays/wedding_overlay.png" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('overlay_path')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>

                  <div>
                    <label for="background_path" class="block text-xs font-bold text-slate-600 mb-2">Background Path (Background File)</label>
                    <input id="background_path" name="background_path" type="text" value="{{ old('background_path', optional($setting)->background_path) }}" placeholder="Contoh: backgrounds/purple_bg.png" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('background_path')
                      <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="flex flex-wrap gap-6 pt-2">
                  <!-- Retake Enabled -->
                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="retake_enabled" value="1" @checked(old('retake_enabled', optional($setting)->retake_enabled ?? true)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">Izinkan Retake (Ulang Foto)</div>
                  </label>

                  <!-- Print Enabled -->
                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="print_enabled" value="1" @checked(old('print_enabled', optional($setting)->print_enabled ?? false)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">Izinkan Cetak Langsung (Print)</div>
                  </label>

                  <!-- Watermark Enabled -->
                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="watermark_enabled" value="1" @checked(old('watermark_enabled', optional($setting)->watermark_enabled ?? false)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">Aktifkan Watermark / Overlay</div>
                  </label>
                </div>
              </div>

              <!-- Timing Section -->
              <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">3. Timing Booth</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  @foreach([
                    'initial_countdown' => ['Initial Countdown', $timing['initial_countdown'] ?? optional($setting)->countdown_seconds ?? 5, 1, 60],
                    'between_capture_delay' => ['Between Capture Delay', $timing['between_capture_delay'] ?? 2, 0, 60],
                    'preview_duration' => ['Preview Duration', $timing['preview_duration'] ?? 3, 1, 120],
                    'retake_timeout' => ['Retake Timeout', $timing['retake_timeout'] ?? 10, 1, 300],
                    'final_preview_duration' => ['Final Preview Duration', $timing['final_preview_duration'] ?? 8, 1, 300],
                    'idle_timeout' => ['Idle Timeout', $timing['idle_timeout'] ?? 30, 5, 1800],
                  ] as $field => [$label, $default, $min, $max])
                    <div>
                      <label for="{{ $field }}" class="block text-xs font-bold text-slate-600 mb-2">{{ $label }} (detik)</label>
                      <input id="{{ $field }}" name="{{ $field }}" type="number" min="{{ $min }}" max="{{ $max }}" value="{{ old($field, $default) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                      @error($field)
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                      @enderror
                    </div>
                  @endforeach
                </div>
              </div>

              <!-- Capture Modes Section -->
              <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">4. Capture Modes (Mode Jepretan)</h3>
                
                @php
                  $enabledModes = $event->eventCaptureModes->where('is_enabled', true)->pluck('mode_type')->toArray();
                  if (empty($enabledModes)) {
                      $enabledModes = ['photo'];
                  }
                @endphp
                <div class="flex flex-wrap gap-6 pt-2">
                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="capture_modes[]" value="photo" @checked(in_array('photo', $enabledModes, true)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">📸 Photo Mode</div>
                  </label>

                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="capture_modes[]" value="gif" @checked(in_array('gif', $enabledModes, true)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">🎞️ GIF Mode</div>
                  </label>

                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="capture_modes[]" value="boomerang" @checked(in_array('boomerang', $enabledModes, true)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">🔁 Boomerang Mode</div>
                  </label>

                  <label class="flex items-center gap-3 select-none cursor-not-allowed opacity-50">
                    <input type="checkbox" disabled class="w-4 h-4 rounded text-slate-400 border-slate-300">
                    <div class="text-xs font-bold text-slate-400">🎥 Video Mode (Coming Soon)</div>
                  </label>
                </div>
                @error('capture_modes')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <!-- Event Templates Section -->
              <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">5. Event Templates Selection (Pilih Layout)</h3>
                
                @php
                  $assignedTemplateIds = $event->eventTemplates->pluck('photobooth_template_id')->toArray();
                  $defaultTemplateId = $event->eventTemplates->where('is_default', true)->first()?->photobooth_template_id ?? optional($setting)->template_id;
                @endphp

                <p class="text-xs text-slate-500">Pilih beberapa template/layout yang ingin diaktifkan untuk event ini. Tentukan salah satu sebagai layout default.</p>
                @error('templates')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
                @error('default_template_id')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                  @forelse($templates as $tmpl)
                    @php
                      $oldTemplateIds = old('templates', $assignedTemplateIds);
                      $isAssigned = in_array($tmpl->id, array_map('intval', $oldTemplateIds), true);
                      $isDefault = ((int)$tmpl->id === (int)old('default_template_id', $defaultTemplateId));
                    @endphp
                    <div class="p-4 border rounded-xl flex items-center justify-between gap-4 {{ $isAssigned ? 'border-indigo-100 bg-indigo-50/20' : 'border-slate-200 bg-white' }}">
                      <div class="flex items-center gap-3">
                        <input type="checkbox" name="templates[]" value="{{ $tmpl->id }}" @checked($isAssigned) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <div>
                          <div class="text-xs font-bold text-slate-900">{{ $tmpl->name }}</div>
                          <div class="text-[10px] text-slate-400 font-semibold">{{ str_replace('_', ' ', $tmpl->template_type) }} ({{ $tmpl->canvas_width }}x{{ $tmpl->canvas_height }} px)</div>
                        </div>
                      </div>
                      
                      <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="default_template_id" value="{{ $tmpl->id }}" @checked($isDefault) class="w-3.5 h-3.5 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span class="text-[10px] font-bold text-slate-500">Default</span>
                      </label>
                    </div>
                  @empty
                    <div class="md:col-span-2 p-6 rounded-xl border border-amber-200 bg-amber-50 text-sm text-amber-800">
                      Belum ada Global Template Library yang aktif. Buat master preset terlebih dahulu di Template Management.
                    </div>
                  @endforelse
                </div>
              </div>

              <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('developer.events.index') }}" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors">
                  Batal
                </a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/15 transition-colors">
                  Simpan Perubahan
                </button>
              </div>
            </form>
          </div>

          <!-- Right Column: Device Assignment Widget -->
          <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
              <div class="p-6 border-b border-slate-100">
                <h2 class="font-bold text-slate-900">Device Pairing</h2>
                <p class="text-xs text-slate-500 mt-1">Pasang/lepas client device untuk menyinkronkan data jepretan kamera.</p>
              </div>

              <div class="p-6 divide-y divide-slate-100 space-y-4">
                @forelse($devices as $device)
                  @php
                    $isThisEvent = (int)$device->current_event_id === (int)$event->id;
                    $isAssignedOther = !$isThisEvent && filled($device->current_event_id);
                  @endphp
                  <div class="pt-4 first:pt-0 flex flex-col gap-2">
                    <div class="flex items-start justify-between gap-3">
                      <div>
                        <div class="font-bold text-xs text-slate-900">{{ $device->device_name }}</div>
                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $device->platform }} | {{ $device->ip_address ?? 'No IP' }}</div>
                      </div>
                      
                      <!-- Status Badge -->
                      <div>
                        @if($isThisEvent)
                          <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-[9px] font-bold uppercase">This Event</span>
                        @elseif($isAssignedOther)
                          <span class="px-2 py-0.5 bg-slate-50 text-slate-500 border border-slate-200 rounded text-[9px] font-bold uppercase truncate max-w-28 inline-block" title="Assigned: {{ $device->currentEvent->name }}">
                            In: {{ $device->currentEvent->name }}
                          </span>
                        @else
                          <span class="px-2 py-0.5 bg-slate-100 text-slate-400 border border-slate-200 rounded text-[9px] font-bold uppercase">Available</span>
                        @endif
                      </div>
                    </div>

                    <!-- Action Button Form -->
                    <div class="mt-1">
                      @if($isThisEvent)
                        <form action="{{ route('developer.events.unassign-device', $event) }}" method="POST">
                          @csrf
                          <input type="hidden" name="device_id" value="{{ $device->id }}">
                          <button type="submit" class="w-full py-1.5 rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 transition-colors font-bold text-xs">
                            Lepas (Unassign)
                          </button>
                        </form>
                      @else
                        <form action="{{ route('developer.events.assign-device', $event) }}" method="POST">
                          @csrf
                          <input type="hidden" name="device_id" value="{{ $device->id }}">
                          <button type="submit" class="w-full py-1.5 rounded-lg border border-indigo-200 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors font-bold text-xs">
                            Assign ke Event Ini
                          </button>
                        </form>
                      @endif
                    </div>
                  </div>
                @empty
                  <div class="py-4 text-center text-slate-400 text-xs">
                    No active devices available.
                  </div>
                @endforelse
              </div>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>
</body>
</html>
