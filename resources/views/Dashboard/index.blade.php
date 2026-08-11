<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Dashboard Jadwal Latihan</title>
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
            SYS_SCHEDULE v2.0
          </div>
        </div>
        <!-- Mobile App Status Indicator -->
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
            JADWAL_LATIHAN
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

    <!-- INNER DASHBOARD BODY (SIDEBAR + MAIN SCHEDULE GRID) -->
    <div class="flex flex-col md:flex-row flex-1 items-stretch">
      
      <!-- DESKTOP SWISS BRUTALIST SIDEBAR -->
      <aside class="hidden md:flex w-64 border-r-grid bg-canvas flex-col justify-between shrink-0">
        
        <!-- Sidebar Navigation Menu Links -->
        <div class="flex flex-col font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
          
          <!-- Section 1: Main Modules -->
          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate">
            01 // MODUL LATIHAN
          </div>
          
          <a href="/dashboard" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold">
            <span>[01] JADWAL LATIHAN</span>
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

          <!-- Section 2: System -->
          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate border-t-grid">
            02 // SISTEM & AKUN
          </div>

          <a href="#settings" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[04] PENGATURAN</span>
            <span class="text-slate font-normal">→</span>
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

      <!-- RIGHT MAIN CONTENT AREA (Workout Schedule View) -->
      <main class="flex-1 flex flex-col min-w-0 bg-canvas justify-between">
        
        <!-- Mobile Quick Info Strip -->
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
                01 // MONDAY_TO_SUNDAY_ROUTINE
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                JADWAL LATIHAN MINGGUAN
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Atur dan kelola pembagian sesi latihan Anda dari hari Senin hingga Minggu.
              </p>
            </div>
            <div class="font-mono text-[10px] sm:text-xs font-bold uppercase tracking-widest text-charcoal bg-light p-2.5 sm:p-3 border-grid">
              DOC_REF: SCHED-2026-v2.0
            </div>
          </div>

          <!-- Schedule Summary Metric Cards -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <!-- Metric Card 1 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                SESI LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                {{ $totalWorkoutDays }} <span class="text-sm sm:text-lg text-slate">HARI</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                HARI AKTIF
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
                RECOVERY / REST
              </div>
            </div>

            <!-- Metric Card 3 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL TERCATAT
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                {{ $totalDaysSet }} <span class="text-sm sm:text-lg text-slate">/ 7</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                CAKUPAN MINGGUAN
              </div>
            </div>

            <!-- Metric Card 4 -->
            <div class="border-grid bg-light p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-charcoal uppercase tracking-widest">
                STATUS MODUL
              </span>
              <div class="font-mono text-xl sm:text-3xl font-bold text-ember flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 bg-ember animate-pulse inline-block"></span>
                ACTIVE
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-charcoal uppercase tracking-wider border-t-grid pt-2">
                DATABASE_SYNCED
              </div>
            </div>
          </div>

          <!-- WEEKLY SCHEDULE GRID (SENIN - MINGGU CARDS) -->
          <div class="flex flex-col gap-4 mt-2">
            <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
              <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tighter text-charcoal">
                JADWAL 7 HARI SEMINGGU
              </h3>
              <span class="font-mono text-xs font-bold text-ember uppercase tracking-widest">
                DATABASE PERSISTENT
              </span>
            </div>

            <!-- 7 Days Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
              @foreach($days as $index => $day)
                @php
                  $sched = $userSchedules->get($day);
                @endphp

                <div class="border-grid bg-canvas flex flex-col justify-between p-4 sm:p-5 gap-4 hover:border-ember transition-colors">
                  
                  <!-- Card Header: Day Title -->
                  <div class="flex justify-between items-center border-b-grid pb-3">
                    <span class="font-mono text-xs font-bold uppercase tracking-widest text-slate">
                      0{{ $index + 1 }} // {{ $day }}
                    </span>
                    @if($sched)
                      @if($sched->is_rest)
                        <span class="font-mono text-[10px] bg-slate text-canvas px-2 py-0.5 font-bold uppercase">
                          REST DAY
                        </span>
                      @else
                        <span class="font-mono text-[10px] bg-ember text-canvas px-2 py-0.5 font-bold uppercase">
                          WORKOUT
                        </span>
                      @endif
                    @else
                      <span class="font-mono text-[10px] bg-light text-slate px-2 py-0.5 font-bold uppercase">
                        EMPTY
                      </span>
                    @endif
                  </div>

                  <!-- Card Body: Schedule Details -->
                  <div class="flex flex-col gap-2 min-h-[90px]">
                    @if($sched)
                      <h4 class="text-lg font-black uppercase tracking-tight text-charcoal">
                        {{ $sched->title }}
                      </h4>

                      @if($sched->focus_target)
                        <div class="font-mono text-xs font-bold text-ember uppercase tracking-wide">
                          FOKUS: {{ $sched->focus_target }}
                        </div>
                      @endif

                      @if($sched->notes)
                        <p class="text-xs font-semibold text-slate mt-1 line-clamp-3">
                          {{ $sched->notes }}
                        </p>
                      @endif
                    @else
                      <div class="flex flex-col items-center justify-center h-full py-4 text-center gap-1">
                        <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">
                          [ BELUM ADA JADWAL ]
                        </span>
                        <span class="text-[11px] font-semibold text-slate">
                          Klik di bawah untuk mengatur latihan.
                        </span>
                      </div>
                    @endif
                  </div>

                  <!-- Card Footer Action Buttons -->
                  <div class="border-t-grid pt-3 flex gap-2">
                    @if($sched)
                      <button 
                        onclick="openScheduleModal('{{ $day }}', '{{ addslashes($sched->title) }}', '{{ addslashes($sched->focus_target) }}', '{{ addslashes($sched->notes) }}', {{ $sched->is_rest ? 'true' : 'false' }})"
                        class="flex-1 border-grid bg-light text-charcoal text-center font-bold text-[11px] uppercase tracking-widest py-2 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
                      >
                        EDIT
                      </button>

                      <form action="/schedules/delete" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="day_name" value="{{ $day }}">
                        <button 
                          type="submit" 
                          class="border-grid bg-canvas text-ember font-bold text-[11px] uppercase tracking-widest px-3 py-2 hover:bg-ember hover:text-canvas transition-none active:translate-y-1"
                          title="Hapus Jadwal"
                        >
                          ✕
                        </button>
                      </form>
                    @else
                      <button 
                        onclick="openScheduleModal('{{ $day }}', '', '', '', false)"
                        class="w-full border-grid bg-ember text-canvas text-center font-bold text-[11px] uppercase tracking-widest py-2.5 hover:bg-charcoal transition-none active:translate-y-1"
                      >
                        + ATUR JADWAL
                      </button>
                    @endif
                  </div>

                </div>
              @endforeach
            </div>
          </div>

        </div>

        <!-- FOOTER -->
        <footer class="mt-auto border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
          <div>NAOOLIFT SYSTEM © 2026</div>
          <div class="text-slate">MODULE: WORKOUT_SCHEDULE_CRUD</div>
        </footer>

      </main>

    </div>

  </div>

  <!-- NATIVE MOBILE APP PWA BOTTOM NAVIGATION BAR -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-charcoal text-canvas border-t-[3px] border-charcoal grid grid-cols-4 font-mono text-[10px] font-bold uppercase tracking-widest text-center shadow-none">
    <a href="/dashboard" class="py-3 bg-ember text-canvas border-r-grid flex flex-col items-center justify-center gap-0.5">
      <span class="text-xs font-black">■</span>
      <span>JADWAL</span>
    </a>
    <a href="#log" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">≡</span>
      <span>LOG</span>
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

  <!-- SWISS BRUTALIST ADD / EDIT SCHEDULE MODAL -->
  <div id="schedule-modal" class="fixed inset-0 z-[100] bg-charcoal/80 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-[480px] border-grid bg-canvas p-6 sm:p-8 flex flex-col gap-4 shadow-none relative animate-fade-in my-auto">
      
      <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
        <div>
          <h3 class="font-black text-xl uppercase tracking-tighter text-charcoal">
            ATUR JADWAL LATIHAN
          </h3>
          <div id="modal-day-subtitle" class="font-mono text-xs font-bold text-ember uppercase tracking-widest">
            HARI SENIN
          </div>
        </div>
        <button onclick="closeScheduleModal()" class="font-mono text-xs font-bold text-charcoal hover:text-ember">[✕]</button>
      </div>

      <form action="/schedules" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" id="form-day-name" name="day_name" value="SENIN">

        <!-- Field 1: Nama Sesi Latihan -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            01 / NAMA SESI LATIHAN / ROUTINE
          </label>
          <input 
            type="text" 
            id="form-title"
            name="title" 
            required 
            placeholder="CONTOH: PUSH DAY - DADA & TRICEPS"
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold uppercase focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
          >
        </div>

        <!-- Field 2: Target Otot Focus -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            02 / TARGET OTOT / FOKUS (OPSIONAL)
          </label>
          <input 
            type="text" 
            id="form-focus-target"
            name="focus_target" 
            placeholder="CONTOH: CHEST, SHOULDERS, TRICEPS"
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold uppercase focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
          >
        </div>

        <!-- Field 3: Catatan Routine -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            03 / CATATAN / TARGET SET & REPS (OPSIONAL)
          </label>
          <textarea 
            id="form-notes"
            name="notes" 
            rows="3"
            placeholder="CONTOH: 4 Set Bench Press, 3 Set Incline Press, 3 Set Lateral Raise"
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-semibold focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
          ></textarea>
        </div>

        <!-- Checkbox: Rest Day Mode -->
        <div class="flex items-center gap-2 pt-1">
          <input 
            type="checkbox" 
            id="form-is-rest"
            name="is_rest"
            class="w-4 h-4 border-grid accent-ember cursor-pointer"
          >
          <label for="form-is-rest" class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal cursor-pointer">
            SET SEBAGAI HARI ISTIRAHAT (REST DAY)
          </label>
        </div>

        <!-- Modal Action Buttons -->
        <div class="flex gap-3 pt-3 border-t-[3px] border-charcoal">
          <button 
            type="button" 
            onclick="closeScheduleModal()" 
            class="flex-1 border-[3px] border-charcoal bg-light text-charcoal font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
          >
            BATAL
          </button>
          <button 
            type="submit" 
            class="flex-1 border-[3px] border-charcoal bg-ember text-canvas font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal transition-none active:translate-y-1"
          >
            SIMPAN JADWAL →
          </button>
        </div>
      </form>

    </div>
  </div>

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
    // 1. Live Clock Counter
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

    // 2. Schedule Modal Open & Close Functions
    function openScheduleModal(dayName, title = '', focusTarget = '', notes = '', isRest = false) {
      const modal = document.getElementById('schedule-modal');
      const daySubtitle = document.getElementById('modal-day-subtitle');
      const formDayName = document.getElementById('form-day-name');
      const formTitle = document.getElementById('form-title');
      const formFocusTarget = document.getElementById('form-focus-target');
      const formNotes = document.getElementById('form-notes');
      const formIsRest = document.getElementById('form-is-rest');

      if (modal) {
        if (daySubtitle) daySubtitle.textContent = `HARI ${dayName}`;
        if (formDayName) formDayName.value = dayName;
        if (formTitle) formTitle.value = title;
        if (formFocusTarget) formFocusTarget.value = focusTarget;
        if (formNotes) formNotes.value = notes;
        if (formIsRest) formIsRest.checked = isRest;

        modal.classList.remove('hidden');
      }
    }

    function closeScheduleModal() {
      const modal = document.getElementById('schedule-modal');
      if (modal) modal.classList.add('hidden');
    }

    // 3. Logout Modal Functions
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
        closeScheduleModal();
        closeLogoutModal();
      }
    });

    // 4. Toast Dismiss
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
