<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Licenses - Both Corner</title>

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
          <h1 class="text-xl font-extrabold text-slate-900">User Licenses</h1>
          <p class="text-xs text-slate-500 mt-1">Daftar pengguna terdaftar dan alokasi lisensi aktif.</p>
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
        
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                  <th class="p-4 pl-6">User Name</th>
                  <th class="p-4">Active Plan</th>
                  <th class="p-4">Subscription Status</th>
                  <th class="p-4 text-right pr-6">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                  <tr class="hover:bg-slate-50/60 align-middle">
                    <td class="p-4 pl-6">
                      <div class="font-bold text-slate-950">{{ $user->name }}</div>
                      <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->email }}</div>
                    </td>
                    <td class="p-4 text-slate-700 font-semibold">
                      {{ $user->currentPlan->name ?? 'Free Plan (Default)' }}
                    </td>
                    <td class="p-4">
                      @php
                        $statusClass = match(strtolower($user->subscription_status ?? 'free')) {
                          'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                          'trial' => 'bg-amber-50 text-amber-700 border-amber-100',
                          'free' => 'bg-slate-50 text-slate-500 border-slate-200',
                          default => 'bg-rose-50 text-rose-700 border-rose-100'
                        };
                      @endphp
                      <span class="inline-flex px-2.5 py-0.5 rounded border font-bold text-[9px] uppercase {{ $statusClass }}">
                        {{ $user->subscription_status ?? 'free' }}
                      </span>
                    </td>
                    <td class="p-4 text-right pr-6">
                      <button onclick="openPlanModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->current_plan_id }}', '{{ $user->subscription_status ?? 'free' }}')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 transition-colors">
                        Override Plan
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="p-12 text-center text-slate-400">
                      Belum ada pengguna terdaftar.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
              {{ $users->links() }}
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>

  <!-- Plan Override Modal -->
  <div id="planModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl border border-slate-200 max-w-md w-full shadow-2xl overflow-hidden">
      <div class="p-6 border-b border-slate-100">
        <h3 class="font-extrabold text-slate-900 text-lg">Override Subscription License</h3>
        <p class="text-xs text-slate-500 mt-1">Ubah lisensi pengguna <span id="modalUserName" class="font-bold text-slate-800"></span> secara manual.</p>
      </div>

      <form id="modalForm" method="POST" class="p-6 space-y-4">
        @csrf
        
        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Subscription Plan</label>
          <select name="subscription_plan_id" id="modalPlanSelect" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-bold text-xs">
            @foreach($plans as $plan)
              <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->code }})</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Status</label>
          <select name="status" id="modalStatusSelect" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-bold text-xs">
            <option value="free">Free</option>
            <option value="trial">Trial</option>
            <option value="active">Active</option>
            <option value="expired">Expired</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Expires At (Optional)</label>
          <input type="date" name="expires_at" id="modalExpiresAt" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-bold text-xs">
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
          <button type="button" onclick="closePlanModal()" class="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 font-bold rounded-xl text-xs text-slate-600 transition-colors">
            Batal
          </button>
          <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openPlanModal(userId, userName, currentPlanId, currentStatus) {
      document.getElementById('modalUserName').innerText = userName;
      document.getElementById('modalForm').action = "/developer/licenses/users/" + userId + "/assign-plan";
      
      // Fallback default select values
      var planSelect = document.getElementById('modalPlanSelect');
      if (currentPlanId) {
        planSelect.value = currentPlanId;
      } else {
        planSelect.selectedIndex = 0;
      }
      
      document.getElementById('modalStatusSelect').value = currentStatus || 'free';
      document.getElementById('planModal').classList.remove('hidden');
    }

    function closePlanModal() {
      document.getElementById('planModal').classList.add('hidden');
    }
  </script>
</body>
</html>
