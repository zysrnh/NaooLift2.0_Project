<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Overview Dasbor Latihan</title>
<meta name="theme-color" content="#1C1C1C">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="NaooLift">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js')
        .catch(err => console.log('SW Registration error:', err));
    });
  }
</script>
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
      <div class="w-full md:w-64 border-b-grid md:border-b-0 md:border-r-grid bg-charcoal text-canvas p-4 sm:p-5 flex items-center justify-between shrink-0">
        <div>
          <a href="/" class="text-xl font-black uppercase tracking-tighter hover:text-ember transition-colors">
            NAOOLIFT.LOG
          </a>
          <div class="font-mono text-[9px] text-slate uppercase tracking-widest mt-0.5">
            ATLET_DASHBOARD v2.0
          </div>
        </div>
        <span class="font-mono text-[10px] bg-ember text-canvas px-2 py-1 font-bold uppercase border-grid flex items-center gap-1.5">
          <span class="w-2 h-2 bg-canvas animate-pulse inline-block"></span>
          LIVE
        </span>
      </div>

      <div class="hidden md:flex flex-1 flex-row font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-canvas">
          <span class="font-sans">MODUL UTAMA:</span>
          <span class="text-ember flex items-center gap-1.5 font-bold">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
            OVERVIEW_UTAMA
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
          
          <a href="/dashboard" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold">
            <span>[01] OVERVIEW</span>
            <span class="text-ember">●</span>
          </a>
          
          <a href="/dashboard/schedule" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[02] JADWAL LATIHAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="/dashboard/logs" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[03] LOG LATIHAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="/dashboard/stats" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[04] STATISTIK</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="/dashboard/comparison" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[05] PERBANDINGAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate border-t-grid">
            02 // SISTEM & AKUN
          </div>

          <a href="/dashboard/settings" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[06] PENGATURAN</span>
            <span class="text-slate font-normal">→</span>
          </a>

          @if(session('is_admin') || (Auth::check() && Auth::user()->is_admin))
            <a href="/admin/dashboard" class="p-4 border-b-grid bg-ember text-canvas flex items-center justify-between font-bold hover:bg-charcoal transition-none">
              <span>[07] PANEL ADMIN</span>
              <span>→</span>
            </a>
          @endif
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

        <!-- MAIN CONTENT WRAPPER -->
        <div class="p-4 sm:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">

          <!-- Hero Section Header & Quick Links -->
          <div class="border-b-[3px] border-charcoal pb-6 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
              <span class="font-mono text-[11px] sm:text-xs font-bold uppercase tracking-widest text-ember">
                01 // ATHLETE_OVERVIEW_MODULE
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                HALO, {{ session('user', 'USER NAOOLIFT') }}
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Ringkasan progres aktivitas latihan, jadwal hari ini, dan histori tonase beban Anda untuk periode <span class="text-charcoal font-bold">{{ $currentMonthLabel }}</span>.
              </p>
            </div>
            
            <!-- QUICK NAVIGATION SHORTCUT BUTTONS -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
              <a 
                href="/dashboard/logs?date={{ date('Y-m-d') }}"
                class="border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-4 py-3 hover:bg-charcoal transition-none active:translate-y-1 flex items-center gap-2"
              >
                <span>[ + CATAT LATIHAN HARI INI ]</span>
              </a>
              <a 
                href="/dashboard/schedule"
                class="border-grid bg-canvas text-charcoal font-bold text-xs uppercase tracking-widest px-4 py-3 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
              >
                <span>[ JADWAL PROGRAM ]</span>
              </a>
            </div>
          </div>

          <!-- FEATURED CARD: TODAY'S SCHEDULED ROUTINE -->
          <div class="border-grid bg-canvas p-6 sm:p-8 border-l-[8px] border-l-ember flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex flex-col gap-2">
              <div class="flex items-center gap-2 font-mono text-xs font-bold uppercase tracking-widest text-slate">
                <span class="w-2.5 h-2.5 bg-ember animate-pulse inline-block"></span>
                <span>JADWAL HARI INI ({{ $todayName }}, {{ date('d/m/Y') }})</span>
              </div>
              <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-charcoal">
                {{ $todayRoutineTitle }}
              </h3>
              @if($todaySchedule && $todaySchedule->focus_target)
                <div class="font-mono text-xs text-slate font-bold">
                  TARGET OTOT: <span class="text-charcoal">{{ $todaySchedule->focus_target }}</span>
                </div>
              @endif
            </div>

            <div class="flex items-center gap-3 shrink-0">
              <a 
                href="/dashboard/logs?date={{ date('Y-m-d') }}" 
                class="border-grid bg-charcoal text-canvas font-bold text-xs uppercase tracking-widest px-6 py-4 hover:bg-ember transition-none active:translate-y-1 text-center w-full md:w-auto"
              >
                CATAT LATIHAN SEKARANG →
              </a>
            </div>
          </div>

          <!-- 4 MAIN OVERVIEW METRIC CARDS -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <div class="border-grid bg-canvas p-5 flex flex-col justify-between gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL TONASE BEBAN (ALL-TIME)
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-ember">
                {{ number_format($allTimeVol) }} <span class="text-sm text-charcoal">KG</span>
              </div>
              <div class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-2">
                AKUMULASI TOTAL
              </div>
            </div>

            <div class="border-grid bg-canvas p-5 flex flex-col justify-between gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL CATATAN LOG
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ number_format($totalLogs) }} <span class="text-sm text-slate">ENTRI</span>
              </div>
              <div class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-2">
                LOGS RECORDED
              </div>
            </div>

            <div class="border-grid bg-canvas p-5 flex flex-col justify-between gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                JADWAL PROGRAM ({{ $currentMonthLabel }})
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ $totalWorkoutDays }} <span class="text-sm text-slate">/ {{ $totalDaysSet }} HARI</span>
              </div>
              <div class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-2">
                PROGRAM COVERAGE
              </div>
            </div>

            <div class="border-grid bg-light p-5 flex flex-col justify-between gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-charcoal uppercase tracking-widest">
                TONASE HARI INI
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ number_format($todayVol) }} <span class="text-sm text-slate">KG</span>
              </div>
              <div class="font-mono text-[9px] text-charcoal font-bold uppercase border-t-grid pt-2">
                {{ $todaySets }} SET LATIHAN
              </div>
            </div>

          </div>

          <!-- RECENT WORKOUT LOGS FEED TABLE -->
          <div class="border-grid bg-canvas p-5 sm:p-8 flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2">
              <div>
                <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest">
                  RECENT_ACTIVITY_LOGS
                </span>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  5 CATATAN LATIHAN TERBARU
                </h3>
              </div>
              <a 
                href="/dashboard/logs" 
                class="font-mono text-xs font-bold text-ember hover:text-charcoal uppercase tracking-widest"
              >
                LIHAT SEMUA LOG LATIHAN →
              </a>
            </div>

            @if($recentLogs && $recentLogs->count() > 0)
              <div class="overflow-x-auto border-grid">
                <table class="w-full text-left border-collapse font-mono text-xs">
                  <thead>
                    <tr class="bg-charcoal text-canvas uppercase text-[11px]">
                      <th class="p-3 border-r-grid">TANGGAL</th>
                      <th class="p-3 border-r-grid">NAMA SESI / ROUTINE</th>
                      <th class="p-3 border-r-grid">GERAKAN LATIHAN</th>
                      <th class="p-3 border-r-grid text-center">SET x REPS</th>
                      <th class="p-3 border-r-grid text-right">BEBAN (KG)</th>
                      <th class="p-3 text-right">TOTAL VOLUMETRIK</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($recentLogs as $idx => $log)
                      @php
                        $vol = $log->sets * $log->reps * $log->weight_kg;
                      @endphp
                      <tr class="border-b-grid {{ $idx % 2 === 1 ? 'bg-light' : 'bg-canvas' }} hover:bg-charcoal hover:text-canvas transition-none">
                        <td class="p-3 border-r-grid font-bold">
                          {{ date('d/m/Y', strtotime($log->log_date)) }}
                        </td>
                        <td class="p-3 border-r-grid font-bold text-ember">
                          {{ $log->routine_title }}
                        </td>
                        <td class="p-3 border-r-grid font-black">
                          {{ $log->exercise_name }}
                        </td>
                        <td class="p-3 border-r-grid text-center font-bold">
                          {{ $log->sets }} SET × {{ $log->reps }} REPS
                        </td>
                        <td class="p-3 border-r-grid text-right font-bold">
                          {{ number_format($log->weight_kg, 1) }} KG
                        </td>
                        <td class="p-3 text-right font-black text-ember">
                          {{ number_format($vol) }} KG
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="p-8 text-center font-mono text-xs font-bold text-slate border-grid bg-light">
                BELUM ADA CATATAN LATIHAN TERSEDIA. SILAKAN TEKAN TOMBOL [ + CATAT LATIHAN HARI INI ] UNTUK MEMULAI.
              </div>
            @endif
          </div>

        </div>

      </main>

    </div>

    <!-- FULL-WIDTH SWISS BRUTALIST FOOTER BAR -->
    <footer class="border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
      <div>NAOOLIFT SYSTEM © 2026</div>
      <div class="text-slate">MODULE: ATHLETE_OVERVIEW_MODULE</div>
    </footer>

  </div>

  <!-- NATIVE MOBILE APP PWA BOTTOM NAVIGATION BAR -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-charcoal text-canvas border-t-[3px] border-charcoal grid grid-cols-4 font-mono text-[10px] font-bold uppercase tracking-widest text-center shadow-none">
    <a href="/dashboard" class="py-3 bg-ember text-canvas flex flex-col items-center justify-center gap-0.5">
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
    <a href="/dashboard/comparison" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">⇄</span>
      <span>BANDING</span>
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
