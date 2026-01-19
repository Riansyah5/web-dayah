@extends('layouts.app')
@section('title', 'Pengaturan Rapor')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Pengaturan Rapor Tahfizh</h5>
            <small class="text-muted">Tahun Ajaran: {{ $activeYear->name }} - {{ $activeYear->semester }}</small>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('tahfizh.setting.update') }}" method="POST">
              @csrf

              <div class="mb-3">
                <label class="form-label small text-muted">Kota (Tempat Terbit Rapor)</label>
                <input type="text" name="city" class="form-control"
                  value="{{ old('city', $setting->city ?? 'Lhokseumawe') }}" required placeholder="Contoh: لؤسيموي">
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Tanggal Pembagian Rapor</label>
                <input type="date" name="distribution_date" class="form-control"
                  value="{{ old('distribution_date', $setting->distribution_date?->format('Y-m-d') ?? date('Y-m-d')) }}"
                  required>
              </div>

              <hr>

              {{-- <div class="mb-3">
                <label class="form-label small text-muted">Nama Kepala Tahfizh</label>
                <input type="text" name="headmaster_name" class="form-control"
                  value="{{ old('headmaster_name', $setting->headmaster_name ?? 'Ustadz Abdullah, Lc.') }}" required>
              </div> --}}

              {{-- <div class="mb-4">
                <label class="form-label small text-muted">NIY / NIP (Opsional)</label>
                <input type="text" name="headmaster_niy" class="form-control"
                  value="{{ old('headmaster_niy', $setting->headmaster_niy) }}">
              </div> --}}

              <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">
                <i class="bi bi-save me-2"></i> Simpan Pengaturan
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // SweetAlert untuk Notifikasi Session
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
      });
    @endif

    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
      });
    @endif
  </script>
@endpush
