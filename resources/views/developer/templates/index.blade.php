<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Global Template Library - Both Corner</title>

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
    @include('developer.sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0">
      <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between shrink-0">
        <div>
          <h1 class="text-xl font-extrabold text-slate-900">Global Template Library</h1>
          <p class="text-xs text-slate-500 mt-1">Master template presets untuk booth app dan admin override. Event setup utama dilakukan dari booth app.</p>
        </div>
        <a href="{{ route('developer.templates.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 transition-colors">
          + Buat Template Baru
        </a>
      </header>

      <div class="p-8">
        @if(session('success'))
          <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold mb-6 flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
          </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
              <h2 class="font-bold text-slate-900">Master Template Presets</h2>
              <p class="text-xs text-slate-500 mt-1">Total terdaftar: {{ $templates->count() }} template.</p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4 pl-6">Nama Template</th>
                  <th class="p-4">Tipe Layout</th>
                  <th class="p-4">Orientasi</th>
                  <th class="p-4">Canvas Size</th>
                  <th class="p-4">Jepretan (Count)</th>
                  <th class="p-4">Global Preset</th>
                  <th class="p-4">Status</th>
                  <th class="p-4">Overlay / Background</th>
                  <th class="p-4 text-right pr-6">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($templates as $template)
                  @php
                    $isGlobal = $template->is_global;
                    $statusClass = $template->status === 'active'
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                        : 'bg-rose-50 text-rose-700 border-rose-100';
                  @endphp
                  <tr class="hover:bg-slate-50/60 align-middle">
                    <td class="p-4 pl-6 font-bold text-slate-900">
                      {{ $template->name }}
                    </td>
                    <td class="p-4 text-slate-700 font-semibold uppercase tracking-wider text-[11px]">
                      {{ str_replace('_', ' ', $template->template_type) }}
                    </td>
                    <td class="p-4 text-slate-600 capitalize">{{ $template->orientation }}</td>
                    <td class="p-4 text-slate-500 font-mono">
                      {{ $template->canvas_width }} x {{ $template->canvas_height }} px
                    </td>
                    <td class="p-4 font-semibold text-slate-700">{{ $template->capture_count }}x Foto</td>
                    <td class="p-4">
                      @if($isGlobal)
                        <span class="inline-flex px-2 py-0.5 rounded border border-indigo-100 bg-indigo-50 text-indigo-700 font-bold text-[10px] uppercase">
                          GLOBAL
                        </span>
                      @else
                        <span class="inline-flex px-2 py-0.5 rounded border border-slate-200 bg-slate-50 text-slate-400 font-bold text-[10px] uppercase">
                          CUSTOM
                        </span>
                      @endif
                    </td>
                    <td class="p-4">
                      <span class="inline-flex px-2 py-0.5 rounded border font-bold text-[10px] uppercase {{ $statusClass }}">
                        {{ $template->status }}
                      </span>
                    </td>
                    <td class="p-4 text-slate-400">
                      <div class="flex items-center gap-3">
                        @if($template->overlay_path)
                          <a href="{{ Storage::url($template->overlay_path) }}" target="_blank" class="px-2 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200 text-[10px] font-bold">Overlay PNG</a>
                        @endif
                        @if($template->background_path)
                          <a href="{{ Storage::url($template->background_path) }}" target="_blank" class="px-2 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200 text-[10px] font-bold">Bg Image</a>
                        @endif
                        @if(!$template->overlay_path && !$template->background_path)
                          <span class="text-[10px] italic">No Custom Assets</span>
                        @endif
                      </div>
                    </td>
                    <td class="p-4 text-right pr-6">
                      <a href="{{ route('developer.templates.edit', $template) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 transition-colors inline-block">
                        Edit & Steps
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="p-12 text-center text-slate-400">
                      <div class="text-4xl mb-3">🎨</div>
                      <p class="font-bold text-slate-800">Belum ada template cetak.</p>
                      <p class="text-xs mt-1">Buat template global baru dengan menekan tombol di kanan atas.</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
