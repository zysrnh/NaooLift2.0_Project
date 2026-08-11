<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Dashboard</title>
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

  .hover-invert:hover {
    background-color: #1C1C1C;
    color: #EAE6E0;
  }

  /* Mobile App Navigation Bar Styles */
  @media (max-width: 767px) {
    body {
      padding-bottom: 70px; /* Offset for mobile bottom app bar */
    }
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
<body class="min-h-screen p-2 sm:p-6 lg:p-8 flex justify-center relative">

  <!-- SWISS BRUTALIST BOTTOM-RIGHT TOAST NOTIFICATION CONTAINER -->
  <div id="toast-container" class="fixed bottom-20 right-4 sm:bottom-6 sm:right-6 z-50 flex flex-col gap-2 max-w-[380px] w-full pointer-events-none">
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

  <!-- Main Dashboard Container Outer Wrapper -->
  <div class="w-full max-w-[1280px] border-grid flex flex-col relative bg-canvas shadow-none animate-fade-in">
    
    <!-- TOP APP HEADER BAR -->
    <header class="flex flex-col md:flex-row border-b-grid">
      <!-- Sidebar Brand Header (Mobile & Desktop) -->
      <div class="w-full md:w-64 border-b-grid md:border-b-0 md:border-r-grid bg-charcoal text-canvas p-4 sm:p-5 flex items-center justify-between shrink-0">
        <div>
          <a href="/" class="text-xl font-black uppercase tracking-tighter hover:text-ember transition-colors">
            NAOOLIFT.LOG
          </a>
          <div class="font-mono text-[9px] text-slate uppercase tracking-widest mt-0.5">
            SYS_DASHBOARD v2.0
          </div>
        </div>
        <!-- Mobile App Status Indicator -->
        <span class="md:hidden font-mono text-[10px] bg-light text-charcoal px-2.5 py-1 font-bold uppercase border-grid flex items-center gap-1.5">
          <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
          ONLINE
        </span>
      </div>

      <!-- Desktop Top Status & Action Navigation Bar (Hidden on Mobile for App Simplicity) -->
      <div class="hidden md:flex flex-1 flex-row font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-canvas">
          <span class="font-sans">MODULE_STATUS:</span>
          <span class="text-ember flex items-center gap-1.5 font-bold">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
            OPERATIONAL
          </span>
        </div>
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-light">
          <span class="font-sans">SYS_TIME:</span>
          <span id="dash-timer" class="font-bold">00:00:00</span>
        </div>
        <div class="flex flex-row w-64 font-sans font-bold">
          <a href="/" class="flex-1 p-4 bg-canvas text-charcoal text-center hover:bg-charcoal hover:text-canvas transition-none flex items-center justify-center border-r-grid active:translate-y-1">
            ← LANDING
          </a>
          @if(session('user'))
            <button onclick="openLogoutModal()" class="flex-1 p-4 bg-charcoal text-canvas text-center hover:bg-ember transition-none flex items-center justify-center active:translate-y-1">
              KELUAR [✕]
            </button>
          @else
            <a href="/login" class="flex-1 p-4 bg-ember text-canvas text-center hover:bg-charcoal transition-none flex items-center justify-center active:translate-y-1">
              MASUK →
            </a>
          @endif
        </div>
      </div>
    </header>

    <!-- INNER DASHBOARD BODY (DESKTOP SIDEBAR + MOBILE PWA LAYOUT) -->
    <div class="flex flex-col md:flex-row flex-1 items-stretch">
      
      <!-- DESKTOP SWISS BRUTALIST SIDEBAR (Hidden on Mobile) -->
      <aside class="hidden md:flex w-64 border-r-grid bg-canvas flex-col justify-between shrink-0">
        
        <!-- Sidebar Navigation Menu Links -->
        <div class="flex flex-col font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
          
          <!-- Section 1: Main Modules -->
          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate">
            01 // MENU UTAMA
          </div>
          
          <a href="/dashboard" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold">
            <span>[01] OVERVIEW</span>
            <span class="text-ember">●</span>
          </a>
          
          <a href="#log" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[02] LOG LATIHAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="#stats" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[03] STATISTIK</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="#schedule" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[04] JADWAL</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <!-- Section 2: Settings & System -->
          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate border-t-grid">
            02 // SISTEM & AKUN
          </div>

          <a href="#settings" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[05] PENGATURAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="#status" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[06] MESIN</span>
            <span class="text-ember font-bold">LIVE</span>
          </a>
        </div>

        <!-- Sidebar Anchored Bottom User Info Block -->
        <div class="mt-auto border-t-grid bg-light flex flex-col font-mono text-xs uppercase tracking-widest">
          <div class="p-4 border-b-grid flex flex-col gap-1 bg-canvas">
            <span class="text-[10px] text-slate font-bold">ACTIVE_SESSION:</span>
            <span class="font-bold text-ember truncate">
              @if(session('user')) {{ session('user') }} @else GUEST_SESSION @endif
            </span>
          </div>

          <a href="/" class="p-3.5 border-b-grid bg-light text-charcoal font-bold text-center hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1">
            ← LANDING PAGE
          </a>

          @if(session('user'))
            <button onclick="openLogoutModal()" class="p-3.5 bg-charcoal text-canvas font-bold text-center hover:bg-ember transition-none active:translate-y-1">
              KELUAR [✕]
            </button>
          @else
            <a href="/login" class="p-3.5 bg-ember text-canvas font-bold text-center hover:bg-charcoal transition-none active:translate-y-1">
              MASUK →
            </a>
          @endif
        </div>

      </aside>

      <!-- RIGHT MAIN CONTENT AREA (Clean Mobile App Canvas) -->
      <main class="flex-1 flex flex-col min-w-0 bg-canvas justify-between">
        
        <!-- Mobile Quick Info Strip (App Style) -->
        <div class="md:hidden flex border-b-grid bg-light p-3 justify-between items-center font-mono text-[11px] font-bold text-charcoal uppercase tracking-widest">
          <span>USER: <span class="text-ember">@if(session('user')) {{ session('user') }} @else GUEST @endif</span></span>
          <span id="dash-timer-mobile">00:00:00</span>
        </div>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="p-4 sm:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">

          <!-- Hero Section Header -->
          <div class="border-b-[3px] border-charcoal pb-4 sm:pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
              <span class="font-mono text-[11px] sm:text-xs font-bold uppercase tracking-widest text-ember">
                01 // OVERVIEW_ANALYTICS
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                DASBOR LATIHAN
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Modul pengawasan dan log ringkasan statistik latihan harian Anda.
              </p>
            </div>
            <div class="font-mono text-[10px] sm:text-xs font-bold uppercase tracking-widest text-charcoal bg-light p-2.5 sm:p-3 border-grid">
              DOC_REF: DASH-2026-v2.0
            </div>
          </div>

          <!-- Metric Cards Grid (App Viewport Adaptive) -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <!-- Metric Card 1 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL SESI
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                0
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                SESI: 0
              </div>
            </div>

            <!-- Metric Card 2 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL BEBAN
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                0 <span class="text-sm sm:text-lg">KG</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                VOLUMETRIK
              </div>
            </div>

            <!-- Metric Card 3 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                REKOR MAX
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-slate">
                -
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                LIFT: UNSET
              </div>
            </div>

            <!-- Metric Card 4 -->
            <div class="border-grid bg-light p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-charcoal uppercase tracking-widest">
                STATUS MESIN
              </span>
              <div class="font-mono text-xl sm:text-3xl font-bold text-ember flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 bg-ember animate-pulse inline-block"></span>
                STANDBY
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-charcoal uppercase tracking-wider border-t-grid pt-2">
                SYSTEM_READY
              </div>
            </div>
          </div>

          <!-- EMPTY STATE PLACEHOLDER BOX -->
          <div class="border-grid bg-light p-6 sm:p-16 flex flex-col items-center justify-center text-center gap-3 sm:gap-4 my-2 min-h-[240px]">
            <div class="w-10 h-10 sm:w-12 sm:h-12 border-grid bg-charcoal text-canvas flex items-center justify-center font-mono font-black text-lg sm:text-xl mb-1">
              !
            </div>
            <h3 class="text-xl sm:text-3xl font-black uppercase tracking-tighter text-charcoal">
              DATA LATIHAN MASIH KOSONG
            </h3>
            <p class="text-xs sm:text-sm font-semibold text-slate max-w-md">
              Belum ada riwayat sesi latihan yang dicatat pada akun ini. Modul input dan grafik perkembangan akan tersedia di sini.
            </p>
            <div class="font-mono text-[10px] sm:text-xs font-bold uppercase tracking-widest text-ember border-grid bg-canvas px-3 py-1.5 mt-1">
              STATUS: WAITING_FOR_LOG_INPUT
            </div>
          </div>

        </div>

        <!-- FOOTER -->
        <footer class="mt-auto border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
          <div>NAOOLIFT SYSTEM © 2026</div>
          <div class="text-slate">MODULE: MOBILE_APP_NATIVE_PWA</div>
        </footer>

      </main>

    </div>

  </div>

  <!-- NATIVE MOBILE APP PWA BOTTOM NAVIGATION BAR (Visible ONLY on Mobile < 768px) -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-charcoal text-canvas border-t-[3px] border-charcoal grid grid-cols-4 font-mono text-[10px] font-bold uppercase tracking-widest text-center shadow-none">
    
    <!-- Tab 1: Dashboard Home -->
    <a href="/dashboard" class="py-3 bg-ember text-canvas border-r-grid flex flex-col items-center justify-center gap-0.5">
      <span class="text-xs font-black">■</span>
      <span>DASBOR</span>
    </a>

    <!-- Tab 2: Logs -->
    <a href="#log" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">≡</span>
      <span>LOG</span>
    </a>

    <!-- Tab 3: Stats -->
    <a href="#stats" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">▲</span>
      <span>REKOR</span>
    </a>

    <!-- Tab 4: Landing / Exit -->
    <a href="/" class="py-3 text-canvas hover:bg-light hover:text-charcoal flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">←</span>
      <span>LANDING</span>
    </a>

  </nav>

  <!-- SWISS BRUTALIST LOGOUT CONFIRMATION MODAL -->
  <div id="logout-modal" class="fixed inset-0 z-[100] bg-charcoal/80 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-[440px] border-grid bg-canvas p-6 sm:p-8 flex flex-col gap-4 shadow-none relative animate-fade-in">
      <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
        <h3 class="font-black text-xl uppercase tracking-tighter text-charcoal">
          KONFIRMASI KELUAR
        </h3>
        <span class="font-mono text-xs font-bold text-ember">SYS_LOGOUT</span>
      </div>

      <p class="text-xs sm:text-sm font-semibold text-charcoal leading-relaxed">
        Apakah Anda yakin ingin mengakhiri sesi latihan aktif saat ini? Sesi akan kembali ke mode tamu.
      </p>

      <div class="flex gap-3 pt-2">
        <button onclick="closeLogoutModal()" class="flex-1 border-[3px] border-charcoal bg-light text-charcoal font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1">
          BATAL
        </button>
        <a href="/logout" class="flex-1 border-[3px] border-charcoal bg-ember text-canvas text-center font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal transition-none active:translate-y-1 flex items-center justify-center">
          YA, KELUAR →
        </a>
      </div>
    </div>
  </div>

  <script>
    // 1. Live Clock Counter (Desktop & Mobile Sync)
    function updateTime() {
      const now = new Date();
      const hrs = String(now.getHours()).padStart(2, '0');
      const mins = String(now.getMinutes()).padStart(2, '0');
      const secs = String(now.getSeconds()).padStart(2, '0');
      const formatted = `${hrs}:${mins}:${secs}`;

      const dt = document.getElementById('dash-timer');
      const dtm = document.getElementById('dash-timer-mobile');
      if (dt) dt.textContent = formatted;
      if (dtm) dtm.textContent = formatted;
    }
    updateTime();
    setInterval(updateTime, 1000);

    // 2. Logout Modal Functions
    function openLogoutModal() {
      const modal = document.getElementById('logout-modal');
      if (modal) modal.classList.remove('hidden');
    }

    function closeLogoutModal() {
      const modal = document.getElementById('logout-modal');
      if (modal) modal.classList.add('hidden');
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeLogoutModal();
    });

    // 3. Toast Dismiss
    function dismissToast() {
      const toast = document.getElementById('toast-msg');
      if (toast) {
        toast.style.transition = 'transform 0.25s ease-out, opacity 0.2s ease-out';
        toast.style.transform = 'translateY(120%)';
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
