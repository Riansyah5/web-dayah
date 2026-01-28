@extends('layouts.app')
@section('title', 'Dashboard Jurnal Tahfizh')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="mb-4">
    <h4 class="fw-bold mb-0">Halaqah Tahfizh</h4>
    <p class="text-muted mb-0">
      {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
    </p>
    <div class="badge bg-primary mt-2">
      <i class="bi bi-people-fill me-1"></i> Kelompok: {{ $halaqah->name }}
    </div>
  </div>

  <div class="row g-4">
    @forelse($schedules as $sched)
    @php
    // Cek status apakah sudah absen
    $existingJournal = $journalStatus[$sched->id];
    $isDone = $existingJournal != null;

    // Cek waktu (Opsional: Memberi warna abu-abu jika belum waktunya)
    $now = \Carbon\Carbon::now()->format('H:i:s');
    $isTime = $now >= $sched->start_time;
    @endphp

    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 h-100 {{ $isDone ? 'border-start border-success border-5' : '' }}">
        <div class="card-body p-4">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">{{ $sched->session_name }}</h5>
            @if($isDone)
            <span class="badge bg-success rounded-pill"><i class="bi bi-check-lg"></i> Selesai</span>
            @else
            <span class="badge bg-light text-dark border">Belum Absen</span>
            @endif
          </div>

          <div class="mb-4">
            <div class="d-flex align-items-center text-muted">
              <i class="bi bi-clock me-2"></i>
              <span class="fw-bold text-dark">
                {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}
              </span>
            </div>
            @if($isDone)
            <small class="text-success fst-italic mt-1 d-block">
              Absen masuk: {{ \Carbon\Carbon::parse($existingJournal->clock_in)->format('H:i') }}
            </small>
            @endif
          </div>

          <div class="d-grid">
            @if($isDone)
            <a href="{{ route('tahfizh.journal.attendance', $existingJournal->id) }}" class="btn btn-outline-success rounded-pill fw-bold">
              <i class="bi bi-people-fill me-2"></i> Update Absensi Santri
            </a>
            <small class="text-center text-muted mt-2" style="font-size: 0.7rem;">
              Klik tombol di atas jika ada santri terlambat.
            </small>
            @else
            <a href="{{ route('tahfizh.journal.open', $sched->id) }}" class="btn btn-primary rounded-pill fw-bold">
              <i class="bi bi-camera me-2"></i> Buka Halaqah
            </a>
            @endif
          </div>

        </div>
      </div>
    </div>
    @empty
    <div class="col-12">
      <div class="alert alert-info text-center rounded-4">
        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
        Tidak ada jadwal halaqah hari ini (Libur).
      </div>
    </div>
    @endforelse
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: "{{ session('success') }}",
      timer: 2000,
      showConfirmButton: false
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: "{{ session('error') }}",
    });
  @endif
</script>
@endpush
