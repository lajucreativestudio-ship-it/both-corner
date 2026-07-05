<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Template Baru - Both Corner</title>

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
              <a href="{{ route('developer.events.index') }}" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">📅</span>
                <span>Event Management</span>
              </a>
            </li>
            <li>
              <a href="{{ route('developer.templates.index') }}" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20 transition-all duration-200">
                <span class="w-8 h-8 rounded-lg bg-indigo-500/15 text-indigo-300 flex items-center justify-center">🎨</span>
                <span>Template Management</span>
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
          <h1 class="text-xl font-extrabold text-slate-900">Buat Template Baru</h1>
          <p class="text-xs text-slate-500 mt-1">Daftarkan layout dan konfigurasi timer photobooth global baru.</p>
        </div>
        <a href="{{ route('developer.templates.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
          ← Kembali ke Template Management
        </a>
      </header>

      <div class="p-8 max-w-4xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Form Template Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Lengkapi parameter layout, canvas, dan timing berikut.</p>
          </div>

          <form action="{{ route('developer.templates.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Section 1: Basic Info -->
            <div class="space-y-4">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">1. Informasi Dasar Layout</h3>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="name" class="block text-xs font-bold text-slate-600 mb-2">Nama Template</label>
                  <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: 4x6 Birthday Grid" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="template_type" class="block text-xs font-bold text-slate-600 mb-2">Tipe Layout</label>
                  <select id="template_type" name="template_type" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                    <option value="photo_4x6_portrait" @selected(old('template_type') === 'photo_4x6_portrait')>Photo 4x6 Portrait</option>
                    <option value="strip_2x6" @selected(old('template_type') === 'strip_2x6')>Strip 2x6</option>
                    <option value="custom" @selected(old('template_type') === 'custom')>Custom Layout Grid</option>
                  </select>
                  @error('template_type')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                  <label for="orientation" class="block text-xs font-bold text-slate-600 mb-2">Orientasi</label>
                  <select id="orientation" name="orientation" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                    <option value="portrait" @selected(old('orientation') === 'portrait')>Portrait</option>
                    <option value="landscape" @selected(old('orientation') === 'landscape')>Landscape</option>
                  </select>
                  @error('orientation')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="canvas_width" class="block text-xs font-bold text-slate-600 mb-2">Canvas Width (px)</label>
                  <input id="canvas_width" name="canvas_width" type="number" value="{{ old('canvas_width', 1200) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  @error('canvas_width')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="canvas_height" class="block text-xs font-bold text-slate-600 mb-2">Canvas Height (px)</label>
                  <input id="canvas_height" name="canvas_height" type="number" value="{{ old('canvas_height', 1800) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  @error('canvas_height')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="capture_count" class="block text-xs font-bold text-slate-600 mb-2">Capture Count</label>
                  <input id="capture_count" name="capture_count" type="number" min="1" max="10" value="{{ old('capture_count', 3) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  @error('capture_count')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Section 2: Upload assets -->
            <div class="space-y-4 pt-2">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">2. Upload File Overlay & Background (PNG/JPG, Maks 5MB)</h3>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="overlay_file" class="block text-xs font-bold text-slate-600 mb-2">Overlay File (Watermark Transparan PNG)</label>
                  <input id="overlay_file" name="overlay_file" type="file" accept="image/*" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs outline-none bg-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                  @error('overlay_file')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="background_file" class="block text-xs font-bold text-slate-600 mb-2">Background File (Gambar Background Canvas)</label>
                  <input id="background_file" name="background_file" type="file" accept="image/*" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs outline-none bg-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                  @error('background_file')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Section 3: Timing configuration -->
            <div class="space-y-4 pt-2">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">3. Konfigurasi Timer & Delay (Detik)</h3>
              
              <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
                <div>
                  <label for="initial_countdown" class="block text-[10px] font-bold text-slate-600 mb-2">Initial Countdown</label>
                  <input id="initial_countdown" name="initial_countdown" type="number" min="1" max="60" value="{{ old('initial_countdown', 5) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none">
                  @error('initial_countdown')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="between_capture_delay" class="block text-[10px] font-bold text-slate-600 mb-2">Delay Between</label>
                  <input id="between_capture_delay" name="between_capture_delay" type="number" min="0" max="60" value="{{ old('between_capture_delay', 2) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none">
                  @error('between_capture_delay')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="preview_duration" class="block text-[10px] font-bold text-slate-600 mb-2">Preview Duration</label>
                  <input id="preview_duration" name="preview_duration" type="number" min="0" max="60" value="{{ old('preview_duration', 3) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none">
                  @error('preview_duration')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="retake_timeout" class="block text-[10px] font-bold text-slate-600 mb-2">Retake Timeout</label>
                  <input id="retake_timeout" name="retake_timeout" type="number" min="0" max="120" value="{{ old('retake_timeout', 10) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none">
                  @error('retake_timeout')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="final_preview_duration" class="block text-[10px] font-bold text-slate-600 mb-2">Final Preview</label>
                  <input id="final_preview_duration" name="final_preview_duration" type="number" min="0" max="120" value="{{ old('final_preview_duration', 8) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none">
                  @error('final_preview_duration')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="idle_timeout" class="block text-[10px] font-bold text-slate-600 mb-2">Idle Timeout</label>
                  <input id="idle_timeout" name="idle_timeout" type="number" min="1" max="3600" value="{{ old('idle_timeout', 30) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none">
                  @error('idle_timeout')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Section 4: Other configurations -->
            <div class="space-y-4 pt-2">
              <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">4. Status & Aksesibilitas</h3>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 align-middle">
                <div>
                  <label for="status" class="block text-xs font-bold text-slate-600 mb-2">Status</label>
                  <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                    <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                  </select>
                  @error('status')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <div class="flex items-center pt-8">
                  <label class="flex items-center gap-3 select-none cursor-pointer">
                    <input type="checkbox" name="is_global" value="1" @checked(old('is_global', true)) class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div class="text-xs font-bold text-slate-700">Set sebagai Global Preset (Dapat digunakan oleh semua client)</div>
                  </label>
                </div>
              </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
              <a href="{{ route('developer.templates.index') }}" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors">
                Batal
              </a>
              <button type="submit" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/15 transition-colors">
                Simpan & Generate Steps
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
