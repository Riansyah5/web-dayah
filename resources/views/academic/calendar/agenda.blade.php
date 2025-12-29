@extends('layouts.app')
@section('title', 'Agenda Kegiatan Akademik')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">

    <div class="d-print-none mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h4 class="fw-bold mb-1">Agenda Kegiatan Akademik</h4>
          <p class="text-muted small">Tahun Ajaran: {{ $activeYear->name }} ({{ $activeYear->semester }})</p>
        </div>
        <div>
          <a href="{{ route('calendar.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-calendar-date me-1"></i> Tampilan Kalender
          </a>
          <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i> Cetak Agenda
          </button>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
          <form action="{{ route('calendar.agenda') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
              <label class="form-label small text-muted">Dari Bulan</label>
              <select name="start_month" class="form-select">
                <option value="">-- Pilih --</option>
                @foreach (range(1, 12) as $m)
                  <option value="{{ $m }}" {{ (isset($startMonth) && $startMonth == $m) ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small text-muted">Sampai Bulan</label>
              <select name="end_month" class="form-select">
                <option value="">-- Pilih --</option>
                @foreach (range(1, 12) as $m)
                  <option value="{{ $m }}" {{ (isset($endMonth) && $endMonth == $m) ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small text-muted">Kategori</label>
              <select name="category" class="form-select">
                <option value="">-- Semua Kategori --</option>
                <option value="academic" {{ $filterCategory == 'academic' ? 'selected' : '' }}>Akademik</option>
                <option value="islamic" {{ $filterCategory == 'islamic' ? 'selected' : '' }}>PHBI / Islam</option>
                <option value="boarding" {{ $filterCategory == 'boarding' ? 'selected' : '' }}>Keasramaan</option>
                <option value="holiday" {{ $filterCategory == 'holiday' ? 'selected' : '' }}>Libur</option>
              </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-primary w-100">Filter</button>
              <a href="{{ route('calendar.agenda') }}" class="btn btn-outline-danger w-100">Reset</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="printable-area bg-white p-4 rounded-4 shadow-sm">

      <div class="d-none d-print-block text-center mb-4 border-bottom pb-3">
        <h3 class="fw-bold mb-0">KALENDER AKADEMIK & KEGIATAN</h3>
        <h5 class="mb-0">{{ strtoupper($activeYear->name) }} - SEMESTER {{ strtoupper($activeYear->semester) }}</h5>
      </div>

      @if ($groupedEvents->isEmpty())
        <div class="alert alert-info text-center border-0 bg-info bg-opacity-10 py-5">
          <i class="bi bi-calendar-x display-4 text-info mb-3"></i><br>
          Tidak ada agenda kegiatan yang ditemukan pada rentang waktu ini.
        </div>
      @else
        @foreach ($groupedEvents as $yearMonth => $events)
          @php
            $dateObj = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth);
          @endphp

          <div class="mb-4 break-inside-avoid">
            <div class="d-flex align-items-center mb-2">
              <div class="bg-primary text-white rounded px-3 py-1 fw-bold me-2">
                {{ $dateObj->locale('id')->translatedFormat('F Y') }}
              </div>
              <div class="h-line flex-grow-1 bg-light" style="height: 2px;"></div>
            </div>

            <table class="table table-bordered align-middle">
              <thead class="bg-light text-center small text-uppercase">
                <tr>
                  <th width="15%">Tanggal</th>
                  <th width="15%">Hijriah</th>
                  <th width="40%">Nama Kegiatan</th>
                  <th width="15%">Kategori</th>
                  <th width="15%">Keterangan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($events as $event)
                  <tr>
                    <td class="text-center fw-bold text-dark">
                      {{ $event->start_date->format('d') }}
                      @if ($event->end_date && $event->end_date != $event->start_date)
                        - {{ $event->end_date->format('d') }}
                      @endif
                      {{ $event->start_date->locale('id')->translatedFormat('F') }}

                      @if ($event->start_date->isToday())
                        <span class="badge bg-danger d-print-none ms-1">Hari Ini</span>
                      @endif
                    </td>
                    <td class="text-center small text-muted fst-italic">
                      {{ $event->hijri_date ?? '-' }}
                    </td>
                    <td>
                      <div class="fw-bold">{{ $event->title }}</div>
                    </td>
                    <td class="text-center">
                      @php
                        $badgeColor = match ($event->category) {
                            'holiday' => 'bg-danger text-white',
                            'academic' => 'bg-primary text-white',
                            'islamic' => 'bg-success text-white',
                            'boarding' => 'bg-warning text-dark',
                            default => 'bg-secondary text-white',
                        };
                      @endphp
                      <span class="badge {{ $badgeColor }} border d-print-none">
                        {{ ucfirst($event->category) }}
                      </span>
                      <span class="d-none d-print-inline fw-bold small">
                        {{ ucfirst($event->category) }}
                      </span>

                      @if ($event->is_holiday)
                        <div class="small text-danger fw-bold mt-1">(Libur KBM)</div>
                      @endif
                    </td>
                    <td class="small text-muted">
                      {{ $event->description ?? '-' }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endforeach

        <div class="d-none d-print-block mt-5">
          <table style="width: 100%; border: none;">
            <tr>
              <td width="70%"></td>
              <td width="30%" class="text-center">
                Kota Santri, {{ now()->translatedFormat('d F Y') }}<br>
                Kepala Bagian Kurikulum
                <br><br><br><br>
                <strong>( ........................... )</strong>
              </td>
            </tr>
          </table>
        </div>
      @endif
    </div>
  </div>

  <style>
    @media print {

      /* Sembunyikan Sidebar, Navbar, dan Filter Form */
      .sidebar,
      nav,
      .d-print-none,
      footer,
      .btn {
        display: none !important;
      }

      /* Reset Container agar full width kertas */
      .container,
      .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      /* Styling Tabel Cetak */
      .printable-area {
        box-shadow: none !important;
        border: none !important;
      }

      .table th,
      .table td {
        border: 1px solid #000 !important;
        padding: 5px !important;
        font-size: 11pt;
      }

      /* Mencegah tabel terpotong jelek saat pindah halaman */
      .break-inside-avoid {
        page-break-inside: avoid;
      }

      body {
        background: white !important;
        font-family: 'Times New Roman', serif;
      }
    }
  </style>
@endsection
@push('scripts')
@endpush
