<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Ringkasan Statistik Latihan</title>
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

  .print-only-header, .print-only-table {
    display: none;
  }

  /* SWISS BRUTALIST A4 PRINT & PDF OPTIMIZATION STYLESHEET */
  @page {
    size: A4 portrait;
    margin: 8mm;
  }

  @media print {
    body {
      background-color: #FFFFFF !important;
      padding: 0 !important;
      color: #000000 !important;
    }
    header, aside, nav, #toast-container, button, form, .no-print, .hero-header, .action-bar {
      display: none !important;
    }
    .print-only-header, .print-only-table {
      display: block !important;
    }
    .w-full.max-w-\[1280px\] {
      max-width: 100% !important;
      border: 2px solid #000000 !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    .border-grid, .border-b-grid, .border-r-grid, .border-t-grid, .border-l-grid {
      border-color: #000000 !important;
    }
    footer {
      border-top: 2px solid #000000 !important;
      padding: 8px !important;
      font-size: 10px !important;
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
      <div class="w-full md:w-64 border-b-grid md:border-b-0 md:border-r-grid bg-charcoal text-canvas p-4 sm:p-5 flex items-center justify-between shrink-0">
        <div>
          <a href="/" class="text-xl font-black uppercase tracking-tighter hover:text-ember transition-colors">
            NAOOLIFT.LOG
          </a>
          <div class="font-mono text-[9px] text-slate uppercase tracking-widest mt-0.5">
            SYS_STATISTICS v2.0
          </div>
        </div>
        <span class="md:hidden font-mono text-[10px] bg-light text-charcoal px-2.5 py-1 font-bold uppercase border-grid flex items-center gap-1.5">
          <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
          ONLINE
        </span>
      </div>

      <div class="hidden md:flex flex-1 flex-row font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-canvas">
          <span class="font-sans">MODUL UTAMA:</span>
          <span class="text-ember flex items-center gap-1.5 font-bold">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
            STATISTIK_RINGKASAN
          </span>
        </div>
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-light">
          <span class="font-sans">HARI INI ({{ $todayName }}):</span>
          <span class="font-bold text-ember font-mono flex items-center gap-1.5 truncate max-w-[210px]" title="{{ $todayRoutineTitle }}">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block shrink-0"></span>
            {{ $todayRoutineTitle }}
          </span>
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

    <!-- INNER DASHBOARD BODY -->
    <div class="flex flex-col md:flex-row flex-1 items-stretch">
      
      <!-- DESKTOP SWISS BRUTALIST SIDEBAR -->
      <aside class="hidden md:flex w-64 border-r-grid bg-canvas flex-col justify-between shrink-0">
        <div class="flex flex-col font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
          
          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate">
            01 // MENU UTAMA
          </div>
          
          <a href="/dashboard" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[01] OVERVIEW</span>
            <span class="text-slate font-normal">→</span>
          </a>
          
          <a href="/dashboard/schedule" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[02] JADWAL LATIHAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="/dashboard/logs" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[03] LOG LATIHAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="/dashboard/stats" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold">
            <span>[04] STATISTIK</span>
            <span class="text-ember">●</span>
          </a>

          <a href="/dashboard/comparison" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[05] PERBANDINGAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate border-t-grid">
            02 // SISTEM & AKUN
          </div>

          <a href="#settings" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[06] PENGATURAN</span>
            <span class="text-slate font-normal">→</span>
          </a>
        </div>

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
        
        <div class="md:hidden flex border-b-grid bg-light p-3 justify-between items-center font-mono text-[11px] font-bold text-charcoal uppercase tracking-widest">
          <span>USER: <span class="text-ember">@if(session('user')) {{ session('user') }} @else GUEST @endif</span></span>
          <span id="dash-timer-mobile">00:00:00</span>
        </div>

        <!-- PRINT ONLY CLEAN ARCHIVAL HEADER -->
        <div class="print-only-header border-b-[3px] border-black p-5 bg-black text-white font-mono">
          <div class="flex justify-between items-center">
            <h1 class="text-xl font-black uppercase tracking-tight">NAOOLIFT — RINGKASAN STATISTIK LATIHAN</h1>
            <div class="text-xs text-amber-400 font-bold">STATISTICS_SUMMARY</div>
          </div>
          <div class="flex justify-between items-center text-xs text-gray-300 mt-2 border-t border-gray-700 pt-2">
            <div>USER: {{ session('user', 'USER NAOOLIFT') }}</div>
            <div>TANGGAL CETAK: {{ date('d/m/Y H:i:s') }}</div>
          </div>
        </div>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="p-4 sm:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">

          <!-- Hero Section Header -->
          <div class="hero-header border-b-[3px] border-charcoal pb-4 sm:pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
              <span class="font-mono text-[11px] sm:text-xs font-bold uppercase tracking-widest text-ember">
                04 // OVERALL_WORKOUT_STATISTICS
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                STATISTIK LATIHAN
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Ringkasan akumulasi tonase beban, jumlah gerakan terfavorit, dan histori tren 7 hari terakhir.
              </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2 shrink-0">
              <a 
                href="/dashboard/comparison"
                class="border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-5 py-3.5 hover:bg-charcoal transition-none active:translate-y-1 flex items-center gap-2"
              >
                <span>[05] BUKA HALAMAN PERBANDINGAN →</span>
              </a>
            </div>
          </div>

          <!-- ALL-TIME OVERVIEW METRICS GRID -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4 border-l-[8px] border-l-ember">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL TONASE BEBAN (ALL-TIME)
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-ember">
                {{ number_format($allTimeVol) }} <span class="text-sm text-charcoal">KG</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                CUMULATIVE LOAD
              </div>
            </div>

            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL SET DICATAT
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ number_format($allTimeSets) }} <span class="text-sm text-slate">SET</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                TOTAL SETS LOGGED
              </div>
            </div>

            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL GERAKAN LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ $totalExercisesCount }} <span class="text-sm text-slate">GERAKAN</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                EXERCISES LOGGED
              </div>
            </div>

            <div class="border-grid bg-light p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-charcoal uppercase tracking-widest">
                HARI AKTIF LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ $totalActiveDays }} <span class="text-sm text-slate">HARI</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-charcoal uppercase tracking-wider border-t-grid pt-2">
                ACTIVE SESSIONS
              </div>
            </div>
          </div>

          <!-- TOP 5 EXERCISES RANKING -->
          <div class="border-grid bg-canvas p-5 sm:p-6 flex flex-col gap-4">
            <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
              <div>
                <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest">
                  RANKING GERAKAN
                </span>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  5 GERAKAN DENGAN BEBAN TONASE TERBESAR
                </h3>
              </div>
              <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">TOP_EXERCISES</span>
            </div>

            @if($topExercises->count() > 0)
              <div class="flex flex-col gap-3 mt-1">
                @foreach($topExercises as $rank => $ex)
                  <div class="border-grid bg-canvas p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 border-grid bg-charcoal text-canvas flex items-center justify-center font-mono font-bold text-xs shrink-0">
                        #{{ $rank + 1 }}
                      </div>
                      <div>
                        <h4 class="font-black text-base uppercase text-charcoal">{{ $ex->exercise_name }}</h4>
                        <div class="font-mono text-xs text-slate font-bold">
                          {{ $ex->total_count }} DILAKUKAN • {{ $ex->total_sets }} SET TOTAL
                        </div>
                      </div>
                    </div>
                    <div class="font-mono text-right w-full md:w-auto border-t-grid md:border-t-0 pt-2 md:pt-0">
                      <span class="text-[10px] text-slate font-bold block uppercase">AKUMULASI VOLUMETRIK</span>
                      <span class="text-lg font-black text-ember">{{ number_format($ex->total_vol) }} KG</span>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="p-8 text-center font-mono text-xs font-bold text-slate border-grid bg-light">
                BELUM ADA DATA GERAKAN UNTUK MEMBUAT RANKING. SILAKAN CATAT LOG LATIHAN.
              </div>
            @endif
          </div>

          <!-- RECENT 7 DAYS TREND GRID -->
          <div class="border-grid bg-canvas p-5 sm:p-6 flex flex-col gap-4">
            <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
              <div>
                <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest">
                  7-DAY HISTORICAL TREND
                </span>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  TREN VOLUMETRIK 7 HARI TERAKHIR
                </h3>
              </div>
              <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">DAILY_TREND</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 mt-1">
              @foreach($recentSevenDays as $item)
                <div class="border-grid p-3 flex flex-col justify-between gap-2 text-center {{ $item['volume'] > 0 ? 'bg-canvas border-t-[6px] border-t-ember' : 'bg-light' }}">
                  <span class="font-mono text-[10px] font-bold uppercase text-slate">
                    {{ date('d/m', strtotime($item['date'])) }}
                  </span>
                  <div class="font-mono text-base font-black {{ $item['volume'] > 0 ? 'text-charcoal' : 'text-slate' }}">
                    {{ number_format($item['volume']) }}
                    <span class="text-[10px] block font-normal">KG</span>
                  </div>
                  <span class="font-mono text-[9px] font-bold uppercase border-t-grid pt-1 text-slate">
                    {{ $item['sets'] }} SET
                  </span>
                </div>
              @endforeach
            </div>
          </div>

        </div>

      </main>

    </div>

    <!-- FULL-WIDTH SWISS BRUTALIST FOOTER BAR -->
    <footer class="border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
      <div>NAOOLIFT SYSTEM © 2026</div>
      <div class="text-slate">MODULE: OVERALL_WORKOUT_STATISTICS</div>
    </footer>

  </div>

  <!-- NATIVE MOBILE APP PWA BOTTOM NAVIGATION BAR -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-charcoal text-canvas border-t-[3px] border-charcoal grid grid-cols-4 font-mono text-[10px] font-bold uppercase tracking-widest text-center shadow-none">
    <a href="/dashboard" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">■</span>
      <span>OVERVIEW</span>
    </a>
    <a href="/dashboard/schedule" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">≡</span>
      <span>JADWAL</span>
    </a>
    <a href="/dashboard/logs" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">✎</span>
      <span>LOG</span>
    </a>
    <a href="/dashboard/stats" class="py-3 bg-ember text-canvas flex flex-col items-center justify-center gap-0.5">
      <span class="text-xs font-black">▲</span>
      <span>STATS</span>
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
      if (e.key === 'Escape') {
        closeLogoutModal();
      }
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
