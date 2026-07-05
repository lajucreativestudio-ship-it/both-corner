<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Event - Both Corner Cloud</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .font-display { font-family: 'Outfit', sans-serif; }
  </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased">
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
          <li class="rounded-xl overflow-hidden">
            <a href="{{ route('client.events.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white bg-indigo-600/10 border-l-4 border-indigo-500 transition-all duration-200">
              <span class="text-base">🏠</span> Events
            </a>
          </li>
          <li class="rounded-xl overflow-hidden">
            <a href="{{ route('dashboard') }}?panel=settings" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">🔧</span> Settings
            </a>
          </li>
          <li class="rounded-xl overflow-hidden">
            <a href="{{ route('dashboard') }}?panel=subscriptions" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">💳</span> Subscriptions
            </a>
          </li>
          <li class="rounded-xl overflow-hidden">
            <a href="{{ route('dashboard') }}?panel=refer-earn" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">💡</span> Refer & Earn
            </a>
          </li>
          <li class="rounded-xl overflow-hidden">
            <a href="{{ route('dashboard') }}?panel=copilot" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">📷</span> Booth Copilot
            </a>
          </li>
          <li class="rounded-xl overflow-hidden">
            <a href="{{ route('dashboard') }}?panel=help" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">❓</span> Help
            </a>
          </li>
          <li class="rounded-xl overflow-hidden">
            <a href="{{ route('dashboard') }}?panel=licenses" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200">
              <span class="text-base">🔐</span> Lisensi & Device
            </a>
          </li>
          <li class="rounded-xl overflow-hidden">
            <form action="{{ route('logout') }}" method="POST" class="block w-full">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-400 transition-all duration-200 hover:text-white hover:bg-red-950/20 border-l-4 border-transparent text-left cursor-pointer">
                <span class="text-base">🚪</span> Log Out
              </button>
            </form>
          </li>
        </ul>
      </div>
      <div class="p-4 border-t border-slate-800 bg-[#15171d]">
        <div class="flex items-center gap-3 p-1.5 rounded-xl">
          <div class="w-10 h-10 rounded-full bg-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md">LS</div>
          <div class="flex flex-col min-w-0">
            <span class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</span>
            <span class="text-[10px] text-slate-500 truncate">Client</span>
          </div>
        </div>
      </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0">
      <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between shrink-0">
        <div>
          <h1 class="text-xl font-extrabold text-slate-900">Create Event</h1>
          <p class="text-xs text-slate-500 mt-1">Setup event, capture mode, layout, timing, dan sharing.</p>
        </div>
        <a href="{{ route('client.events.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">← Back to Events</a>
      </header>

      <div class="p-8 max-w-5xl">
        @if($errors->any())
          <div class="mb-6 p-4 rounded-2xl border border-rose-200 bg-rose-50 text-sm text-rose-700 font-semibold">
            {{ $errors->first() }}
          </div>
        @endif

        @if($limits['max_events'] !== null && $eventCount >= $limits['max_events'])
          <div class="mb-6 p-4 rounded-2xl border border-amber-200 bg-amber-50 text-sm text-amber-800 font-semibold">
            Event limit plan Anda sudah tercapai. Upgrade subscription untuk membuat event baru.
          </div>
        @endif

        <form action="{{ route('client.events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
          @csrf
          <div class="p-6 space-y-8">
            <section class="space-y-4">
              <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">1. Event Info</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="name">Event Name</label>
                  <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="event_date">Event Date</label>
                  <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="location">Location</label>
                  <input id="location" name="location" type="text" value="{{ old('location') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2" for="status">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                      <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                      <option value="active" @selected(old('status') === 'active')>Active</option>
                      <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2" for="gallery_visibility">Sharing</label>
                    <select id="gallery_visibility" name="gallery_visibility" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                      <option value="private" @selected(old('gallery_visibility', 'private') === 'private')>Private</option>
                      <option value="public" @selected(old('gallery_visibility') === 'public')>Public</option>
                    </select>
                  </div>
                </div>
              </div>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
              <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">2. Capture Modes</h2>
              @php($oldModes = old('capture_modes', ['photo']))
              <div class="flex flex-wrap gap-6">
                @foreach(['photo' => 'Photo', 'gif' => 'GIF', 'boomerang' => 'Boomerang'] as $mode => $label)
                  <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="capture_modes[]" value="{{ $mode }}" @checked(in_array($mode, $oldModes, true)) class="w-4 h-4 rounded text-indigo-600 border-slate-300">
                    <span class="text-xs font-bold text-slate-700">{{ $label }}</span>
                  </label>
                @endforeach
                <label class="flex items-center gap-3 cursor-not-allowed opacity-50">
                  <input type="checkbox" disabled class="w-4 h-4 rounded border-slate-300">
                  <span class="text-xs font-bold text-slate-400">Video (Coming Soon)</span>
                </label>
              </div>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
              <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">3. Template / Layout</h2>
              @php
                $selectedTemplateIds = array_map('intval', old('templates', []));
                $defaultTemplateId = old('default_template_id', $selectedTemplateIds[0] ?? null);
              @endphp
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($templates as $template)
                  @php($isSelected = in_array((int)$template->id, $selectedTemplateIds, true))
                  <div class="p-4 border rounded-xl flex items-center justify-between gap-4 {{ $isSelected ? 'border-indigo-100 bg-indigo-50/30' : 'border-slate-200 bg-white' }}">
                    <label class="flex items-center gap-3 min-w-0 cursor-pointer">
                      <input type="checkbox" name="templates[]" value="{{ $template->id }}" @checked($isSelected) class="w-4 h-4 rounded text-indigo-600 border-slate-300">
                      <span class="min-w-0">
                        <span class="block text-xs font-bold text-slate-900 truncate">{{ $template->name }}</span>
                        <span class="block text-[10px] text-slate-400 font-semibold">{{ $template->is_global ? 'Global' : 'Custom' }} · {{ str_replace('_', ' ', $template->template_type) }}</span>
                      </span>
                    </label>
                    <label class="flex items-center gap-1.5 shrink-0 cursor-pointer">
                      <input type="radio" name="default_template_id" value="{{ $template->id }}" @checked((string)$defaultTemplateId === (string)$template->id) class="w-3.5 h-3.5 text-indigo-600 border-slate-300">
                      <span class="text-[10px] font-bold text-slate-500">Default</span>
                    </label>
                  </div>
                @endforeach
              </div>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
              <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">4. Custom Upload Template</h2>
              @if(!$features['custom_template_upload'])
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 font-semibold">
                  Custom template upload requires subscription.
                </div>
              @endif
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 {{ !$features['custom_template_upload'] ? 'opacity-50' : '' }}">
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="custom_template_name">Custom Template Name</label>
                  <input id="custom_template_name" name="custom_template_name" type="text" value="{{ old('custom_template_name') }}" @disabled(!$features['custom_template_upload']) class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2">Default Custom Upload</label>
                  <label class="h-[46px] flex items-center gap-2 rounded-xl border border-slate-200 px-4 text-xs font-bold text-slate-600">
                    <input type="radio" name="default_template_id" value="custom" @checked(old('default_template_id') === 'custom') @disabled(!$features['custom_template_upload']) class="text-indigo-600 border-slate-300">
                    Use uploaded custom layout as default
                  </label>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="overlay_file">Overlay / Frame</label>
                  <input id="overlay_file" name="overlay_file" type="file" accept="image/*" @disabled(!$features['custom_template_upload']) class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="background_file">Background</label>
                  <input id="background_file" name="background_file" type="file" accept="image/*" @disabled(!$features['custom_template_upload']) class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white">
                </div>
              </div>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
              <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2">5. Timing</h2>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                  'initial_countdown' => ['Initial Countdown', 5, 1, 60],
                  'between_capture_delay' => ['Between Capture Delay', 2, 0, 60],
                  'preview_duration' => ['Preview Duration', 3, 1, 120],
                  'retake_timeout' => ['Retake Timeout', 10, 1, 300],
                  'final_preview_duration' => ['Final Preview Duration', 8, 1, 300],
                  'idle_timeout' => ['Idle Timeout', 30, 5, 1800],
                ] as $field => [$label, $default, $min, $max])
                  <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2" for="{{ $field }}">{{ $label }}</label>
                    <input id="{{ $field }}" name="{{ $field }}" type="number" min="{{ $min }}" max="{{ $max }}" value="{{ old($field, $default) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                  </div>
                @endforeach
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="template_type">Template Type</label>
                  <select id="template_type" name="template_type" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white">
                    <option value="photo_4x6_portrait" @selected(old('template_type', 'photo_4x6_portrait') === 'photo_4x6_portrait')>4x6 Portrait</option>
                    <option value="strip_2x6" @selected(old('template_type') === 'strip_2x6')>2x6 Strip</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="orientation">Orientation</label>
                  <select id="orientation" name="orientation" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white">
                    <option value="portrait" @selected(old('orientation', 'portrait') === 'portrait')>Portrait</option>
                    <option value="landscape" @selected(old('orientation') === 'landscape')>Landscape</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="canvas_width">Width</label>
                  <input id="canvas_width" name="canvas_width" type="number" value="{{ old('canvas_width', 1200) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="canvas_height">Height</label>
                  <input id="canvas_height" name="canvas_height" type="number" value="{{ old('canvas_height', 1800) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 mb-2" for="capture_count">Capture Count</label>
                  <input id="capture_count" name="capture_count" type="number" min="1" max="10" value="{{ old('capture_count', 3) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                </div>
              </div>
              <input type="hidden" name="layout_type" value="{{ old('layout_type', 'custom') }}">
              <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-3 cursor-pointer"><input type="checkbox" name="retake_enabled" value="1" @checked(old('retake_enabled', true)) class="w-4 h-4 rounded text-indigo-600 border-slate-300"><span class="text-xs font-bold text-slate-700">Retake</span></label>
                <label class="flex items-center gap-3 cursor-pointer"><input type="checkbox" name="print_enabled" value="1" @checked(old('print_enabled', false)) class="w-4 h-4 rounded text-indigo-600 border-slate-300"><span class="text-xs font-bold text-slate-700">Print</span></label>
                <label class="flex items-center gap-3 cursor-pointer"><input type="checkbox" name="watermark_enabled" value="1" @checked(old('watermark_enabled', false)) class="w-4 h-4 rounded text-indigo-600 border-slate-300"><span class="text-xs font-bold text-slate-700">Watermark</span></label>
              </div>
            </section>
          </div>

          <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3">
            <a href="{{ route('client.events.index') }}" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-200/60">Cancel</a>
            <button type="submit" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/15">Save Event</button>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
