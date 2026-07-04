<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog & Berita Terbaru - Both Corner</title>
  
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
<body class="bg-slate-50 text-slate-900 overflow-x-hidden antialiased">

  <!-- Header / Navigation -->
  <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/80 border-b border-slate-200/50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
      <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-indigo-500/20">B</div>
        <span class="text-xl font-bold tracking-tight text-slate-900">Both<span class="text-indigo-600">Corner</span></span>
      </a>
      
      <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
        <a href="{{ route('landing') }}" class="hover:text-indigo-600 transition-colors">Beranda</a>
        @foreach($menus as $menu)
          <a href="{{ route('landing') }}{{ $menu->url }}" class="hover:text-indigo-600 transition-colors">{{ $menu->title }}</a>
        @endforeach
      </nav>

      <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/25 transition-all duration-200 hover:-translate-y-0.5">Buka Dashboard</a>
      </div>
    </div>
  </header>

  <!-- Hero Blog Section -->
  <section class="py-16 bg-gradient-to-b from-indigo-50/50 to-slate-50 border-b border-slate-250/20">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <span class="px-3 py-1 rounded-full bg-indigo-55/10 text-indigo-600 text-xs font-extrabold uppercase tracking-wider">Blog & Berita</span>
      <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mt-4 max-w-2xl mx-auto">
        Kabar Terbaru, Panduan, & Catatan Rilis
      </h1>
      <p class="text-slate-500 text-sm md:text-base mt-4 max-w-lg mx-auto">
        Temukan informasi seputar perangkat yang kompatibel, pembaruan fitur, dan tips operasional photobooth Both Corner di sini.
      </p>
    </div>
  </section>

  <!-- Blog Listing -->
  <section class="py-16 max-w-7xl mx-auto px-6">
    @if($articles->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($articles as $article)
          <article class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div>
              <!-- Feature image -->
              <div class="h-48 bg-slate-100 overflow-hidden relative flex items-center justify-center p-4 border-b border-slate-100">
                <img src="{{ asset($article->image_url ?: 'login_slide_1.png') }}" class="max-h-full max-w-full object-contain" alt="{{ $article->title }}">
              </div>
              <div class="p-6">
                <!-- Meta information -->
                <div class="flex items-center gap-2.5 text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">
                  <span class="text-indigo-600">{{ $article->category }}</span>
                  <span>•</span>
                  <span>{{ $article->created_at->format('d M Y') }}</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 leading-snug hover:text-indigo-600 transition-colors">
                  <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                </h2>
                <p class="text-slate-500 text-xs mt-3 line-clamp-3 leading-relaxed">
                  {{ strip_tags($article->content) }}
                </p>
                @if($article->tags)
                  <div class="flex flex-wrap gap-1 mt-3">
                    @foreach(explode(',', $article->tags) as $tag)
                      <span class="bg-slate-100 text-slate-550 px-2 py-0.5 rounded text-[9px] font-bold">#{{ trim($tag) }}</span>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
            <div class="p-6 pt-0">
              <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                Baca Selengkapnya <span>→</span>
              </a>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <div class="text-center py-20 bg-white rounded-3xl border border-slate-200/50">
        <span class="text-5xl block mb-4">📰</span>
        <h3 class="text-lg font-bold text-slate-900">Belum Ada Artikel</h3>
        <p class="text-slate-400 text-sm mt-1">Kami akan segera menerbitkan kabar menarik di sini.</p>
      </div>
    @endif
  </section>

  <!-- Footer -->
  <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
      <a href="{{ route('landing') }}" class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold">B</div>
        <span class="text-lg font-bold text-white">Both<span class="text-indigo-400">Corner</span></span>
      </a>
      <span class="text-xs text-slate-500">© 2026 Both Corner Cloud. All rights reserved.</span>
    </div>
  </footer>

</body>
</html>
