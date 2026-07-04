<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Halaman Statis Baru - Both Corner</title>
  
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
        <h1 class="font-extrabold text-slate-900 text-lg leading-tight">Buat Halaman Statis Baru</h1>
      </div>
    </div>
    
    <div class="flex items-center gap-2">
      <a href="{{ route('developer.dashboard') }}" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-650 bg-slate-100 hover:bg-slate-200 transition-all">Batal</a>
      <button form="create-page-form" type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 hover:-translate-y-0.5 transition-all cursor-pointer">Simpan Halaman</button>
    </div>
  </header>

  <!-- Editor Container -->
  <main class="flex-grow max-w-7xl w-full mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Main Fields -->
    <div class="lg:col-span-2 space-y-6">
      <form id="create-page-form" action="{{ route('developer.static-pages.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm space-y-5">
          <h2 class="font-bold text-slate-900 border-b border-slate-100 pb-3 text-sm">Informasi & Struktur Halaman Baru</h2>
          
          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">Nama Halaman (CMS Label)</label>
            <input type="text" name="title" id="page-title" required oninput="updateSlug()" placeholder="Contoh: Tentang Kami, Cara Membayar, dll." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors">
          </div>

          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">Slug (URL)</label>
            <input type="text" name="slug" id="page-slug" required placeholder="url-halaman-statis" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 font-mono text-slate-800 transition-colors">
          </div>

          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">Hero Title (Judul Utama Halaman)</label>
            <input type="text" name="hero_title" required placeholder="Ketik judul utama hero..." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors">
          </div>

          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">Hero Subtitle (Deskripsi / Slogan)</label>
            <textarea name="hero_subtitle" rows="5" required placeholder="Tulis deskripsi penjelasan halaman..." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors leading-relaxed"></textarea>
          </div>

          <div class="flex flex-col gap-1.5 text-xs sm:text-sm">
            <label class="font-bold text-slate-700">CTA Button Text (Tombol Utama)</label>
            <input type="text" name="cta_text" required placeholder="Contoh: Coba Sekarang" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-800 transition-colors">
          </div>
        </div>
      </form>
    </div>

    <!-- Sidebar Settings -->
    <div class="space-y-6">
      <div class="bg-slate-900 text-slate-400 p-6 rounded-2xl shadow-sm text-xs space-y-2">
        <h4 class="font-bold text-white mb-2">💡 Tips Desain</h4>
        <p>Judul utama mendukung format gradasi teks untuk meningkatkan penampilan estetika halaman utama. Cobalah menyematkan elemen ini pada judul utama Anda:</p>
        <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 font-mono text-[10px] text-indigo-300 select-all overflow-x-auto">
          &lt;span class="bg-gradient-to-r from-violet-600 via-indigo-600 to-indigo-500 bg-clip-text text-transparent"&gt;Teks Gradasi&lt;/span&gt;
        </div>
      </div>
    </div>

  </main>

  <script>
    function updateSlug() {
      const title = document.getElementById('page-title').value;
      const slugInput = document.getElementById('page-slug');
      slugInput.value = title.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '');
    }
  </script>

</body>
</html>
