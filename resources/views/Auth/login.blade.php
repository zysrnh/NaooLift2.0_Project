<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Login Akun</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=Space+Mono:wght@400;700&display=swap');

  body {
    background-color: #EAE6E0; /* Canvas */
    color: #1C1C1C; /* Charcoal */
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
  }

  .font-mono {
    font-family: 'Space Mono', monospace;
  }

  /* Reset all rounded corners and shadows universally per rule.md */
  * {
    border-radius: 0 !important;
    box-shadow: none !important;
  }

  /* Utility lines for the brutal grid look */
  .border-grid {
    border: 3px solid #1C1C1C;
  }
  .border-b-grid {
    border-bottom: 3px solid #1C1C1C;
  }
  .border-r-grid {
    border-right: 3px solid #1C1C1C;
  }
  .border-t-grid {
    border-top: 3px solid #1C1C1C;
  }
  .border-l-grid {
    border-left: 3px solid #1C1C1C;
  }

  /* Entrance Animation */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(16px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .animate-fade-in {
    animation: fadeInUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }

  /* Toast Slide-Up Animation */
  @keyframes toastSlideUp {
    from {
      transform: translateY(120%);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  .animate-toast-slide {
    animation: toastSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }

  .hover-invert:hover {
    background-color: #1C1C1C;
    color: #EAE6E0;
  }

  ::-webkit-scrollbar { display: none; }
</style>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          canvas: '#EAE6E0', 
          charcoal: '#1C1C1C',
          ember: '#9A4A2E', 
          slate: '#535366',
          light: '#D8D3CA',
        }
      }
    }
  }
</script>
</head>
<body class="min-h-screen py-3 px-2 sm:px-6 flex items-center justify-center relative">

  <!-- SWISS BRUTALIST BOTTOM-RIGHT TOAST NOTIFICATION CONTAINER -->
  <div id="toast-container" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 flex flex-col gap-2 max-w-[380px] w-full pointer-events-none">
    @if(session('error') || $errors->any())
      <div id="toast-msg" class="pointer-events-auto border-[3px] border-charcoal bg-charcoal text-canvas p-3.5 font-mono text-xs font-bold uppercase tracking-widest flex items-start justify-between gap-3 animate-toast-slide border-l-[8px] border-l-ember">
        <div class="flex items-start gap-2">
          <span class="text-ember font-black">✕</span>
          <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
        <button onclick="dismissToast()" class="text-canvas hover:text-ember font-mono font-bold text-xs">[✕]</button>
      </div>
    @elseif(session('success'))
      <div id="toast-msg" class="pointer-events-auto border-[3px] border-charcoal bg-ember text-canvas p-3.5 font-mono text-xs font-bold uppercase tracking-widest flex items-start justify-between gap-3 animate-toast-slide">
        <div class="flex items-start gap-2">
          <span class="font-black">✓</span>
          <span>{{ session('success') }}</span>
        </div>
        <button onclick="dismissToast()" class="text-canvas hover:text-charcoal font-mono font-bold text-xs">[✕]</button>
      </div>
    @elseif(session('info'))
      <div id="toast-msg" class="pointer-events-auto border-[3px] border-charcoal bg-light text-charcoal p-3.5 font-mono text-xs font-bold uppercase tracking-widest flex items-start justify-between gap-3 animate-toast-slide border-l-[8px] border-l-charcoal">
        <div class="flex items-start gap-2">
          <span class="font-black">ℹ</span>
          <span>{{ session('info') }}</span>
        </div>
        <button onclick="dismissToast()" class="text-charcoal hover:text-ember font-mono font-bold text-xs">[✕]</button>
      </div>
    @endif
  </div>

  <!-- Login Main Container (Compact One Screen Fit) -->
  <div class="w-full max-w-[500px] border-grid bg-canvas flex flex-col animate-fade-in my-auto">
    
    <!-- Top Header Bar -->
    <header class="bg-charcoal text-canvas p-3 sm:p-4 border-b-grid flex justify-between items-center">
      <a href="/" class="text-lg sm:text-xl font-black uppercase tracking-tighter hover:text-ember transition-colors">
        NAOOLIFT.LOG
      </a>
      <a href="/" class="font-mono text-[11px] bg-ember text-canvas px-2.5 py-1 font-bold uppercase hover:bg-canvas hover:text-charcoal transition-none">
        ← LANDING
      </a>
    </header>

    <!-- Main Login Form Block -->
    <div class="p-4 sm:p-6 flex flex-col">
      <h2 class="text-2xl sm:text-3xl font-black uppercase tracking-tighter text-charcoal mb-1">
        MASUK KE AKUN
      </h2>
      <p class="text-xs font-semibold text-slate mb-4 pb-3 border-b-[3px] border-charcoal">
        Masukkan kredensial untuk mengakses sesi log latihan lengkap.
      </p>

      <form action="/login" method="POST" class="space-y-4">
        @csrf

        <!-- Field 1: Alamat Email -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            01 / ALAMAT EMAIL
          </label>
          <input 
            type="email" 
            name="email" 
            required 
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold uppercase focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
          >
        </div>

        <!-- Field 2: Kata Sandi -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            02 / KATA SANDI
          </label>
          <input 
            type="password" 
            name="password" 
            required 
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
          >
        </div>

        <!-- Submit CTA Button -->
        <button 
          type="submit" 
          class="w-full border-[3px] border-charcoal bg-ember text-canvas text-center font-black uppercase tracking-widest py-3 text-sm hover:bg-charcoal transition-none active:translate-y-1 mt-2"
        >
          MASUK SEKARANG →
        </button>
      </form>
    </div>

    <!-- Bottom Switch Auth Footer Link -->
    <div class="border-t-grid bg-light p-3 text-center">
      <a href="/register" class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal hover:text-ember transition-colors">
        BELUM PUNYA AKUN? DAFTAR DI SINI →
      </a>
    </div>

  </div>

  <script>
    function dismissToast() {
      const toast = document.getElementById('toast-msg');
      if (toast) {
        toast.style.transition = 'transform 0.25s ease-out, opacity 0.2s ease-out';
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 250);
      }
    }
    setTimeout(() => {
      dismissToast();
    }, 4500);
  </script>
</body>
</html>
