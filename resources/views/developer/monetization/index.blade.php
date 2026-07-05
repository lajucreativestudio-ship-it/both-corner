<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Monetization Settings - Both Corner</title>

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
          <h1 class="text-xl font-extrabold text-slate-900">Monetization Settings</h1>
          <p class="text-xs text-slate-500 mt-1">Konfigurasi variabel periklanan, pelacakan analitik, dan teks branding default.</p>
        </div>
        <a href="{{ route('developer.licenses.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
          ← Kembali ke Overview
        </a>
      </header>

      <div class="p-8">

        @if(session('success'))
          <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-semibold rounded-2xl">
            {{ session('success') }}
          </div>
        @endif
        
        <form method="POST" action="{{ route('developer.monetization.store') }}" class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-8 space-y-6">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 1. General Config -->
            <div class="space-y-4">
              <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">🌐 General & Domain Settings</h3>
              
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Public Share Domain (Tanpa protocol)</label>
                <input type="text" name="public_share_domain" value="{{ old('public_share_domain', $settings['public_share_domain']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
                <span class="text-[10px] text-slate-400 mt-1 block">Contoh: share.bothcorner.com</span>
              </div>
              
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Default Watermark Text</label>
                <input type="text" name="default_watermark_text" value="{{ old('default_watermark_text', $settings['default_watermark_text']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Default Branding Footer Text</label>
                <input type="text" name="default_branding_text" value="{{ old('default_branding_text', $settings['default_branding_text']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>
            </div>

            <!-- 2. AdSense Config -->
            <div class="space-y-4">
              <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">🖥️ Google AdSense (Web Gallery)</h3>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Google AdSense Enabled (1 = Yes, 0 = No)</label>
                <input type="text" name="adsense_enabled" value="{{ old('adsense_enabled', $settings['adsense_enabled']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Google AdSense Client ID (ca-pub-xxx)</label>
                <input type="text" name="adsense_client_id" value="{{ old('adsense_client_id', $settings['adsense_client_id']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Google AdSense Download Page Slot ID</label>
                <input type="text" name="adsense_download_slot_id" value="{{ old('adsense_download_slot_id', $settings['adsense_download_slot_id']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
            <!-- 3. AdMob Config -->
            <div class="space-y-4">
              <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">📱 Google AdMob (Android App SDK)</h3>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">AdMob Enabled (1 = Yes, 0 = No)</label>
                <input type="text" name="admob_enabled" value="{{ old('admob_enabled', $settings['admob_enabled']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">AdMob App ID</label>
                <input type="text" name="admob_app_id" value="{{ old('admob_app_id', $settings['admob_app_id']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">AdMob Banner Unit ID</label>
                <input type="text" name="admob_banner_unit_id" value="{{ old('admob_banner_unit_id', $settings['admob_banner_unit_id']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">AdMob Interstitial Unit ID</label>
                <input type="text" name="admob_interstitial_unit_id" value="{{ old('admob_interstitial_unit_id', $settings['admob_interstitial_unit_id']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>
            </div>

            <!-- 4. Analytics Config -->
            <div class="space-y-4">
              <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">📊 Analytics Tracking</h3>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Google/Firebase Analytics Enabled (1 = Yes, 0 = No)</label>
                <input type="text" name="analytics_enabled" value="{{ old('analytics_enabled', $settings['analytics_enabled']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Firebase Measurement ID (G-XXXXXX)</label>
                <input type="text" name="firebase_measurement_id" value="{{ old('firebase_measurement_id', $settings['firebase_measurement_id']) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-medium text-xs text-slate-800">
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors">
              Simpan Konfigurasi Monetisasi
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>
