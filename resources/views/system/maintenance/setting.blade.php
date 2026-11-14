@extends('layouts.app')

@section('title', 'Pengaturan Mode Pemeliharaan')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@push('styles')
  <style>
    :root {
      --berry-primary: #2196f3;
      --berry-secondary: #5e35b1;
      --berry-success: #00c853;
      --berry-danger: #f44336;
      --berry-border: #e3e8ef;
      --berry-heading: #121926;
      --berry-text: #697586;
    }

    .berry-header {
      background: #fff;
      border-radius: 12px;
      border: 1px solid var(--berry-border);
      padding: 1.25rem 1.5rem;
    }
    .berry-header .page-title { color: var(--berry-heading); font-weight: 600; margin: 0; }
    .berry-header .breadcrumb { margin: 0 0 .35rem; font-size: .8125rem; }
    .berry-header .breadcrumb-item a { color: var(--berry-text); text-decoration: none; }
    .berry-header .breadcrumb-item.active { color: var(--berry-secondary); }

    .berry-card {
      background: #fff;
      border: 1px solid var(--berry-border);
      border-radius: 12px;
      box-shadow: none;
    }

    .toggle-box {
      border-radius: 12px;
      padding: 1.25rem 1.5rem;
      transition: all .2s;
    }
    .toggle-box.active-mode {
      background: #ffebee;
      border: 2px solid #ef5350;
    }
    .toggle-box.inactive-mode {
      background: #e8f5e9;
      border: 2px solid #66bb6a;
    }

    .form-switch-lg .form-check-input {
      width: 3.5rem;
      height: 1.75rem;
      cursor: pointer;
    }
  </style>
@endpush

@section('content')
<div class="row">
  <div class="col-12 mb-3">
    <div class="berry-header d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item">Sistem</li>
            <li class="breadcrumb-item active">Mode Pemeliharaan</li>
          </ol>
        </nav>
        <h4 class="page-title text-danger"><i class="ti ti-power me-1"></i> Kontrol Mode Pemeliharaan (Maintenance)</h4>
      </div>
      <div>
        <a href="{{ route('maintenance.screen') }}" target="_blank" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
          <i class="ti ti-external-link"></i> Pratinjau Layar Pemeliharaan
        </a>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="col-12 mb-3">
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 m-0" role="alert">
        <i class="ti ti-circle-check fs-4"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="col-12 col-lg-8 mx-auto">
    <div class="berry-card">
      <div class="p-3 border-bottom">
        <h5 class="mb-0 fw-bold text-dark">Pengaturan Status & Pesan Aplikasi</h5>
      </div>
      <div class="p-4">
        <form action="{{ route('system.maintenance-mode.update') }}" method="POST">
          @csrf

          <!-- SAKELAR STATUS MAINTENANCE -->
          <div class="toggle-box mb-4 {{ $maintenance['is_active'] ? 'active-mode' : 'inactive-mode' }}">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h5 class="fw-bold mb-1 {{ $maintenance['is_active'] ? 'text-danger' : 'text-success' }}">
                  <i class="ti {{ $maintenance['is_active'] ? 'ti-alert-triangle' : 'ti-circle-check' }} me-1"></i>
                  Status: {{ $maintenance['is_active'] ? 'MODE PEMELIHARAAN AKTIF' : 'APLIKASI AKTIF NORMAL' }}
                </h5>
                <p class="mb-0 small text-muted">
                  @if($maintenance['is_active'])
                    Aplikasi sedang dinonaktifkan untuk pengguna umum. Hanya akun <strong>Superadmin</strong> yang dapat mengakses sistem.
                  @else
                    Seluruh pengguna (Admin, Guru, Pegawai, Siswa, dan Pengunjung) dapat mengakses aplikasi secara normal.
                  @endif
                </p>
              </div>
              <div class="form-check form-switch form-switch-lg ms-3">
                <input type="hidden" name="is_maintenance" value="0">
                <input class="form-check-input" type="checkbox" name="is_maintenance" value="1" id="maintenanceSwitch" {{ $maintenance['is_active'] ? 'checked' : '' }} onchange="this.form.submit()">
              </div>
            </div>
          </div>

          <div class="alert alert-info d-flex align-items-start gap-2 mb-4 small">
            <i class="ti ti-info-circle fs-4 mt-1"></i>
            <div>
              <strong>Hak Akses Superadmin Tetap Terbuka:</strong><br>
              Saat mode ini diaktifkan, Anda selaku Superadmin tetap dapat membuka seluruh menu, memperbaiki pengaturan, mengelola data, dan memantau sistem seperti biasa tanpa terputus.
            </div>
          </div>

          <!-- PESAN & ESTIMASI -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Judul Pengumuman Pemeliharaan <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $maintenance['title']) }}" required placeholder="Contoh: Sistem Sedang Dalam Pemeliharaan Rutin">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Pesan / Penjelasan kepada Pengguna <span class="text-danger">*</span></label>
            <textarea name="message" rows="4" class="form-control" required placeholder="Tuliskan alasan pemeliharaan dan himbauan kepada pengguna...">{{ old('message', $maintenance['message']) }}</textarea>
            <small class="text-muted">Pesan ini akan dibaca oleh seluruh pengguna atau tamu yang mencoba membuka website saat dinonaktifkan.</small>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Estimasi Waktu Selesai (Opsional)</label>
              <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at', $maintenance['end_at'] ? \Carbon\Carbon::parse($maintenance['end_at'])->format('Y-m-d\TH:i') : '') }}">
              <small class="text-muted">Akan ditampilkan sebagai perkiraan waktu selesai untuk pengguna.</small>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Nomor WhatsApp Bantuan Darurat (Opsional)</label>
              <input type="text" name="contact_phone" class="form-control" placeholder="Contoh: 6281234567890" value="{{ old('contact_phone', $maintenance['contact_phone']) }}">
              <small class="text-muted">Format internasional (diawali 62), akan menjadi tombol chat WA darurat.</small>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <button type="submit" class="btn btn-primary px-4 fw-semibold">
              <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
