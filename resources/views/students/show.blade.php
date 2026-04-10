@extends('layouts.app')
@section('title', 'Students')

@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush
@push('styles')
<style>
  /* Custom Profile Styling */
  .profile-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #edf2f7;
  }

  .avatar-lg {
    width: 100px;
    height: 100px;
    font-size: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    font-weight: 700;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }

  .info-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #9ca3af;
    /* Gray-400 */
    font-weight: 600;
    margin-bottom: 0.25rem;
  }

  .info-value {
    font-size: 0.95rem;
    color: #1f2937;
    /* Gray-800 */
    font-weight: 500;
  }

  .card-section {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    transition: transform 0.2s;
  }

  .card-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .icon-box {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
  }

  /* Styling Timeline Sederhana */
  .timeline {
    border-left: 2px solid #e5e7eb;
    margin-left: 10px;
    padding-left: 20px;
    position: relative;
  }

  .timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
  }

  .timeline-dot {
    width: 12px;
    height: 12px;
    background-color: #d1d5db;
    border-radius: 50%;
    position: absolute;
    left: -27px;
    top: 5px;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px #d1d5db;
  }

  .timeline-item.active .timeline-dot {
    background-color: #4f46e5;
    box-shadow: 0 0 0 4px #e0e7ff;
  }

  /* styling classroom history  */
  .academic-timeline {
    position: relative;
    padding-left: 1.5rem;
    margin-left: 0.5rem;
    border-left: 2px solid #e5e7eb;
  }

  .academic-item {
    position: relative;
    margin-bottom: 1.5rem;
  }

  .academic-item:last-child {
    margin-bottom: 0;
  }

  .academic-dot {
    position: absolute;
    left: -1.95rem;
    top: 0.25rem;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color: #fff;
    border: 2px solid #9ca3af;
  }

  .academic-item.current .academic-dot {
    background-color: #4f46e5;
    /* Primary color */
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
  }

  /* Parent Card Styling */
  .parent-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
  }

  .parent-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
  }

  .bg-father {
    background: linear-gradient(160deg, #ffffff 0%, #f0f9ff 100%);
    border-top: 3px solid #0ea5e9;
    /* Sky Blue */
  }

  .bg-mother {
    background: linear-gradient(160deg, #ffffff 0%, #fff1f2 100%);
    border-top: 3px solid #ec4899;
    /* Pink */
  }

  /* --- Pengaturan Print (Cetak) --- */
  @media print {
    body {
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      background-color: white !important;
    }
    /* Sembunyikan Header Topbar & Sidebar Bawaan Berry Template */
    .pc-sidebar, .pc-header {
      display: none !important;
    }
    /* Hilangkan padding margin sidebar agar konten penuh */
    .pc-container {
      margin-left: 0 !important;
      margin-top: 0 !important;
      padding: 0 !important;
    }
    .card {
      box-shadow: none !important;
      border: 1px solid #e5e7eb !important;
      break-inside: avoid;
    }
    /* Tampilkan selalu Data Wali meskipun di-collapse */
    .collapse {
      display: block !important;
      height: auto !important;
    }
  }
</style>
@endpush

@section('content')


<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold text-dark mb-1">Detail Santri</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small">
          <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Data
              Santri</a></li>
          <li class="breadcrumb-item active text-primary" aria-current="page">Detail</li>
        </ol>
      </nav>
    </div>
    <div class="d-flex gap-2 align-items-center">
      <button onclick="window.print()" class="btn btn-outline-primary shadow-sm rounded-3 d-print-none">
        <i class="bi bi-printer me-2"></i>Cetak
      </button>
      <a href="{{ route('students.index') }}" class="btn btn-outline-secondary text-mute shadow-sm rounded-3 d-print-none">
        <i class="bi bi-arrow-left me-2"></i>Kembali
      </a>
    </div>
  </div>

  <div class="card profile-header rounded-4 mb-4 shadow-sm position-relative">
    <div class="card-body p-4 p-md-5">
      <div class="row align-items-center">
        <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
          @php
          $colors = ['#4F46E5', '#059669', '#D97706', '#DC2626', '#7C3AED'];
          // Hash nama untuk dapat warna konsisten
          $colorIndex = crc32($student->name) % count($colors);
          $bgColor = $colors[$colorIndex];
          $initial = strtoupper(substr($student->name, 0, 1));
          @endphp
          <div class="avatar-lg mx-auto" style="background-color: {{ $bgColor }}">
            {{ $initial }}
          </div>
        </div>
        <div class="col-md text-center text-md-start">
          <h2 class="fw-bold text-dark mb-1">{{ $student->name }}</h2>
          <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 mb-2">
            <span class="badge bg-light text-dark border">
              <i class="bi bi-upc-scan me-1"></i> NIS: {{ $student->nis }}
            </span>
            @if ($student->nisn)
            <span class="badge bg-light text-dark border">
              NISN: {{ $student->nisn }}
            </span>
            @endif
            <span class="badge {{ $student->gender == 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
              {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </span>
          </div>
          <div class="text-muted small">
            <i class="bi bi-calendar-check me-1"></i> Terdaftar sejak:
            {{ $student->created_at->locale('id')->translatedFormat('d F Y') }}
          </div>
        </div>
        <div class="col-md-auto mt-3 mt-md-3 d-flex flex-wrap gap-2 justify-content-center d-print-none">

          {{-- tombol riwayat rapor --}}
          <div class="dropdown ms-2">
            <button class="btn btn-success text-white dropdown-toggle rounded-3 shadow-sm px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-journal-text me-2"></i>Rapor
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('student.history', $student->id) }}">Rapor Akademik</a></li>
              <li><a class="dropdown-item" href="{{ route('tahfizh.assessment.history', $student->id) }}">Rapor Tahfizh</a></li>
            </ul>
          </div>
          {{-- tombol riwayat pelanggaran --}}
          <a href="{{ route('violations.index', $student->id) }}" class="btn btn-danger text-white rounded-3 shadow-sm px-3">
            <i class="bi bi-journal-text me-2"></i>Pelanggaran
          </a>
          {{-- tombol riwayat izin --}}
          <a href="{{ route('students.permissions', $student->id) }}" class="btn btn-info text-white rounded-3 shadow-sm px-3">
            <i class="bi bi-journal-text me-2"></i>Riwayat Izin
          </a>
          {{-- tombol pindah kamar --}}
          <button class="btn btn-primary text-white rounded-3 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#moveRoomModal">
            <i class="bi bi-arrow-left-right me-2"></i>Pindah Kamar
          </button>
          @can('kelola-data-santri')
          {{-- tombol edit data --}}
          <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning text-white rounded-3 shadow-sm px-4">
            <i class="bi bi-pencil-square me-2"></i>Edit
          </a>
          <button class="btn btn-outline-danger rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="bi bi-trash"></i>
          </button>
          @endcan
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card card-section mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="fw-bold text-dark">
            <i class="bi bi-info-circle me-2 text-primary"></i>Status Akademik
          </h6>
          <div class="d-print-none">
            <a href="{{ route('tahfizh.report.show', $student->id) }}" class="btn btn-sm btn-info text-white rounded me-1" title="Lihat Hafalan">
              <i class="bi bi-bar-chart-line"></i>
            </a>
            @if ($student->status == 'active')
            @can('kelola-data-santri')
            <button type="button" class="btn btn-warning btn-sm text-dark fw-bold rounded" data-bs-toggle="modal" data-bs-target="#modalMutasi">
              <i class="bi bi-box-arrow-right me-2"></i> Mutasi
            </button>
            @endcan
            @else

            <div class="alert alert-secondary d-inline-block py-1 px-3 mb-0">
              <i class="bi bi-info-circle me-2"></i>
              Status: <strong>{{ strtoupper($student->status) }}</strong>
              @if ($student->exitDetail)
              <small class="ms-2 text-muted">({{ $student->exitDetail->exit_date->translatedFormat('d M Y') }})</small>
              @endif
            </div>
            @endif
          </div>
        </div>
        <div class="card-body p-4">
          <div class="text-center mb-2">
            @php
            $statusConfig = match ($student->status) {
            'active' => ['bg' => '#00ff9d', 'text' => '#0f5132', 'icon' => 'bi-check-circle-fill', 'label' => 'Aktif Belajar'],
            'graduated' => ['bg' => '#cfe2ff', 'text' => '#084298', 'icon' => 'bi-mortarboard-fill', 'label' => 'Lulus'],
            'suspended' => ['bg' => '#f8d7da', 'text' => '#842029', 'icon' => 'bi-exclamation-triangle-fill', 'label' => 'Skorsing'],
            'moved' => ['bg' => '#fff3cd', 'text' => '#664d03', 'icon' => 'bi-arrow-right-circle-fill', 'label' => 'Pindah'],
            default => ['bg' => '#e2e3e5', 'text' => '#41464b', 'icon' => 'bi-question-circle-fill', 'label' => ucfirst($student->status)],
            };
            @endphp

            <div class="d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm" style="background-color: {{ $statusConfig['bg'] }}; border: 1px solid rgba(0,0,0,0.05);">
              <i class="bi {{ $statusConfig['icon'] }} me-2 fs-5" style="color: {{ $statusConfig['text'] }};"></i>
              <span class="fw-bold text-uppercase small" style="color: {{ $statusConfig['text'] }}; letter-spacing: 1px;">
                {{ $statusConfig['label'] }}
              </span>
            </div>
          </div>

          <div class="row g-3">
            @php
            $infoItems = [
            ['label' => 'Jenjang Pendidikan', 'value' => $student->education_level, 'icon' => 'bi-mortarboard', 'color' => 'indigo'],
            ['label' => 'Kelas & Jurusan', 'value' => ($student->class_group ?? '-') . ($student->major ? " ({$student->major})" : ""), 'icon' => 'bi-easel', 'color' => 'info'],
            ['label' => 'Asal Sekolah', 'value' => $student->previous_school, 'icon' => 'bi-building', 'color' => 'orange'],
            ['label' => 'Tanggal Masuk', 'value' => $student->acceptance_date?->translatedFormat('d F Y'), 'icon' => 'bi-calendar-check', 'color' => 'success'],
            ];
            @endphp

            @foreach($infoItems as $item)
            <div class="col-12">
              <div class="py-1 px-2 rounded-4 border-0 shadow-sm bg-white d-flex align-items-center transition-hover" style="border-left: 4px solid var(--bs-{{ $item['color'] == 'indigo' ? 'primary' : ($item['color'] == 'orange' ? 'warning' : $item['color']) }}) !important;">

                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3 bg-{{ $item['color'] == 'indigo' ? 'primary' : ($item['color'] == 'orange' ? 'warning' : $item['color']) }} bg-opacity-10 text-{{ $item['color'] == 'indigo' ? 'primary' : ($item['color'] == 'orange' ? 'warning' : $item['color']) }}" style="width: 48px; height: 48px;">
                  <i class="bi {{ $item['icon'] }} fs-4"></i>
                </div>

                <div class="ms-3">
                  <p class="text-muted small mb-0 fw-medium uppercase tracking-tight">{{ $item['label'] }}</p>
                  <h6 class="mb-0 fw-bold text-dark">{{ $item['value'] ?? '-' }}</h6>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="card card-section mb-4">
        <div class="card-body">
          <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Kamar
          </h6>

          <div class="timeline">
            @forelse($student->roomHistories as $history)
            <div class="timeline-item {{ $history->is_active ? 'active' : '' }}">
              <div class="timeline-dot"></div>
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                    {{ $history->room->dormitory_name }} - {{ $history->room->name }}
                  </h6>
                  <small class="text-muted d-block">
                    {{ $history->reason ?? 'Penempatan Awal' }}
                  </small>
                </div>
                <div class="text-end">
                  <span class="badge {{ $history->is_active ? 'bg-success' : 'bg-light text-muted border' }} rounded-pill" style="font-size: 0.7rem;">
                    {{ $history->start_date->format('d/m/y') }}
                  </span>
                </div>
              </div>
              @if (!$history->is_active)
              <small class="text-muted" style="font-size: 0.75rem;">
                s/d {{ $history->end_date ? $history->end_date->format('d/m/y') : 'Sekarang' }}
              </small>
              @endif
            </div>
            @empty
            <p class="text-muted small fst-italic">Belum ada riwayat kamar.</p>
            @endforelse
          </div>
        </div>
      </div>

      <div class="card card-section mb-4">
        <div class="card-body">
          <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="bi bi-mortarboard-fill me-2 text-primary"></i>Riwayat Kelas
          </h6>

          <div class="academic-timeline">
            @forelse($student->classrooms as $class)
            @php
            // Cek apakah ini kelas di tahun ajaran aktif saat ini
            $isActive = $class->academicYear->is_active;
            @endphp

            <div class="academic-item {{ $isActive ? 'current' : '' }}">
              <div class="academic-dot"></div>

              <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-3">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge {{ $isActive ? 'bg-primary' : 'bg-secondary text-light' }} rounded-pill">
                      {{ $class->academicYear->name }} - {{ $class->academicYear->semester }}
                    </span>

                    @if ($isActive)
                    <small class="text-primary fw-bold">Sedang Menempuh</small>
                    @else
                    <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i>Selesai</small>
                    @endif
                  </div>

                  <h5 class="fw-bold text-dark mb-1">
                    {{ $class->name }}
                  </h5>

                  <div class="small text-muted mb-2">
                    {{ $class->level->stage->code ?? '' }} &bull; {{ $class->level->name }}
                    @if ($class->major)
                    &bull; Jurusan {{ $class->major->name }}
                    @endif
                  </div>

                  <div class="d-flex align-items-center border-top pt-2 mt-2">
                    <div class="avatar-xs bg-white text-muted border rounded-circle me-2 d-flex justify-content-center align-items-center" style="width:24px; height:24px; font-size:10px;">
                      <i class="bi bi-person"></i>
                    </div>
                    <small class="text-muted">
                      Wali: <strong>{{ $class->homeroom_teacher ?? '-' }}</strong>
                    </small>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="text-center py-3">
              <p class="text-muted small fst-italic">Belum ada riwayat kelas.</p>
            </div>
            @endforelse
          </div>

        </div>
      </div>

      <div class="card card-section bg-secondary text-white">
        <div class="card-body">
          <h6 class="fw-bold border-bottom border-white pb-2 mb-3 border-opacity-25 text-white">
            <i class="bi bi-building me-2"></i>Lokasi Asrama
          </h6>
          <div class="row">
            <div class="col-6">
              <div class="small text-white text-opacity-75">Gedung</div>
              <div class="fs-5 fw-bold">{{ $student->dormitory ?? 'Non-Mukim' }}</div>
            </div>
            <div class="col-6">
              <div class="small text-white text-opacity-75">Kamar</div>
              <div class="fs-5 fw-bold">{{ $student->room ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">

      <div class="card card-section mb-4">
        <div class="card-body p-4">
          <h6 class="fw-bold text-dark border-bottom pb-3 mb-4 d-flex align-items-center">
            <div class="icon-box bg-warning bg-opacity-10 text-warning me-2">
              <i class="bi bi-person-lines-fill"></i>
            </div>
            Biodata Diri
          </h6>

          
          <div class="p-4 bg-light bg-opacity-50 rounded-4 border border-light-subtle">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="d-flex align-items-start">
                  <div class="me-3 mt-1 text-warning opacity-75">
                    <i class="bi bi-calendar-event fs-5"></i>
                  </div>
                  <div>
                    <div class="info-label mb-1">Tempat, Tanggal Lahir</div>
                    <div class="fw-bold text-dark fs-5">
                      {{ $student->birth_place }}, {{ $student->birth_date->locale('id')->translatedFormat('d F Y') }}
                    </div>
                    <div class="text-muted small mt-1">
                      <i class="bi bi-hourglass-split me-1"></i>Usia {{ $student->birth_date->age }} Tahun
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="d-flex align-items-start">
                  <div class="me-3 mt-1 text-warning opacity-75">
                    <i class="bi bi-sort-numeric-down fs-5"></i>
                  </div>
                  <div>
                    <div class="info-label mb-1">Anak Ke-</div>
                    <div class="fw-bold text-dark fs-5">
                      {{ $student->child_order ?? '-' }}
                    </div>
                  </div>
                </div>
              </div>

              {{-- <div class="col-12">
                <hr class="border-primary my-1">
              </div> --}}

              <div class="col-md-6">
                <div class="d-flex align-items-start">
                  <div class="me-3 mt-1 text-warning opacity-75">
                    <i class="bi bi-card-heading fs-5"></i>
                  </div>
                  <div>
                    <div class="info-label mb-1">NIK (Nomor Induk Kependudukan)</div>
                    <div class="font-monospace fs-5 text-dark bg-white px-2 py-1 rounded border border-light-subtle d-inline-block">
                      {{ $student->nik ?? '-' }}
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="d-flex align-items-start">
                  <div class="me-3 mt-1 text-warning opacity-75">
                    <i class="bi bi-people fs-5"></i>
                  </div>
                  <div>
                    <div class="info-label mb-1">Nomor Kartu Keluarga</div>
                    <div class="font-monospace fs-5 text-dark bg-white px-2 py-1 rounded border border-light-subtle d-inline-block">
                      {{ $student->family_card_number ?? '-' }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="card card-section mb-4">
        <div class="card-body p-4">
          <h6 class="fw-bold text-dark border-bottom pb-3 mb-4 d-flex align-items-center">
            <div class="icon-box bg-success bg-opacity-10 text-success me-2">
              <i class="bi bi-people-fill"></i>
            </div>
            Data Orang Tua
          </h6>

          <div class="row g-4">
            <div class="col-md-6">
              <div class="card parent-card bg-father h-100 rounded-4">
                <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                      <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3">
                        <i class="bi bi-gender-male fs-5"></i>
                      </div>
                      <div>
                        <h6 class="fw-bold text-dark mb-0">Ayah</h6>
                        <small class="text-muted">Data Ayah Kandung</small>
                      </div>
                    </div>
                    @if ($student->father_status == 'alive')
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Hidup</span>
                    @elseif ($student->father_status == 'deceased')
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Almarhum</span>
                    @endif
                  </div>

                  <div class="mb-3">
                    <div class="info-label text-primary opacity-75">Nama Lengkap</div>
                    <div class="fw-bold text-dark fs-5">{{ $student->father_name ?? '-' }}</div>
                  </div>

                  <div class="row g-3">
                    <div class="col-12">
                      <div class="info-label">NIK</div>
                      <div class="info-value font-monospace text-dark">{{ $student->father_nik ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                      <div class="info-label">Pekerjaan</div>
                      <div class="info-value">
                        {{ $student->father_occupation ?? '-' }}
                        @if ($student->father_occupation_detail)
                        <i class="bi bi-info-circle-fill text-primary ms-1 btn-detail-occupation" style="cursor: pointer;" data-title="Detail Pekerjaan Ayah" data-detail="{{ $student->father_occupation_detail }}"></i>
                        @endif
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="info-label">Pendidikan</div>
                      <div class="info-value">{{ $student->father_education ?? '-' }}</div>
                    </div>
                    <div class="col-12 pt-2 border-top border-primary border-opacity-10">
                      <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-whatsapp me-2 text-success fs-5"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->father_phone) }}" target="_blank">
                          <span class="fw-medium">
                            @if($student->father_phone)
                            {{ preg_replace('/(\d{2})(\d{3})(\d{4})(\d+)/', '$1 $2-$3-$4', $student->father_phone) }}
                            @else
                            -
                            @endif
                          </span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card parent-card bg-mother h-100 rounded-4">
                <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                      <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle me-3">
                        <i class="bi bi-gender-female fs-5"></i>
                      </div>
                      <div>
                        <h6 class="fw-bold text-dark mb-0">Ibu</h6>
                        <small class="text-muted">Data Ibu Kandung</small>
                      </div>
                    </div>
                    @if ($student->mother_status == 'alive')
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Hidup</span>
                    @elseif ($student->mother_status == 'deceased')
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Almarhum</span>
                    @endif
                  </div>

                  <div class="mb-3">
                    <div class="info-label text-danger opacity-75">Nama Lengkap</div>
                    <div class="fw-bold text-dark fs-5">{{ $student->mother_name ?? '-' }}</div>
                  </div>

                  <div class="row g-3">
                    <div class="col-12">
                      <div class="info-label">NIK</div>
                      <div class="info-value font-monospace text-dark">{{ $student->mother_nik ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                      <div class="info-label">Pekerjaan</div>
                      <div class="info-value">
                        {{ $student->mother_occupation ?? '-' }}
                        @if ($student->mother_occupation_detail)
                        <i class="bi bi-info-circle-fill text-danger ms-1 btn-detail-occupation" style="cursor: pointer;" data-title="Detail Pekerjaan Ibu" data-detail="{{ $student->mother_occupation_detail }}"></i>
                        @endif
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="info-label">Pendidikan</div>
                      <div class="info-value">{{ $student->mother_education ?? '-' }}</div>
                    </div>
                    <div class="col-12 pt-2 border-top border-danger border-opacity-10">
                      <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-whatsapp me-2 text-success fs-5"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->mother_phone) }}" target="_blank">
                          <span class="fw-medium">
                            @if($student->mother_phone)
                            {{ preg_replace('/(\d{2})(\d{3})(\d{4})(\d+)/', '$1 $2-$3-$4', $student->mother_phone) }}
                            @else
                            -
                            @endif
                          </span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          {{-- Data Wali (Collapsible) --}}
          <div class="mt-4">
            <a class="btn btn-outline-secondary border-opacity-25 w-100 text-start fw-semibold d-flex justify-content-between align-items-center p-3 rounded-3 d-print-none" data-bs-toggle="collapse" href="#collapseGuardian" role="button" aria-expanded="false" aria-controls="collapseGuardian">
              <span>
                <i class="bi bi-person-badge me-2 text-warning"></i>
                Data Wali (Opsional)
              </span>
              <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="collapseGuardian">
              <div class="p-4 mt-2 bg-light bg-opacity-50 rounded-3 border border-light-subtle">
                @if ($student->guardian_name)
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="info-label">Nama Wali</div>
                    <div class="info-value">{{ $student->guardian_name }}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value text-success">
                      @if ($student->guardian_phone)
                      <i class="bi bi-whatsapp me-2 text-success fs-5"></i>
                      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->guardian_phone) }}" target="_blank">
                        <span class="fw-medium">
                          @if($student->guardian_phone)
                          {{ preg_replace('/(\d{2})(\d{3})(\d{4})(\d+)/', '$1 $2-$3-$4', $student->guardian_phone) }}
                          @else
                          -
                          @endif
                        </span>
                      </a>
                      @else
                      -
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-label">Pekerjaan</div>
                    <div class="info-value">
                      {{ $student->guardian_occupation ?? '-' }}
                      @if ($student->guardian_occupation_detail)
                      <a href="javascript:void(0)" class="text-primary ms-1 text-decoration-none btn-detail-occupation" data-title="Detail Pekerjaan Wali" data-detail="{{ $student->guardian_occupation_detail }}" title="Lihat Detail">
                        <i class="bi bi-info-circle-fill"></i>
                      </a>
                      @endif
                    </div>
                  </div>
                </div>
                @else
                <p class="text-muted text-center mb-0 small fst-italic">Tidak ada data wali yang diisikan.</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-section">
        <div class="card-body p-4">
          <h6 class="fw-bold text-dark border-bottom pb-3 mb-4 d-flex align-items-center">
            <div class="icon-box bg-danger bg-opacity-10 text-danger me-2">
              <i class="bi bi-geo-alt-fill"></i>
            </div>
            Alamat Domisili
          </h6>

          <div class="row">
            <div class="col-12">
              <p class="mb-1 text-dark fw-medium">
                {{ $student->village ? 'Desa ' . $student->village . ',' : '' }}
                {{ $student->district ? 'Kec. ' . $student->district . ',' : '' }}
              </p>
              <p class="mb-0 text-muted">
                {{ $student->regency ? $student->regency . ',' : '' }}
                {{ $student->province ?? '' }}
              </p>
              @if (!$student->village && !$student->regency)
              <span class="text-muted fst-italic">Data alamat belum dilengkapi.</span>
              @endif
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-body p-4 text-center">
        <div class="mb-3 text-danger display-1">
          <i class="bi bi-exclamation-circle"></i>
        </div>
        <h5 class="fw-bold mb-2">Hapus Data Santri?</h5>
        <p class="text-muted">Data <strong>{{ $student->name }}</strong> akan dihapus permanen.</p>
        <div class="d-flex justify-content-center gap-2 mt-4">
          <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
          <form action="{{ route('students.destroy', $student->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal pindah kamar --}}
<div class="modal fade" id="moveRoomModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Mutasi / Pindah Kamar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('students.moveRoom', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="alert alert-info small mb-3">
            <i class="bi bi-info-circle me-1"></i> Saat ini santri berada di:
            <strong>{{ $student->room ?? 'Belum ada kamar' }}</strong>
            ({{ $student->dormitory ?? '-' }})
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Kamar Tujuan</label>
            <select name="new_room_id" class="form-select" required>
              <option value="">-- Pilih Kamar Baru --</option>
              @foreach ($all_rooms as $room)
              <option value="{{ $room->id }}">
                {{ $room->dorm->name ?? 'Asrama' }} - {{ $room->name }} (Sisa:
                {{ $room->capacity - ($room->assignments_count ?? 0) }})
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Pindah</label>
            <input type="date" name="move_date" class="form-control" value="{{ date('Y-m-d') }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Alasan Pindah</label>
            <textarea name="reason" class="form-control" rows="2" placeholder="Contoh: Kenaikan Kelas, Rotasi Semester, Hukuman..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary px-4">Proses Pindah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalMutasi" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0">
      <div class="modal-header bg-warning bg-opacity-10">
        <h5 class="modal-title fw-bold text-dark">Proses Mutasi Santri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('students.exit.store', $student->id) }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="alert alert-info border-0 d-flex align-items-center mb-3">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
            <div>
              <small>Tindakan ini akan menonaktifkan santri <strong>{{ $student->name }}</strong> dari sistem absensi
                dan kelas aktif.</small>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Jenis Mutasi</label>
            <select name="status" id="statusSelect" class="form-select" required>
              <option value="">-- Pilih Status Baru --</option>
              <option value="graduated">Lulus (Graduated)</option>
              <option value="moved">Pindah Sekolah (Moved)</option>
              <option value="suspended">Diberhentikan (Suspended)</option>
              <option value="deceased">Meninggal Dunia</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Tanggal Efektif</label>
            <input type="date" name="exit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Nomor SK / Surat Pindah / Ijazah</label>
            <input type="text" name="sk_number" class="form-control" placeholder="Opsional">
          </div>

          <div class="mb-3 d-none" id="destinationField">
            <label class="form-label fw-bold small text-muted">Sekolah / Pondok Tujuan</label>
            <input type="text" name="destination" class="form-control" placeholder="Nama sekolah baru...">
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Alasan / Catatan</label>
            <textarea name="reason" class="form-control" rows="2" placeholder="Contoh: Mengikuti orang tua pindah tugas..."></textarea>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-dark px-4">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Script Sederhana untuk Show/Hide Sekolah Tujuan
  document.getElementById('statusSelect').addEventListener('change', function() {
    var destField = document.getElementById('destinationField');
    if (this.value === 'moved') {
      destField.classList.remove('d-none');
    } else {
      destField.classList.add('d-none');
    }
  });

</script>
@endsection

@push('scripts')
{{-- sweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Notifikasi Sukses
  @if(session('success'))
  Swal.fire({
    icon: 'success'
    , title: 'Berhasil'
    , text: '{{ session('
    success ') }}'
    , timer: 2000
    , showConfirmButton: false
  });
  @elseif(session('error'))
  Swal.fire({
    icon: 'error'
    , title: 'Gagal'
    , html: '{{ session('
    error ') }}'
    , showConfirmButton: true
  });
  @endif

  // Event listener untuk tombol detail pekerjaan
  document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-detail-occupation')) {
      const btn = e.target.closest('.btn-detail-occupation');
      Swal.fire({
        title: btn.getAttribute('data-title')
        , text: btn.getAttribute('data-detail')
        , icon: 'info'
        , confirmButtonColor: '#3085d6'
        , confirmButtonText: 'Tutup'
      });
    }
  });

</script>
@endpush
