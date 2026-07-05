<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>License Overview - Both Corner</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body { font-family: 'Instrument Sans', sans-serif; }
    h1, h2, h3, h4, .font-display { font-family: 'Outfit', sans-serif; }
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
          <h1 class="text-xl font-extrabold text-slate-900">Subscription & Licenses Overview</h1>
          <p class="text-xs text-slate-500 mt-1">Kelola pembagian fitur, limitasi akun, dan hak istimewa pengguna SaaS.</p>
        </div>
      </header>

      <div class="p-8 space-y-8">
        
        <!-- Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Free Tier Users</span>
            <span class="text-3xl font-extrabold text-slate-800 block mt-2">{{ $totalFreeUsers }}</span>
            <span class="text-[10px] text-slate-400 mt-1">Pengguna tanpa paket subscription aktif</span>
          </div>

          <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Active Paid Subscriptions</span>
            <span class="text-3xl font-extrabold text-indigo-600 block mt-2">{{ $totalActivePaidUsers }}</span>
            <span class="text-[10px] text-slate-400 mt-1">Status subscription aktif / trial</span>
          </div>

          <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Available Plans</span>
            <span class="text-3xl font-extrabold text-slate-800 block mt-2">{{ $totalPlans }}</span>
            <span class="text-[10px] text-slate-400 mt-1">Paket yang ditawarkan secara publik</span>
          </div>
        </div>

        <!-- Links Control Board -->
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 space-y-4">
          <h3 class="font-extrabold text-slate-900 text-base">Control Panel Links</h3>
          <p class="text-xs text-slate-500">Pilih menu di bawah ini untuk mengakses konfigurasi khusus lisensi dan plan.</p>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <a href="{{ route('developer.licenses.plans') }}" class="p-6 border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50/10 rounded-2xl flex flex-col justify-between transition-all group">
              <span class="text-2xl group-hover:scale-110 transition-transform block">📋</span>
              <span class="font-bold text-slate-800 block mt-4">Subscription Plans</span>
              <span class="text-[11px] text-slate-400 mt-1">Lihat limitasi event, watermark, iklan, dan custom template per paket.</span>
            </a>

            <a href="{{ route('developer.licenses.users') }}" class="p-6 border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50/10 rounded-2xl flex flex-col justify-between transition-all group">
              <span class="text-2xl group-hover:scale-110 transition-transform block">👤</span>
              <span class="font-bold text-slate-800 block mt-4">User Licenses</span>
              <span class="text-[11px] text-slate-400 mt-1">Daftar pengguna terdaftar dan berikan override lisensi secara manual.</span>
            </a>

            <a href="{{ route('developer.monetization.index') }}" class="p-6 border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50/10 rounded-2xl flex flex-col justify-between transition-all group">
              <span class="text-2xl group-hover:scale-110 transition-transform block">💵</span>
              <span class="font-bold text-slate-800 block mt-4">Monetization Settings</span>
              <span class="text-[11px] text-slate-400 mt-1">Atur AdSense client ID, AdMob banner/interstitial, domain share, dan watermark dasar.</span>
            </a>
          </div>
        </div>

      </div>
    </main>
  </div>
</body>
</html>
