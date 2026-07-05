<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events - Both Corner Cloud</title>
  
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
    .custom-event-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
    }
    @media (min-width: 640px) {
      .custom-event-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
      }
    }
    @media (min-width: 768px) {
      .custom-event-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
      }
    }
    @media (min-width: 1024px) {
      .custom-event-grid {
        grid-template-columns: repeat(6, minmax(0, 1fr));
      }
    }
    @media (min-width: 1280px) {
      .custom-event-grid {
        grid-template-columns: repeat(8, minmax(0, 1fr));
      }
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
          <h1 class="text-xl font-extrabold text-slate-900">Events</h1>
          <p class="text-xs text-slate-500 mt-1">Kelola cloud gallery, sharing, dan media event photobooth Anda.</p>
        </div>
      </header>

      <div class="p-8">
        <!-- Notice Banner -->
        <div class="bg-indigo-600 text-white rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 mb-6 shadow-lg shadow-indigo-600/15">
          <div class="flex items-center gap-4">
            <span class="text-3xl">📺</span>
            <div class="text-sm">
              <strong class="block text-base">Both Corner Cloud Gallery Manager</strong>
              Event dan media disinkronkan dari booth app; web dipakai untuk galeri, sharing, dan download.
            </div>
          </div>
        </div>

        <form method="GET" action="{{ route('client.events.index') }}" class="mb-8 bg-white rounded-2xl border border-slate-200/70 p-4 shadow-sm">
          <label for="q" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Search Events</label>
          <div class="flex flex-col sm:flex-row gap-3">
            <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Search by event name, location, or slug" class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            <button type="submit" class="px-5 py-3 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 transition-colors">Search</button>
            @if(request('q'))
              <a href="{{ route('client.events.index') }}" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-center">Reset</a>
            @endif
          </div>
        </form>

        <!-- Event Cards Grid -->
        @if($events->isEmpty())
          <div class="bg-white rounded-2xl border border-slate-200/70 p-16 text-center text-slate-400">
            <span class="text-5xl block mb-4">📅</span>
            <h3 class="font-extrabold text-slate-900 text-lg">Belum Ada Event</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Event akan muncul setelah dibuat atau disinkronkan dari booth app/admin.</p>
          </div>
        @else
          <div class="custom-event-grid">
            @foreach($events as $event)
              @php
                // Cover image fallbacks:
                // 1. cover_photo_path
                // 2. First photo from photos relationship
                // 3. Beautiful gradient placeholder
                $coverUrl = null;
                if ($event->cover_photo_path) {
                    $coverUrl = Storage::url($event->cover_photo_path);
                } elseif ($event->photos->isNotEmpty()) {
                    $coverUrl = Storage::url($event->photos->first()->file_path);
                }
              @endphp

              <div class="bg-white rounded-2xl border border-slate-200/50 hover:border-indigo-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                <a href="{{ route('client.events.manage', $event) }}" class="block aspect-[4/3] bg-slate-100 overflow-hidden relative">
                  @if($coverUrl)
                    <img src="{{ $coverUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $event->name }}" loading="lazy">
                  @else
                    <div class="w-full h-full bg-gradient-to-tr from-indigo-500/10 to-purple-500/10 flex flex-col items-center justify-center gap-2 group-hover:scale-105 transition-transform duration-300">
                      <span class="text-4xl">📸</span>
                      <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">No Photos Yet</span>
                    </div>
                  @endif
                </a>

                <div class="p-4 flex-grow flex flex-col justify-between">
                  <div class="space-y-2">
                    <div class="flex items-center justify-between gap-2">
                      @php
                        $status = strtolower($event->status);
                        $badgeClass = match($status) {
                          'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                          'completed' => 'bg-blue-50 text-blue-700 border-blue-100',
                          default => 'bg-slate-100 text-slate-600 border-slate-200'
                        };
                      @endphp
                      <span class="inline-flex px-2 py-0.5 rounded border font-bold text-[9px] uppercase {{ $badgeClass }}">
                        {{ $event->status }}
                      </span>
                      <span class="text-[10px] text-slate-500 font-bold font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                        {{ $event->photos_count }} Photos
                      </span>
                    </div>

                    <a href="{{ route('client.events.manage', $event) }}" class="block">
                      <h3 class="font-bold text-slate-900 text-sm group-hover:text-indigo-600 transition-colors line-clamp-1" title="{{ $event->name }}">
                        {{ $event->name }}
                      </h3>
                    </a>

                    <div class="space-y-1 text-xs text-slate-400 font-medium">
                      <div class="flex items-center gap-1.5">
                        <span>📅</span> {{ $event->event_date ? $event->event_date->format('d M Y') : '-' }}
                      </div>
                      <div class="flex items-center gap-1.5 truncate" title="{{ $event->location }}">
                        <span>📍</span> {{ $event->location ?? '-' }}
                      </div>
                    </div>
                  </div>

                  <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
                    <a href="{{ route('client.events.manage', $event) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
                      Manage
                    </a>
                    <a href="{{ route('client.events.show', $event) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
                      Details
                    </a>
                    <a href="{{ route('client.events.gallery', $event) }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 transition-colors">
                      Open Gallery
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </main>
  </div>

</body>
</html>
