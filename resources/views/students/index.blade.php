@extends('layouts.app')
@section('title', 'Students')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  {{-- CSS DataTables & Bootstrap 5 --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@push('styles')
  <style>
    /* Palet mengikuti Berry Dashboard. Font mengikuti template (Public Sans). */
    .iz-page,
    .iz-modal {
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
      --iz-orange: #ef6c00;
    }

    /* ---------- Kartu & header halaman ---------- */
    .iz-page .card {
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      box-shadow: 0 1px 2px rgba(18, 25, 38, .04);
      margin-bottom: 0;
    }
    .iz-page .page-header { margin-bottom: 1.25rem; }
    .iz-page .page-header .breadcrumb { margin-bottom: .35rem; font-size: .85rem; }
    
    .iz-page .page-header-actions-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
    }

    .iz-page .page-header-title h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--iz-heading);
      margin: 0;
    }

    .iz-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .4rem;
      border-radius: 8px;
      font-weight: 500;
      font-size: .9rem;
      padding: .6rem 1.1rem;
      border: 0;
      white-space: nowrap;
      text-decoration: none;
      transition: background-color .15s ease;
    }
    .iz-btn--soft-ok { background: var(--iz-success-light); color: var(--iz-success); }
    .iz-btn--soft-ok:hover,
    .iz-btn--soft-ok.show { background: #cdf3dd; color: var(--iz-success); }
    .iz-btn--soft-primary { background: var(--iz-primary-light); color: var(--iz-primary-dark); }
    .iz-btn--soft-primary:hover { background: #cfe8fc; color: var(--iz-primary-dark); }
    .iz-btn--edit { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-btn--delete { background: var(--iz-danger-light); color: var(--iz-danger); }

    .iz-header-actions .dropdown-menu {
      border: 1px solid var(--iz-line);
      border-radius: 10px;
      box-shadow: 0 8px 24px rgba(18, 25, 38, .1);
      padding: .4rem;
    }
    .iz-header-actions .dropdown-item { border-radius: 6px; font-size: .9rem; padding: .5rem .75rem; }

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
    .iz-stat--total { background: var(--iz-secondary-dark); }
    .iz-stat--active { background: var(--iz-primary-dark); }
    .iz-stat--other { background: var(--iz-orange); }

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

    /* ---------- Avatar, badge, tombol aksi ---------- */
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
    .iz-chip {
      display: inline-flex;
      align-items: center;
      font-size: .76rem;
      font-weight: 600;
      padding: .28rem .6rem;
      border-radius: 6px;
      white-space: nowrap;
    }
    .iz-chip--ok { background: var(--iz-success-light); color: var(--iz-success); }
    .iz-chip--primary { background: var(--iz-primary-light); color: var(--iz-primary-dark); }
    .iz-chip--late { background: var(--iz-danger-light); color: var(--iz-danger); }
    .iz-chip--muted { background: #eef2f6; color: var(--iz-muted); }

    .iz-icon-btn {
      width: 2.1rem;
      height: 2.1rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      border: 0;
      transition: filter .15s ease;
    }
    .iz-icon-btn:hover { filter: brightness(.95); }
    .iz-icon-btn--view { background: var(--iz-primary-light); color: var(--iz-primary-dark); }
    .iz-icon-btn--edit { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-icon-btn--delete { background: var(--iz-danger-light); color: var(--iz-danger); }

    /* ---------- Tabel (desktop) ---------- */
    .iz-table { color: var(--iz-body); margin: 0; font-size: .88rem; }
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
      padding: .8rem .9rem;
      vertical-align: middle;
      border-color: var(--iz-line);
    }
    .iz-table tbody tr:hover > * { background-color: var(--iz-soft); }
    .iz-name { display: flex; align-items: center; gap: .75rem; }
    .iz-name a { font-weight: 600; color: var(--iz-heading); text-decoration: none; }
    .iz-name a:hover { color: var(--iz-secondary-dark); }
    .iz-sub { color: var(--iz-muted); font-size: .8rem; }

    /* DataTables: info & paginasi custom */
    .iz-page .dataTables_info { color: var(--iz-muted); font-size: .85rem; padding: 0 !important; }
    .iz-page .dataTables_paginate { padding: 0 !important; }
    .iz-page .pagination { margin: 0; flex-wrap: wrap; gap: .2rem; }
    .iz-page .page-link { border-radius: 8px !important; border: 1px solid var(--iz-line); color: var(--iz-body); }
    .iz-page .page-item.active .page-link {
      background: var(--iz-secondary-dark);
      border-color: var(--iz-secondary-dark);
      color: #fff;
    }
    .iz-page .dataTables_filter,
    .iz-page .dataTables_length { display: none; }

    /* ---------- Expandable card (mobile) ---------- */
    .iz-cards { display: none; }
    .iz-stu {
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      margin-bottom: .65rem;
      overflow: hidden;
    }
    .iz-stu-top {
      display: flex;
      align-items: center;
      gap: .65rem;
      padding: .85rem;
    }
    .iz-stu-id { flex: 1 1 auto; min-width: 0; }
    .iz-stu-name {
      display: block;
      font-weight: 600;
      color: var(--iz-heading);
      text-decoration: none;
      line-height: 1.35;
      overflow-wrap: anywhere;
    }
    .iz-stu-toggle {
      flex: 0 0 auto;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .25rem;
      border: 1px solid var(--iz-line);
      background: var(--iz-soft);
      color: var(--iz-body);
      border-radius: 8px;
      padding: .45rem .55rem;
      font-size: .8rem;
      white-space: nowrap;
    }
    .iz-stu-toggle i { transition: transform .2s ease; }
    .iz-stu.is-expanded .iz-stu-toggle i { transform: rotate(180deg); }
    .iz-stu-details { display: none; padding: 0 .85rem .85rem; }
    .iz-stu.is-expanded .iz-stu-details { display: block; }
    .iz-stu-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .75rem 1rem;
      margin: 0;
      padding: .85rem 0;
      border-top: 1px dashed var(--iz-line);
    }
    .iz-stu-grid dt { font-size: .72rem; font-weight: 500; color: var(--iz-muted); margin: 0 0 .15rem; }
    .iz-stu-grid dd { margin: 0; font-size: .86rem; color: var(--iz-body); overflow-wrap: anywhere; }
    .iz-stu-grid .span-2 { grid-column: 1 / -1; }
    .iz-stu-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .5rem;
      padding-top: .8rem;
      border-top: 1px solid var(--iz-line);
    }
    .iz-stu-actions .iz-btn { flex: 1 1 auto; padding: .55rem .5rem; font-size: .82rem; }

    .iz-empty { text-align: center; color: var(--iz-muted); padding: 2.5rem 1rem; }
    .iz-empty .bi { font-size: 2.25rem; display: block; margin-bottom: .5rem; color: #b6c0cd; }

    /* ---------- Modal ---------- */
    .iz-modal .modal-content { border: 0; border-radius: 12px; overflow: hidden; }
    .iz-modal .modal-header { border-bottom: 1px solid var(--iz-line); padding: 1.1rem 1.35rem; }
    .iz-modal .modal-title { font-weight: 600; color: var(--iz-heading); font-size: 1.05rem; }
    .iz-modal .modal-body { padding: 1.35rem; }
    .iz-modal .form-control {
      border: 1px solid var(--iz-line);
      border-radius: 8px;
      font-size: .92rem;
      padding: .65rem .9rem;
    }
    .iz-modal .form-control:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }
    .iz-modal input[type="file"] { border-style: dashed; border-color: #b6c0cd; }
    .iz-modal input[type="file"]::file-selector-button {
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
      border: 0;
      border-radius: 6px;
      padding: .4rem .85rem;
      margin-right: .85rem;
      font-weight: 600;
    }
    .iz-note {
      display: flex;
      gap: .7rem;
      background: var(--iz-primary-light);
      color: var(--iz-primary-dark);
      border-radius: 10px;
      padding: .8rem 1rem;
      font-size: .86rem;
      margin-bottom: 1rem;
    }
    .iz-note .bi { margin-top: .1rem; }

    /* SweetAlert di atas modal Bootstrap */
    .swal2-container { z-index: 2000 !important; }

    /* ---------- Mobile (< 768px) ---------- */
    @media (max-width: 767.98px) {
      .iz-page .page-header-title h2 { font-size: 1.25rem; }
      .iz-page .page-header-actions-row { flex-direction: column; align-items: stretch; gap: 1rem; }
      .iz-header-actions { display: flex; flex-wrap: wrap; gap: .5rem; width: 100%; }
      .iz-header-actions > * { flex: 1 1 calc(50% - .5rem); }
      .iz-header-actions .iz-btn { width: 100%; padding: .65rem .5rem; }

      .iz-stat { padding: .8rem; }
      .iz-stat-inner { flex-direction: column; align-items: flex-start; gap: .4rem; }
      .iz-stat-icon { width: 2.1rem; height: 2.1rem; font-size: 1rem; }
      .iz-stat-label { font-size: .7rem; }
      .iz-stat-value { font-size: 1.4rem; }

      /* Tabel disembunyikan, diganti daftar kartu */
      .iz-table-wrap { display: none; }
      .iz-cards { display: block; }
      .iz-list-body { padding: .75rem !important; }

      /* Paginasi ringkas: sebelumnya, halaman aktif, berikutnya */
      #customPagination { flex-direction: column; text-align: center; gap: .6rem !important; }
      #customPagination .dataTables_paginate { margin-left: 0 !important; }
      #customPagination .pagination { justify-content: center; }
      #customPagination .paginate_button:not(.active):not(.previous):not(.next) { display: none; }

      .iz-modal .modal-body { padding: 1.1rem; }
      .iz-modal .iz-btn { width: 100%; padding: .75rem 1rem; }
    }

    @media (prefers-reduced-motion: reduce) {
      .iz-page *, .iz-modal * { transition: none !important; }
    }
  </style>
@endpush

@section('content')
  @php
    $canManage = auth()->check() && auth()->user()->can('kelola-data-santri');
    $semuaSantri = collect($students);
    $totalSantri = $semuaSantri->count();
    $totalAktif = $semuaSantri->where('status', 'active')->count();
    $totalLainnya = $totalSantri - $totalAktif;
  @endphp

  <div class="iz-page">

    {{-- Header halaman: breadcrumb di atas, judul dan tombol sejajar kiri-kanan --}}
    <div class="page-header">
      <div class="page-block">
        <ul class="breadcrumb mb-3">
          <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
          <li class="breadcrumb-item active" aria-current="page">Data Santri</li>
        </ul>

        <div class="page-header-actions-row">
          {{-- Judul (Kiri) --}}
          <div class="page-header-title">
            <h2>Data Santri</h2>
          </div>

          {{-- Kumpulan Tombol (Kanan) --}}
          <div class="iz-header-actions d-flex gap-2 flex-row flex-wrap align-items-center">
            {{-- Dropdown Export --}}
            <div class="dropdown">
              <button class="iz-btn iz-btn--soft-ok dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-download"></i> Export
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('students.export', ['format' => 'xlsx']) }}"><i
                      class="bi bi-file-earmark-excel text-success me-2"></i>Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('students.export', ['format' => 'csv']) }}"><i
                      class="bi bi-filetype-csv text-primary me-2"></i>CSV</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" id="btnExportPrint"><i
                      class="bi bi-printer text-secondary me-2"></i>Print</a></li>
              </ul>
            </div>

            @can('kelola-data-santri')
              <button type="button" class="iz-btn iz-btn--soft-primary" data-bs-toggle="modal"
                data-bs-target="#importModal">
                <i class="bi bi-file-earmark-spreadsheet"></i> Import
              </button>
              <button type="button" class="btn btn-primary iz-btn" data-bs-toggle="modal"
                data-bs-target="#checkNisnModal">
                <i class="bi bi-plus-lg"></i> Tambah
              </button>
            @endcan
          </div>
        </div>
      </div>
    </div>

    {{-- Statistik --}}
    {{-- <div class="row g-2 g-md-3 mb-4">
      <div class="col-4">
        <div class="card iz-stat iz-stat--total">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-people"></i></div>
            <div>
              <div class="iz-stat-label">Total santri</div>
              <div class="iz-stat-value">{{ $totalSantri }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card iz-stat iz-stat--active">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-person-check"></i></div>
            <div>
              <div class="iz-stat-label">Santri aktif</div>
              <div class="iz-stat-value">{{ $totalAktif }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card iz-stat iz-stat--other">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-mortarboard"></i></div>
            <div>
              <div class="iz-stat-label">Lulus / lainnya</div>
              <div class="iz-stat-value">{{ $totalLainnya }}</div>
            </div>
          </div>
        </div>
      </div>
    </div> --}}

    {{-- Pencarian & jumlah baris --}}
    <div class="row g-2 mb-3">
      <div class="col-12 col-md-6 col-lg-5">
        <div class="iz-search">
          <i class="bi bi-search"></i>
          <input type="search" id="customSearchBox" class="form-control" placeholder="Cari nama, NIS, dll"
            autocomplete="off">
        </div>
      </div>
      <div class="col-12 col-md-3 col-lg-2">
        <select id="customLengthChange" class="form-select iz-field" aria-label="Jumlah baris">
          <option value="10">10 baris</option>
          <option value="25">25 baris</option>
          <option value="50">50 baris</option>
          <option value="-1">Semua</option>
        </select>
      </div>
    </div>

    {{-- Daftar santri --}}
    <div class="card overflow-hidden">
      <div class="card-body p-0 iz-list-body">

        {{-- Tabel (desktop) --}}
        <div class="table-responsive iz-table-wrap">
          <table id="myTable" class="table iz-table table-hover align-middle w-100">
            <thead>
              <tr>
                <th class="ps-4">No</th>
                <th>Nama Santri</th>
                <th>NISN / NIS</th>
                <th>Tempat / Tgl Lahir</th>
                <th>Asrama / Kamar</th>
                <th>Jenjang</th>
                <th>Status</th>
                <th class="text-end pe-4 no-export" style="min-width: 110px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($students as $student)
                @php
                  $statusCls = match ($student->status) {
                      'active' => 'ok',
                      'graduated' => 'primary',
                      'suspended' => 'late',
                      default => 'muted',
                  };
                  $statusText = match ($student->status) {
                      'active' => 'Aktif',
                      'graduated' => 'Lulus',
                      'suspended' => 'Skorsing',
                      'moved' => 'Pindah',
                      default => $student->status,
                  };
                  $gender =$student->gender == 'L' ? 'Laki-laki' : 'Perempuan';
                  $tglLahir =$student->birth_date
                      ? \Carbon\Carbon::parse($student->birth_date)->locale('id')->translatedFormat('d F Y')
                      : '-';

                  $card = [
                      'nama' => $student->name,
                      'gender' => $gender,
                      'nisn' => $student->nisn,
                      'nis' => $student->nis,
                      'tempat' => $student->birth_place,
                      'tglLahir' => $tglLahir,
                      'asrama' => $student->dormitory ?? 'Non-Mukim',
                      'kamar' => $student->room,
                      'jenjang' => $student->education_level,
                      'status' => $statusText,
                      'statusCls' => $statusCls,
                      'show' => route('students.show', $student->id),
                      'edit' => $canManage ? route('students.edit', $student->id) : null,
                      'delete' => $canManage ? route('students.destroy', $student->id) : null,
                  ];
                @endphp
                <tr data-card="{{ json_encode($card, JSON_UNESCAPED_UNICODE) }}">
                  <td class="ps-4">{{ $loop->iteration }}</td>
                  <td>
                    <div class="iz-name">
                      <div class="iz-avatar">{{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}</div>
                      <div>
                        <a class="student-name" href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a>
                        <div class="iz-sub">{{ $gender }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="fw-semibold text-dark">{{ $student->nisn ?? '-' }}</div>
                    <span class="iz-sub">{{ $student->nis }}</span>
                  </td>
                  <td>
                    {{ $student->birth_place ?? '-' }}, {{ $tglLahir }}
                  </td>
                  <td>
                    {{ $student->dormitory ?? 'Non-Mukim' }}
                    <div class="iz-sub">Kamar: {{ $student->room ?? '-' }}</div>
                  </td>
                  <td>{{ $student->education_level ?? '-' }}</td>
                  <td>
                    <span class="iz-chip iz-chip--{{ $statusCls }}">{{ $statusText }}</span>
                  </td>
                  <td class="text-end pe-4">
                    <div class="d-inline-flex gap-1">
                      <a href="{{ route('students.show', $student->id) }}" class="iz-icon-btn iz-icon-btn--view"
                        title="Detail" aria-label="Detail {{ $student->name }}">
                        <i class="bi bi-eye"></i>
                      </a>
                      @can('kelola-data-santri')
                        <a href="{{ route('students.edit', $student->id) }}" class="iz-icon-btn iz-icon-btn--edit"
                          title="Edit" aria-label="Edit {{ $student->name }}">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        <button type="button" class="iz-icon-btn iz-icon-btn--delete js-delete"
                          data-url="{{ route('students.destroy', $student->id) }}"
                          data-name="{{ $student->name }}" title="Hapus" aria-label="Hapus {{ $student->name }}">
                          <i class="bi bi-trash"></i>
                        </button>
                      @endcan
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Daftar kartu (mobile) --}}
        <div id="mobileCards" class="iz-cards"></div>
      </div>

      {{-- Paginasi DataTables --}}
      <div class="card-footer bg-transparent border-top py-3">
        <div id="customPagination" class="d-flex justify-content-between align-items-center flex-wrap gap-2"></div>
      </div>
    </div>
  </div>

  {{-- Form hapus --}}
  <form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
  </form>

  {{-- Modal Cek NISN --}}
  <div class="modal fade iz-modal" id="checkNisnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-2 px-md-0">
      <div class="modal-content shadow-lg">
        <div class="modal-header">
          <h5 class="modal-title">Cek ketersediaan NISN</h5>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <form id="checkNisnForm">
            <label for="checkNisnInput" class="form-label fw-semibold small">Masukkan NISN</label>
            <input type="text" class="form-control" id="checkNisnInput" required inputmode="numeric"
              placeholder="Nomor Induk Siswa Nasional">
            <div class="form-text mb-3">Sistem akan mengecek apakah NISN sudah terdaftar.</div>
            <button type="submit" class="btn btn-primary iz-btn w-100">Cek & lanjut</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal Import --}}
  <div class="modal fade iz-modal" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-2 px-md-0">
      <div class="modal-content shadow-lg">
        <div class="modal-header">
          <h5 class="modal-title">Import data santri</h5>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="iz-note" role="alert">
            <i class="bi bi-info-circle"></i>
            <div>Pastikan format file sesuai. Kolom <strong>NIS</strong> wajib unik. Gunakan template di bawah ini.</div>
          </div>

          <a href="{{ route('students.template') }}" class="iz-btn iz-btn--soft-ok w-100 mb-4">
            <i class="bi bi-download"></i> Unduh template Excel/CSV
          </a>

          <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="fileImport" class="form-label fw-semibold small">Upload file (.xlsx / .csv)</label>
            <input class="form-control mb-4" type="file" id="fileImport" name="file" required
              accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

            <div class="d-flex flex-column flex-md-row-reverse gap-2">
              <button type="submit" class="btn btn-primary iz-btn">Import data</button>
              <button type="button" class="btn btn-light iz-btn" data-bs-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

  <script>
    function esc(value) {
      return String(value ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
      }[c]));
    }
    function val(value) {
      return value ? esc(value) : '-';
    }

    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 2000,
        showConfirmButton: false
      });
    @elseif (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: @json(session('error')),
        showConfirmButton: true
      });
    @endif

    $(document).ready(function() {
      var table = $('#myTable').DataTable({
        dom: 'Brtip',
        buttons: [{
            extend: 'excel',
            className: 'd-none',
            exportOptions: { columns: ':not(.no-export)' }
          },
          {
            extend: 'csv',
            className: 'd-none',
            exportOptions: { columns: ':not(.no-export)' }
          },
          {
            extend: 'print',
            className: 'd-none',
            exportOptions: { columns: ':not(.no-export)' }
          }
        ],
        paging: true,
        pageLength: 10,
        ordering: true,
        info: true,
        columnDefs: [
          { orderable: false, targets: 7 }
        ],
        language: {
          url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
          paginate: {
            previous: '<i class="bi bi-chevron-left"></i>',
            next: '<i class="bi bi-chevron-right"></i>'
          }
        },
        initComplete: function() {
          $('.dataTables_info').appendTo('#customPagination');
          $('.dataTables_paginate').appendTo('#customPagination').addClass('ms-auto');
        }
      });

      function renderCards() {
        const $wrap =$('#mobileCards');
        if (!$wrap.length) return;

        let html = '';
        table.rows({ page: 'current' }).nodes().to$().each(function() {
          const c = $(this).data('card');
          if (!c) return;

          const actions = `
            <div class="iz-stu-actions">
              <a href="${esc(c.show)}" class="iz-btn iz-btn--soft-primary">
                <i class="bi bi-eye"></i> Detail
              </a>
              ${c.edit ? `
                <a href="${esc(c.edit)}" class="iz-btn iz-btn--edit">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>` : ''}
              ${c.delete ? `
                <button type="button" class="iz-btn iz-btn--delete js-delete"
                  data-url="${esc(c.delete)}" data-name="${esc(c.nama)}">
                  <i class="bi bi-trash"></i> Hapus
                </button>` : ''}
            </div>`;

          html += `
            <div class="iz-stu">
              <div class="iz-stu-top">
                <div class="iz-avatar">${esc((c.nama || '?').charAt(0).toUpperCase())}</div>
                <div class="iz-stu-id">
                  <a class="iz-stu-name" href="${esc(c.show)}">${esc(c.nama)}</a>
                  <div class="iz-sub">${val(c.nis)} &middot; ${val(c.jenjang)}</div>
                </div>
                <span class="iz-chip iz-chip--${esc(c.statusCls)}">${val(c.status)}</span>
                <button type="button" class="iz-stu-toggle" aria-expanded="false"
                  aria-label="Lihat detail ${esc(c.nama)}" title="Lihat detail">
                  <i class="bi bi-chevron-down"></i>
                </button>
              </div>

              <div class="iz-stu-details">
                <dl class="iz-stu-grid">
                  <div><dt>NISN</dt><dd>${val(c.nisn)}</dd></div>
                  <div><dt>NIS</dt><dd>${val(c.nis)}</dd></div>
                  <div><dt>Jenis kelamin</dt><dd>${val(c.gender)}</dd></div>
                  <div><dt>Jenjang</dt><dd>${val(c.jenjang)}</dd></div>
                  <div><dt>Asrama</dt><dd>${val(c.asrama)}</dd></div>
                  <div><dt>Kamar</dt><dd>${val(c.kamar)}</dd></div>
                  <div class="span-2"><dt>Tempat, tanggal lahir</dt><dd>${val(c.tempat)}, ${val(c.tglLahir)}</dd></div>
                </dl>
                ${actions}
              </div>
            </div>`;
        });

        if (!html) {
          html = `<div class="iz-empty"><i class="bi bi-inbox"></i>Data santri tidak ditemukan.</div>`;
        }

        $wrap.html(html);
      }

      $(document).on('click', '.iz-stu-toggle', function() {
        const $button =$(this);
        const $card =$button.closest('.iz-stu');
        const isExpanded = $card.hasClass('is-expanded');
        const nama = $card.find('.iz-stu-name').text().trim();

        $card.toggleClass('is-expanded', !isExpanded);
        $button.attr('aria-expanded', String(!isExpanded));$button.attr('aria-label', (isExpanded ? 'Lihat detail ' : 'Tutup detail ') + nama);
        $button.attr('title', isExpanded ? 'Lihat detail' : 'Tutup detail');
      });

      table.on('draw.dt', renderCards);
      renderCards();

      $('#customSearchBox').on('input', function() {
        table.search(this.value).draw();
      });

      $('#btnExportPrint').on('click', function(e) {
        e.preventDefault();
        table.button('.buttons-print').trigger();
      });

      $('#customLengthChange').on('change', function() {
        table.page.len(this.value).draw();
      });

      $('#checkNisnModal').on('shown.bs.modal', function() {
        $('#checkNisnInput').focus();
      });

      $('#checkNisnForm').on('submit', function(e) {
        e.preventDefault();
        var nisn = $('#checkNisnInput').val().trim();
        var existingNisns = @json($students->pluck('nisn')->filter()->values()).map(String);

        if (existingNisns.includes(nisn)) {
          Swal.fire({
            icon: 'error',
            title: 'Data sudah ada',
            text: 'NISN ' + nisn + ' sudah terdaftar dalam sistem.',
            confirmButtonColor: '#e53935'
          });
        } else {
          window.location.href = @json(route('students.create')) + '?nisn=' + encodeURIComponent(nisn);
        }
      });
    });

    $(document).on('click', '.js-delete', function(e) {
      e.preventDefault();
      var url = $(this).data('url');
      var name = $(this).data('name');

      Swal.fire({
        title: 'Hapus data santri?',
        html: `Data santri <strong>${esc(name)}</strong> akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#697586',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          var form = document.getElementById('delete-form');
          form.action = url;
          form.submit();
        }
      });
    });
  </script>
@endpush