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
    // Ambil Data
    $existingJournal = $journalStatus[$sched->id];
    $isDone = $existingJournal != null;
    
    $permission = $permissionStatus[$sched->id] ?? null; // Data Izin
    $substitute = $substituteStatus[$sched->id] ?? null; // Data Badal
@endphp

<div class="col-md-4">
    @php
        $cardClass = '';
        if ($isDone) $cardClass = 'border-start border-success border-5';
        else if ($permission && $permission->status == 'approved') $cardClass = 'border-start border-warning border-5 bg-warning bg-opacity-10';
        else if ($permission && $permission->status == 'pending') $cardClass = 'border-start border-warning border-5';
    @endphp

    <div class="card border-0 shadow-sm rounded-4 h-100 {{ $cardClass }}">
        <div class="card-body p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">{{ $sched->session_name }}</h5>
                
                @if($isDone)
                    <span class="badge bg-success rounded-pill"><i class="bi bi-check-lg"></i> Selesai</span>
                @elseif($permission)
                    @if($permission->status == 'approved')
                         <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-person-dash"></i> Izin</span>
                    @else
                         <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                    @endif
                @else
                    <span class="badge bg-light text-dark border">Belum Absen</span>
                @endif
            </div>

            <div class="mb-4">
                <div class="d-flex align-items-center text-muted">
                    <i class="bi bi-clock me-2"></i>
                    <span class="fw-bold text-dark">
                        {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}
                    </span>
                </div>

                @if($permission && $permission->status == 'approved')
                    <div class="mt-3 p-2 bg-white rounded border border-warning small">
                        <div class="fw-bold text-warning-emphasis">Status: Izin Disetujui</div>
                        
                        @if($substitute)
                            <div class="mt-1 text-muted">
                                Digantikan oleh:<br>
                                <strong class="text-dark">{{ $substitute->substituteTeacher->name }}</strong>
                            </div>
                        @else
                            <div class="mt-1 text-danger fst-italic">Menunggu Admin menunjuk badal.</div>
                        @endif
                    </div>
                @elseif($permission && $permission->status == 'pending')
                    <div class="mt-3 text-muted small fst-italic">
                        Menunggu persetujuan admin...
                    </div>
                @endif
            </div>

            <div class="d-grid">
                @if($isDone)
                    <a href="{{ route('tahfizh.journal.attendance', $existingJournal->id) }}" class="btn btn-outline-success rounded-pill fw-bold">
                        <i class="bi bi-people-fill me-2"></i> Update Absensi
                    </a>

                @elseif($permission)
                    <button class="btn btn-secondary rounded-pill fw-bold disabled" disabled>
                        <i class="bi bi-lock-fill me-2"></i> Absen Terkunci
                    </button>

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
