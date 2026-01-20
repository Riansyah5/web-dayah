@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Jadwal Pelajaran</h4>
        <p class="text-muted small mb-0">Atur roster mingguan per kelas (Tahun: {{ $activeYear->name }})</p>
      </div>
    </div>

    <div class="row g-3">
      @foreach ($classrooms as $class)
        <div class="col-md-3">
          <a href="{{ route('academic.schedule.show', $class->id) }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm hover-card text-center">
              <div class="card-body p-4">
                <div
                  class="avatar-md bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px;">
                  {{ substr($class->name, 0, 2) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $class->name }}</h5>
                <small class="text-muted">{{ $class->lesson_schedules_count }} Mapel Terjadwal</small>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
@endsection
@push('scripts')
@endpush
