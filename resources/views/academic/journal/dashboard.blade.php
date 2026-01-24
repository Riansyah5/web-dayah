@extends('layouts.app')
@section('title', 'Dashboard Jurnal Guru')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <h4 class="fw-bold">Halo, {{ Auth::user()->name }}!</h4>
        <p class="text-muted mb-0">Jadwal Mengajar: {{ $today->translatedFormat('l, d F Y') }}</p>
      </div>

      <a href="{{ route('academic.permission.create') }}" class="btn btn-outline-danger rounded-pill shadow-sm">
        <i class="bi bi-envelope-x me-2"></i> Berhalangan
      </a>
    </div>

    @php
      // Cek permission hari ini (bisa dipass dari controller atau query langsung di view composer)
      use App\Models\Teacher;
      $teacher = Teacher::where('name', Auth::user()->name)->first();
      $todayPermission = \App\Models\TeacherPermission::where('teacher_id', $teacher->id)
          ->where('date', date('Y-m-d'))
          ->where('status', 'approved')
          ->first();
    @endphp

    @if ($todayPermission)
      <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
        <i class="bi bi-exclamation-circle-fill fs-1 me-3"></i>
        <div>
          <h5 class="fw-bold mb-1">Anda Sedang Izin ({{ ucfirst($todayPermission->type) }})</h5>
          <p class="mb-0">Jadwal Anda hari ini telah dinonaktifkan dan akan dialihkan ke guru pengganti.</p>
        </div>
      </div>
    @else
    @endif

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

    @if ($substituteSchedules->count() > 0)
      <h6 class="fw-bold text-warning mb-3"><i class="bi bi-person-badge me-2"></i> Jadwal Pengganti (Badal)</h6>
      <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center">
        <i class="bi bi-info-circle-fill me-3 fs-4"></i>
        <div>Anda ditugaskan menggantikan guru lain hari ini. Mohon isi jurnal seperti biasa.</div>
      </div>
      <div class="row g-3">
        @foreach ($substituteSchedules as $sub)
          <div class="col-md-4">
            {{-- Passing lessonSchedule dari relasi badal --}}
            @include('academic.journal.partials.schedule-card', [
                'schedule' => $sub->lessonSchedule,
                'isBadal' => true,
                'note' => $sub->note,
            ])
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Contoh penggunaan SweetAlert2 untuk notifikasi
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '{{ session('success') }}',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
      });
    @endif

    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
      });
    @endif
  </script>
@endpush
