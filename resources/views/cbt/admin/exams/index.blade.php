@extends('layouts.app')
@section('title', 'Jadwal & Token Ujian')
@push('link')
@endpush
@push('styles')
<style>
  @keyframes blink {
    0% {
      opacity: 1;
    }

    50% {
      opacity: 0.5;
    }

    100% {
      opacity: 1;
    }
  }

  .animate-blink {
    animation: blink 1.5s infinite;
  }

</style>
@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1"><i class="bi bi-calendar-event text-primary me-2"></i>Jadwal & Token Ujian</h4>
      <p class="text-muted small mb-0">Atur jadwal pelaksanaan ujian dan kelola token keamanan.</p>
    </div>
    <a href="{{ route('admin.cbt.exams.create') }}" class="btn btn-primary rounded-pill shadow-sm">
      <i class="bi bi-plus-lg me-1"></i> Buat Jadwal Ujian
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th class="ps-4">Nama Ujian</th>
            <th>Waktu Pelaksanaan</th>
            <th>Durasi</th>
            <th class="text-center">Token (Live)</th>
            <th>Status</th>
            <th class="text-end pe-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($exams as $exam)
          @php
          $now = \Carbon\Carbon::now();
          $isOngoing = $now->between($exam->start_time, $exam->end_time);
          $isFinished = $now->greaterThan($exam->end_time);
          @endphp
          <tr>
            <td class="ps-4">
              <div class="fw-bold">{{ $exam->name }}</div>
              <div class="text-muted small" style="font-size: 11px;">
                Bank Soal: {{ $exam->questionBank->subject_name }} ({{ $exam->questionBank->level }})
              </div>
            </td>
            <td>
              <div class="small fw-bold">{{ $exam->start_time->translatedFormat('d M Y') }}</div>
              <div class="small text-muted">{{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }} WIB</div>
            </td>
            <td>{{ $exam->duration }} Menit</td>
            <td class="text-center">
              @if($isFinished)
              <span class="badge bg-secondary">KEDALUWARSA</span>
              @else
              <div class="d-inline-flex align-items-center border border-2 border-primary rounded px-3 py-1 bg-primary bg-opacity-10 text-primary fw-bold fs-5 font-monospace">
                {{ $exam->token }}
              </div>
              <form action="{{ route('admin.cbt.exams.refresh_token', $exam->id) }}" method="POST" class="d-inline ms-1">
                @csrf
                <button type="button" class="btn btn-sm btn-link text-warning p-0" title="Refresh Token (Acak Ulang)" onclick="confirmRefresh(this.closest('form'))">
                  <i class="bi bi-arrow-clockwise fs-5"></i>
                </button>
              </form>
              @endif
            </td>
            <td>
              @if($isFinished)
              <span class="badge bg-dark rounded-pill">Selesai</span>
              @elseif($isOngoing)
              <span class="badge bg-success rounded-pill animate-blink">Sedang Berjalan</span>
              @else
              <span class="badge bg-warning text-dark rounded-pill">Belum Mulai</span>
              @endif
            </td>
            <td class="text-end pe-4">
              <a href="{{ route('admin.cbt.exams.monitor', $exam->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 me-1">
                <i class="bi bi-display"></i> Live Monitor
              </a>
              <form action="{{ route('admin.cbt.exams.destroy', $exam->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="confirmDelete(this.closest('form'))" title="Hapus Jadwal">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">Belum ada jadwal ujian yang dibuat.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  @if(session('success'))
  Swal.fire({
    icon: 'success'
    , title: 'Berhasil!'
    , text: "{{ session('success') }}"
    , timer: 3000
    , showConfirmButton: false
  });
  @endif

  function confirmRefresh(form) {
    Swal.fire({
      title: 'Ganti Token?'
      , text: "Santri yang sedang login tidak terpengaruh, tapi yang baru mau login butuh token baru."
      , icon: 'warning'
      , showCancelButton: true
      , confirmButtonColor: '#3085d6'
      , cancelButtonColor: '#d33'
      , confirmButtonText: 'Ya, Ganti Token!'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    })
  }

  function confirmDelete(form) {
    Swal.fire({
      title: 'Hapus Jadwal?'
      , text: "Data yang dihapus tidak dapat dikembalikan!"
      , icon: 'warning'
      , showCancelButton: true
      , confirmButtonColor: '#d33'
      , cancelButtonColor: '#3085d6'
      , confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    })
  }

</script>
@endpush
