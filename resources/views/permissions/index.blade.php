@extends('layouts.app')
@section('title', 'Perizinan Santri')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@push('styles')
  <style>
    /* Palet mengikuti Berry Dashboard. Font mengikuti template (Public Sans). */
    .iz-page {
      --iz-heading: #121926;
      --iz-body: #364152;
      --iz-muted: #697586;
      --iz-line: #e3e8ef;
      --iz-surface: #ffffff;
      --iz-soft: #f8fafc;

      --iz-primary: #2196f3;
      --iz-primary-dark: #1e88e5;
      --iz-primary-light: #e3f2fd;
      --iz-secondary: #673ab7;
      --iz-secondary-dark: #5e35b1;
      --iz-secondary-light: #ede7f6;
      --iz-success: #00a152;
      --iz-success-light: #e3f9ec;
      --iz-warning: #b26a00;
      --iz-warning-light: #fff4d6;
      --iz-danger: #e53935;
      --iz-danger-light: #ffebee;

      padding-bottom: 5rem; /* ruang untuk tombol mengambang di mobile */
    }

    /* ---------- Kartu (mengikuti .card Berry) ---------- */
    .iz-page .card {
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      box-shadow: 0 1px 2px rgba(18, 25, 38, .04);
      margin-bottom: 0;
    }

    /* ---------- Header halaman ---------- */
    .iz-page .page-header { margin-bottom: 1.25rem; }
    .iz-page .page-header .breadcrumb { margin-bottom: .35rem; font-size: .85rem; }
    .iz-page .page-header-title h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--iz-heading);
      margin: 0;
    }

    .iz-btn-add {
      border-radius: 8px;
      font-weight: 500;
      padding: .6rem 1.1rem;
    }

    .iz-fab {
      position: fixed;
      right: 1rem;
      bottom: calc(1rem + env(safe-area-inset-bottom, 0px));
      z-index: 1030;
      width: 3.5rem;
      height: 3.5rem;
      border-radius: 50%;
      display: none;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      padding: 0;
      box-shadow: 0 8px 20px rgba(33, 150, 243, .4);
    }

    /* ---------- Kartu statistik berwarna ---------- */
    .iz-stat {
      position: relative;
      overflow: hidden;
      color: #fff;
      border: 0 !important;
      border-radius: 12px;
      padding: 1.15rem 1.25rem;
      height: 100%;
    }
    .iz-stat::before,
    .iz-stat::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, .12);
      pointer-events: none;
    }
    .iz-stat::before { width: 9rem; height: 9rem; top: -4.5rem; right: -3rem; }
    .iz-stat::after { width: 7rem; height: 7rem; top: -5.5rem; right: 2.5rem; background: rgba(255, 255, 255, .08); }
    .iz-stat-inner { position: relative; z-index: 1; display: flex; align-items: center; gap: .9rem; }
    .iz-stat-icon {
      flex: 0 0 auto;
      width: 2.75rem;
      height: 2.75rem;
      border-radius: 10px;
      background: rgba(255, 255, 255, .2);
      display: grid;
      place-items: center;
      font-size: 1.3rem;
    }
    .iz-stat-label { font-size: .85rem; opacity: .9; line-height: 1.2; }
    .iz-stat-value { font-size: 1.9rem; font-weight: 600; line-height: 1.15; }
    .iz-stat--out { background: var(--iz-secondary-dark); }
    .iz-stat--late { background: var(--iz-danger); }
    .iz-stat--ok { background: var(--iz-primary-dark); }

    /* ---------- Tab ---------- */
    .iz-tabs {
      gap: .5rem;
      margin-bottom: 1rem;
      flex-wrap: nowrap;
      overflow-x: auto;
      scrollbar-width: none;
    }
    .iz-tabs::-webkit-scrollbar { display: none; }
    .iz-tabs .nav-link {
      color: var(--iz-body);
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 8px;
      font-weight: 500;
      font-size: .9rem;
      padding: .55rem 1.1rem;
      white-space: nowrap;
    }
    .iz-tabs .nav-link:hover { background: var(--iz-secondary-light); color: var(--iz-secondary-dark); }
    .iz-tabs .nav-link.active {
      background: var(--iz-secondary-light);
      border-color: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
    }
    .iz-tab-count {
      display: inline-block;
      min-width: 1.4rem;
      margin-left: .4rem;
      padding: .05rem .45rem;
      border-radius: 999px;
      background: var(--iz-secondary-dark);
      color: #fff;
      font-size: .72rem;
      text-align: center;
    }

    /* ---------- Pencarian & filter ---------- */
    .iz-search { position: relative; }
    .iz-search > .bi {
      position: absolute;
      left: .95rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--iz-muted);
      pointer-events: none;
    }
    .iz-search .form-control,
    .iz-field {
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 8px;
      padding: .65rem 1rem;
      font-size: .9rem;
    }
    .iz-search .form-control { padding-left: 2.5rem; }
    .iz-search .form-control:focus,
    .iz-field:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }

    /* ---------- Akordeon asrama ---------- */
    .iz-group { overflow: hidden; margin-bottom: 1rem; }
    .iz-group .accordion-button {
      background: var(--iz-surface);
      color: var(--iz-heading);
      font-weight: 600;
      padding: 1rem 1.25rem;
      gap: .75rem;
      box-shadow: none;
    }
    .iz-group .accordion-button:not(.collapsed) {
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
    }
    .iz-group .accordion-button:focus-visible {
      outline: 2px solid var(--iz-secondary);
      outline-offset: -2px;
    }
    .iz-group-count {
      margin-left: auto;
      margin-right: .5rem;
      font-size: .78rem;
      font-weight: 600;
      color: var(--iz-secondary-dark);
      background: var(--iz-surface);
      border-radius: 999px;
      padding: .2rem .65rem;
      white-space: nowrap;
    }

    /* ---------- Tabel (desktop) ---------- */
    .iz-table { margin: 0; font-size: .88rem; color: var(--iz-body); }
    .iz-table thead th {
      background: var(--iz-soft);
      color: var(--iz-heading);
      font-size: .82rem;
      font-weight: 600;
      border-bottom: 1px solid var(--iz-line);
      padding: .8rem .9rem;
      white-space: nowrap;
    }
    .iz-table tbody td {
      padding: .85rem .9rem;
      border-color: var(--iz-line);
      vertical-align: middle;
    }
    .iz-table tbody tr:last-child td { border-bottom: 0; }

    .iz-student { display: flex; align-items: center; gap: .75rem; }
    .iz-avatar {
      flex: 0 0 auto;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
      font-weight: 600;
      display: grid;
      place-items: center;
    }
    .iz-student-name { font-weight: 600; color: var(--iz-heading); line-height: 1.25; }
    .iz-student-meta { color: var(--iz-muted); font-size: .8rem; }

    /* Badge ala Berry (varian light) */
    .iz-chip {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      font-size: .78rem;
      font-weight: 600;
      padding: .3rem .65rem;
      border-radius: 6px;
      white-space: nowrap;
    }
    .iz-chip--type { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-chip--late { background: var(--iz-danger-light); color: var(--iz-danger); }
    .iz-chip--ok { background: var(--iz-success-light); color: var(--iz-success); }

    .iz-actions { display: flex; justify-content: flex-end; align-items: center; gap: .4rem; }
    .iz-icon-btn {
      width: 2.25rem;
      height: 2.25rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      border: 1px solid var(--iz-line);
      background: var(--iz-surface);
      color: var(--iz-muted);
      transition: all .15s ease;
    }
    .iz-icon-btn:hover {
      background: var(--iz-secondary-light);
      border-color: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
    }
    .iz-return-btn {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      border: 0;
      border-radius: 8px;
      background: var(--iz-success);
      color: #fff;
      font-weight: 500;
      font-size: .85rem;
      height: 2.25rem;
      padding: 0 .9rem;
      transition: background-color .15s ease;
    }
    .iz-return-btn:hover { background: #008a45; }

    /* ---------- Kosong & pagination ---------- */
    .iz-empty { text-align: center; color: var(--iz-muted); padding: 3rem 1rem; }
    .iz-empty .bi { font-size: 2.25rem; display: block; margin-bottom: .5rem; color: #b6c0cd; }

    .iz-page .pagination { margin: 0; flex-wrap: wrap; gap: .2rem; }
    .iz-page .page-link { border-radius: 8px !important; border: 1px solid var(--iz-line); color: var(--iz-body); }
    .iz-page .page-item.active .page-link {
      background: var(--iz-secondary-dark);
      border-color: var(--iz-secondary-dark);
      color: #fff;
    }

    /* Tombol detail untuk tampilan mobile */
    .iz-expand-toggle {
      display: none;
      flex: 0 0 auto;
      align-items: center;
      justify-content: center;
      gap: .3rem;
      border: 1px solid var(--iz-line);
      border-radius: 7px;
      background: var(--iz-surface);
      color: var(--iz-secondary-dark);
      font-size: .78rem;
      padding: .35rem .55rem;
      white-space: nowrap;
    }
    .iz-expand-toggle i { transition: transform .15s ease; }
    .iz-expand-toggle[aria-expanded="true"] i { transform: rotate(180deg); }

    /* ---------- Mobile: baris tabel menjadi kartu ---------- */
    @media (max-width: 767.98px) {
      .iz-page .page-header-title h2 { font-size: 1.25rem; }
      .iz-header-btn { display: none !important; }
      .iz-fab { display: inline-flex; }

      /* Statistik mobile: tetap horizontal seperti desktop */
      .iz-stat { padding: .65rem .5rem; }
      .iz-stat-inner {
        flex-direction: row;
        align-items: center;
        gap: .25rem;
      }
      .iz-stat-icon {
        flex: 0 0 auto;
        width: 1.8rem;
        height: 1.8rem;
        font-size: .9rem;
      }
      .iz-stat-inner > div:last-child { min-width: 0; }
      .iz-stat-label {
        min-height: 0;
        font-size: .5rem;
        line-height: 1.2;
        text-align: left;
        white-space: nowrap;
      }
      .iz-stat-value {
        font-size: 1.25rem;
        line-height: 1.2;
        white-space: nowrap;
      }

      .iz-tabs { display: flex; }
      .iz-tabs .nav-item { flex: 1 1 0; }
      .iz-tabs .nav-link { width: 100%; text-align: center; padding: .55rem .5rem; }

      .iz-table thead { display: none; }
      .iz-table,
      .iz-table tbody,
      .iz-table tr,
      .iz-table td { display: block; width: 100%; }

      .iz-table tbody { padding: .6rem; background: var(--iz-soft); }
      .iz-table tbody tr {
        border: 1px solid var(--iz-line);
        border-left: 4px solid var(--iz-line);
        border-radius: 10px;
        padding: .35rem .75rem .65rem;
        margin-bottom: .65rem;
        background: var(--iz-surface);
      }
      .iz-table tbody tr:last-child { margin-bottom: 0; }
      .iz-table tbody tr.is-late { border-left-color: var(--iz-danger); }
      .iz-table tbody tr.is-ok { border-left-color: var(--iz-success); }

      .iz-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
        padding: .45rem 0;
        border: 0;
        text-align: right;
        background: transparent;
        box-shadow: none;
      }
      .iz-table tbody td[data-label]::before {
        content: attr(data-label);
        flex: 0 0 auto;
        color: var(--iz-muted);
        font-size: .8rem;
        text-align: left;
      }
      .iz-table tbody td.cell-name {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .5rem;
        text-align: left;
        padding-top: .65rem;
        padding-bottom: .65rem;
        margin-bottom: 0;
      }
      .iz-table .iz-student { flex: 1 1 auto; min-width: 0; gap: .55rem; }
      .iz-table .iz-student-name { overflow-wrap: anywhere; }
      .iz-expand-toggle { display: inline-flex; }

      /* Pada awalnya, detail disembunyikan; nama dan aksi tetap terlihat. */
      .iz-table--expandable tbody tr:not(.is-expanded) td[data-label] {
        display: none;
      }
      .iz-table--expandable tbody tr.is-expanded td[data-label] {
        display: flex;
      }
      .iz-table--expandable tbody tr.is-expanded td.cell-name {
        border-bottom: 1px dashed var(--iz-line);
        margin-bottom: .25rem;
      }
      .iz-table tbody td.cell-actions {
        display: block;
        padding-top: .65rem;
        margin-top: .1rem;
        border-top: 1px dashed var(--iz-line);
      }
      .iz-table tbody td.cell-empty { display: block; text-align: center; }
      .iz-table tbody tr.row-empty { border-left-width: 1px; }

      .iz-actions { width: 100%; justify-content: stretch; }
      .iz-actions form { flex: 1 1 auto; display: flex; }
      .iz-actions .iz-return-btn { width: 100%; justify-content: center; height: 2.6rem; }
      .iz-actions .iz-icon-btn { width: 2.6rem; height: 2.6rem; }
    }

    /* Penyesuaian tambahan untuk HP berlayar kecil */
    @media (max-width: 380px) {
      .iz-stat { padding: .55rem .3rem; }
      .iz-stat-inner { gap: .3rem; }
      .iz-stat-icon {
        width: 1.4rem;
        height: 1.4rem;
        font-size: .75rem;
      }
      .iz-stat-label { font-size: .56rem; }
      .iz-stat-value { font-size: 1rem; }
    }

    @media (prefers-reduced-motion: reduce) {
      .iz-page * { transition: none !important; }
    }
  </style>
@endpush

@section('content')
  @php
    $totalActive = $activePermissions->count();
    $lateCount = $activePermissions->filter(fn($p) => now()->gt($p->end_date))->count();
    $onTimeCount = $totalActive - $lateCount;
  @endphp

  {{-- Tanpa wrapper .container: template Berry sudah membungkus konten dengan .pc-container / .pc-content --}}
  <div class="iz-page">

    {{-- Header halaman (gaya Berry) --}}
    <div class="page-header">
      <div class="page-block">
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
          <li class="breadcrumb-item active" aria-current="page">Perizinan Santri</li>
        </ul>
        <div class="row align-items-center g-3">
          <div class="col">
            <div class="page-header-title">
              <h2>Perizinan Santri</h2>
            </div>
          </div>
          <div class="col-auto">
            <a href="{{ route('permissions.create') }}"
              class="btn btn-primary iz-btn-add iz-header-btn d-inline-flex align-items-center">
              <i class="bi bi-plus-lg me-2"></i>Buat izin baru
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-2 g-md-3 mb-4">
      <div class="col-4">
        <div class="card iz-stat iz-stat--out">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-box-arrow-right"></i></div>
            <div>
              <div class="iz-stat-label">Sedang di luar</div>
              <div class="iz-stat-value">{{ $totalActive }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card iz-stat iz-stat--late">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
              <div class="iz-stat-label">Melewati batas</div>
              <div class="iz-stat-value">{{ $lateCount }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card iz-stat iz-stat--ok">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-clock-history"></i></div>
            <div>
              <div class="iz-stat-label">Masih dlm batas</div>
              <div class="iz-stat-value">{{ $onTimeCount }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Tab --}}
    <ul class="nav nav-pills iz-tabs" id="pills-tab" role="tablist">
      <li class="nav-item">
        <button class="nav-link active" id="pills-active-tab" data-bs-toggle="pill" data-bs-target="#pills-active"
          type="button" role="tab">
          Sedang izin<span class="iz-tab-count">{{ $totalActive }}</span>
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history"
          type="button" role="tab">
          Riwayat izin
        </button>
      </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">

      {{-- ============ TAB 1: SEDANG IZIN ============ --}}
      <div class="tab-pane fade show active" id="pills-active">
        @php
          // Kelompokkan data izin berdasarkan asrama dari relasi student
          $groupedActivePermissions = $activePermissions
              ->groupBy(function ($perm) {
                  return $perm->student->dormitory ?? 'Tanpa Asrama';
              })
              ->sortKeys();
        @endphp

        <div class="iz-search mb-3">
          <i class="bi bi-search"></i>
          <input type="search" id="searchActivePermission" class="form-control"
            placeholder="Cari nama santri yang sedang izin" autocomplete="off">
        </div>

        <div class="accordion" id="accordionActivePermissions">
          @forelse($groupedActivePermissions as $dormitory => $perms)
            <div class="card iz-group accordion-item">
              <h2 class="accordion-header" id="heading-{{ Str::slug($dormitory) }}">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                  data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($dormitory) }}"
                  aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                  aria-controls="collapse-{{ Str::slug($dormitory) }}">
                  <i class="bi bi-building"></i>
                  <span>{{ $dormitory }}</span>
                  <span class="iz-group-count">{{ $perms->count() }} santri</span>
                </button>
              </h2>
              <div id="collapse-{{ Str::slug($dormitory) }}"
                class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                aria-labelledby="heading-{{ Str::slug($dormitory) }}" data-bs-parent="#accordionActivePermissions">
                <div class="accordion-body p-0">
                  <div class="table-responsive">
                    <table class="table iz-table iz-table--expandable align-middle">
                      <thead>
                        <tr>
                          <th class="ps-4">Santri</th>
                          <th>Kamar</th>
                          <th>Jenis</th>
                          <th>Petugas</th>
                          <th>Keluar</th>
                          <th>Batas kembali</th>
                          <th>Status</th>
                          <th class="text-end pe-4">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($perms as $perm)
                          @php $isLate = now()->gt($perm->end_date); @endphp
                          <tr class="{{ $isLate ? 'is-late' : 'is-ok' }}">
                            <td class="ps-md-4 cell-name">
                              <div class="iz-student">
                                <div class="iz-avatar">{{ mb_strtoupper(mb_substr($perm->student->name, 0, 1)) }}</div>
                                <div>
                                  <div class="iz-student-name js-student-name">{{ $perm->student->name }}</div>
                                  <div class="iz-student-meta">{{ $perm->student->class_group ?? '-' }}</div>
                                </div>
                              </div>
                              <button type="button" class="iz-expand-toggle" aria-expanded="false" aria-label="Tampilkan detail izin">
                                Detail <i class="bi bi-chevron-down"></i>
                              </button>
                            </td>
                            <td data-label="Kamar">{{ $perm->student->room ?? '-' }}</td>
                            <td data-label="Jenis">
                              <span class="iz-chip iz-chip--type">{{ ucfirst($perm->type) }}</span>
                            </td>
                            <td data-label="Petugas">
                              <span class="fw-semibold">{{ $perm->user->name ?? '-' }}</span>
                            </td>
                            <td data-label="Waktu keluar">
                              {{ $perm->start_date->locale('id')->translatedFormat('d M H:i') }}
                            </td>
                            <td data-label="Batas kembali">
                              <span class="fw-semibold {{ $isLate ? 'text-danger' : '' }}">
                                {{ $perm->end_date->locale('id')->translatedFormat('d M H:i') }}
                              </span>
                            </td>
                            <td data-label="Sisa waktu">
                              @if ($isLate)
                                <span class="iz-chip iz-chip--late">
                                  <i class="bi bi-exclamation-circle"></i>
                                  Telat {{ $perm->end_date->locale('id')->diffForHumans(null, true) }}
                                </span>
                              @else
                                <span class="iz-chip iz-chip--ok">
                                  <i class="bi bi-clock"></i>
                                  Sisa {{ $perm->end_date->locale('id')->diffForHumans(null, true) }}
                                </span>
                              @endif
                            </td>
                            <td class="text-end pe-md-4 cell-actions">
                              <div class="iz-actions">
                                <a href="{{ route('permissions.print', $perm->id) }}" target="_blank"
                                  class="iz-icon-btn" title="Lihat dan cetak surat" aria-label="Lihat dan cetak surat">
                                  <i class="bi bi-printer"></i>
                                </a>
                                <a href="{{ route('permissions.downloadpdf', $perm->id) }}" target="_blank"
                                  class="iz-icon-btn" title="Unduh PDF surat izin" aria-label="Unduh PDF surat izin">
                                  <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('permissions.return', $perm->id) }}" method="POST"
                                  class="d-inline">
                                  @csrf
                                  @method('PUT')
                                  <button type="submit" class="iz-return-btn btn-return"
                                    data-name="{{ $perm->student->name }}">
                                    <i class="bi bi-check-lg"></i> Kembali
                                  </button>
                                </form>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="card">
              <div class="iz-empty">
                <i class="bi bi-house-check"></i>
                Semua santri sudah di asrama. Tidak ada yang sedang izin di luar.
              </div>
            </div>
          @endforelse
        </div>

        <div id="activeSearchEmpty" class="card d-none">
          <div class="iz-empty">
            <i class="bi bi-search"></i>
            Tidak ada santri dengan nama tersebut.
          </div>
        </div>
      </div>

      {{-- ============ TAB 2: RIWAYAT ============ --}}
      <div class="tab-pane fade" id="pills-history">
        <div class="row g-2 mb-3">
          <div class="col-12 col-md-8">
            <div class="iz-search">
              <i class="bi bi-search"></i>
              <input type="search" id="searchHistory" class="form-control"
                placeholder="Cari nama santri, alasan, atau petugas" autocomplete="off">
            </div>
          </div>
          <div class="col-12 col-md-4">
            <input type="month" id="filterMonthHistory" class="form-control iz-field" title="Filter bulan dan tahun"
              aria-label="Filter bulan dan tahun">
          </div>
        </div>

        <div id="history-container">
          <div class="card overflow-hidden">
            <div class="table-responsive">
              <table class="table iz-table iz-table--expandable align-middle">
                <thead>
                  <tr>
                    <th class="ps-4">Santri</th>
                    <th>Jenis</th>
                    <th>Petugas</th>
                    <th>Alasan</th>
                    <th>Tgl keluar</th>
                    <th>Tgl kembali</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($historyPermissions as $perm)
                    <tr class="{{ $perm->status == 'late' ? 'is-late' : 'is-ok' }}">
                      <td class="ps-md-4 cell-name">
                        <div class="iz-student">
                          <div class="iz-avatar">{{ mb_strtoupper(mb_substr($perm->student->name, 0, 1)) }}</div>
                          <div class="iz-student-name">{{ $perm->student->name }}</div>
                        </div>
                        <button type="button" class="iz-expand-toggle" aria-expanded="false" aria-label="Tampilkan detail riwayat izin">
                          Detail <i class="bi bi-chevron-down"></i>
                        </button>
                      </td>
                      <td data-label="Jenis">
                        <span class="iz-chip iz-chip--type">{{ ucfirst($perm->type) }}</span>
                      </td>
                      <td data-label="Petugas"><span class="fw-semibold">{{ $perm->user->name ?? '-' }}</span></td>
                      <td data-label="Alasan">{{ Str::limit($perm->reason, 30) }}</td>
                      <td data-label="Tgl keluar">{{ $perm->start_date->format('d/m/y H:i') }}</td>
                      <td data-label="Tgl kembali">
                        {{ $perm->returned_at ? $perm->returned_at->format('d/m/y H:i') : '-' }}
                      </td>
                      <td data-label="Status">
                        @if ($perm->status == 'late')
                          <span class="iz-chip iz-chip--late"><i class="bi bi-exclamation-circle"></i>Terlambat</span>
                        @elseif($perm->status == 'returned')
                          <span class="iz-chip iz-chip--ok"><i class="bi bi-check-circle"></i>Tepat waktu</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr class="row-empty">
                      <td colspan="7" class="cell-empty">
                        <div class="iz-empty py-4">
                          <i class="bi bi-inbox"></i>
                          Riwayat izin tidak ditemukan. Coba ubah kata kunci atau bulan.
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            <div class="p-3 border-top">
              {{ $historyPermissions->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- Tombol mengambang (khusus mobile) --}}
  <a href="{{ route('permissions.create') }}" class="btn btn-primary iz-fab" aria-label="Buat izin baru">
    <i class="bi bi-plus-lg"></i>
  </a>
@endsection

@push('scripts')
  {{-- sweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Expand/collapse detail baris tabel pada tampilan mobile.
    document.addEventListener('click', function(e) {
      const toggle = e.target.closest('.iz-expand-toggle');
      if (!toggle) return;

      const row = toggle.closest('tr');
      if (!row) return;

      const expanded = row.classList.toggle('is-expanded');
      toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      toggle.innerHTML = expanded
        ? 'Tutup <i class="bi bi-chevron-down"></i>'
        : 'Detail <i class="bi bi-chevron-down"></i>';
    });

    // 1. Notifikasi sukses (toast di tengah atas)
    @if (session('success'))
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      Toast.fire({
        icon: 'success',
        title: @json(session('success'))
      });
    @endif

    // 2. Konfirmasi santri kembali
    document.querySelectorAll('.btn-return').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const name = this.getAttribute('data-name');

        Swal.fire({
          title: 'Konfirmasi kembali',
          html: `Apakah santri <strong>${name}</strong> sudah kembali ke asrama?`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#00a152',
          cancelButtonColor: '#e53935',
          confirmButtonText: 'Ya, sudah',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // 3. Simpan dan pulihkan tab aktif menggunakan localStorage
    document.addEventListener("DOMContentLoaded", function() {
      let activeTab = null;
      try { activeTab = localStorage.getItem('activeTab'); } catch (e) {}

      if (activeTab) {
        let tabElement = document.querySelector(`button[data-bs-target="${activeTab}"]`);
        if (tabElement) {
          tabElement.click();
        }
      }

      document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
          let target = e.target.getAttribute('data-bs-target');
          try { localStorage.setItem('activeTab', target); } catch (err) {}
        });
      });
    });

    // 4. Live search santri yang sedang izin (tab 1)
    const searchInput = document.getElementById('searchActivePermission');
    const activeSearchEmpty = document.getElementById('activeSearchEmpty');

    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const filter = this.value.trim().toLowerCase();
        const accordions = document.querySelectorAll('#accordionActivePermissions .iz-group');
        let anyVisible = false;

        accordions.forEach(accordion => {
          const collapse = accordion.querySelector('.accordion-collapse');
          const toggleBtn = accordion.querySelector('.accordion-button');
          const rows = accordion.querySelectorAll('tbody tr');
          let hasVisibleRow = false;

          // Simpan kondisi awal akordeon agar bisa dipulihkan saat pencarian dikosongkan
          if (collapse && collapse.dataset.initialOpen === undefined) {
            collapse.dataset.initialOpen = collapse.classList.contains('show') ? '1' : '0';
          }

          rows.forEach(row => {
            const nameElement = row.querySelector('.js-student-name');
            if (nameElement) {
              const match = nameElement.textContent.toLowerCase().includes(filter);
              row.style.display = match ? '' : 'none';
              if (match) hasVisibleRow = true;
            }
          });

          accordion.style.display = hasVisibleRow ? '' : 'none';
          if (hasVisibleRow) anyVisible = true;

          // Saat mencari, buka asrama yang punya hasil
          if (collapse && toggleBtn) {
            const shouldOpen = filter ? hasVisibleRow : collapse.dataset.initialOpen === '1';
            collapse.classList.toggle('show', shouldOpen);
            toggleBtn.classList.toggle('collapsed', !shouldOpen);
            toggleBtn.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
          }
        });

        if (activeSearchEmpty) {
          activeSearchEmpty.classList.toggle('d-none', anyVisible || accordions.length === 0);
        }
      });
    }

    // =========================================================
    // 5. AJAX search, filter bulan & pagination (Riwayat Izin)
    // =========================================================
    let searchDebounceTimer;
    const searchHistoryInput = document.getElementById('searchHistory');
    const filterMonthInput = document.getElementById('filterMonthHistory');
    const historyContainer = document.getElementById('history-container');

    // Fungsi utama fetch data
    function fetchHistoryData(pageUrl = null) {
      const searchQuery = searchHistoryInput ? searchHistoryInput.value : '';
      const monthQuery = filterMonthInput ? filterMonthInput.value : '';

      let url = new URL(pageUrl || window.location.href);

      if (searchQuery) url.searchParams.set('search_history', searchQuery);
      else url.searchParams.delete('search_history');

      if (monthQuery) url.searchParams.set('month_history', monthQuery);
      else url.searchParams.delete('month_history');

      if (historyContainer) {
        historyContainer.style.transition = 'opacity 0.2s ease';
        historyContainer.style.opacity = '0.5';
      }

      fetch(url.toString(), {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');

          const newContent = doc.getElementById('history-container').innerHTML;
          if (historyContainer) {
            historyContainer.innerHTML = newContent;
            historyContainer.style.opacity = '1';
          }

          window.history.pushState({}, "", url.toString());
        })
        .catch(error => {
          console.error('Error fetching history:', error);
          if (historyContainer) historyContainer.style.opacity = '1';
        });
    }

    // Trigger saat mengetik (debounce 400ms)
    if (searchHistoryInput) {
      searchHistoryInput.addEventListener('input', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
          fetchHistoryData();
        }, 400);
      });
    }

    // Trigger saat memilih bulan
    if (filterMonthInput) {
      filterMonthInput.addEventListener('change', function() {
        fetchHistoryData();
      });
    }

    // Trigger saat klik pagination
    document.addEventListener('click', function(e) {
      let paginationLink = e.target.closest('#history-container .pagination a');
      if (paginationLink) {
        e.preventDefault();
        fetchHistoryData(paginationLink.href);
      }
    });
  </script>
@endpush