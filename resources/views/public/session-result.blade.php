<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Session {{ $session->session_code }} - Both Corner</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .font-display { font-family: 'Outfit', sans-serif; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">
  <!-- Header -->
  <header class="bg-white border-b border-slate-200/60 sticky top-0 z-50 shadow-sm">
    <div class="max-w-4xl mx-auto px-6 h-20 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-indigo-600/10">B</div>
        <div>
          <span class="block text-base font-extrabold text-slate-900 tracking-tight">Both<span class="text-indigo-600">Corner</span></span>
          <span class="text-[9px] uppercase tracking-[0.2em] text-slate-400 font-bold">Session Sharing</span>
        </div>
      </div>
      @if($event->gallery_visibility === 'public')
        <a href="{{ route('public.event-gallery', $event->slug) }}" class="text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-4 py-2 rounded-xl transition-all">
          ← Lihat Semua Foto
        </a>
      @endif
    </div>
  </header>

  <main class="max-w-4xl mx-auto px-6 py-12 space-y-8">
    @if($showAds)
      <div class="p-6 bg-amber-50/50 border border-amber-200/60 rounded-3xl text-center shadow-sm">
        <span class="text-[10px] uppercase font-extrabold text-amber-600 tracking-wider block">Sponsored Advertisement</span>
        <div class="h-28 flex items-center justify-center bg-white border border-slate-200 rounded-2xl mt-3 text-slate-400 font-bold text-xs uppercase tracking-widest shadow-inner select-none">
          Google AdSense Banner Placeholder
        </div>
      </div>
    @endif

    <!-- Event Details Card -->
    <div class="bg-white rounded-3xl border border-slate-200/60 p-6 md:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <span class="text-[9px] uppercase tracking-widest font-extrabold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">BOOTH SESSION</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-2">{{ $event->name }}</h1>
        <p class="text-xs text-slate-400 mt-1">Session Code: <span class="font-mono font-bold text-slate-600">{{ $session->session_code }}</span></p>
      </div>
      
      <div class="text-xs text-slate-500 space-y-1 text-left sm:text-right font-medium">
        @if($event->event_date)
          <div>📅 {{ $event->event_date->format('d M Y') }}</div>
        @endif
        @if($event->location)
          <div>📍 {{ $event->location }}</div>
        @endif
      </div>
    </div>

    <!-- Final Outputs Section -->
    <div class="space-y-4">
      <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
        <span>✨</span> Hasil Cetak / Final Photo
      </h2>

      @if($finalPhotos->isEmpty())
        <div class="p-12 text-center border border-dashed border-slate-200 bg-white rounded-3xl text-slate-400 text-xs">
          Belum ada file foto final terunggah untuk sesi ini.
        </div>
      @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          @foreach($finalPhotos as $final)
            @php
              $finalUrl = Storage::disk('public')->url($final->file_path);
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-4 space-y-4 flex flex-col justify-between group">
              <a href="{{ $finalUrl }}" target="_blank" class="block bg-slate-100 rounded-2xl overflow-hidden aspect-[3/4] relative">
                <img src="{{ $finalUrl }}" alt="Final Templated Output" class="w-full h-full object-contain">
              </a>
              <div class="flex items-center gap-2">
                <a href="{{ $finalUrl }}" target="_blank" class="flex-1 py-2.5 rounded-xl text-center text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                  Buka Gambar
                </a>
                <a href="{{ $finalUrl }}" download="final_{{ $session->session_code }}.jpg" class="flex-1 py-2.5 rounded-xl text-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10">
                  Download
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Raw Snaps Section -->
    <div class="space-y-4 pt-4 border-t border-slate-200/60">
      <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
        <span>📸</span> Jepretan Kamera (Raw Snaps)
      </h2>

      @if($rawPhotos->isEmpty())
        <div class="p-8 text-center bg-slate-100/50 rounded-2xl text-slate-400 text-xs">
          Tidak ada jepretan kamera mentah (raw snaps) untuk sesi ini.
        </div>
      @else
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          @foreach($rawPhotos as $raw)
            @php
              $rawUrl = Storage::disk('public')->url($raw->file_path);
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-2 flex flex-col justify-between">
              <a href="{{ $rawUrl }}" target="_blank" class="block aspect-square bg-slate-100 rounded-xl overflow-hidden">
                <img src="{{ $rawUrl }}" alt="Raw Snap {{ $raw->step_number }}" class="w-full h-full object-cover">
              </a>
              
              <div class="mt-2 text-center text-[10px] font-bold text-slate-400">
                Snap #{{ $raw->step_number ?? '-' }}
              </div>

              <div class="mt-2 flex items-center gap-1">
                <a href="{{ $rawUrl }}" download="snap_{{ $raw->step_number }}_{{ $session->session_code }}.jpg" class="w-full py-1.5 rounded-lg text-center text-[10px] font-bold text-slate-600 bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-200/60">
                  Download
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Share placeholder footer -->
    <div class="p-6 bg-slate-900 text-slate-400 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
      <div class="space-y-1">
        <h3 class="text-white text-sm font-bold">Butuh QR Code sesi ini?</h3>
        <p class="text-xs">Copy URL sesi ini untuk di-share atau dibuat QR generator luar.</p>
      </div>
      <div class="flex items-center gap-2 w-full sm:w-auto">
        <input type="text" readonly value="{{ url('/s/' . $session->public_token) }}" id="sessionUrlInput" class="bg-slate-800 text-slate-300 text-xs rounded-xl px-4 py-2.5 outline-none font-mono flex-1 sm:w-64 border border-slate-700">
        <button onclick="copySessionUrl()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shrink-0">
          Copy
        </button>
      </div>
  </main>

  @if($showWatermark)
    <footer class="py-8 text-center text-slate-400 text-xs font-semibold">
      Powered by <span class="text-indigo-600 font-extrabold font-display">BothCorner</span>
    </footer>
  @endif

  <script>
    function copySessionUrl() {
      var copyText = document.getElementById("sessionUrlInput");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(copyText.value);
      alert("URL berhasil disalin ke clipboard!");
    }
  </script>
</body>
</html>
