<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NaooLift — Panel Kontrol Admin</title>
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
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

  /* Blinking & Pulse Animations per rule.md */
  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.2; }
  }
  .animate-blink {
    animation: blink 1.2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }

  /* Section Highlight Pulse on Sidebar Click */
  @keyframes sectionPulseHighlight {
    0% {
      border-color: #9A4A2E;
      background-color: rgba(154, 74, 46, 0.12);
    }
    50% {
      border-color: #9A4A2E;
    }
    100% {
      border-color: #1C1C1C;
      background-color: #EAE6E0;
    }
  }
  .animate-section-highlight {
    animation: sectionPulseHighlight 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }

  /* Sharp Brutalist Card Hover Translation Animation */
  .admin-card-pop {
    transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.15s ease, background-color 0.15s ease;
  }
  .admin-card-pop:hover {
    transform: translateY(-4px);
  }

  /* Table Row Hover Fast Invert */
  .admin-row-hover {
    transition: background-color 0.1s linear, color 0.1s linear;
  }
  .admin-row-hover:hover {
    background-color: #1C1C1C !important;
    color: #EAE6E0 !important;
  }
  .admin-row-hover:hover td {
    color: #EAE6E0 !important;
  }

  /* Button Click Tactile Translate */
  .btn-tactile {
    transition: transform 0.08s ease-out, background-color 0.1s linear, color 0.1s linear;
  }
  .btn-tactile:active {
    transform: translateY(2px);
  }

  .hover-invert {
    transition: background-color 0.1s ease, color 0.1s ease;
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

  <!-- Main Admin Container Outer Wrapper -->
  <div class="w-full max-w-[1280px] border-grid flex flex-col relative bg-canvas shadow-none animate-fade-in">
    
    <!-- TOP UNIFIED APP HEADER BAR -->
    <header class="flex flex-col md:flex-row border-b-grid">
      <div class="w-full md:w-64 border-b-grid md:border-b-0 md:border-r-grid bg-ember text-canvas p-4 sm:p-5 flex items-center justify-between shrink-0">
        <div>
          <a href="/admin/dashboard" class="text-xl font-black uppercase tracking-tighter hover:text-charcoal transition-colors">
            NAOOLIFT.ADMIN
          </a>
          <div class="font-mono text-[9px] text-canvas uppercase tracking-widest mt-0.5">
            SYS_CONTROL_PANEL v2.0
          </div>
        </div>
        <span class="font-mono text-[10px] bg-charcoal text-canvas px-2 py-1 font-bold uppercase border-grid flex items-center gap-1.5">
          <span class="w-2 h-2 bg-ember animate-blink inline-block"></span>
          ADMIN
        </span>
      </div>

      <div class="hidden md:flex flex-1 flex-row font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-canvas">
          <span class="font-sans">MODUL SYSTEM:</span>
          <span class="text-ember flex items-center gap-1.5 font-bold">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
            MANAJEMEN_ADMINISTRATOR
          </span>
        </div>
        <div class="flex-1 p-4 border-r-grid flex justify-between items-center bg-light">
          <span class="font-sans">MAILBOX MASUKAN:</span>
          <span class="font-bold font-mono text-ember flex items-center gap-1.5">
            <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
            {{ $unreadFeedbackCount }} UNREAD
          </span>
        </div>
        <div class="flex flex-row w-64 font-sans font-bold">
          <a href="/dashboard" class="flex-1 p-4 bg-canvas text-charcoal text-center hover:bg-charcoal hover:text-canvas transition-none flex items-center justify-center border-r-grid btn-tactile">
            ← DASBOR USER
          </a>
          <button onclick="openLogoutModal()" class="flex-1 p-4 bg-charcoal text-canvas text-center hover:bg-ember transition-none flex items-center justify-center btn-tactile">
            KELUAR [✕]
          </button>
        </div>
      </div>
    </header>

    <!-- INNER DASHBOARD BODY -->
    <div class="flex flex-col md:flex-row flex-1 items-stretch">
      
      <!-- DESKTOP SWISS BRUTALIST SIDEBAR -->
      <aside class="hidden md:flex w-64 border-r-grid bg-canvas flex-col justify-between shrink-0">
        <div class="flex flex-col font-mono text-xs font-bold uppercase tracking-widest text-charcoal">
          
          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate">
            01 // PANEL ADMIN
          </div>
          
          <a href="#overview-section" class="p-4 border-b-grid bg-charcoal text-canvas flex items-center justify-between font-bold sidebar-item">
            <span>[01] OVERVIEW ADMIN</span>
            <span class="text-ember animate-blink">●</span>
          </a>

          <a href="#mailbox-section" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none text-ember font-bold sidebar-item">
            <span>[02] MAILBOX MASUKAN</span>
            <span class="bg-ember text-canvas text-[10px] px-1.5 py-0.5 font-bold animate-pulse">{{ $unreadFeedbackCount }}</span>
          </a>
          
          <a href="#users-section" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none sidebar-item">
            <span>[03] MANAJEMEN USER</span>
            <span class="text-slate font-normal">↓</span>
          </a>

          <a href="#logs-section" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none sidebar-item">
            <span>[04] MONITORING LOG</span>
            <span class="text-slate font-normal">↓</span>
          </a>

          <div class="p-3 bg-light border-b-grid font-bold text-[10px] text-slate border-t-grid">
            02 // NAVIGASI USER
          </div>

          <a href="/dashboard" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[05] DASBOR UTAMA</span>
            <span class="text-slate font-normal">→</span>
          </a>

          <a href="/dashboard/settings" class="p-4 border-b-grid hover-invert flex items-center justify-between transition-none">
            <span>[06] PENGATURAN</span>
            <span class="text-slate font-normal">→</span>
          </a>
        </div>

        <div class="mt-auto border-t-grid bg-light flex flex-col font-mono text-xs uppercase tracking-widest">
          <div class="p-4 flex flex-col gap-1 bg-canvas">
            <span class="text-[10px] text-slate font-bold">ADMIN_SESSION:</span>
            <span class="font-bold text-ember truncate flex items-center gap-1.5">
              <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
              {{ session('user', 'ADMIN') }}
            </span>
          </div>
        </div>
      </aside>

      <!-- RIGHT MAIN CONTENT AREA -->
      <main class="flex-1 flex flex-col min-w-0 bg-canvas">
        
        <div class="md:hidden flex border-b-grid bg-light p-3 justify-between items-center font-mono text-[11px] font-bold text-charcoal uppercase tracking-widest">
          <span>ADMIN: <span class="text-ember">{{ session('user', 'ADMIN') }}</span></span>
          <a href="/dashboard" class="text-ember font-bold">[ DASBOR USER → ]</a>
        </div>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="p-4 sm:p-8 lg:p-10 flex flex-col gap-6 sm:gap-8">

          <!-- Hero Section Header -->
          <div id="overview-section" class="border-b-[3px] border-charcoal pb-4 sm:pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4 scroll-mt-6">
            <div>
              <span class="font-mono text-[11px] sm:text-xs font-bold uppercase tracking-widest text-ember flex items-center gap-1.5">
                <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
                ADMINISTRATION_CONTROL_CENTER
              </span>
              <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-charcoal mt-1">
                PANEL KONTROL ADMIN
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate mt-1 max-w-xl">
                Pengelolaan akun pengguna terdaftar, mailbox masukan & saran pengembangan, serta pengawasan catatan log latihan global.
              </p>
            </div>
            
            <div class="flex items-center gap-2 shrink-0">
              <a 
                href="/dashboard"
                class="border-grid bg-ember text-canvas font-bold text-xs uppercase tracking-widest px-5 py-3.5 hover:bg-charcoal transition-none btn-tactile"
              >
                <span>← BUKA DASBOR ATLET USER</span>
              </a>
            </div>
          </div>

          <!-- 4 EXECUTIVE ADMIN METRIC CARDS WITH ANIMATIONS -->
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4 border-l-[8px] border-l-ember admin-card-pop cursor-default">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL PENGGUNA TERDAFTAR
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-ember">
                {{ $totalUsersCount }} <span class="text-sm text-charcoal">USER</span>
              </div>
              <div class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-2 flex justify-between items-center">
                <span>REGISTERED ATHLETES</span>
                <span class="text-ember font-bold">●</span>
              </div>
            </div>

            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4 border-l-[8px] border-l-charcoal admin-card-pop cursor-default">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                MAILBOX MASUKAN & SARAN
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal flex items-baseline gap-2">
                <span>{{ $feedbackMessages->count() }}</span>
                <span class="text-xs font-bold text-ember animate-pulse">({{ $unreadFeedbackCount }} BARU)</span>
              </div>
              <div class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-2 flex justify-between items-center">
                <span>USER FEEDBACK MESSAGES</span>
                <span class="text-ember font-bold">●</span>
              </div>
            </div>

            <div class="border-grid bg-canvas p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4 admin-card-pop cursor-default">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-slate uppercase tracking-widest">
                TOTAL LOG LATIHAN
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ $totalLogsCount }} <span class="text-sm text-slate">LOGS</span>
              </div>
              <div class="font-mono text-[9px] text-slate font-bold uppercase border-t-grid pt-2 flex justify-between items-center">
                <span>WORKOUT LOGS RECORDED</span>
                <span class="text-charcoal font-bold">●</span>
              </div>
            </div>

            <div class="border-grid bg-light p-4 sm:p-5 flex flex-col justify-between gap-3 sm:gap-4 admin-card-pop cursor-default">
              <span class="font-mono text-[10px] sm:text-[11px] font-bold text-charcoal uppercase tracking-widest">
                TOTAL TONASE SISTEM
              </span>
              <div class="font-mono text-3xl sm:text-4xl font-black text-charcoal">
                {{ number_format($totalSystemVolume) }} <span class="text-sm text-slate">KG</span>
              </div>
              <div class="font-mono text-[9px] text-charcoal font-bold uppercase border-t-grid pt-2 flex justify-between items-center">
                <span>TOTAL WEIGHT LIFTED</span>
                <span class="text-ember font-bold">●</span>
              </div>
            </div>
          </div>

          <!-- TABLE 01: MAILBOX MASUKAN & SARAN PENGEMBANGAN -->
          <div id="mailbox-section" class="border-grid bg-canvas p-5 sm:p-8 flex flex-col gap-4 border-l-[8px] border-l-ember scroll-mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2">
              <div>
                <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest flex items-center gap-1.5">
                  <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
                  SECTION 01 // MAILBOX_FEEDBACK_MESSAGES
                </span>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  MAILBOX MASUKAN & SARAN PENGEMBANGAN PENGGUNA
                </h3>
              </div>
              <span class="font-mono text-xs font-bold text-ember uppercase tracking-widest bg-light px-2.5 py-1 border-grid animate-pulse">
                {{ $unreadFeedbackCount }} PESAN BELUM DIBACA
              </span>
            </div>

            @if($feedbackMessages->count() > 0)
              <div class="overflow-x-auto border-grid">
                <table class="w-full text-left border-collapse font-mono text-xs">
                  <thead>
                    <tr class="bg-charcoal text-canvas uppercase text-[11px]">
                      <th class="p-3 border-r-grid">TANGGAL & WAKTU</th>
                      <th class="p-3 border-r-grid">PENGIRIM</th>
                      <th class="p-3 border-r-grid">EMAIL CONTACT</th>
                      <th class="p-3 border-r-grid">KATEGORI</th>
                      <th class="p-3 border-r-grid">ISU / SARAN PESAN</th>
                      <th class="p-3 border-r-grid text-center">STATUS</th>
                      <th class="p-3 text-center">AKSI MAILBOX</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($feedbackMessages as $idx => $fb)
                      <tr class="border-b-grid {{ !$fb->is_read ? 'bg-amber-100/60 font-bold' : ($idx % 2 === 1 ? 'bg-light' : 'bg-canvas') }} admin-row-hover">
                        <td class="p-3 border-r-grid font-bold text-[11px] whitespace-nowrap">
                          {{ date('d/m/Y H:i', strtotime($fb->created_at)) }}
                        </td>
                        <td class="p-3 border-r-grid font-black">
                          {{ strtoupper($fb->user_name) }}
                        </td>
                        <td class="p-3 border-r-grid font-bold text-slate">
                          {{ $fb->user_email ?? 'N/A' }}
                        </td>
                        <td class="p-3 border-r-grid font-bold">
                          <span class="bg-charcoal text-canvas px-2 py-0.5 text-[10px]">
                            {{ $fb->category }}
                          </span>
                        </td>
                        <td class="p-3 border-r-grid max-w-xs sm:max-w-md font-sans text-xs font-semibold leading-relaxed">
                          {{ $fb->message }}
                        </td>
                        <td class="p-3 border-r-grid text-center font-bold">
                          @if(!$fb->is_read)
                            <span class="bg-ember text-canvas px-2 py-1 text-[10px] animate-pulse">● BELUM DIBACA</span>
                          @else
                            <span class="bg-light text-slate border-grid px-2 py-1 text-[10px]">SUDAH DIBACA</span>
                          @endif
                        </td>
                        <td class="p-3 text-center flex justify-center gap-1.5">
                          <form action="/admin/feedback/toggle-read" method="POST">
                            @csrf
                            <input type="hidden" name="feedback_id" value="{{ $fb->id }}">
                            <button type="submit" class="border-grid bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas font-mono font-bold text-[10px] px-2 py-1 btn-tactile">
                              @if($fb->is_read) [TANDAI BELUM] @else [TANDAI DIBACA] @endif
                            </button>
                          </form>

                          <form action="/admin/feedback/delete" method="POST" onsubmit="return confirm('HAPUS PESAN MASUKAN INI?');">
                            @csrf
                            <input type="hidden" name="feedback_id" value="{{ $fb->id }}">
                            <button type="submit" class="border-grid bg-ember text-canvas hover:bg-charcoal font-mono font-bold text-[10px] px-2 py-1 btn-tactile">
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
                MAILBOX ADMIN KOSONG. BELUM ADA MASUKAN DARI PENGGUNA.
              </div>
            @endif
          </div>

          <!-- TABLE 02: MANAJEMEN AKUN PENGGUNA -->
          <div id="users-section" class="border-grid bg-canvas p-5 sm:p-8 flex flex-col gap-4 scroll-mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2">
              <div>
                <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest">
                  SECTION 02 // USER_ACCOUNTS_MANAGEMENT
                </span>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  MANAJEMEN AKUN PENGGUNA TERDAFTAR
                </h3>
              </div>
              <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">{{ $users->count() }} PENGGUNA</span>
            </div>

            @if($users->count() > 0)
              <div class="overflow-x-auto border-grid">
                <table class="w-full text-left border-collapse font-mono text-xs">
                  <thead>
                    <tr class="bg-charcoal text-canvas uppercase text-[11px]">
                      <th class="p-3 border-r-grid">ID</th>
                      <th class="p-3 border-r-grid">NAMA PENGGUNA</th>
                      <th class="p-3 border-r-grid">ALAMAT EMAIL</th>
                      <th class="p-3 border-r-grid text-center">TOTAL LOG</th>
                      <th class="p-3 border-r-grid text-center">TOTAL JADWAL</th>
                      <th class="p-3 border-r-grid text-center">HAK AKSES</th>
                      <th class="p-3 border-r-grid text-center">TGL DAFTAR</th>
                      <th class="p-3 text-center">AKSI ADMIN</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($users as $idx => $u)
                      <tr class="border-b-grid {{ $idx % 2 === 1 ? 'bg-light' : 'bg-canvas' }} admin-row-hover">
                        <td class="p-3 border-r-grid font-bold">
                          #{{ $u->id }}
                        </td>
                        <td class="p-3 border-r-grid font-black">
                          {{ strtoupper($u->name) }}
                        </td>
                        <td class="p-3 border-r-grid font-bold">
                          {{ $u->email }}
                        </td>
                        <td class="p-3 border-r-grid text-center font-bold text-ember">
                          {{ $u->workout_logs_count }} LOGS
                        </td>
                        <td class="p-3 border-r-grid text-center font-bold">
                          {{ $u->schedules_count }} HARI
                        </td>
                        <td class="p-3 border-r-grid text-center font-bold whitespace-nowrap">
                          @if($u->is_admin)
                            <span class="bg-ember text-canvas px-2.5 py-1 text-[10px] inline-block font-mono font-bold tracking-wider border-grid">● ADMIN</span>
                          @else
                            <span class="bg-light text-charcoal px-2.5 py-1 text-[10px] inline-block font-mono font-bold tracking-wider border-grid">ATLET</span>
                          @endif
                        </td>
                        <td class="p-3 border-r-grid text-center text-slate">
                          {{ date('d/m/Y', strtotime($u->created_at)) }}
                        </td>
                        <td class="p-3 text-center flex justify-center gap-1.5">
                          <form action="/admin/users/toggle-admin" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                            <button type="submit" class="border-grid bg-canvas text-charcoal hover:bg-charcoal hover:text-canvas font-mono font-bold text-[10px] px-2 py-1 btn-tactile">
                              [TOGGLE ADMIN]
                            </button>
                          </form>

                          <form action="/admin/users/delete" method="POST" onsubmit="return confirm('HAPUS AKUN {{ strtoupper($u->name) }} DAN SELURUH DATANYA?');">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                            <button type="submit" class="border-grid bg-ember text-canvas hover:bg-charcoal font-mono font-bold text-[10px] px-2 py-1 btn-tactile">
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
                BELUM ADA PENGGUNA TERDAFTAR DI DATABASE.
              </div>
            @endif
          </div>

          <!-- TABLE 03: MONITORING WORKOUT LOGS GLOBAL -->
          <div id="logs-section" class="border-grid bg-canvas p-5 sm:p-8 flex flex-col gap-4 scroll-mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-[3px] border-charcoal pb-4 gap-2">
              <div>
                <span class="font-mono text-[10px] font-bold text-ember uppercase tracking-widest flex items-center gap-1.5">
                  <span class="w-2 h-2 bg-ember animate-pulse inline-block"></span>
                  SECTION 03 // GLOBAL_WORKOUT_LOGS_MONITORING
                </span>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-charcoal mt-0.5">
                  MONITORING WORKOUT LOGS GLOBAL (15 TERAKHIR)
                </h3>
              </div>
              <span class="font-mono text-xs font-bold text-slate uppercase tracking-widest">GLOBAL MONITORING</span>
            </div>

            @if($latestSystemLogs->count() > 0)
              <div class="overflow-x-auto border-grid">
                <table class="w-full text-left border-collapse font-mono text-xs">
                  <thead>
                    <tr class="bg-charcoal text-canvas uppercase text-[11px]">
                      <th class="p-3 border-r-grid">TANGGAL</th>
                      <th class="p-3 border-r-grid">PENGGUNA</th>
                      <th class="p-3 border-r-grid">SESI / ROUTINE</th>
                      <th class="p-3 border-r-grid">GERAKAN LATIHAN</th>
                      <th class="p-3 border-r-grid text-center">SET × REPS</th>
                      <th class="p-3 border-r-grid text-right">BEBAN (KG)</th>
                      <th class="p-3 border-r-grid text-right">VOLUMETRIK</th>
                      <th class="p-3 text-center">AKSI ADMIN</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($latestSystemLogs as $idx => $log)
                      @php
                        $vol = $log->sets * $log->reps * $log->weight_kg;
                      @endphp
                      <tr class="border-b-grid {{ $idx % 2 === 1 ? 'bg-light' : 'bg-canvas' }} admin-row-hover">
                        <td class="p-3 border-r-grid font-bold">
                          {{ date('d/m/Y', strtotime($log->log_date)) }}
                        </td>
                        <td class="p-3 border-r-grid font-bold text-ember">
                          {{ $log->user ? strtoupper($log->user->name) : 'UNKNOWN' }}
                        </td>
                        <td class="p-3 border-r-grid font-bold">
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
                        <td class="p-3 border-r-grid text-right font-black text-ember">
                          {{ number_format($vol) }} KG
                        </td>
                        <td class="p-3 text-center">
                          <form action="/admin/logs/delete" method="POST" onsubmit="return confirm('HAPUS ENTRI LOG {{ $log->exercise_name }}?');">
                            @csrf
                            <input type="hidden" name="log_id" value="{{ $log->id }}">
                            <button type="submit" class="border-grid bg-ember text-canvas font-mono font-bold text-[10px] px-2 py-1 btn-tactile">
                              [✕ HAPUS LOG]
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
                BELUM ADA ENTRI CATATAN LOG LATIHAN GLOBAL TERDAFTAR.
              </div>
            @endif
          </div>

        </div>

      </main>

    </div>

    <!-- FULL-WIDTH SWISS BRUTALIST FOOTER BAR -->
    <footer class="border-t-grid bg-charcoal text-canvas p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center font-mono text-[10px] sm:text-xs uppercase tracking-widest gap-2">
      <div>NAOOLIFT SYSTEM © 2026</div>
      <div class="text-slate">MODULE: ADMINISTRATION_CONTROL_CENTER</div>
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
        Apakah Anda yakin ingin mengakhiri sesi administrasi aktif saat ini? Sesi akan kembali ke mode tamu.
      </p>
      <div class="flex gap-3 pt-2">
        <button onclick="closeLogoutModal()" class="flex-1 border-[3px] border-charcoal bg-light text-charcoal font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal hover:text-canvas transition-none btn-tactile">
          BATAL
        </button>
        <a href="/logout" class="flex-1 border-[3px] border-charcoal bg-ember text-canvas text-center font-bold text-xs uppercase tracking-widest py-3 hover:bg-charcoal transition-none btn-tactile flex items-center justify-center">
          YA, KELUAR →
        </a>
      </div>
    </div>
  </div>

  <script>
    // Smooth Scroll & Section Highlight Animation when clicking Sidebar Items
    document.querySelectorAll('aside a.sidebar-item[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
          // Update active sidebar item styling
          document.querySelectorAll('aside a.sidebar-item').forEach(el => {
            el.classList.remove('bg-charcoal', 'text-canvas');
            if (!el.classList.contains('hover-invert')) el.classList.add('hover-invert');
          });
          this.classList.add('bg-charcoal', 'text-canvas');

          // Smooth scroll to section
          targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });

          // Trigger section pulse highlight animation
          targetElement.classList.remove('animate-section-highlight');
          void targetElement.offsetWidth; // Reflow
          targetElement.classList.add('animate-section-highlight');
        }
      });
    });

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
