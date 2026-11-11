@extends('layouts.app')
@section('title', 'Data Pegawai')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.datatables.net/v/bs5/dt-2.0.8/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/r-3.0.2/datatables.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
    .iz-btn--soft-ok:hover { background: #cdf3dd; color: var(--iz-success); }

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

    /* ---------- Avatar, badge, tombol aksi ---------- */
    .iz-avatar {
      flex: 0 0 auto;
      width: 2.25rem;
      height: 2.25rem;
      border-radius: 50%;
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
      font-weight: 600;
      font-size: .9rem;
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
    .iz-chip--warn { background: var(--iz-warning-light); color: var(--iz-warning); }
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
    .iz-icon-btn--edit { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-icon-btn--delete { background: var(--iz-danger-light); color: var(--iz-danger); }

    /* ---------- DataTables (desktop) ---------- */
    .iz-page .dt-container { font-size: .88rem; }
    .iz-page .dt-toolbar-top { margin-bottom: 1rem; }
    .iz-page .dt-buttons .btn {
      border-radius: 8px !important;
      font-size: .82rem;
      font-weight: 500;
      margin-right: .35rem;
      padding: .45rem .85rem;
    }
    .iz-page .dt-search { display: flex; align-items: center; }
    .iz-page .dt-search input {
      width: 260px;
      margin-left: 0 !important;
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 8px;
      padding: .5rem .9rem;
    }
    .iz-page .dt-search input:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
      outline: 0;
    }

    .iz-table { color: var(--iz-body); margin: 0; }
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
      padding: .75rem .9rem;
      vertical-align: middle;
      border-color: var(--iz-line);
      white-space: nowrap;
    }
    .iz-table tbody tr:hover > * { background-color: var(--iz-soft); }
    .iz-name { display: flex; align-items: center; gap: .65rem; }
    .iz-name a { font-weight: 600; color: var(--iz-heading); text-decoration: none; }
    .iz-name a:hover { color: var(--iz-secondary-dark); }

    .iz-page .dt-paging .page-link { border-radius: 8px !important; color: var(--iz-body); border: 1px solid var(--iz-line); }
    .iz-page .dt-paging .page-item.active .page-link {
      background: var(--iz-secondary-dark);
      border-color: var(--iz-secondary-dark);
      color: #fff;
    }
    .iz-page .dt-paging .pagination { margin: 0; flex-wrap: wrap; gap: .2rem; }
    .iz-page .dt-info { color: var(--iz-muted); }

    /* Khusus tombol Baris */
    .iz-page .dt-buttons .buttons-page-length {
        color: #ffffff !important;
        white-space: nowrap;
    }

    /* ---------- Daftar kartu (mobile) ---------- */
    .iz-cards { display: none; }
    #mobileCards { margin-top: 1rem; }
    .iz-emp {
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      padding: .9rem;
      margin-bottom: .7rem;
    }
    .iz-emp-top { display: flex; align-items: center; gap: .7rem; }
    .iz-emp-top .iz-avatar { width: 2.6rem; height: 2.6rem; }
    .iz-emp-id { flex: 1 1 auto; min-width: 0; }
    .iz-emp-name {
      display: block;
      font-weight: 600;
      color: var(--iz-heading);
      text-decoration: none;
      line-height: 1.25;
      overflow-wrap: anywhere;
    }
    .iz-emp-role { color: var(--iz-muted); font-size: .82rem; }
    .iz-emp-tags { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .7rem; }

    .iz-emp details { margin-top: .7rem; border-top: 1px dashed var(--iz-line); padding-top: .6rem; }
    .iz-emp summary {
      cursor: pointer;
      font-size: .84rem;
      font-weight: 600;
      color: var(--iz-secondary-dark);
      list-style: none;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .iz-emp summary::-webkit-details-marker { display: none; }
    .iz-emp summary::after { content: '\F282'; font-family: 'bootstrap-icons'; transition: transform .2s ease; }
    .iz-emp details[open] summary::after { transform: rotate(180deg); }
    .iz-emp dl { margin: .6rem 0 0; }
    .iz-emp dl > div { display: flex; justify-content: space-between; gap: 1rem; padding: .3rem 0; font-size: .84rem; }
    .iz-emp dt { flex: 0 0 auto; font-weight: 400; color: var(--iz-muted); }
    .iz-emp dd { margin: 0; text-align: right; color: var(--iz-body); overflow-wrap: anywhere; }

    .iz-emp-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .5rem;
      margin-top: .8rem;
    }
    .iz-emp-actions .iz-btn { padding: .55rem .5rem; font-size: .86rem; }
    .iz-btn--edit { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-btn--delete { background: var(--iz-danger-light); color: var(--iz-danger); }

    .iz-empty { text-align: center; color: var(--iz-muted); padding: 2.5rem 1rem; }
    .iz-empty .bi { font-size: 2.25rem; display: block; margin-bottom: .5rem; color: #b6c0cd; }

    /* ---------- Modal import ---------- */
    .iz-modal .modal-content { border: 0; border-radius: 12px; overflow: hidden; }
    .iz-modal .modal-header { border-bottom: 1px solid var(--iz-line); padding: 1.1rem 1.35rem; }
    .iz-modal .modal-title { font-weight: 600; color: var(--iz-heading); font-size: 1.05rem; }
    .iz-modal .modal-body { padding: 1.35rem; }
    .iz-modal .form-control {
      border: 1px dashed #b6c0cd;
      border-radius: 8px;
      font-size: .9rem;
      padding: .65rem .9rem;
    }
    .iz-modal .form-control:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }
    .iz-modal input[type="file"]::file-selector-button {
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
      border: 0;
      border-radius: 6px;
      padding: .4rem .85rem;
      margin-right: .85rem;
      font-weight: 600;
    }
    .iz-template-box {
      display: flex;
      align-items: center;
      gap: .85rem;
      background: var(--iz-soft);
      border: 1px solid var(--iz-line);
      border-radius: 10px;
      padding: .85rem 1rem;
      margin: 1rem 0 1.25rem;
      font-size: .88rem;
    }
    .iz-template-icon {
      flex: 0 0 auto;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 10px;
      background: var(--iz-primary-light);
      color: var(--iz-primary-dark);
      display: grid;
      place-items: center;
      font-size: 1.2rem;
    }

    /* ---------- Mobile (< 768px) ---------- */
    @media (max-width: 767.98px) {
      .iz-page .page-header-title h2 { font-size: 1.25rem; }
      .iz-header-actions { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; width: 100%; }
      .iz-header-actions .iz-btn { width: 100%; padding: .65rem .5rem; }

      .iz-stat { padding: .8rem; }
      .iz-stat-inner { flex-direction: column; align-items: flex-start; gap: .4rem; }
      .iz-stat-icon { width: 2.1rem; height: 2.1rem; font-size: 1rem; }
      .iz-stat-label { font-size: .7rem; }
      .iz-stat-value { font-size: 1.4rem; }

      /* Sembunyikan hanya area tabel; toolbar dan kartu mobile tetap terlihat */
      .iz-page .dt-scroll { display: none !important; }
      .iz-page #mobileCards.iz-cards { display: block !important; }
      .iz-page .iz-header-actions { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; width: 100%; }
      .iz-page .iz-header-actions .iz-btn { width: 100%; padding: .65rem .5rem; white-space: normal; }
      .iz-page .page-header-title h2 { margin-bottom: 0; }

      /* Toolbar: pencarian di atas, tombol 2 kolom di bawahnya */
      .iz-page .dt-toolbar-top {
        flex-direction: column-reverse !important;
        align-items: stretch !important;
        gap: .75rem !important;
      }
      .iz-page .dt-toolbar-top .dt-buttons { display: flex; flex-wrap: wrap; gap: .4rem; width: 100%; }
      .iz-page .dt-toolbar-top .dt-buttons .btn { flex: 1 1 calc(50% - .4rem); margin: 0; text-align: center; }
      .iz-page .dt-search { width: 100%; }
      .iz-page .dt-search input { width: 100%; }

      /* Toolbar bawah: informasi data tetap di atas, pagination di bawah */
      .iz-page .dt-toolbar-bottom {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        text-align: center;
        gap: .75rem !important;
        width: 100%;
      }

      /* Informasi jumlah data */
      .iz-page .dt-toolbar-bottom .dt-info {
        order: 1;
        width: 100%;
        margin: 0 !important;
        text-align: center;
      }

      /* Nomor halaman diletakkan paling bawah */
      .iz-page .dt-toolbar-bottom .dt-paging {
        order: 2;
        display: flex !important;
        justify-content: center !important;
        width: 100%;
        margin-top: .25rem !important;
      }

      /* Pagination tetap satu baris */
      .iz-page .dt-toolbar-bottom .dt-paging .pagination {
        display: flex !important;
        flex-wrap: nowrap !important;
        justify-content: center;
        gap: .2rem;
        margin: 0;
      }

      .iz-page .dt-toolbar-bottom .dt-paging .page-link,
      .iz-page #mobileCards + .dt-paging .page-link {
        padding: .35rem .6rem;
        white-space: nowrap;
      }

      /* Pagination yang dipindahkan berada setelah seluruh kartu mobile */
      .iz-page #mobileCards + .dt-paging {
        display: flex !important;
        justify-content: center !important;
        width: 100%;
        margin-top: 1rem !important;
      }

      .iz-page #mobileCards + .dt-paging .pagination {
        display: flex !important;
        flex-wrap: nowrap !important;
        justify-content: center;
        gap: .2rem;
        margin: 0;
      }

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
    $semuaPegawai = collect($pegawais);
    $totalPegawai = $semuaPegawai->count();
    $totalAktif = $semuaPegawai->where('status_pegawai', 'Aktif')->count();
    $totalLainnya = $totalPegawai - $totalAktif;
  @endphp

  {{-- Tanpa wrapper .container: template Berry sudah membungkus konten dengan .pc-container / .pc-content --}}
  <div class="iz-page">

    {{-- Header halaman (gaya Berry) --}}
    <div class="page-header">
      <div class="page-block">
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
          <li class="breadcrumb-item active" aria-current="page">Data Pegawai</li>
        </ul>

        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mt-3">
          <div class="page-header-title">
            <h2>Data Master Pegawai</h2>
            <p class="text-muted small mb-0 mt-1">Kelola data dan informasi pegawai.</p>
          </div>
          <div class="iz-header-actions d-flex gap-2">
            <button type="button" class="iz-btn iz-btn--soft-ok" data-bs-toggle="modal" data-bs-target="#importModal">
              <i class="bi bi-file-earmark-spreadsheet"></i> Import Excel
            </button>
            <a href="{{ route('pegawai.create') }}" class="btn btn-primary iz-btn">
              <i class="bi bi-plus-lg"></i> Tambah
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-2 g-md-3 mb-4">
      <div class="col-4">
        <div class="card iz-stat iz-stat--total">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-people"></i></div>
            <div>
              <div class="iz-stat-label">Total pegawai</div>
              <div class="iz-stat-value">{{ $totalPegawai }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card iz-stat iz-stat--active">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-person-check"></i></div>
            <div>
              <div class="iz-stat-label">Pegawai aktif</div>
              <div class="iz-stat-value">{{ $totalAktif }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card iz-stat iz-stat--other">
          <div class="iz-stat-inner">
            <div class="iz-stat-icon"><i class="bi bi-person-dash"></i></div>
            <div>
              <div class="iz-stat-label">Status lainnya</div>
              <div class="iz-stat-value">{{ $totalLainnya }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
      <div class="card-body p-3 p-md-4">
        <table id="completeTable" class="table iz-table align-middle w-100 nowrap">
          <thead>
            <tr>
              <th width="3%" class="text-center">No.</th>
              <th>Nama Lengkap</th>
              <th width="5%" class="text-center">L/P</th>
              <th>Tempat Lahir</th>
              <th>Tgl. Lahir</th>
              <th>Jabatan</th>
              <th>Kategori</th>
              <th>Status Kawin</th>
              <th>NIK</th>
              <th>No. KK</th>
              <th>Desa</th>
              <th>Kecamatan</th>
              <th>Kabupaten</th>
              <th>Provinsi</th>
              <th>TMT</th>
              <th>Status</th>
              <th width="8%" class="text-center no-export">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pegawais as $pegawai)
              @php
                $kategoriCls = match ($pegawai->kategori_pegawai) {
                    'PNS', 'TETAP' => 'ok',
                    'HONORER', 'KONTRAK', 'TRAINING' => 'warn',
                    default => 'muted',
                };
                $statusCls = match ($pegawai->status_pegawai) {
                    'Aktif' => 'ok',
                    'Cuti' => 'warn',
                    'Keluar' => 'late',
                    default => 'muted',
                };
                $tglLahir = $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('d/m/Y') : '-';
                $tmt = $pegawai->terhitung_mulai_tanggal ? $pegawai->terhitung_mulai_tanggal->format('d/m/Y') : '-';
                $alamat = collect([$pegawai->desa, $pegawai->kecamatan, $pegawai->kabupaten, $pegawai->provinsi])
                    ->filter()
                    ->implode(', ');

                // Data ringkas untuk tampilan kartu di mobile
                $card = [
                    'nama' => $pegawai->nama,
                    'jk' => $pegawai->jenis_kelamin,
                    'jabatan' => $pegawai->jabatan,
                    'kategori' => $pegawai->kategori_pegawai,
                    'kategoriCls' => $kategoriCls,
                    'status' => $pegawai->status_pegawai,
                    'statusCls' => $statusCls,
                    'tempat' => $pegawai->tempat_lahir,
                    'tglLahir' => $tglLahir,
                    'kawin' => $pegawai->status_perkawinan,
                    'nik' => $pegawai->nik,
                    'kk' => $pegawai->no_kk,
                    'alamat' => $alamat,
                    'tmt' => $tmt,
                    'show' => route('pegawai.show', $pegawai->id),
                    'edit' => route('pegawai.edit', $pegawai->id),
                    'delete' => route('pegawai.destroy', $pegawai->id),
                ];
              @endphp
              <tr data-card="{{ json_encode($card, JSON_UNESCAPED_UNICODE) }}">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                  <div class="iz-name">
                    <div class="iz-avatar">{{ mb_strtoupper(mb_substr($pegawai->nama, 0, 1)) }}</div>
                    <a href="{{ route('pegawai.show', $pegawai->id) }}">{{ $pegawai->nama }}</a>
                  </div>
                </td>
                <td class="text-center">{{ $pegawai->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                <td>{{ $pegawai->tempat_lahir }}</td>
                <td>{{ $tglLahir }}</td>
                <td>{{ $pegawai->jabatan }}</td>
                <td><span class="iz-chip iz-chip--{{ $kategoriCls }}">{{ $pegawai->kategori_pegawai }}</span></td>
                <td>{{ $pegawai->status_perkawinan }}</td>
                <td>{{ $pegawai->nik }}</td>
                <td>{{ $pegawai->no_kk }}</td>
                <td>{{ $pegawai->desa }}</td>
                <td>{{ $pegawai->kecamatan }}</td>
                <td>{{ $pegawai->kabupaten }}</td>
                <td>{{ $pegawai->provinsi }}</td>
                <td>{{ $tmt }}</td>
                <td><span class="iz-chip iz-chip--{{ $statusCls }}">{{ $pegawai->status_pegawai }}</span></td>
                <td class="text-center">
                  <div class="d-inline-flex gap-1">
                    <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="iz-icon-btn iz-icon-btn--edit"
                      title="Edit" aria-label="Edit {{ $pegawai->nama }}">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <button type="button" class="iz-icon-btn iz-icon-btn--delete js-delete"
                      data-url="{{ route('pegawai.destroy', $pegawai->id) }}" title="Hapus"
                      aria-label="Hapus {{ $pegawai->nama }}">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        {{-- Kartu khusus mobile, berada di luar wrapper scroll DataTables --}}
        <div id="mobileCards" class="iz-cards" aria-live="polite"></div>
      </div>
    </div>
  </div>

  {{-- Form hapus (satu form dipakai bersama oleh tabel dan kartu) --}}
  <form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
  </form>

  {{-- Modal Import Excel --}}
  <div class="modal fade iz-modal" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-2 px-md-0">
      <div class="modal-content shadow-lg">
        <div class="modal-header">
          <div>
            <h5 class="modal-title" id="importModalLabel">Import data pegawai</h5>
            <p class="text-muted small mb-0">Perbarui data pegawai dari file Excel.</p>
          </div>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="file" class="form-label fw-semibold small">Pilih file Excel</label>
            <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">

            <div class="iz-template-box">
              <div class="iz-template-icon"><i class="bi bi-file-earmark-arrow-down"></i></div>
              <div>
                <span class="d-block fw-semibold">Belum punya formatnya?</span>
                <a href="{{ route('pegawai.template') }}" class="text-decoration-none fw-semibold">
                  Unduh template Excel <i class="bi bi-chevron-right small"></i>
                </a>
              </div>
            </div>

            <div class="d-flex flex-column flex-md-row-reverse gap-2">
              <button type="submit" class="btn btn-primary iz-btn">
                <i class="bi bi-cloud-upload"></i> Mulai import
              </button>
              <button type="button" class="btn btn-light iz-btn" data-bs-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script type="text/javascript"
    src="https://cdn.datatables.net/v/bs5/dt-2.0.8/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/r-3.0.2/datatables.min.js">
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script>
    // Escape teks agar aman disisipkan ke HTML kartu
    function esc(value) {
      return String(value ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
      }[c]));
    }
    function val(value) {
      return value ? esc(value) : '-';
    }

    // Konfirmasi hapus (dipakai tombol di tabel maupun di kartu mobile)
    $(document).on('click', '.js-delete', function(e) {
      e.preventDefault();
      const url = $(this).data('url');

      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#697586',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-4' }
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.getElementById('delete-form');
          form.action = url;
          form.submit();
        }
      });
    });

    $(document).ready(function() {
      var table = $('#completeTable').DataTable({
        scrollX: true,
        pagingType: 'simple_numbers',
        // Layout wrapper dengan kelas pembantu agar bisa ditata via CSS
        dom: '<"dt-toolbar-top d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"Bf>rt<"dt-toolbar-bottom d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, "Semua"]
        ],
        buttons: [
          {
            extend: 'pageLength',
            className: 'btn btn-outline-secondary btn-sm',
            text: '<i class="bi bi-list-ul me-1"></i> Baris'
          },
          {
            extend: 'excelHtml5',
            text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
            className: 'btn btn-outline-success btn-sm',
            title: 'Data Pegawai',
            exportOptions: { columns: ':visible:not(.no-export)' }
          },
          {
            extend: 'pdfHtml5',
            text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
            className: 'btn btn-outline-danger btn-sm',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Data Pegawai',
            exportOptions: { columns: ':visible:not(.no-export)' }
          },
          {
            extend: 'colvis',
            text: '<i class="bi bi-eye me-1"></i> Kolom',
            className: 'btn btn-outline-primary btn-sm',
            columns: ':not(.no-export)'
          }
        ],
        language: {
          search: "",
          searchPlaceholder: "Cari pegawai...",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
          emptyTable: "Belum ada data pegawai",
          zeroRecords: "Data tidak ditemukan",
          paginate: {
            first: '<i class="bi bi-chevron-double-left"></i>',
            last: '<i class="bi bi-chevron-double-right"></i>',
            next: '<i class="bi bi-chevron-right"></i>',
            previous: '<i class="bi bi-chevron-left"></i>'
          },
          buttons: {
            colvis: 'Atur Kolom'
          }
        },
        columnDefs: [
          { orderable: false, targets: 16 },
          // Kolom detail disembunyikan secara default (tetap bisa dibuka lewat tombol Kolom)
          { targets: [7, 8, 9, 10, 11, 12, 13, 14], visible: false }
        ],
        initComplete: function() {
          $('.dt-search input').addClass('form-control form-control-sm');

          // Wadah kartu sudah dibuat langsung di Blade, di luar wrapper scroll DataTables.
          // Jangan panggil renderCards() di sini: variabel table belum selesai diinisialisasi.
        }
      });

      // Jalankan setelah DataTables selesai diinisialisasi dan variabel table tersedia.
      renderCards();

      /*
       * Pada mobile, pindahkan hanya navigasi nomor halaman ke bawah kartu.
       * Placeholder menjaga posisi asli agar pagination dapat dikembalikan
       * ke toolbar bawah DataTables saat layar kembali ke desktop.
       */
      const $paging = $('.dt-toolbar-bottom .dt-paging');
      const $pagingPlaceholder = $('<div class="dt-paging-placeholder"></div>');
      $paging.before($pagingPlaceholder);

      function arrangeMobilePaging() {
        const isMobile = window.matchMedia('(max-width: 767.98px)').matches;

        if (isMobile) {
          $('#mobileCards').after($paging);
        } else {
          $pagingPlaceholder.before($paging);
        }
      }

      arrangeMobilePaging();
      $(window).on('resize', arrangeMobilePaging);

      // Bangun daftar kartu dari baris halaman aktif (ikut pencarian, urutan, dan paginasi DataTables)
      function renderCards() {
        const $wrap = $('#mobileCards');
        if (!$wrap.length) return;

        let html = '';
        table.rows({ page: 'current' }).nodes().to$().each(function() {
          const c = $(this).data('card');
          if (!c) return;

          
        html += `
          <div class="iz-emp">
            <div class="iz-emp-top">
              <div class="iz-avatar">${esc((c.nama || '?').charAt(0).toUpperCase())}</div>

              <div class="iz-emp-id">
                <a class="iz-emp-name" href="${esc(c.show)}">${esc(c.nama)}</a>
                <div class="iz-emp-role">${val(c.jabatan)}</div>
              </div>

              <span class="iz-chip iz-chip--${esc(c.statusCls)}">${val(c.status)}</span>
            </div>

            <div class="iz-emp-tags">
              <span class="iz-chip iz-chip--${esc(c.kategoriCls)}">${val(c.kategori)}</span>
              <span class="iz-chip iz-chip--muted">${val(c.jk)}</span>
            </div>

            <details>
              <summary>Lihat detail</summary>

              <dl>
                <div><dt>Tempat, tgl. lahir</dt><dd>${val(c.tempat)}, ${val(c.tglLahir)}</dd></div>
                <div><dt>Status kawin</dt><dd>${val(c.kawin)}</dd></div>
                <div><dt>NIK</dt><dd>${val(c.nik)}</dd></div>
                <div><dt>No. KK</dt><dd>${val(c.kk)}</dd></div>
                <div><dt>Alamat</dt><dd>${val(c.alamat)}</dd></div>
                <div><dt>TMT</dt><dd>${val(c.tmt)}</dd></div>
              </dl>

              <div class="iz-emp-actions">
                <a href="${esc(c.edit)}" class="iz-btn iz-btn--edit">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>

                <button type="button"
                  class="iz-btn iz-btn--delete js-delete"
                  data-url="${esc(c.delete)}">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </div>
            </details>
          </div>`;

        });

        if (!html) {
          html = `<div class="iz-empty"><i class="bi bi-inbox"></i>Data pegawai tidak ditemukan.</div>`;
        }
        $wrap.html(html);
      }

      table.on('draw.dt search.dt order.dt', renderCards);

      // Rapikan lebar kolom saat ukuran layar berubah (mis. dari mobile ke desktop)
      let resizeTimer;
      $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => table.columns.adjust(), 200);
      });

      @if (session('success'))
        Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        }).fire({
          icon: 'success',
          title: @json(session('success'))
        });
      @endif

      @if (session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: @json(session('error')),
          customClass: { popup: 'rounded-4' }
        });
      @endif
    });
  </script>
@endpush