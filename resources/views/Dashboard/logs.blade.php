<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Log Catatan Latihan & Stopwatch</title>
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
    header, aside, nav, #toast-container, button, form, .no-print, .hero-header, .action-bar, .timer-widget, .view-selector-bar {
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
            SYS_WORKOUT_LOGS v2.0
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
            CATATAN_LOG_LATIHAN
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

          <a href="/dashboard/logs" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold">
            <span>[03] LOG LATIHAN</span>
            <span class="text-ember">●</span>
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
            <h1 class="text-xl font-black uppercase tracking-tight">NAOOLIFT — ARSIP LOG CATATAN LATIHAN</h1>
            <div class="text-xs text-amber-400 font-bold">WORKOUT_LOGS_ARCHIVE</div>
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
                03 // WORKOUT_LOGGING_MODULE
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                LOG CATATAN LATIHAN
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Catat setiap set, repetisi, beban (KG), serta durasi latihan Anda. Gunakan stopwatch sesi di bawah untuk menghitung waktu latihan.
              </p>
            </div>
            
            <!-- ACTION BUTTONS -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
              <button 
                onclick="openAddLogModal()"
                class="border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-5 py-3.5 hover:bg-charcoal transition-none active:translate-y-1 flex items-center gap-2"
              >
                <span>[ + TAMBAH LOG LATIHAN ]</span>
              </button>

              <a 
                href="/dashboard/logs/export-excel?date={{ $selectedDate }}"
                class="border-grid bg-canvas text-charcoal font-bold text-xs uppercase tracking-widest px-4 py-3.5 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
                title="Download file Excel"
              >
                <span>[ EXPORT EXCEL ]</span>
              </a>

              <button 
                onclick="window.print()"
                class="border-grid bg-canvas text-charcoal font-bold text-xs uppercase tracking-widest px-4 py-3.5 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
                title="Cetak PDF A4"
              >
                <span>[ CETAK / PDF ARSIP ]</span>
              </button>
            </div>
          </div>

          <!-- BACKGROUND ACTIVE WORKOUT STOPWATCH WIDGET (RUNS IN BACKGROUND) -->
          <div class="timer-widget border-grid bg-charcoal text-canvas p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 border-l-[8px] border-l-ember">
            <div class="flex flex-col gap-1">
              <div class="flex items-center gap-2 font-mono text-[11px] font-bold uppercase tracking-widest text-slate">
                <span id="workout-pulse-dot" class="w-2.5 h-2.5 bg-slate inline-block"></span>
                <span id="workout-timer-status">STOPWATCH SESI LATIHAN (SIAP)</span>
              </div>
              <div class="font-mono text-4xl sm:text-5xl font-black text-canvas tracking-tight" id="workout-stopwatch-display">
                00:00:00
              </div>
              <span class="font-mono text-[10px] text-slate font-bold uppercase">
                PERSISTENT IN BACKGROUND (TIDAK MATI SAAT TAB DITUTUP)
              </span>
            </div>

            <!-- STOPWATCH CONTROL BUTTONS -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
              <button 
                id="btn-timer-start" 
                onclick="startWorkoutTimer()" 
                class="border-grid bg-ember text-canvas font-mono font-bold text-xs uppercase tracking-widest px-4 py-3 hover:bg-canvas hover:text-charcoal transition-none active:translate-y-1"
              >
                [ ▶ MULAI SESI ]
              </button>

              <button 
                id="btn-timer-pause" 
                onclick="pauseWorkoutTimer()" 
                class="border-grid bg-canvas text-charcoal font-mono font-bold text-xs uppercase tracking-widest px-4 py-3 hover:bg-ember hover:text-canvas transition-none active:translate-y-1 hidden"
              >
                [ ⏸ PAUSE ]
              </button>

              <button 
                id="btn-timer-finish" 
                onclick="finishWorkoutTimer()" 
                class="border-grid bg-ember text-canvas font-mono font-bold text-xs uppercase tracking-widest px-4 py-3 hover:bg-canvas hover:text-charcoal transition-none active:translate-y-1 hidden"
              >
                [ ⏹ SIMPAN DURASI KE LOG ]
              </button>

              <button 
                onclick="resetWorkoutTimer()" 
                class="border-grid bg-canvas text-charcoal font-mono font-bold text-xs uppercase tracking-widest px-3 py-3 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
                title="Reset Timer"
              >
                [ ↺ RESET ]
              </button>
            </div>
          </div>

          <!-- VIEW SELECTOR BAR (HARIAN / MINGGU INI / BULAN INI / SEMUA RIWAYAT) -->
          <div class="view-selector-bar border-grid bg-light p-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 font-mono text-xs font-bold uppercase tracking-widest">
            <a 
              href="/dashboard/logs?view=daily&date={{ $selectedDate }}" 
              class="p-3 border-grid text-center transition-none {{ $activeView === 'daily' ? 'bg-charcoal text-canvas' : 'bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas' }}"
            >
              [01] HARIAN (TANGGAL PILIHAN)
            </a>
            <a 
              href="/dashboard/logs?view=weekly" 
              class="p-3 border-grid text-center transition-none {{ $activeView === 'weekly' ? 'bg-charcoal text-canvas' : 'bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas' }}"
            >
              [02] MINGGU INI (MINGGUAN)
            </a>
            <a 
              href="/dashboard/logs?view=monthly" 
              class="p-3 border-grid text-center transition-none {{ $activeView === 'monthly' ? 'bg-charcoal text-canvas' : 'bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas' }}"
            >
              [03] BULAN INI (BULANAN)
            </a>
            <a 
              href="/dashboard/logs?view=all" 
              class="p-3 border-grid text-center transition-none {{ $activeView === 'all' ? 'bg-charcoal text-canvas' : 'bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas' }}"
            >
              [04] SEMUA RIWAYAT (ALL-TIME)
            </a>
          </div>

          <!-- DATE SELECTOR FORM (DISPLAYED ONLY WHEN IN DAILY VIEW) -->
          @if($activeView === 'daily')
            <form action="/dashboard/logs" method="GET" class="border-grid bg-light p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
              <input type="hidden" name="view" value="daily">
              <div class="flex items-center gap-3 w-full sm:w-auto">
                <span class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal whitespace-nowrap">PILIH TANGGAL LATIHAN:</span>
                <input 
                  type="date" 
                  name="date" 
                  value="{{ $selectedDate }}"
                  onchange="this.form.submit()"
                  class="bg-canvas border-grid p-2 font-mono text-xs font-bold uppercase text-charcoal focus:outline-none focus:border-ember cursor-pointer"
                >
              </div>
              
              <div class="font-mono text-xs text-slate font-bold uppercase">
                PROGRAM SESI: <span class="text-ember font-black">{{ $scheduledRoutine ? $scheduledRoutine->title : 'BELUM ADA JADWAL' }}</span>
              </div>
            </form>
          @endif

          <!-- AGGREGATE SUMMARY METRICS FOR SELECTED VIEW -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-2 border-l-[8px] border-l-ember">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL VOLUMETRIK BEBAN
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-ember">
                {{ number_format($totalVolumeKg) }} <span class="text-sm text-charcoal">KG</span>
              </div>
              <span class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-1">
                ACCUMULATED VOLUME
              </span>
            </div>

            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-2">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL GERAKAN LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ $totalExercises }} <span class="text-sm text-slate">GERAKAN</span>
              </div>
              <span class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-1">
                EXERCISES COUNT
              </span>
            </div>

            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-2">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL SET LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ $totalSets }} <span class="text-sm text-slate">SET</span>
              </div>
              <span class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-1">
                TOTAL SETS LOGGED
              </span>
            </div>

            <div class="border-grid bg-light p-4 sm:p-5 flex flex-col justify-between gap-2">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-charcoal uppercase tracking-widest">
                AKUMULASI DURASI SESI
              </span>
              <div class="font-mono text-2xl sm:text-3xl font-black text-charcoal">
                @php
                  $hours = floor($totalDurationSeconds / 3600);
                  $mins = floor(($totalDurationSeconds % 3600) / 60);
                  $secs = $totalDurationSeconds % 60;
                @endphp
                @if($hours > 0)
                  {{ $hours }}<span class="text-xs text-slate">J</span> {{ $mins }}<span class="text-xs text-slate">M</span>
                @elseif($mins > 0)
                  {{ $mins }}<span class="text-xs text-slate">M</span> {{ $secs }}<span class="text-xs text-slate">D</span>
                @else
                  {{ $secs }}<span class="text-xs text-slate">DETIK</span>
                @endif
              </div>
              <span class="font-mono text-[9px] text-charcoal font-bold uppercase border-t-grid pt-1">
                WORKOUT DURATION
              </span>
            </div>
          </div>

          <!-- TABLE OF LOGGED EXERCISES -->
          <div class="border-grid bg-canvas p-5 sm:p-8 flex flex-col gap-4">
            <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-4">
              <div>
                <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest">
                  LIST_ENTRIES
                </span>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  {{ $viewLabel }}
                </h3>
              </div>
              <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">{{ $logs->count() }} DATA</span>
            </div>

            @if($logs->count() > 0)
              <div class="overflow-x-auto border-grid">
                <table class="w-full text-left border-collapse font-mono text-xs">
                  <thead>
                    <tr class="bg-charcoal text-canvas uppercase text-[11px]">
                      <th class="p-3 border-r-grid">TANGGAL</th>
                      <th class="p-3 border-r-grid">SESI / ROUTINE</th>
                      <th class="p-3 border-r-grid">GERAKAN LATIHAN</th>
                      <th class="p-3 border-r-grid text-center">SET × REPS</th>
                      <th class="p-3 border-r-grid text-right">BEBAN (KG)</th>
                      <th class="p-3 border-r-grid text-right">VOLUMETRIK</th>
                      <th class="p-3 border-r-grid text-center">DURASI SESI</th>
                      <th class="p-3 border-r-grid">CATATAN</th>
                      <th class="p-3 text-center no-print">AKSI</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($logs as $idx => $log)
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
                        <td class="p-3 border-r-grid font-black text-sm">
                          {{ $log->exercise_name }}
                        </td>
                        <td class="p-3 border-r-grid text-center font-bold">
                          {{ $log->sets }} SET × {{ $log->reps }} REPS
                        </td>
                        <td class="p-3 border-r-grid text-right font-bold">
                          {{ number_format($log->weight_kg, 1) }} KG
                        </td>
                        <td class="p-3 border-r-grid text-right font-black text-ember">
                          {{ number_format($vol) }} KG
                        </td>
                        <td class="p-3 border-r-grid text-center font-bold">
                          @if($log->duration_seconds)
                            @php
                              $dMins = floor($log->duration_seconds / 60);
                              $dSecs = $log->duration_seconds % 60;
                            @endphp
                            <span class="bg-ember text-canvas px-2 py-1 text-[10px]">
                              ⏱ {{ $dMins }}M {{ $dSecs }}S
                            </span>
                          @else
                            <span class="text-slate font-normal">-</span>
                          @endif
                        </td>
                        <td class="p-3 border-r-grid font-sans text-xs text-slate">
                          {{ $log->notes ?? '-' }}
                        </td>
                        <td class="p-3 text-center no-print">
                          <form action="/dashboard/logs/delete" method="POST" onsubmit="return confirm('HAPUS CATATAN GERAKAN {{ $log->exercise_name }}?');">
                            @csrf
                            <input type="hidden" name="log_id" value="{{ $log->id }}">
                            <input type="hidden" name="view" value="{{ $activeView }}">
                            <button type="submit" class="text-ember hover:text-canvas hover:bg-ember font-mono font-bold text-[11px] px-2 py-1 border-grid transition-none">
                              [✕ HAPUS]
                            </button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="p-8 text-center font-mono text-xs font-bold text-slate border-grid bg-light">
                BELUM ADA CATATAN LATIHAN UNTUK PERIODE TAMPILAN INI. SILAKAN KLIK TOMBOL [ + TAMBAH LOG LATIHAN ].
              </div>
            @endif
          </div>

          <!-- PRINT ONLY CLEAN COMPACT TABULAR ARCHIVE -->
          <div class="print-only-table font-mono text-xs">
            <table class="w-full border-collapse border-[2px] border-black">
              <thead>
                <tr class="bg-black text-white uppercase text-[11px]">
                  <th class="border-[2px] border-black p-2 text-left">TANGGAL</th>
                  <th class="border-[2px] border-black p-2 text-left">SESI / ROUTINE</th>
                  <th class="border-[2px] border-black p-2 text-left">GERAKAN LATIHAN</th>
                  <th class="border-[2px] border-black p-2 text-center">SET × REPS</th>
                  <th class="border-[2px] border-black p-2 text-right">BEBAN (KG)</th>
                  <th class="border-[2px] border-black p-2 text-right">VOLUMETRIK</th>
                </tr>
              </thead>
              <tbody>
                @foreach($logs as $log)
                  @php $vol = $log->sets * $log->reps * $log->weight_kg; @endphp
                  <tr class="border-[1.5px] border-black font-bold">
                    <td class="border-[1.5px] border-black p-2">{{ date('d/m/Y', strtotime($log->log_date)) }}</td>
                    <td class="border-[1.5px] border-black p-2">{{ $log->routine_title }}</td>
                    <td class="border-[1.5px] border-black p-2 font-black">{{ $log->exercise_name }}</td>
                    <td class="border-[1.5px] border-black p-2 text-center">{{ $log->sets }} SET × {{ $log->reps }} REPS</td>
                    <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($log->weight_kg, 1) }} KG</td>
                    <td class="border-[1.5px] border-black p-2 text-right font-black" style="color: #9A4A2E;">{{ number_format($vol) }} KG</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>

      </main>

    </div>

    <!-- FULL-WIDTH SWISS BRUTALIST FOOTER BAR -->
    <footer class="border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
      <div>NAOOLIFT SYSTEM © 2026</div>
      <div class="text-slate">MODULE: WORKOUT_LOGGING_MODULE</div>
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
    <a href="/dashboard/logs" class="py-3 bg-ember text-canvas flex flex-col items-center justify-center gap-0.5">
      <span class="text-xs font-black">✎</span>
      <span>LOG</span>
    </a>
    <a href="/dashboard/comparison" class="py-3 text-canvas hover:bg-light hover:text-charcoal border-r-grid flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">⇄</span>
      <span>BANDING</span>
    </a>
  </nav>

  <!-- ADD WORKOUT LOG MODAL -->
  <div id="add-log-modal" class="fixed inset-0 z-[100] bg-charcoal/80 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-[540px] border-grid bg-canvas p-6 sm:p-8 flex flex-col gap-4 shadow-none relative animate-fade-in">
      <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
        <h3 class="font-black text-xl uppercase tracking-tighter text-charcoal">
          CATAT GERAKAN LATIHAN
        </h3>
        <button onclick="closeAddLogModal()" class="font-mono text-xs font-bold text-ember hover:text-charcoal">[✕ TUTUP]</button>
      </div>

      <form action="/dashboard/logs" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="view" value="{{ $activeView }}">
        <input type="hidden" id="modal-duration-seconds" name="duration_seconds" value="">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="flex flex-col gap-1">
            <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
              TANGGAL LATIHAN
            </label>
            <input 
              type="date" 
              name="log_date" 
              value="{{ $selectedDate }}"
              required
              class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
            >
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
              NAMA SESI / ROUTINE
            </label>
            <input 
              type="text" 
              name="routine_title" 
              value="{{ $scheduledRoutine ? $scheduledRoutine->title : 'LATIHAN BEBAS' }}"
              required
              class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
            >
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            NAMA GERAKAN LATIHAN
          </label>
          <input 
            type="text" 
            name="exercise_name" 
            placeholder="MISAL: BENCH PRESS / BARBELL SQUAT"
            required
            class="w-full bg-light border-grid p-3 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
          >
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div class="flex flex-col gap-1">
            <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
              JUMLAH SET
            </label>
            <input 
              type="number" 
              name="sets" 
              value="4"
              min="1"
              required
              class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
            >
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
              REPETISI
            </label>
            <input 
              type="number" 
              name="reps" 
              value="10"
              min="1"
              required
              class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
            >
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
              BEBAN (KG)
            </label>
            <input 
              type="number" 
              step="0.5" 
              name="weight_kg" 
              value="20"
              min="0"
              required
              class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
            >
          </div>
        </div>

        <!-- STOPWATCH DURATION RECORDED BADGE -->
        <div id="modal-duration-badge" class="hidden font-mono text-xs font-bold bg-light p-2.5 border-grid text-ember flex items-center gap-2">
          <span>⏱ DURASI SESI TERDAFTAR:</span>
          <span id="modal-duration-text" class="font-black text-charcoal">00:00:00</span>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            CATATAN OPSIONAL
          </label>
          <input 
            type="text" 
            name="notes" 
            placeholder="MISAL: RPE 8, FORM BAGUS"
            class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
          >
        </div>

        <button 
          type="submit" 
          class="w-full border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest py-3.5 hover:bg-charcoal transition-none active:translate-y-1 mt-2"
        >
          SIMPAN LOG LATIHAN →
        </button>
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

  <!-- PERSISTENT BACKGROUND WORKOUT STOPWATCH JAVASCRIPT ENGINE -->
  <script>
    let timerInterval = null;

    function formatTime(seconds) {
      const h = Math.floor(seconds / 3600);
      const m = Math.floor((seconds % 3600) / 60);
      const s = seconds % 60;
      const hh = String(h).padStart(2, '0');
      const mm = String(m).padStart(2, '0');
      const ss = String(s).padStart(2, '0');
      return `${hh}:${mm}:${ss}`;
    }

    function updateWorkoutTimerUI() {
      const isRunning = localStorage.getItem('naoolift_timer_running') === 'true';
      const isPaused = localStorage.getItem('naoolift_timer_paused') === 'true';
      const startTime = parseInt(localStorage.getItem('naoolift_timer_start_time') || '0', 10);
      const accumulatedPaused = parseInt(localStorage.getItem('naoolift_timer_accumulated') || '0', 10);

      const display = document.getElementById('workout-stopwatch-display');
      const statusText = document.getElementById('workout-timer-status');
      const pulseDot = document.getElementById('workout-pulse-dot');

      const btnStart = document.getElementById('btn-timer-start');
      const btnPause = document.getElementById('btn-timer-pause');
      const btnFinish = document.getElementById('btn-timer-finish');

      if (isRunning) {
        const now = Date.now();
        const elapsedSecs = Math.floor((now - startTime) / 1000) + accumulatedPaused;
        if (display) display.textContent = formatTime(elapsedSecs);
        if (statusText) statusText.textContent = 'STOPWATCH SESI LATIHAN (● RUNNING IN BACKGROUND)';
        if (pulseDot) pulseDot.className = 'w-2.5 h-2.5 bg-ember animate-pulse inline-block';

        if (btnStart) btnStart.classList.add('hidden');
        if (btnPause) btnPause.classList.remove('hidden');
        if (btnFinish) btnFinish.classList.remove('hidden');
      } else if (isPaused) {
        if (display) display.textContent = formatTime(accumulatedPaused);
        if (statusText) statusText.textContent = 'STOPWATCH SESI LATIHAN (PAUSED)';
        if (pulseDot) pulseDot.className = 'w-2.5 h-2.5 bg-slate inline-block';

        if (btnStart) {
          btnStart.textContent = '[ ▶ LANJUTKAN ]';
          btnStart.classList.remove('hidden');
        }
        if (btnPause) btnPause.classList.add('hidden');
        if (btnFinish) btnFinish.classList.remove('hidden');
      } else {
        if (display) display.textContent = '00:00:00';
        if (statusText) statusText.textContent = 'STOPWATCH SESI LATIHAN (SIAP)';
        if (pulseDot) pulseDot.className = 'w-2.5 h-2.5 bg-slate inline-block';

        if (btnStart) {
          btnStart.textContent = '[ ▶ MULAI SESI ]';
          btnStart.classList.remove('hidden');
        }
        if (btnPause) btnPause.classList.add('hidden');
        if (btnFinish) btnFinish.classList.add('hidden');
      }
    }

    function startWorkoutTimer() {
      const isPaused = localStorage.getItem('naoolift_timer_paused') === 'true';
      if (!isPaused) {
        localStorage.setItem('naoolift_timer_start_time', Date.now());
        localStorage.setItem('naoolift_timer_accumulated', '0');
      } else {
        localStorage.setItem('naoolift_timer_start_time', Date.now());
        localStorage.setItem('naoolift_timer_paused', 'false');
      }

      localStorage.setItem('naoolift_timer_running', 'true');

      if (timerInterval) clearInterval(timerInterval);
      timerInterval = setInterval(updateWorkoutTimerUI, 1000);
      updateWorkoutTimerUI();
    }

    function pauseWorkoutTimer() {
      const isRunning = localStorage.getItem('naoolift_timer_running') === 'true';
      if (isRunning) {
        const startTime = parseInt(localStorage.getItem('naoolift_timer_start_time') || '0', 10);
        const accumulated = parseInt(localStorage.getItem('naoolift_timer_accumulated') || '0', 10);
        const elapsedSecs = Math.floor((Date.now() - startTime) / 1000) + accumulated;

        localStorage.setItem('naoolift_timer_accumulated', elapsedSecs);
        localStorage.setItem('naoolift_timer_running', 'false');
        localStorage.setItem('naoolift_timer_paused', 'true');
      }

      if (timerInterval) clearInterval(timerInterval);
      updateWorkoutTimerUI();
    }

    function resetWorkoutTimer() {
      localStorage.removeItem('naoolift_timer_start_time');
      localStorage.removeItem('naoolift_timer_running');
      localStorage.removeItem('naoolift_timer_paused');
      localStorage.removeItem('naoolift_timer_accumulated');

      if (timerInterval) clearInterval(timerInterval);
      updateWorkoutTimerUI();
    }

    function finishWorkoutTimer() {
      const isRunning = localStorage.getItem('naoolift_timer_running') === 'true';
      let elapsedSecs = parseInt(localStorage.getItem('naoolift_timer_accumulated') || '0', 10);

      if (isRunning) {
        const startTime = parseInt(localStorage.getItem('naoolift_timer_start_time') || '0', 10);
        elapsedSecs = Math.floor((Date.now() - startTime) / 1000) + elapsedSecs;
      }

      pauseWorkoutTimer();

      // Populate into add log modal
      const modalSecInput = document.getElementById('modal-duration-seconds');
      const modalBadge = document.getElementById('modal-duration-badge');
      const modalText = document.getElementById('modal-duration-text');

      if (modalSecInput) modalSecInput.value = elapsedSecs;
      if (modalBadge) modalBadge.classList.remove('hidden');
      if (modalText) modalText.textContent = formatTime(elapsedSecs);

      openAddLogModal();
    }

    // Initialize timer state on page load
    if (localStorage.getItem('naoolift_timer_running') === 'true') {
      timerInterval = setInterval(updateWorkoutTimerUI, 1000);
    }
    updateWorkoutTimerUI();

    function updateTime() {
      const now = new Date();
      const hrs = String(now.getHours()).padStart(2, '0');
      const mins = String(now.getMinutes()).padStart(2, '0');
      const secs = String(now.getSeconds()).padStart(2, '0');
      const formatted = `${hrs}:${mins}:${secs}`;

      const dtm = document.getElementById('dash-timer-mobile');
      if (dtm) dtm.textContent = formatted;
    }
    updateTime();
    setInterval(updateTime, 1000);

    function openAddLogModal() {
      const modal = document.getElementById('add-log-modal');
      if (modal) modal.classList.remove('hidden');
    }

    function closeAddLogModal() {
      const modal = document.getElementById('add-log-modal');
      if (modal) modal.classList.add('hidden');
    }

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
        closeAddLogModal();
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
