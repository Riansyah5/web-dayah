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
      <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary text-mute shadow-sm rounded-3">
          <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
      </div>
    </div>

    <div class="card profile-header rounded-4 mb-4 shadow-sm position-relative overflow-hidden">
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
              <span
                class="badge {{ $student->gender == 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
                {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
              </span>
            </div>
            <div class="text-muted small">
              <i class="bi bi-calendar-check me-1"></i> Terdaftar sejak:
              {{ $student->created_at->translatedFormat('d F Y') }}
            </div>
          </div>
          <div class="col-md-auto mt-3 mt-md-3 d-flex flex-wrap gap-2 justify-content-center">
            {{-- tombol riwayat rapor --}}
            <a href="{{ route('student.history', $student->id) }}"
              class="btn btn-success text-white rounded-3 shadow-sm px-3 ms-2">
              <i class="bi bi-journal-text me-2"></i>Rapor
            </a>
            {{-- tombol riwayat pelanggaran --}}
            <a href="{{ route('violations.index', $student->id) }}"
              class="btn btn-danger text-white rounded-3 shadow-sm px-3">
              <i class="bi bi-journal-text me-2"></i>Pelanggaran
            </a>
            {{-- tombol riwayat izin --}}
            <a href="{{ route('students.permissions', $student->id) }}"
              class="btn btn-info text-white rounded-3 shadow-sm px-3">
              <i class="bi bi-journal-text me-2"></i>Riwayat Izin
            </a>
            {{-- tombol pindah kamar --}}
            <button class="btn btn-primary text-white rounded-3 shadow-sm px-3" data-bs-toggle="modal"
              data-bs-target="#moveRoomModal">
              <i class="bi bi-arrow-left-right me-2"></i>Pindah Kamar
            </button>
            {{-- tombol edit data --}}
            <a href="{{ route('students.edit', $student->id) }}"
              class="btn btn-warning text-white rounded-3 shadow-sm px-4">
              <i class="bi bi-pencil-square me-2"></i>Edit
            </a>
            <button class="btn btn-outline-danger rounded-3 shadow-sm" data-bs-toggle="modal"
              data-bs-target="#deleteModal">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="card card-section mb-4">
          <div class="card-body">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
              <i class="bi bi-info-circle me-2 text-primary"></i>Status Akademik
            </h6>

            <div class="mb-3">
              <div class="info-label">Status Santri</div>
              @php
                $statusClass = match ($student->status) {
                    'active' => 'success',
                    'graduated' => 'primary',
                    'suspended' => 'danger',
                    default => 'secondary',
                };
                $statusLabel = match ($student->status) {
                    'active' => 'Aktif',
                    'graduated' => 'Lulus',
                    'suspended' => 'Skorsing',
                    'moved' => 'Pindah',
                    default => ucfirst($student->status),
                };
              @endphp
              <span class="badge bg-{{ $statusClass }} fs-6 rounded-pill px-3">
                {{ $statusLabel }}
              </span>
            </div>

            <div class="row g-3">
              <div class="col-6">
                <div class="info-label">Jenjang</div>
                <div class="info-value">{{ $student->education_level ?? '-' }}</div>
              </div>
              <div class="col-6">
                <div class="info-label">Kelas</div>
                <div class="info-value">{{ $student->class_group ?? '-' }}</div>
              </div>
              <div class="col-12">
                <div class="info-label">Jurusan</div>
                <div class="info-value">{{ $student->major ?? '-' }}</div>
              </div>
              <div class="col-12">
                <div class="info-label">Asal Sekolah</div>
                <div class="info-value">{{ $student->previous_school ?? '-' }}</div>
              </div>
              <div class="col-12">
                <div class="info-label">Tanggal Masuk</div>
                <div class="info-value">
                  {{ $student->acceptance_date ? $student->acceptance_date->translatedFormat('d F Y') : '-' }}
                </div>
              </div>
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
                      <span
                        class="badge {{ $history->is_active ? 'bg-success' : 'bg-light text-muted border' }} rounded-pill"
                        style="font-size: 0.7rem;">
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
                          <small class="text-muted"><i
                              class="bi bi-check-circle-fill text-success me-1"></i>Selesai</small>
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
                        <div
                          class="avatar-xs bg-white text-muted border rounded-circle me-2 d-flex justify-content-center align-items-center"
                          style="width:24px; height:24px; font-size:10px;">
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

            <div class="row g-4">
              <div class="col-md-6">
                <div class="info-label">Tempat, Tanggal Lahir</div>
                <div class="info-value">
                  {{ $student->birth_place }}, {{ $student->birth_date->translatedFormat('d F Y') }}
                  <span class="text-muted small">({{ $student->birth_date->age }} Tahun)</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-label">Anak Ke-</div>
                <div class="info-value">{{ $student->child_order ? $student->child_order : '-' }}</div>
              </div>
              <div class="col-md-6">
                <div class="info-label">NIK (Nomor Induk Kependudukan)</div>
                <div class="info-value font-monospace">{{ $student->nik ?? '-' }}</div>
              </div>
              <div class="col-md-6">
                <div class="info-label">Nomor Kartu Keluarga</div>
                <div class="info-value font-monospace">{{ $student->family_card_number ?? '-' }}</div>
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
                <div class="p-3 bg-light rounded-3 h-100">
                  <h6 class="fw-bold text-muted mb-3"><i class="bi bi-gender-male me-1"></i> Ayah</h6>
                  <div class="mb-2">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $student->father_name ?? '-' }}</div>
                  </div>
                  <div class="mb-2">
                    <div class="info-label">Pekerjaan</div>
                    <div class="info-value">{{ $student->father_occupation ?? '-' }}</div>
                  </div>
                  <div>
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value text-success">
                      @if ($student->father_phone)
                        <i class="bi bi-whatsapp me-1"></i> {{ $student->father_phone }}
                      @else
                        -
                      @endif
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100">
                  <h6 class="fw-bold text-muted mb-3"><i class="bi bi-gender-female me-1"></i> Ibu</h6>
                  <div class="mb-2">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $student->mother_name ?? '-' }}</div>
                  </div>
                  <div class="mb-2">
                    <div class="info-label">Pekerjaan</div>
                    <div class="info-value">{{ $student->mother_occupation ?? '-' }}</div>
                  </div>
                  <div>
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value text-success">
                      @if ($student->mother_phone)
                        <i class="bi bi-whatsapp me-1"></i> {{ $student->mother_phone }}
                      @else
                        -
                      @endif
                    </div>
                  </div>
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
              <textarea name="reason" class="form-control" rows="2"
                placeholder="Contoh: Kenaikan Kelas, Rotasi Semester, Hukuman..."></textarea>
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

@endsection

@push('scripts')
@endpush
