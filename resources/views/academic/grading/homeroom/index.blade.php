@extends('layouts.app')
@section('title', 'Leger & Rapor Wali Kelas')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="mb-4">
      <h4 class="fw-bold mb-1">Dashboard Wali Kelas</h4>
      <p class="text-muted small">Kelola rapor siswa perwalian Anda.</p>
    </div>

    <div class="d-flex align-items-center mb-3">
      <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
        style="width:32px;height:32px;">
        <i class="bi bi-star-fill small"></i>
      </div>
      <div>
        <h6 class="fw-bold text-primary mb-0">Kelas Aktif Saat Ini</h6>
        <small class="text-muted">{{ $activeYear->name ?? '-' }} ({{ $activeYear->semester ?? '-' }})</small>
      </div>
    </div>

    @if ($activeClasses->isEmpty())
      <div class="alert alert-warning border-0 shadow-sm mb-5">
        <i class="bi bi-exclamation-circle me-2"></i> Anda tidak memiliki kelas aktif semester ini.
      </div>
    @else
      <div class="accordion shadow-sm rounded-4 overflow-hidden mb-5" id="accordionActive">
        @foreach ($activeClasses as $levelName => $classes)
          <div class="accordion-item border-0 border-bottom">
            <h2 class="accordion-header" id="headingActive{{ $loop->index }}">
              <button class="accordion-button py-3 bg-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseActive{{ $loop->index }}" aria-expanded="true">
                <div class="d-flex align-items-center w-100">
                  <span class="fw-bold text-dark fs-5 me-3">{{ $levelName }}</span>
                  <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                    {{ $classes->count() }} Rombel
                  </span>
                </div>
              </button>
            </h2>
            <div id="collapseActive{{ $loop->index }}" class="accordion-collapse collapse show"
              data-bs-parent="#accordionActive">
              <div class="accordion-body bg-light p-3">
                <div class="row g-3">
                  @foreach ($classes as $class)
                    <div class="col-md-4 col-lg-3">
                      <a href="{{ route('grading.homeroom.show', $class->id) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-card">
                          <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                              <h6 class="fw-bold text-dark mb-1">{{ $class->name }}</h6>
                              <small class="text-muted"><i class="bi bi-people me-1"></i> {{ $class->students_count }}
                                Siswa</small>
                            </div>
                            <div class="btn btn-sm btn-primary rounded-circle">
                              <i class="bi bi-chevron-right"></i>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif


    @if ($historyClasses->isNotEmpty())
      <div class="d-flex align-items-center mb-3 mt-5">
        <div
          class="avatar-sm bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
          style="width:32px;height:32px;">
          <i class="bi bi-archive-fill small"></i>
        </div>
        <h6 class="fw-bold text-secondary mb-0">Arsip / Riwayat Kelas</h6>
      </div>

      <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionHistory">
        @foreach ($historyClasses as $yearGroup => $classes)
          <div class="accordion-item border-0 border-bottom">
            <h2 class="accordion-header" id="headingHist{{ $loop->index }}">
              <button class="accordion-button collapsed py-3 bg-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseHist{{ $loop->index }}">
                <div class="d-flex align-items-center w-100">
                  <i class="bi bi-calendar-range text-muted me-3"></i>
                  <span class="fw-bold text-secondary me-3">{{ $yearGroup }}</span>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">
                    {{ $classes->count() }} Kelas
                  </span>
                </div>
              </button>
            </h2>
            <div id="collapseHist{{ $loop->index }}" class="accordion-collapse collapse"
              data-bs-parent="#accordionHistory">
              <div class="accordion-body p-0">
                <div class="list-group list-group-flush">
                  @foreach ($classes as $class)
                    <a href="{{ route('grading.homeroom.show', $class->id) }}"
                      class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-4 py-3">
                      <div>
                        <span class="fw-bold text-dark">{{ $class->name }}</span>
                        <small class="text-muted ms-2">({{ $class->level->name }})</small>
                      </div>
                      <div class="d-flex align-items-center text-muted small">
                        <span class="me-3">{{ $class->students_count }} Siswa</span>
                        <i class="bi bi-box-arrow-up-right"></i>
                      </div>
                    </a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <style>
    /* Efek hover untuk card aktif */
    .hover-card {
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .hover-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }
  </style>
@endsection
@push('scripts')
@endpush
