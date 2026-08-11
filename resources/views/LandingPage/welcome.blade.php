<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Swiss Workout Log</title>
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

  /* Reset all rounded corners and shadows universally for Swiss Brutalism */
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

  /* Sharp brutal hover & active states */
  .hover-invert {
    transition: background-color 0.1s ease, color 0.1s ease;
  }
  .hover-invert:hover {
    background-color: #1C1C1C;
    color: #EAE6E0;
  }
  .hover-ember {
    transition: background-color 0.1s ease, color 0.1s ease;
  }
  .hover-ember:hover {
    background-color: #9A4A2E; 
    color: #EAE6E0;
  }

  /* Ticker Marquee Animation */
  @keyframes marquee {
    0% { transform: translateX(0%); }
    100% { transform: translateX(-50%); }
  }
  .animate-marquee {
    display: flex;
    width: 200%;
    animation: marquee 22s linear infinite;
  }
  .animate-marquee:hover {
    animation-play-state: paused;
  }

  /* Blinking Indicator */
  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.2; }
  }
  .animate-blink {
    animation: blink 1.2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }

  /* Mobile Navigation Drawer Transition (Precise & Fast per rule.md) */
  @media (max-width: 767px) {
    #nav-menu {
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      transform: translateY(-8px);
      transition: max-height 0.25s ease-out, opacity 0.2s ease-out, transform 0.25s ease-out;
    }
    #nav-menu.is-open {
      max-height: 240px;
      opacity: 1;
      transform: translateY(0);
    }
    #nav-menu a {
      opacity: 0;
      transform: translateY(-6px);
      transition: opacity 0.2s ease-out, transform 0.2s ease-out, background-color 0.1s ease, color 0.1s ease;
    }
    #nav-menu.is-open a:nth-child(1) {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.05s;
    }
    #nav-menu.is-open a:nth-child(2) {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.1s;
    }
  }

  @media (min-width: 768px) {
    #nav-menu {
      max-height: none !important;
      opacity: 1 !important;
      transform: none !important;
      overflow: visible !important;
    }
    #nav-menu a {
      opacity: 1 !important;
      transform: none !important;
      transition-delay: 0s !important;
    }
  }

  #nav-toggle-icon {
    transition: transform 0.2s ease-out;
  }
  #nav-toggle-btn.is-open #nav-toggle-icon {
    transform: rotate(180deg);
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

  <!-- Main Container: Acts like a printed sheet of paper -->
  <div class="w-full max-w-[1280px] border-grid flex flex-col relative bg-canvas shadow-none">
    
    <!-- TOP NAV / HEADER (Table-like structure) -->
    <header class="flex flex-col md:flex-row border-b-grid">
      
      <!-- Logo Block -->
      <div class="p-4 sm:p-6 md:w-1/4 border-b-grid md:border-b-0 md:border-r-grid bg-charcoal text-canvas flex items-center justify-between">
        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tighter">NAOOLIFT.LOG</h1>
        
        <!-- Mobile Menu Toggle Button -->
        <button id="nav-toggle-btn" class="md:hidden font-bold text-xs bg-ember text-canvas px-3 py-2 uppercase hover:bg-canvas hover:text-charcoal transition-colors flex items-center gap-1.5 active:translate-y-1">
          <span id="nav-toggle-text">MENU</span>
          <svg id="nav-toggle-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
      
      <!-- Nav Links (Desktop & Mobile Drawer) -->
      <div id="nav-menu" class="flex flex-col sm:flex-row flex-1 font-bold text-xs sm:text-sm uppercase tracking-widest text-charcoal border-b-grid md:border-b-0">
        <a href="#data" class="flex-1 p-4 sm:p-6 border-b-grid sm:border-b-0 border-r-grid hover-invert flex items-center justify-center transition-none">
          DATA MODULES
        </a>
        <a href="#system" class="flex-1 p-4 sm:p-6 border-r-grid hover-invert flex items-center justify-center transition-none">
          SYSTEM CORE
        </a>
      </div>

      <!-- Action Buttons (DASHBOARD & MASUK / KELUAR) -->
      <div class="flex flex-col sm:flex-row md:w-1/3">
        <a href="/dashboard" class="flex-1 p-4 sm:p-6 bg-light text-charcoal font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-charcoal hover:text-canvas transition-none flex items-center justify-center border-b-grid sm:border-b-0 sm:border-r-grid active:translate-y-1">
          DASHBOARD →
        </a>
        @if(session('user'))
          <button onclick="openLogoutModal()" class="flex-1 p-4 sm:p-6 bg-charcoal text-canvas font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-ember transition-none flex items-center justify-center active:translate-y-1">
            KELUAR [✕]
          </button>
        @else
          <a href="/register" class="flex-1 p-4 sm:p-6 bg-ember text-canvas font-bold text-xs sm:text-sm uppercase tracking-widest hover:bg-charcoal transition-none flex items-center justify-center active:translate-y-1">
            MASUK →
          </a>
        @endif
      </div>
    </header>

    <!-- INFO STRIP (Relocated to Top below Header per User Request) -->
    <div class="flex flex-col md:flex-row border-b-grid text-xs sm:text-sm font-bold uppercase tracking-widest text-charcoal">
      <div class="flex-1 p-4 border-b-grid md:border-b-0 md:border-r-grid flex justify-between items-center">
        <span>STATUS:</span>
        <span class="text-ember flex items-center gap-1.5 font-mono">
          <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
          @if(session('user')) OPERATIONAL @else GUEST_SESSION @endif
        </span>
      </div>
      <div class="flex-1 p-4 border-b-grid md:border-b-0 md:border-r-grid flex justify-between items-center">
        <span>FORMAT:</span>
        <span class="font-mono">PROGRESSIVE WEB APP</span>
      </div>
      <div class="flex-1 p-4 flex justify-between items-center bg-light">
        <span>ACTIVE USER:</span>
        <span class="font-mono @if(!session('user')) text-slate @endif">
          {{ session('user') ?? 'UNAUTHENTICATED' }}
        </span>
      </div>
    </div>

    <!-- HERO SECTION (Split Screen) -->
    <section class="flex flex-col lg:flex-row border-b-grid">
      
      <!-- Left: Big Typography -->
      <div class="lg:w-2/3 p-6 sm:p-12 lg:p-20 border-b-grid lg:border-b-0 lg:border-r-grid flex flex-col justify-center bg-light">
        <div class="font-mono text-xs uppercase tracking-widest mb-6 sm:mb-10 border-b-[3px] border-charcoal pb-2 inline-block w-max font-bold text-charcoal">
          Doc Ref: SYS-2026-WL
        </div>
        
        <h2 class="text-4xl sm:text-7xl lg:text-[7.5rem] font-black uppercase leading-[0.88] tracking-tighter mb-6 sm:mb-8 text-charcoal">
          RECORD<br>
          EVERY<br>
          <span class="text-ember">REP.</span>
        </h2>
        
        <p class="text-sm sm:text-lg font-bold max-w-md border-l-[4px] border-ember pl-4 text-charcoal leading-relaxed">
          Sistem log kebugaran utilitarian. Tanpa gangguan visual. Fokus penuh pada metrik volume, waktu, dan konsistensi harian.
        </p>
      </div>

      <!-- Right: Real-time Stats Grid -->
      <div class="lg:w-1/3 flex flex-col">
        
        <!-- Timer Block -->
        <div class="p-6 sm:p-10 flex-1 border-b-grid flex flex-col justify-center hover:bg-light transition-none">
          <div class="flex justify-between items-end mb-6 text-charcoal">
            <span class="text-xs sm:text-sm font-bold uppercase tracking-widest">01 / TIMER</span>
            <div class="font-mono text-xs font-bold bg-charcoal text-canvas px-2 py-1 flex items-center gap-1.5">
              <span class="w-2 h-2 bg-ember animate-blink inline-block"></span>
              <span>REC</span>
            </div>
          </div>
          <div id="live-timer" class="font-mono text-4xl sm:text-6xl font-black tracking-tighter text-ember">
            01:12:04
          </div>
        </div>

        <!-- Streak Block -->
        <div class="p-6 sm:p-10 flex-1 flex flex-col justify-center bg-charcoal text-canvas hover:bg-ember transition-none cursor-default">
          <span class="text-xs sm:text-sm font-bold uppercase tracking-widest mb-4 sm:mb-6">02 / STREAK</span>
          <div class="flex items-baseline gap-2">
            <span class="font-mono text-6xl sm:text-8xl font-black tracking-tighter leading-none">18</span>
            <span class="text-xl sm:text-2xl font-bold uppercase tracking-widest">DAYS</span>
          </div>
        </div>

      </div>
    </section>

    <!-- MODULES SECTION -->
    <section id="data" class="grid grid-cols-1 md:grid-cols-3">
      
      <!-- Module 1: Volume Load -->
      <div class="p-6 sm:p-8 border-b-grid md:border-b-0 md:border-r-grid flex flex-col bg-canvas">
        <h3 class="font-black text-2xl sm:text-3xl uppercase tracking-tighter mb-3 text-charcoal">VOLUME<br>LOAD</h3>
        <p class="text-xs sm:text-sm font-semibold mb-8 pb-4 border-b-[3px] border-charcoal text-slate">
          Akumulasi total tonase beban yang diangkat selama siklus latihan berjalan.
        </p>
        
        <div class="mt-auto">
          <div class="font-mono text-xs uppercase mb-2 flex justify-between font-bold text-charcoal">
            <span>CUR_WEEK</span>
            <span>4,200 KG</span>
          </div>
          <!-- Blocky Animated Progress Bar -->
          <div class="w-full h-6 border-grid p-[2px] flex bg-light">
            <div class="h-full bg-charcoal w-[65%] transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Module 2: Weekly Stark Graph -->
      <div class="p-6 sm:p-8 border-b-grid md:border-b-0 md:border-r-grid flex flex-col bg-canvas">
        <h3 class="font-black text-2xl sm:text-3xl uppercase tracking-tighter mb-3 text-charcoal">WEEKLY<br>GRAPH</h3>
        <p class="text-xs sm:text-sm font-semibold mb-8 pb-4 border-b-[3px] border-charcoal text-slate">
          Perbandingan frekuensi dan intensitas per hari dalam format bar absolut.
        </p>
        
        <!-- Interactive Stark Bar Chart with Hover Tooltips -->
        <div class="mt-auto">
          <div class="h-28 flex items-end justify-between border-b-[3px] border-charcoal gap-1.5 sm:gap-2 pt-4">
            
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 30%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">30%</span>
            </div>
            
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 50%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">50%</span>
            </div>
            
            <div class="w-full bg-ember border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 90%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-ember text-canvas font-mono text-[9px] px-1 font-bold">90%</span>
            </div>
            
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 40%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">40%</span>
            </div>
            
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 70%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">70%</span>
            </div>
            
            <div class="w-full bg-light border-grid border-b-0 hover:bg-charcoal transition-colors cursor-pointer relative group/bar" style="height: 20%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">20%</span>
            </div>
            
            <div class="w-full bg-charcoal border-grid border-b-0 hover:bg-ember transition-colors cursor-pointer relative group/bar" style="height: 60%">
              <span class="hidden group-hover/bar:block absolute -top-7 left-1/2 -translate-x-1/2 bg-charcoal text-canvas font-mono text-[9px] px-1 font-bold">60%</span>
            </div>

          </div>
          <div class="flex justify-between text-[10px] font-mono font-bold uppercase mt-2 text-charcoal">
            <span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span><span>SUN</span>
          </div>
        </div>
      </div>

      <!-- Module 3: System Sync -->
      <div id="system" class="p-6 sm:p-8 flex flex-col bg-ember text-canvas">
        <h3 class="font-black text-2xl sm:text-3xl uppercase tracking-tighter mb-3">SYSTEM<br>SYNC</h3>
        <p class="text-xs sm:text-sm font-semibold mb-8 pb-4 border-b-[3px] border-charcoal">
          Penyimpanan data lokal yang aman tanpa perlu sinkronisasi cloud yang lambat.
        </p>
        
        <div class="mt-auto">
          <button id="sync-btn" class="block w-full border-[3px] border-charcoal bg-charcoal text-canvas text-center font-bold uppercase tracking-widest py-4 hover:bg-canvas hover:text-charcoal transition-none active:translate-y-1">
            FORCE SYNC
          </button>
        </div>
      </div>

    </section>

    <!-- FOOTER -->
    <footer class="border-t-grid p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs font-bold uppercase tracking-widest bg-light text-charcoal">
      <span>NAOOLIFT © 2026</span>
      <span>ENGINEERED FOR LIFTING</span>
    </footer>

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
    // 1. Responsive Mobile Navigation Toggle & Icon Animation
    const navToggleBtn = document.getElementById('nav-toggle-btn');
    const navMenu = document.getElementById('nav-menu');
    const navToggleText = document.getElementById('nav-toggle-text');
    const navToggleIcon = document.getElementById('nav-toggle-icon');

    if (navToggleBtn && navMenu) {
      navToggleBtn.addEventListener('click', () => {
        const isOpen = navMenu.classList.contains('is-open');
        if (isOpen) {
          navMenu.classList.remove('is-open');
          navToggleBtn.classList.remove('is-open');
          if (navToggleText) navToggleText.textContent = 'MENU';
          if (navToggleIcon) {
            navToggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>';
          }
        } else {
          navMenu.classList.add('is-open');
          navToggleBtn.classList.add('is-open');
          if (navToggleText) navToggleText.textContent = 'CLOSE';
          if (navToggleIcon) {
            navToggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>';
          }
        }
      });
    }

    // 2. Logout Confirmation Modal Triggers
    function openLogoutModal() {
      const modal = document.getElementById('logout-modal');
      if (modal) modal.classList.remove('hidden');
    }

    function closeLogoutModal() {
      const modal = document.getElementById('logout-modal');
      if (modal) modal.classList.add('hidden');
    }

    // Close modal when pressing Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeLogoutModal();
    });

    // 3. Real-time Live Workout Seconds Timer Counter
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

    // 4. Force Sync Button Feedback Action
    const syncBtn = document.getElementById('sync-btn');
    if (syncBtn) {
      syncBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const originalText = syncBtn.textContent;
        syncBtn.textContent = 'SYNCING...';
        syncBtn.classList.add('bg-canvas', 'text-charcoal');
        setTimeout(() => {
          syncBtn.textContent = 'SYNC COMPLETE ✓';
          setTimeout(() => {
            syncBtn.textContent = originalText;
            syncBtn.classList.remove('bg-canvas', 'text-charcoal');
          }, 1500);
        }, 800);
      });
    }

    // 5. Toast Dismiss Function
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
</body>
</html>