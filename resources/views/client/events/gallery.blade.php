<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery: {{ $event->name }} - Both Corner Cloud</title>
  
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
        <div class="min-w-0">
          <h1 class="text-xl font-extrabold text-slate-900 truncate">Gallery: {{ $event->name }}</h1>
          <p class="text-xs text-slate-500 mt-1">Daftar semua foto digital yang di-upload oleh device untuk event ini.</p>
        </div>
        <div class="flex items-center gap-4 shrink-0">
          <a href="{{ route('client.events.manage', $event) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-200/60 px-3.5 py-2 rounded-xl transition-all">
            Manage Gallery
          </a>
          <a href="{{ route('client.events.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
            ← Back to Events
          </a>
        </div>
      </header>

      <div class="p-8 space-y-6">
        <!-- Gallery Summary Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <span class="text-2xl">📸</span>
            <div>
              <p class="text-sm font-bold text-slate-800">Total Foto Event</p>
              <p class="text-xs text-slate-400">Total terhitung di server: {{ $photos->total() }} foto.</p>
            </div>
          </div>
          <div class="text-xs font-mono text-slate-500 bg-slate-50 border border-slate-200/60 px-3 py-1.5 rounded-xl">
            Halaman {{ $photos->currentPage() }} dari {{ $photos->lastPage() }}
          </div>
        </div>

        <!-- Photos Grid -->
        @if($photos->isEmpty())
          <div class="bg-white rounded-2xl border border-slate-200/70 p-16 text-center text-slate-400">
            <span class="text-5xl block mb-4">🖼️</span>
            <h3 class="font-extrabold text-slate-900 text-lg">Belum Ada Foto</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Upload foto dari aplikasi photobooth device menggunakan API Photo Upload untuk melihat foto tampil di sini.</p>
          </div>
        @else
          <div class="custom-event-grid">
            @foreach($photos as $photo)
              @php
                $fileUrl = Storage::url($photo->file_path);
              @endphp
              <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all duration-200">
                <!-- Clickable Image to Open in new tab -->
                <a href="{{ $fileUrl }}" target="_blank" class="block aspect-[4/3] bg-slate-100 overflow-hidden relative">
                  <img src="{{ $fileUrl }}" alt="{{ $photo->original_filename ?? 'Photo' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </a>
                <div class="p-3 border-t border-slate-100 flex flex-col justify-between grow">
                  <div>
                    <p class="text-xs font-bold text-slate-800 truncate" title="{{ $photo->original_filename ?? 'Photo' }}">
                      {{ $photo->original_filename ?? 'Untitled Photo' }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">
                      Uploaded: {{ $photo->uploaded_at ? $photo->uploaded_at->format('d M Y H:i:s') : '-' }}
                    </p>
                    @if($photo->booth_session_id && optional($photo->boothSession)->public_token)
                      <div class="mt-2">
                        <a href="{{ url('/s/' . $photo->boothSession->public_token) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 hover:underline">
                          🔗 View Session
                        </a>
                      </div>
                    @endif
                  </div>
                  <div class="mt-3 grid grid-cols-2 gap-2">
                    <a href="{{ $fileUrl }}" target="_blank" class="px-2 py-1.5 rounded-lg text-center text-[11px] font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                      Buka
                    </a>
                    <a href="{{ $fileUrl }}" download="{{ $photo->original_filename ?? 'photo.jpg' }}" class="px-2 py-1.5 rounded-lg text-center text-[11px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10">
                      Download
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Laravel Pagination -->
          <div class="mt-8 flex justify-center">
            <div class="bg-white px-4 py-3 rounded-2xl border border-slate-200 shadow-sm">
              {{ $photos->links() }}
            </div>
          </div>
        @endif
      </div>
    </main>
  </div>

</body>
</html>
