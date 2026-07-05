<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Monitoring - Both Corner</title>

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
          <h1 class="text-xl font-extrabold text-slate-900">Event Monitoring</h1>
          <p class="text-xs text-slate-500 mt-1">Pantau semua event, owner, device, foto, public link, dan status sinkronisasi booth.</p>
        </div>
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
              <h2 class="font-bold text-slate-900">Event Monitoring List</h2>
              <p class="text-xs text-slate-500 mt-1">Total terdaftar: {{ $events->count() }} event.</p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4 pl-6">Nama Event</th>
                  <th class="p-4">Client Owner</th>
                  <th class="p-4">Tanggal</th>
                  <th class="p-4">Lokasi</th>
                  <th class="p-4">Status</th>
                  <th class="p-4">Visibility</th>
                  <th class="p-4">Total Foto</th>
                  <th class="p-4">Assigned Devices</th>
                  <th class="p-4 text-right pr-6">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($events as $event)
                  @php
                    $status = strtolower($event->status);
                    $statusClass = match($status) {
                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'completed' => 'bg-blue-50 text-blue-700 border-blue-100',
                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                    };
                    $visibility = strtolower($event->gallery_visibility);
                    $visibilityClass = $visibility === 'public'
                        ? 'bg-purple-50 text-purple-700 border-purple-100'
                        : 'bg-slate-100 text-slate-600 border-slate-200';
                  @endphp
                  <tr class="hover:bg-slate-50/60 align-middle">
                    <td class="p-4 pl-6">
                      <div class="font-bold text-slate-900">{{ $event->name }}</div>
                      <div class="text-[10px] text-slate-400 font-mono mt-0.5">Slug: {{ $event->slug }}</div>
                    </td>
                    <td class="p-4 text-slate-700">
                      <div>{{ $event->user->name }}</div>
                      <div class="text-[11px] text-slate-400">{{ $event->user->email }}</div>
                    </td>
                    <td class="p-4 text-slate-600">
                      {{ $event->event_date ? $event->event_date->format('d M Y') : '-' }}
                    </td>
                    <td class="p-4 text-slate-600">{{ $event->location ?? '-' }}</td>
                    <td class="p-4">
                      <span class="inline-flex px-2 py-0.5 rounded border font-bold text-[10px] uppercase {{ $statusClass }}">
                        {{ $event->status }}
                      </span>
                    </td>
                    <td class="p-4">
                      <span class="inline-flex px-2 py-0.5 rounded border font-bold text-[10px] uppercase {{ $visibilityClass }}">
                        {{ $event->gallery_visibility }}
                      </span>
                    </td>
                    <td class="p-4 font-semibold text-slate-700">{{ $event->photos_count }} Foto</td>
                    <td class="p-4 font-semibold text-slate-700">
                      <span class="px-2 py-0.5 bg-slate-50 rounded border border-slate-200">
                        {{ $deviceCounts[$event->id] ?? 0 }} Device
                      </span>
                    </td>
                    <td class="p-4 text-right pr-6">
                      <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('developer.events.manage', $event) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 transition-colors inline-block">
                          Monitoring Center
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="p-12 text-center text-slate-400">
                      <div class="text-4xl mb-3">📅</div>
                      <p class="font-bold text-slate-800">Belum ada event photobooth.</p>
                      <p class="text-xs mt-1">Event akan muncul setelah dibuat atau disinkronkan dari booth app/admin foundation.</p>
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
