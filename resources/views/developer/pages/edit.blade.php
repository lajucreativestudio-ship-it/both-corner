<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Halaman: {{ $page->title }} - Both Corner</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  <style>
    body {
      font-family: 'Instrument Sans', sans-serif;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

  <!-- Main Header -->
  <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
    <div class="flex items-center gap-3">
      <a href="{{ route('developer.dashboard') }}" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center text-slate-600 font-bold text-sm cursor-pointer select-none">
        ←
      </a>
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">CMS Halaman Statis</span>
        <h1 class="font-extrabold text-slate-900 text-lg leading-tight">Edit Halaman: {{ $page->title }}</h1>
      </div>
    </div>
    
    <div class="flex items-center gap-2">
      <a href="{{ route('developer.dashboard') }}" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-650 bg-slate-100 hover:bg-slate-200 transition-all">Batal</a>
      <button form="edit-page-form" type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 hover:-translate-y-0.5 transition-all cursor-pointer">Simpan Perubahan</button>
    </div>
  </header>

  <!-- Editor Container -->
  <main class="flex-grow max-w-7xl w-full mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Main Fields -->
    <div class="lg:col-span-2 space-y-6">
      @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-250 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold">
          {{ session('success') }}
        </div>
      @endif

      <form id="edit-page-form" action="{{ route('developer.static-pages.update', $page->id) }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-5">
          <h2 class="font-bold text-slate-900 border-b border-slate-100 pb-3 text-sm">Informasi & Struktur Halaman</h2>
          
          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">Nama Halaman (CMS Label)</label>
            <input type="text" name="title" required value="{{ $page->title }}" placeholder="Nama halaman..." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors">
          </div>

          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">Hero Title (Judul Utama Halaman)</label>
            <input type="text" name="hero_title" required value="{{ $page->hero_title }}" placeholder="Ketik judul utama..." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors">
            <span class="text-[10px] text-slate-400">Anda dapat menggunakan tag HTML seperti &lt;br&gt; atau kelas gradasi teks kustom untuk variasi estetika.</span>
          </div>

          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">Hero Subtitle (Deskripsi / Slogan)</label>
            <textarea name="hero_subtitle" rows="5" required placeholder="Tulis deskripsi subjudul di sini..." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors leading-relaxed">{{ $page->hero_subtitle }}</textarea>
          </div>

          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">CTA Button Text (Tombol Utama)</label>
            <input type="text" name="cta_text" required value="{{ $page->cta_text }}" placeholder="Teks tombol..." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors">
          </div>
        </div>
      </form>
    </div>

    <!-- Sidebar Settings -->
    <div class="space-y-6">
      <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-4 text-xs sm:text-sm">
        <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3">Metadata Halaman</h3>
        
        <div class="flex flex-col gap-1">
          <span class="text-[10px] font-bold text-slate-400 uppercase">Slug Sistem</span>
          <span class="font-mono text-slate-600 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100 w-fit">{{ $page->slug }}</span>
        </div>

        <div class="flex flex-col gap-1">
          <span class="text-[10px] font-bold text-slate-400 uppercase">Tipe Konten</span>
          <span class="font-semibold text-slate-700">Static / Dynamic Page Content</span>
        </div>

        <div class="flex flex-col gap-1">
          <span class="text-[10px] font-bold text-slate-400 uppercase">Terakhir Diperbarui</span>
          <span class="text-slate-600 font-semibold">{{ $page->updated_at ? $page->updated_at->diffForHumans() : 'Belum pernah' }}</span>
        </div>
      </div>

      <div class="bg-slate-900 text-slate-400 p-6 rounded-2xl shadow-sm text-xs space-y-2">
        <h4 class="font-bold text-white mb-2">💡 Tips Desain</h4>
        <p>Judul utama mendukung format gradasi teks untuk meningkatkan penampilan estetika halaman utama. Cobalah menyematkan elemen ini pada judul utama Anda:</p>
        <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 font-mono text-[10px] text-indigo-300 select-all overflow-x-auto">
          &lt;span class="bg-gradient-to-r from-violet-600 via-indigo-600 to-indigo-500 bg-clip-text text-transparent"&gt;Teks Gradasi&lt;/span&gt;
        </div>
      </div>
    </div>

  </main>

</body>
</html>
