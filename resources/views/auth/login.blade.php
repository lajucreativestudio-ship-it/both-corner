<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Both Corner Cloud</title>
  
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
    .fade-slide {
      opacity: 0;
      transition: opacity 0.8s ease-in-out;
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .fade-slide.active {
      opacity: 1;
      position: relative;
    }
  </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 select-none">

  <!-- Main Container Card mirroring reference layout -->
  <div class="w-full max-w-5xl bg-white rounded-3xl border border-slate-200/60 shadow-2xl overflow-hidden flex flex-col md:flex-row aspect-[1.6/1]">
    
    <!-- Left Column: Form -->
    <div class="w-full md:w-[50%] p-8 sm:p-10 md:p-12 flex flex-col justify-between">
      
      <!-- Brand Logo -->
      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-indigo-500 to-violet-600 flex items-center justify-center text-white font-extrabold text-xs">B</div>
        <span class="text-base font-bold text-slate-900 tracking-tight">Both<span class="text-indigo-600">Corner</span></span>
      </div>

      <!-- Welcome Text -->
      <div class="mt-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat datang kembali</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
      </div>

      <!-- Social Login -->
      <button onclick="showToast('🔑 Menghubungkan dengan Google Auth...')" class="w-full mt-6 py-2.5 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-xs sm:text-sm font-bold text-slate-700 flex items-center justify-center gap-2 transition-colors cursor-pointer bg-white">
        <!-- Google SVG Icon -->
        <svg class="w-4 h-4" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <g transform="matrix(1, 0, 0, 1, 0, 0)">
            <path d="M21.35,11.1H12v2.7h5.38C17,15.28,14.86,16.5,12,16.5c-3.03,0-5.6-2.05-6.52-4.82a6.83,6.83,0,0,1,0-3.36c.92-2.77,3.49-4.82,6.52-4.82a6.3,6.3,0,0,1,4.45,1.74L18.4,3.2A9,9,0,0,0,12,1.5,9.65,9.65,0,0,0,3,7.2a9.6,9.6,0,0,0,0,9.6,9.65,9.65,0,0,0,9,5.7c5.4,0,9-3.75,9-9.15A7.44,7.44,0,0,0,21.35,11.1Z" fill="#ea4335" />
            <path d="M12,22.5A9.65,9.65,0,0,0,21,16.8H12Z" fill="#34a853" />
            <path d="M12,1.5A9.65,9.65,0,0,0,3,7.2L5.48,9.08C6.4,6.31,8.97,4.26,12,4.26A6.3,6.3,0,0,1,16.45,6L18.4,4.05A9,9,0,0,0,12,1.5Z" fill="#fbbc05" />
            <path d="M21.35,11.1H12v2.7h5.38C17,15.28,14.86,16.5,12,16.5Z" fill="#4285f4" />
          </g>
        </svg>
        Lanjutkan dengan Google
      </button>

      <!-- Divider -->
      <div class="relative flex py-4 items-center">
        <div class="flex-grow border-t border-slate-200/70"></div>
        <span class="flex-shrink mx-4 text-[10px] sm:text-xs text-slate-400 font-medium bg-white px-2">atau lanjutkan dengan email</span>
        <div class="flex-grow border-t border-slate-200/70"></div>
      </div>

      <!-- Form Inputs -->
      <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

        @if ($errors->any())
          <div class="p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-semibold">
            {{ $errors->first('email') }}
          </div>
        @endif

        <div class="flex flex-col gap-1.5">
          <label for="email" class="text-xs font-bold text-slate-700">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email', 'lajucreativestudio@gmail.com') }}" required placeholder="Masukkan email kamu" class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white transition-colors">
        </div>

        <div class="flex flex-col gap-1.5">
          <label for="password" class="text-xs font-bold text-slate-700">Password</label>
          <input type="password" id="password" name="password" value="password123" required placeholder="Masukkan password Anda" class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white transition-colors">
        </div>

        <!-- Remember me & Forgot Password -->
        <div class="flex items-center justify-between text-xs sm:text-sm">
          <label class="flex items-center gap-2 select-none cursor-pointer">
            <input type="checkbox" checked class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
            <span class="text-xs font-semibold text-slate-600">Ingat saya</span>
          </label>
          <a href="#" onclick="showToast('🔑 Silakan hubungi admin untuk reset password.')" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">Lupa password?</a>
        </div>

        <!-- Captcha Row matching reference image -->
        <div class="p-3 bg-slate-50 border border-slate-200/60 rounded-2xl flex flex-col sm:flex-row items-center gap-3">
          <!-- Captcha Mock Image -->
          <div class="w-full sm:w-[50%] h-14 bg-white rounded-lg overflow-hidden border border-slate-200/50 flex items-center justify-center p-1 select-none">
            <img src="{{ asset('captcha_mock.png') }}" class="h-full w-full object-contain" alt="Captcha Image">
          </div>
          <!-- Captcha Input and Reload Button -->
          <div class="w-full sm:w-[50%] flex items-center gap-1.5">
            <input type="text" value="D4R9BK" placeholder="Enter captcha" class="flex-1 min-w-0 px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:border-indigo-500 outline-none">
            <button type="button" onclick="showToast('🔄 Memuat ulang captcha baru...')" class="w-9 h-9 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center font-bold text-lg cursor-pointer shrink-0">
              🔄
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3 rounded-xl text-xs sm:text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/25 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer mt-4">
          Masuk
        </button>
      </form>

      <!-- Sign Up Footer -->
      <div class="text-center mt-6 text-xs sm:text-sm text-slate-500">
        Don't have an account? <a href="#" onclick="showToast('🎟 Silakan daftar paket premium di dashboard.')" class="text-indigo-600 font-bold hover:underline">Daftar</a>
      </div>

    </div>

    <!-- Right Column: Slider -->
    <div class="hidden md:w-[50%] md:flex bg-slate-50 border-l border-slate-200/50 flex-col justify-center p-10 relative overflow-hidden select-none">
      
      <!-- Slide 1 -->
      <div class="fade-slide active" id="slide-1">
        <div class="w-64 h-64 flex items-center justify-center mb-6">
          <img src="{{ asset('login_slide_1.png') }}" class="max-h-full max-w-full object-contain drop-shadow-xl" alt="Photo Printer 3D Illustration">
        </div>
        <h3 class="text-lg font-bold text-slate-900 mt-4 text-center">Premium Photo Quality</h3>
        <p class="text-xs text-slate-500 mt-2 text-center max-w-xs leading-relaxed">High-resolution prints with professional-grade materials.</p>
      </div>

      <!-- Slide 2 -->
      <div class="fade-slide" id="slide-2">
        <div class="w-64 h-64 flex items-center justify-center mb-6">
          <img src="{{ asset('login_slide_2.png') }}" class="max-h-full max-w-full object-contain drop-shadow-xl" alt="DSLR Camera 3D Illustration">
        </div>
        <h3 class="text-lg font-bold text-slate-900 mt-4 text-center">Easy Camera Integration</h3>
        <p class="text-xs text-slate-500 mt-2 text-center max-w-xs leading-relaxed">Seamless connection to Canon, Nikon, and Sony DSLR/Mirrorless cameras via USB.</p>
      </div>

      <!-- Slide Indicators -->
      <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-2">
        <div class="w-2 h-2 rounded-full bg-indigo-600 transition-colors" id="dot-1"></div>
        <div class="w-2 h-2 rounded-full bg-slate-300 transition-colors" id="dot-2"></div>
      </div>

    </div>

  </div>

  <!-- Toast Notification -->
  <div id="toast-notif" class="fixed bottom-8 right-8 bg-white border border-indigo-200 text-slate-900 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-24 opacity-0 transition-all duration-300 z-[100]">
    <span id="toast-icon" class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">✓</span>
    <span id="toast-message" class="text-sm font-semibold">Pesan sukses</span>
  </div>

  <!-- Slider & Toast JS -->
  <script>
    // Slideshow logic
    let currentSlide = 1;
    const totalSlides = 2;

    setInterval(() => {
      const activeSlide = document.getElementById(`slide-${currentSlide}`);
      const activeDot = document.getElementById(`dot-${currentSlide}`);
      
      activeSlide.classList.remove('active');
      activeDot.classList.remove('bg-indigo-600');
      activeDot.classList.add('bg-slate-300');

      currentSlide = currentSlide === totalSlides ? 1 : currentSlide + 1;

      const nextSlide = document.getElementById(`slide-${currentSlide}`);
      const nextDot = document.getElementById(`dot-${currentSlide}`);
      
      nextSlide.classList.add('active');
      nextDot.classList.remove('bg-slate-300');
      nextDot.classList.add('bg-indigo-600');
    }, 4500);

    // Toast notification
    function showToast(message, isSuccess = true) {
      const toast = document.getElementById('toast-notif');
      const msg = document.getElementById('toast-message');
      const icon = document.getElementById('toast-icon');
      msg.textContent = message;

      if (isSuccess) {
        icon.textContent = '✓';
        icon.className = "w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold";
      } else {
        icon.textContent = '✖';
        icon.className = "w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold";
      }

      toast.classList.remove('translate-y-24', 'opacity-0');
      toast.classList.add('translate-y-0', 'opacity-100');
      
      setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-24', 'opacity-0');
      }, 3500);
    }
  </script>
</body>
</html>
