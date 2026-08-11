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

  .hover-invert:hover {
    background-color: #1C1C1C;
    color: #EAE6E0;
  }
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
<body class="min-h-screen p-3 sm:p-8 flex items-center justify-center">

  <!-- Login Main Container -->
  <div class="w-full max-w-[560px] border-grid bg-canvas flex flex-col">
    
    <!-- Top Header Bar -->
    <header class="bg-charcoal text-canvas p-4 sm:p-6 border-b-grid flex justify-between items-center">
      <div>
        <a href="/" class="text-xl sm:text-2xl font-black uppercase tracking-tighter hover:text-ember transition-colors">
          NAOOLIFT.LOG
        </a>
        <div class="font-mono text-[10px] text-slate uppercase tracking-widest mt-0.5">
          MODULE: AUTH_LOGIN
        </div>
      </div>
      <a href="/" class="font-mono text-xs bg-ember text-canvas px-3 py-1.5 font-bold uppercase hover:bg-canvas hover:text-charcoal transition-none">
        ← LANDING
      </a>
    </header>

    @if(session('success'))
      <!-- Success Flash Alert from Register Redirect -->
      <div class="bg-ember text-canvas p-4 border-b-grid font-mono text-xs font-bold uppercase tracking-widest flex items-center justify-between">
        <span>✓ {{ session('success') }}</span>
      </div>
    @endif

    <!-- Info Status Banner -->
    <div class="bg-light p-3 sm:p-4 border-b-grid font-mono text-xs font-bold uppercase tracking-widest text-charcoal flex justify-between items-center">
      <span>DOC_REF: LOG-2026</span>
      <span class="text-ember flex items-center gap-1.5">
        <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
        AUTHENTICATION_ACTIVE
      </span>
    </div>

    <!-- Main Login Form Block -->
    <div class="p-6 sm:p-10 flex flex-col">
      <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tighter text-charcoal mb-2">
        MASUK KE AKUN
      </h2>
      <p class="text-xs sm:text-sm font-semibold text-slate mb-8 pb-4 border-b-[3px] border-charcoal">
        Masukkan kredensial akun Anda untuk mengakses sesi log latihan lengkap.
      </p>

      <form action="/login" method="POST" class="space-y-6">
        @csrf

        <!-- Field 1: Alamat Email -->
        <div class="flex flex-col gap-2">
          <label class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
            01 / ALAMAT EMAIL
          </label>
          <input 
            type="email" 
            name="email" 
            required 
            placeholder="USER@NAOOLIFT.LOG" 
            class="w-full bg-light border-grid p-3.5 font-mono text-sm text-charcoal font-bold uppercase placeholder-slate focus:bg-canvas focus:outline-none"
          >
        </div>

        <!-- Field 2: Kata Sandi -->
        <div class="flex flex-col gap-2">
          <label class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
            02 / KATA SANDI
          </label>
          <input 
            type="password" 
            name="password" 
            required 
            placeholder="••••••••" 
            class="w-full bg-light border-grid p-3.5 font-mono text-sm text-charcoal font-bold placeholder-slate focus:bg-canvas focus:outline-none"
          >
        </div>

        <!-- Submit CTA Button -->
        <button 
          type="submit" 
          class="w-full border-[3px] border-charcoal bg-ember text-canvas text-center font-black uppercase tracking-widest py-4 text-sm sm:text-base hover:bg-charcoal transition-none active:translate-y-1 mt-4"
        >
          MASUK SEKARANG →
        </button>
      </form>
    </div>

    <!-- Bottom Switch Auth Footer Link -->
    <div class="border-t-grid bg-light p-4 text-center">
      <a href="/register" class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal hover:text-ember transition-colors">
        BELUM PUNYA AKUN? DAFTAR DI SINI →
      </a>
    </div>

  </div>

</body>
</html>
