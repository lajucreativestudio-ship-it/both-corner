<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Both Corner - Software Photobooth Windows PC & Android Tablet</title>
  <meta name="description" content="Software photobooth lengkap untuk perangkat Android dan Windows. Kelola event, welcome screen, template layout, dan sharing gallery langsung dari cloud dashboard.">
  
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
<body class="bg-slate-50 text-slate-950 antialiased selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">

  <!-- Ambient Glow Effects -->
  <div class="absolute top-[-10%] left-[-20%] w-[600px] h-[600px] rounded-full bg-violet-200/40 blur-[120px] pointer-events-none -z-10"></div>
  <div class="absolute top-[20%] right-[-20%] w-[500px] h-[500px] rounded-full bg-indigo-200/30 blur-[100px] pointer-events-none -z-10"></div>

  <!-- Header / Navigation -->
  <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/80 border-b border-slate-200/50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
      <a href="#" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-indigo-500/20">B</div>
        <span class="text-xl font-bold tracking-tight text-slate-900">Both<span class="text-indigo-600">Corner</span></span>
      </a>
      
      <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
        @foreach($menus as $menu)
          @if(Str::startsWith($menu->url, '/'))
            <a href="{{ $menu->url }}" class="hover:text-indigo-600 transition-colors">{{ $menu->title }}</a>
          @else
            <a href="{{ $menu->url }}" class="hover:text-indigo-600 transition-colors">{{ $menu->title }}</a>
          @endif
        @endforeach
      </nav>

      <div class="flex items-center gap-4">
        <a href="#unduh" class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 rounded-full text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all duration-200">Free Trial</a>
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/25 transition-all duration-200 hover:-translate-y-0.5">Buka Dashboard</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="relative pt-16 pb-20 px-6 max-w-7xl mx-auto text-center">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
        {!! $landingPage ? $landingPage->hero_title : 'Software Photobooth Profesional <br><span class="bg-gradient-to-r from-violet-600 via-indigo-600 to-indigo-500 bg-clip-text text-transparent">Windows PC & Android Tablet</span>' !!}
      </h1>
      <p class="text-lg text-slate-600 mt-6 leading-relaxed max-w-3xl mx-auto">
        {{ $landingPage ? $landingPage->hero_subtitle : 'Jalankan photobooth interaktif langsung dari tablet Android atau PC Windows Anda. Kompatibel dengan kamera DSLR Canon, Nikon, Sony, GoPro, dan Webcam. Konfigurasi seluruh event secara instan dari cloud.' }}
      </p>
      
      <div class="mt-10 flex flex-wrap gap-4 justify-center">
        <a href="#unduh" class="inline-flex items-center justify-center px-8 py-4 rounded-full text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/40 hover:-translate-y-0.5 transition-all duration-200">
          {{ $landingPage ? $landingPage->cta_text : 'Coba Free Trial' }}
        </a>
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-full text-base font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 shadow-sm hover:-translate-y-0.5 transition-all duration-200">
          Buka Cloud Dashboard
        </a>
      </div>
    </div>

    <!-- Interactive Showcase Mockup of Software Welcome Screen -->
    <div class="mt-16 max-w-4xl mx-auto rounded-2xl border border-slate-200/70 bg-white shadow-2xl overflow-hidden">
      <!-- Window Header -->
      <div class="bg-slate-50 border-b border-slate-200 px-4 py-3 flex items-center gap-2">
        <div class="w-3 h-3 rounded-full bg-red-400"></div>
        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
        <div class="w-3 h-3 rounded-full bg-green-400"></div>
        <span class="text-xs font-semibold text-slate-400 mx-auto select-none">Both Corner Photobooth App</span>
        <div class="w-12"></div>
      </div>
      <!-- Window Content -->
      <div class="relative w-full aspect-[16/10] bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 flex flex-col justify-between p-8 bg-[radial-gradient(circle_at_center,var(--tw-gradient-stops))] from-slate-50 to-slate-300 text-slate-900 select-none">
          <div class="w-full flex justify-center z-10">
            <span class="text-2xl font-bold tracking-tight text-slate-800">Both<span class="text-indigo-600">Corner</span></span>
          </div>

          <!-- Mock Live Camera Feed -->
          <div class="absolute inset-x-8 top-16 bottom-24 bg-black rounded-xl overflow-hidden shadow-inner flex items-center justify-center">
            <img src="{{ asset('photobooth_event.png') }}" class="w-full h-full object-cover opacity-90 transition-opacity duration-300 hover:opacity-100" alt="Mock Live Feed Photo">
          </div>

          <!-- Pulsing Tap To Start Button -->
          <div class="absolute top-[48%] left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
            <button class="px-8 py-3.5 rounded-full text-base font-bold text-white bg-gradient-to-r from-violet-600 to-indigo-600 shadow-lg shadow-indigo-600/40 animate-pulse cursor-pointer">
              TAP TO START
            </button>
          </div>

          <!-- Mode Options at Bottom -->
          <div class="w-full flex justify-center gap-4 z-10">
            <div class="bg-white/95 backdrop-blur-sm border border-slate-200/50 rounded-lg px-4 py-2 text-xs font-bold shadow-sm flex items-center gap-2">📸 PHOTO</div>
            <div class="bg-white/95 backdrop-blur-sm border border-slate-200/50 rounded-lg px-4 py-2 text-xs font-bold shadow-sm flex items-center gap-2">🎞️ GIF</div>
            <div class="bg-white/95 backdrop-blur-sm border border-slate-200/50 rounded-lg px-4 py-2 text-xs font-bold shadow-sm flex items-center gap-2">🔄 BOOMERANG</div>
            <div class="bg-white/95 backdrop-blur-sm border border-slate-200/50 rounded-lg px-4 py-2 text-xs font-bold shadow-sm flex items-center gap-2">🎥 VIDEO</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Core Features Section -->
  <section id="fitur" class="py-20 bg-white border-y border-slate-200/50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1: Capture -->
        <div class="p-8 rounded-2xl border border-slate-100 hover:border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 text-center">
          <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">📸</div>
          <h3 class="text-xl font-bold text-slate-900">Capture</h3>
          <p class="text-slate-500 mt-3 text-sm leading-relaxed">
            Ambil foto, GIF animasi, video boomerang, dan mode potret berkualitas tinggi menggunakan aplikasi client Windows PC atau tablet Android Anda di lapangan.
          </p>
        </div>

        <!-- Feature 2: Customize -->
        <div class="p-8 rounded-2xl border border-slate-100 hover:border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 text-center">
          <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">🎨</div>
          <h3 class="text-xl font-bold text-slate-900">Customize</h3>
          <p class="text-slate-500 mt-3 text-sm leading-relaxed">
            Bentuk pengalaman photobooth yang khas untuk setiap event. Kustomisasi welcome screen, warna tema aplikasi, template layout cetak, hingga background foto.
          </p>
        </div>

        <!-- Feature 3: Share -->
        <div class="p-8 rounded-2xl border border-slate-100 hover:border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 text-center">
          <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">🔗</div>
          <h3 class="text-xl font-bold text-slate-900">Share & Print</h3>
          <p class="text-slate-500 mt-3 text-sm leading-relaxed">
            Bagikan hasil secara instan lewat Email, SMS, WhatsApp, QR code, atau cetak fisik kertas berbagai ukuran (strip 2x6, postcard 4x6) secara otomatis dengan printer foto.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Use Cases / Software Modes (8 Items: 4 Top, 4 Bottom) -->
  <section id="use-cases" class="py-20 bg-slate-50/50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lebih dari Sekedar Aplikasi Photobooth Biasa</h2>
        <p class="text-slate-500 mt-3">Pilih mode operasional terbaik yang sesuai dengan konsep event dan keinginan klien Anda.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Glam Booth -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">✨</div>
          <h3 class="text-lg font-bold text-slate-900">Glam Booth (Kardashian Style)</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Berikan tamu Anda kulit yang tampak sempurna secara instan menggunakan filter penghalus wajah kustom dan mode hitam-putih kontras tinggi yang menawan.
          </p>
        </div>

        <!-- Green Screen -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">🟢</div>
          <h3 class="text-lg font-bold text-slate-900">Green Screen & AI Background</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Pindahkan tamu Anda ke mana saja di seluruh dunia. Ganti latar belakang foto secara real-time dengan bantuan green screen fisik atau teknologi AI background removal.
          </p>
        </div>

        <!-- Mirror Booth -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">🪞</div>
          <h3 class="text-lg font-bold text-slate-900">Mirror Booth (Interactive Video)</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Pandu tamu sepanjang sesi foto menggunakan animasi petunjuk visual interaktif pada cermin, suara pemandu, serta hitung mundur kustom.
          </p>
        </div>

        <!-- Cashless Payments -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">💳</div>
          <h3 class="text-lg font-bold text-slate-900">Cashless Payments (Vending Mode)</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Ubah photobooth Anda menjadi mesin penghasil profit otomatis. Integrasikan pembayaran non-tunai (QRIS/Card) tanpa perangkat keras tambahan yang rumit.
          </p>
        </div>

        <!-- Generate Voucher -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">🎟️</div>
          <h3 class="text-lg font-bold text-slate-900">Generate Voucher (Offline Mode)</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Buat dan cetak kode voucher unik secara instan dari dashboard untuk memberikan sesi foto bagi tamu secara terbatas atau berbayar via kasir offline.
          </p>
        </div>

        <!-- Scan Barcode -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">📲</div>
          <h3 class="text-lg font-bold text-slate-900">Scan Barcode untuk Unduh</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Tamu cukup memindai barcode unik yang tercetak pada kertas foto fisik untuk langsung mengunduh file digital resolusi tinggi mereka dari cloud storage.
          </p>
        </div>

        <!-- Multi Device Control -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">🖥️</div>
          <h3 class="text-lg font-bold text-slate-900">Multi Device Control</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Hubungkan dan kendalikan beberapa perangkat sharing station, printer server, dan kamera tambahan secara nirkabel dalam satu jaringan lokal yang stabil.
          </p>
        </div>

        <!-- Full Branding -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/50 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col items-start group">
          <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">🏷️</div>
          <h3 class="text-lg font-bold text-slate-900">Full Kustomisasi Personal Branding</h3>
          <p class="text-slate-500 mt-2 text-xs leading-relaxed">
            Terapkan branding visual usaha Anda secara menyeluruh pada aplikasi. Mulai dari watermark foto, email custom sender, domain website kustom, hingga logo startup screen.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Customizable Sections (Touch Rows) -->
  <section id="kustomisasi" class="py-24 max-w-7xl mx-auto px-6">
    <div class="text-center max-w-3xl mx-auto mb-20">
      <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sentuhan Personal di Setiap Sudut</h2>
      <p class="text-slate-500 mt-3">Sesuaikan tampilan visual software untuk menciptakan pengalaman bermerek yang mendalam.</p>
    </div>

    <!-- Row 1: Customize All Screens -->
    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16 mb-24">
      <div class="w-full lg:w-1/2">
        <h3 class="text-2xl font-bold text-slate-900">Kustomisasi Welcome Screen</h3>
        <p class="text-slate-600 mt-4 leading-relaxed">
          Desain seluruh layar yang dilihat oleh tamu Anda saat mendekati booth. Atur font teks kustom, paduan warna tema, gambar latar belakang (*background*), hingga pemutaran video promosi melingkar (*looping video*) untuk menarik perhatian tamu.
        </p>
        <p class="text-slate-600 mt-3 leading-relaxed">
          Anda juga dapat menaruh kotak *live preview camera* di layar utama agar tamu bisa langsung menyesuaikan posisi pose mereka sebelum hitung mundur dimulai.
        </p>
      </div>
      <div class="w-full lg:w-1/2">
        <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-200/50 hover:shadow-xl transition-shadow duration-300">
          <img src="{{ asset('welcome_screen_editor.png') }}" class="w-full h-auto object-cover" alt="Custom Screen Setup Photo">
        </div>
      </div>
    </div>

    <!-- Row 2: Drag and Drop Print Layout (Reverse Layout) -->
    <div class="flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-16 mb-24">
      <div class="w-full lg:w-1/2">
        <h3 class="text-2xl font-bold text-slate-900">Template Editor Tata Letak Cetak</h3>
        <p class="text-slate-600 mt-4 leading-relaxed">
          Buat template tata letak cetak photostrip 2x6 inci atau postcard 4x6 inci yang berisi 1 hingga 4 pose foto. Gunakan editor tata letak cloud drag-and-drop kami untuk meletakkan kotak foto, teks nama event, serta logo sponsor dengan sangat presisi.
        </p>
        <p class="text-slate-600 mt-3 leading-relaxed">
          Template cetak dapat diunggah dengan file overlay `.png` transparan buatan desainer Anda untuk branding acara korporat kelas atas.
        </p>
      </div>
      <div class="w-full lg:w-1/2">
        <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-200/50 hover:shadow-xl transition-shadow duration-300">
          <img src="{{ asset('photobooth_strip.png') }}" class="w-full h-auto object-cover" alt="Print Template Editor Preview">
        </div>
      </div>
    </div>

    <!-- Row 3: Sharing Gallery -->
    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
      <div class="w-full lg:w-1/2">
        <h3 class="text-2xl font-bold text-slate-900">Galeri Digital Online Bermerek (Both Corner Gallery)</h3>
        <p class="text-slate-600 mt-4 leading-relaxed">
          Setiap foto, GIF, dan video yang diambil di lapangan akan langsung masuk ke Cloud Gallery milik Anda. Tamu dapat memindai QR Code pasca foto untuk diarahkan ke halaman web galeri khusus event tersebut.
        </p>
        <p class="text-slate-600 mt-3 leading-relaxed">
          Kustomisasi galeri digital Anda dengan logo bisnis, domain kustom, tautan media sosial, serta sematkan (*embed*) galeri tersebut langsung di website Anda untuk kebutuhan SEO marketing.
        </p>
      </div>
      <div class="w-full lg:w-1/2">
        <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-200/50 hover:shadow-xl transition-shadow duration-300">
          <img src="{{ asset('sharing_gallery.png') }}" class="w-full h-auto object-cover" alt="Cloud Sharing Gallery Mobile Screen View">
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works (Steps) -->
  <section id="cara-kerja" class="py-20 bg-slate-900 text-white rounded-3xl max-w-7xl mx-6 lg:mx-auto px-8 md:px-12 my-12 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900/40 via-violet-900/30 to-slate-900/50 -z-10"></div>
    <div class="text-center max-w-3xl mx-auto mb-16">
      <h2 class="text-3xl font-extrabold tracking-tight">3 Langkah Sederhana Menjalankan Event</h2>
      <p class="text-slate-400 mt-3 text-sm">Manajemen photobooth berbasis cloud untuk menghemat waktu penyiapan lapangan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white/5 border border-white/10 p-8 rounded-2xl backdrop-blur-sm">
        <div class="text-2xl font-bold text-indigo-400 mb-4">01. Rancang di Web</div>
        <p class="text-slate-300 text-sm leading-relaxed">
          Login ke dashboard Both Corner Cloud dari laptop Anda. Siapkan event, kustomisasi tombol welcome screen, dan unggah template print layout.
        </p>
      </div>
      <div class="bg-white/5 border border-white/10 p-8 rounded-2xl backdrop-blur-sm">
        <div class="text-2xl font-bold text-violet-400 mb-4">02. Buka Aplikasi & Jalankan</div>
        <p class="text-slate-300 text-sm leading-relaxed">
          Buka aplikasi client Both Corner di Windows PC atau tablet Android Anda di lokasi event. Seluruh konfigurasi cloud Anda akan langsung termuat secara instan.
        </p>
      </div>
      <div class="bg-white/5 border border-white/10 p-8 rounded-2xl backdrop-blur-sm">
        <div class="text-2xl font-bold text-indigo-400 mb-4">03. Jepret & Bagikan</div>
        <p class="text-slate-300 text-sm leading-relaxed">
          Biarkan para tamu berfoto ria. Foto dicetak secara otomatis dan file digital langsung terunggah ke galeri cloud siap unduh.
        </p>
      </div>
  </section>

  <!-- Pricing Section (Choose the Perfect Plan) -->
  <section id="pricing" class="py-24 bg-white border-y border-slate-200/50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Choose the Perfect Plan</h2>
        <p class="text-slate-500 mt-3 text-sm">Flexible solutions tailored to fit your community's unique needs with premium features and dedicated support</p>

        <!-- Filters matching screenshot -->
        <div class="mt-8 inline-flex p-1 rounded-xl bg-slate-100 text-xs font-semibold gap-1 select-none">
          <button onclick="showToast('Filter: Semua Paket')" class="px-4 py-2 rounded-lg text-slate-500 hover:text-slate-900 transition-colors">Semua Paket</button>
          <button onclick="showToast('Filter: DSLRBooth')" class="px-4 py-2 rounded-lg text-slate-500 hover:text-slate-900 transition-colors">DSLRBooth</button>
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

      <!-- Pricing Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach($plans as $index => $plan)
          @php
            $icons = ['🏆', '⭐', '⚡', '🤝'];
            $icon = $icons[$index % count($icons)];
            $isDark = ($index === 0);
          @endphp
          <div class="p-6 rounded-2xl border flex flex-col justify-between group hover:-translate-y-1 transition-all duration-300 {{ $isDark ? 'bg-slate-900 text-white border-slate-800 shadow-xl' : 'bg-white text-slate-900 border-slate-200 shadow-sm hover:shadow-xl' }}">
            <div>
              <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl mb-4 {{ $isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-indigo-50 text-indigo-600' }}">{{ $icon }}</div>
              <h3 class="text-base font-bold leading-tight {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ $plan->name }}</h3>
              <div class="mt-4 flex items-baseline gap-1">
                <span class="text-xs font-bold text-slate-400">Rp</span>
                <span class="text-2xl font-extrabold {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ number_format($plan->price, 0, ',', '.') }}</span>
                <span class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-500' }}">/ bln</span>
              </div>
              
              <div class="flex flex-wrap gap-1 mt-3">
                <span class="px-2 py-0.5 rounded text-[8px] font-bold {{ $isDark ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-55/20' : 'bg-indigo-55/10 text-indigo-600' }}">Mendukung Photobooth {{ $plan->is_internal }}</span>
                <span class="px-2 py-0.5 rounded text-[8px] font-bold {{ $isDark ? 'bg-emerald-600/20 text-emerald-300 border border-emerald-55/20' : 'bg-emerald-55/10 text-emerald-650' }}">Payment: {{ $plan->payment_method }}</span>
              </div>

              <ul class="mt-6 space-y-2.5 text-xs {{ $isDark ? 'text-slate-300' : 'text-slate-500' }}">
                @foreach($plan->features_list as $feature)
                  <li class="flex items-start gap-2">✓ <span>{{ $feature }}</span></li>
                @endforeach
              </ul>
            </div>
            <a href="{{ route('login') }}" class="mt-8 w-full py-2.5 rounded-full text-xs font-bold text-center text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-600/25">Pilih Paket</a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Blog Section (Dynamic) -->
  <section id="blog" class="py-24 bg-slate-50 border-t border-slate-200/50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider">Blog & Berita</span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-3 tracking-tight">Kabar Terbaru & Panduan</h2>
        <p class="text-slate-500 mt-2 text-sm">Informasi kompatibilitas kamera, panduan printer, dan upgrade versi Both Corner</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($latestArticles as $article)
          <article class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div>
              <div class="h-44 bg-slate-55 overflow-hidden relative flex items-center justify-center p-4 border-b border-slate-100">
                <img src="{{ asset($article->image_url ?: 'login_slide_1.png') }}" class="max-h-full max-w-full object-contain" alt="{{ $article->title }}">
              </div>
              <div class="p-6">
                <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">
                  <span class="text-indigo-600">{{ $article->category }}</span>
                  <span>•</span>
                  <span>{{ $article->created_at->format('d M Y') }}</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 leading-snug hover:text-indigo-600 transition-colors">
                  <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                </h3>
                <p class="text-slate-500 text-xs mt-2 line-clamp-3 leading-relaxed">
                  {{ strip_tags($article->content) }}
                </p>
              </div>
            </div>
            <div class="p-6 pt-0">
              <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                Baca Selengkapnya <span>→</span>
              </a>
            </div>
          </article>
        @endforeach
      </div>

      <div class="text-center mt-12">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition-all duration-200 cursor-pointer shadow-lg shadow-slate-900/10">
          Lihat Semua Artikel Blog
        </a>
      </div>
    </div>
  </section>

  <!-- Download APK/EXE Section -->
  <section id="unduh" class="py-20 text-center max-w-7xl mx-auto px-6">
    <div class="max-w-4xl mx-auto bg-white rounded-3xl border border-slate-200/60 p-10 md:p-16 shadow-xl">
      <h2 class="text-3xl font-extrabold text-slate-900">Unduh Aplikasi Both Corner Client</h2>
      <p class="text-slate-500 mt-4 max-w-2xl mx-auto leading-relaxed">
        Unduh versi gratis trial aplikasi photobooth untuk perangkat keras yang Anda gunakan di lapangan.
      </p>
      <div class="mt-8 flex flex-wrap gap-4 justify-center">
        <button onclick="showToast('📥 Mulai mengunduh installer Windows (.exe)...')" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
          💻 Download for Windows PC
        </button>
        <button onclick="showToast('📥 Mulai mengunduh APK Android (.apk)...')" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
          🤖 Download for Android Tablet
        </button>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
      <a href="#" class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold">B</div>
        <span class="text-lg font-bold text-white">Both<span class="text-indigo-400">Corner</span></span>
      </a>
      <div class="flex flex-wrap justify-center gap-8 text-sm">
        <a href="#fitur" class="hover:text-white transition-colors">Fitur</a>
        <a href="#use-cases" class="hover:text-white transition-colors">Solusi</a>
        <a href="#kustomisasi" class="hover:text-white transition-colors">Kustomisasi</a>
        <a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a>
        <a href="{{ route('login') }}" class="hover:text-white transition-colors">Dashboard</a>
      </div>
      <div class="text-xs text-slate-500">
        &copy; 2026 Both Corner Platform. Hak Cipta Dilindungi.
      </div>
    </div>
  </footer>

  <!-- Toast Notification -->
  <div id="toast-notif" class="fixed bottom-8 right-8 bg-white border border-indigo-200 text-slate-900 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-24 opacity-0 transition-all duration-300 z-[100]">
    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">✓</span>
    <span id="toast-message" class="text-sm font-semibold">Pesan sukses</span>
  </div>

  <script>
    function showToast(message) {
      const toast = document.getElementById('toast-notif');
      const msg = document.getElementById('toast-message');
      msg.textContent = message;
      toast.classList.remove('translate-y-24', 'opacity-0');
      toast.classList.add('translate-y-0', 'opacity-100');
      
      setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-24', 'opacity-0');
      }, 4000);
    }
  </script>
</body>
</html>
