@extends('layouts.app')
@section('title', 'Direktori Alumni & Mutasi')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
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

      --iz-secondary: #673ab7;
      --iz-secondary-dark: #5e35b1;
      --iz-secondary-light: #ede7f6;
      --iz-primary-light: #e3f2fd;
      --iz-primary-dark: #1e88e5;
      --iz-success: #00a152;
      --iz-success-light: #e3f9ec;
      --iz-warning: #b26a00;
      --iz-warning-light: #fff4d6;
      --iz-danger: #e53935;
      --iz-danger-light: #ffebee;
    }

    /* ---------- Kartu & header halaman ---------- */
    .iz-page .card {
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      box-shadow: 0 1px 2px rgba(18, 25, 38, .04);
      margin-bottom: 1rem;
    }
    .iz-page .page-header { margin-bottom: 1.25rem; }
    .iz-page .page-header .breadcrumb { margin-bottom: .35rem; font-size: .85rem; }
    .iz-page .page-header-title h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--iz-heading);
      margin: 0;
    }
    .iz-page .page-subtitle { color: var(--iz-muted); font-size: .85rem; margin: .25rem 0 0; }

    .iz-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .45rem;
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
    .iz-btn--soft-ok:hover:not(:disabled) { background: #cdf3dd; color: var(--iz-success); }
    .iz-btn--soft-ok:disabled { opacity: .55; cursor: not-allowed; }
    .iz-btn--light { background: #eef2f6; color: var(--iz-body); }
    .iz-btn--light:hover { background: #e3e8ef; color: var(--iz-heading); }
    .iz-btn--soft-primary { background: var(--iz-primary-light); color: var(--iz-primary-dark); }
    .iz-btn--soft-primary:hover { background: #cfe8fc; color: var(--iz-primary-dark); }
    .iz-btn--soft-danger { background: var(--iz-danger-light); color: var(--iz-danger); }
    .iz-btn--soft-danger:hover { background: #fcd9d9; color: var(--iz-danger); }
    .iz-btn--soft-warn { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-btn--soft-warn:hover { background: #ffecb8; color: var(--iz-warning); }

    /* ---------- Filter ---------- */
    .iz-search { position: relative; }
    .iz-search > .bi {
      position: absolute;
      left: .95rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--iz-muted);
      pointer-events: none;
    }
    .iz-filter .form-control,
    .iz-filter .form-select {
      background-color: var(--iz-surface);
      border: 1px solid #cdd5df;
      border-radius: 8px;
      padding: .65rem 1rem;
      font-size: .9rem;
      color: var(--iz-body);
    }
    .iz-filter .iz-search .form-control { padding-left: 2.5rem; }
    .iz-filter .form-control:focus,
    .iz-filter .form-select:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }

    /* ---------- Kepala daftar ---------- */
    .iz-list-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
      padding: 1rem 1.25rem;
      border-bottom: 1px solid var(--iz-line);
    }
    .iz-list-title {
      display: flex;
      align-items: center;
      gap: .6rem;
      font-size: 1rem;
      font-weight: 600;
      color: var(--iz-heading);
      margin: 0;
    }
    .iz-count {
      padding: .15rem .6rem;
      border-radius: 6px;
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
      font-size: .75rem;
      font-weight: 600;
    }

    /* ---------- Avatar & badge ---------- */
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
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .02em;
      padding: .3rem .65rem;
      border-radius: 6px;
      white-space: nowrap;
    }
    .iz-chip--ok { background: var(--iz-success-light); color: var(--iz-success); }
    .iz-chip--warn { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-chip--late { background: var(--iz-danger-light); color: var(--iz-danger); }
    .iz-chip--dark { background: #e3e8ef; color: #364152; }
    .iz-chip--muted { background: #eef2f6; color: var(--iz-muted); }

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
    .iz-table tbody tr:last-child td { border-bottom: 0; }
    .iz-table tbody tr:hover > * { background-color: var(--iz-soft); }
    .iz-name { display: flex; align-items: center; gap: .75rem; }
    .iz-name a { font-weight: 600; color: var(--iz-heading); text-decoration: none; }
    .iz-name a:hover { color: var(--iz-secondary-dark); }
    .iz-sub { color: var(--iz-muted); font-size: .8rem; }
    .iz-key { color: var(--iz-muted); font-size: .76rem; display: block; }
    .iz-val { font-weight: 600; color: var(--iz-heading); overflow-wrap: anywhere; }

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
    .iz-icon-btn:hover { filter: brightness(.94); }
    .iz-icon-btn--view { background: var(--iz-primary-light); color: var(--iz-primary-dark); }
    .iz-icon-btn--pdf { background: var(--iz-danger-light); color: var(--iz-danger); }
    .iz-icon-btn--letter { background: var(--iz-warning-light); color: var(--iz-warning); }

    /* DataTables dipakai hanya untuk ekspor; sembunyikan elemen bawaannya */
    .iz-page .dataTables_wrapper .dt-buttons { display: none; }
    .iz-page .dataTables_wrapper { padding: 0; }

    /* ---------- Daftar kartu (mobile) ---------- */
    .iz-cards { display: none; padding: .75rem; }
    .iz-alu {
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      padding: .9rem;
      margin-bottom: .7rem;
    }
    .iz-alu:last-child { margin-bottom: 0; }
    .iz-alu-top { display: flex; align-items: center; gap: .7rem; }
    .iz-alu-id { flex: 1 1 auto; min-width: 0; }
    .iz-alu-name {
      display: block;
      font-weight: 600;
      color: var(--iz-heading);
      text-decoration: none;
      line-height: 1.25;
      overflow-wrap: anywhere;
    }
    .iz-alu-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .65rem 1rem;
      margin: .8rem 0 0;
      padding-top: .75rem;
      border-top: 1px dashed var(--iz-line);
    }
    .iz-alu-grid .span-2 { grid-column: 1 / -1; }
    .iz-alu-actions { display: flex; gap: .5rem; margin-top: .85rem; }
    .iz-alu-actions .iz-btn { flex: 1 1 0; padding: .55rem .4rem; font-size: .86rem; }

    /* ---------- Kosong & paginasi ---------- */
    .iz-empty { text-align: center; color: var(--iz-muted); padding: 3rem 1rem; }
    .iz-empty .bi { font-size: 2.5rem; display: block; margin-bottom: .5rem; color: #b6c0cd; }
    .iz-empty strong { display: block; color: var(--iz-heading); margin-bottom: .2rem; }

    .iz-foot { padding: 1rem 1.25rem; border-top: 1px solid var(--iz-line); }
    .iz-page .pagination { margin: 0; flex-wrap: wrap; gap: .2rem; }
    .iz-page .page-link { border-radius: 8px !important; border: 1px solid var(--iz-line); color: var(--iz-body); }
    .iz-page .page-item.active .page-link {
      background: var(--iz-secondary-dark);
      border-color: var(--iz-secondary-dark);
      color: #fff;
    }
    .iz-page .iz-foot nav p { margin: 0; }

    /* ---------- Mobile (< 768px) ---------- */
    @media (max-width: 767.98px) {
      .iz-page .page-header-title h2 { font-size: 1.25rem; }
      .iz-header-actions .iz-btn { width: 100%; padding: .7rem 1rem; }

      .iz-list-head { padding: .9rem 1rem; }
      .iz-table-wrap { display: none; }
      .iz-cards { display: block; }
      .iz-foot { padding: .9rem 1rem; }

      /* 16px mencegah iOS memperbesar layar saat input difokuskan */
      .iz-filter .form-control,
      .iz-filter .form-select { font-size: 16px; padding: .75rem 1rem; }
      .iz-filter .iz-search .form-control { padding-left: 2.6rem; }
    }

    @media (prefers-reduced-motion: reduce) {
      .iz-page * { transition: none !important; }
    }
  </style>
@endpush

@section('content')
  @php
    $statusClass = ['graduated' => 'ok', 'moved' => 'warn', 'suspended' => 'late', 'deceased' => 'dark'];
    $statusLabel = [
        'graduated' => 'LULUS',
        'moved' => 'PINDAH',
        'suspended' => 'DO/KELUAR',
        'deceased' => 'MENINGGAL',
    ];

    $isPaginator = $alumni instanceof \Illuminate\Contracts\Pagination\Paginator;
    $totalData = method_exists($alumni, 'total') ? $alumni->total() : $alumni->count();
    $hasFilter = request()->filled('search') || request()->filled('year') || request()->filled('category');
  @endphp

  {{-- Tanpa wrapper .container: template Berry sudah membungkus konten dengan .pc-container / .pc-content --}}
  <div class="iz-page">

    {{-- Header halaman (gaya Berry) --}}
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-end g-3">
          <div class="col-12 col-md">
            <div class="row">
              <div class="col">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                  <li class="breadcrumb-item">Santri</li>
                  <li class="breadcrumb-item active" aria-current="page">Alumni &amp; Mutasi</li>
                </ul>
              </div>
            </div> <br>
            <div class="page-header-title">
              <h2>Direktori Alumni &amp; Mutasi</h2>
            </div>
            <p class="page-subtitle">Arsip data santri yang telah lulus atau pindah sekolah.</p>
          </div>
          <div class="col-12 col-md-auto iz-header-actions">
            <button type="button" class="iz-btn iz-btn--soft-ok" id="btnExportExcel"
              {{ $alumni->count() === 0 ? 'disabled' : '' }}>
              <i class="bi bi-file-earmark-excel"></i> Export Excel
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- Filter (server-side, lewat query string) --}}
    <div class="card iz-filter">
      <div class="card-body p-3">
        <form action="{{ route('alumni.index') }}" method="GET" class="row g-2 g-md-3">

          <div class="col-12 col-md-4">
            <div class="iz-search">
              <i class="bi bi-search"></i>
              <input type="search" name="search" class="form-control" placeholder="Cari nama atau NIS"
                value="{{ request('search') }}" autocomplete="off">
            </div>
          </div>

          <div class="col-6 col-md-3">
            <select name="year" class="form-select" aria-label="Filter tahun">
              <option value="">Semua tahun</option>
              @foreach ($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                  Tahun {{ $year }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-6 col-md-3">
            <select name="category" class="form-select" aria-label="Filter status">
              <option value="">Semua status</option>
              <option value="graduated" {{ request('category') == 'graduated' ? 'selected' : '' }}>Lulus (Alumni)</option>
              <option value="moved" {{ request('category') == 'moved' ? 'selected' : '' }}>Pindah (Mutasi)</option>
              <option value="suspended" {{ request('category') == 'suspended' ? 'selected' : '' }}>Dikeluarkan</option>
            </select>
          </div>

          <div class="col-12 col-md-2">
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary iz-btn flex-fill">
                <i class="bi bi-funnel"></i> Filter
              </button>
              @if ($hasFilter)
                <a href="{{ route('alumni.index') }}" class="iz-btn iz-btn--light" title="Atur ulang filter"
                  aria-label="Atur ulang filter">
                  <i class="bi bi-x-lg"></i>
                </a>
              @endif
            </div>
          </div>

        </form>
      </div>
    </div>

    {{-- Daftar alumni & mutasi --}}
    <div class="card overflow-hidden">
      <div class="iz-list-head">
        <h5 class="iz-list-title">
          Daftar santri keluar
          <span class="iz-count">{{ $totalData }}</span>
        </h5>
      </div>

      @if ($alumni->count() === 0)
        <div class="iz-empty">
          <i class="bi bi-archive"></i>
          <strong>Data alumni tidak ditemukan.</strong>
          @if ($hasFilter)
            Coba ubah atau atur ulang filter pencarian.
          @endif
        </div>
      @else
        {{-- Tabel (desktop) --}}
        <div class="table-responsive iz-table-wrap">
          <table id="alumniTable" class="table iz-table align-middle" style="width:100%">
            <thead>
              <tr>
                <th class="ps-4">Nama Santri</th>
                <th>Tahun Keluar</th>
                <th>Status</th>
                <th>Keterangan / Tujuan</th>
                <th class="text-end pe-4 no-export">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($alumni as $student)
                @php
                  $exit = $student->exitDetail;
                  $tahun = $exit->exit_year ?? '-';
                  $tanggal = $exit?->exit_date ? $exit->exit_date->locale('id')->translatedFormat('d M Y') : '-';

                  $cls = $statusClass[$student->status] ?? 'muted';
                  $label = $statusLabel[$student->status] ?? strtoupper($student->status);

                  // Keterangan sesuai status
                  $infoKey = null;
                  $infoVal = null;
                  if ($student->status == 'graduated') {
                      $infoKey = 'No. SK/Ijazah';
                      $infoVal = $exit->sk_number ?? '-';
                  } elseif ($student->status == 'moved') {
                      $infoKey = 'Pindah ke';
                      $infoVal = $exit->destination ?? '-';
                  }

                  // Tombol cetak sesuai status
                  $printUrl = null;
                  $printLabel = null;
                  $printClass = null;
                  $printIcon = null;
                  if ($student->status == 'graduated') {
                      $printUrl = route('students.exit.print-skl', $student->id);
                      $printLabel = 'Cetak SKL';
                      $printClass = 'pdf';
                      $printIcon = 'bi-file-earmark-pdf';
                  } elseif ($student->status == 'moved') {
                      $printUrl = route('students.exit.print', $student->id);
                      $printLabel = 'Cetak Surat Pindah';
                      $printClass = 'letter';
                      $printIcon = 'bi-envelope-paper';
                  }
                @endphp
                <tr>
                  <td class="ps-4" data-export="{{ $student->name }} (NIS: {{ $student->nis }})">
                    <div class="iz-name">
                      <div class="iz-avatar">{{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}</div>
                      <div>
                        <a href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a>
                        <div class="iz-sub">NIS: {{ $student->nis }}</div>
                      </div>
                    </div>
                  </td>
                  <td data-export="{{ $tahun }} ({{ $tanggal }})">
                    <div class="fw-semibold text-dark">{{ $tahun }}</div>
                    <div class="iz-sub">{{ $tanggal }}</div>
                  </td>
                  <td data-export="{{ $label }}">
                    <span class="iz-chip iz-chip--{{ $cls }}">{{ $label }}</span>
                  </td>
                  <td data-export="{{ $infoKey ? $infoKey . ': ' . $infoVal : '-' }}">
                    @if ($infoKey)
                      <span class="iz-key">{{ $infoKey }}</span>
                      <span class="iz-val">{{ $infoVal }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-end pe-4">
                    <div class="d-inline-flex gap-1">
                      <a href="{{ route('students.show', $student->id) }}" class="iz-icon-btn iz-icon-btn--view"
                        title="Lihat profil" aria-label="Lihat profil {{ $student->name }}">
                        <i class="bi bi-person"></i>
                      </a>
                      @if ($printUrl)
                        <a href="{{ $printUrl }}" target="_blank" class="iz-icon-btn iz-icon-btn--{{ $printClass }}"
                          title="{{ $printLabel }}" aria-label="{{ $printLabel }} {{ $student->name }}">
                          <i class="bi {{ $printIcon }}"></i>
                        </a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Daftar kartu (mobile) --}}
        <div class="iz-cards">
          @foreach ($alumni as $student)
            @php
              $exit = $student->exitDetail;
              $tahun = $exit->exit_year ?? '-';
              $tanggal = $exit?->exit_date ? $exit->exit_date->locale('id')->translatedFormat('d M Y') : '-';
              $cls = $statusClass[$student->status] ?? 'muted';
              $label = $statusLabel[$student->status] ?? strtoupper($student->status);

              $infoKey = null;
              $infoVal = null;
              if ($student->status == 'graduated') {
                  $infoKey = 'No. SK/Ijazah';
                  $infoVal = $exit->sk_number ?? '-';
              } elseif ($student->status == 'moved') {
                  $infoKey = 'Pindah ke';
                  $infoVal = $exit->destination ?? '-';
              }
            @endphp

            <div class="iz-alu">
              <div class="iz-alu-top">
                <div class="iz-avatar">{{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}</div>
                <div class="iz-alu-id">
                  <a class="iz-alu-name" href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a>
                  <div class="iz-sub">NIS: {{ $student->nis }}</div>
                </div>
                <span class="iz-chip iz-chip--{{ $cls }}">{{ $label }}</span>
              </div>

              <div class="iz-alu-grid">
                <div>
                  <span class="iz-key">Tahun keluar</span>
                  <span class="iz-val">{{ $tahun }}</span>
                </div>
                <div>
                  <span class="iz-key">Tanggal</span>
                  <span class="iz-val">{{ $tanggal }}</span>
                </div>
                @if ($infoKey)
                  <div class="span-2">
                    <span class="iz-key">{{ $infoKey }}</span>
                    <span class="iz-val">{{ $infoVal }}</span>
                  </div>
                @endif
              </div>

              <div class="iz-alu-actions">
                <a href="{{ route('students.show', $student->id) }}" class="iz-btn iz-btn--soft-primary">
                  <i class="bi bi-person"></i> Profil
                </a>
                @if ($student->status == 'graduated')
                  <a href="{{ route('students.exit.print-skl', $student->id) }}" target="_blank"
                    class="iz-btn iz-btn--soft-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Cetak SKL
                  </a>
                @elseif ($student->status == 'moved')
                  <a href="{{ route('students.exit.print', $student->id) }}" target="_blank"
                    class="iz-btn iz-btn--soft-warn">
                    <i class="bi bi-envelope-paper"></i> Surat Pindah
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        {{-- Paginasi server (hanya jika data berupa paginator) --}}
        @if ($isPaginator && method_exists($alumni, 'links'))
          <div class="iz-foot">
            {{ $alumni->appends(request()->query())->links('pagination::bootstrap-5') }}
          </div>
        @endif
      @endif
    </div>

  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

  {{-- DataTables Buttons & JSZip (wajib untuk Excel) --}}
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

  <script>
    $(document).ready(function () {
      const tableEl = $('#alumniTable');
      if (!tableEl.length) return;

      // Paginasi, pencarian, dan urutan ditangani server, jadi DataTables hanya dipakai untuk ekspor Excel.
      const table = tableEl.DataTable({
        dom: 'Bt',
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        buttons: [{
          extend: 'excelHtml5',
          className: 'd-none',
          title: 'Direktori Alumni & Mutasi',
          filename: 'alumni-mutasi',
          exportOptions: {
            columns: ':not(.no-export)',
            format: {
              // Pakai teks ringkas dari atribut data-export agar kolom Excel rapi
              body: function (data, row, column, node) {
                if (node && node.getAttribute && node.getAttribute('data-export')) {
                  return node.getAttribute('data-export');
                }
                return $('<div>').html(data).text().replace(/\s+/g, ' ').trim();
              }
            }
          }
        }]
      });

      // Tombol Export Excel di header
      $('#btnExportExcel').on('click', function () {
        table.button('.buttons-excel').trigger();
      });
    });
  </script>
@endpush