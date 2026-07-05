<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Gallery: {{ $event->name }} - Both Corner Cloud</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .font-display { font-family: 'Outfit', sans-serif; }
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 14px; }
  </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased">
  @php
    $cloud = array_merge([
      'show_event_date' => true,
      'link_sharing_enabled' => $event->gallery_visibility === 'public',
      'guest_access_enabled' => true,
      'download_all_enabled' => false,
      'password' => null,
      'website' => null,
    ], $cloudSettings ?? []);
    $publicUrl = url('/e/' . $event->slug);
    $features = $license['features'] ?? [];
  @endphp

  <div class="flex min-h-screen">
    <aside class="w-64 bg-[#1a1d24] text-slate-400 flex flex-col justify-between select-none shrink-0">
      <div>
        <div class="p-6 border-b border-slate-800">
          <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-500 to-violet-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-indigo-500/20">B</div>
            <span class="text-lg font-bold text-white tracking-tight">Both<span class="text-indigo-400">Corner</span></span>
          </a>
        </div>
        <ul class="mt-6 flex flex-col gap-1 px-4">
          <li class="rounded-xl overflow-hidden"><a href="{{ route('client.events.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white bg-indigo-600/10 border-l-4 border-indigo-500 transition-all duration-200"><span class="text-base">🏠</span> Events</a></li>
          <li class="rounded-xl overflow-hidden"><a href="{{ route('dashboard') }}?panel=settings" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200"><span class="text-base">🔧</span> Settings</a></li>
          <li class="rounded-xl overflow-hidden"><a href="{{ route('dashboard') }}?panel=subscriptions" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200"><span class="text-base">💳</span> Subscriptions</a></li>
          <li class="rounded-xl overflow-hidden"><a href="{{ route('dashboard') }}?panel=refer-earn" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200"><span class="text-base">💡</span> Refer & Earn</a></li>
          <li class="rounded-xl overflow-hidden"><a href="{{ route('dashboard') }}?panel=copilot" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200"><span class="text-base">📷</span> Booth Copilot</a></li>
          <li class="rounded-xl overflow-hidden"><a href="{{ route('dashboard') }}?panel=help" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200"><span class="text-base">❓</span> Help</a></li>
          <li class="rounded-xl overflow-hidden"><a href="{{ route('dashboard') }}?panel=licenses" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200"><span class="text-base">🔐</span> Lisensi & Device</a></li>
          <li class="rounded-xl overflow-hidden">
            <form action="{{ route('logout') }}" method="POST" class="block w-full">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-400 transition-all duration-200 hover:text-white hover:bg-red-950/20 border-l-4 border-transparent text-left cursor-pointer"><span class="text-base">🚪</span> Log Out</button>
            </form>
          </li>
        </ul>
      </div>
      <div class="p-4 border-t border-slate-800 bg-[#15171d]">
        <div class="flex items-center gap-3 p-1.5 rounded-xl">
          <div class="w-10 h-10 rounded-full bg-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</div>
          <div class="flex flex-col min-w-0">
            <span class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</span>
            <span class="text-[10px] text-slate-500 truncate">Client</span>
          </div>
        </div>
      </div>
    </aside>

    <main class="flex-1 min-w-0">
      <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between">
        <div class="min-w-0">
          <h1 class="text-xl font-extrabold text-slate-900 truncate">{{ $event->name }}</h1>
          <p class="text-xs text-slate-500 mt-1">Cloud gallery manager, sharing controls, and media downloads.</p>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('client.events.gallery', $event) }}" class="px-4 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100">Open Gallery</a>
          <a href="{{ route('client.events.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Back to Events</a>
        </div>
      </header>

      <div class="p-8 space-y-6">
        @if(session('success'))
          <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-sm text-emerald-700 font-semibold">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-[360px_1fr] gap-6">
          <aside class="space-y-6">
            <form action="{{ route('client.events.cloud-settings.update', $event) }}" method="POST" class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
              @csrf
              @method('PUT')
              <div class="p-5 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-900">Gallery Settings</h2>
                <p class="text-xs text-slate-500 mt-1">Booth design is configured in Booth App.</p>
              </div>
              <div class="p-5 space-y-4">
                <a href="{{ url('/e/' . $event->slug) }}" target="_blank" class="block text-center px-4 py-3 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700">Preview Public Page</a>
                <button type="button" disabled class="w-full px-4 py-3 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 cursor-not-allowed">Configure design in Booth App</button>

                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="name">Event Name</label>
                  <input id="name" name="name" type="text" value="{{ old('name', $event->name) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="event_date">Event Date</label>
                  <input id="event_date" name="event_date" type="date" value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="gallery_visibility">Gallery Visibility</label>
                  <select id="gallery_visibility" name="gallery_visibility" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option value="private" @selected(old('gallery_visibility', $event->gallery_visibility) === 'private')>Private</option>
                    <option value="public" @selected(old('gallery_visibility', $event->gallery_visibility) === 'public')>Public</option>
                  </select>
                </div>

                @foreach([
                  'show_event_date' => 'Show Event Date',
                  'link_sharing_enabled' => 'Link Sharing',
                  'guest_access_enabled' => 'Guest Access',
                  'download_all_enabled' => 'Download All',
                ] as $field => $label)
                  <label class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3">
                    <span class="text-xs font-bold text-slate-700">{{ $label }}</span>
                    <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $cloud[$field] ?? false)) class="w-4 h-4 rounded text-indigo-600 border-slate-300">
                  </label>
                @endforeach

                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="password">Password</label>
                  <input id="password" name="password" type="text" value="{{ old('password', $cloud['password']) }}" placeholder="Optional" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="website">Website</label>
                  <input id="website" name="website" type="url" value="{{ old('website', $cloud['website']) }}" placeholder="https://example.com" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
              </div>
              <div class="p-5 border-t border-slate-100 bg-slate-50">
                <button type="submit" class="w-full px-4 py-3 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700">Save Settings</button>
              </div>
            </form>

            <div class="bg-white rounded-2xl border border-slate-200/70 p-5 shadow-sm">
              <h2 class="font-extrabold text-slate-900">Sharing</h2>
              @if($event->gallery_visibility === 'public')
                <p class="text-xs text-slate-500 mt-2">Public event URL</p>
                <a href="{{ $publicUrl }}" target="_blank" class="block mt-2 break-all text-xs font-bold text-indigo-600 underline">{{ $publicUrl }}</a>
              @else
                <p class="mt-2 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">Gallery is private. Switch visibility to public to share this event link.</p>
              @endif

              @if(($features['ads_enabled'] ?? false) || ($features['watermark_enabled'] ?? false))
                <p class="mt-4 text-xs text-amber-800 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">Free plan note: ads or watermark placeholders may appear on public download pages. Custom design upload is handled in Booth App and may require subscription.</p>
              @else
                <p class="mt-4 text-xs text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">Your current plan supports cleaner sharing with no ads/watermark placeholders.</p>
              @endif
            </div>
          </aside>

          <section class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
              <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                  <h2 class="font-extrabold text-slate-900">Media Manager</h2>
                  <p class="text-xs text-slate-500 mt-1">{{ $event->photos_count }} files synced from booth devices.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                  @foreach(['Upload', 'Link', 'Embed', 'Shares', 'Analytics', 'Slideshow', 'Delete Event'] as $action)
                    <button type="button" disabled class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 bg-slate-100 border border-slate-200 cursor-not-allowed">{{ $action }}</button>
                  @endforeach
                </div>
              </div>

              <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <label class="flex items-center gap-2 text-xs font-bold text-slate-600">
                  <input type="checkbox" disabled class="w-4 h-4 rounded border-slate-300">
                  Select all
                </label>
                @if($cloud['download_all_enabled'])
                  <button type="button" disabled class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 opacity-70 cursor-not-allowed">Download All</button>
                @endif
              </div>

              <div class="p-4">
                @if($photos->isEmpty())
                  <div class="p-16 text-center text-slate-400">
                    <span class="text-5xl block mb-4">📸</span>
                    <h3 class="font-extrabold text-slate-900 text-lg">No media yet</h3>
                    <p class="text-xs text-slate-500 mt-1">Photos will appear after booth devices upload captures.</p>
                  </div>
                @else
                  <div class="media-grid">
                    @foreach($photos as $photo)
                      @php($fileUrl = Storage::url($photo->file_path))
                      <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="relative aspect-[4/3] bg-slate-100">
                          <input type="checkbox" disabled class="absolute left-2 top-2 z-10 w-4 h-4 rounded border-slate-300">
                          <a href="{{ $fileUrl }}" target="_blank">
                            <img src="{{ $fileUrl }}" alt="{{ $photo->original_filename ?? 'Photo' }}" class="w-full h-full object-cover">
                          </a>
                          <span class="absolute right-2 top-2 px-2 py-0.5 rounded bg-slate-950/70 text-white text-[9px] font-bold uppercase">{{ $photo->photo_type ?? 'final' }}</span>
                        </div>
                        <div class="p-3 space-y-2">
                          <p class="text-xs font-bold text-slate-800 truncate">{{ $photo->original_filename ?? 'Untitled' }}</p>
                          @if($photo->boothSession?->public_token)
                            <a href="{{ url('/s/' . $photo->boothSession->public_token) }}" target="_blank" class="block text-[10px] font-bold text-indigo-600 underline truncate">Session Link</a>
                          @endif
                          <div class="grid grid-cols-3 gap-1">
                            <a href="{{ $fileUrl }}" target="_blank" class="px-2 py-1.5 rounded text-center text-[10px] font-bold text-slate-700 bg-slate-100">Preview</a>
                            <a href="{{ $fileUrl }}" download="{{ $photo->original_filename ?? 'photo.jpg' }}" class="px-2 py-1.5 rounded text-center text-[10px] font-bold text-white bg-indigo-600">Download</a>
                            <button type="button" disabled class="px-2 py-1.5 rounded text-[10px] font-bold text-slate-400 bg-slate-100 cursor-not-allowed">Delete</button>
                          </div>
                        </div>
                      </article>
                    @endforeach
                  </div>
                  <div class="mt-6">{{ $photos->links() }}</div>
                @endif
              </div>
            </div>
          </section>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
