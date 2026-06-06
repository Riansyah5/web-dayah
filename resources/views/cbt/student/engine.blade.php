<!DOCTYPE html>
<html lang="id" data-theme="sunset">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CBT | {{ $studentExam->exam->name }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

  <script>
    const savedTheme = localStorage.getItem('cbt_theme') || 'sunset';
    document.documentElement.setAttribute('data-theme', savedTheme);

  </script>

  <style>
    /* =========================================
           SISTEM VARIABEL TEMA DINAMIS
           ========================================= */

    /* 1. SUNSET ENERGY (Default) */
    :root,
    [data-theme="sunset"] {
      --c-1: #FF5A5A;
      /* Coral */
      --c-2: #FF8B5A;
      /* Orange */
      --c-3: #FFA95A;
      /* Peach */
      --c-4: #FFD45A;
      /* Yellow */
      --c-4-text: #9c6c00;

      --bg-body: #fffaf6;
      --surface: #ffffff;
      --text-main: #2d1b15;
      --text-muted: #857068;
      --border-soft: #f2e9e4;
      --border-hard: #e2d2ca;

      --answered-bg: #fff0e5;
      --hover-bg: #fffaf5;
      --shadow-color: rgba(255, 90, 90, 0.2);
      --warning-text: #d63d3d;
    }

    /* 2. OCEAN TRUST */
    [data-theme="ocean"] {
      --c-1: #111FA2;
      /* Navy */
      --c-2: #5478FF;
      /* Royal Blue */
      --c-3: #53CBF3;
      /* Cyan */
      --c-4: #FFDE42;
      /* Yellow */
      --c-4-text: #111FA2;

      --bg-body: #f4f7fb;
      --surface: #ffffff;
      --text-main: #0c153b;
      --text-muted: #64748b;
      --border-soft: #e2e8f0;
      --border-hard: #cbd5e1;

      --answered-bg: #eaf8fd;
      --hover-bg: #fcfdfe;
      --shadow-color: rgba(84, 120, 255, 0.25);
      --warning-text: #111FA2;
    }

    /* 3. DREAMY CALM */
    [data-theme="dreamy"] {
      --c-1: #6367FF;
      /* Indigo */
      --c-2: #8494FF;
      /* Periwinkle */
      --c-3: #C9BEFF;
      /* Lavender */
      --c-4: #FFDBFD;
      /* Lilac */
      --c-4-text: #1a1b36;

      --bg-body: #f8f9ff;
      --surface: #ffffff;
      --text-main: #1a1b36;
      --text-muted: #6b7280;
      --border-soft: #e5e7eb;
      --border-hard: #C9BEFF;

      --answered-bg: rgba(201, 190, 255, 0.2);
      --hover-bg: #fdfdff;
      --shadow-color: rgba(99, 103, 255, 0.25);
      --warning-text: #d13076;
    }

    /* =========================================
           GLOBAL STYLING
           ========================================= */
    body {
      background-color: var(--bg-body);
      font-family: 'Inter', sans-serif;
      color: var(--text-main);
      letter-spacing: -0.01em;
      -webkit-font-smoothing: antialiased;
      transition: background-color 0.4s ease, color 0.4s ease;
    }

    /* Anti-Cheat */
    .unselectable {
      user-select: none;
      -moz-user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
    }

    textarea, input {
      user-select: text !important;
      -webkit-user-select: text !important;
      -moz-user-select: text !important;
      -ms-user-select: text !important;
    }

    /* === PREMIUM NAVBAR === */
    .navbar-premium {
      background: linear-gradient(135deg, var(--c-1) 0%, var(--c-2) 100%);
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 20px var(--shadow-color);
      transition: background 0.4s ease;
    }

    .btn-logout {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(4px);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 100px;
      font-weight: 600;
      padding: 8px 20px;
      transition: all 0.2s;
      font-size: 0.9rem;
    }

    .btn-logout:hover {
      background: white;
      color: var(--c-1);
      transform: translateY(-1px);
    }

    /* Timer "Dynamic Island" */
    .timer-island {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      padding: 8px 20px;
      border-radius: 100px;
      font-family: 'Inter', monospace;
      font-variant-numeric: tabular-nums;
      font-weight: 700;
      color: white;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
    }

    .timer-warning {
      background: var(--c-4) !important;
      border-color: var(--c-4) !important;
      color: var(--warning-text) !important;
      animation: pulse-warning 2s infinite;
    }

    @keyframes pulse-warning {
      0% {
        box-shadow: 0 0 0 0 var(--shadow-color);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
      }
    }

    /* === CONTAINERS & TYPOGRAPHY === */
    .surface-container {
      background: var(--surface);
      border-radius: 24px;
      box-shadow: 0 10px 40px -10px var(--shadow-color);
      border: 1px solid var(--border-soft);
      transition: all 0.4s ease;
    }

    .q-badge {
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      color: var(--c-2);
    }

    .text-dynamic,
    .text-dynamic p {
      font-family: 'Amiri', 'Inter', serif;
      font-size: 1.4rem;
      line-height: 2;
      color: var(--text-main);
      font-weight: 500;
    }

    /* === RADIO OPTIONS === */
    .option-box {
      cursor: pointer;
      padding: 20px 24px;
      border: 2px solid var(--border-soft);
      border-radius: 16px;
      background: var(--surface);
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: flex-start;
      gap: 16px;
      margin: 0;
    }

    .option-box:hover {
      border-color: var(--c-3);
      background-color: var(--hover-bg);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px var(--shadow-color);
    }

    .option-indicator {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      border: 2px solid var(--border-hard);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 4px;
      transition: all 0.2s ease;
    }

    .option-indicator::after {
      content: '';
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--surface);
      transform: scale(0);
      transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .option-radio:checked+.option-box {
      border-color: var(--c-1);
      background: var(--hover-bg);
      box-shadow: 0 4px 14px var(--shadow-color);
    }

    .option-radio:checked+.option-box .option-indicator {
      background: var(--c-1);
      border-color: var(--c-1);
    }

    .option-radio:checked+.option-box .option-indicator::after {
      transform: scale(1);
    }

    /* === NAVIGATION GRID === */
    .nav-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 10px;
    }

    .nav-btn {
      aspect-ratio: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.95rem;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.2s;
    }

    .nav-empty {
      background: var(--surface);
      border: 2px solid var(--border-soft);
      color: var(--text-muted);
    }

    .nav-empty:hover {
      border-color: var(--c-3);
      color: var(--c-1);
    }

    .nav-answered {
      background: var(--answered-bg);
      border: 2px solid var(--c-2);
      color: var(--c-1);
    }

    .nav-current {
      background: var(--c-1);
      color: white;
      border: 2px solid var(--c-1);
      box-shadow: 0 4px 12px var(--shadow-color);
    }

    /* === TEXTAREA & BUTTONS === */
    .premium-textarea {
      background: var(--bg-body);
      border: 2px solid transparent;
      border-radius: 16px;
      padding: 20px;
      transition: all 0.2s;
      resize: vertical;
    }

    .premium-textarea:focus {
      background: var(--surface);
      border-color: var(--c-2);
      box-shadow: 0 0 0 4px var(--shadow-color);
      outline: none;
    }

    .btn-primary-custom {
      background: linear-gradient(135deg, var(--c-1) 0%, var(--c-2) 100%);
      color: white;
      border: none;
      border-radius: 100px;
      font-weight: 700;
      padding: 14px 32px;
      transition: all 0.2s;
    }

    .btn-primary-custom:hover {
      opacity: 0.9;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -10px var(--shadow-color);
    }

    .btn-outline-custom {
      background: white;
      color: var(--c-1);
      border: 2px solid var(--border-soft);
      border-radius: 100px;
      font-weight: 700;
      padding: 12px 32px;
      transition: all 0.2s;
    }

    .btn-outline-custom:hover {
      border-color: var(--c-3);
      background: var(--hover-bg);
      color: var(--c-1);
    }

    .btn-finish {
      background: var(--c-1);
      color: white;
      border: none;
      border-radius: 100px;
      font-weight: 800;
      padding: 16px 32px;
      transition: all 0.2s;
      letter-spacing: 0.5px;
    }

    .btn-finish:hover {
      opacity: 0.9;
      color: #fff;
      box-shadow: 0 8px 20px var(--shadow-color);
    }

    /* Indikator Simpan Kelas Dinamis */
    .save-indicator-box {
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.3s;
      border: 1px solid transparent;
    }

    .state-saving {
      background: var(--answered-bg);
      border-color: var(--c-3);
      color: var(--c-1);
    }

    .state-saved {
      background: var(--bg-body);
      border-color: var(--border-soft);
      color: var(--text-muted);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-track {
      background: transparent;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--c-3);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--c-2);
    }

    /* =========================================
           FLOATING THEME SWITCHER
           ========================================= */
    .theme-switcher-wrapper {
      position: fixed;
      bottom: 30px;
      left: 30px;
      z-index: 1050;
    }

    .theme-fab {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      background: var(--surface);
      color: var(--c-1);
      border: 2px solid var(--border-soft);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .theme-fab:hover {
      transform: scale(1.05) rotate(15deg);
      border-color: var(--c-3);
    }

    .theme-menu {
      position: absolute;
      bottom: 70px;
      left: 0;
      background: var(--surface);
      border: 1px solid var(--border-soft);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      padding: 12px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(15px);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .theme-menu.active {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .theme-option {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      cursor: pointer;
      border: 3px solid transparent;
      transition: transform 0.2s;
      position: relative;
    }

    .theme-option:hover {
      transform: scale(1.1);
    }

    .theme-option.active-theme {
      border-color: var(--text-main);
    }

    /* Disabled Button State */
    .btn-disabled-custom {
      background: var(--border-soft) !important;
      color: var(--text-muted) !important;
      cursor: not-allowed;
      box-shadow: none !important;
    }

  </style>
</head>
<body class="unselectable" oncontextmenu="return false;">

  <div class="theme-switcher-wrapper">
    <div class="theme-menu" id="themeMenu">
      <div class="theme-option" style="background: linear-gradient(135deg, #FF5A5A, #FF8B5A);" onclick="changeTheme('sunset', this)" title="Sunset Energy"></div>
      <div class="theme-option" style="background: linear-gradient(135deg, #111FA2, #53CBF3);" onclick="changeTheme('ocean', this)" title="Ocean Trust"></div>
      <div class="theme-option" style="background: linear-gradient(135deg, #6367FF, #C9BEFF);" onclick="changeTheme('dreamy', this)" title="Dreamy Calm"></div>
    </div>
    <button class="theme-fab" onclick="toggleThemeMenu()" title="Ubah Warna Tema">
      <i class="bi bi-palette-fill fs-4"></i>
    </button>
  </div>

  <nav class="navbar-premium py-3 mb-4">
    <div class="container-xl d-flex justify-content-between align-items-center">

      <div class="d-flex align-items-center gap-3 text-white">
        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 45px; height: 45px; background: var(--c-4); color: var(--c-4-text); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
          <i class="bi bi-laptop fs-4"></i>
        </div>
        <div class="d-none d-md-block">
          <div class="fw-bold fs-5 lh-1 mb-1">{{ $studentExam->exam->name }}</div>
          <small class="badge bg-white bg-opacity-20 text-muted rounded-pill" style="font-size: 10px; letter-spacing: 1px;">CBT SYSTEM</small>
        </div>
      </div>

      <div class="timer-island" id="timerContainer">
        <i class="bi bi-stopwatch fs-5"></i>
        <span id="timerDisplay">00:00:00</span>
      </div>

      <div class="d-flex align-items-center gap-4 text-white">
        <div class="d-none d-lg-block text-end">
          <div class="fw-bold lh-1 mb-1" style="font-size: 0.95rem;">{{ Auth::guard('cbt')->user()->student->name }}</div>
          <div style="color: var(--c-4); font-size: 11px; font-weight: 600;">Peserta Ujian</div>
        </div>
        <form action="{{ route('cbt.logout') }}" method="POST" class="m-0">
          @csrf
          <button type="button" onclick="confirmLogout(this.closest('form'))" class="btn-logout">
            Keluar
          </button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container-xl pb-5">
    <div class="row g-4 g-xl-5">

      <div class="col-lg-8">
        <div class="surface-container p-4 p-md-5">

          <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light">
            <div>
              <span class="q-badge d-block mb-1">Pertanyaan</span>
              <h3 class="fw-bolder mb-0" style="font-size: 2.2rem; color: var(--c-1);">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="text-end">
              <span class="badge px-4 py-2 rounded-pill fw-bold shadow-sm" style="background: var(--c-4); color: var(--c-4-text); font-size: 0.9rem;">
                <i class="bi bi-star-fill me-1" style="color: inherit; opacity:0.8;"></i> {{ $currentAnswer->question->score_weight }} Poin
              </span>
            </div>
          </div>

          <div class="mb-5">
            <div class="text-dynamic" dir="auto">
              {{-- {!! nl2br(e($currentAnswer->question->question_text)) !!} --}}
              {!! $currentAnswer->question->question_text !!}
            </div>

            @if($currentAnswer->question->image_file)
            <div class="mt-4 text-center text-md-start">
              <img src="{{ asset('storage/'.$currentAnswer->question->image_file) }}" class="img-fluid rounded-4 border" style="max-height: 400px; box-shadow: 0 8px 24px var(--shadow-color); border-color: var(--border-soft)!important;">
            </div>
            @endif

            @if($currentAnswer->question->audio_file)
            <div class="mt-4 p-2 rounded-pill border" style="background: var(--bg-body); border-color: var(--border-soft)!important;">
              <audio controls class="w-100" style="height: 45px;">
                <source src="{{ asset('storage/'.$currentAnswer->question->audio_file) }}" type="audio/mpeg"></audio>
            </div>
            @endif
          </div>

          @if($currentAnswer->question->type == 'multiple_choice')
          <div class="d-flex flex-column gap-3">
            @foreach($currentAnswer->question->options as $opt)
            <div class="w-100">
              <input type="radio" name="option_choice" id="opt_{{ $opt->id }}" class="d-none option-radio" value="{{ $opt->id }}" {{ $currentAnswer->cbt_option_id == $opt->id ? 'checked' : '' }} onchange="autosaveOption({{ $currentAnswer->id }}, {{ $opt->id }})">

              <label for="opt_{{ $opt->id }}" class="option-box w-100">
                <div class="option-indicator"></div>
                <div class="flex-grow-1 text-dynamic pt-1" dir="auto" style="font-size: 1.25rem; line-height: 1.6;">
                  {{ $opt->option_text }}
                  @if($opt->image_file)
                  <img src="{{ asset('storage/'.$opt->image_file) }}" class="img-fluid rounded-3 mt-3 border d-block shadow-sm" style="max-height: 200px; border-color: var(--border-soft)!important;">
                  @endif
                </div>
              </label>
            </div>
            @endforeach
          </div>
          @else
          <div class="form-group">
            <label class="q-badge mb-3 d-block"><i class="bi bi-pencil-square me-2"></i>Lembar Jawaban Essay</label>
            <textarea id="essayInput" class="form-control text-dynamic premium-textarea w-100" rows="8" dir="auto" placeholder="Mulai mengetik jawaban Anda di sini..." onkeyup="debouncedAutosaveEssay({{ $currentAnswer->id }})">{{ $currentAnswer->essay_answer }}</textarea>
          </div>
          @endif

          <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top border-light">
            @if($page > 1)
            <a href="?no={{ $page - 1 }}" class="btn-outline-custom text-decoration-none">
              <i class="bi bi-arrow-left me-2"></i> Sebelumnya
            </a>
            @else
            <div></div>
            @endif

            @if($page < $totalQuestions) <a href="?no={{ $page + 1 }}" class="btn-primary-custom text-decoration-none">
              Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
              </a>
              @else
              <button type="button" onclick="confirmFinish()" class="btn-finish d-md-none">
                KUMPULKAN
              </button>
              @endif
          </div>

        </div>
      </div>

      <div class="col-lg-4">
        <div class="surface-container p-4 position-sticky" style="top: 100px;">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bolder mb-0" style="letter-spacing: 0.5px; color: var(--text-main);">PETA SOAL</h6>
            <span class="badge rounded-pill px-3 py-2" style="background: var(--answered-bg); color: var(--c-1); border: 1px solid var(--c-3);">{{ $totalQuestions }} Total</span>
          </div>

          <div class="d-flex align-items-center gap-2 mb-4 p-3 rounded-3 save-indicator-box state-saved" id="saveIndicator">
            <i class="bi bi-cloud-check text-success fs-5"></i>
            <span>Tersimpan otomatis</span>
          </div>

          <div class="nav-grid mb-4">
            @foreach($studentExam->answers as $ans)
            @php
            $isCurrent = ($ans->question_order == $page);
            $isAnswered = ($ans->cbt_option_id !== null || !empty($ans->essay_answer));

            $btnClass = 'nav-empty';
            if ($isCurrent) $btnClass = 'nav-current';
            elseif ($isAnswered) $btnClass = 'nav-answered';
            @endphp
            <a href="?no={{ $ans->question_order }}" class="{{ $btnClass }} nav-btn" id="navbox_{{ $ans->question_order }}">
              {{ $ans->question_order }}
            </a>
            @endforeach
          </div>

          <div class="d-flex justify-content-center gap-3 mb-5" style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">
            <div class="d-flex align-items-center gap-2"><span class="rounded-circle" style="width:10px; height:10px; background:var(--c-2);"></span> Terjawab</div>
            <div class="d-flex align-items-center gap-2"><span class="rounded-circle" style="width:10px; height:10px; background:var(--c-1);"></span> Saat Ini</div>
            <div class="d-flex align-items-center gap-2"><span class="rounded-circle border border-2" style="width:10px; height:10px; background:white; border-color:var(--border-hard)!important;"></span> Kosong</div>
          </div>

          <hr class="text-muted opacity-10 mb-4">

          <form id="finishForm" action="{{ route('cbt.engine.finish', $studentExam->id) }}" method="POST">
            @csrf
            <button type="button" onclick="confirmFinish()" class="btn-finish w-100 d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-check2-square fs-5"></i> KUMPULKAN UJIAN
            </button>
          </form>

        </div>
      </div>

    </div>
  </div>
  <div class="modal fade" id="warningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger border-3 rounded-4 shadow-lg">
        <div class="modal-body text-center p-5">
          <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
          <h4 class="fw-bold text-danger mt-3 mb-4">PESAN DARI PENGAWAS</h4>
          <p class="fs-5 mb-4 text-dark" id="warningMessageText">...</p>
          <button type="button" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold" data-bs-dismiss="modal">
            SAYA MENGERTI
          </button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // --- SISTEM THEME SWITCHER ---
    function toggleThemeMenu() {
      document.getElementById('themeMenu').classList.toggle('active');
    }

    function changeTheme(themeName, element) {
      // Set ke LocalStorage dan HTML Element
      document.documentElement.setAttribute('data-theme', themeName);
      localStorage.setItem('cbt_theme', themeName);

      // Highlight Opsi Terpilih
      document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active-theme'));
      if (element) element.classList.add('active-theme');

      // Tutup Menu
      document.getElementById('themeMenu').classList.remove('active');
    }

    // Highlight theme yang sedang aktif saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
      const currentTheme = localStorage.getItem('cbt_theme') || 'sunset';
      const options = document.querySelectorAll('.theme-option');
      if (currentTheme === 'sunset') options[0].classList.add('active-theme');
      if (currentTheme === 'ocean') options[1].classList.add('active-theme');
      if (currentTheme === 'dreamy') options[2].classList.add('active-theme');
    });

    // Tutup menu tema jika klik di luar
    document.addEventListener('click', function(event) {
      const wrapper = document.querySelector('.theme-switcher-wrapper');
      if (!wrapper.contains(event.target)) {
        document.getElementById('themeMenu').classList.remove('active');
      }
    });

    // --- COUNTDOWN TIMER ---
    let remainingSeconds = {{ $remainingSeconds }};
    const timerDisplay = document.getElementById('timerDisplay');
    const timerContainer = document.getElementById('timerContainer');
    const timerIcon = timerContainer.querySelector('i');

    function formatTime(sec) {
      let h = Math.floor(sec / 3600);
      let m = Math.floor((sec % 3600) / 60);
      let s = Math.floor(sec % 60);
      return [h, m, s].map(v => v < 10 ? "0" + v : v).join(":");
    }

    const countdown = setInterval(() => {
      if (remainingSeconds <= 0) {
        clearInterval(countdown);
        timerDisplay.innerText = "WAKTU HABIS";
        timerContainer.classList.add('timer-warning');

        Swal.fire({
          title: 'Waktu Habis'
          , text: "Sistem mengunci layar. Jawaban otomatis dikumpulkan."
          , icon: 'warning'
          , allowOutsideClick: false
          , showConfirmButton: false
          , timer: 3000
          , customClass: {
            popup: 'rounded-4'
          }
        }).then(() => {
          document.getElementById('finishForm').submit();
        });
      } else {
        timerDisplay.innerText = formatTime(remainingSeconds);
        if (remainingSeconds < 300 && !timerContainer.classList.contains('timer-warning')) {
          timerContainer.classList.add('timer-warning');
          timerIcon.classList.replace('text-white', 'text-danger');
        }
        remainingSeconds--;
      }
    }, 1000);

    // --- AUTOSAVE AJAX ---
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const saveIndicator = document.getElementById('saveIndicator');

    // --- LOGIC TOMBOL SELESAI ---
    const currentPage = {{ $page }};
    const totalQuestions = {{ $totalQuestions }};
    // Ambil daftar soal yang sudah dijawab dari server saat load
    const answeredOrders = @json($studentExam->answers->filter(fn($a) => $a->cbt_option_id || $a->essay_answer)->pluck('question_order'));
    const answeredQuestions = new Set(answeredOrders);

    function updateFinishButtonState() {
      const btns = document.querySelectorAll('.btn-finish');
      const isComplete = answeredQuestions.size >= totalQuestions;

      btns.forEach(btn => {
        if (!isComplete) btn.classList.add('btn-disabled-custom');
        else btn.classList.remove('btn-disabled-custom');
      });
    }

    function updateNavBoxToAnswered() {
      const navbox = document.getElementById('navbox_{{ $page }}');
      if (!navbox.classList.contains('nav-current')) {
        navbox.classList.remove('nav-empty');
        navbox.classList.add('nav-answered');
      }
    }

    function showSavingStatus(isSaving) {
      if (isSaving) {
        saveIndicator.className = "d-flex align-items-center gap-2 mb-4 p-3 rounded-3 save-indicator-box state-saving";
        saveIndicator.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span><span>Menyimpan...</span>';
      } else {
        saveIndicator.innerHTML = '<i class="bi bi-cloud-check-fill fs-5 me-2"></i><span>Tersimpan!</span>';
        setTimeout(() => {
          saveIndicator.className = "d-flex align-items-center gap-2 mb-4 p-3 rounded-3 save-indicator-box state-saved";
          saveIndicator.innerHTML = '<i class="bi bi-cloud-check text-success fs-5"></i><span>Tersimpan otomatis</span>';
        }, 2000);
      }
    }

    function autosaveOption(answerId, optionId) {
      showSavingStatus(true);
      fetch(`/cbt/exam/autosave/${answerId}`, {
        method: 'POST'
        , headers: {
          'Content-Type': 'application/json'
          , 'X-CSRF-TOKEN': csrfToken
        }
        , body: JSON.stringify({
          option_id: optionId
        })
      }).then(res => res.json()).then(data => {
        if (data.status === 'success') {
          showSavingStatus(false);
          updateNavBoxToAnswered();
          answeredQuestions.add(currentPage);
          updateFinishButtonState();
        }
      });
    }

    // --- DEBOUNCE HELPER ---
    function debounce(func, delay) {
      let timeout;
      return function(...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), delay);
      };
    }

    function autosaveEssay(answerId) {
      const text = document.getElementById('essayInput').value;
      showSavingStatus(true);
      fetch(`/cbt/exam/autosave/${answerId}`, {
        method: 'POST'
        , headers: {
          'Content-Type': 'application/json'
          , 'X-CSRF-TOKEN': csrfToken
        }
        , body: JSON.stringify({
          essay_text: text
        })
      }).then(res => res.json()).then(data => {
        if (data.status === 'success') {
          showSavingStatus(false);
          if (text.trim() !== '') {
            updateNavBoxToAnswered();
            answeredQuestions.add(currentPage);
          } else {
            answeredQuestions.delete(currentPage);
          }
          updateFinishButtonState();
        }
      });
    }

    // Buat versi debounced dari fungsi autosave essay
    const debouncedAutosaveEssay = debounce(autosaveEssay, 1500); // Simpan setelah 1.5 detik tidak mengetik

    function confirmFinish() {
      // Cek kelengkapan jawaban
      if (answeredQuestions.size < totalQuestions) {
        Swal.fire({
          title: 'Belum Selesai!'
          , text: `Anda baru menjawab ${answeredQuestions.size} dari ${totalQuestions} soal. Mohon lengkapi semua jawaban sebelum mengumpulkan.`
          , icon: 'warning'
          , confirmButtonText: 'Lanjutkan Mengerjakan'
          , customClass: {
            popup: 'rounded-4'
            , confirmButton: 'btn btn-primary-custom rounded-pill px-4 mx-2 py-2'
          }
          , buttonsStyling: false
        });
        return;
      }

      Swal.fire({
        title: 'Akhiri Ujian?'
        , text: "Pastikan Anda telah memeriksa kembali semua jawaban."
        , icon: 'question'
        , showCancelButton: true
        , confirmButtonText: 'Ya, Kumpulkan'
        , cancelButtonText: 'Batal'
        , reverseButtons: true
        , customClass: {
          popup: 'rounded-4'
          , confirmButton: 'btn btn-primary-custom rounded-pill px-4 mx-2 py-2'
          , cancelButton: 'btn btn-outline-custom rounded-pill px-4 mx-2 py-2'
        }
        , buttonsStyling: false
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Mengumpulkan Ujian'
            , text: 'Mohon tunggu sebentar...'
            , allowOutsideClick: false
            , showConfirmButton: false
            , willOpen: () => {
              Swal.showLoading();
            }
          });
          document.getElementById('finishForm').submit();
        }
      });
    }

    function confirmLogout(form) {
      Swal.fire({
        title: 'Keluar Aplikasi?'
        , text: "Sesi pengerjaan soal saat ini akan terhenti sementara."
        , icon: 'warning'
        , showCancelButton: true
        , confirmButtonText: 'Ya, Keluar'
        , cancelButtonText: 'Batal'
        , customClass: {
          popup: 'rounded-4'
          , confirmButton: 'btn btn-primary-custom rounded-pill px-4 mx-2 py-2'
          , cancelButton: 'btn btn-outline-custom rounded-pill px-4 mx-2 py-2'
        }
        , buttonsStyling: false
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    }

    // Inisialisasi status tombol saat halaman dimuat
    document.addEventListener('DOMContentLoaded', updateFinishButtonState);

    // --- 3. HEARTBEAT (Kirim sinyal online & Cek Pesan Pengawas) ---
    // Kita percepat menjadi 15 detik agar pesan cepat sampai
    setInterval(() => {
      fetch(`/cbt/exam/heartbeat/{{ $studentExam->id }}`, {
          method: 'POST'
          , headers: {
            'Content-Type': 'application/json'
            , 'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(res => res.json())
        .then(data => {
          // Jika ada pesan teguran dari server
          if (data.warning_message) {
            document.getElementById('warningMessageText').innerText = data.warning_message;
            // Tampilkan modal yang tidak bisa ditutup kecuali diklik tombol "SAYA MENGERTI"
            new bootstrap.Modal(document.getElementById('warningModal')).show();
          }
        })
        .catch(err => console.log('Koneksi internet mungkin terputus.'));
    }, 15000); // 15 Detik

    // --- ANTI-CHEAT: BLOKIR KLIK KANAN, SELEKSI & SHORTCUT ---
    // 1. Blokir klik kanan secara global (melengkapi oncontextmenu di <body>)
    document.addEventListener('contextmenu', event => event.preventDefault());

    // 2. Blokir drag/seleksi teks (kecuali sedang mengisi essay)
    document.addEventListener('selectstart', event => {
      if (event.target.tagName !== 'TEXTAREA' && event.target.tagName !== 'INPUT') {
        event.preventDefault();
      }
    });

    // 3. Blokir shortcut copy/paste/inspect element (F12, Ctrl+C, Ctrl+A, dll)
    document.addEventListener('keydown', function(event) {
      if (
        event.key === 'F12' || 
        (event.ctrlKey && ['u', 'U', 's', 'S', 'p', 'P', 'c', 'C', 'a', 'A'].includes(event.key)) ||
        (event.ctrlKey && event.shiftKey && ['i', 'I', 'j', 'J', 'c', 'C'].includes(event.key))
      ) {
        event.preventDefault();
      }
    });
  </script>
</body>
</html>
