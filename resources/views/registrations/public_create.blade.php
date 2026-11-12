<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran Santri Baru (PSB Online) - Dayah Mataqu</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">

  <style>
    :root {
      --iz-heading: #121926;
      --iz-body: #364152;
      --iz-muted: #697586;
      --iz-line: #e3e8ef;
      --iz-surface: #ffffff;
      --iz-soft: #f8fafc;
      --iz-secondary: #673ab7;
      --iz-secondary-dark: #5e35b1;
      --iz-secondary-light: #ede7f6;
      --iz-primary: #2196f3;
      --iz-primary-light: #e3f2fd;
      --iz-success: #00a152;
      --iz-success-light: #e3f9ec;
      --iz-danger: #e53935;
      --iz-danger-light: #ffebee;
      --circle: 38px;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f4f6f9;
      color: var(--iz-body);
      margin: 0;
      padding: 0;
    }

    .psb-header {
      background: #ffffff;
      border-bottom: 1px solid var(--iz-line);
      padding: 1.15rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .psb-header .brand-title {
      font-weight: 700;
      font-size: 1.15rem;
      color: var(--iz-heading);
      margin: 0;
    }
    .psb-header .brand-sub {
      font-size: .8rem;
      color: var(--iz-muted);
      margin: 0;
    }

    .main-wrapper {
      max-width: 880px;
      margin: 2rem auto 3rem;
      padding: 0 1rem;
    }

    .psb-hero {
      background: linear-gradient(135deg, var(--iz-secondary) 0%, #3f51b5 100%);
      border-radius: 16px;
      color: #fff;
      padding: 2rem 2.25rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 10px 30px rgba(103, 58, 183, .15);
    }
    .psb-hero h2 { font-weight: 700; font-size: 1.75rem; margin-bottom: .4rem; }
    .psb-hero p { margin-bottom: 0; opacity: .9; font-size: .95rem; }

    .card-form {
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(18, 25, 38, .04);
      overflow: hidden;
    }

    /* STEP WIZARD */
    .psb-steps {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      padding: 1.5rem 1.75rem 1.25rem;
      background: #ffffff;
    }
    .psb-steps-line {
      position: absolute;
      left: 2.5rem;
      right: 2.5rem;
      top: calc(1.5rem + (var(--circle) / 2) - 1.5px);
      height: 3px;
      background: var(--iz-line);
      z-index: 1;
    }
    .psb-step {
      position: relative;
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: transparent;
      border: 0;
      padding: 0;
      cursor: pointer;
      color: var(--iz-muted);
      max-width: 100px;
    }
    .psb-step-circle {
      width: var(--circle);
      height: var(--circle);
      border-radius: 50%;
      background: var(--iz-soft);
      border: 2px solid var(--iz-line);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 600;
      color: var(--iz-muted);
      transition: all .25s ease;
    }
    .psb-step-text {
      font-size: .75rem;
      font-weight: 600;
      margin-top: .4rem;
      text-align: center;
      transition: color .25s ease;
      white-space: nowrap;
    }
    .psb-step.active .psb-step-circle {
      background: var(--iz-secondary);
      border-color: var(--iz-secondary);
      color: #fff;
      box-shadow: 0 0 0 4px var(--iz-secondary-light);
    }
    .psb-step.active .psb-step-text { color: var(--iz-secondary); }
    .psb-step.completed .psb-step-circle {
      background: var(--iz-success);
      border-color: var(--iz-success);
      color: #fff;
    }
    .psb-step.completed .psb-step-text { color: var(--iz-success); }

    .form-body {
      padding: 2rem;
      border-top: 1px solid var(--iz-line);
    }

    .step-section-head {
      display: flex;
      align-items: center;
      gap: .85rem;
      margin-bottom: 1.5rem;
    }
    .step-section-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: var(--iz-secondary-light);
      color: var(--iz-secondary);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }
    .step-section-title {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--iz-heading);
      margin: 0;
    }
    .step-section-desc {
      font-size: .85rem;
      color: var(--iz-muted);
      margin: 0;
    }

    .bank-box {
      background: #f0f7ff;
      border: 1px solid #c8e1ff;
      border-radius: 12px;
      padding: 1.25rem;
      margin-bottom: 1.5rem;
    }
    .bank-badge {
      background: #0056b3;
      color: #fff;
      font-size: .75rem;
      font-weight: 700;
      padding: .2rem .6rem;
      border-radius: 4px;
      display: inline-block;
      margin-bottom: .4rem;
    }

    .form-label {
      font-weight: 600;
      font-size: .85rem;
      color: var(--iz-heading);
      margin-bottom: .35rem;
    }
    .form-control, .form-select {
      border: 1px solid #cdd5df;
      border-radius: 8px;
      padding: .65rem .95rem;
      font-size: .92rem;
      color: var(--iz-body);
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }

    .parent-card {
      background: #fafbfc;
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      padding: 1.25rem;
      margin-bottom: 1.25rem;
    }

    .upload-zone {
      border: 2px dashed #b0bec5;
      border-radius: 12px;
      background: #fafbfc;
      padding: 1.5rem;
      text-align: center;
      cursor: pointer;
      transition: all .2s;
    }
    .upload-zone:hover {
      border-color: var(--iz-secondary);
      background: #f8f6fc;
    }

    .action-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 2rem;
      padding-top: 1.25rem;
      border-top: 1px solid var(--iz-line);
    }

    .btn-secondary-custom {
      background: var(--iz-secondary);
      border-color: var(--iz-secondary);
      color: #fff;
      font-weight: 600;
      padding: .65rem 1.4rem;
      border-radius: 8px;
      transition: all .2s;
    }
    .btn-secondary-custom:hover {
      background: var(--iz-secondary-dark);
      border-color: var(--iz-secondary-dark);
      color: #fff;
    }

    @media (max-width: 767.98px) {
      .psb-hero { padding: 1.5rem 1.25rem; }
      .psb-steps { padding: 1rem .5rem; }
      .psb-step-text { font-size: .68rem; }
      .form-body { padding: 1.25rem 1rem; }
    }


    /* =========================================================
       PSB MATAQU — BRAND THEME
       Palet diambil dari logo: hijau utama dan hijau lime.
    ========================================================= */
    :root {
      --iz-heading: #12351f;
      --iz-body: #344b3a;
      --iz-muted: #718276;
      --iz-line: #e1eadf;
      --iz-soft: #f5f9f2;
      --iz-secondary: #078f2d;
      --iz-secondary-dark: #056d25;
      --iz-secondary-light: #e7f6e9;
      --iz-primary: #078f2d;
      --iz-primary-light: #edf9df;
      --iz-success: #16833b;
      --iz-success-light: #e5f6e8;
      --iz-danger: #dc3545;
      --iz-danger-light: #fff0f1;
    }

    body {
      background:
        radial-gradient(circle at 8% 8%, rgba(193, 229, 67, .16), transparent 24rem),
        radial-gradient(circle at 94% 25%, rgba(5, 144, 45, .10), transparent 26rem),
        linear-gradient(180deg, #fbfdf8 0%, #f3f9ef 48%, #f8fbf6 100%);
      color: var(--iz-body);
    }

    .psb-header {
      background: rgba(255, 255, 255, .94);
      border-bottom: 1px solid rgba(225, 234, 223, .95);
      box-shadow: 0 5px 24px rgba(18, 53, 31, .055);
      backdrop-filter: blur(14px);
    }

    .psb-header .container > div:first-child > div:first-child {
      background: linear-gradient(135deg, #078f2d, #0b7130 58%, #8bbd2f) !important;
      color: #fff !important;
      box-shadow: 0 8px 18px rgba(5, 144, 45, .22);
    }

    .psb-header .brand-title { color: #12351f; letter-spacing: .035em; }
    .psb-header .brand-sub { color: #718276; }

    .psb-header .btn-outline-secondary {
      color: #08752b;
      border-color: #a9d68e;
      background: #f5fbe9;
      border-radius: 999px;
      padding: .5rem .9rem;
      font-weight: 600;
      transition: .2s ease;
    }

    .psb-header .btn-outline-secondary:hover {
      color: #fff;
      border-color: #078f2d;
      background: #078f2d;
      transform: translateY(-1px);
    }

    .main-wrapper { max-width: 940px; margin-top: 2.25rem; }

    .psb-hero {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,.3);
      border-radius: 22px;
      padding: 2.35rem 2.5rem;
      background:
        radial-gradient(circle at 88% 18%, rgba(255,255,255,.23), transparent 9rem),
        radial-gradient(circle at 72% 110%, rgba(193,229,67,.34), transparent 15rem),
        linear-gradient(118deg, #056d25 0%, #078f2d 52%, #83b92c 100%);
      box-shadow: 0 18px 42px rgba(5, 109, 37, .19);
    }

    .psb-hero::before,
    .psb-hero::after {
      content: "";
      position: absolute;
      z-index: -1;
      border: 1px solid rgba(255,255,255,.2);
      border-radius: 50%;
      pointer-events: none;
    }

    .psb-hero::before {
      width: 230px;
      height: 230px;
      right: -55px;
      top: -115px;
      box-shadow: 0 0 0 22px rgba(255,255,255,.045), 0 0 0 48px rgba(255,255,255,.035);
    }

    .psb-hero::after {
      width: 105px;
      height: 105px;
      right: 20%;
      bottom: -72px;
      background: rgba(255,255,255,.07);
    }

    .psb-hero h2 {
      color: #fff;
      font-size: clamp(1.45rem, 3vw, 2rem);
      letter-spacing: -.035em;
      text-shadow: 0 2px 12px rgba(15, 23, 42, .12);
    }

    .psb-hero .badge {
      border: 1px solid rgba(255,255,255,.7);
      box-shadow: 0 5px 16px rgba(15, 23, 42, .12);
      padding: .65rem 1rem !important;
    }

    .card-form {
      border: 1px solid #e1eadf;
      border-radius: 20px;
      box-shadow: 0 14px 42px rgba(18, 53, 31, .075);
    }

    .psb-steps {
      background: linear-gradient(180deg, #fff 0%, #fbfef8 100%);
      padding-top: 1.65rem;
      padding-bottom: 1.4rem;
    }

    .psb-steps-line {
      height: 4px;
      border-radius: 999px;
      background: linear-gradient(90deg, #b7df86, #d8ee8a, #a7d9a8);
    }

    .psb-step-circle {
      background: #f4f8f1;
      border: 2px solid #d7e8d3;
      color: #718276;
      box-shadow: 0 3px 9px rgba(18, 53, 31, .04);
    }

    .psb-step:nth-of-type(2) .psb-step-circle { border-color: #c5e4b5; }
    .psb-step:nth-of-type(3) .psb-step-circle { border-color: #d8e99a; }
    .psb-step:nth-of-type(4) .psb-step-circle { border-color: #b9dfbd; }
    .psb-step:nth-of-type(5) .psb-step-circle { border-color: #a7d9a8; }

    .psb-step.active .psb-step-circle {
      background: linear-gradient(135deg, #078f2d, #65a928);
      border-color: transparent;
      color: #fff;
      box-shadow: 0 0 0 5px #e7f6e9, 0 7px 16px rgba(5, 144, 45, .22);
      transform: translateY(-2px);
    }

    .psb-step.active .psb-step-text { color: #078f2d; }

    .psb-step.completed .psb-step-circle {
      background: linear-gradient(135deg, #16833b, #79ad2c);
      border-color: transparent;
      color: #fff;
      box-shadow: 0 0 0 4px #e5f6e8;
    }

    .psb-step.completed .psb-step-text { color: #16833b; }

    .form-body {
      background: linear-gradient(180deg, #fff 0%, #fdfffb 100%);
      border-top-color: #e7efe3;
    }

    .step-section-head {
      padding: .15rem 0 .95rem;
      border-bottom: 1px solid #e5efdf;
    }

    .step-section-icon {
      width: 48px;
      height: 48px;
      flex: 0 0 48px;
      border-radius: 14px;
      color: #fff;
      background: linear-gradient(135deg, #078f2d, #91bd2d);
      box-shadow: 0 7px 16px rgba(5, 144, 45, .18);
    }

    .step-section-title { color: #12351f; }
    .step-section-desc { color: #718276; }

    .form-label { color: #263f2d; font-size: .84rem; }

    .form-control,
    .form-select {
      min-height: 44px;
      border: 1px solid #d4e1d1;
      border-radius: 10px;
      background-color: #fff;
      transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .form-control:hover,
    .form-select:hover { border-color: #9bc78a; }

    .form-control:focus,
    .form-select:focus {
      border-color: #078f2d;
      background-color: #fff;
      box-shadow: 0 0 0 .22rem rgba(5, 144, 45, .13);
    }

    .bank-box {
      position: relative;
      overflow: hidden;
      border: 1px solid #cce4a0;
      border-radius: 16px;
      background:
        radial-gradient(circle at 100% 0%, rgba(193, 229, 67, .22), transparent 13rem),
        linear-gradient(135deg, #f2fbe7 0%, #eff9f0 100%);
      box-shadow: 0 7px 20px rgba(5, 109, 37, .055);
    }

    .bank-badge {
      background: linear-gradient(90deg, #056d25, #078f2d);
      border-radius: 999px;
      padding: .35rem .75rem;
      letter-spacing: .035em;
    }

    .bank-box .fs-6.text-primary {
      color: #08752b !important;
      background: #fff;
      border: 1px solid #cce4a0;
      border-radius: 999px;
      padding: .45rem .8rem;
    }

    .bank-box .btn-outline-primary {
      color: #078f2d;
      border-color: #8bc58b;
      border-radius: 8px;
      font-weight: 600;
    }

    .bank-box .btn-outline-primary:hover {
      color: #fff;
      background: #078f2d;
      border-color: #078f2d;
    }

    .bank-box .alert-warning {
      background: #fffbea;
      border-color: #eadb9b !important;
      border-radius: 10px;
      color: #755d16;
    }

    .parent-card {
      border: 1px solid #e1eadf;
      border-radius: 15px;
      background: linear-gradient(135deg, #fff 0%, #f8fbf6 100%);
      box-shadow: 0 5px 16px rgba(18, 53, 31, .035);
    }

    .parent-card:has(.bi-gender-male) {
      border-left: 4px solid #1683a5;
      background: linear-gradient(110deg, #eef9f8 0%, #fff 38%);
    }

    .parent-card:has(.bi-gender-female) {
      border-left: 4px solid #8bad32;
      background: linear-gradient(110deg, #f6fae9 0%, #fff 38%);
    }

    .upload-zone {
      border: 2px dashed #9acb75;
      border-radius: 15px;
      background: linear-gradient(135deg, #f5fbe9, #eff9f0);
      padding: 1.8rem 1.25rem;
    }

    .upload-zone:hover {
      border-color: #078f2d;
      background: linear-gradient(135deg, #edf8dc, #e8f6eb);
      box-shadow: 0 8px 20px rgba(5, 144, 45, .08);
      transform: translateY(-1px);
    }

    .upload-zone > i { color: #078f2d !important; }

    .action-bar { border-top: 1px solid #e5efdf; gap: .75rem; }

    .btn-secondary-custom {
      background: linear-gradient(100deg, #078f2d, #65a928);
      border: 0;
      border-radius: 10px;
      box-shadow: 0 6px 14px rgba(5, 144, 45, .2);
    }

    .btn-secondary-custom:hover {
      background: linear-gradient(100deg, #056d25, #4e8c20);
      box-shadow: 0 8px 18px rgba(5, 144, 45, .26);
      transform: translateY(-1px);
    }

    #btnPrev {
      border-radius: 10px;
      font-weight: 600;
      border-color: #cbd8c7;
      color: #526b57;
    }

    #btnSubmit {
      border: 0;
      border-radius: 10px;
      background: linear-gradient(100deg, #16833b, #5a9f2b);
      box-shadow: 0 6px 14px rgba(22, 131, 59, .18);
    }

    #btnSubmit:hover {
      background: linear-gradient(100deg, #0f6d30, #477f22);
      transform: translateY(-1px);
    }

    .form-check-input:checked {
      background-color: #078f2d;
      border-color: #078f2d;
    }

    .alert-danger {
      border: 1px solid #fecdd3;
      border-radius: 12px;
      background: #fff1f2;
      color: #9f1239;
    }

    footer { color: #718276 !important; }

    @media (max-width: 767.98px) {
      .main-wrapper { margin-top: 1.25rem; }
      .psb-hero { padding: 1.6rem 1.25rem; border-radius: 17px; }
      .psb-hero h2 { max-width: 17rem; }
      .psb-steps { padding: 1.15rem .45rem 1rem; }
      .psb-steps-line { left: 1.8rem; right: 1.8rem; }
      .psb-step { max-width: 68px; }
      .psb-step-circle { width: 34px; height: 34px; font-size: .9rem; }
      .psb-step-text {
        font-size: .62rem;
        white-space: normal;
        line-height: 1.2;
        max-width: 65px;
      }
      .form-body { padding: 1.2rem .95rem; }
      .step-section-title { font-size: 1rem; }
      .step-section-desc { font-size: .78rem; }
      .action-bar { align-items: stretch; }
      .action-bar > button,
      .action-bar .ms-auto > button { min-height: 43px; }
    }

    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
      }
    }

  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="psb-header">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.35rem;">
          <img src="{{ asset('assets/images/logo_dayah.png') }}" alt="" class="img-fluid" style="max-height: 33px;">
        </div>
        <div>
          <h1 class="brand-title">DAYAH MATAQU</h1>
          <p class="brand-sub">Sistem Penerimaan Santri Baru (PSB Online)</p>
        </div>
      </div>
      <div>
        <a href="{{ route('psb.check') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
          <i class="bi bi-search"></i>
          <span class="d-none d-sm-inline">Cek Status Pendaftaran</span>
        </a>
      </div>
    </div>
  </header>

  <!-- MAIN CONTAINER -->
  <div class="main-wrapper">
    <!-- HERO BANNER -->
    <div class="psb-hero">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
          <h2>Formulir Pendaftaran Santri Baru</h2>
          {{-- <p>Tahun Ajaran {{ $activeYear?->name ?? '2026/2027' }} &bull; Selesaikan pembayaran biaya pendaftaran untuk membuka formulir.</p> --}}
        </div>
        <span class="badge bg-white text-dark px-3 py-2 fw-semibold rounded-pill">
          <i class="bi bi-check2-circle text-success me-1"></i> Gelombang Aktif
        </span>
      </div>
    </div>

    <!-- CARD FORMULIR -->
    <div class="card-form">
      <!-- STEP INDICATOR (5 LANGKAH) -->
      <div class="psb-steps">
        <div class="psb-steps-line"></div>
        <button type="button" class="psb-step active" data-step="1">
          <div class="psb-step-circle"><i class="bi bi-credit-card-2-front"></i></div>
          <div class="psb-step-text">Pembayaran</div>
        </button>
        <button type="button" class="psb-step" data-step="2">
          <div class="psb-step-circle"><i class="bi bi-person"></i></div>
          <div class="psb-step-text">Data Diri</div>
        </button>
        <button type="button" class="psb-step" data-step="3">
          <div class="psb-step-circle"><i class="bi bi-geo-alt"></i></div>
          <div class="psb-step-text">Alamat</div>
        </button>
        <button type="button" class="psb-step" data-step="4">
          <div class="psb-step-circle"><i class="bi bi-people"></i></div>
          <div class="psb-step-text">Orang Tua</div>
        </button>
        <button type="button" class="psb-step" data-step="5">
          <div class="psb-step-circle"><i class="bi bi-check-lg"></i></div>
          <div class="psb-step-text">Konfirmasi</div>
        </button>
      </div>

      <!-- FORM BODY -->
      <div class="form-body">
        @if ($errors->any())
          <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
            <div>
              <strong class="d-block mb-1">Ada data yang belum lengkap atau tidak valid:</strong>
              <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <form id="psbForm" action="{{ route('psb.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          @php
            $occupations = [
              'PNS', 'PPPK', 'TNI', 'POLRI', 'Pegawai BUMN/BUMD', 'Karyawan Swasta', 'Wiraswasta', 'Pedagang',
              'Petani', 'Nelayan', 'Peternak', 'Buruh', 'Sopir/Driver', 'Tukang', 'Guru/Dosen', 'Tenaga Kesehatan',
              'TKI/PMI', 'Ibu Rumah Tangga', 'Tidak Bekerja', 'Lainnya',
            ];

            $educations = [
              'Tidak Sekolah', 'Tidak Tamat SD', 'SD/MI', 'SMP/MTs', 'SMA/MA/SMK',
              'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3',
            ];
          @endphp

          <!-- =================== STEP 1: PEMBAYARAN & UPLOAD SLIP =================== -->
          <div class="form-step" id="step1">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-wallet2"></i></div>
              <div>
                <h5 class="step-section-title">Langkah 1: Biaya Pendaftaran & Upload Slip Pembayaran</h5>
                <p class="step-section-desc">Lakukan transfer ke rekening resmi PSB MATAQU dan unggah bukti transfer di bawah ini.</p>
              </div>
            </div>

            <!-- INFO REKENING RESMI -->
            <div class="bank-box">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <span class="bank-badge">REKENING RESMI PEMBAYARAN PSB</span>
                <span class="fw-bold text-primary fs-6">Biaya Pendaftaran: Rp 350.000</span>
              </div>
              <div class="row align-items-center g-2 mt-1">
                <div class="col-12 col-md-4">
                  <div class="text-muted small">Bank Tujuan:</div>
                  <strong class="text-dark fs-6"><i class="bi bi-bank me-1 text-primary"></i> Bank Syariah Indonesia (BSI)</strong>
                </div>
                <div class="col-12 col-md-4">
                  <div class="text-muted small">Nomor Rekening:</div>
                  <div class="d-flex align-items-center gap-2">
                    <strong class="text-dark fs-5 font-monospace" id="rekNum">7550550053</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" onclick="copyRekening()" title="Salin No. Rekening">
                      <i class="bi bi-clipboard"></i> Salin
                    </button>
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="text-muted small">Atas Nama:</div>
                  <strong class="text-dark">PSB MATAQU</strong>
                </div>
              </div>
              <div class="alert alert-warning border mt-3 mb-0 py-2 px-3 small">
                <i class="bi bi-info-circle-fill me-1"></i> Mohon cantumkan nama calon santri pada berita transfer, contoh: <em>PSB - Fulan</em>.
              </div>
            </div>

            <!-- FORM INPUT PEMBAYARAN -->
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Nama Pemilik Rekening / Pengirim <span class="text-danger">*</span></label>
                <input type="text" name="payment_sender_name" id="payment_sender_name" class="form-control" placeholder="Nama penyetor sesuai struk/m-banking" value="{{ old('payment_sender_name') }}" required>
                <div class="invalid-feedback">Nama pemilik rekening pengirim wajib diisi.</div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Bank Pengirim</label>
                <input type="text" name="payment_bank_name" class="form-control" placeholder="Contoh: BSI / Bank Aceh / BRI / Mandiri" value="{{ old('payment_bank_name') }}">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Nominal Transfer (Rp)</label>
                <input type="number" name="payment_amount" class="form-control" placeholder="350000" value="{{ old('payment_amount', 350000) }}">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Tanggal Transfer</label>
                <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}">
              </div>

              <div class="col-12">
                <label class="form-label">Unggah Bukti / Slip Pembayaran <span class="text-danger">*</span></label>
                <div class="upload-zone" onclick="document.getElementById('payment_receipt').click()">
                  <i class="bi bi-cloud-arrow-up fs-1 text-primary d-block mb-1"></i>
                  <span class="fw-semibold text-dark d-block">Klik di sini untuk memilih foto / file slip transfer</span>
                  <small class="text-muted d-block">Format JPG, JPEG, PNG, atau PDF (Maksimal 3 MB)</small>
                  <span id="fileChosenLabel" class="badge bg-success-subtle text-success border border-success-subtle mt-2 d-none">
                    <i class="bi bi-file-earmark-check me-1"></i> File terpilih
                  </span>
                </div>
                <input type="file" name="payment_receipt" id="payment_receipt" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleReceiptSelected(this)">
                <div class="invalid-feedback" id="receiptErrorFeedback">Wajib mengunggah bukti slip transfer sebelum melanjutkan.</div>
              </div>
            </div>
          </div>

          <!-- =================== STEP 2: DATA DIRI & JENJANG =================== -->
          <div class="form-step d-none" id="step2">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-person-badge"></i></div>
              <div>
                <h5 class="step-section-title">Langkah 2: Pilihan Jenjang & Data Calon Santri</h5>
                <p class="step-section-desc">Identitas lengkap calon santri sesuai Akta Kelahiran / KK</p>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Pilihan Jenjang Pendidikan <span class="text-danger">*</span></label>
                <select name="target_stage" class="form-select" required>
                  <option value="" disabled {{ old('target_stage') ? '' : 'selected' }}>-- Pilih Jenjang --</option>
                  @foreach($stages as $stg)
                    <option value="{{ $stg->name }}" {{ old('target_stage') == $stg->name ? 'selected' : '' }}>{{ $stg->name }}</option>
                  @endforeach
                  @if($stages->isEmpty())
                    <option value="MTS">MTS / SMP</option>
                    <option value="MA">MA / SMA</option>
                  @endif
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Sekolah Asal <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="text" name="previous_school" class="form-control" placeholder="Contoh: SDN 1 Banda Aceh / MIS" value="{{ old('previous_school') }}">
              </div>
              <div class="col-12">
                <label class="form-label">Nama Lengkap Santri <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Nama lengkap sesuai KK / Akta" value="{{ old('name') }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="gender" class="form-select" required>
                  <option value="" disabled {{ old('gender') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                  <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Anak Ke- <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="number" name="child_order" min="1" class="form-control" placeholder="Contoh: 1" value="{{ old('child_order') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-muted fw-normal">(16 digit)</span></label>
                <input type="text" name="nik" maxlength="16" class="form-control" placeholder="16 digit NIK santri" value="{{ old('nik') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" name="nisn" maxlength="20" class="form-control" placeholder="NISN dari sekolah asal" value="{{ old('nisn') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                <input type="text" name="birth_place" class="form-control" placeholder="Kota / Kabupaten tempat lahir" value="{{ old('birth_place') }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
              </div>
            </div>
          </div>

          <!-- =================== STEP 3: ALAMAT & IDENTITAS KK =================== -->
          <div class="form-step d-none" id="step3">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-geo-alt"></i></div>
              <div>
                <h5 class="step-section-title">Langkah 3: Alamat & Identitas Domisili</h5>
                <p class="step-section-desc">Alamat tempat tinggal keluarga calon santri saat ini</p>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Nomor Kartu Keluarga (KK)</label>
                <input type="text" name="family_card_number" maxlength="16" class="form-control" placeholder="16 digit nomor kartu keluarga" value="{{ old('family_card_number') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Provinsi</label>
                <input type="text" name="province" class="form-control" placeholder="Contoh: Aceh" value="{{ old('province', 'Aceh') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Kabupaten / Kota</label>
                <input type="text" name="regency" class="form-control" placeholder="Contoh: Aceh Besar" value="{{ old('regency') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Kecamatan</label>
                <input type="text" name="district" class="form-control" placeholder="Contoh: Darul Imarah" value="{{ old('district') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Gampong / Desa</label>
                <input type="text" name="village" class="form-control" placeholder="Contoh: Garot" value="{{ old('village') }}">
              </div>
            </div>
          </div>

          <!-- =================== STEP 4: ORANG TUA =================== -->
          <div class="form-step d-none" id="step4">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-people"></i></div>
              <div>
                <h5 class="step-section-title">Langkah 4: Data Orang Tua Kandung</h5>
                <p class="step-section-desc">Identitas ayah dan ibu kandung calon santri</p>
              </div>
            </div>

            <!-- AYAH -->
            <div class="parent-card">
              <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-primary">
                <i class="bi bi-gender-male"></i> Data Ayah Kandung
              </h6>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <label class="form-label">Status Ayah</label>
                  <select name="father_status" class="form-select">
                    <option value="alive" {{ old('father_status') == 'alive' ? 'selected' : '' }}>Masih Hidup</option>
                    <option value="deceased" {{ old('father_status') == 'deceased' ? 'selected' : '' }}>Meninggal Dunia</option>
                  </select>
                </div>
                <div class="col-12 col-md-8">
                  <label class="form-label">Nama Ayah Kandung</label>
                  <input type="text" name="father_name" class="form-control" placeholder="Nama lengkap ayah" value="{{ old('father_name') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">No. Telepon / WhatsApp Ayah</label>
                  <input type="tel" name="father_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('father_phone') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pendidikan Terakhir Ayah</label>
                  <select name="father_education" class="form-select">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach($educations as $edu)
                      <option value="{{ $edu }}" {{ old('father_education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pekerjaan Ayah</label>
                  <select name="father_occupation" class="form-select">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($occupations as $job)
                      <option value="{{ $job }}" {{ old('father_occupation') == $job ? 'selected' : '' }}>{{ $job }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Detail Pekerjaan Ayah</label>
                  <input type="text" name="father_occupation_detail" class="form-control" placeholder="Instansi / Jenis Usaha" value="{{ old('father_occupation_detail') }}">
                </div>
              </div>
            </div>

            <!-- IBU -->
            <div class="parent-card">
              <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-danger">
                <i class="bi bi-gender-female"></i> Data Ibu Kandung
              </h6>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <label class="form-label">Status Ibu</label>
                  <select name="mother_status" class="form-select">
                    <option value="alive" {{ old('mother_status') == 'alive' ? 'selected' : '' }}>Masih Hidup</option>
                    <option value="deceased" {{ old('mother_status') == 'deceased' ? 'selected' : '' }}>Meninggal Dunia</option>
                  </select>
                </div>
                <div class="col-12 col-md-8">
                  <label class="form-label">Nama Ibu Kandung</label>
                  <input type="text" name="mother_name" class="form-control" placeholder="Nama lengkap ibu" value="{{ old('mother_name') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">No. Telepon / WhatsApp Ibu</label>
                  <input type="tel" name="mother_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('mother_phone') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pendidikan Terakhir Ibu</label>
                  <select name="mother_education" class="form-select">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach($educations as $edu)
                      <option value="{{ $edu }}" {{ old('mother_education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pekerjaan Ibu</label>
                  <select name="mother_occupation" class="form-select">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($occupations as $job)
                      <option value="{{ $job }}" {{ old('mother_occupation') == $job ? 'selected' : '' }}>{{ $job }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Detail Pekerjaan Ibu</label>
                  <input type="text" name="mother_occupation_detail" class="form-control" placeholder="Instansi / Jenis Usaha" value="{{ old('mother_occupation_detail') }}">
                </div>
              </div>
            </div>
          </div>

          <!-- =================== STEP 5: WALI & KONFIRMASI =================== -->
          <div class="form-step d-none" id="step5">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-shield-check"></i></div>
              <div>
                <h5 class="step-section-title">Langkah 5: Data Wali (Opsional) & Konfirmasi</h5>
                <p class="step-section-desc">Lengkapi jika tinggal bersama wali dan setujui ketentuan</p>
              </div>
            </div>

            <div class="parent-card mb-4">
              <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-secondary">
                <i class="bi bi-person-badge"></i> Data Wali (Jika Ada)
              </h6>
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">Nama Wali</label>
                  <input type="text" name="guardian_name" class="form-control" placeholder="Nama wali santri" value="{{ old('guardian_name') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">No. Telepon / WhatsApp Wali</label>
                  <input type="tel" name="guardian_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('guardian_phone') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pekerjaan Wali</label>
                  <select name="guardian_occupation" class="form-select">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($occupations as $job)
                      <option value="{{ $job }}" {{ old('guardian_occupation') == $job ? 'selected' : '' }}>{{ $job }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Detail Pekerjaan Wali</label>
                  <input type="text" name="guardian_occupation_detail" class="form-control" placeholder="Instansi / Usaha wali" value="{{ old('guardian_occupation_detail') }}">
                </div>
              </div>
            </div>

            <!-- PERNYATAAN -->
            <div class="alert alert-light border p-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="agreementCheck" required>
                <label class="form-check-label small" for="agreementCheck">
                  Saya menyatakan bahwa seluruh data yang diisikan dalam formulir pendaftaran ini beserta bukti pembayaran yang dilampirkan adalah <strong>benar dan sah</strong>. Saya bersedia mengikuti seluruh tata tertib dan tahapan seleksi yang ditentukan oleh panitia PSB Dayah Mataqu.
                </label>
              </div>
            </div>
          </div>

          <!-- ACTION BAR -->
          <div class="action-bar">
            <button type="button" class="btn btn-outline-secondary" id="btnPrev" style="display: none;">
              <i class="bi bi-arrow-left me-1"></i> Sebelumnya
            </button>
            <div class="ms-auto d-flex gap-2">
              <button type="button" class="btn btn-secondary-custom" id="btnNext">
                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
              </button>
              <button type="submit" class="btn btn-success px-4 fw-semibold" id="btnSubmit" style="display: none;">
                <i class="bi bi-send-check me-1"></i> Kirim Pendaftaran
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <footer class="text-center py-4 text-muted small">
    &copy; {{ date('Y') }} Dayah Mataqu. Seluruh hak cipta dilindungi.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function copyRekening() {
      const rek = document.getElementById('rekNum').innerText.trim();
      navigator.clipboard.writeText(rek).then(() => {
        alert('Nomor rekening BSI ' + rek + ' berhasil disalin.');
      }).catch(() => {
        prompt('Salin nomor rekening:', rek);
      });
    }

    function handleReceiptSelected(input) {
      const label = document.getElementById('fileChosenLabel');
      const err = document.getElementById('receiptErrorFeedback');
      if (input.files && input.files[0]) {
        const file = input.files[0];
        label.classList.remove('d-none');
        label.innerHTML = '<i class="bi bi-file-earmark-check me-1"></i> ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        err.style.display = 'none';
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      let currentStep = 1;
      const totalSteps = 5;

      const btnPrev = document.getElementById('btnPrev');
      const btnNext = document.getElementById('btnNext');
      const btnSubmit = document.getElementById('btnSubmit');

      function showStep(step) {
        // Sembunyikan semua step
        for (let i = 1; i <= totalSteps; i++) {
          const stepEl = document.getElementById('step' + i);
          const stepBtn = document.querySelector(`.psb-step[data-step="${i}"]`);
          
          if (i === step) {
            stepEl.classList.remove('d-none');
            stepBtn.classList.add('active');
            stepBtn.classList.remove('completed');
          } else if (i < step) {
            stepEl.classList.add('d-none');
            stepBtn.classList.remove('active');
            stepBtn.classList.add('completed');
          } else {
            stepEl.classList.add('d-none');
            stepBtn.classList.remove('active', 'completed');
          }
        }

        // Tampilan tombol
        btnPrev.style.display = (step === 1) ? 'none' : 'inline-block';
        if (step === totalSteps) {
          btnNext.style.display = 'none';
          btnSubmit.style.display = 'inline-block';
        } else {
          btnNext.style.display = 'inline-block';
          btnSubmit.style.display = 'none';
        }

        window.scrollTo({ top: document.querySelector('.card-form').offsetTop - 30, behavior: 'smooth' });
      }

      function validateStep(step) {
        const stepEl = document.getElementById('step' + step);
        let valid = true;

        if (step === 1) {
          // Validasi Langkah 1 (Pembayaran)
          const sender = document.getElementById('payment_sender_name');
          const receipt = document.getElementById('payment_receipt');
          const errReceipt = document.getElementById('receiptErrorFeedback');

          if (!sender.value.trim()) {
            sender.classList.add('is-invalid');
            valid = false;
          } else {
            sender.classList.remove('is-invalid');
          }

          if (!receipt.files || receipt.files.length === 0) {
            errReceipt.style.display = 'block';
            valid = false;
          } else {
            errReceipt.style.display = 'none';
          }

          if (!valid) {
            alert('Harap isi Nama Pemilik Rekening Pengirim dan unggah Berkas Slip Bukti Pembayaran sebelum melanjutkan ke pengisian formulir pendaftaran.');
          }
          return valid;
        }

        // Validasi langkah lainnya
        const requiredInputs = stepEl.querySelectorAll('input[required], select[required]');
        requiredInputs.forEach(input => {
          if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
          } else {
            input.classList.remove('is-invalid');
          }
        });

        if (!valid) {
          alert('Harap lengkapi semua kolom yang bertanda bintang (*) sebelum melanjutkan.');
        }

        return valid;
      }

      btnNext.addEventListener('click', function () {
        if (validateStep(currentStep)) {
          if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
          }
        }
      });

      btnPrev.addEventListener('click', function () {
        if (currentStep > 1) {
          currentStep--;
          showStep(currentStep);
        }
      });

      // Klik langsung ke step wizard
      document.querySelectorAll('.psb-step').forEach(btn => {
        btn.addEventListener('click', function () {
          const target = parseInt(this.getAttribute('data-step'));
          if (target < currentStep) {
            currentStep = target;
            showStep(currentStep);
          } else if (target > currentStep) {
            if (validateStep(currentStep)) {
              currentStep = target;
              showStep(currentStep);
            }
          }
        });
      });

      // Hapus kelas is-invalid saat pengguna mulai mengisi
      document.querySelectorAll('#psbForm input, #psbForm select').forEach(input => {
        input.addEventListener('input', () => input.classList.remove('is-invalid'));
        input.addEventListener('change', () => input.classList.remove('is-invalid'));
      });
    });
  </script>
</body>
</html>