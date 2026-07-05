<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subscription Plans - Both Corner</title>

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
          <h1 class="text-xl font-extrabold text-slate-900">Subscription Plans</h1>
          <p class="text-xs text-slate-500 mt-1">Daftar paket penawaran SaaS Both Corner beserta fitur bawaannya.</p>
        </div>
        <a href="{{ route('developer.licenses.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
          ← Kembali ke Overview
        </a>
      </header>

      <div class="p-8">
        
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4 pl-6">Plan Name</th>
                  <th class="p-4">Monthly / Yearly Price</th>
                  <th class="p-4">Limits (Events/Devices)</th>
                  <th class="p-4">Custom Upload</th>
                  <th class="p-4">Watermark / Ads</th>
                  <th class="p-4">Mobile AdMob / Web AdSense</th>
                  <th class="p-4 text-right pr-6">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($plans as $plan)
                  <tr class="hover:bg-slate-50/60 align-middle">
                    <td class="p-4 pl-6">
                      <div class="font-bold text-slate-950">{{ $plan->name }}</div>
                      <div class="text-[10px] font-mono text-slate-400 mt-0.5">Code: {{ $plan->code }}</div>
                    </td>
                    <td class="p-4 font-semibold text-slate-700">
                      @if((float)$plan->price_monthly === 0.0)
                        Free Tier
                      @else
                        {{ number_format($plan->price_monthly, 0, ',', '.') }} / {{ number_format($plan->price_yearly, 0, ',', '.') }} {{ $plan->currency }}
                      @endif
                    </td>
                    <td class="p-4 text-slate-600 font-medium">
                      📅 {{ $plan->max_events ?? 'Unlimited' }} Events &bull; 💻 {{ $plan->max_devices ?? 'Unlimited' }} Devices
                    </td>
                    <td class="p-4">
                      <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $plan->custom_template_upload ? 'bg-emerald-50 border border-emerald-100 text-emerald-700' : 'bg-slate-100 border border-slate-200 text-slate-400' }}">
                        {{ $plan->custom_template_upload ? 'Yes' : 'No' }}
                      </span>
                    </td>
                    <td class="p-4">
                      <div class="space-y-0.5">
                        <div class="text-[10px] text-slate-500">
                          Watermark: <span class="font-bold {{ $plan->watermark_enabled ? 'text-rose-600' : 'text-slate-400' }}">{{ $plan->watermark_enabled ? 'On' : 'Off' }}</span>
                        </div>
                        <div class="text-[10px] text-slate-500">
                          Ads: <span class="font-bold {{ $plan->ads_enabled ? 'text-rose-600' : 'text-slate-400' }}">{{ $plan->ads_enabled ? 'On' : 'Off' }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="p-4">
                      <div class="space-y-0.5">
                        <div class="text-[10px] text-slate-500">
                          AdMob: <span class="font-bold {{ $plan->admob_enabled ? 'text-rose-600' : 'text-slate-400' }}">{{ $plan->admob_enabled ? 'On' : 'Off' }}</span>
                        </div>
                        <div class="text-[10px] text-slate-500">
                          AdSense: <span class="font-bold {{ $plan->adsense_enabled ? 'text-rose-600' : 'text-slate-400' }}">{{ $plan->adsense_enabled ? 'On' : 'Off' }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="p-4 text-right pr-6">
                      <span class="inline-flex px-2 py-0.5 rounded border font-bold text-[9px] uppercase bg-emerald-50 text-emerald-700 border-emerald-100">
                        {{ $plan->status }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="p-12 text-center text-slate-400">
                      Belum ada pricing plan terdaftar.
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
