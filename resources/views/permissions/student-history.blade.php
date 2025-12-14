@extends('layouts.app')
@section('title', 'Riwayat Izin Santri')
@push('link')
@endpush
@push('styles')
  <style>
    /* Styling Web View */
    .timeline-month {
      position: relative;
      padding-left: 20px;
      margin-bottom: 30px;
    }

    .timeline-month::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
      background: #e5e7eb;
      border-radius: 4px;
    }

    .card-permission {
      border: 1px solid #f3f4f6;
      transition: all 0.2s;
    }

    .card-permission:hover {
      border-color: #d1d5db;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Styling KHUSUS UNTUK CETAK (PRINT) */
    @media print {

      /* Sembunyikan elemen navigasi, tombol, sidebar, footer website */
      .navbar,
      .btn,
      .no-print,
      footer,
      header {
        display: none !important;
      }

      /* Reset layout container agar full width kertas */
      .container,
      .container-fluid {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      /* Styling text agar hitam pekat */
      body {
        background-color: white !important;
        color: black !important;
        font-family: 'Times New Roman', serif;
      }

      /* Ubah tampilan card menjadi list biasa untuk kertas */
      .card,
      .card-body {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
      }

      .timeline-month::before {
        display: none;
      }

      .timeline-month {
        padding-left: 0;
      }

      /* Tampilkan Header Kop Surat (Hanya muncul saat print) */
      .print-header {
        display: block !important;
        text-align: center;
        border-bottom: 2px solid black;
        margin-bottom: 20px;
        padding-bottom: 10px;
      }

      /* Tabel lebih rapat */
      table {
        width: 100% !important;
        border-collapse: collapse;
      }

      th,
      td {
        border: 1px solid #000 !important;
        padding: 5px !important;
        font-size: 12px;
      }

      .badge {
        border: none !important;
        color: black !important;
        padding: 0 !important;
        font-weight: normal;
      }
    }

    /* Header Print (Default Hidden) */
    .print-header {
      display: none;
    }
  </style>
@endpush
@section('content')

  <div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
      <div>
        <h4 class="fw-bold text-dark mb-1">Riwayat Perizinan</h4>
        <p class="text-muted small mb-0">Laporan aktivitas keluar-masuk santri.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('students.show', $student->id) }}" class="btn btn-light border">
          <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#downloadPdfModal">
          <i class="bi bi-file-earmark-pdf me-2"></i>PDF
        </button>
        <button onclick="window.print()" class="btn btn-primary shadow-sm">
          <i class="bi bi-printer me-2"></i>Cetak Laporan
        </button>
      </div>
    </div>

    <div class="print-header">
      <h3 style="margin:0; text-transform: uppercase;">Laporan Riwayat Perizinan Santri</h3>
      <p style="margin:0;">Ma'had Ta'limul Qur'an Utsman bin Affan</p>
      <p style="margin:0; font-size: 12px;">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="card border-0 shadow-sm mb-4 bg-light rounded-4">
      <div class="card-body p-4">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless table-sm mb-0">
              <tr>
                <td width="120" class="text-muted">Nama Santri</td>
                <td class="fw-bold">: {{ $student->name }}</td>
              </tr>
              <tr>
                <td class="text-muted">NIS</td>
                <td class="fw-bold">: {{ $student->nis }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless table-sm mb-0">
              <tr>
                <td width="120" class="text-muted">Asrama</td>
                <td class="fw-bold">: {{ $student->dormitory ?? '-' }} ({{ $student->room ?? '-' }})</td>
              </tr>
              <tr>
                <td class="text-muted">Total Izin</td>
                <td class="fw-bold">: {{ $student->permissions->count() }} Kali</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    @forelse($permissions as $month => $monthPermissions)
      <div class="timeline-month">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-primary text-white px-3 py-1 rounded-pill small fw-bold me-3">
            {{ $month }}
          </div>
          <div class="text-muted small">
            Total: {{ $monthPermissions->count() }} Izin
          </div>
        </div>

        <div class="card card-permission rounded-3 mb-4 border-0 shadow-sm">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light text-secondary small text-uppercase">
                  <tr>
                    <th class="ps-4 py-3">Tanggal Izin</th>
                    <th>Keperluan</th>
                    <th>Durasi</th>
                    <th>Status Kembali</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($monthPermissions as $perm)
                    <tr>
                      <td class="ps-4">
                        <span class="d-block fw-bold text-dark">{{ $perm->start_date->format('d/m/Y') }}</span>
                        <small class="text-muted">{{ $perm->start_date->format('H:i') }} WIB</small>
                      </td>
                      <td>
                        <span class="badge bg-light text-dark border me-1">{{ ucfirst($perm->type) }}</span>
                        <span class="text-dark">{{ $perm->reason }}</span>
                      </td>
                      <td>
                        <small class="d-block text-muted">Rencana: {{ $perm->end_date->format('d/m H:i') }}</small>
                        @if ($perm->returned_at)
                          <small class="d-block text-success">Real: {{ $perm->returned_at->format('d/m H:i') }}</small>
                        @else
                          <span class="badge bg-warning text-dark">Belum Kembali</span>
                        @endif
                      </td>
                      <td>
                        @if ($perm->status == 'late')
                          <span class="badge bg-danger rounded-pill">Terlambat</span>
                        @elseif($perm->status == 'returned')
                          <span class="badge bg-success rounded-pill">Tepat Waktu</span>
                        @elseif($perm->status == 'approved')
                          <span class="badge bg-warning text-dark rounded-pill">Sedang Izin</span>
                        @else
                          <span class="badge bg-secondary rounded-pill">{{ $perm->status }}</span>
                        @endif
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
      <div class="text-center py-5">
        <p class="text-muted fst-italic">Belum ada riwayat perizinan untuk santri ini.</p>
      </div>
    @endforelse

  </div>

  {{-- Modal Download PDF --}}
  <div class="modal fade" id="downloadPdfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Download Laporan Izin</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('permissions.pdf', $student->id) }}" method="GET">
            <div class="mb-3">
              <label class="form-label small text-muted">Pilih Periode</label>
              <select class="form-select mb-2" name="period" id="periodSelect" onchange="toggleCustomDate(this)">
                <option value="all">Semua Riwayat</option>
                <option value="current_month">Bulan Ini</option>
                <option value="custom">Pilih Tanggal Manual</option>
              </select>
            </div>

            <div id="customDateBox" class="d-none bg-light p-2 rounded mb-3">
              <small class="d-block mb-1">Dari Tanggal:</small>
              <input type="date" name="start_date" class="form-control form-control-sm mb-2">
              <small class="d-block mb-1">Sampai Tanggal:</small>
              <input type="date" name="end_date" class="form-control form-control-sm">
            </div>

            <button type="submit" class="btn btn-danger w-100">
              <i class="bi bi-download me-2"></i>Download PDF
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    function toggleCustomDate(select) {
      const box = document.getElementById('customDateBox');
      if (select.value === 'custom') {
        box.classList.remove('d-none');
      } else {
        box.classList.add('d-none');
        box.querySelectorAll('input').forEach(i => i.value = '');
      }
    }
  </script>
@endpush
