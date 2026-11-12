@extends('layouts.app')

@section('title', 'Detail Calon Santri - ' . $registration->name)

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@push('styles')
  <style>
    :root {
      --berry-primary: #2196f3;
      --berry-primary-light: #e3f2fd;
      --berry-secondary: #5e35b1;
      --berry-secondary-light: #ede7f6;
      --berry-success: #00c853;
      --berry-success-light: #b9f6ca;
      --berry-warning: #ffab00;
      --berry-warning-light: #fff8e1;
      --berry-danger: #f44336;
      --berry-danger-light: #fbe9e7;
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

    .info-group {
      margin-bottom: 1.15rem;
    }
    .info-label {
      font-size: .75rem;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: var(--berry-text);
      font-weight: 600;
      margin-bottom: .25rem;
    }
    .info-value {
      font-size: .95rem;
      font-weight: 500;
      color: var(--berry-heading);
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
            <li class="breadcrumb-item"><a href="{{ route('registrations.index') }}">Penerimaan Santri (PSB)</a></li>
            <li class="breadcrumb-item active">Detail Calon Santri</li>
          </ol>
        </nav>
        <h4 class="page-title">{{ $registration->name }}</h4>
      </div>
      <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('registrations.index') }}" class="btn btn-outline-secondary">
          <i class="ti ti-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('psb.slip', $registration->id) }}" target="_blank" class="btn btn-outline-primary">
          <i class="ti ti-printer"></i> Cetak Bukti PDF
        </a>
        {{-- <button type="button" class="btn btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal">
          <i class="ti ti-receipt"></i> Verifikasi Pembayaran
        </button> --}}
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
          <i class="ti ti-edit"></i> Ubah Status / Nilai
        </button>
        @if($registration->status !== 'enrolled')
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#enrollModal">
            <i class="ti ti-user-plus"></i> Terima Santri Aktif
          </button>
        @else
          <a href="{{ route('student.biodata.show', $registration->student_id) }}" class="btn btn-success">
            <i class="ti ti-id"></i> Lihat Santri Aktif
          </a>
        @endif
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

  @if(session('error'))
    <div class="col-12 mb-3">
      <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 m-0" role="alert">
        <i class="ti ti-alert-circle fs-4"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <!-- STATUS BANNER -->
  <div class="col-12 mb-4">
    <div class="berry-card p-3 p-md-4">
      <div class="row align-items-center g-3">
        <div class="col-12 col-md-auto text-center text-md-start">
          <div class="d-inline-flex p-3 rounded-circle {{ $registration->gender == 'L' ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning' }}" style="font-size: 2.25rem;">
            <i class="ti {{ $registration->gender == 'L' ? 'ti-man' : 'ti-woman' }}"></i>
          </div>
        </div>
        <div class="col-12 col-md">
          <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
            <h4 class="mb-0 fw-bold">{{ $registration->name }}</h4>
            <span class="badge bg-light text-dark border">{{ $registration->target_stage }}</span>
            @php
              $badgeColors = [
                'pending' => 'bg-warning-subtle text-warning border border-warning-subtle',
                'verified' => 'bg-info-subtle text-info border border-info-subtle',
                'passed' => 'bg-success-subtle text-success border border-success-subtle',
                'rejected' => 'bg-danger-subtle text-danger border border-danger-subtle',
                'enrolled' => 'bg-primary-subtle text-primary border border-primary-subtle',
              ];
            @endphp
            <span class="badge {{ $badgeColors[$registration->status] ?? 'bg-secondary' }} px-3 py-1">
              {{ $registration->status_label }}
            </span>
            {!! $registration->payment_status_badge !!}
          </div>
          <div class="text-muted small">
            No. Registrasi: <span class="fw-semibold text-primary font-monospace">{{ $registration->registration_number }}</span>
            &bull; Tanggal Daftar: {{ $registration->created_at->format('d F Y, H:i') }} WIB
            @if($registration->academicYear)
              &bull; Tahun Ajaran: {{ $registration->academicYear->name }}
            @endif
          </div>
        </div>
        <div class="col-12 col-md-auto text-md-end border-start-md ps-md-4">
          <div class="text-muted small">Nilai Seleksi / Ujian</div>
          <div class="fs-2 fw-bold {{ $registration->selection_score >= 70 ? 'text-success' : ($registration->selection_score !== null ? 'text-danger' : 'text-muted') }}">
            {{ $registration->selection_score !== null ? number_format($registration->selection_score, 1) : '-' }}
          </div>
        </div>
      </div>
      @if($registration->notes)
        <div class="alert alert-light border mt-3 mb-0 py-2 px-3 small">
          <strong><i class="ti ti-notes"></i> Catatan Panitia:</strong> {{ $registration->notes }}
        </div>
      @endif
    </div>
  </div>

  <!-- BUKTI & INFORMASI PEMBAYARAN -->
  <div class="col-12 mb-4">
    <div class="berry-card">
      <div class="p-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
          <i class="ti ti-receipt text-primary fs-5"></i>
          <h6 class="mb-0 fw-bold">Bukti & Informasi Pembayaran Pendaftaran</h6>
        </div>
        <div class="d-flex align-items-center gap-2">
          {!! $registration->payment_status_badge !!}
          <button type="button" class="btn btn-sm btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal">
            <i class="ti ti-edit"></i> Verifikasi Pembayaran
          </button>
        </div>
      </div>
      <div class="p-3 p-md-4">
        <div class="row g-3 align-items-center">
          <div class="col-12 col-md-4 text-center">
            @if($registration->payment_receipt)
              @php
                $ext = pathinfo($registration->payment_receipt, PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']);
              @endphp
              @if($isImage)
                <div class="border rounded p-2 bg-light d-inline-block position-relative">
                  <img src="{{ $registration->payment_receipt_url }}" alt="Slip Pembayaran" class="img-fluid rounded shadow-sm" style="max-height: 200px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#receiptPreviewModal">
                  <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#receiptPreviewModal">
                      <i class="ti ti-zoom-in"></i> Perbesar Slip
                    </button>
                    <a href="{{ $registration->payment_receipt_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                      <i class="ti ti-external-link"></i> Tab Baru
                    </a>
                  </div>
                </div>
              @else
                <div class="p-4 border rounded bg-light text-center">
                  <i class="ti ti-file-text fs-1 text-danger d-block mb-2"></i>
                  <a href="{{ $registration->payment_receipt_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-download"></i> Buka Berkas Slip (PDF)
                  </a>
                </div>
              @endif
            @else
              <div class="p-4 border rounded bg-light text-center text-muted">
                <i class="ti ti-file-off fs-1 d-block mb-2"></i>
                <small>Tidak ada berkas slip pembayaran yang dilampirkan.</small>
              </div>
            @endif
          </div>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-sm-6 info-group">
                <div class="info-label">Nama Pengirim / Pemilik Rekening</div>
                <div class="info-value">{{ $registration->payment_sender_name ?: '-' }}</div>
              </div>
              <div class="col-12 col-sm-6 info-group">
                <div class="info-label">Bank Pengirim</div>
                <div class="info-value">{{ $registration->payment_bank_name ?: '-' }}</div>
              </div>
              <div class="col-12 col-sm-6 info-group">
                <div class="info-label">Nominal Transfer</div>
                <div class="info-value text-success fw-bold fs-6">
                  Rp {{ $registration->payment_amount ? number_format($registration->payment_amount, 0, ',', '.') : '-' }}
                </div>
              </div>
              <div class="col-12 col-sm-6 info-group">
                <div class="info-label">Tanggal Transfer</div>
                <div class="info-value">
                  {{ $registration->payment_date ? $registration->payment_date->translatedFormat('d F Y') : '-' }}
                </div>
              </div>
              @if($registration->payment_notes)
                <div class="col-12">
                  <div class="alert alert-light border py-2 px-3 mb-0 small">
                    <strong><i class="ti ti-info-circle"></i> Catatan Verifikator Keuangan:</strong> {{ $registration->payment_notes }}
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- BIODATA SANTRI & DATA ALAMAT -->
  <div class="col-12 col-lg-6 mb-4">
    <div class="berry-card h-100">
      <div class="p-3 border-bottom d-flex align-items-center gap-2">
        <i class="ti ti-user text-primary fs-5"></i>
        <h6 class="mb-0 fw-bold">Data Pribadi Calon Santri</h6>
      </div>
      <div class="p-3 p-md-4">
        <div class="row">
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Nama Lengkap</div>
            <div class="info-value">{{ $registration->name }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Jenis Kelamin</div>
            <div class="info-value">{{ $registration->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">NIK (Nomor Induk Kependudukan)</div>
            <div class="info-value font-monospace">{{ $registration->nik ?: '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">NISN</div>
            <div class="info-value font-monospace">{{ $registration->nisn ?: '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Nomor Kartu Keluarga (KK)</div>
            <div class="info-value font-monospace">{{ $registration->family_card_number ?: '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Tempat, Tanggal Lahir</div>
            <div class="info-value">{{ $registration->birth_place }}, {{ $registration->birth_date ? \Carbon\Carbon::parse($registration->birth_date)->format('d F Y') : '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Anak Ke-</div>
            <div class="info-value">{{ $registration->child_order ? 'Anak ke-' . $registration->child_order : '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Sekolah Asal</div>
            <div class="info-value">{{ $registration->previous_school ?: '-' }}</div>
          </div>
        </div>

        <hr class="my-3 text-muted">

        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="ti ti-map-pin text-primary"></i> Alamat Tempat Tinggal
        </h6>
        <div class="row">
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Provinsi</div>
            <div class="info-value">{{ $registration->province ?: '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Kabupaten / Kota</div>
            <div class="info-value">{{ $registration->regency ?: '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Kecamatan</div>
            <div class="info-value">{{ $registration->district ?: '-' }}</div>
          </div>
          <div class="col-12 col-sm-6 info-group">
            <div class="info-label">Desa / Gampong</div>
            <div class="info-value">{{ $registration->village ?: '-' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- DATA ORANG TUA & WALI -->
  <div class="col-12 col-lg-6 mb-4">
    <div class="berry-card h-100">
      <div class="p-3 border-bottom d-flex align-items-center gap-2">
        <i class="ti ti-users text-primary fs-5"></i>
        <h6 class="mb-0 fw-bold">Data Orang Tua & Wali</h6>
      </div>
      <div class="p-3 p-md-4">
        <!-- DATA AYAH -->
        <div class="mb-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-primary mb-0"><i class="ti ti-user"></i> Data Ayah Kandung</h6>
            <span class="badge {{ $registration->father_status == 'deceased' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} small">
              {{ $registration->father_status == 'deceased' ? 'Meninggal Dunia' : 'Masih Hidup' }}
            </span>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">Nama Ayah</div>
              <div class="info-value">{{ $registration->father_name ?: '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">No. Telepon / WhatsApp</div>
              <div class="info-value">
                @if($registration->father_phone)
                  <a href="https://wa.me/{{ $registration->father_phone }}" target="_blank" class="text-success text-decoration-none">
                    <i class="bi bi-whatsapp"></i> +{{ $registration->father_phone }}
                  </a>
                @else
                  -
                @endif
              </div>
            </div>
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">Pendidikan Terakhir</div>
              <div class="info-value">{{ $registration->father_education ?: '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">Pekerjaan</div>
              <div class="info-value">
                {{ $registration->father_occupation ?: '-' }}
                @if($registration->father_occupation_detail)
                  <span class="text-muted small">({{ $registration->father_occupation_detail }})</span>
                @endif
              </div>
            </div>
          </div>
        </div>

        <hr class="my-3 text-muted">

        <!-- DATA IBU -->
        <div class="mb-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-primary mb-0"><i class="ti ti-user-heart"></i> Data Ibu Kandung</h6>
            <span class="badge {{ $registration->mother_status == 'deceased' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} small">
              {{ $registration->mother_status == 'deceased' ? 'Meninggal Dunia' : 'Masih Hidup' }}
            </span>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">Nama Ibu</div>
              <div class="info-value">{{ $registration->mother_name ?: '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">No. Telepon / WhatsApp</div>
              <div class="info-value">
                @if($registration->mother_phone)
                  <a href="https://wa.me/{{ $registration->mother_phone }}" target="_blank" class="text-success text-decoration-none">
                    <i class="bi bi-whatsapp"></i> +{{ $registration->mother_phone }}
                  </a>
                @else
                  -
                @endif
              </div>
            </div>
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">Pendidikan Terakhir</div>
              <div class="info-value">{{ $registration->mother_education ?: '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 info-group">
              <div class="info-label">Pekerjaan</div>
              <div class="info-value">
                {{ $registration->mother_occupation ?: '-' }}
                @if($registration->mother_occupation_detail)
                  <span class="text-muted small">({{ $registration->mother_occupation_detail }})</span>
                @endif
              </div>
            </div>
          </div>
        </div>

        @if($registration->guardian_name)
          <hr class="my-3 text-muted">
          <!-- DATA WALI -->
          <div>
            <h6 class="fw-bold text-primary mb-2"><i class="ti ti-shield"></i> Data Wali</h6>
            <div class="row">
              <div class="col-12 col-sm-6 info-group">
                <div class="info-label">Nama Wali</div>
                <div class="info-value">{{ $registration->guardian_name }}</div>
              </div>
              <div class="col-12 col-sm-6 info-group">
                <div class="info-label">No. Telepon / WhatsApp</div>
                <div class="info-value">
                  @if($registration->guardian_phone)
                    <a href="https://wa.me/{{ $registration->guardian_phone }}" target="_blank" class="text-success text-decoration-none">
                      <i class="bi bi-whatsapp"></i> +{{ $registration->guardian_phone }}
                    </a>
                  @else
                    -
                  @endif
                </div>
              </div>
              <div class="col-12 col-sm-6 info-group">
                <div class="info-label">Pekerjaan</div>
                <div class="info-value">
                  {{ $registration->guardian_occupation ?: '-' }}
                  @if($registration->guardian_occupation_detail)
                    <span class="text-muted small">({{ $registration->guardian_occupation_detail }})</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- MODAL VERIFIKASI PEMBAYARAN -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('registrations.verify-payment', $registration->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Verifikasi Pembayaran Pendaftaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Status Pembayaran <span class="text-danger">*</span></label>
            <select name="payment_status" class="form-select" required>
              <option value="verified" {{ $registration->payment_status == 'verified' ? 'selected' : '' }}>Pembayaran Sah / Lunas (Verified)</option>
              <option value="unverified" {{ $registration->payment_status == 'unverified' ? 'selected' : '' }}>Menunggu Verifikasi (Unverified)</option>
              <option value="rejected" {{ $registration->payment_status == 'rejected' ? 'selected' : '' }}>Ditolak / Slip Tidak Valid (Rejected)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catatan Verifikasi Keuangan</label>
            <textarea name="payment_notes" class="form-control" rows="3" placeholder="Contoh: Dana masuk sesuai mutasi BSI atau struk transfer tidak terbaca...">{{ $registration->payment_notes }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL PREVIEW SLIP BESAR -->
@if($registration->payment_receipt)
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bukti Transfer - {{ $registration->payment_sender_name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-2 bg-dark">
        <img src="{{ $registration->payment_receipt_url }}" alt="Slip Pembayaran" class="img-fluid" style="max-height: 80vh;">
      </div>
      <div class="modal-footer">
        <a href="{{ $registration->payment_receipt_url }}" download class="btn btn-outline-secondary">
          <i class="ti ti-download"></i> Unduh Gambar
        </a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endif

<!-- MODAL UPDATE STATUS & SKOR -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('registrations.update-status', $registration->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Ubah Status & Nilai Seleksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Status Pendaftaran <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
              <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi (Pending)</option>
              <option value="verified" {{ $registration->status == 'verified' ? 'selected' : '' }}>Berkas Terverifikasi (Verified)</option>
              <option value="passed" {{ $registration->status == 'passed' ? 'selected' : '' }}>Lulus Seleksi (Passed)</option>
              <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Tidak Lulus / Ditolak (Rejected)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Nilai Seleksi / Tes (0 - 100)</label>
            <input type="number" step="0.1" min="0" max="100" name="selection_score" class="form-control" placeholder="Contoh: 85.5" value="{{ $registration->selection_score }}">
            <small class="text-muted">Masukkan nilai tes membaca Quran, wawancara, atau tes akademik calon santri.</small>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catatan Panitia</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Tuliskan catatan kelengkapan berkas atau hasil seleksi...">{{ $registration->notes }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL ENROLL MENJADI SANTRI AKTIF -->
@if($registration->status !== 'enrolled')
<div class="modal fade" id="enrollModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('registrations.enroll', $registration->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Terima Menjadi Santri Aktif</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info small mb-3">
            Tindakan ini akan memindahkan data calon santri <strong>{{ $registration->name }}</strong> ke tabel <strong>Santri Aktif</strong> dan mengubah status pendaftaran menjadi <em>Santri Aktif (Enrolled)</em>.
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Nomor Induk Santri (NIS) <span class="text-danger">*</span></label>
            <input type="text" name="nis" class="form-control" required placeholder="Contoh: 202601001" value="{{ old('nis') }}">
            <small class="text-muted">NIS harus unik dan belum terdaftar di sistem santri aktif.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="ti ti-check"></i> Konfirmasi & Terima</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endsection

