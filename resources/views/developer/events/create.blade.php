<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Event Baru - Both Corner</title>

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
        <div>
          <h1 class="text-xl font-extrabold text-slate-900">Buat Event Baru</h1>
          <p class="text-xs text-slate-500 mt-1">Daftarkan event photobooth baru untuk klien, sistem akan membuatkan default settings secara otomatis.</p>
        </div>
        <a href="{{ route('developer.events.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
          ← Kembali ke Event Management
        </a>
      </header>

      <div class="p-8 max-w-5xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Form Event Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Event adalah pusat setup client, layout, capture mode, timing, dan gallery.</p>
          </div>

          <form action="{{ route('developer.events.store') }}" method="POST" class="p-6 space-y-8">
            @csrf

            <div class="space-y-4">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">1. Event Info</h3>

              <div>
                <label for="user_id" class="block text-xs font-bold text-slate-600 mb-2">Client Owner</label>
                <select id="user_id" name="user_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                  <option value="">-- Pilih Klien --</option>
                  @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
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
                  <label for="name" class="block text-xs font-bold text-slate-600 mb-2">Nama Event</label>
                  <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: Wedding Budi & Ani" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="slug" class="block text-xs font-bold text-slate-600 mb-2">Slug URL</label>
                  <input id="slug" name="slug" type="text" value="{{ old('slug') }}" placeholder="Kosongkan untuk auto-generate" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  @error('slug')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="event_date" class="block text-xs font-bold text-slate-600 mb-2">Tanggal Event</label>
                <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                @error('event_date')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <!-- Location -->
              <div>
                <label for="location" class="block text-xs font-bold text-slate-600 mb-2">Lokasi Event</label>
                <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="Contoh: Hotel Mulia, Jakarta" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                @error('location')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
              </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label for="status" class="block text-xs font-bold text-slate-600 mb-2">Status</label>
                <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                  <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                  <option value="active" @selected(old('status') === 'active')>Active</option>
                  <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                </select>
                @error('status')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="gallery_visibility" class="block text-xs font-bold text-slate-600 mb-2">Gallery Visibility</label>
                <select id="gallery_visibility" name="gallery_visibility" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                  <option value="private" @selected(old('gallery_visibility', 'private') === 'private')>Private</option>
                  <option value="public" @selected(old('gallery_visibility') === 'public')>Public</option>
                </select>
                @error('gallery_visibility')
                  <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

                <div>
                  <label for="layout_type" class="block text-xs font-bold text-slate-600 mb-2">Layout Type</label>
                  <select id="layout_type" name="layout_type" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                    @foreach(['classic' => 'Classic', 'strip' => 'Strip', 'custom' => 'Custom'] as $value => $label)
                      <option value="{{ $value }}" @selected(old('layout_type', 'classic') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  @error('layout_type')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">2. Capture Modes</h3>

              @php($oldModes = old('capture_modes', ['photo']))
              <div class="flex flex-wrap gap-6 pt-2">
                @foreach(['photo' => 'Photo Mode', 'gif' => 'GIF Mode', 'boomerang' => 'Boomerang Mode'] as $mode => $label)
                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="capture_modes[]" value="{{ $mode }}" @checked(in_array($mode, $oldModes, true)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">{{ $label }}</div>
                  </label>
                @endforeach

                <label class="flex items-center gap-3 select-none cursor-not-allowed opacity-50">
                  <input type="checkbox" disabled class="w-4 h-4 rounded text-slate-400 border-slate-300">
                  <div class="text-xs font-bold text-slate-400">Video Mode (Coming Soon)</div>
                </label>
              </div>
              @error('capture_modes')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">3. Template / Layout Event</h3>

              @php
                $selectedTemplateIds = array_map('intval', old('templates', []));
                $defaultTemplateId = (int) old('default_template_id', $selectedTemplateIds[0] ?? 0);
              @endphp

              @error('templates')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
              @enderror
              @error('default_template_id')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
              @enderror

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($templates as $template)
                  @php
                    $isSelected = in_array((int)$template->id, $selectedTemplateIds, true);
                    $isDefault = (int)$template->id === $defaultTemplateId;
                  @endphp
                  <div class="p-4 border rounded-xl flex items-center justify-between gap-4 {{ $isSelected ? 'border-indigo-100 bg-indigo-50/20' : 'border-slate-200 bg-white' }}">
                    <label class="flex items-center gap-3 cursor-pointer min-w-0">
                      <input type="checkbox" name="templates[]" value="{{ $template->id }}" @checked($isSelected) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                      <span class="min-w-0">
                        <span class="block text-xs font-bold text-slate-900 truncate">{{ $template->name }}</span>
                        <span class="block text-[10px] text-slate-400 font-semibold">{{ str_replace('_', ' ', $template->template_type) }} ({{ $template->canvas_width }}x{{ $template->canvas_height }} px)</span>
                      </span>
                    </label>

                    <label class="flex items-center gap-1.5 cursor-pointer shrink-0">
                      <input type="radio" name="default_template_id" value="{{ $template->id }}" @checked($isDefault) class="w-3.5 h-3.5 text-indigo-600 focus:ring-indigo-500 border-slate-300">
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

            <div class="space-y-4 pt-4 border-t border-slate-100">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">4. Timing Booth</h3>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                  'initial_countdown' => ['Initial Countdown', 5, 1, 60],
                  'between_capture_delay' => ['Between Capture Delay', 2, 0, 60],
                  'preview_duration' => ['Preview Duration', 3, 1, 120],
                  'retake_timeout' => ['Retake Timeout', 10, 1, 300],
                  'final_preview_duration' => ['Final Preview Duration', 8, 1, 300],
                  'idle_timeout' => ['Idle Timeout', 30, 5, 1800],
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

              <input type="hidden" name="capture_count" value="{{ old('capture_count', 3) }}">

              <div class="flex flex-wrap gap-6 pt-2">
                <label class="flex items-center gap-3 select-none cursor-pointer">
                  <input type="checkbox" name="retake_enabled" value="1" @checked(old('retake_enabled', true)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                  <div class="text-xs font-bold text-slate-700">Izinkan Retake</div>
                </label>

                <label class="flex items-center gap-3 select-none cursor-pointer">
                  <input type="checkbox" name="print_enabled" value="1" @checked(old('print_enabled', false)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                  <div class="text-xs font-bold text-slate-700">Izinkan Print</div>
                </label>

                <label class="flex items-center gap-3 select-none cursor-pointer">
                  <input type="checkbox" name="watermark_enabled" value="1" @checked(old('watermark_enabled', false)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                  <div class="text-xs font-bold text-slate-700">Aktifkan Watermark</div>
                </label>
              </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
              <a href="{{ route('developer.events.index') }}" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors">
                Batal
              </a>
              <button type="submit" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/15 transition-colors">
                Simpan Event Setup
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
