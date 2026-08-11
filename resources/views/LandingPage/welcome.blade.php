<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Utilitarian Workout Log System</title>
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

  /* Reset all rounded corners and shadows universally for Swiss Brutalism per rule.md */
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

  /* Sharp brutal hover states */
  .hover-invert {
    transition: background-color 0.1s ease, color 0.1s ease;
  }
  .hover-invert:hover {
    background-color: #1C1C1C;
    color: #EAE6E0;
  }

  /* Blinking Indicator */
  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.2; }
  }
  .animate-blink {
    animation: blink 1.2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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

  /* Smooth Brutalist Modal Pop & Step Switcher Animations */
  @keyframes modalPopIn {
    from {
      opacity: 0;
      transform: scale(0.94) translateY(12px);
    }
    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }
  .animate-modal-pop {
    animation: modalPopIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }

  @keyframes stepFadeIn {
    from {
      opacity: 0;
      transform: translateX(12px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  .animate-step-in {
    animation: stepFadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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

  <!-- Main Container: Acts like a printed sheet of journal paper -->
  <div class="w-full max-w-[1280px] border-grid flex flex-col relative bg-canvas shadow-none">
    
    <!-- TOP NAV / HEADER -->
    <header class="flex flex-col md:flex-row border-b-grid">
      
      <!-- Logo Block -->
      <div class="p-4 sm:p-6 md:w-1/4 border-b-grid md:border-b-0 md:border-r-grid bg-charcoal text-canvas flex items-center justify-between">
        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tighter">NAOOLIFT.LOG</h1>
      </div>
      
      <!-- Nav Links -->
      <div class="flex flex-row flex-1 font-mono text-xs font-bold uppercase tracking-widest text-charcoal border-b-grid md:border-b-0">
        <a href="#fitur" class="flex-1 p-4 sm:p-6 border-r-grid hover-invert flex items-center justify-center transition-none">
          [01] FITUR UTAMA
        </a>
        <a href="#modul" class="flex-1 p-4 sm:p-6 border-r-grid hover-invert flex items-center justify-center transition-none">
          [02] MODUL SISTEM
        </a>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row md:w-1/3">
        @if(session('user'))
          <a href="/dashboard" class="flex-1 p-4 sm:p-6 bg-light text-charcoal font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-charcoal hover:text-canvas transition-none flex items-center justify-center border-b-grid sm:border-b-0 sm:border-r-grid active:translate-y-1">
            DASHBOARD →
          </a>
          <a href="/admin/dashboard" class="flex-1 p-4 sm:p-6 bg-ember text-canvas font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-charcoal transition-none flex items-center justify-center border-b-grid sm:border-b-0 sm:border-r-grid active:translate-y-1">
            ADMIN →
          </a>
          <button onclick="openLogoutModal()" class="p-4 sm:p-6 bg-charcoal text-canvas font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-ember transition-none flex items-center justify-center active:translate-y-1">
            KELUAR [✕]
          </button>
        @else
          <a href="/login" class="flex-1 p-4 sm:p-6 bg-light text-charcoal font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-charcoal hover:text-canvas transition-none flex items-center justify-center border-b-grid sm:border-b-0 sm:border-r-grid active:translate-y-1">
            MASUK →
          </a>
          <a href="/register" class="flex-1 p-4 sm:p-6 bg-ember text-canvas font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-charcoal transition-none flex items-center justify-center active:translate-y-1">
            REGISTRASI →
          </a>
        @endif
      </div>
    </header>

    <!-- INFO STRIP -->
    <div class="flex flex-col md:flex-row border-b-grid text-xs sm:text-sm font-bold uppercase tracking-widest text-charcoal">
      <div class="flex-1 p-4 border-b-grid md:border-b-0 md:border-r-grid flex justify-between items-center">
        <span>STATUS SISTEM:</span>
        <span class="text-ember flex items-center gap-1.5 font-mono">
          <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
          ACTIVE_DEVELOPMENT
        </span>
      </div>
      <div class="flex-1 p-4 border-b-grid md:border-b-0 md:border-r-grid flex justify-between items-center">
        <span>ARSITEKTUR:</span>
        <span class="font-mono">PROGRESSIVE OVERLOAD LOG</span>
      </div>
      <div class="flex-1 p-4 flex justify-between items-center bg-light">
        <span>SESI AKTIF:</span>
        <span class="font-mono @if(!session('user')) text-slate @endif">
          {{ session('user') ?? 'UNAUTHENTICATED' }}
        </span>
      </div>
    </div>

    <!-- HERO SECTION (Split Screen) -->
    <section class="flex flex-col lg:flex-row border-b-grid">
      
      <!-- Left Hero Typography & Actions -->
      <div class="lg:w-2/3 p-6 sm:p-12 lg:p-16 border-b-grid lg:border-b-0 lg:border-r-grid flex flex-col justify-center bg-light">
        <div class="font-mono text-xs uppercase tracking-widest mb-6 border-b-[3px] border-charcoal pb-2 inline-block w-max font-bold text-ember">
          DOC_REF: NAOOLIFT-SYS-2026
        </div>
        
        <h2 class="text-4xl sm:text-7xl lg:text-[7.5rem] font-black uppercase leading-[0.88] tracking-tighter mb-6 text-charcoal">
          RECORD<br>
          EVERY<br>
          <span class="text-ember">REP.</span>
        </h2>
        
        <p class="text-sm sm:text-base font-semibold max-w-lg border-l-[6px] border-ember pl-4 text-charcoal leading-relaxed mb-8">
          Sistem jurnal kebugaran utilitarian. Tanpa gangguan visual. Fokus penuh pada metrik tonase beban, durasi waktu background, dan perbandingan harian / mingguan / bulanan.
        </p>

        <div class="flex flex-wrap items-center gap-3">
          <a 
            href="/dashboard" 
            class="border-[3px] border-charcoal bg-ember text-canvas font-bold text-xs sm:text-sm uppercase tracking-widest px-8 py-4 hover:bg-charcoal transition-none active:translate-y-1"
          >
            BUKA DASBOR LATIHAN →
          </a>
          <button 
            onclick="openFeedbackModal(2)" 
            class="border-[3px] border-charcoal bg-canvas text-charcoal font-bold text-xs sm:text-sm uppercase tracking-widest px-8 py-4 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
          >
            ISI SARAN & MASUKAN
          </button>
        </div>
      </div>

      <!-- Right Real-time Stats Grid -->
      <div class="lg:w-1/3 flex flex-col">
        
        <!-- Live Stopwatch Preview Block -->
        <div class="p-6 sm:p-10 flex-1 border-b-grid flex flex-col justify-center bg-canvas hover:bg-light transition-none">
          <div class="flex justify-between items-end mb-6 text-charcoal">
            <span class="text-xs sm:text-sm font-bold uppercase tracking-widest">01 / BACKGROUND TIMER</span>
            <div class="font-mono text-xs font-bold bg-charcoal text-canvas px-2 py-1 flex items-center gap-1.5">
              <span class="w-2 h-2 bg-ember animate-blink inline-block"></span>
              <span>REC</span>
            </div>
          </div>
          <div id="live-timer" class="font-mono text-4xl sm:text-6xl font-black tracking-tighter text-ember">
            01:12:04
          </div>
          <span class="font-mono text-[10px] text-slate font-bold uppercase mt-2">RUNS PERSISTENT IN BACKGROUND</span>
        </div>

        <!-- System Operational Status Block -->
        <div class="p-6 sm:p-10 flex-1 flex flex-col justify-center bg-charcoal text-canvas hover:bg-ember transition-none cursor-default">
          <div class="flex justify-between items-center mb-4 sm:mb-6">
            <span class="text-xs sm:text-sm font-bold uppercase tracking-widest">02 / STATUS SISTEM</span>
            <span class="font-mono text-[10px] bg-canvas text-charcoal px-2 py-0.5 font-bold uppercase flex items-center gap-1.5 border-grid">
              <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
              ONLINE
            </span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="font-mono text-5xl sm:text-7xl font-black tracking-tighter leading-none text-canvas">v2.0</span>
            <span class="text-lg sm:text-xl font-bold uppercase tracking-widest text-light">OPERATIONAL</span>
          </div>
        </div>

      </div>
    </section>

    <!-- 3 CLEAN CORE FEATURE MODULES SECTION -->
    <section id="fitur" class="grid grid-cols-1 md:grid-cols-3 border-b-grid">
      
      <!-- Module 1: Volume Load & Stopwatch -->
      <div class="p-6 sm:p-8 border-b-grid md:border-b-0 md:border-r-grid flex flex-col bg-canvas">
        <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest mb-1">FITUR 01 // LOAD & TIMER</span>
        <h3 class="font-black text-2xl sm:text-3xl uppercase tracking-tighter mb-3 text-charcoal">VOLUME LOAD & STOPWATCH</h3>
        <p class="text-xs sm:text-sm font-semibold mb-8 pb-4 border-b-[3px] border-charcoal text-slate">
          Penghitungan tonase beban (Set × Reps × KG) serta Stopwatch background yang tidak mati saat tab ditutup.
        </p>
        
        <div class="mt-auto">
          <div class="font-mono text-xs uppercase mb-2 flex justify-between font-bold text-charcoal">
            <span>CUR_WEEK_LOAD</span>
            <span class="text-ember">4,200 KG</span>
          </div>
          <div class="w-full h-6 border-grid p-[2px] flex bg-light">
            <div class="h-full bg-charcoal w-[75%] transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Module 2: Weekly Schedule & Multi-Period Logs -->
      <div class="p-6 sm:p-8 border-b-grid md:border-b-0 md:border-r-grid flex flex-col bg-canvas">
        <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest mb-1">FITUR 02 // LOGS & SCHEDULE</span>
        <h3 class="font-black text-2xl sm:text-3xl uppercase tracking-tighter mb-3 text-charcoal">JADWAL & MULTI-PERIODE LOG</h3>
        <p class="text-xs sm:text-sm font-semibold mb-8 pb-4 border-b-[3px] border-charcoal text-slate">
          Penjadwalan program bulanan dan filter tampilan log: Harian, Minggu Ini, Bulan Ini, & Semua Riwayat.
        </p>
        
        <div class="mt-auto">
          <div class="h-28 flex items-end justify-between border-b-[3px] border-charcoal gap-1.5 sm:gap-2 pt-4">
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 35%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">35%</span>
            </div>
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 60%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">60%</span>
            </div>
            <div class="w-full bg-ember border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 95%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-ember text-canvas font-mono text-[9px] px-1 font-bold">95%</span>
            </div>
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 45%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">45%</span>
            </div>
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 75%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">75%</span>
            </div>
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 30%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">30%</span>
            </div>
            <div class="w-full bg-charcoal border-grid border-b-0 hover:bg-ember transition-colors cursor-pointer relative group/bar" style="height: 65%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">65%</span>
            </div>
          </div>
          <div class="flex justify-between text-[10px] font-mono font-bold uppercase mt-2 text-charcoal">
            <span>SEN</span><span>SEL</span><span>RAB</span><span>KAM</span><span>JUM</span><span>SAB</span><span>MIN</span>
          </div>
        </div>
      </div>

      <!-- Module 3: Comparative Analytics & Exports -->
      <div class="p-6 sm:p-8 flex flex-col bg-ember text-canvas">
        <span class="font-mono text-[10px] font-bold text-canvas uppercase tracking-widest mb-1">FITUR 03 // ANALYTICS & EXPORTS</span>
        <h3 class="font-black text-2xl sm:text-3xl uppercase tracking-tighter mb-3">ANALISIS & ARSIP DATA</h3>
        <p class="text-xs sm:text-sm font-semibold mb-8 pb-4 border-b-[3px] border-charcoal">
          Modul perbandingan selektif (Harian/Mingguan/Bulanan/Tanggal A vs B) serta ekspor cetak PDF A4 & Excel.
        </p>
        
        <div class="mt-auto">
          <a href="/dashboard/comparison" class="block w-full border-[3px] border-charcoal bg-charcoal text-canvas text-center font-mono font-bold text-xs uppercase tracking-widest py-4 hover:bg-canvas hover:text-charcoal transition-none active:translate-y-1">
            BUKA PERBANDINGAN PERFORMA →
          </a>
        </div>
      </div>

    </section>

    <!-- STRUCTURED CATALOG OF 6 SYSTEM MODULES -->
    <section id="modul" class="p-6 sm:p-12 lg:p-16 border-b-grid bg-light flex flex-col gap-6">
      <div class="border-b-[3px] border-charcoal pb-4 flex flex-col sm:flex-row sm:items-end justify-between gap-2">
        <div>
          <span class="font-mono text-xs font-bold uppercase tracking-widest text-ember">
            02 // NAVIGATION_ARCHITECTURE
          </span>
          <h3 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-charcoal mt-1">
            KATALOG MODUL DASBOR NAOOLIFT
          </h3>
        </div>
        <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">6 MODUL UTAMA</span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-mono text-xs uppercase font-bold text-charcoal">
        <a href="/dashboard" class="border-grid bg-canvas p-5 hover-invert flex flex-col justify-between gap-3">
          <div class="flex justify-between items-center border-b-grid pb-2">
            <span>[01] OVERVIEW</span>
            <span class="text-ember">→</span>
          </div>
          <span class="text-[11px] font-normal text-slate">Ringkasan cepat jadwal hari ini, 4 metrik progres, dan feed 5 log latihan terbaru.</span>
        </a>

        <a href="/dashboard/schedule" class="border-grid bg-canvas p-5 hover-invert flex flex-col justify-between gap-3">
          <div class="flex justify-between items-center border-b-grid pb-2">
            <span>[02] JADWAL LATIHAN</span>
            <span class="text-ember">→</span>
          </div>
          <span class="text-[11px] font-normal text-slate">Manajemen program mingguan per bulan dengan target fokus otot & Rest Day.</span>
        </a>

        <a href="/dashboard/logs" class="border-grid bg-canvas p-5 hover-invert flex flex-col justify-between gap-3">
          <div class="flex justify-between items-center border-b-grid pb-2">
            <span>[03] LOG LATIHAN</span>
            <span class="text-ember">→</span>
          </div>
          <span class="text-[11px] font-normal text-slate">Pencatatan set, reps, beban (KG), stopwatch background, dan filter Harian/Mingguan/Bulanan/Semua.</span>
        </a>

        <a href="/dashboard/stats" class="border-grid bg-canvas p-5 hover-invert flex flex-col justify-between gap-3">
          <div class="flex justify-between items-center border-b-grid pb-2">
            <span>[04] STATISTIK</span>
            <span class="text-ember">→</span>
          </div>
          <span class="text-[11px] font-normal text-slate">Analisis tonase all-time, tren 7 hari terakhir, dan Top 5 Ranking Gerakan.</span>
        </a>

        <a href="/dashboard/comparison" class="border-grid bg-canvas p-5 hover-invert flex flex-col justify-between gap-3">
          <div class="flex justify-between items-center border-b-grid pb-2">
            <span>[05] PERBANDINGAN</span>
            <span class="text-ember">→</span>
          </div>
          <span class="text-[11px] font-normal text-slate">Evaluasi side-by-side: Harian, Mingguan, Bulanan, dan pilihan Tanggal A vs B.</span>
        </a>

        <a href="/dashboard/settings" class="border-grid bg-canvas p-5 hover-invert flex flex-col justify-between gap-3">
          <div class="flex justify-between items-center border-b-grid pb-2">
            <span>[06] PENGATURAN</span>
            <span class="text-ember">→</span>
          </div>
          <span class="text-[11px] font-normal text-slate">Pengaturan profil pengguna dan pembaruan kata sandi akun secara aman.</span>
        </a>
      </div>
    </section>

    <!-- FOOTER BAR -->
    <footer class="border-t-grid p-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-bold uppercase tracking-widest bg-charcoal text-canvas font-mono">
      <div>NAOOLIFT © 2026</div>
      <div class="text-slate">ENGINEERED FOR PROGRESSIVE OVERLOAD</div>
    </footer>

  </div>

  <!-- 2-STEP INTERACTIVE ANNOUNCEMENT & FEEDBACK MODAL -->
  <div id="feedback-modal" class="fixed inset-0 z-[100] bg-charcoal/80 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-[540px] border-grid bg-canvas p-6 sm:p-8 flex flex-col gap-5 shadow-none relative animate-modal-pop">
      
      <!-- STEP 1: ANNOUNCEMENT NOTICE TEXT -->
      <div id="modal-step-1" class="flex flex-col gap-4 animate-step-in">
        <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
          <span class="font-mono text-xs font-bold text-ember uppercase tracking-widest flex items-center gap-1.5">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
            ! SYSTEM ANNOUNCEMENT
          </span>
          <button onclick="closeFeedbackModal()" class="font-mono text-xs font-bold text-slate hover:text-ember">[✕ LEWATI]</button>
        </div>

        <div class="flex flex-col gap-2">
          <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-charcoal leading-none">
            WEB MASIH DALAM TAHAP PENGEMBANGAN AKTIF
          </h3>
          <span class="font-mono text-[10px] text-ember font-bold uppercase">VERSION 2.0 DEVELOPMENT PHASE</span>
        </div>

        <p class="text-xs sm:text-sm font-semibold text-slate leading-relaxed border-l-[4px] border-ember pl-3.5 bg-light p-3">
          Selamat datang di <strong class="text-charcoal font-black">NaooLift v2.0</strong>! Aplikasi ini saat ini sedang dalam tahap pengembangan aktif. Kami sangat mengapresiasi masukan, ide fitur baru, atau laporan kendala dari Anda untuk menyempurnakan aplikasi.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
          <button 
            onclick="goToModalStep(2)" 
            class="flex-1 border-[3px] border-charcoal bg-ember text-canvas font-bold text-xs uppercase tracking-widest py-3.5 hover:bg-charcoal transition-none active:translate-y-1"
          >
            [ + BERI MASUKAN / SARAN ]
          </button>
          
          <button 
            onclick="closeFeedbackModal()" 
            class="border-[3px] border-charcoal bg-canvas text-charcoal font-bold text-xs uppercase tracking-widest px-6 py-3.5 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
          >
            LEWATI →
          </button>
        </div>
      </div>

      <!-- STEP 2: FEEDBACK FORM FOR ADMIN MAILBOX -->
      <div id="modal-step-2" class="flex flex-col gap-4 hidden animate-step-in">
        <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
          <h3 class="font-black text-xl uppercase tracking-tighter text-charcoal">
            FORMULIR MASUKAN & SARAN
          </h3>
          <button onclick="goToModalStep(1)" class="font-mono text-xs font-bold text-slate hover:text-ember">[← KEMBALI]</button>
        </div>

        <p class="text-xs font-semibold text-slate leading-relaxed">
          Silakan tuliskan masukan Anda. Pesan ini akan langsung masuk ke <strong class="text-charcoal font-black">Mailbox Admin</strong>.
        </p>

        <form action="/feedback" method="POST" class="space-y-3.5">
          @csrf
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
                NAMA ANDA (OPSIONAL)
              </label>
              <input 
                type="text" 
                name="user_name" 
                value="{{ session('user', '') }}"
                placeholder="MISAL: NAOO"
                class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
              >
            </div>

            <div class="flex flex-col gap-1">
              <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
                EMAIL CONTACT (OPSIONAL)
              </label>
              <input 
                type="email" 
                name="user_email" 
                value="{{ session('user_email', '') }}"
                placeholder="NAMA@EMAIL.COM"
                class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
              >
            </div>
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
              KATEGORI PESAN
            </label>
            <select 
              name="category"
              class="w-full bg-light border-grid p-2.5 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember cursor-pointer"
            >
              <option value="SARAN & MASUKAN">SARAN & MASUKAN PENGEMBANGAN</option>
              <option value="PERMINTAAN FITUR">PERMINTAAN FITUR BARU</option>
              <option value="LAPORAN BUG">LAPORAN BUG / ERROR</option>
            </select>
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-mono text-[10px] font-bold uppercase tracking-widest text-charcoal">
              ISIKAN PESAN / SARAN ANDA *
            </label>
            <textarea 
              name="message" 
              rows="3" 
              required
              placeholder="TULISKAN MASUKAN ATAU IDE UNTUK PEMBARUAN NAOOLIFT DI SINI..."
              class="w-full bg-light border-grid p-3 font-mono text-xs font-bold uppercase text-charcoal focus:bg-canvas focus:outline-none focus:border-ember"
            ></textarea>
          </div>

          <div class="flex gap-2 pt-1">
            <button 
              type="button"
              onclick="goToModalStep(1)"
              class="border-grid bg-light text-charcoal font-bold text-xs uppercase tracking-widest px-4 py-3 hover:bg-charcoal hover:text-canvas transition-none active:translate-y-1"
            >
              ← KEMBALI
            </button>
            <button 
              type="submit" 
              class="flex-1 border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal transition-none active:translate-y-1"
            >
              KIRIM KE MAILBOX ADMIN →
            </button>
          </div>
        </form>
      </div>

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
    // Automatically trigger active development announcement pop-up on page load
    window.addEventListener('DOMContentLoaded', () => {
      if (!sessionStorage.getItem('naoolift_dev_popup_dismissed')) {
        setTimeout(() => {
          openFeedbackModal(1);
        }, 400);
      }
    });

    function openFeedbackModal(step = 1) {
      const modal = document.getElementById('feedback-modal');
      if (modal) {
        modal.classList.remove('hidden');
        goToModalStep(step);
      }
    }

    function goToModalStep(step) {
      const step1 = document.getElementById('modal-step-1');
      const step2 = document.getElementById('modal-step-2');
      
      if (step === 1) {
        if (step2) step2.classList.add('hidden');
        if (step1) {
          step1.classList.remove('hidden');
          step1.classList.remove('animate-step-in');
          void step1.offsetWidth; // Trigger reflow
          step1.classList.add('animate-step-in');
        }
      } else if (step === 2) {
        if (step1) step1.classList.add('hidden');
        if (step2) {
          step2.classList.remove('hidden');
          step2.classList.remove('animate-step-in');
          void step2.offsetWidth; // Trigger reflow
          step2.classList.add('animate-step-in');
        }
      }
    }

    function closeFeedbackModal() {
      const modal = document.getElementById('feedback-modal');
      if (modal) modal.classList.add('hidden');
      sessionStorage.setItem('naoolift_dev_popup_dismissed', 'true');
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
        closeFeedbackModal();
        closeLogoutModal();
      }
    });

    // Real-time Live Workout Seconds Timer Counter
    const timerElement = document.getElementById('live-timer');
    if (timerElement) {
      let seconds = 4;
      let minutes = 12;
      let hours = 1;

      setInterval(() => {
        seconds++;
        if (seconds >= 60) {
          seconds = 0;
          minutes++;
          if (minutes >= 60) {
            minutes = 0;
            hours++;
          }
        }
        const formatNum = num => num.toString().padStart(2, '0');
        timerElement.textContent = `${formatNum(hours)}:${formatNum(minutes)}:${formatNum(seconds)}`;
      }, 1000);
    }

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

  <!-- SWISS BRUTALIST PWA INSTALL BANNER -->
  <div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 z-40 max-w-[420px] border-[3px] border-charcoal bg-charcoal text-canvas p-4 font-mono text-xs font-bold uppercase tracking-widest hidden shadow-none animate-toast-slide border-l-[8px] border-l-ember">
    <div class="flex items-start justify-between gap-3 mb-2">
      <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 bg-ember animate-pulse inline-block"></span>
        <span>PASANG APLIKASI NAOOLIFT (PWA)</span>
      </div>
      <button onclick="dismissPwaBanner()" class="text-canvas hover:text-ember font-mono font-bold text-xs">[✕]</button>
    </div>
    <p class="font-sans text-xs text-light mb-3 normal-case tracking-normal">
      Pasang NaooLift langsung ke layar beranda Laptop atau HP Anda untuk akses offline &amp; performa cepat.
    </p>
    <button id="pwa-install-btn" class="w-full border-[2px] border-canvas bg-ember text-canvas hover:bg-canvas hover:text-charcoal font-mono font-bold text-xs uppercase tracking-widest py-2.5 transition-none active:translate-y-1">
      [ + PASANG SEKARANG TO HOMESCREEN ]
    </button>
  </div>

  <!-- FLOATING SWISS BRUTALIST PWA INSTALL BUTTON -->
  <button 
    id="pwa-floating-btn" 
    onclick="triggerPwaInstall()" 
    class="fixed bottom-6 left-6 z-40 border-[3px] border-charcoal bg-ember text-canvas p-3 sm:px-4 sm:py-3 font-mono text-xs font-bold uppercase tracking-widest flex items-center gap-2 hover:bg-charcoal transition-none btn-tactile active:translate-y-1"
    title="Pasang NaooLift ke Homescreen Laptop/HP"
  >
    <span class="w-2.5 h-2.5 bg-canvas animate-pulse inline-block"></span>
    <span>TAMBAHKAN KE LAYAR UTAMA</span>
  </button>

  <!-- PWA INSTALL GUIDE MODAL -->
  <div id="pwa-guide-modal" class="fixed inset-0 z-[100] bg-charcoal/80 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-[460px] border-grid bg-canvas p-6 sm:p-8 flex flex-col gap-4 shadow-none relative animate-fade-in border-l-[8px] border-l-ember">
      <div class="flex justify-between items-center border-b-[3px] border-charcoal pb-3">
        <h3 class="font-black text-xl uppercase tracking-tighter text-charcoal flex items-center gap-2">
          <span>[SYS_PWA]</span> PASANG NAOOLIFT
        </h3>
        <button onclick="closePwaModal()" class="text-charcoal hover:text-ember font-mono font-bold text-xs">[✕]</button>
      </div>

      <div id="pwa-modal-body" class="flex flex-col gap-3 font-sans text-xs sm:text-sm font-semibold text-charcoal">
        <p>
          Aplikasi NaooLift dapat dipasang langsung ke layar beranda HP atau Laptop Anda tanpa perlu mengunduh dari App Store.
        </p>

        <div id="pwa-ios-instructions" class="p-3 bg-light border-grid flex flex-col gap-1.5 font-mono text-xs">
          <span class="font-bold text-ember">PETUNJUK IOS (SAFARI):</span>
          <span>1. Tekan tombol <strong class="underline">Share / Bagikan [⎋]</strong> di navigasi Safari.</span>
          <span>2. Gulir ke bawah lalu pilih <strong class="underline">'Add to Home Screen' (+ Tambah ke Layar Utama)</strong>.</span>
        </div>

        <div id="pwa-chrome-instructions" class="p-3 bg-light border-grid flex flex-col gap-1.5 font-mono text-xs hidden">
          <span class="font-bold text-ember">PETUNJUK BROWSER:</span>
          <span>Tekan ikon <strong>Install / Tambahkan [⊕]</strong> di baris URL browser Anda.</span>
        </div>
      </div>

      <div class="flex gap-3 pt-2">
        <button id="pwa-modal-prompt-btn" onclick="triggerPwaPromptAction()" class="w-full border-[3px] border-charcoal bg-ember text-canvas font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal transition-none btn-tactile">
          [ + PASANG KE LAYAR UTAMA ]
        </button>
      </div>
    </div>
  </div>

  <script>
    let deferredPrompt = null;
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPrompt = e;
      const btn = document.getElementById('pwa-floating-btn');
      if (btn) btn.classList.remove('hidden');
    });

    function triggerPwaInstall() {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
          if (choiceResult.outcome === 'accepted') {
            console.log('User accepted PWA prompt');
          }
          deferredPrompt = null;
        });
      } else {
        openPwaModal();
      }
    }

    function openPwaModal() {
      const modal = document.getElementById('pwa-guide-modal');
      if (modal) modal.classList.remove('hidden');

      const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
      if (isIOS) {
        document.getElementById('pwa-ios-instructions')?.classList.remove('hidden');
        document.getElementById('pwa-chrome-instructions')?.classList.add('hidden');
      } else {
        document.getElementById('pwa-ios-instructions')?.classList.add('hidden');
        document.getElementById('pwa-chrome-instructions')?.classList.remove('hidden');
      }
    }

    function closePwaModal() {
      const modal = document.getElementById('pwa-guide-modal');
      if (modal) modal.classList.add('hidden');
    }

    function triggerPwaPromptAction() {
      if (deferredPrompt) {
        triggerPwaInstall();
      } else {
        closePwaModal();
      }
    }
  </script>
</body>
</html>