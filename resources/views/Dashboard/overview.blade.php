<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Dashboard Overview</title>
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

  @media (max-width: 767px) {
    body {
      padding-bottom: 70px;
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
    
    <!-- TOP UNIFIED APP HEADER BAR -->
    <header class="flex flex-col md:flex-row border-b-grid">
      <!-- Sidebar Brand Header -->
      <div class="w-full md:w-64 border-b-grid md:border-b-0 md:border-r-grid bg-charcoal text-canvas p-4 sm:p-5 flex items-center justify-between shrink-0">
        <div>
          <a href="/" class="text-xl font-black uppercase tracking-tighter hover:text-ember transition-colors">
            NAOOLIFT.LOG
          </a>
          <div class="font-mono text-[9px] text-slate uppercase tracking-widest mt-0.5">
            SYS_OVERVIEW v2.0
          </div>
        </div>
        <span class="md:hidden font-mono text-[10px] bg-light text-charcoal px-2.5 py-1 font-bold uppercase border-grid flex items-center gap-1.5">
          <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
          ONLINE
        </span>
      </div>

      <!-- Desktop Top Status & Action Navigation Bar -->
      <div class="hidden md:flex flex-1 flex-row font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-canvas">
          <span class="font-sans">MODUL UTAMA:</span>
          <span class="text-ember flex items-center gap-1.5 font-bold">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
            RINGKASAN_DASBOR
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

    <!-- INNER DASHBOARD BODY (2 COLUMNS) -->
    <div class="flex flex-col md:flex-row flex-1 items-stretch">
      
      <!-- DESKTOP SWISS BRUTALIST SIDEBAR -->
      <aside class="hidden md:flex w-64 border-r-grid bg-canvas flex-col justify-between shrink-0">
        
        <!-- Sidebar Navigation Menu Links -->
        <div class="flex flex-col font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
          
          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate">
            01 // MENU UTAMA
          </div>
          
          <a href="/dashboard" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold">
            <span>[01] OVERVIEW</span>
            <span class="text-ember">●</span>
          </a>
          
          <a href="/dashboard/schedule" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[02] JADWAL LATIHAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="#log" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[03] LOG LATIHAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="#stats" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[04] STATISTIK</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate border-t-grid">
            02 // SISTEM & AKUN
          </div>

          <a href="#settings" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[05] PENGATURAN</span>
            <span class="text-slate font-normal">→</span>
          </a>
        </div>

        <!-- Sidebar Anchored Bottom User Info Block -->
        <div class="mt-auto border-t-grid bg-light flex flex-col font-mono text-xs uppercase tracking-widest">
          <div class="p-4 flex flex-col gap-1 bg-canvas">
            <span class="text-[10px] text-slate font-bold">ACTIVE_SESSION:</span>
            <span class="font-bold text-ember truncate">
              @if(session('user')) {{ session('user') }} @else GUEST_SESSION @endif
            </span>
          </div>
        </div>

      </aside>

      <!-- RIGHT MAIN CONTENT AREA -->
      <main class="flex-1 flex flex-col min-w-0 bg-canvas">
        
        <!-- Mobile Quick Info Strip -->
        <div class="md:hidden flex border-b-grid bg-light p-3 justify-between items-center font-mono text-[11px] font-bold text-charcoal uppercase tracking-widest">
          <span>USER: <span class="text-ember">@if(session('user')) {{ session('user') }} @else GUEST @endif</span></span>
          <span id="dash-timer-mobile">00:00:00</span>
        </div>

        <!-- MAIN OVERVIEW CONTENT WRAPPER -->
        <div class="p-4 sm:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">

          <!-- Hero Section Header -->
          <div class="border-b-[3px] border-charcoal pb-4 sm:pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
              <span class="font-mono text-[11px] sm:text-xs font-bold uppercase tracking-widest text-ember">
                01 // DASHBOARD_OVERVIEW_PANEL
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                RINGKASAN DASBOR
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Selamat datang di sistem NaooLift. Pantau jadwal latihan harian dan status modul Anda.
              </p>
            </div>
            <div class="font-mono text-[10px] sm:text-xs font-bold uppercase tracking-widest text-charcoal bg-light p-2.5 sm:p-3 border-grid">
              DOC_REF: OVERVIEW-2026
            </div>
          </div>

          <!-- TODAY'S ROUTINE HIGHLIGHT CARD -->
          <div class="border-grid bg-canvas p-6 sm:p-8 flex flex-col gap-4">
            <div class="flex justify-between items-center border-b-grid pb-3">
              <span class="font-mono text-xs font-bold uppercase tracking-widest text-ember flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-ember animate-pulse inline-block"></span>
                LATIHAN HARI INI ({{ $todayName }})
              </span>
              <a href="/dashboard/schedule" class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal hover:text-ember transition-colors">
                KELOLA JADWAL →
              </a>
            </div>

            @if($todaySchedule)
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-2">
                <div>
                  <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-charcoal">
                    {{ $todaySchedule->title }}
                  </h3>
                  @if($todaySchedule->focus_target)
                    <div class="font-mono text-xs font-bold text-ember uppercase tracking-wider mt-1">
                      TARGET OTOT: {{ $todaySchedule->focus_target }}
                    </div>
                  @endif
                  @if($todaySchedule->notes)
                    <p class="text-xs font-semibold text-slate mt-2 max-w-lg">
                      {{ $todaySchedule->notes }}
                    </p>
                  @endif
                </div>

                @if($todaySchedule->is_rest)
                  <span class="font-mono text-xs bg-slate text-canvas px-4 py-2 font-bold uppercase border-grid">
                    REST DAY / ISTIRAHAT
                  </span>
                @else
                  <span class="font-mono text-xs bg-ember text-canvas px-4 py-2 font-bold uppercase border-grid">
                    SESI WORKOUT AKTIF
                  </span>
                @endif
              </div>
            @else
              <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-4">
                <div>
                  <h3 class="text-xl font-black uppercase tracking-tight text-charcoal">
                    BELUM ADA JADWAL LATIHAN UNTUK HARI {{ $todayName }}
                  </h3>
                  <p class="text-xs font-semibold text-slate mt-1">
                    Tambahkan jadwal latihan hari ini agar sesi latihan Anda tercatat teratur.
                  </p>
                </div>
                <a href="/dashboard/schedule" class="border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-4 py-3 hover:bg-charcoal transition-none active:translate-y-1">
                  + ATUR JADWAL →
                </a>
              </div>
            @endif
          </div>

          <!-- Summary Analytics Metric Cards -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <!-- Metric Card 1 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                JADWAL LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                {{ $totalWorkoutDays }} <span class="text-sm sm:text-lg text-slate">HARI</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                WORKOUT ROUTINE
              </div>
            </div>

            <!-- Metric Card 2 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                HARI ISTIRAHAT
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                {{ $totalRestDays }} <span class="text-sm sm:text-lg text-slate">HARI</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                RECOVERY DAYS
              </div>
            </div>

            <!-- Metric Card 3 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                CAKUPAN JADWAL
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                {{ $totalDaysSet }} <span class="text-sm sm:text-lg text-slate">/ 7</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                WEEKLY COVERAGE
              </div>
            </div>

            <!-- Metric Card 4 -->
            <div class="border-grid bg-light p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-charcoal uppercase tracking-widest">
                STATUS SISTEM
              </span>
              <div class="font-mono text-xl sm:text-3xl font-bold text-ember flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 bg-ember animate-pulse inline-block"></span>
                READY
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-charcoal uppercase tracking-wider border-t-grid pt-2">
                DATABASE_SYNCED
              </div>
            </div>
          </div>

          <!-- ACTIVE MODULES QUICK LINK CARDS -->
          <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
              <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tighter text-charcoal">
                MODUL SISTEM AKTIF
              </h3>
              <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">
                SYSTEM MODULES
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Module 1: Schedule -->
              <a href="/dashboard/schedule" class="border-grid bg-canvas p-5 flex flex-col justify-between gap-4 hover:border-ember transition-colors group">
                <div class="flex justify-between items-start">
                  <div>
                    <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest">MODUL 01</span>
                    <h4 class="text-2xl font-black uppercase tracking-tight text-charcoal group-hover:text-ember transition-colors mt-0.5">
                      JADWAL LATIHAN MINGGUAN
                    </h4>
                  </div>
                  <span class="font-mono text-xs bg-ember text-canvas px-2.5 py-1 font-bold uppercase">AKTIF</span>
                </div>
                <p class="text-xs font-semibold text-slate">
                  Atur pembagian hari latihan (Senin - Minggu), target otot, serta catatan sesi latihan secara dinamis.
                </p>
                <div class="border-t-grid pt-3 font-mono text-xs font-bold text-charcoal uppercase tracking-widest flex items-center justify-between">
                  <span>BUKA MODUL JADWAL</span>
                  <span>→</span>
                </div>
              </a>

              <!-- Module 2: Workout Log -->
              <div class="border-grid bg-light p-5 flex flex-col justify-between gap-4 opacity-75">
                <div class="flex justify-between items-start">
                  <div>
                    <span class="font-mono text-[10px] font-bold text-slate uppercase tracking-widest">MODUL 02</span>
                    <h4 class="text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                      LOG SESI LATIHAN
                    </h4>
                  </div>
                  <span class="font-mono text-xs bg-slate text-canvas px-2.5 py-1 font-bold uppercase">SEGERA</span>
                </div>
                <p class="text-xs font-semibold text-slate">
                  Pencatatan real-time set, reps, dan beban berat latihan harian Anda.
                </p>
                <div class="border-t-grid pt-3 font-mono text-xs font-bold text-slate uppercase tracking-widest flex items-center justify-between">
                  <span>TAHAP PENGEMBANGAN</span>
                  <span>🔒</span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </main>

    </div>

    <!-- FULL-WIDTH SWISS BRUTALIST FOOTER BAR -->
    <footer class="border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
      <div>NAOOLIFT SYSTEM © 2026</div>
      <div class="text-slate">MODULE: OVERVIEW_PANEL</div>
    </footer>

  </div>

  <!-- NATIVE MOBILE APP PWA BOTTOM NAVIGATION BAR -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-charcoal text-canvas border-t-[3px] border-charcoal grid grid-cols-4 font-mono text-[10px] font-bold uppercase tracking-widest text-center shadow-none">
    <a href="/dashboard" class="py-3 bg-ember text-canvas border-r-grid flex flex-col items-center justify-center gap-0.5">
      <span class="text-xs font-black">■</span>
      <span>OVERVIEW</span>
    </a>
    <a href="/dashboard/schedule" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">≡</span>
      <span>JADWAL</span>
    </a>
    <a href="#stats" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">▲</span>
      <span>REKOR</span>
    </a>
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
