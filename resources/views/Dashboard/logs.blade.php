<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Log Sesi Latihan</title>
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
            LOG_SESI_LATIHAN
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

          <!-- Hero Section Header -->
          <div class="border-b-[3px] border-charcoal pb-4 sm:pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
              <span class="font-mono text-[11px] sm:text-xs font-bold uppercase tracking-widest text-ember">
                03 // DATE_BASED_WORKOUT_LOGS
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                LOG SESI LATIHAN
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Catat beban berat, set, dan repetisi gerakan latihan Anda berdasarkan tanggal.
              </p>
            </div>
            
            <button 
              onclick="openAddLogModal()"
              class="border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-5 py-3.5 hover:bg-charcoal transition-none active:translate-y-1 shrink-0 flex items-center justify-center gap-2"
            >
              <span>+ CATAT GERAKAN LATIHAN</span>
            </button>
          </div>

          <!-- DATE SELECTOR ACTION BAR -->
          <div class="border-grid bg-light p-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="/dashboard/logs" method="GET" class="flex items-center gap-2 w-full md:w-auto">
              <span class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal whitespace-nowrap">
                PILIH TANGGAL:
              </span>
              <input 
                type="date" 
                name="date"
                value="{{ $selectedDate }}"
                onchange="this.form.submit()"
                class="bg-canvas border-grid p-2 font-mono text-xs font-bold uppercase text-charcoal focus:outline-none focus:border-ember cursor-pointer flex-1 md:flex-none"
              >
              <button type="submit" class="border-grid bg-charcoal text-canvas font-bold text-xs uppercase tracking-widest px-3 py-2 hover:bg-ember transition-none">
                CARI
              </button>
            </form>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
              <a href="/dashboard/logs?date={{ date('Y-m-d') }}" class="border-grid bg-canvas px-3 py-1.5 font-mono text-[11px] font-bold uppercase text-charcoal hover:bg-charcoal hover:text-canvas transition-none">
                [ HARI INI ]
              </a>
              <a href="/dashboard/logs?date={{ date('Y-m-d', strtotime('-1 day')) }}" class="border-grid bg-canvas px-3 py-1.5 font-mono text-[11px] font-bold uppercase text-charcoal hover:bg-charcoal hover:text-canvas transition-none">
                [ KEMARIN ]
              </a>
            </div>
          </div>

          <!-- CONNECTED ROUTINE HIGHLIGHT STRIP -->
          <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
              <span class="font-mono text-[10px] font-bold text-slate uppercase tracking-widest">
                TANGGAL DILIHAT: {{ date('d F Y', strtotime($selectedDate)) }} ({{ $dayNameId }})
              </span>
              <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                ROUTINE: {{ $scheduledRoutine ? $scheduledRoutine->title : 'LATIHAN BEBAS / UNSET' }}
              </h3>
              @if($scheduledRoutine && $scheduledRoutine->focus_target)
                <div class="font-mono text-xs font-bold text-ember uppercase tracking-wider mt-0.5">
                  FOKUS OTOT TERJADWAL: {{ $scheduledRoutine->focus_target }}
                </div>
              @endif
            </div>

            @if($scheduledRoutine)
              @if($scheduledRoutine->is_rest)
                <span class="font-mono text-xs bg-slate text-canvas px-3 py-1.5 font-bold uppercase border-grid">
                  TERJADWAL: REST DAY
                </span>
              @else
                <span class="font-mono text-xs bg-ember text-canvas px-3 py-1.5 font-bold uppercase border-grid">
                  TERJADWAL: WORKOUT
                </span>
              @endif
            @else
              <span class="font-mono text-xs bg-light text-slate px-3 py-1.5 font-bold uppercase border-grid">
                BELUM ADA JADWAL DITETAPKAN
              </span>
            @endif
          </div>

          <!-- Date Summary Metrics -->
          <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
            <!-- Metric 1 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL GERAKAN
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                {{ $totalExercises }} <span class="text-sm sm:text-lg text-slate">GERAKAN</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                EXERCISES LOGGED
              </div>
            </div>

            <!-- Metric 2 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL SET & REPS
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-charcoal">
                {{ $totalSets }} <span class="text-sm sm:text-lg text-slate">SET</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                TOTAL VOLUME SETS
              </div>
            </div>

            <!-- Metric 3 -->
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4 col-span-2 lg:col-span-1">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                VOLUMETRIK LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-5xl font-bold text-ember">
                {{ number_format($totalVolumeKg) }} <span class="text-sm sm:text-lg text-charcoal">KG</span>
              </div>
              <div class="font-mono text-[9px] sm:text-[10px] font-bold text-slate uppercase tracking-wider border-t-grid pt-2">
                TOTAL WEIGHT LOAD
              </div>
            </div>
          </div>

          <!-- LOGGED EXERCISES LIST TABLE / STACK -->
          <div class="flex flex-col gap-4 mt-2">
            <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
              <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tighter text-charcoal">
                CATATAN LATIHAN TANGGAL {{ date('d/m/Y', strtotime($selectedDate)) }}
              </h3>
              <span class="font-mono text-xs font-bold text-ember uppercase tracking-widest">
                {{ $logs->count() }} ENTRI DICATAT
              </span>
            </div>

            @if($logs->count() > 0)
              <div class="flex flex-col gap-3">
                @foreach($logs as $index => $log)
                  <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-ember transition-colors">
                    
                    <div class="flex items-start gap-4">
                      <div class="w-10 h-10 border-grid bg-charcoal text-canvas flex items-center justify-center font-mono font-bold text-sm shrink-0">
                        0{{ $index + 1 }}
                      </div>

                      <div class="flex flex-col gap-1">
                        <h4 class="text-lg sm:text-xl font-black uppercase tracking-tight text-charcoal">
                          {{ $log->exercise_name }}
                        </h4>
                        
                        <div class="font-mono text-xs font-bold text-ember uppercase tracking-wider flex items-center gap-3">
                          <span>BEBAN: {{ number_format($log->weight_kg, 1) }} KG</span>
                          <span>•</span>
                          <span>SET: {{ $log->sets }} SET</span>
                          <span>•</span>
                          <span>REPS: {{ $log->reps }} REPS</span>
                        </div>

                        @if($log->notes)
                          <p class="text-xs font-semibold text-slate mt-1">
                            Catatan: {{ $log->notes }}
                          </p>
                        @endif
                      </div>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end border-t-grid md:border-t-0 pt-3 md:pt-0">
                      <div class="font-mono text-right">
                        <span class="text-[10px] text-slate font-bold block uppercase">TOTAL VOLUMETRIK</span>
                        <span class="text-base font-bold text-charcoal uppercase">
                          {{ number_format($log->sets * $log->reps * $log->weight_kg) }} KG
                        </span>
                      </div>

                      <form action="/dashboard/logs/delete" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="log_id" value="{{ $log->id }}">
                        <button 
                          type="submit" 
                          class="border-grid bg-canvas text-ember font-bold text-xs uppercase tracking-widest px-3 py-2 hover:bg-ember hover:text-canvas transition-none active:translate-y-1"
                          title="Hapus Log"
                        >
                          ✕ HAPUS
                        </button>
                      </form>
                    </div>

                  </div>
                @endforeach
              </div>
            @else
              <!-- Clean Empty State Container -->
              <div class="border-grid bg-light p-8 sm:p-16 flex flex-col items-center justify-center text-center gap-4 my-2 min-h-[260px]">
                <div class="w-12 h-12 border-grid bg-charcoal text-canvas flex items-center justify-center font-mono font-black text-xl mb-1">
                  ✎
                </div>
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tighter text-charcoal">
                  BELUM ADA CATATAN LATIHAN PADA TANGGAL INI
                </h3>
                <p class="text-xs sm:text-sm font-semibold text-slate max-w-md">
                  Tekan tombol "+ CATAT GERAKAN LATIHAN" untuk memasukkan set, reps, dan beban berat latihan Anda pada tanggal ini.
                </p>
                <button 
                  onclick="openAddLogModal()"
                  class="border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-6 py-3.5 hover:bg-charcoal transition-none active:translate-y-1 mt-2"
                >
                  + CATAT GERAKAN LATIHAN SEKARANG
                </button>
              </div>
            @endif

          </div>

        </div>

      </main>

    </div>

    <!-- FULL-WIDTH SWISS BRUTALIST FOOTER BAR -->
    <footer class="border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
      <div>NAOOLIFT SYSTEM © 2026</div>
      <div class="text-slate">MODULE: DATE_BASED_WORKOUT_LOGS</div>
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
    <a href="/dashboard/logs" class="py-3 bg-ember text-canvas border-r-grid flex flex-col items-center justify-center gap-0.5">
      <span class="text-xs font-black">✎</span>
      <span>LOG</span>
    </a>
    <a href="/" class="py-3 text-canvas hover:bg-light hover:text-charcoal flex flex-col items-center justify-center gap-0.5 transition-none">
      <span class="text-xs font-black">←</span>
      <span>LANDING</span>
    </a>
  </nav>

  <!-- SWISS BRUTALIST ADD WORKOUT LOG MODAL -->
  <div id="log-modal" class="fixed inset-0 z-[100] bg-charcoal/80 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-[480px] border-grid bg-canvas p-6 sm:p-8 flex flex-col gap-4 shadow-none relative animate-fade-in my-auto">
      
      <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
        <div>
          <h3 class="font-black text-xl uppercase tracking-tighter text-charcoal">
            CATAT GERAKAN LATIHAN
          </h3>
          <div class="font-mono text-xs font-bold text-ember uppercase tracking-widest">
            MODUL LOG SESI LATIHAN
          </div>
        </div>
        <button onclick="closeLogModal()" class="font-mono text-xs font-bold text-charcoal hover:text-ember">[✕]</button>
      </div>

      <form action="/dashboard/logs" method="POST" class="space-y-4">
        @csrf

        <!-- Field 0: Tanggal Latihan -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            01 / TANGGAL LATIHAN
          </label>
          <input 
            type="date" 
            name="log_date" 
            value="{{ $selectedDate }}"
            required 
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold uppercase focus:bg-canvas focus:outline-none focus:border-ember transition-colors cursor-pointer"
          >
        </div>

        <!-- Field 1: Nama Gerakan Latihan -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            02 / NAMA GERAKAN LATIHAN (EXERCISE)
          </label>
          <input 
            type="text" 
            name="exercise_name" 
            required 
            placeholder="CONTOH: BENCH PRESS / INCLINE DUMBBELL PRESS"
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold uppercase focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
          >
        </div>

        <!-- Field 2: Set, Reps, Beban (KG) Grid -->
        <div class="grid grid-cols-3 gap-3">
          <div class="flex flex-col gap-1">
            <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
              SET
            </label>
            <input 
              type="number" 
              name="sets" 
              value="4"
              min="1"
              required 
              class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold text-center focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
            >
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
              REPS
            </label>
            <input 
              type="number" 
              name="reps" 
              value="10"
              min="1"
              required 
              class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold text-center focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
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
              placeholder="60.0"
              required 
              class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-bold text-center focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
            >
          </div>
        </div>

        <!-- Field 3: Catatan Log -->
        <div class="flex flex-col gap-1">
          <label class="font-mono text-[11px] font-bold uppercase tracking-widest text-charcoal">
            03 / CATATAN PROGRES / EVALUASI (OPSIONAL)
          </label>
          <textarea 
            name="notes" 
            rows="3"
            placeholder="CONTOH: Set 4 terasa berat, tembus rekor 60kg."
            class="w-full bg-light border-grid p-2.5 font-mono text-xs text-charcoal font-semibold focus:bg-canvas focus:outline-none focus:border-ember transition-colors"
          ></textarea>
        </div>

        <!-- Modal Action Buttons -->
        <div class="flex gap-3 pt-3 border-t-[3px] border-charcoal">
          <button 
            type="button" 
            onclick="closeLogModal()" 
            class="flex-1 border-[3px] border-charcoal bg-light text-charcoal font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
          >
            BATAL
          </button>
          <button 
            type="submit" 
            class="flex-1 border-[3px] border-charcoal bg-ember text-canvas font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal transition-none active:translate-y-1"
          >
            SIMPAN LOG →
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

    function openAddLogModal() {
      const modal = document.getElementById('log-modal');
      if (modal) modal.classList.remove('hidden');
    }

    function closeLogModal() {
      const modal = document.getElementById('log-modal');
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
        closeLogModal();
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
