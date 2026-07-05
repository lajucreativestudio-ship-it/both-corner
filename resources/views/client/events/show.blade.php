<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Event: {{ $event->name }} - Both Corner Cloud</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Laravel Vite / Tailwind CSS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    h1, h2, h3, h4, .font-display {
      font-family: 'Outfit', sans-serif;
    }
  </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased">

  <div class="flex min-h-screen">
    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-[#1a1d24] text-slate-400 flex flex-col justify-between select-none shrink-0">
      <div>
        <div class="p-6 border-b border-slate-800">
          <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-500 to-violet-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-indigo-500/20">B</div>
            <span class="text-lg font-bold text-white tracking-tight">Both<span class="text-indigo-400">Corner</span></span>
          </a>
        </div>
        <ul class="mt-6 flex flex-col gap-1 px-4">
          <li class="rounded-xl overflow-hidden" id="menu-events">
            <a href="{{ route('client.events.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white bg-indigo-600/10 border-l-4 border-indigo-500 transition-all duration-200">
              <span class="text-base">🏠</span> Events
            </a>
          </li>
          <li class="rounded-xl overflow-hidden" id="menu-settings">
            <a href="{{ route('dashboard') }}?panel=settings" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">🔧</span> Settings
            </a>
          </li>
          <li class="rounded-xl overflow-hidden" id="menu-subscriptions">
            <a href="{{ route('dashboard') }}?panel=subscriptions" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">💳</span> Subscriptions
            </a>
          </li>
          <li class="rounded-xl overflow-hidden" id="menu-refer-earn">
            <a href="{{ route('dashboard') }}?panel=refer-earn" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">💡</span> Refer & Earn
            </a>
          </li>
          <li class="rounded-xl overflow-hidden" id="menu-copilot">
            <a href="{{ route('dashboard') }}?panel=copilot" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">📷</span> Booth Copilot
            </a>
          </li>
          <li class="rounded-xl overflow-hidden" id="menu-help">
            <a href="{{ route('dashboard') }}?panel=help" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">❓</span> Help
            </a>
          </li>
          <li class="rounded-xl overflow-hidden" id="menu-licenses">
            <a href="{{ route('dashboard') }}?panel=licenses" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">🔐</span> Lisensi & Device
            </a>
          </li>
          <li class="rounded-xl overflow-hidden" id="menu-logout">
            <form action="{{ route('logout') }}" method="POST" class="block w-full">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-400 transition-all duration-200 hover:text-white hover:bg-red-950/20 border-l-4 border-transparent text-left cursor-pointer">
                <span class="text-base">🚪</span> Log Out
              </button>
            </form>
          </li>
        </ul>
      </div>
      
      <!-- Profile section -->
      <div class="p-4 border-t border-slate-800 bg-[#15171d] relative">
        <div class="flex items-center justify-between p-1.5 rounded-xl">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md">LS</div>
            <div class="flex flex-col min-w-0">
              <span class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</span>
              <span class="text-[10px] text-slate-500 truncate">Client</span>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0">
      <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between shrink-0">
        <div>
          <h1 class="text-xl font-extrabold text-slate-900 truncate">Event: {{ $event->name }}</h1>
          <p class="text-xs text-slate-500 mt-1">Detail informasi, konfigurasi, dan galeri foto event photobooth.</p>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('client.events.manage', $event) }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 transition-colors">
            Manage Gallery
          </a>
          <a href="{{ route('client.events.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">← Kembali ke Daftar Event</a>
        </div>
      </header>

      <div class="p-8 max-w-4xl space-y-6">
        <!-- Event Overview Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 p-6 md:p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <div class="space-y-2">
            <div class="flex items-center gap-3">
              <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $event->name }}</h2>
              @php
                $status = strtolower($event->status);
                $badgeClass = match($status) {
                  'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                  'completed' => 'bg-blue-50 text-blue-700 border-blue-100',
                  default => 'bg-slate-100 text-slate-600 border-slate-200'
                };
              @endphp
              <span class="inline-flex px-2.5 py-1 rounded-full border font-bold text-[10px] uppercase {{ $badgeClass }}">
                {{ $event->status }}
              </span>
            </div>
            
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">
              <span class="flex items-center gap-1.5">
                📅 {{ $event->event_date ? $event->event_date->format('d M Y') : '-' }}
              </span>
              <span class="flex items-center gap-1.5">
                📍 {{ $event->location ?? '-' }}
              </span>
              <span class="flex items-center gap-1.5 font-semibold text-slate-800">
                📷 {{ $event->photos_count }} Foto Ter-upload
              </span>
            </div>
          </div>

          <div class="flex flex-wrap gap-3 shrink-0">
            <a href="{{ route('client.events.manage', $event) }}" class="px-6 py-3 rounded-xl text-sm font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 transition-all duration-200 cursor-pointer">
              Manage Gallery
            </a>
            <a href="{{ route('client.events.gallery', $event) }}" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/15 transition-all duration-200 cursor-pointer">
              Buka Galeri Foto →
            </a>
          </div>
        </div>

        <!-- Event Settings -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100">
            <h3 class="font-extrabold text-slate-900 text-base">Konfigurasi & Settings Basic</h3>
            <p class="text-xs text-slate-500 mt-1">Pengaturan photobooth yang aktif untuk event ini.</p>
          </div>

          @if($setting)
            <div class="p-6 md:p-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
              <!-- Layout Type -->
              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Layout Type</span>
                <span class="text-lg font-extrabold text-slate-800 mt-2 capitalize">{{ $setting->layout_type }}</span>
              </div>

              <!-- Countdown Seconds -->
              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Countdown</span>
                <span class="text-lg font-extrabold text-slate-800 mt-2">{{ $setting->countdown_seconds }} Detik</span>
              </div>

              <!-- Capture Count -->
              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Capture</span>
                <span class="text-lg font-extrabold text-slate-800 mt-2">{{ $setting->capture_count }} Kali</span>
              </div>

              <!-- Retake Enabled -->
              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Retake Foto</span>
                <div class="mt-2">
                  @if($setting->retake_enabled)
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>
                  @else
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">Nonaktif</span>
                  @endif
                </div>
              </div>

              <!-- Print Enabled -->
              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cetak Langsung (Print)</span>
                <div class="mt-2">
                  @if($setting->print_enabled)
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>
                  @else
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">Nonaktif</span>
                  @endif
                </div>
              </div>

              <!-- Watermark Enabled -->
              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Watermark / Overlay</span>
                <div class="mt-2">
                  @if($setting->watermark_enabled)
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>
                  @else
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">Nonaktif</span>
                  @endif
                </div>
              </div>
            </div>
          @else
            <div class="p-12 text-center text-slate-400">
              <span class="text-3xl block mb-2">🔧</span>
              <p class="text-sm font-semibold">Belum ada setting yang terkonfigurasi untuk event ini.</p>
              <p class="text-xs mt-1 text-slate-400">Hubungkan device kamera client untuk menyinkronkan setting event otomatis.</p>
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>

</body>
</html>
