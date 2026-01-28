@extends('layouts.app')
@section('title', 'Jadwal Halaqah Tahfizh')
@push('link')
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-1">Pengaturan Waktu Halaqah</h4>
            <p class="text-muted small mb-0">Sesuaikan waktu halaqah dengan jadwal shalat bulanan.</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($groupedSchedules as $schedule)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary rounded-pill me-2">{{ $schedule->total_days }} Hari Aktif</span>
                        @if($schedule->session_name == 'Qabla Shubuh' || $schedule->session_name == "Ba'da Shubuh")
                            <i class="bi bi-moon-stars-fill text-muted ms-auto"></i>
                        @else
                            <i class="bi bi-sun-fill text-warning ms-auto"></i>
                        @endif
                    </div>
                    <h5 class="fw-bold text-dark mb-0">{{ $schedule->session_name }}</h5>
                </div>

                <div class="card-body px-4 py-3">
                    <form action="{{ route('tahfizh.admin.schedule.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="session_name" value="{{ $schedule->session_name }}">

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Jam Mulai</label>
                                <input type="time" name="start_time" 
                                       class="form-control form-control-lg fw-bold bg-light" 
                                       value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Jam Selesai</label>
                                <input type="time" name="end_time" 
                                       class="form-control form-control-lg fw-bold bg-light" 
                                       value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary rounded-pill fw-bold">
                                <i class="bi bi-arrow-repeat me-1"></i> Update Waktu
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                    <small class="text-muted fst-italic" style="font-size: 0.75rem;">
                        *Mengubah waktu ini akan berdampak pada jadwal hari Senin s/d Sabtu secara otomatis.
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@push('scripts')
@endpush
