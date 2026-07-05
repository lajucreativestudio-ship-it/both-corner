<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Developer Admin Dashboard - Both Corner</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  <style>
    body {
      font-family: 'Instrument Sans', sans-serif;
    }
    .db-panel {
      transition: opacity 0.15s ease-in-out;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 overflow-x-hidden antialiased flex h-screen">

  <!-- Sidebar -->
  <aside id="developer-sidebar" class="w-72 bg-[#0f172a] text-slate-400 flex flex-col justify-between shrink-0 border-r border-slate-800 select-none transition-all duration-300 ease-in-out">
    <div class="min-h-0 flex flex-col">
      <!-- Brand Logo -->
      <div id="developer-sidebar-brand" class="h-20 px-6 border-b border-slate-800/70 flex items-center gap-3 shrink-0 transition-all duration-300">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shrink-0">B</div>
        <div class="sidebar-label leading-tight">
          <span class="block text-base font-extrabold text-white tracking-tight">Both<span class="text-indigo-400">Dev</span></span>
          <span class="text-[10px] uppercase tracking-[0.22em] text-slate-500 font-bold">Admin Console</span>
        </div>
      </div>
      
      <!-- Nav Menu list -->
      <nav id="developer-sidebar-nav" class="p-4 overflow-y-auto transition-all duration-300">
        <p class="sidebar-label text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] px-3 mb-3">Developer Tools</p>
        <ul class="space-y-1.5">
          <li id="menu-summary">
            <a href="#" data-nav-link onclick="switchPanel('summary')" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20 transition-all duration-200 cursor-pointer">
              <span class="w-8 h-8 rounded-lg bg-indigo-500/15 text-indigo-300 flex items-center justify-center">📊</span>
              <span class="sidebar-label truncate">Ringkasan</span>
            </a>
          </li>

          <li>
            <a href="{{ route('developer.devices.index') }}" data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200 cursor-pointer">
              <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">💻</span>
              <span class="sidebar-label truncate">Device Management</span>
            </a>
          </li>
          
          <!-- Dropdown Kelola Konten -->
          <li>
            <button type="button" id="parent-dropdown-content" data-nav-parent onclick="toggleDropdown('dropdown-content')" class="w-full flex items-center justify-between px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent hover:border-slate-700/70 hover:text-white hover:bg-slate-800/50 text-slate-300 transition-all duration-200 cursor-pointer">
              <span class="sidebar-parent-label flex items-center gap-3 min-w-0">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center shrink-0">📁</span>
                <span class="sidebar-label truncate">Kelola Konten</span>
              </span>
              <span id="chevron-dropdown-content" class="sidebar-chevron transition-transform duration-200 text-[10px] text-slate-500 rotate-180">▲</span>
            </button>
            <ul id="dropdown-content" data-nav-group class="relative ml-7 mt-1.5 space-y-1 border-l border-slate-700/60 pl-3 block">
              <li id="menu-articles">
                <a href="#" data-nav-link onclick="switchPanel('articles')" class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all duration-200 cursor-pointer">
                  <span class="w-5 text-center">📰</span> <span class="sidebar-label truncate">Kelola Artikel</span>
                </a>
              </li>
              <li id="menu-categories">
                <a href="#" data-nav-link onclick="switchPanel('categories')" class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all duration-200 cursor-pointer">
                  <span class="w-5 text-center">🏷️</span> <span class="sidebar-label truncate">Kategori Artikel</span>
                </a>
              </li>
              <li id="menu-pages">
                <a href="#" data-nav-link onclick="switchPanel('pages')" class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all duration-200 cursor-pointer">
                  <span class="w-5 text-center">📄</span> <span class="sidebar-label truncate">Kelola Halaman Statis</span>
                </a>
              </li>
              <li id="menu-menus">
                <a href="#" data-nav-link onclick="switchPanel('menus')" class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all duration-200 cursor-pointer">
                  <span class="w-5 text-center">🔗</span> <span class="sidebar-label truncate">Kelola Menu Navigasi</span>
                </a>
              </li>
            </ul>
          </li>

          <li id="menu-payments">
            <a href="#" data-nav-link onclick="switchPanel('payments')" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200 cursor-pointer">
              <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">💳</span>
              <span class="sidebar-label truncate">Gateway Pembayaran</span>
            </a>
          </li>

          <li id="menu-revenue">
            <a href="#" data-nav-link onclick="switchPanel('revenue')" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200 cursor-pointer">
              <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">📈</span>
              <span class="sidebar-label truncate">Pendapatan</span>
            </a>
          </li>

          <li id="menu-pricing">
            <a href="#" data-nav-link onclick="switchPanel('pricing')" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200 cursor-pointer">
              <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">💰</span>
              <span class="sidebar-label truncate">Pengaturan Harga</span>
            </a>
          </li>

          <!-- Dropdown Kelola Pengguna & Hak Akses -->
          <li>
            <button type="button" id="parent-dropdown-users" data-nav-parent onclick="toggleDropdown('dropdown-users')" class="w-full flex items-center justify-between px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent hover:border-slate-700/70 hover:text-white hover:bg-slate-800/50 text-slate-300 transition-all duration-200 cursor-pointer">
              <span class="sidebar-parent-label flex items-center gap-3 min-w-0">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center shrink-0">👥</span>
                <span class="sidebar-label truncate">Pengguna & Tim</span>
              </span>
              <span id="chevron-dropdown-users" class="sidebar-chevron transition-transform duration-200 text-[10px] text-slate-500 rotate-180">▲</span>
            </button>
            <ul id="dropdown-users" data-nav-group class="relative ml-7 mt-1.5 space-y-1 border-l border-slate-700/60 pl-3 block">
              <li id="menu-users">
                <a href="#" data-nav-link onclick="switchPanel('users')" class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all duration-200 cursor-pointer">
                  <span class="w-5 text-center">👤</span> <span class="sidebar-label truncate">Daftar Pengguna / Tim</span>
                </a>
              </li>
              <li id="menu-roles">
                <a href="#" data-nav-link onclick="switchPanel('roles')" class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all duration-200 cursor-pointer">
                  <span class="w-5 text-center">🔑</span> <span class="sidebar-label truncate">Peran & Hak Akses</span>
                </a>
              </li>
            </ul>
          </li>

          <li id="menu-chats">
            <a href="#" data-nav-link onclick="switchPanel('chats')" class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70 transition-all duration-200 cursor-pointer">
              <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center">💬</span>
              <span class="sidebar-label truncate">Chat CS & Tiket</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    
    <!-- Profile footer with popover logout -->
    <div id="developer-profile-footer" class="p-4 border-t border-slate-800 bg-slate-950/20 relative transition-all duration-300">
      <div id="profile-dropdown" class="sidebar-label hidden absolute bottom-16 left-4 right-4 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden z-50 py-1 transition-all duration-200">
        <div class="px-4 py-2 border-b border-slate-800 bg-slate-950/40">
          <p class="text-[9px] text-slate-500 font-semibold uppercase tracking-wider">Signed in as</p>
          <p class="text-xs font-bold text-white truncate">developer@bothcorner.com</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="block w-full">
          @csrf
          <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-red-400 hover:text-red-300 hover:bg-red-550/10 transition-colors cursor-pointer">
            🚪 Log Out
          </button>
        </form>
      </div>

      <div id="developer-profile-trigger" onclick="toggleProfileDropdown(event)" class="flex items-center justify-between cursor-pointer group p-1.5 rounded-xl hover:bg-slate-800/30 transition-colors">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-indigo-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md group-hover:scale-105 transition-transform shrink-0">DV</div>
          <div class="sidebar-label flex flex-col min-w-0">
            <span class="text-xs font-bold text-white group-hover:text-indigo-300 transition-colors truncate">Developer</span>
            <span class="text-[10px] text-slate-500 truncate">Administrator</span>
          </div>
        </div>
        <span class="sidebar-label text-slate-500 group-hover:text-white text-[10px] select-none">▲</span>
      </div>
    </div>
  </aside>

  <!-- Main Content Area -->
  <main class="flex-1 flex flex-col min-w-0 overflow-y-auto h-screen">
    
    <!-- Top Header -->
    <header class="h-20 bg-white border-b border-slate-200/60 px-8 flex items-center justify-between shrink-0">
      <div class="flex items-center gap-4">
        <button type="button" onclick="toggleDeveloperSidebar()" class="w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 flex items-center justify-center transition-colors cursor-pointer" aria-label="Toggle sidebar">
          <span class="text-lg leading-none">☰</span>
        </button>
        <h2 class="text-lg font-bold text-slate-800">Developer Cloud Manager</h2>
      </div>
      <a href="{{ route('landing') }}" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Landing Page Beranda →</a>
    </header>

    <!-- Content panels -->
    <div class="p-8 flex-1">
      
      <!-- Alert Notifications -->
      @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs sm:text-sm font-semibold mb-6 flex items-center gap-2">
          <span>✓</span> {{ session('success') }}
        </div>
      @endif

      <!-- PANEL: SUMMARY -->
      <section id="panel-summary" class="db-panel block">
        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">User Aktif</span>
              <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_users'] }}</p>
            </div>
            <span class="text-3xl">👥</span>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Device Client</span>
              <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_devices'] }}</p>
            </div>
            <span class="text-3xl">💻</span>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Device Online</span>
              <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['online_devices'] }}</p>
            </div>
            <span class="text-3xl text-emerald-500">●</span>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Artikel Blog</span>
              <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_articles'] }}</p>
            </div>
            <span class="text-3xl">📰</span>
          </div>
        </div>

        <!-- Support & CS Tickets Summary Card Section -->
        <h3 class="font-bold text-slate-900 text-sm mb-4">Ringkasan Tiket Bantuan CS</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between cursor-pointer" onclick="switchPanel('chats')">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Tiket</span>
              <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['tickets_total'] }}</p>
            </div>
            <span class="text-2xl">💬</span>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between cursor-pointer" onclick="switchPanel('chats')">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status Open</span>
              <p class="text-3xl font-extrabold text-amber-650 mt-1">{{ $stats['tickets_open'] }}</p>
            </div>
            <span class="text-xl">⚠️</span>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between cursor-pointer" onclick="switchPanel('chats')">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">On Going</span>
              <p class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $stats['tickets_ongoing'] }}</p>
            </div>
            <span class="text-xl">🔄</span>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm flex items-center justify-between cursor-pointer" onclick="switchPanel('chats')">
            <div>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Closed</span>
              <p class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $stats['tickets_closed'] }}</p>
            </div>
            <span class="text-xl">✓</span>
          </div>
        </div>

        <!-- Devices Section -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden mb-8">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900">Perangkat Client Aktif di Lapangan</h3>
            <span class="text-[10px] text-indigo-600 bg-indigo-50 border border-indigo-100 font-bold px-2 py-0.5 rounded-full">Real-time status</span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4">Nama Perangkat</th>
                  <th class="p-4">Platform</th>
                  <th class="p-4">Koneksi Kamera</th>
                  <th class="p-4">Koneksi Cloud (Internet)</th>
                  <th class="p-4">Aktif Terakhir</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @foreach($devices as $dev)
                  <tr class="hover:bg-slate-50/50">
                    <td class="p-4 font-bold text-slate-800">{{ $dev->device_name }}</td>
                    <td class="p-4">
                      <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $dev->platform === 'Windows' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                        {{ $dev->platform }}
                      </span>
                    </td>
                    <td class="p-4 font-semibold {{ $dev->camera_status === 'Connected' ? 'text-emerald-600' : 'text-rose-500' }}">
                      📷 {{ $dev->camera_status }}
                    </td>
                    <td class="p-4">
                      @if($dev->is_online)
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                          Online
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-bold text-[10px]">
                          Offline
                        </span>
                      @endif
                    </td>
                    <td class="p-4 text-slate-500 text-xs">{{ $dev->last_active_at }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- PANEL: KELOLA ARTIKEL -->
      <section id="panel-articles" class="db-panel hidden">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900">Semua Artikel Blog</h3>
            <a href="{{ route('developer.articles.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 cursor-pointer flex items-center gap-1.5 transition-all">
              <span>📝</span> Tulis Artikel Baru
            </a>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4">Judul</th>
                  <th class="p-4">Kategori</th>
                  <th class="p-4">Tanggal Rilis</th>
                  <th class="p-4 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @foreach($articles as $art)
                  <tr class="hover:bg-slate-50/50">
                    <td class="p-4 font-bold text-slate-800 max-w-sm truncate">{{ $art->title }}</td>
                    <td class="p-4">
                      <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-bold">{{ $art->category }}</span>
                    </td>
                    <td class="p-4 text-slate-500 text-xs">{{ $art->created_at->format('d M Y') }}</td>
                    <td class="p-4 text-center">
                      <a href="{{ route('developer.articles.edit', $art->id) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-bold mr-4">Edit</a>
                      <form action="{{ route('developer.articles.destroy', $art->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus artikel ini?')" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">Hapus</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- PANEL: KELOLA HARGA -->
      <section id="panel-pricing" class="db-panel hidden">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
          
          <!-- Form Tambah/Edit Paket Harga -->
          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm self-start">
            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4" id="pricing-form-title">Tambah Paket Harga</h3>
            <form id="pricing-form" action="{{ route('developer.pricing.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
              @csrf
              <input type="hidden" id="pricing-method" name="_method" value="POST">
              
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Nama Paket</label>
                <input type="text" name="name" id="pricing-name" required placeholder="Contoh: Online Starter Plan, Promo Merdeka" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Kategori Paket</label>
                <select name="category" id="pricing-category" required class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                  <option value="Reguler">Reguler</option>
                  <option value="Promo">Promo</option>
                  <option value="Seasonal Event">Seasonal Event</option>
                  <option value="Kustom">Kustom / Khusus</option>
                </select>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Harga (Rupiah)</label>
                <div class="flex items-center gap-2">
                  <span class="text-slate-400 font-bold">Rp</span>
                  <input type="number" name="price" id="pricing-price" required placeholder="448500" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 font-bold transition-colors">
                </div>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Periode Tagihan</label>
                <select name="billing_period" id="pricing-billing-period" required class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                  <option value="monthly">Bulanan (Monthly)</option>
                  <option value="annual">Tahunan (Annual)</option>
                  <option value="lifetime">Sekali Bayar (Lifetime)</option>
                </select>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Tipe Integrasi</label>
                <select name="is_internal" id="pricing-is-internal" required class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                  <option value="Internal">Internal (Both Corner App)</option>
                  <option value="DSLRBooth">DSLRBooth / Eksternal</option>
                </select>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Metode Pembayaran</label>
                <select name="payment_method" id="pricing-payment-method" required class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                  <option value="Online">Online Gateway (Midtrans/Xendit)</option>
                  <option value="Voucher">Kode Voucher / Manual</option>
                  <option value="Hybrid">Hybrid</option>
                </select>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Fitur (Pisahkan Per Baris)</label>
                <textarea name="features" id="pricing-features" rows="5" required placeholder="Cetak foto tak terbatas&#10;Setting PIN keamanan&#10;Support DSLR & Mirrorless" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-xs leading-relaxed transition-colors"></textarea>
              </div>

              <div class="flex gap-2">
                <button type="submit" id="pricing-submit-btn" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10 cursor-pointer">Simpan Paket</button>
                <button type="button" id="pricing-cancel-btn" onclick="cancelEditPricing()" class="hidden px-4 py-2.5 rounded-xl font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Batal</button>
              </div>
            </form>
          </div>

          <!-- Tabel List Paket Harga -->
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h3 class="font-bold text-slate-900">Daftar Paket & Harga Langganan</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4">Nama Paket</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Billing</th>
                    <th class="p-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($plans as $plan)
                    <tr class="hover:bg-slate-50/50">
                      <td class="p-4 font-bold text-slate-800">
                        <div class="leading-tight">{{ $plan->name }}</div>
                        <span class="text-[9px] text-slate-400 font-normal">{{ $plan->is_internal }} • via {{ $plan->payment_method }}</span>
                      </td>
                      <td class="p-4">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold 
                          {{ $plan->category === 'Promo' ? 'bg-amber-50 text-amber-600 border border-amber-200/30' : '' }}
                          {{ $plan->category === 'Seasonal Event' ? 'bg-purple-50 text-purple-600 border border-purple-200/30' : '' }}
                          {{ $plan->category === 'Reguler' ? 'bg-indigo-50 text-indigo-600 border border-indigo-200/30' : '' }}
                          {{ $plan->category === 'Kustom' ? 'bg-slate-100 text-slate-600 border border-slate-200/30' : '' }}
                        ">
                          {{ $plan->category }}
                        </span>
                      </td>
                      <td class="p-4 font-bold text-slate-700">Rp {{ number_format($plan->price, 0, ',', '.') }}</td>
                      <td class="p-4 text-slate-550 capitalize">{{ $plan->billing_period }}</td>
                      <td class="p-4 text-center whitespace-nowrap">
                        <button type="button" onclick="editPricing({{ $plan->id }}, '{{ addslashes($plan->name) }}', '{{ $plan->category }}', {{ intval($plan->price) }}, '{{ $plan->billing_period }}', '{{ $plan->is_internal }}', '{{ $plan->payment_method }}', `{{ implode('\n', json_decode($plan->features, true) ?: []) }}`)" class="text-indigo-600 hover:text-indigo-750 text-xs font-bold mr-4 cursor-pointer">Edit</button>
                        <form action="{{ route('developer.pricing.destroy', $plan->id) }}" method="POST" class="inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" onclick="return confirm('Hapus paket harga ini?')" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">Hapus</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </section>

      <!-- PANEL: KELOLA MENU NAVIGASI -->
      <section id="panel-menus" class="db-panel hidden">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
          <!-- Form Tambah Menu -->
          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm self-start">
            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Tambah Menu Navigasi</h3>
            <form action="{{ route('developer.menus.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
              @csrf
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Nama Menu (Label)</label>
                <input type="text" name="title" required placeholder="Contoh: Blog, Bantuan, dll." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Target URL (Hash atau Rute)</label>
                <input type="text" name="url" required placeholder="Contoh: #pricing atau /blog" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Penempatan Halaman</label>
                <select name="type" required class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                  <option value="landing_page">Landing Page</option>
                  <option value="user_dashboard">User Dashboard (Sidebar)</option>
                </select>
              </div>
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Urutan (Order)</label>
                <input type="number" name="order" required value="0" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>
              <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10 cursor-pointer">Simpan Menu</button>
            </form>
          </div>

          <!-- Table Menus -->
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h3 class="font-bold text-slate-900">Struktur Navigasi Menu</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4">Label</th>
                    <th class="p-4">URL</th>
                    <th class="p-4">Tipe Halaman</th>
                    <th class="p-4">Urutan</th>
                    <th class="p-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($menus as $menu)
                    <tr class="hover:bg-slate-50/50">
                      <td class="p-4 font-bold text-slate-800">{{ $menu->title }}</td>
                      <td class="p-4 font-mono text-slate-500 text-xs">{{ $menu->url }}</td>
                      <td class="p-4">
                        <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $menu->type === 'landing_page' ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-600' }}">
                          {{ $menu->type }}
                        </span>
                      </td>
                      <td class="p-4 text-slate-500">{{ $menu->order }}</td>
                      <td class="p-4 text-center">
                        <form action="{{ route('developer.menus.destroy', $menu->id) }}" method="POST" class="inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" onclick="return confirm('Hapus menu ini?')" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">Hapus</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- PANEL: KELOLA KATEGORI ARTIKEL -->
      <section id="panel-categories" class="db-panel hidden">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
          <!-- Form Tambah Kategori -->
          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm self-start">
            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4" id="cat-form-title">Tambah Kategori Artikel</h3>
            <form id="category-form" action="{{ route('developer.categories.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
              @csrf
              <input type="hidden" id="category-method" name="_method" value="POST">
              
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Nama Kategori</label>
                <input type="text" name="name" id="category-name" required placeholder="Contoh: Kamera, Tutorial, dll." oninput="updateCategorySlug()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Slug</label>
                <input type="text" name="slug" id="category-slug" required placeholder="url-slug-kategori" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 font-mono transition-colors">
              </div>
              <div class="flex gap-2">
                <button type="submit" id="category-submit-btn" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10 cursor-pointer">Simpan Kategori</button>
                <button type="button" id="category-cancel-btn" onclick="cancelEditCategory()" class="hidden px-4 py-2.5 rounded-xl font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Batal</button>
              </div>
            </form>
          </div>

          <!-- Table Categories -->
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h3 class="font-bold text-slate-900">Daftar Kategori Artikel</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4">Nama</th>
                    <th class="p-4">Slug</th>
                    <th class="p-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($categories as $cat)
                    <tr class="hover:bg-slate-50/50">
                      <td class="p-4 font-bold text-slate-800" id="cat-name-{{ $cat->id }}">{{ $cat->name }}</td>
                      <td class="p-4 font-mono text-slate-500 text-xs" id="cat-slug-{{ $cat->id }}">{{ $cat->slug }}</td>
                      <td class="p-4 text-center">
                        <button type="button" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->slug }}')" class="text-indigo-600 hover:text-indigo-750 text-xs font-bold mr-4 cursor-pointer">Edit</button>
                        <form action="{{ route('developer.categories.destroy', $cat->id) }}" method="POST" class="inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">Hapus</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- PANEL: KELOLA HALAMAN STATIS -->
      <section id="panel-pages" class="db-panel hidden">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900">Daftar Halaman Statis</h3>
            <a href="{{ route('developer.static-pages.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">+ Tambah Halaman Baru</a>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4">Nama Halaman</th>
                  <th class="p-4">Slug</th>
                  <th class="p-4">Judul Hero</th>
                  <th class="p-4 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @foreach($staticPages as $page)
                  <tr class="hover:bg-slate-50/50">
                    <td class="p-4 font-bold text-slate-800">{{ $page->title }}</td>
                    <td class="p-4 font-mono text-slate-500 text-xs">{{ $page->slug }}</td>
                    <td class="p-4 text-slate-500 max-w-sm truncate">{!! $page->hero_title !!}</td>
                    <td class="p-4 text-center">
                      <a href="{{ route('developer.static-pages.edit', $page->id) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-bold mr-4 transition-colors">Edit</a>
                      @if($page->slug !== 'landing')
                        <form action="{{ route('developer.static-pages.destroy', $page->id) }}" method="POST" class="inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" onclick="return confirm('Hapus halaman statis ini?')" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">Hapus</button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- PANEL: GATEWAY PEMBAYARAN -->
      <section id="panel-payments" class="db-panel hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          @foreach($gateways as $gw)
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                  <div class="flex items-center gap-2">
                    <span class="text-xl">💳</span>
                    <h3 class="font-extrabold text-slate-900 text-base leading-tight">{{ $gw->name }}</h3>
                  </div>
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $gw->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                    {{ $gw->is_active ? 'Active' : 'Disabled' }}
                  </span>
                </div>

                <form action="{{ route('developer.payment-gateways.update', $gw->id) }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                  @csrf
                  
                  <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">Status Gateway</label>
                    <select name="is_active" class="px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 transition-colors">
                      <option value="1" {{ $gw->is_active ? 'selected' : '' }}>Aktif (Terima Pembayaran)</option>
                      <option value="0" {{ !$gw->is_active ? 'selected' : '' }}>Non-aktif (Disabled)</option>
                    </select>
                  </div>

                  <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">Environment</label>
                    <select name="is_sandbox" class="px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 transition-colors">
                      <option value="1" {{ $gw->is_sandbox ? 'selected' : '' }}>Sandbox / Development</option>
                      <option value="0" {{ !$gw->is_sandbox ? 'selected' : '' }}>Production / Live</option>
                    </select>
                  </div>

                  @if($gw->name === 'Midtrans')
                    <div class="flex flex-col gap-1.5">
                      <label class="text-[10px] font-bold text-slate-500 uppercase">Client Key</label>
                      <input type="text" name="client_id" value="{{ $gw->client_id }}" class="px-3 py-2 rounded-xl border border-slate-200 font-mono text-slate-700 bg-white outline-none focus:border-indigo-500 transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                      <label class="text-[10px] font-bold text-slate-500 uppercase">Server Key</label>
                      <input type="text" name="server_key" value="{{ $gw->server_key }}" class="px-3 py-2 rounded-xl border border-slate-200 font-mono text-slate-700 bg-white outline-none focus:border-indigo-500 transition-colors">
                    </div>
                  @else
                    <div class="flex flex-col gap-1.5">
                      <label class="text-[10px] font-bold text-slate-500 uppercase">API Key / Token</label>
                      <input type="text" name="api_key" value="{{ $gw->api_key }}" class="px-3 py-2 rounded-xl border border-slate-200 font-mono text-slate-700 bg-white outline-none focus:border-indigo-500 transition-colors">
                    </div>
                  @endif

                  <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-white bg-slate-900 hover:bg-slate-800 transition-colors shadow-sm cursor-pointer text-center">
                      Update {{ $gw->name }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      </section>

      <!-- PANEL: PENDAPATAN & REVENUE -->
      <section id="panel-revenue" class="db-panel hidden">
        
        <!-- Metrics Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pendapatan</span>
              <span class="text-2xl font-extrabold text-slate-950 block mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 text-xl font-bold">💵</div>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Subscribers Aktif</span>
              <span class="text-2xl font-extrabold text-slate-950 block mt-1">{{ $stats['active_subscribers'] }} Member</span>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xl font-bold">👥</div>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata Transaksi</span>
              <span class="text-2xl font-extrabold text-slate-950 block mt-1">Rp {{ number_format($stats['total_revenue'] / max(1, count($transactions)), 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 text-xl font-bold">📈</div>
          </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
          <!-- Chart -->
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
            <h3 class="font-bold text-slate-950 mb-4">Tren Transaksi Bulanan</h3>
            <div class="w-full h-64 flex items-end justify-between px-4 pb-2 border-b border-slate-200 pt-8 relative">
              
              <!-- Grid line markers -->
              <div class="absolute inset-x-0 bottom-16 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-300">
                <span></span><span>Rp 500k</span>
              </div>
              <div class="absolute inset-x-0 bottom-32 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-300">
                <span></span><span>Rp 1M</span>
              </div>
              
              <!-- Bars representing months -->
              <div class="flex flex-col items-center gap-2 w-16">
                <div class="w-8 bg-indigo-200 hover:bg-indigo-300 rounded-t transition-all" style="height: 60px;" title="Januari: Rp 450.000"></div>
                <span class="text-[10px] font-bold text-slate-400">Jan</span>
              </div>
              <div class="flex flex-col items-center gap-2 w-16">
                <div class="w-8 bg-indigo-200 hover:bg-indigo-300 rounded-t transition-all" style="height: 110px;" title="Februari: Rp 900.000"></div>
                <span class="text-[10px] font-bold text-slate-400">Feb</span>
              </div>
              <div class="flex flex-col items-center gap-2 w-16">
                <div class="w-8 bg-indigo-200 hover:bg-indigo-300 rounded-t transition-all" style="height: 95px;" title="Maret: Rp 780.000"></div>
                <span class="text-[10px] font-bold text-slate-400">Mar</span>
              </div>
              <div class="flex flex-col items-center gap-2 w-16">
                <div class="w-8 bg-indigo-200 hover:bg-indigo-300 rounded-t transition-all" style="height: 150px;" title="April: Rp 1.250.000"></div>
                <span class="text-[10px] font-bold text-slate-400">Apr</span>
              </div>
              <div class="flex flex-col items-center gap-2 w-16">
                <div class="w-8 bg-indigo-200 hover:bg-indigo-300 rounded-t transition-all" style="height: 130px;" title="Mei: Rp 1.100.000"></div>
                <span class="text-[10px] font-bold text-slate-400">Mei</span>
              </div>
              <div class="flex flex-col items-center gap-2 w-16">
                <div class="w-8 bg-indigo-600 hover:bg-indigo-700 rounded-t transition-all" style="height: 180px;" title="Juni (Aktif): Rp {{ number_format($stats['total_revenue'], 0) }}"></div>
                <span class="text-[10px] font-bold text-slate-600 font-extrabold">Jun</span>
              </div>
            </div>
          </div>

          <!-- Recent Transactions -->
          <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 overflow-hidden">
            <h3 class="font-bold text-slate-950 mb-4">Riwayat Pembayaran Terbaru</h3>
            <div class="flow-root">
              <ul class="divide-y divide-slate-100 text-xs">
                @foreach($transactions as $tx)
                  <li class="py-3 flex items-center justify-between">
                    <div>
                      <p class="font-bold text-slate-900 leading-tight">{{ $tx->user_name }}</p>
                      <p class="text-[9px] text-slate-450 mt-0.5">{{ $tx->plan_name }} • via {{ $tx->gateway }}</p>
                    </div>
                    <div class="text-right">
                      <p class="font-bold text-emerald-600 leading-tight">Rp {{ number_format($tx->amount, 0, ',', '.') }}</p>
                      <p class="text-[9px] text-slate-400 mt-0.5">{{ $tx->created_at->diffForHumans() }}</p>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- PANEL: KELOLA PENGGUNA & TIM -->
      <section id="panel-users" class="db-panel hidden">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
          
          <!-- Form Tambah/Edit Pengguna -->
          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm self-start">
            @if(auth()->user()->role === 'team')
              <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl text-xs font-bold mb-4">
                ⚠️ Hak Akses Terbatas: Sebagai anggota Tim Monitor, Anda tidak diizinkan menambah atau mengubah konfigurasi pengguna.
              </div>
            @endif

            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4" id="user-form-title">Tambah Pengguna / Tim</h3>
            <form id="user-form" action="{{ route('developer.users.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm @if(auth()->user()->role === 'team') opacity-50 pointer-events-none @endif">
              @csrf
              <input type="hidden" id="user-method" name="_method" value="POST">
              
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Nama Lengkap</label>
                <input type="text" name="name" id="user-name" required placeholder="Contoh: Ferry Monitor, Andi Wijaya" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Email Address</label>
                <input type="email" name="email" id="user-email" required placeholder="name@domain.com" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Password (Min. 6 Karakter)</label>
                <input type="password" name="password" id="user-password" placeholder="Ketik password..." class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                <span class="text-[9px] text-slate-400" id="user-password-hint">Wajib diisi saat membuat pengguna baru. Kosongkan jika mengedit dan tidak ingin mengubah.</span>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Peran & Hak Akses</label>
                <select name="role_id" id="user-role" required class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                  @foreach($roles as $rl)
                    <option value="{{ $rl->id }}">{{ $rl->name }}</option>
                  @endforeach
                </select>
                <div class="mt-2 bg-slate-50 border border-slate-100 p-2.5 rounded-lg text-[9px] leading-relaxed text-slate-500">
                  <strong>💡 Privilege Separation:</strong><br>
                  Peran menentukan halaman dan tindakan apa saja yang dapat diakses oleh anggota tim ini.
                </div>
              </div>

              <div class="flex gap-2">
                <button type="submit" id="user-submit-btn" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10 cursor-pointer">Simpan Pengguna</button>
                <button type="button" id="user-cancel-btn" onclick="cancelEditUser()" class="hidden px-4 py-2.5 rounded-xl font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Batal</button>
              </div>
            </form>
          </div>

          <!-- Tabel List Pengguna -->
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h3 class="font-bold text-slate-900">Daftar Pengguna/Tim</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4">Nama</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Peran (Role)</th>
                    <th class="p-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($users as $usr)
                    <tr class="hover:bg-slate-50/50">
                      <td class="p-4 font-bold text-slate-800">{{ $usr->name }}</td>
                      <td class="p-4 font-mono text-slate-500 text-xs">{{ $usr->email }}</td>
                      <td class="p-4">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-200/30">
                          {{ $usr->customRole ? $usr->customRole->name : strtoupper($usr->role) }}
                        </span>
                      </td>
                      <td class="p-4 text-center whitespace-nowrap">
                        @if(auth()->user()->role === 'admin')
                          <button type="button" onclick="editUser({{ $usr->id }}, '{{ addslashes($usr->name) }}', '{{ $usr->email }}', {{ $usr->role_id ?? '0' }})" class="text-indigo-600 hover:text-indigo-750 text-xs font-bold mr-4 cursor-pointer">Edit</button>
                          @if($usr->id !== auth()->id())
                            <form action="{{ route('developer.users.destroy', $usr->id) }}" method="POST" class="inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" onclick="return confirm('Hapus pengguna ini?')" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">Hapus</button>
                            </form>
                          @endif
                        @else
                          <span class="text-slate-400">View Only</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </section>

      <!-- PANEL: PERAN & HAK AKSES -->
      <section id="panel-roles" class="db-panel hidden">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
          
          <!-- Form Tambah/Edit Peran -->
          <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm self-start">
            @if(auth()->user()->role === 'team')
              <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl text-xs font-bold mb-4">
                ⚠️ Hak Akses Terbatas: Sebagai anggota Tim Monitor, Anda tidak diizinkan mengubah konfigurasi peran.
              </div>
            @endif

            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4" id="role-form-title">Tambah Peran & Hak Akses</h3>
            <form id="role-form" action="{{ route('developer.roles.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm @if(auth()->user()->role === 'team') opacity-50 pointer-events-none @endif">
              @csrf
              <input type="hidden" id="role-method" name="_method" value="POST">
              
              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Nama Peran</label>
                <input type="text" name="name" id="role-name" required placeholder="Contoh: CS Supervisor, Editor Konten" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
                <span class="text-[9px] text-slate-400" id="role-name-hint">Nama peran bawaan sistem tidak dapat diubah namanya.</span>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700">Deskripsi Singkat</label>
                <input type="text" name="description" id="role-description" placeholder="Contoh: Mengurusi bantuan tiket member sehari-hari" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 transition-colors">
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-700 mb-1">Daftar Hak Akses Halaman</label>
                <div class="space-y-2.5 max-h-60 overflow-y-auto p-3 border border-slate-150 rounded-xl bg-slate-50/50">
                  
                  @php
                    $availablePerms = [
                      'view_summary' => '📊 Ringkasan / Dashboard Utama',
                      'manage_articles' => '📰 Kelola Artikel & Kategori Blog',
                      'manage_static_pages' => '📄 Kelola Halaman Statis',
                      'manage_navigation_menus' => '🔗 Kelola Menu Navigasi',
                      'manage_payment_gateways' => '💳 Kelola Gateway Pembayaran',
                      'manage_revenue' => '📈 Melihat Pendapatan (Revenue)',
                      'manage_pricing' => '💰 Kelola Paket & Kategori Harga',
                      'manage_users' => '👥 Kelola Pengguna & Hak Akses Tim',
                      'manage_chats' => '💬 Membalas Chat CS & Tiket Bantuan'
                    ];
                  @endphp

                  @foreach($availablePerms as $key => $label)
                    <label class="flex items-start gap-2.5 cursor-pointer text-xs select-none">
                      <input type="checkbox" name="permissions[]" value="{{ $key }}" class="role-permission-checkbox mt-0.5 rounded text-indigo-600 focus:ring-indigo-550">
                      <div>
                        <span class="font-bold text-slate-800">{{ $label }}</span>
                      </div>
                    </label>
                  @endforeach

                </div>
              </div>

              <div class="flex gap-2">
                <button type="submit" id="role-submit-btn" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/10 cursor-pointer">Simpan Peran</button>
                <button type="button" id="role-cancel-btn" onclick="cancelEditRole()" class="hidden px-4 py-2.5 rounded-xl font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Batal</button>
              </div>
            </form>
          </div>

          <!-- Tabel List Peran -->
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h3 class="font-bold text-slate-900">Daftar Peran & Hak Akses</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4">Nama Peran</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4">Hak Akses Aktif</th>
                    <th class="p-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($roles as $roleRecord)
                    <tr class="hover:bg-slate-50/50">
                      <td class="p-4 whitespace-nowrap">
                        <span class="font-bold text-slate-800 block">{{ $roleRecord->name }}</span>
                        @if(in_array($roleRecord->name, ['Admin Utama', 'Team Monitor', 'Client / Member']))
                          <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mt-0.5 block">SYSTEM DEFAULT</span>
                        @endif
                      </td>
                      <td class="p-4 text-slate-500 text-xs leading-normal min-w-40">{{ $roleRecord->description }}</td>
                      <td class="p-4 leading-relaxed">
                        <div class="flex flex-wrap gap-1 max-w-xs">
                          @foreach($roleRecord->permissions ?? [] as $perm)
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                              {{ str_replace('_', ' ', $perm) }}
                            </span>
                          @endforeach
                          @if(empty($roleRecord->permissions))
                            <span class="text-slate-400 italic text-[10px]">Tidak ada akses panel</span>
                          @endif
                        </div>
                      </td>
                      <td class="p-4 text-center whitespace-nowrap">
                        @if(auth()->user()->role === 'admin')
                          <button type="button" onclick="editRole({{ $roleRecord->id }}, '{{ addslashes($roleRecord->name) }}', '{{ addslashes($roleRecord->description) }}', '{{ json_encode($roleRecord->permissions) }}')" class="text-indigo-600 hover:text-indigo-750 text-xs font-bold mr-4 cursor-pointer">Edit</button>
                          @if(!in_array($roleRecord->name, ['Admin Utama', 'Team Monitor', 'Client / Member']))
                            <form action="{{ route('developer.roles.destroy', $roleRecord->id) }}" method="POST" class="inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" onclick="return confirm('Hapus peran ini? Semua pengguna dengan peran ini akan kehilangan akses.')" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">Hapus</button>
                            </form>
                          @endif
                        @else
                          <span class="text-slate-400">View Only</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </section>

      <!-- PANEL: CHAT CS & TIKET BANTUAN -->
      <section id="panel-chats" class="db-panel hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[calc(100vh-14rem)] min-h-[500px]">
          
          <!-- Daftar Tiket (Left) -->
          <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="p-4 border-b border-slate-100">
              <h3 class="font-bold text-slate-950">Tiket Bantuan Masuk</h3>
              <p class="text-[10px] text-slate-400 mt-0.5">Kelola dan tanggapi konsultasi member</p>
            </div>
            
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
              @foreach($tickets as $tkt)
                <div onclick="openTicketDetail({{ $tkt->id }})" id="ticket-item-{{ $tkt->id }}" class="ticket-list-item p-4 hover:bg-slate-50/70 transition-colors cursor-pointer text-xs">
                  <div class="flex items-center justify-between mb-1.5">
                    <span class="px-2 py-0.5 rounded font-bold text-[9px]
                      {{ $tkt->status === 'open' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                      {{ $tkt->status === 'on_going' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : '' }}
                      {{ $tkt->status === 'closed' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                    " id="ticket-badge-val-{{ $tkt->id }}">
                      {{ strtoupper(str_replace('_', ' ', $tkt->status)) }}
                    </span>
                    <span class="text-[9px] text-slate-400">{{ $tkt->created_at->diffForHumans() }}</span>
                  </div>
                  <h4 class="font-bold text-slate-900 leading-tight mb-1 truncate">{{ $tkt->subject }}</h4>
                  <p class="text-[10px] text-slate-450">Oleh: <span class="font-semibold">{{ $tkt->user->name }}</span></p>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Conversation Detail Box (Right) -->
          <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm flex flex-col h-full relative overflow-hidden">
            
            <!-- Default Placeholder -->
            <div id="chat-placeholder" class="absolute inset-0 bg-slate-50 flex flex-col items-center justify-center p-6 text-center select-none z-10">
              <span class="text-4xl mb-3">💬</span>
              <h4 class="font-bold text-slate-800 text-sm">Pilih Tiket Bantuan</h4>
              <p class="text-xs text-slate-400 mt-1 max-w-xs">Silakan klik salah satu tiket di sebelah kiri untuk melihat pesan keluhan & merespons.</p>
            </div>

            <!-- Active Conversation Blocks -->
            @foreach($tickets as $tkt)
              <div id="chat-detail-{{ $tkt->id }}" class="ticket-chat-detail hidden flex flex-col h-full">
                
                <!-- Chat Box Header -->
                <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                  <div>
                    <h3 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $tkt->subject }}</h3>
                    <p class="text-[10px] text-slate-450 mt-0.5">Pengirim: {{ $tkt->user->name }} ({{ $tkt->user->email }})</p>
                  </div>
                  
                  <!-- Status modifier form -->
                  <form action="{{ route('developer.tickets.status', $tkt->id) }}" method="POST" class="flex items-center gap-1.5">
                    @csrf
                    <select name="status" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-[10px] font-bold text-slate-700 outline-none focus:border-indigo-500">
                      <option value="open" {{ $tkt->status === 'open' ? 'selected' : '' }}>Open</option>
                      <option value="on_going" {{ $tkt->status === 'on_going' ? 'selected' : '' }}>On Going</option>
                      <option value="closed" {{ $tkt->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-bold text-white bg-slate-900 hover:bg-slate-800 cursor-pointer">Set</button>
                  </form>
                </div>

                <!-- Messages Feed -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-100/30">
                  @foreach($tkt->messages as $msg)
                    @php
                      $isMe = in_array($msg->sender->role, ['admin', 'team']);
                    @endphp
                    <div class="flex flex-col @if($isMe) items-end @else items-start @endif space-y-1">
                      <div class="flex items-center gap-1.5 text-[9px] text-slate-400">
                        <span class="font-bold">{{ $msg->sender->name }}</span>
                        <span>•</span>
                        <span>{{ $msg->created_at->format('H:i') }}</span>
                      </div>
                      <div class="max-w-lg px-4 py-2.5 rounded-2xl text-xs leading-relaxed
                        @if($isMe)
                          bg-indigo-600 text-white rounded-tr-none shadow-sm
                        @else
                          bg-white text-slate-800 border border-slate-200/70 rounded-tl-none shadow-inner
                        @endif
                      ">
                        {{ $msg->message }}
                      </div>
                    </div>
                  @endforeach
                </div>

                <!-- Reply Area footer -->
                <div class="p-4 border-t border-slate-100 bg-white">
                  <form action="{{ route('developer.tickets.reply', $tkt->id) }}" method="POST" class="flex gap-3 items-end">
                    @csrf
                    <textarea name="message" required rows="2" placeholder="Ketik tanggapan / konsultasi balasan di sini..." class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-xs leading-relaxed transition-colors resize-none"></textarea>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 cursor-pointer text-center">
                      Balas
                    </button>
                  </form>
                </div>

              </div>
            @endforeach

          </div>
        </div>
      </section>

    </div>

  </main>

  <!-- JS tabs logic -->
  <script>
    let isDeveloperSidebarCollapsed = localStorage.getItem('bothcorner:developer-sidebar-collapsed') === 'true';

    function applyDeveloperSidebarState() {
      const sidebar = document.getElementById('developer-sidebar');
      if (!sidebar) return;

      sidebar.classList.toggle('w-72', !isDeveloperSidebarCollapsed);
      sidebar.classList.toggle('w-20', isDeveloperSidebarCollapsed);

      const brand = document.getElementById('developer-sidebar-brand');
      if (brand) {
        brand.classList.toggle('justify-center', isDeveloperSidebarCollapsed);
        brand.classList.toggle('px-6', !isDeveloperSidebarCollapsed);
        brand.classList.toggle('px-3', isDeveloperSidebarCollapsed);
      }

      const nav = document.getElementById('developer-sidebar-nav');
      if (nav) {
        nav.classList.toggle('p-4', !isDeveloperSidebarCollapsed);
        nav.classList.toggle('px-3', isDeveloperSidebarCollapsed);
        nav.classList.toggle('py-4', isDeveloperSidebarCollapsed);
      }

      sidebar.querySelectorAll('.sidebar-label').forEach(el => {
        el.classList.toggle('hidden', isDeveloperSidebarCollapsed);
      });

      sidebar.querySelectorAll('.sidebar-chevron').forEach(el => {
        el.classList.toggle('hidden', isDeveloperSidebarCollapsed);
      });

      sidebar.querySelectorAll('[data-nav-link], [data-nav-parent]').forEach(item => {
        item.classList.toggle('justify-center', isDeveloperSidebarCollapsed);
        item.classList.toggle('px-3.5', !isDeveloperSidebarCollapsed);
        item.classList.toggle('px-2', isDeveloperSidebarCollapsed);
      });

      sidebar.querySelectorAll('.sidebar-parent-label').forEach(item => {
        item.classList.toggle('justify-center', isDeveloperSidebarCollapsed);
      });

      const profileFooter = document.getElementById('developer-profile-footer');
      if (profileFooter) {
        profileFooter.classList.toggle('p-4', !isDeveloperSidebarCollapsed);
        profileFooter.classList.toggle('p-3', isDeveloperSidebarCollapsed);
      }

      const profileTrigger = document.getElementById('developer-profile-trigger');
      if (profileTrigger) {
        profileTrigger.classList.toggle('justify-center', isDeveloperSidebarCollapsed);
        profileTrigger.classList.toggle('justify-between', !isDeveloperSidebarCollapsed);
      }

      sidebar.querySelectorAll('[data-nav-group]').forEach(group => {
        if (isDeveloperSidebarCollapsed) {
          group.classList.add('hidden');
          group.classList.remove('block');
        } else {
          group.classList.remove('hidden');
          group.classList.add('block');
        }
      });
    }

    function toggleDeveloperSidebar() {
      isDeveloperSidebarCollapsed = !isDeveloperSidebarCollapsed;
      localStorage.setItem('bothcorner:developer-sidebar-collapsed', String(isDeveloperSidebarCollapsed));
      applyDeveloperSidebarState();
    }

    function switchPanel(panelId) {
      // Hide all panels
      document.querySelectorAll('.db-panel').forEach(p => {
        p.classList.add('hidden');
        p.classList.remove('block');
      });

      // Show requested panel
      const target = document.getElementById('panel-' + panelId);
      if (target) {
        target.classList.remove('hidden');
        target.classList.add('block');
      }

      // Update sidebar active states
      document.querySelectorAll('[data-nav-link]').forEach(link => {
        link.classList.remove('bg-indigo-500/10', 'text-white', 'border-indigo-500/20', 'shadow-sm', 'shadow-indigo-950/20');
        link.classList.add('border-transparent');
      });
      document.querySelectorAll('[data-nav-parent]').forEach(parent => {
        parent.classList.remove('bg-slate-800/70', 'text-white', 'border-slate-700/80');
        parent.classList.add('border-transparent', 'text-slate-300');
      });

      const activeLi = document.getElementById('menu-' + panelId);
      if (activeLi) {
        const activeLink = activeLi.querySelector('a');
        if (activeLink) {
          activeLink.classList.remove('border-transparent');
          activeLink.classList.add('bg-indigo-500/10', 'text-white', 'border-indigo-500/20', 'shadow-sm', 'shadow-indigo-950/20');
        }

        const activeGroup = activeLi.closest('[data-nav-group]');
        if (activeGroup && !isDeveloperSidebarCollapsed) {
          activeGroup.classList.remove('hidden');
          activeGroup.classList.add('block');

          const parent = document.getElementById('parent-' + activeGroup.id);
          const chevron = document.getElementById('chevron-' + activeGroup.id);
          if (parent) {
            parent.classList.remove('border-transparent', 'text-slate-300');
            parent.classList.add('bg-slate-800/70', 'text-white', 'border-slate-700/80');
          }
          if (chevron) {
            chevron.classList.add('rotate-180');
          }
        }
      }
    }

    function toggleDropdown(id) {
      if (isDeveloperSidebarCollapsed) {
        toggleDeveloperSidebar();
        return;
      }

      const menu = document.getElementById(id);
      const chevron = document.getElementById('chevron-' + id);
      if (menu) {
        if (menu.classList.contains('hidden')) {
          menu.classList.remove('hidden');
          menu.classList.add('block');
          if (chevron) chevron.classList.add('rotate-180');
        } else {
          menu.classList.remove('block');
          menu.classList.add('hidden');
          if (chevron) chevron.classList.remove('rotate-180');
        }
      }
    }

    document.addEventListener('DOMContentLoaded', applyDeveloperSidebarState);

    // Category client-side slug auto generator
    function updateCategorySlug() {
      const name = document.getElementById('category-name').value;
      const slugInput = document.getElementById('category-slug');
      slugInput.value = name.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '');
    }

    // Category edit handler
    function editCategory(id, name, slug) {
      document.getElementById('cat-form-title').innerText = "Edit Kategori Artikel";
      document.getElementById('category-name').value = name;
      document.getElementById('category-slug').value = slug;
      
      const form = document.getElementById('category-form');
      form.action = `/developer/categories/${id}`;
      document.getElementById('category-method').value = "POST";
      
      document.getElementById('category-submit-btn').innerText = "Update Kategori";
      document.getElementById('category-cancel-btn').classList.remove('hidden');
    }

    function cancelEditCategory() {
      document.getElementById('cat-form-title').innerText = "Tambah Kategori Artikel";
      document.getElementById('category-name').value = '';
      document.getElementById('category-slug').value = '';
      
      const form = document.getElementById('category-form');
      form.action = "{{ route('developer.categories.store') }}";
      document.getElementById('category-method').value = "POST";
      
      document.getElementById('category-submit-btn').innerText = "Simpan Kategori";
      document.getElementById('category-cancel-btn').classList.add('hidden');
    }

    // Pricing edit helper
    function editPricing(id, name, category, price, billingPeriod, isInternal, paymentMethod, features) {
      document.getElementById('pricing-form-title').innerText = "Edit Paket Harga";
      document.getElementById('pricing-name').value = name;
      document.getElementById('pricing-category').value = category;
      document.getElementById('pricing-price').value = price;
      document.getElementById('pricing-billing-period').value = billingPeriod;
      document.getElementById('pricing-is-internal').value = isInternal;
      document.getElementById('pricing-payment-method').value = paymentMethod;
      document.getElementById('pricing-features').value = features;
      
      const form = document.getElementById('pricing-form');
      form.action = `/developer/pricing/${id}`;
      document.getElementById('pricing-method').value = "POST";
      
      document.getElementById('pricing-submit-btn').innerText = "Update Paket";
      document.getElementById('pricing-cancel-btn').classList.remove('hidden');
    }

    function cancelEditPricing() {
      document.getElementById('pricing-form-title').innerText = "Tambah Paket Harga";
      document.getElementById('pricing-name').value = '';
      document.getElementById('pricing-category').value = 'Reguler';
      document.getElementById('pricing-price').value = '';
      document.getElementById('pricing-billing-period').value = 'monthly';
      document.getElementById('pricing-is-internal').value = 'Internal';
      document.getElementById('pricing-payment-method').value = 'Online';
      document.getElementById('pricing-features').value = '';
      
      const form = document.getElementById('pricing-form');
      form.action = "{{ route('developer.pricing.store') }}";
      document.getElementById('pricing-method').value = "POST";
      
      document.getElementById('pricing-submit-btn').innerText = "Simpan Paket";
      document.getElementById('pricing-cancel-btn').classList.add('hidden');
    }

    // Users Edit helpers
    function editUser(id, name, email, role) {
      document.getElementById('user-form-title').innerText = "Edit Informasi Pengguna";
      document.getElementById('user-name').value = name;
      document.getElementById('user-email').value = email;
      document.getElementById('user-role').value = role;
      document.getElementById('user-password').required = false;
      document.getElementById('user-password-hint').innerText = "Kosongkan jika tidak ingin mengubah password.";
      
      const form = document.getElementById('user-form');
      form.action = `/developer/users/${id}`;
      document.getElementById('user-method').value = "POST";
      
      document.getElementById('user-submit-btn').innerText = "Update Pengguna";
      document.getElementById('user-cancel-btn').classList.remove('hidden');
    }

    function cancelEditUser() {
      document.getElementById('user-form-title').innerText = "Tambah Pengguna / Tim";
      document.getElementById('user-name').value = '';
      document.getElementById('user-email').value = '';
      document.getElementById('user-password').value = '';
      document.getElementById('user-password').required = true;
      document.getElementById('user-password-hint').innerText = "Wajib diisi saat membuat pengguna baru.";
      document.getElementById('user-role').value = 'user';
      
      const form = document.getElementById('user-form');
      form.action = "{{ route('developer.users.store') }}";
      document.getElementById('user-method').value = "POST";
      
      document.getElementById('user-submit-btn').innerText = "Simpan Pengguna";
      document.getElementById('user-cancel-btn').classList.add('hidden');
    }

    // Support ticket chat detail toggler
    function openTicketDetail(ticketId) {
      // Hide placeholder
      document.getElementById('chat-placeholder').classList.add('hidden');
      
      // Hide all details
      document.querySelectorAll('.ticket-chat-detail').forEach(el => {
        el.classList.add('hidden');
      });
      
      // Remove active background classes from items
      document.querySelectorAll('.ticket-list-item').forEach(el => {
        el.classList.remove('bg-indigo-50/70', 'border-l-4', 'border-indigo-600');
      });

      // Show selected chat detail
      const activeDetail = document.getElementById('chat-detail-' + ticketId);
      if (activeDetail) {
        activeDetail.classList.remove('hidden');
        // Scroll messages feed to bottom
        const feed = activeDetail.querySelector('.overflow-y-auto');
        if (feed) {
          feed.scrollTop = feed.scrollHeight;
        }
      }

      // Highlight active list item
      const activeItem = document.getElementById('ticket-item-' + ticketId);
      if (activeItem) {
        activeItem.classList.add('bg-indigo-50/70', 'border-l-4', 'border-indigo-600');
      }
    }

    function toggleProfileDropdown(event) {
      if (event) event.stopPropagation();
      const dropdown = document.getElementById('profile-dropdown');
      if (dropdown) {
        dropdown.classList.toggle('hidden');
      }
    }

    // Click outside to close profile dropdown
    window.addEventListener('click', () => {
      const dropdown = document.getElementById('profile-dropdown');
      if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
      }
    });
  </script>

</body>
</html>
