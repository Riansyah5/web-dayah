@extends('layouts.app')
@section('title', 'Leger & Rapor Wali Kelas')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-4">Leger & Rapor Wali Kelas</h4>
    <div class="row g-4">
      @foreach ($classrooms as $classroom)
        <div class="col-md-4">
          <div class="card h-100 border-0 shadow-sm rounded-4">
            <div class="card-body">
              <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $classroom->level->name ?? '-' }}</span>
                <small class="text-muted">{{ $classroom->academicYear->name ?? '-' }}</small>
              </div>
              <h5 class="fw-bold">{{ $classroom->name }}</h5>
              <p class="text-muted mb-3">{{ $classroom->major->name ?? 'Umum' }}</p>
              <div class="d-grid">
                <a href="{{ route('grading.homeroom.show', $classroom->id) }}"
                  class="btn btn-outline-primary rounded-pill">
                  <i class="bi bi-journal-text me-2"></i>Buka Leger
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
@push('scripts')
@endpush
