<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Both Corner Cloud</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Laravel Vite / Tailwind CSS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    h1, h2, h3, h4, .font-display {
      font-family: 'Outfit', sans-serif;
    }
  </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased">

  <div class="flex min-h-screen">
    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-[#1a1d24] text-slate-400 flex flex-col justify-between select-none shrink-0">
      <div>
        <div class="p-6 border-b border-slate-800">
          <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-500 to-violet-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-indigo-500/20">B</div>
            <span class="text-lg font-bold text-white tracking-tight">Both<span class="text-indigo-400">Corner</span></span>
          </a>
        </div>
        <ul class="mt-6 flex flex-col gap-1 px-4">
          @foreach($menus as $index => $menu)
            @php
              $panelName = str_replace('#panel-', '', $menu->url);
              $isActive = ($index === 0);
              $emojis = [
                  'events' => '🏠',
                  'settings' => '🔧',
                  'subscriptions' => '💳',
                  'refer-earn' => '💡',
                  'copilot' => '📷',
                  'help' => '❓'
              ];
              $emoji = $emojis[$panelName] ?? '🔗';
            @endphp
            <li class="rounded-xl overflow-hidden" id="menu-{{ $panelName }}">
              <a href="#" onclick="switchPanel('{{ $panelName }}')" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-200 {{ $isActive ? 'text-white bg-indigo-600/10 border-l-4 border-indigo-500' : 'hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent' }}">
                <span class="text-base">{{ $emoji }}</span> {{ $menu->title }}
              </a>
            </li>
          @endforeach
          <li class="rounded-xl overflow-hidden" id="menu-licenses">
            <a href="#" onclick="switchPanel('licenses')" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-200 hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent">
              <span class="text-base">🔐</span> Lisensi & Device
            </a>
          </li>
        </ul>
      </div>
      
      <!-- Profile section -->
      <div class="p-4 border-t border-slate-800 bg-[#15171d] relative">
        <!-- Upward Dropdown Menu -->
        <div id="profile-dropdown" class="hidden absolute bottom-16 left-4 right-4 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden z-50 py-1 transition-all duration-205">
          <div class="px-4 py-2 border-b border-slate-800 bg-slate-950/40">
            <p class="text-[9px] text-slate-500 font-semibold uppercase tracking-wider">Signed in as</p>
            <p class="text-xs font-bold text-white truncate">lajucreativestudio@gmail.com</p>
          </div>
          <a href="#" onclick="switchPanel('settings'); toggleProfileDropdown(event)" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/60 transition-colors">
            ⚙️ Account Info
          </a>
          <div class="border-t border-slate-800"></div>
          <form action="{{ route('logout') }}" method="POST" class="block w-full">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-red-400 hover:text-red-300 hover:bg-red-550/10 transition-colors cursor-pointer">
              🚪 Log Out
            </button>
          </form>
        </div>

        <div onclick="toggleProfileDropdown(event)" class="flex items-center justify-between cursor-pointer group p-1.5 rounded-xl hover:bg-slate-800/30 transition-colors">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md group-hover:scale-105 transition-transform">LS</div>
            <div class="flex flex-col min-w-0">
              <span class="text-xs font-bold text-white group-hover:text-violet-300 transition-colors truncate">Laju Studio</span>
              <span class="text-[10px] text-slate-500 truncate">Administrator</span>
            </div>
          </div>
          <span class="text-slate-500 group-hover:text-white text-[10px] select-none">▲</span>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0">
      
      <!-- Panel Events -->
      <section id="panel-events" class="db-panel p-8 block">
        <!-- Notice Banner -->
        <div class="bg-indigo-600 text-white rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 mb-8 shadow-lg shadow-indigo-600/15">
          <div class="flex items-center gap-4">
            <span class="text-3xl">📺</span>
            <div class="text-sm">
              <strong class="block text-base">Both Corner Cloud akan segera bertransisi ke Dashboard Baru</strong>
              Kelola event, lakukan remote konfigurasi, dan pilih dari ratusan template profesional secara instan.
            </div>
          </div>
          <button onclick="showToast('⚡ Fitur Dashboard baru sedang disiapkan!')" class="px-6 py-2.5 rounded-full text-xs font-bold bg-white text-indigo-700 hover:bg-indigo-5 transition-all duration-200 cursor-pointer shrink-0">
            Coba Dashboard Baru
          </button>
        </div>

        <!-- Events List Header with Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
          <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Events</h2>
          <div class="flex items-center gap-2 max-w-md w-full sm:w-auto">
            <input type="text" placeholder="Cari nama event..." id="event-search-input" oninput="filterEvents()" class="flex-1 px-4 py-2 text-sm rounded-full border border-slate-200 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white transition-colors">
            <button onclick="filterEvents()" class="px-5 py-2 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 transition-colors cursor-pointer">Cari</button>
          </div>
        </div>

        <!-- Event Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6" id="events-grid-container">
          <!-- Populated dynamically by JS -->
        </div>
      </section>

      <!-- Panel Settings -->
      <section id="panel-settings" class="db-panel p-8 hidden">
        <div class="flex justify-between items-center border-b border-slate-200 pb-4 mb-6">
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Settings</h1>
          <button onclick="saveSettings()" class="px-6 py-2.5 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all duration-200 cursor-pointer">Save Settings</button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/50 p-6 md:p-8 shadow-sm space-y-6">
          <!-- Row 1: Business Name & Username -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
              <label for="settings-business-name" class="text-sm font-bold text-slate-700">Business Name</label>
              <input type="text" id="settings-business-name" value="Laju Studio" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
              <span class="text-xs text-slate-400">Ditampilkan pada homepage dan album event Anda.</span>
            </div>
            <div class="flex flex-col gap-2">
              <label for="settings-username" class="text-sm font-bold text-slate-700">Username</label>
              <input type="text" id="settings-username" value="1354452179" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
              <span class="text-xs text-slate-400">Homepage Anda akan beralamat di: bothcorner.co/u/yourusername (Business Plan)</span>
            </div>
          </div>

          <!-- Row 2: Email -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
              <label for="settings-email" class="text-sm font-bold text-slate-700">Email</label>
              <input type="email" id="settings-email" value="lajucreativestudio@gmail.com" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
            </div>
          </div>
          <div class="flex flex-wrap gap-4 text-xs font-semibold">
            <a href="#" class="text-indigo-600 hover:text-indigo-700 hover:underline" onclick="showToast('📧 Instruksi perubahan email dikirim ke email lama.')">Change Email</a>
            <a href="#" class="text-indigo-600 hover:text-indigo-700 hover:underline" onclick="showToast('🔑 Silakan cek email untuk instruksi reset password.')">Change Password</a>
            <a href="#" class="text-red-500 hover:text-red-600 hover:underline" onclick="showToast('✖ Akun tidak dapat dihapus selama masa aktif paket.', false)">Delete Account</a>
          </div>

          <!-- Checkbox default guest access -->
          <div class="flex items-start gap-3 border-t border-slate-100 pt-6">
            <input type="checkbox" id="settings-default-guest-access" checked class="mt-1 w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
            <label for="settings-default-guest-access" class="flex flex-col gap-0.5 select-none">
              <span class="text-sm font-bold text-slate-800">Default Guest Access</span>
              <span class="text-xs text-slate-500">Izinkan tamu untuk melihat berkas foto/video tamu lainnya. <a href="#" class="text-indigo-600 hover:underline">Info Selengkapnya</a>. Ini menyetel default untuk event baru dan bisa diatur per event.</span>
            </label>
          </div>

          <!-- Subscription Box -->
          <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-6">
            <h4 class="text-sm font-bold text-indigo-900">Both Corner Cloud Subscription - Upgrade untuk kontrol brand penuh</h4>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
              Upgrade ke paket premium untuk mengaktifkan kustomisasi album dan homepage dengan logo bisnis Anda sendiri, tautan ke website Anda, perlindungan kata sandi pada event, embed event di situs web Anda, domain kustom, serta analitik Google Analytics.
            </p>
            <p class="text-xs text-indigo-600/80 font-semibold mt-3">Saat ini Anda menggunakan paket gratis basic.</p>
            <button onclick="showToast('💳 Mengarahkan ke gerbang pembayaran...')" class="mt-4 px-5 py-2.5 rounded-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10 cursor-pointer">
              Upgrade Subscription
            </button>
          </div>

          <!-- Logo upload row -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6">
            <div class="flex flex-col gap-2">
              <label class="text-sm font-bold text-slate-700">Light Logo Image</label>
              <div onclick="showToast('🔒 Fitur ini memerlukan paket Business.', false)" class="w-full h-32 border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center text-xs text-slate-400 text-center px-6 bg-slate-50 hover:bg-slate-100/50 transition-colors cursor-pointer select-none">
                Upgrade to the Business Plan to enable this feature.
              </div>
              <span class="text-xs text-slate-400">Logo terang untuk digunakan di homepage dan halaman galeri event Anda.</span>
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-sm font-bold text-slate-700">Dark Logo Image</label>
              <div onclick="showToast('🔒 Fitur ini memerlukan paket Business.', false)" class="w-full h-32 border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center text-xs text-slate-400 text-center px-6 bg-slate-50 hover:bg-slate-100/50 transition-colors cursor-pointer select-none">
                Upgrade to the Business Plan to enable this feature.
              </div>
              <span class="text-xs text-slate-400">Logo gelap untuk digunakan di homepage dan halaman galeri event Anda.</span>
            </div>
          </div>

          <!-- Row 3: Website & Custom Domain -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6">
            <div class="flex flex-col gap-2">
              <label for="settings-website" class="text-sm font-bold text-slate-700">Your Website</label>
              <input type="text" id="settings-website" placeholder="http://yoursite.com" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
            </div>
            <div class="flex flex-col gap-2">
              <label for="settings-custom-domain" class="text-sm font-bold text-slate-700">Custom Domain</label>
              <input type="text" id="settings-custom-domain" placeholder="photos.yourdomain.com" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
              <span class="text-xs text-slate-400">Dapatkan domain/subdomain kustom seperti: photos.company.com untuk tampilan yang sangat profesional.</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Panel Subscriptions -->
      <section id="panel-subscriptions" class="db-panel p-8 hidden">
        <div class="border-b border-slate-200 pb-4 mb-6">
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Subscriptions & Billing</h1>
          <p class="text-slate-500 text-sm mt-1">Kelola detail paket langganan dan tagihan akun Both Corner Cloud Anda.</p>
        </div>

        <!-- Pricing Header -->
        <div class="text-center max-w-3xl mx-auto mb-10">
          <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Choose the Perfect Plan</h2>
          <p class="text-slate-500 mt-2 text-sm leading-relaxed">Flexible solutions tailored to fit your community's unique needs with premium features and dedicated support</p>
          
          <!-- Filters matching screenshot -->
          <div class="mt-6 inline-flex p-1 rounded-xl bg-slate-200/60 text-xs font-semibold gap-1 select-none">
            <button onclick="showToast('Filter: Semua Paket')" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 transition-colors">Semua Paket</button>
            <button onclick="showToast('Filter: DSLRBooth')" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 transition-colors">DSLRBooth</button>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white shadow-sm">Internal</button>
          </div>

          <!-- Billing Period Toggle -->
          <div class="mt-4 flex items-center justify-center gap-3 select-none">
            <span class="text-xs font-bold text-slate-500">Bulanan</span>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" checked onchange="showToast('Beralih ke Pembayaran Tahunan (Diskon 20%!)')" class="sr-only peer">
              <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
            </label>
            <span class="text-xs font-bold text-slate-900">Annual <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] ml-1">Hemat 20%</span></span>
          </div>
        </div>

        <!-- 4 Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
          @foreach($plans as $index => $plan)
            @php
              $icons = ['🏆', '⭐', '⚡', '🤝'];
              $icon = $icons[$index % count($icons)];
              $isDark = ($index === 0);
            @endphp
            <div class="p-6 rounded-2xl border flex flex-col justify-between group {{ $isDark ? 'bg-[#1e293b] text-white border-slate-700 shadow-xl' : 'bg-white text-slate-900 border-slate-200 shadow-sm hover:shadow-lg transition-all duration-200' }}">
              <div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl mb-4 {{ $isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-indigo-50 text-indigo-600' }}">{{ $icon }}</div>
                <h3 class="text-lg font-bold leading-tight {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ $plan->name }}</h3>
                <div class="mt-4 flex items-baseline gap-1">
                  <span class="text-xs font-bold text-slate-400">Rp</span>
                  <span class="text-2xl font-extrabold {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ number_format($plan->price, 0, ',', '.') }}</span>
                  <span class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-500' }}">/ bln</span>
                </div>
                
                <!-- Badges -->
                <div class="flex flex-wrap gap-1.5 mt-3">
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $isDark ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/20' : 'bg-indigo-50 text-indigo-600 border border-indigo-200/50' }}">Mendukung Photobooth {{ $plan->is_internal }}</span>
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $isDark ? 'bg-emerald-600/20 text-emerald-300 border border-emerald-500/20' : 'bg-emerald-50 text-emerald-600 border border-emerald-200/50' }}">Payment: {{ $plan->payment_method }}</span>
                </div>

                <!-- Features -->
                <ul class="mt-6 space-y-2.5 text-xs {{ $isDark ? 'text-slate-300' : 'text-slate-500' }}">
                  @foreach($plan->features_list as $feature)
                    <li class="flex items-start gap-2">✓ <span>{{ $feature }}</span></li>
                  @endforeach
                </ul>
              </div>
              <button onclick="showToast('Membuka gerbang pembayaran untuk {{ $plan->name }}...')" class="mt-8 w-full py-2.5 rounded-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg cursor-pointer {{ $isDark ? 'shadow-indigo-600/25' : 'shadow-indigo-600/15' }}">Pilih Paket</button>
            </div>
          @endforeach
        </div>
      </section>

      <!-- Panel Licenses & Devices -->
      <section id="panel-licenses" class="db-panel p-8 hidden">
        <div class="border-b border-slate-200 pb-4 mb-6">
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lisensi & Device Aktif</h1>
          <p class="text-slate-500 text-sm mt-1">Pantau pemakaian lisensi dan perangkat yang sedang terhubung ke akun Both Corner Cloud Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
          <div class="bg-white rounded-2xl border border-slate-200/70 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Paket Aktif</p>
            <h3 class="text-lg font-extrabold text-slate-900 mt-2">{{ $license['plan'] }}</h3>
            <span class="inline-flex mt-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $license['status'] }}</span>
          </div>
          <div class="bg-white rounded-2xl border border-slate-200/70 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Device Online</p>
            <div class="mt-2 flex items-end gap-1">
              <span class="text-3xl font-extrabold text-slate-900">{{ $license['active_devices'] }}</span>
              <span class="text-sm font-bold text-slate-400 mb-1">/ {{ $license['device_limit'] }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-2">Perangkat aktif saat ini.</p>
          </div>
          <div class="bg-white rounded-2xl border border-slate-200/70 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Terdaftar</p>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $license['registered_devices'] }}</div>
            <p class="text-xs text-slate-500 mt-2">Total device yang pernah dipasangkan.</p>
          </div>
          <div class="bg-white rounded-2xl border border-slate-200/70 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Perpanjangan</p>
            <div class="mt-2 text-lg font-extrabold text-slate-900">{{ $license['renewal_date'] }}</div>
            <p class="text-xs text-slate-500 mt-2">Tanggal estimasi tagihan berikutnya.</p>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2 class="text-lg font-extrabold text-slate-900">Daftar Device Terhubung</h2>
              <p class="text-xs text-slate-500 mt-1">Gunakan daftar ini untuk mengecek laptop/tablet mana saja yang sedang memakai lisensi.</p>
            </div>
            <div class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-600">
              {{ $license['license_key'] }}
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                  <th class="px-5 py-3 font-bold">Device</th>
                  <th class="px-5 py-3 font-bold">Platform</th>
                  <th class="px-5 py-3 font-bold">Status</th>
                  <th class="px-5 py-3 font-bold">Kamera</th>
                  <th class="px-5 py-3 font-bold">Terakhir Aktif</th>
                  <th class="px-5 py-3 font-bold text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($devices as $device)
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-5 py-4">
                      <div class="font-bold text-slate-900">{{ $device->device_name }}</div>
                      <div class="text-xs text-slate-400">ID device: BC-{{ str_pad($device->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-5 py-4 text-slate-600">{{ $device->platform }}</td>
                    <td class="px-5 py-4">
                      @if($device->is_online)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                          <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Offline
                        </span>
                      @endif
                    </td>
                    <td class="px-5 py-4">
                      <span class="text-xs font-bold {{ $device->camera_status === 'Connected' ? 'text-emerald-600' : 'text-red-500' }}">{{ $device->camera_status }}</span>
                    </td>
                    <td class="px-5 py-4 text-slate-500">{{ optional($device->last_active_at)->diffForHumans() ?? 'Belum pernah aktif' }}</td>
                    <td class="px-5 py-4 text-right">
                      <button onclick="showToast('🔐 Permintaan lepas device {{ $device->device_name }} dikirim ke server.')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 hover:bg-indigo-50 transition-colors">Lepas</button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                      <div class="text-4xl mb-3">🔌</div>
                      <p class="font-bold text-slate-800">Belum ada device yang terhubung.</p>
                      <p class="text-xs text-slate-500 mt-1">Login dari aplikasi Windows atau Android client untuk mengaktifkan lisensi device pertama.</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Panel Refer & Earn -->
      <section id="panel-refer-earn" class="db-panel p-8 hidden">
        <div class="border-b border-slate-200 pb-4 mb-6">
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Refer & Earn</h1>
          <p class="text-slate-500 text-sm mt-1">Undang rekan vendor lainnya dan dapatkan bonus saldo cloud.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/50 p-12 shadow-sm text-center max-w-2xl mx-auto">
          <span class="text-5xl block mb-4">🎁</span>
          <h3 class="text-xl font-bold text-slate-900">Bagikan Link Referral Anda</h3>
          <p class="text-slate-500 text-sm mt-2 max-w-md mx-auto leading-relaxed mb-6">
            Dapatkan gratis 1 bulan paket Business untuk setiap teman yang mendaftar dan berlangganan menggunakan link referral Anda.
          </p>
          <div class="flex gap-2 max-w-md mx-auto">
            <input type="text" value="https://bothcorner.co/ref/lajustudio" id="ref-link-input" readonly class="flex-grow px-4 py-2 border border-slate-200 rounded-xl bg-slate-50 outline-none text-center font-mono text-xs">
            <button onclick="copyRefLink()" class="px-6 py-2 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 cursor-pointer shrink-0">Salin</button>
          </div>
        </div>
      </section>

      <!-- Panel Copilot -->
      <section id="panel-copilot" class="db-panel p-8 hidden">
        <div class="border-b border-slate-200 pb-4 mb-6">
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Booth Copilot</h1>
          <p class="text-slate-500 text-sm mt-1">Pantau perangkat kamera di lapangan secara real-time dari jarak jauh.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/50 p-12 shadow-sm text-center max-w-2xl mx-auto">
          <span class="text-5xl block mb-4">📱</span>
          <h3 class="text-xl font-bold text-slate-900">Perangkat Kamera Tidak Aktif</h3>
          <p class="text-slate-500 text-sm mt-2 max-w-md mx-auto leading-relaxed">
            Hubungkan dan aktifkan fitur Booth Copilot di aplikasi Android / Windows client Anda untuk memantau status baterai, sisa kertas printer, dan jepretan kamera secara real-time.
          </p>
        </div>
      </section>

      <!-- Panel Help -->
      <section id="panel-help" class="db-panel p-8 hidden">
        <div class="border-b border-slate-200 pb-4 mb-6">
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Bantuan & Dukungan</h1>
          <p class="text-slate-500 text-sm mt-1">Temukan panduan penyiapan photobooth dan kontak dukungan teknis.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/50 p-6 md:p-8 shadow-sm max-w-3xl mx-auto space-y-6">
          <h3 class="text-lg font-bold text-slate-900">Pertanyaan Sering Diajukan (FAQ)</h3>
          <div class="space-y-4">
            <div class="border-b border-slate-100 pb-4">
              <strong class="block text-sm font-semibold text-slate-800 mb-1">Bagaimana cara menyinkronkan event ke aplikasi Android/Windows?</strong>
              <span class="text-xs text-slate-500 leading-relaxed">Pastikan perangkat Anda terhubung ke internet, lalu login dengan username <strong>1354452179</strong> atau email Anda di dalam aplikasi Both Corner di tablet/laptop.</span>
            </div>
            <div class="pb-2">
              <strong class="block text-sm font-semibold text-slate-800 mb-1">Apakah Both Corner mendukung printer eksternal?</strong>
              <span class="text-xs text-slate-500 leading-relaxed">Ya, aplikasi Windows mendukung cetak driver printer lokal secara langsung, sedangkan versi Android mendukung AirPrint atau koneksi print server.</span>
            </div>
          </div>
        </div>
      </section>

    </main>
  </div>

  <!-- Toast Notification -->
  <div id="toast-notif" class="fixed bottom-8 right-8 bg-white border border-indigo-200 text-slate-900 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-24 opacity-0 transition-all duration-300 z-[100]">
    <span id="toast-icon" class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">✓</span>
    <span id="toast-message" class="text-sm font-semibold">Pesan sukses</span>
  </div>

  <script>
    // Mock Events matching fotoshare Cloud screenshot
    let mockEvents = [
      { id: 1, title: 'Pernikahan Murdia & Muh Ali', date: 'January 8, 2026', img: '{{ asset('photobooth_event.png') }}' },
      { id: 2, title: 'PCA Warehouse D12.12', date: 'December 12, 2025', img: '{{ asset('welcome_screen_editor.png') }}' },
      { id: 3, title: 'PCA Head Office D12.12', date: 'December 11, 2025', img: '{{ asset('sharing_gallery.png') }}' },
      { id: 4, title: 'Fulfillment Country Workshop', date: 'April 25, 2025', img: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=600&auto=format&fit=crop' },
      { id: 5, title: 'Kate & Jack\'s Wedding', date: 'April 20, 2025', img: '{{ asset('photobooth_strip.png') }}' },
      { id: 6, title: 'Wedding Baim & Fany 13 April 2025', date: 'April 12, 2025', img: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=600&auto=format&fit=crop' }
    ];

    // Switch Active Panel in Sidebar
    function switchPanel(panelId) {
      // Hide all panels
      const panels = document.querySelectorAll('.db-panel');
      panels.forEach(p => {
        p.classList.remove('block');
        p.classList.add('hidden');
      });

      // Show selected panel
      const targetPanel = document.getElementById(`panel-${panelId}`);
      if (targetPanel) {
        targetPanel.classList.remove('hidden');
        targetPanel.classList.add('block');
      }

      // Update active status on sidebar items
      const menuItems = document.querySelectorAll('aside ul li a');
      menuItems.forEach(item => {
        item.className = "flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:text-white hover:bg-slate-800/50 border-l-4 border-transparent transition-all duration-200";
      });

      const activeMenuItem = document.querySelector(`#menu-${panelId} a`);
      if (activeMenuItem) {
        activeMenuItem.className = "flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white bg-indigo-600/10 border-l-4 border-indigo-500 transition-all duration-200";
      }

      // Load events list on events tab
      if (panelId === 'events') {
        renderEvents(mockEvents);
      }
    }

    // Render Events Grid
    function renderEvents(events) {
      const container = document.getElementById('events-grid-container');
      if (!container) return;

      container.innerHTML = '';

      if (events.length === 0) {
        container.innerHTML = `
          <div class="col-span-full text-center py-16 text-slate-400">
            <p class="text-4xl mb-2">📅</p>
            <p class="text-sm font-semibold">Tidak ada event yang cocok dengan pencarian Anda.</p>
          </div>
        `;
        return;
      }

      events.forEach(ev => {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-2xl border border-slate-200/50 hover:border-indigo-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden cursor-pointer';
        card.onclick = () => showToast(`📂 Membuka konfigurasi remote untuk event: "${ev.title}"`);
        card.innerHTML = `
          <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
            <img src="${ev.img}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="${ev.title}" loading="lazy">
          </div>
          <div class="p-4">
            <div class="font-bold text-sm text-slate-800 truncate">${ev.title}</div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">${ev.date}</div>
          </div>
        `;
        container.appendChild(card);
      });
    }

    // Filter Events by Search input
    function filterEvents() {
      const input = document.getElementById('event-search-input');
      if (!input) return;

      const query = input.value.toLowerCase();
      const filtered = mockEvents.filter(ev => ev.title.toLowerCase().includes(query));

      renderEvents(filtered);
    }

    // Save Settings Form
    function saveSettings() {
      const businessName = document.getElementById('settings-business-name').value;
      showToast(`💾 Pengaturan "${businessName}" berhasil disimpan ke cloud database!`);
    }

    // Copy Referral link
    function copyRefLink() {
      const refInput = document.getElementById('ref-link-input');
      if (refInput) {
        refInput.select();
        refInput.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(refInput.value).then(() => {
          showToast('📋 Link referral disalin ke clipboard!');
        }).catch(err => {
          showToast('✖ Gagal menyalin link', false);
        });
      }
    }

    // Toast notification helper
    function showToast(message, isSuccess = true) {
      const toast = document.getElementById('toast-notif');
      const toastMsg = document.getElementById('toast-message');
      const toastIcon = document.getElementById('toast-icon');

      if (toast) {
        toastMsg.innerText = message;
        if (isSuccess) {
          toastIcon.innerText = '✓';
          toastIcon.className = "w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold";
        } else {
          toastIcon.innerText = '✖';
          toastIcon.className = "w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold";
        }
        
        toast.classList.remove('translate-y-24', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
          toast.classList.remove('translate-y-0', 'opacity-100');
          toast.classList.add('translate-y-24', 'opacity-0');
        }, 3500);
      }
    }

    // Toggle profile dropdown
    function toggleProfileDropdown(event) {
      if (event) event.stopPropagation();
      const dropdown = document.getElementById('profile-dropdown');
      if (dropdown) {
        dropdown.classList.toggle('hidden');
      }
    }

    // Close dropdown on click outside
    window.addEventListener('click', () => {
      const dropdown = document.getElementById('profile-dropdown');
      if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
      }
    });

    // Page Load initialization
    document.addEventListener('DOMContentLoaded', () => {
      renderEvents(mockEvents);
    });

    // CS Support chat toggle
    let isCsWidgetOpen = false;
    let myTickets = [];

    function toggleCsWidget() {
      const windowEl = document.getElementById('cs-chat-window');
      isCsWidgetOpen = !isCsWidgetOpen;
      if (isCsWidgetOpen) {
        windowEl.classList.remove('hidden');
        loadMyTickets();
      } else {
        windowEl.classList.add('hidden');
      }
    }

    function switchCsTab(tab) {
      const tabList = document.getElementById('tab-ticket-list');
      const tabCreate = document.getElementById('tab-ticket-create');
      const bodyList = document.getElementById('cs-body-list');
      const bodyCreate = document.getElementById('cs-body-create');
      const bodyChat = document.getElementById('cs-body-chat');

      tabList.className = "flex-grow py-2.5 text-center border-b-2 " + (tab === 'list' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500');
      tabCreate.className = "flex-grow py-2.5 text-center border-b-2 " + (tab === 'create' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500');

      bodyList.classList.add('hidden');
      bodyCreate.classList.add('hidden');
      bodyChat.classList.add('hidden');

      if (tab === 'list') {
        bodyList.classList.remove('hidden');
        loadMyTickets();
      } else if (tab === 'create') {
        bodyCreate.classList.remove('hidden');
      } else if (tab === 'chat') {
        bodyChat.classList.remove('hidden');
      }
    }

    function loadMyTickets() {
      fetch('/client/tickets')
        .then(res => res.json())
        .then(data => {
          myTickets = data;
          const container = document.getElementById('cs-body-list');
          if (data.length === 0) {
            container.innerHTML = `
              <div class="text-center py-12 text-slate-400 text-xs">
                <span class="text-2xl block mb-2">🤷‍♂️</span>
                Belum ada riwayat keluhan/konsultasi.
              </div>`;
            return;
          }

          container.innerHTML = data.map(t => {
            let badgeClass = 'bg-amber-50 text-amber-600 border border-amber-100';
            if (t.status === 'on_going') badgeClass = 'bg-indigo-50 text-indigo-600 border border-indigo-100';
            if (t.status === 'closed') badgeClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';

            return `
              <div onclick="openClientChatDetail(${t.id})" class="p-3 bg-white hover:bg-slate-50/70 border border-slate-200/75 rounded-xl cursor-pointer transition-colors shadow-sm text-xs">
                <div class="flex items-center justify-between mb-1">
                  <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase ${badgeClass}">${t.status.replace('_', ' ')}</span>
                  <span class="text-[9px] text-slate-400">${new Date(t.created_at).toLocaleDateString()}</span>
                </div>
                <h4 class="font-bold text-slate-800 leading-tight truncate">${t.subject}</h4>
              </div>
            `;
          }).join('');
        });
    }

    function openClientChatDetail(ticketId) {
      const ticket = myTickets.find(t => t.id === ticketId);
      if (!ticket) return;

      document.getElementById('active-ticket-id').value = ticketId;
      switchCsTab('chat');

      renderChatFeed(ticket.messages);
    }

    function renderChatFeed(messages) {
      const feed = document.getElementById('cs-chat-feed');
      feed.innerHTML = messages.map(m => {
        const isMe = m.sender_id === {{ auth()->id() }};
        return `
          <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'} space-y-1">
            <span class="text-[8px] text-slate-400">${m.sender.name}</span>
            <div class="max-w-[70%] px-3.5 py-2 rounded-2xl text-xs leading-relaxed ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 border border-slate-200 rounded-tl-none'}">
              ${m.message}
            </div>
          </div>
        `;
      }).join('');
      feed.scrollTop = feed.scrollHeight;
    }

    function submitNewTicket(event) {
      event.preventDefault();
      const subject = document.getElementById('tkt-subject').value;
      const message = document.getElementById('tkt-message').value;

      fetch('/client/tickets', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ subject, message })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('tkt-subject').value = '';
          document.getElementById('tkt-message').value = '';
          switchCsTab('list');
          showToast('✓ Tiket berhasil dibuat!');
        }
      });
    }

    function sendTicketReply(event) {
      event.preventDefault();
      const ticketId = document.getElementById('active-ticket-id').value;
      const message = document.getElementById('reply-message').value;

      fetch(`/client/tickets/${ticketId}/messages`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('reply-message').value = '';
          fetch('/client/tickets')
            .then(res => res.json())
            .then(tickets => {
              myTickets = tickets;
              const updatedTicket = tickets.find(t => t.id == ticketId);
              if (updatedTicket) {
                renderChatFeed(updatedTicket.messages);
              }
            });
        }
      });
    }
  </script>

  <!-- Floating CS Chat Support Widget -->
  <div class="fixed bottom-6 right-6 z-50">
    <!-- Floating Button -->
    <button onclick="toggleCsWidget()" class="w-14 h-14 rounded-full bg-indigo-600 hover:bg-indigo-750 text-white flex items-center justify-center shadow-xl shadow-indigo-600/35 hover:scale-105 active:scale-95 transition-all cursor-pointer">
      <span class="text-2xl">🎧</span>
    </button>

    <!-- Chat Popover Window -->
    <div id="cs-chat-window" class="hidden absolute bottom-18 right-0 w-80 sm:w-96 h-[480px] bg-white rounded-2xl border border-slate-200 shadow-2xl flex flex-col overflow-hidden">
      <!-- Chat Header -->
      <div class="bg-indigo-600 text-white p-4 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <span class="text-xl">👩‍💻</span>
          <div>
            <h4 class="font-bold text-xs leading-none">Customer Service Both Corner</h4>
            <span class="text-[9px] text-indigo-200">Konsultasi & Masalah Client</span>
          </div>
        </div>
        <button onclick="toggleCsWidget()" class="text-white hover:text-indigo-200 text-lg font-bold cursor-pointer select-none">×</button>
      </div>

      <!-- Tickets View / Create View -->
      <div class="flex-grow flex flex-col overflow-hidden" id="cs-main-body">
        
        <!-- Tab Navigations -->
        <div class="flex border-b border-slate-100 text-[10px] font-bold text-slate-500 bg-slate-50">
          <button id="tab-ticket-list" onclick="switchCsTab('list')" class="flex-grow py-2.5 text-center border-b-2 border-indigo-600 text-indigo-600 cursor-pointer">Tiket Saya</button>
          <button id="tab-ticket-create" onclick="switchCsTab('create')" class="flex-grow py-2.5 text-center border-b-2 border-transparent cursor-pointer">Buat Konsultasi Baru</button>
        </div>

        <!-- Tab 1: Ticket list view -->
        <div id="cs-body-list" class="flex-grow overflow-y-auto p-4 space-y-3">
          <div class="text-center py-8 text-slate-400 text-xs">
            Memuat tiket bantuan...
          </div>
        </div>

        <!-- Tab 2: Create Ticket form -->
        <div id="cs-body-create" class="hidden flex-grow p-4 overflow-y-auto">
          <form id="create-ticket-form" onsubmit="submitNewTicket(event)" class="space-y-4 text-xs">
            <div class="flex flex-col gap-1">
              <label class="font-bold text-slate-700">Subjek Keluhan / Konsultasi</label>
              <input type="text" id="tkt-subject" required placeholder="Contoh: Gagal aktivasi voucher, DSLR error" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
            </div>
            <div class="flex flex-col gap-1">
              <label class="font-bold text-slate-700">Pesan Detail</label>
              <textarea id="tkt-message" required rows="4" placeholder="Jelaskan secara rinci kendala Anda..." class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors leading-relaxed"></textarea>
            </div>
            <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 cursor-pointer">
              Kirim Tiket
            </button>
          </form>
        </div>

        <!-- Tab 3: Message conversation feed -->
        <div id="cs-body-chat" class="hidden flex-grow flex flex-col overflow-hidden bg-slate-50">
          <button onclick="switchCsTab('list')" class="px-3 py-2 border-b border-slate-100 bg-white text-[10px] font-bold text-indigo-600 flex items-center gap-1 hover:bg-slate-50 cursor-pointer">
            ← Kembali ke daftar tiket
          </button>
          
          <!-- Conversation List -->
          <div class="flex-grow overflow-y-auto p-4 space-y-3" id="cs-chat-feed">
            <!-- Messages populated via JS -->
          </div>

          <!-- Send reply area -->
          <div class="p-3 border-t border-slate-200 bg-white">
            <form id="reply-ticket-form" onsubmit="sendTicketReply(event)" class="flex gap-2 items-end">
              <input type="hidden" id="active-ticket-id" value="">
              <textarea id="reply-message" required rows="1" placeholder="Ketik pesan..." class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-xs transition-colors resize-none"></textarea>
              <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 cursor-pointer">
                Kirim
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>
