<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $article->title }} - Both Corner</title>
  
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

  <!-- Article Details -->
  <main class="py-16 max-w-4xl mx-auto px-6">
    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:underline mb-8">
      ← Kembali ke Blog
    </a>

    <!-- Header info -->
    <div class="mb-8">
      <div class="flex items-center gap-2.5 text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">
        <span class="text-indigo-600">{{ $article->category }}</span>
        <span>•</span>
        <span>{{ $article->created_at->format('d M Y') }}</span>
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
        {{ $article->title }}
      </h1>
      
      @if($article->tags)
        <div class="flex flex-wrap gap-1.5 mt-4">
          @foreach(explode(',', $article->tags) as $tag)
            <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 px-2 py-0.5 rounded text-[10px] font-bold">#{{ trim($tag) }}</span>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Featured Image -->
    @if($article->image_url)
      <div class="bg-white rounded-3xl border border-slate-200/50 p-8 flex items-center justify-center h-80 overflow-hidden mb-10 shadow-sm">
        <img src="{{ asset($article->image_url) }}" class="max-h-full max-w-full object-contain" alt="{{ $article->title }}">
      </div>
    @endif

    <!-- Content -->
    <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-6">
      {!! $parsedContent !!}
    </div>

    <!-- Related Articles -->
    @if($related->count() > 0)
      <div class="border-t border-slate-200 pt-16 mt-16">
        <h3 class="text-xl font-bold text-slate-900 mb-6">Artikel Terkait</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          @foreach($related as $rel)
            <div class="bg-white p-5 rounded-2xl border border-slate-200/50 hover:shadow-md transition-shadow">
              <span class="text-[10px] text-indigo-600 font-bold uppercase">{{ $rel->category }}</span>
              <h4 class="text-sm font-bold text-slate-900 mt-2 line-clamp-2 hover:text-indigo-600 transition-colors">
                <a href="{{ route('blog.show', $rel->slug) }}">{{ $rel->title }}</a>
              </h4>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </main>

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
