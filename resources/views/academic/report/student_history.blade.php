@extends('layouts.app')
@section('title', 'Jejak Studi Santri - ' . $student->name)
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-light border text-muted shadow-sm rounded-3 mb-2">
        <i class="bi bi-arrow-left me-2"></i>Kembali
      </a>
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white overflow-hidden position-relative">
      <div class="position-absolute top-0 end-0 p-3 opacity-25">
        <i class="bi bi-mortarboard-fill display-1"></i>
      </div>
      <div class="card-body p-4 position-relative">
        <div class="d-flex align-items-center">
          <div
            class="avatar-lg bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
            style="width: 80px; height: 80px; font-size: 2rem;">
            {{ substr($student->name, 0, 1) }}
          </div>
          <div class="ms-4">
            <h6 class="text-white-50 text-uppercase letter-spacing-1 mb-1">Portofolio Akademik</h6>
            <h2 class="fw-bold mb-1">{{ $student->name }}</h2>
            <p class="mb-0 opacity-75"><i class="bi bi-upc-scan me-2"></i>NIS: {{ $student->nis }}</p>
          </div>
        </div>
      </div>
    </div>

    <h5 class="fw-bold text-secondary mb-4 ps-2 border-start border-4 border-primary">Jejak Studi</h5>

    <div class="row">
      <div class="col-lg-8">
        @if ($history->isEmpty())
          <div class="alert alert-info rounded-4 border-0 shadow-sm">
            <i class="bi bi-info-circle me-2"></i> Belum ada riwayat rapor untuk santri ini.
          </div>
        @else
          <div class="timeline">
            @foreach ($history as $class)
              <div class="card border-0 shadow-sm rounded-4 mb-4 timeline-card hover-lift">
                <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                      <span
                        class="badge {{ $class->academicYear->semester == 'Genap' ? 'bg-success' : 'bg-info' }} rounded-pill mb-2">
                        {{ $class->academicYear->name }} - {{ $class->academicYear->semester }}
                      </span>
                      <h4 class="fw-bold mb-0 text-dark">{{ $class->name }}</h4>
                      <small class="text-muted">{{ $class->level->name }} &bull; Wali Kelas:
                        {{ $class->homeroom_teacher ?? '-' }}</small>
                    </div>

                    <div class="text-center">
                      <h3 class="fw-bold text-primary mb-0">{{ number_format($class->average_score, 1) }}</h3>
                      <small class="text-muted" style="font-size: 0.7rem;">RATA-RATA</small>
                    </div>
                  </div>

                  <div class="bg-light rounded-3 p-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-4 text-center">
                      <div>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">SAKIT</small>
                        <span class="fw-bold">{{ $class->report_summary->sick ?? 0 }}</span>
                      </div>
                      <div>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">IZIN</small>
                        <span class="fw-bold">{{ $class->report_summary->permission ?? 0 }}</span>
                      </div>
                      <div>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">ALPA</small>
                        <span class="fw-bold text-danger">{{ $class->report_summary->absent ?? 0 }}</span>
                      </div>
                    </div>

                    @if ($class->academicYear->semester == 'Genap')
                      <div class="text-end">
                        <small class="text-muted d-block" style="font-size: 0.7rem;">KEPUTUSAN</small>
                        <span class="fw-bold text-success">{{ strtoupper($class->report_summary->status ?? '-') }}</span>
                      </div>
                    @endif
                  </div>

                  <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted fst-italic text-truncate" style="max-width: 300px;">
                      "{{ $class->report_summary->notes ?? 'Tidak ada catatan khusus.' }}"
                    </small>

                    {{-- Link ke Route Print PDF yang sudah kita buat sebelumnya --}}
                    <div class="d-flex gap-2">
                      <a href="{{ route('grading.homeroom.preview', ['studentId' => $student->id, 'classroomId' => $class->id]) }}"
                        target="_blank" class="btn btn-outline-info rounded-pill btn-sm px-3">
                        <i class="bi bi-eye me-2"></i> Lihat
                      </a>
                      <a href="{{ route('grading.homeroom.print', ['studentId' => $student->id, 'classroomId' => $class->id]) }}"
                        target="_blank" class="btn btn-outline-primary rounded-pill btn-sm px-3">
                        <i class="bi bi-printer me-2"></i> Cetak
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Ringkasan</h6>
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between px-0">
                <span>Total Semester</span>
                <span class="fw-bold">{{ $history->count() }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between px-0">
                <span>Rata-rata Total</span>
                <span class="fw-bold text-primary">
                  {{ $history->count() > 0 ? number_format($history->avg('average_score'), 1) : 0 }}
                </span>
              </li>
              <li class="list-group-item d-flex justify-content-between px-0">
                <span>Tahun Masuk</span>
                <span class="fw-bold">{{ $history->last()->academicYear->name ?? '-' }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .timeline {
      position: relative;
    }

    /* Garis vertikal timeline */
    .timeline::before {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      left: 20px;
      width: 2px;
      background: #e9ecef;
      z-index: 0;
    }

    .timeline-card {
      margin-left: 0;
      /* Bisa disesuaikan kalau mau indent */
      transition: transform 0.2s;
      z-index: 1;
    }

    .hover-lift:hover {
      transform: translateY(-5px);
    }
  </style>
@endsection
@push('scripts')
@endpush
