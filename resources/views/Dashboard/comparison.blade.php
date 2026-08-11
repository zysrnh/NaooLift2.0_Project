<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Halaman Perbandingan Performa</title>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="shortcut icon" href="/favicon.ico">
<link rel="manifest" href="/site.webmanifest">
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
    header, aside, nav, #toast-container, button, form, .no-print, .hero-header, .action-bar, .mode-selector-bar {
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
            SYS_COMPARISON v2.0
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
            PERBANDINGAN_PERFORMA
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

          <a href="/dashboard/stats" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[04] STATISTIK</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="/dashboard/comparison" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold">
            <span>[05] PERBANDINGAN</span>
            <span class="text-ember">●</span>
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

        <!-- PRINT ONLY CLEAN ARCHIVAL HEADER -->
        <div class="print-only-header border-b-[3px] border-black p-5 bg-black text-white font-mono">
          <div class="flex justify-between items-center">
            <h1 class="text-xl font-black uppercase tracking-tight">NAOOLIFT — ARSIP LAPORAN PERBANDINGAN PERFORMA</h1>
            <div class="text-xs text-amber-400 font-bold">COMPARISON_REPORT</div>
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
                05 // PERIODIC_COMPARISON_ANALYTICS
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                HALAMAN PERBANDINGAN
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Pilih mode perbandingan: Harian, Mingguan, Bulanan, atau Pilihan Tanggal Bebas (Tanggal A vs B).
              </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2 shrink-0">
              <button 
                onclick="window.print()"
                class="border-grid bg-canvas text-charcoal font-bold text-xs uppercase tracking-widest px-5 py-3.5 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
                title="Cetak Laporan PDF"
              >
                <span>[ CETAK LAPORAN PDF ]</span>
              </button>
            </div>
          </div>

          <!-- MODE SELECTOR TABS BAR (USER CHOICE SELECTOR) -->
          <div class="mode-selector-bar border-grid bg-light p-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 font-mono text-xs font-bold uppercase tracking-widest">
            <button 
              onclick="selectComparisonMode('daily')" 
              id="tab-daily"
              class="mode-btn p-3 border-grid text-center transition-none bg-charcoal text-canvas"
            >
              [01] HARI INI VS KEMARIN
            </button>
            <button 
              onclick="selectComparisonMode('weekly')" 
              id="tab-weekly"
              class="mode-btn p-3 border-grid text-center transition-none bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas"
            >
              [02] MINGGU INI VS KEMARIN
            </button>
            <button 
              onclick="selectComparisonMode('monthly')" 
              id="tab-monthly"
              class="mode-btn p-3 border-grid text-center transition-none bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas"
            >
              [03] BULAN INI VS KEMARIN
            </button>
            <button 
              onclick="selectComparisonMode('custom')" 
              id="tab-custom"
              class="mode-btn p-3 border-grid text-center transition-none bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas"
            >
              [04] TANGGAL A VS TANGGAL B
            </button>
          </div>

          <!-- COMPARISON BLOCK 1: HARI INI VS KEMARIN (DAILY) -->
          <div id="comp-block-daily" class="border-grid bg-canvas flex flex-col gap-4 p-5 sm:p-8 animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2">
              <div>
                <span class="font-mono text-[11px] font-bold text-ember uppercase tracking-widest">
                  OPSI 01 // DAILY_COMPARISON
                </span>
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  PERBANDINGAN: HARI INI VS KEMARIN
                </h3>
              </div>
              <div class="font-mono text-xs font-bold px-4 py-2 border-grid {{ $dailyVolDiff >= 0 ? 'bg-ember text-canvas' : 'bg-slate text-canvas' }}">
                STATUS: {{ $dailyVolDiff >= 0 ? 'NAIK' : 'TURUN' }} {{ $dailyVolDiff >= 0 ? '+' : '' }}{{ number_format($dailyVolDiff) }} KG ({{ $dailyVolPercent >= 0 ? '+' : '' }}{{ $dailyVolPercent }}%)
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-2">
              <!-- TODAY BOX -->
              <div class="border-grid bg-canvas p-6 flex flex-col justify-between gap-4 border-l-[8px] border-l-ember">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>HARI INI ({{ $todayName }})</span>
                  <span class="text-ember font-black">● LIVE</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">TOTAL VOLUMETRIK</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-charcoal">
                    {{ number_format($todayVol) }} <span class="text-lg text-slate">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL GERAKAN</span>
                    <span class="text-base font-black">{{ $todayExercises }} GERAKAN</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL SET</span>
                    <span class="text-base font-black">{{ $todaySets }} SET</span>
                  </div>
                </div>
              </div>

              <!-- YESTERDAY BOX -->
              <div class="border-grid bg-light p-6 flex flex-col justify-between gap-4">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>KEMARIN ({{ date('d/m/Y', strtotime('-1 day')) }})</span>
                  <span class="text-slate font-bold">PREVIOUS_DAY</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">TOTAL VOLUMETRIK</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-charcoal">
                    {{ number_format($yesterdayVol) }} <span class="text-lg text-slate">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL GERAKAN</span>
                    <span class="text-base font-black">{{ $yesterdayExercises }} GERAKAN</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL SET</span>
                    <span class="text-base font-black">{{ $yesterdaySets }} SET</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- COMPARISON BLOCK 2: MINGGU INI VS MINGGU KEMARIN (WEEKLY) -->
          <div id="comp-block-weekly" class="border-grid bg-canvas flex flex-col gap-4 p-5 sm:p-8 hidden animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2">
              <div>
                <span class="font-mono text-[11px] font-bold text-ember uppercase tracking-widest">
                  OPSI 02 // WEEKLY_VOLUME_COMPARISON
                </span>
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  PERBANDINGAN: MINGGU INI VS MINGGU KEMARIN
                </h3>
              </div>
              <div class="font-mono text-xs font-bold px-4 py-2 border-grid {{ $weeklyVolDiff >= 0 ? 'bg-ember text-canvas' : 'bg-slate text-canvas' }}">
                STATUS: {{ $weeklyVolDiff >= 0 ? 'NAIK' : 'TURUN' }} {{ $weeklyVolDiff >= 0 ? '+' : '' }}{{ number_format($weeklyVolDiff) }} KG ({{ $weeklyVolPercent >= 0 ? '+' : '' }}{{ $weeklyVolPercent }}%)
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-2">
              <!-- THIS WEEK BOX -->
              <div class="border-grid bg-canvas p-6 flex flex-col justify-between gap-4 border-l-[8px] border-l-charcoal">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>MINGGU INI</span>
                  <span class="text-charcoal font-black">CURRENT_WEEK</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">AKUMULASI VOLUMETRIK</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-charcoal">
                    {{ number_format($thisWeekVol) }} <span class="text-lg text-slate">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">HARI AKTIF LATIHAN</span>
                    <span class="text-base font-black">{{ $thisWeekActiveDays }} HARI</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL VOLUME SET</span>
                    <span class="text-base font-black">{{ $thisWeekSets }} SET</span>
                  </div>
                </div>
              </div>

              <!-- LAST WEEK BOX -->
              <div class="border-grid bg-light p-6 flex flex-col justify-between gap-4">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>MINGGU KEMARIN</span>
                  <span class="text-slate font-bold">PREVIOUS_WEEK</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">AKUMULASI VOLUMETRIK</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-charcoal">
                    {{ number_format($lastWeekVol) }} <span class="text-lg text-slate">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">HARI AKTIF LATIHAN</span>
                    <span class="text-base font-black">{{ $lastWeekActiveDays }} HARI</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL VOLUME SET</span>
                    <span class="text-base font-black">{{ $lastWeekSets }} SET</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- COMPARISON BLOCK 3: BULAN INI VS BULAN KEMARIN (MONTHLY) -->
          <div id="comp-block-monthly" class="border-grid bg-canvas flex flex-col gap-4 p-5 sm:p-8 hidden animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2">
              <div>
                <span class="font-mono text-[11px] font-bold text-ember uppercase tracking-widest">
                  OPSI 03 // MONTHLY_CONSISTENCY_COMPARISON
                </span>
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  PERBANDINGAN: {{ $thisMonthLabel }} VS {{ $lastMonthLabel }}
                </h3>
              </div>
              <div class="font-mono text-xs font-bold px-4 py-2 border-grid {{ $monthlyVolDiff >= 0 ? 'bg-ember text-canvas' : 'bg-slate text-canvas' }}">
                STATUS: {{ $monthlyVolDiff >= 0 ? 'NAIK' : 'TURUN' }} {{ $monthlyVolDiff >= 0 ? '+' : '' }}{{ number_format($monthlyVolDiff) }} KG ({{ $monthlyVolPercent >= 0 ? '+' : '' }}{{ $monthlyVolPercent }}%)
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-2">
              <!-- THIS MONTH BOX -->
              <div class="border-grid bg-canvas p-6 flex flex-col justify-between gap-4 border-l-[8px] border-l-ember">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>BULAN INI ({{ $thisMonthLabel }})</span>
                  <span class="text-ember font-black">CURRENT_MONTH</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">TOTAL TONASE BULANAN</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-ember">
                    {{ number_format($thisMonthVol) }} <span class="text-lg text-charcoal">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">KONSISTENSI HARI</span>
                    <span class="text-base font-black">{{ $thisMonthActiveDays }} HARI LATIHAN</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL SET LOGGED</span>
                    <span class="text-base font-black">{{ $thisMonthSets }} SET</span>
                  </div>
                </div>
              </div>

              <!-- LAST MONTH BOX -->
              <div class="border-grid bg-light p-6 flex flex-col justify-between gap-4">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>BULAN KEMARIN ({{ $lastMonthLabel }})</span>
                  <span class="text-slate font-bold">PREVIOUS_MONTH</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">TOTAL TONASE BULANAN</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-charcoal">
                    {{ number_format($lastMonthVol) }} <span class="text-lg text-slate">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">KONSISTENSI HARI</span>
                    <span class="text-base font-black">{{ $lastMonthActiveDays }} HARI LATIHAN</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL SET LOGGED</span>
                    <span class="text-base font-black">{{ $lastMonthSets }} SET</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- COMPARISON BLOCK 4: CUSTOM TANGGAL A VS TANGGAL B -->
          <div id="comp-block-custom" class="border-grid bg-canvas flex flex-col gap-4 p-5 sm:p-8 hidden animate-fade-in">
            
            <!-- CUSTOM DATE SELECTOR FORM -->
            <form action="/dashboard/comparison" method="GET" class="border-grid bg-light p-4 flex flex-col md:flex-row items-center justify-between gap-4">
              <input type="hidden" name="mode" value="custom">

              <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                  <span class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal whitespace-nowrap">TANGGAL A:</span>
                  <input 
                    type="date" 
                    name="date_a" 
                    value="{{ $customDateA }}"
                    required
                    class="bg-canvas border-grid p-2 font-mono text-xs font-bold uppercase text-charcoal focus:outline-none focus:border-ember cursor-pointer"
                  >
                </div>

                <span class="font-mono text-xs font-black text-ember">VS</span>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                  <span class="font-mono text-xs font-bold uppercase tracking-widest text-charcoal whitespace-nowrap">TANGGAL B:</span>
                  <input 
                    type="date" 
                    name="date_b" 
                    value="{{ $customDateB }}"
                    required
                    class="bg-canvas border-grid p-2 font-mono text-xs font-bold uppercase text-charcoal focus:outline-none focus:border-ember cursor-pointer"
                  >
                </div>
              </div>

              <button 
                type="submit" 
                class="w-full md:w-auto border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-5 py-2.5 hover:bg-charcoal transition-none active:translate-y-1"
              >
                BANDINGKAN DUA TANGGAL →
              </button>
            </form>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2 mt-2">
              <div>
                <span class="font-mono text-[11px] font-bold text-ember uppercase tracking-widest">
                  OPSI 04 // CUSTOM_DATE_COMPARISON
                </span>
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  PERBANDINGAN: {{ date('d/m/Y', strtotime($customDateA)) }} VS {{ date('d/m/Y', strtotime($customDateB)) }}
                </h3>
              </div>
              <div class="font-mono text-xs font-bold px-4 py-2 border-grid {{ $customVolDiff >= 0 ? 'bg-ember text-canvas' : 'bg-slate text-canvas' }}">
                STATUS: {{ $customVolDiff >= 0 ? 'NAIK' : 'TURUN' }} {{ $customVolDiff >= 0 ? '+' : '' }}{{ number_format($customVolDiff) }} KG ({{ $customVolPercent >= 0 ? '+' : '' }}{{ $customVolPercent }}%)
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-2">
              <!-- DATE A BOX -->
              <div class="border-grid bg-canvas p-6 flex flex-col justify-between gap-4 border-l-[8px] border-l-ember">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>TANGGAL A ({{ date('d F Y', strtotime($customDateA)) }})</span>
                  <span class="text-ember font-black">DATE_A</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">TOTAL VOLUMETRIK</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-charcoal">
                    {{ number_format($customVolA) }} <span class="text-lg text-slate">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL GERAKAN</span>
                    <span class="text-base font-black">{{ $customExercisesA }} GERAKAN</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL SET</span>
                    <span class="text-base font-black">{{ $customSetsA }} SET</span>
                  </div>
                </div>
              </div>

              <!-- DATE B BOX -->
              <div class="border-grid bg-light p-6 flex flex-col justify-between gap-4">
                <div class="flex justify-between items-center font-mono text-xs font-bold uppercase tracking-widest text-slate border-b-grid pb-3">
                  <span>TANGGAL B ({{ date('d F Y', strtotime($customDateB)) }})</span>
                  <span class="text-slate font-bold">DATE_B</span>
                </div>
                
                <div class="flex flex-col gap-1">
                  <span class="font-mono text-[11px] font-bold text-slate uppercase">TOTAL VOLUMETRIK</span>
                  <div class="font-mono text-4xl sm:text-5xl font-black text-charcoal">
                    {{ number_format($customVolB) }} <span class="text-lg text-slate">KG</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-charcoal border-t-grid pt-3 font-bold">
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL GERAKAN</span>
                    <span class="text-base font-black">{{ $customExercisesB }} GERAKAN</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-slate block uppercase">TOTAL SET</span>
                    <span class="text-base font-black">{{ $customSetsB }} SET</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PRINT ONLY CLEAN COMPACT DATA TABLE FOR COMPARISON -->
          <div class="print-only-table font-mono text-xs">
            <table class="w-full border-collapse border-[2px] border-black">
              <thead>
                <tr class="bg-black text-white uppercase text-[11px]">
                  <th class="border-[2px] border-black p-2 text-left">PERIODE PERBANDINGAN</th>
                  <th class="border-[2px] border-black p-2 text-right">VOLUMETRIK SEKARANG (KG)</th>
                  <th class="border-[2px] border-black p-2 text-right">VOLUMETRIK SEBELUM (KG)</th>
                  <th class="border-[2px] border-black p-2 text-right">SELISIH DELTA</th>
                  <th class="border-[2px] border-black p-2 text-center">PERSENTASE</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-[1.5px] border-black font-bold">
                  <td class="border-[1.5px] border-black p-2 font-black">1. HARI INI VS KEMARIN</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($todayVol) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($yesterdayVol) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ $dailyVolDiff >= 0 ? '+' : '' }}{{ number_format($dailyVolDiff) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-center" style="color: #9A4A2E;">{{ $dailyVolPercent >= 0 ? '+' : '' }}{{ $dailyVolPercent }}%</td>
                </tr>
                <tr class="border-[1.5px] border-black font-bold">
                  <td class="border-[1.5px] border-black p-2 font-black">2. MINGGU INI VS MINGGU KEMARIN</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($thisWeekVol) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($lastWeekVol) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ $weeklyVolDiff >= 0 ? '+' : '' }}{{ number_format($weeklyVolDiff) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-center" style="color: #9A4A2E;">{{ $weeklyVolPercent >= 0 ? '+' : '' }}{{ $weeklyVolPercent }}%</td>
                </tr>
                <tr class="border-[1.5px] border-black font-bold">
                  <td class="border-[1.5px] border-black p-2 font-black">3. {{ $thisMonthLabel }} VS {{ $lastMonthLabel }}</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($thisMonthVol) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($lastMonthVol) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ $monthlyVolDiff >= 0 ? '+' : '' }}{{ number_format($monthlyVolDiff) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-center" style="color: #9A4A2E;">{{ $monthlyVolPercent >= 0 ? '+' : '' }}{{ $monthlyVolPercent }}%</td>
                </tr>
                <tr class="border-[1.5px] border-black font-bold">
                  <td class="border-[1.5px] border-black p-2 font-black">4. CUSTOM: {{ date('d/m/Y', strtotime($customDateA)) }} VS {{ date('d/m/Y', strtotime($customDateB)) }}</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($customVolA) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ number_format($customVolB) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-right">{{ $customVolDiff >= 0 ? '+' : '' }}{{ number_format($customVolDiff) }} KG</td>
                  <td class="border-[1.5px] border-black p-2 text-center" style="color: #9A4A2E;">{{ $customVolPercent >= 0 ? '+' : '' }}{{ $customVolPercent }}%</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

      </main>

    </div>

    <!-- FULL-WIDTH SWISS BRUTALIST FOOTER BAR -->
    <footer class="border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
      <div>NAOOLIFT SYSTEM © 2026</div>
      <div class="text-slate">MODULE: PERIODIC_COMPARISON_ANALYTICS</div>
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
    <a href="/dashboard/comparison" class="py-3 bg-ember text-canvas flex flex-col items-center justify-center gap-0.5">
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

    function selectComparisonMode(mode) {
      const dailyBlock = document.getElementById('comp-block-daily');
      const weeklyBlock = document.getElementById('comp-block-weekly');
      const monthlyBlock = document.getElementById('comp-block-monthly');
      const customBlock = document.getElementById('comp-block-custom');

      const dailyBtn = document.getElementById('tab-daily');
      const weeklyBtn = document.getElementById('tab-weekly');
      const monthlyBtn = document.getElementById('tab-monthly');
      const customBtn = document.getElementById('tab-custom');

      // Reset blocks
      if (dailyBlock) dailyBlock.classList.add('hidden');
      if (weeklyBlock) weeklyBlock.classList.add('hidden');
      if (monthlyBlock) monthlyBlock.classList.add('hidden');
      if (customBlock) customBlock.classList.add('hidden');

      // Reset buttons styling
      const activeClass = 'mode-btn p-3 border-grid text-center transition-none bg-charcoal text-canvas';
      const inactiveClass = 'mode-btn p-3 border-grid text-center transition-none bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas';

      if (dailyBtn) dailyBtn.className = inactiveClass;
      if (weeklyBtn) weeklyBtn.className = inactiveClass;
      if (monthlyBtn) monthlyBtn.className = inactiveClass;
      if (customBtn) customBtn.className = inactiveClass;

      if (mode === 'daily') {
        if (dailyBlock) dailyBlock.classList.remove('hidden');
        if (dailyBtn) dailyBtn.className = activeClass;
      } else if (mode === 'weekly') {
        if (weeklyBlock) weeklyBlock.classList.remove('hidden');
        if (weeklyBtn) weeklyBtn.className = activeClass;
      } else if (mode === 'monthly') {
        if (monthlyBlock) monthlyBlock.classList.remove('hidden');
        if (monthlyBtn) monthlyBtn.className = activeClass;
      } else if (mode === 'custom') {
        if (customBlock) customBlock.classList.remove('hidden');
        if (customBtn) customBtn.className = activeClass;
      }
    }

    // Initialize initial active mode from server or default
    const initialMode = "{{ $activeMode }}";
    if (initialMode && initialMode !== 'daily') {
      selectComparisonMode(initialMode);
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
