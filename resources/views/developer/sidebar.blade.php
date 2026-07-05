@php
  $currentRoute = request()->route() ? request()->route()->getName() : '';
  $currentPanel = request()->query('panel', 'summary');
  
  $isSummaryActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'summary');
  $isDevicesActive = str_starts_with($currentRoute, 'developer.devices');
  $isEventsActive = str_starts_with($currentRoute, 'developer.events');
  $isTemplatesActive = str_starts_with($currentRoute, 'developer.templates');
  $isLicensesActive = str_starts_with($currentRoute, 'developer.licenses');
  $isMonetizationActive = str_starts_with($currentRoute, 'developer.monetization');
  
  // SPA panels
  $isArticlesActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'articles');
  $isCategoriesActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'categories');
  $isPagesActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'pages');
  $isMenusActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'menus');
  $isPaymentsActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'payments');
  $isRevenueActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'revenue');
  $isPricingActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'pricing');
  $isUsersActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'users');
  $isRolesActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'roles');
  $isChatsActive = ($currentRoute === 'developer.dashboard' && $currentPanel === 'chats');
  
  // Dropdown states
  $isContentGroupOpen = $isArticlesActive || $isCategoriesActive || $isPagesActive || $isMenusActive;
  $isUsersGroupOpen = $isUsersActive || $isRolesActive;
@endphp

<aside id="developer-sidebar" class="w-72 bg-[#0f172a] text-slate-400 flex flex-col justify-between shrink-0 border-r border-slate-800 select-none transition-all duration-300 ease-in-out">
  <div class="min-h-0 flex flex-col flex-1">
    <!-- Brand Logo -->
    <div id="developer-sidebar-brand" class="h-20 px-6 border-b border-slate-800/70 flex items-center gap-3 shrink-0 transition-all duration-300">
      <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shrink-0">B</div>
      <div class="sidebar-label leading-tight">
        <span class="block text-base font-extrabold text-white tracking-tight">Both<span class="text-indigo-400">Dev</span></span>
        <span class="text-[10px] uppercase tracking-[0.22em] text-slate-500 font-bold">Admin Console</span>
      </div>
    </div>

    <!-- Nav Menu list -->
    <nav id="developer-sidebar-nav" class="p-4 overflow-y-auto transition-all duration-300 flex-1">
      <p class="sidebar-label text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] px-3 mb-3">Developer Tools</p>
      <ul class="space-y-1.5">
        <li id="menu-summary">
          <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'summary\')"' : 'href="' . route('developer.dashboard') . '?panel=summary"' !!} data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isSummaryActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isSummaryActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">📊</span>
            <span class="sidebar-label truncate">Ringkasan</span>
          </a>
        </li>

        <li>
          <a href="{{ route('developer.devices.index') }}" data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isDevicesActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isDevicesActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">💻</span>
            <span class="sidebar-label truncate">Device Management</span>
          </a>
        </li>

        <li>
          <a href="{{ route('developer.events.index') }}" data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isEventsActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isEventsActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">📅</span>
            <span class="sidebar-label truncate">Event Monitoring</span>
          </a>
        </li>

        <li>
          <a href="{{ route('developer.templates.index') }}" data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isTemplatesActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isTemplatesActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">🎨</span>
            <span class="sidebar-label truncate">Global Templates</span>
          </a>
        </li>

        <li>
          <a href="{{ route('developer.licenses.index') }}" data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isLicensesActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isLicensesActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">🔑</span>
            <span class="sidebar-label truncate">Lisensi & Plan</span>
          </a>
        </li>

        <li>
          <a href="{{ route('developer.monetization.index') }}" data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isMonetizationActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isMonetizationActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">💵</span>
            <span class="sidebar-label truncate">Monetisasi</span>
          </a>
        </li>

        <!-- Dropdown Kelola Konten -->
        <li>
          <button type="button" id="parent-dropdown-content" data-nav-parent onclick="toggleDropdown('dropdown-content')" class="w-full flex items-center justify-between px-3.5 py-3 text-sm font-bold rounded-xl border border-transparent hover:border-slate-700/70 hover:text-white hover:bg-slate-800/50 text-slate-300 transition-all duration-200 cursor-pointer">
            <span class="sidebar-parent-label flex items-center gap-3 min-w-0">
              <span class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center shrink-0">📁</span>
              <span class="sidebar-label truncate">Kelola Konten</span>
            </span>
            <span id="chevron-dropdown-content" class="sidebar-chevron transition-transform duration-200 text-[10px] text-slate-500 {{ $isContentGroupOpen ? '' : 'rotate-180' }}">▲</span>
          </button>
          <ul id="dropdown-content" data-nav-group class="relative ml-7 mt-1.5 space-y-1 border-l border-slate-700/60 pl-3 {{ $isContentGroupOpen ? 'block' : 'hidden' }}">
            <li id="menu-articles">
              <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'articles\')"' : 'href="' . route('developer.dashboard') . '?panel=articles"' !!} data-nav-link class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border {{ $isArticlesActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm' : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all duration-200 cursor-pointer">
                <span class="w-5 text-center">📰</span> <span class="sidebar-label truncate">Kelola Artikel</span>
              </a>
            </li>
            <li id="menu-categories">
              <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'categories\')"' : 'href="' . route('developer.dashboard') . '?panel=categories"' !!} data-nav-link class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border {{ $isCategoriesActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm' : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all duration-200 cursor-pointer">
                <span class="w-5 text-center">🏷️</span> <span class="sidebar-label truncate">Kategori Artikel</span>
              </a>
            </li>
            <li id="menu-pages">
              <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'pages\')"' : 'href="' . route('developer.dashboard') . '?panel=pages"' !!} data-nav-link class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border {{ $isPagesActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm' : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all duration-200 cursor-pointer">
                <span class="w-5 text-center">📄</span> <span class="sidebar-label truncate">Kelola Halaman Statis</span>
              </a>
            </li>
            <li id="menu-menus">
              <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'menus\')"' : 'href="' . route('developer.dashboard') . '?panel=menus"' !!} data-nav-link class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border {{ $isMenusActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm' : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all duration-200 cursor-pointer">
                <span class="w-5 text-center">🔗</span> <span class="sidebar-label truncate">Kelola Menu Navigasi</span>
              </a>
            </li>
          </ul>
        </li>

        <li id="menu-payments">
          <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'payments\')"' : 'href="' . route('developer.dashboard') . '?panel=payments"' !!} data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isPaymentsActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isPaymentsActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">💳</span>
            <span class="sidebar-label truncate">Gateway Pembayaran</span>
          </a>
        </li>

        <li id="menu-revenue">
          <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'revenue\')"' : 'href="' . route('developer.dashboard') . '?panel=revenue"' !!} data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isRevenueActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isRevenueActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">📈</span>
            <span class="sidebar-label truncate">Pendapatan</span>
          </a>
        </li>

        <li id="menu-pricing">
          <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'pricing\')"' : 'href="' . route('developer.dashboard') . '?panel=pricing"' !!} data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isPricingActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isPricingActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">💰</span>
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
            <span id="chevron-dropdown-users" class="sidebar-chevron transition-transform duration-200 text-[10px] text-slate-500 {{ $isUsersGroupOpen ? '' : 'rotate-180' }}">▲</span>
          </button>
          <ul id="dropdown-users" data-nav-group class="relative ml-7 mt-1.5 space-y-1 border-l border-slate-700/60 pl-3 {{ $isUsersGroupOpen ? 'block' : 'hidden' }}">
            <li id="menu-users">
              <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'users\')"' : 'href="' . route('developer.dashboard') . '?panel=users"' !!} data-nav-link class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border {{ $isUsersActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm' : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all duration-200 cursor-pointer">
                <span class="w-5 text-center">👤</span> <span class="sidebar-label truncate">Daftar Pengguna / Tim</span>
              </a>
            </li>
            <li id="menu-roles">
              <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'roles\')"' : 'href="' . route('developer.dashboard') . '?panel=roles"' !!} data-nav-link class="flex items-center gap-2.5 px-3 py-2.5 text-[13px] font-semibold rounded-lg border {{ $isRolesActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm' : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all duration-200 cursor-pointer">
                <span class="w-5 text-center">🔑</span> <span class="sidebar-label truncate">Peran & Hak Akses</span>
              </a>
            </li>
          </ul>
        </li>

        <li id="menu-chats">
          <a {!! $currentRoute === 'developer.dashboard' ? 'href="#" onclick="switchPanel(\'chats\')"' : 'href="' . route('developer.dashboard') . '?panel=chats"' !!} data-nav-link class="flex items-center gap-3 px-3.5 py-3 text-sm font-bold rounded-xl border {{ $isChatsActive ? 'border-indigo-500/20 bg-indigo-500/10 text-white shadow-sm shadow-indigo-950/20' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-800/50 hover:border-slate-700/70' }} transition-all duration-200 cursor-pointer">
            <span class="w-8 h-8 rounded-lg {{ $isChatsActive ? 'bg-indigo-500/15 text-indigo-300' : 'bg-slate-800 text-slate-300' }} flex items-center justify-center shrink-0">💬</span>
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
        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->email ?? '' }}</p>
      </div>
      <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-indigo-400 hover:text-indigo-300 hover:bg-slate-800/60 transition-colors">
        👤 Client Dashboard
      </a>
      <div class="border-t border-slate-800"></div>
      <form action="{{ route('logout') }}" method="POST" class="block w-full">
        @csrf
        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-red-400 hover:text-red-300 hover:bg-red-550/10 transition-colors cursor-pointer">
          🚪 Log Out
        </button>
      </form>
    </div>

    <div id="developer-profile-trigger" onclick="toggleProfileDropdown(event)" class="flex items-center justify-between cursor-pointer group p-1.5 rounded-xl hover:bg-slate-800/30 transition-colors">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md group-hover:scale-105 transition-transform shrink-0">
          {{ strtoupper(substr(auth()->user()->name ?? 'DV', 0, 2)) }}
        </div>
        <div class="sidebar-label flex flex-col min-w-0">
          <span class="text-xs font-bold text-white group-hover:text-indigo-300 transition-colors truncate">{{ auth()->user()->name ?? 'Developer' }}</span>
          <span class="text-[10px] text-slate-500 truncate">Administrator</span>
        </div>
      </div>
      <span class="sidebar-label text-slate-500 group-hover:text-white text-[10px] select-none">▲</span>
    </div>
  </div>
</aside>

<script>
  if (typeof window.toggleDropdown === 'undefined') {
    window.toggleDropdown = function(id) {
      const dropdown = document.getElementById(id);
      const chevron = document.getElementById('chevron-' + id);
      if (dropdown) {
        dropdown.classList.toggle('hidden');
        dropdown.classList.toggle('block');
        if (chevron) {
          chevron.classList.toggle('rotate-180');
        }
      }
    };
  }
  if (typeof window.toggleProfileDropdown === 'undefined') {
    window.toggleProfileDropdown = function(event) {
      if (event) event.stopPropagation();
      const dropdown = document.querySelector('#developer-profile-footer #profile-dropdown');
      if (dropdown) {
        dropdown.classList.toggle('hidden');
      }
    };
    window.addEventListener('click', () => {
      const dropdown = document.querySelector('#developer-profile-footer #profile-dropdown');
      if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
      }
    });
  }
</script>
