@extends('layouts.app')
@section('title', 'Kelulusan Massal')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="mb-4">
      <h4 class="fw-bold">Proses Kelulusan Massal</h4>
      <p class="text-muted">Pilih kelas tingkat akhir untuk memproses kelulusan santri.</p>
    </div>

    <div class="row g-4">
      @foreach ($classrooms as $class)
        <div class="col-md-3">
          <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
            <div class="card-body text-center py-4">
              <div
                class="avatar-lg bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                style="width: 60px; height: 60px;">
                <span class="fw-bold fs-4">{{ $class->level->name }}</span>
              </div>
              <h5 class="fw-bold mb-1">{{ $class->name }}</h5>
              <p class="text-muted small mb-3">
                {{ $class->students->count() }} Santri Aktif
              </p>

              @if ($class->students->count() > 0)
                <a href="{{ route('graduation.create', $class->id) }}" class="btn btn-outline-success rounded-pill px-4">
                  Proses Kelas Ini
                </a>
              @else
                <button disabled class="btn btn-light rounded-pill px-4 text-muted">Kosong / Sudah Lulus</button>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
@push('scripts')
  {{-- sweetAlert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Notifikasi Sukses
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @elseif (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: '{{ session('error') }}',
        showConfirmButton: true
      });
    @endif
  </script>
@endpush
