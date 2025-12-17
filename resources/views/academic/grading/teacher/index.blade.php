@extends('layouts.app')
@section('title', 'Input Nilai Akademik')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-4">Input Nilai Akademik</h4>

    <div class="row g-4">
      @foreach ($courses as $course)
        <div class="col-md-4">
          <div class="card h-100 border-0 shadow-sm rounded-4">
            <div class="card-body">
              <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $course->subject->code }}</span>
                <small class="text-muted">KKM: {{ $course->kkm }}</small>
              </div>
              <h5 class="fw-bold">{{ $course->subject->name }}</h5>
              <p class="text-muted mb-3">{{ $course->classroom->name }} ({{ $course->classroom->academicYear->name }})</p>

              <div class="d-grid">
                <a href="{{ route('grading.teacher.show', $course->id) }}" class="btn btn-outline-primary rounded-pill">
                  <i class="bi bi-pencil-square me-2"></i>Input Nilai
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
