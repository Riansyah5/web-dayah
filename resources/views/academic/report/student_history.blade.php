@extends('layouts.app')
@section('title', 'Jejak Studi Santri - ' . $student->name)

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background-color: #f8fafc;
  }

  .card-modern {
    border: none;
    border-radius: 18px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
  }

  /* Academic Banner */
  .academic-banner {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
  }

  .banner-icon-bg {
    position: absolute;
    right: -10px;
    top: -20px;
    font-size: 8rem;
    color: rgba(255, 255, 255, 0.08);
    pointer-events: none;
  }

  .avatar-circle {
    width: 68px;
    height: 68px;
    font-size: 1.6rem;
    font-weight: 800;
    background: #ffffff;
    color: #2563eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    flex-shrink: 0;
  }

  /* Timeline & Cards */
  .timeline-card {
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    background: #ffffff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .timeline-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
  }

  .score-badge-box {
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    padding: 0.5rem 0.85rem;
    text-align: center;
    min-width: 75px;
  }

  .attendance-pill-box {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 0.75rem;
  }

  .stat-label {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #64748b;
    text-transform: uppercase;
  }

  .stat-number {
    font-size: 1.05rem;
    font-weight: 800;
    line-height: 1.2;
  }

  /* Mobile Tweaks */
  @media (max-width: 575.98px) {
    .nav-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.5rem;
      width: 100%;
    }

    .nav-actions .btn-back {
      grid-column: span 2;
    }

    .avatar-circle {
      width: 54px;
      height: 54px;
      font-size: 1.3rem;
    }

    .banner-title {
      font-size: 1.25rem;
    }

    .timeline-action-btns {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.4rem;
      width: 100%;
    }

    .timeline-action-btns .btn-edit {
      grid-column: span 2;
    }
  }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">

  {{-- Top Navigation & Actions --}}
  <div class="nav-actions d-flex flex-wrap align-items-center gap-2 mb-3 mb-md-4">
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-secondary btn-back rounded-3 shadow-sm py-2 px-3">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <a href="{{ route('student.biodata.show', $student->id) }}" target="_blank"
       class="btn btn-white border rounded-3 shadow-sm fw-semibold text-secondary py-2 px-3">
      <i class="bi bi-eye text-primary me-1"></i> Biodata
    </a>
    <a href="{{ route('student.biodata.print', $student->id) }}" target="_blank"
       class="btn btn-white border rounded-3 shadow-sm fw-semibold text-secondary py-2 px-3">
      <i class="bi bi-printer text-success me-1"></i> Cetak
    </a>
  </div>

  {{-- Student Banner --}}
  <div class="academic-banner text-white shadow-sm mb-4">
    <div class="banner-icon-bg">
      <i class="bi bi-mortarboard-fill"></i>
    </div>
    <div class="p-3 p-md-4 position-relative">
      <div class="d-flex align-items-center">
        <div class="avatar-circle">
          {{ strtoupper(substr($student->name, 0, 1)) }}
        </div>
        <div class="ms-3 ms-md-4 text-truncate">
          <span class="badge bg-white bg-opacity-25 rounded-pill px-2 py-1 small mb-1 fw-semibold">
            Portofolio Akademik
          </span>
          <h3 class="fw-bold mb-1 text-white text-truncate banner-title">{{ $student->name }}</h3>
          <p class="mb-0 text-white text-opacity-75 small">
            <i class="bi bi-upc-scan me-1"></i>NIS: <strong>{{ $student->nis ?? '-' }}</strong> &bull; {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 g-md-4">
    {{-- Left Column: Semester History (Timeline) --}}
    <div class="col-lg-8 order-2 order-lg-1">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold text-dark mb-0 ps-2 border-start border-4 border-primary">
          Riwayat Semester
        </h5>
        <span class="badge bg-light text-muted border rounded-pill">{{ $history->count() }} Semester</span>
      </div>

      @if ($history->isEmpty())
        <div class="card card-modern border-0 p-4 text-center">
          <div class="text-muted py-4">
            <i class="bi bi-folder-x display-4 d-block mb-2 text-secondary opacity-50"></i>
            <h6 class="fw-semibold text-dark">Belum Ada Riwayat Rapor</h6>
            <p class="small text-muted mb-0">Santri ini belum memiliki rekap nilai atau kelas terdaftar.</p>
          </div>
        </div>
      @else
        <div class="d-flex flex-column gap-3">
          @foreach ($history as $class)
            <div class="card timeline-card shadow-sm">
              <div class="card-body p-3 p-md-4">
                
                {{-- Header Item --}}
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                  <div>
                    <span class="badge {{ $class->academicYear->semester == 'Genap' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }} rounded-pill px-2 py-1 small fw-semibold mb-1">
                      {{ $class->academicYear->name }} &bull; Semester {{ $class->academicYear->semester }}
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">{{ $class->name }}</h5>
                    <small class="text-muted d-block mt-1">
                      {{ $class->level->name ?? '-' }} &bull; Wali Kelas: <strong>{{ $class->homeroom_teacher ?? '-' }}</strong>
                    </small>
                  </div>

                  <div class="score-badge-box flex-shrink-0">
                    <span class="stat-label d-block text-primary">Rata-Rata</span>
                    <span class="stat-number text-primary">{{ number_format($class->average_score ?? 0, 1) }}</span>
                  </div>
                </div>

                {{-- Attendance & Decision Section --}}
                <div class="attendance-pill-box mb-3">
                  <div class="row g-2 align-items-center text-center">
                    <div class="col-4 col-sm-3 border-end">
                      <span class="stat-label d-block">Sakit</span>
                      <span class="stat-number text-dark">{{ $class->report_summary->sick ?? 0 }}</span>
                    </div>
                    <div class="col-4 col-sm-3 border-end">
                      <span class="stat-label d-block">Izin</span>
                      <span class="stat-number text-dark">{{ $class->report_summary->permission ?? 0 }}</span>
                    </div>
                    <div class="col-4 col-sm-3 {{ $class->academicYear->semester == 'Genap' ? 'border-end' : '' }}">
                      <span class="stat-label d-block">Alpa</span>
                      <span class="stat-number text-danger">{{ $class->report_summary->absent ?? 0 }}</span>
                    </div>

                    @if ($class->academicYear->semester == 'Genap')
                      <div class="col-12 col-sm-3 text-center text-sm-end pt-2 pt-sm-0">
                        <span class="stat-label d-block text-success">Keputusan</span>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fw-bold">
                          {{ strtoupper($class->report_summary->status ?? '-') }}
                        </span>
                      </div>
                    @endif
                  </div>
                </div>

                {{-- Notes & Actions Footer --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 pt-2 border-top">
                  <div class="text-truncate w-100 me-2">
                    <small class="text-muted fst-italic text-truncate d-block" title="{{ $class->report_summary->notes ?? '' }}">
                      <i class="bi bi-quote me-1"></i>{{ $class->report_summary->notes ?? 'Tidak ada catatan khusus.' }}
                    </small>
                  </div>

                  <div class="timeline-action-btns d-flex gap-1 flex-shrink-0">
                    <a href="{{ route('student.report.edit', ['student' => $student->id, 'classroom' => $class->id]) }}"
                       class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-pill px-3 btn-edit">
                      <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('grading.homeroom.preview', ['studentId' => $student->id, 'classroomId' => $class->id]) }}"
                       target="_blank" class="btn btn-sm btn-light border text-secondary rounded-pill px-3">
                      <i class="bi bi-eye text-info me-1"></i> Lihat
                    </a>
                    <a href="{{ route('grading.homeroom.print', ['studentId' => $student->id, 'classroomId' => $class->id]) }}"
                       target="_blank" class="btn btn-sm btn-light border text-secondary rounded-pill px-3">
                      <i class="bi bi-printer text-primary me-1"></i> Cetak
                    </a>
                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Right Column: Summary Card --}}
    <div class="col-lg-4 order-1 order-lg-2">
      <div class="card card-modern sticky-top" style="top: 1.5rem; z-index: 10;">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
              <i class="bi bi-graph-up"></i>
            </div>
            <h6 class="fw-bold mb-0 text-dark">Ringkasan Studi</h6>
          </div>

          <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
              <span class="text-muted small">Total Semester</span>
              <span class="fw-bold text-dark">{{ $history->count() }}</span>
            </div>

            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
              <span class="text-muted small">Rata-rata Kumulatif</span>
              <span class="fw-bold text-primary fs-6">
                {{ $history->count() > 0 ? number_format($history->avg('average_score'), 1) : '0.0' }}
              </span>
            </div>

            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
              <span class="text-muted small">Tahun Masuk</span>
              <span class="fw-bold text-dark">{{ $history->last()->academicYear->name ?? '-' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: @json(session('success')),
      timer: 2000,
      showConfirmButton: false
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: @json(session('error')),
    });
  @endif
</script>
@endpush