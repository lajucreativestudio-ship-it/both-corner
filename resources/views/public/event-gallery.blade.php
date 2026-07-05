<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery: {{ $event->name }} - Both Corner</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .font-display { font-family: 'Outfit', sans-serif; }
    .public-photo-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
    }
    @media (min-width: 640px) {
      .public-photo-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (min-width: 768px) {
      .public-photo-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
      .public-photo-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
    }
    @media (min-width: 1280px) {
      .public-photo-grid { grid-template-columns: repeat(8, minmax(0, 1fr)); }
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">
  <!-- Header -->
  <header class="bg-white border-b border-slate-200/60 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-indigo-600/10">B</div>
        <div>
          <span class="block text-base font-extrabold text-slate-900 tracking-tight">Both<span class="text-indigo-600">Corner</span></span>
          <span class="text-[9px] uppercase tracking-[0.2em] text-slate-400 font-bold">Public Cloud Gallery</span>
        </div>
      </div>
      <div class="text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 px-3.5 py-1.5 rounded-full">
        🌐 Public View
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="bg-gradient-to-b from-white to-slate-50 border-b border-slate-200/50 py-12 px-6">
    <div class="max-w-7xl mx-auto text-center space-y-4">
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $event->name }}</h1>
      
      <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-500 font-medium">
        @if($event->event_date)
          <span class="flex items-center gap-1.5">
            📅 {{ $event->event_date->format('d M Y') }}
          </span>
        @endif
        @if($event->location)
          <span class="flex items-center gap-1.5">
            📍 {{ $event->location }}
          </span>
        @endif
        <span class="flex items-center gap-1.5 font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100/50 px-3 py-1 rounded-full text-xs">
          📷 {{ $photos->total() }} Final Photos
        </span>
      </div>
    </div>
  </section>

  <!-- Main Grid -->
  <main class="max-w-7xl mx-auto px-6 py-12">
    @if($showAds)
      <div class="mb-8 p-6 bg-amber-50/50 border border-amber-200/60 rounded-3xl text-center shadow-sm max-w-7xl mx-auto">
        <span class="text-[10px] uppercase font-extrabold text-amber-600 tracking-wider block">Sponsored Advertisement</span>
        <div class="h-28 flex items-center justify-center bg-white border border-slate-200 rounded-2xl mt-3 text-slate-400 font-bold text-xs uppercase tracking-widest shadow-inner select-none">
          Google AdSense Banner Placeholder
        </div>
      </div>
    @endif

    @if($photos->isEmpty())
      <div class="bg-white rounded-3xl border border-slate-200/60 p-16 text-center text-slate-400 shadow-sm max-w-lg mx-auto">
        <span class="text-5xl block mb-4">🖼️</span>
        <h3 class="font-extrabold text-slate-900 text-lg">Belum Ada Foto</h3>
        <p class="text-xs text-slate-500 mt-1">Foto-foto jepretan final dari device akan muncul di sini.</p>
      </div>
    @else
      <div class="public-photo-grid">
        @foreach($photos as $photo)
          @php
            $fileUrl = Storage::disk('public')->url($photo->file_path);
          @endphp
          <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all duration-200">
            <!-- Image Link -->
            <a href="{{ $fileUrl }}" target="_blank" class="block aspect-[4/3] bg-slate-100 overflow-hidden relative">
              <img src="{{ $fileUrl }}" alt="{{ $photo->original_filename ?? 'Photo' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </a>

            <div class="p-3.5 border-t border-slate-100 flex flex-col justify-between grow">
              <div>
                <p class="text-[10px] text-slate-400 font-semibold">
                  {{ $photo->uploaded_at ? $photo->uploaded_at->format('d M Y H:i') : '-' }}
                </p>
              </div>

              <!-- Session Sharing Link -->
              @if($photo->booth_session_id && optional($photo->boothSession)->public_token)
                <div class="mt-2 pt-2 border-t border-slate-100/70">
                  <a href="{{ route('public.session-result', $photo->boothSession->public_token) }}" class="inline-flex w-full items-center justify-center gap-1.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[10px] rounded-xl transition-all border border-indigo-100/50">
                    🔗 View Full Session
                  </a>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="mt-12 flex justify-center">
        <div class="bg-white px-4 py-3 rounded-2xl border border-slate-200 shadow-sm">
          {{ $photos->links() }}
        </div>
      </div>
    @endif
  </main>
</body>
</html>
