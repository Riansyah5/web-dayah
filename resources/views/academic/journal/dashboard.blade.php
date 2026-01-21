@extends('layouts.app')
@section('title', 'Dashboard Jurnal Guru')
@push('link')
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h4 class="fw-bold">Halo, {{ Auth::user()->name }}!</h4>
        <p class="text-muted">Jadwal Mengajar Hari Ini: {{ $today->translatedFormat('l, d F Y') }}</p>
    </div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calendar-check me-2"></i> Jadwal Utama Anda</h6>
    <div class="row g-3 mb-5">
        @forelse($regularSchedules as $item)
            <div class="col-md-4">
                @include('academic.journal.partials.schedule-card', ['schedule' => $item, 'isBadal' => false])
            </div>
        @empty
            <div class="col-12 text-muted">Tidak ada jadwal reguler hari ini.</div>
        @endforelse
    </div>

    @if($substituteSchedules->count() > 0)
        <h6 class="fw-bold text-warning mb-3"><i class="bi bi-person-badge me-2"></i> Jadwal Pengganti (Badal)</h6>
        <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
            <div>Anda ditugaskan menggantikan guru lain hari ini. Mohon isi jurnal seperti biasa.</div>
        </div>
        <div class="row g-3">
            @foreach($substituteSchedules as $sub)
                <div class="col-md-4">
                    {{-- Passing lessonSchedule dari relasi badal --}}
                    @include('academic.journal.partials.schedule-card', ['schedule' => $sub->lessonSchedule, 'isBadal' => true, 'note' => $sub->note])
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
@push('scripts')
@endpush
