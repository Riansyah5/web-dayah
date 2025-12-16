@extends('layouts.app')
@section('title', 'Migrasi / Kenaikan Kelas')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <h4 class="fw-bold mb-4">Migrasi / Kenaikan Kelas</h4>

        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-primary">Form Proses Otomatis</h6>
          </div>
          <div class="card-body p-4">

            <form id="promotion-form" action="{{ route('promotion.process') }}" method="POST">
              @csrf

              <div class="mb-4">
                <label class="form-label fw-bold">Jenis Proses</label>
                <div class="d-flex gap-3">
                  <div class="form-check card p-3 border w-50">
                    <input class="form-check-input" type="radio" name="type" id="typeCopy" value="copy" checked>
                    <label class="form-check-label fw-bold" for="typeCopy">
                      Ganti Semester (Copy Kelas)
                    </label>
                    <small class="d-block text-muted mt-1">
                      Menyalin kelas & siswa apa adanya. <br>Contoh: 7A (Ganjil) -> 7A (Genap).
                    </small>
                  </div>
                  <div class="form-check card p-3 border w-50">
                    <input class="form-check-input" type="radio" name="type" id="typePromote" value="promote">
                    <label class="form-check-label fw-bold" for="typePromote">
                      Kenaikan Kelas (Naik Tingkat)
                    </label>
                    <small class="d-block text-muted mt-1">
                      Menaikkan tingkat siswa. <br>Contoh: 7A -> 8A. (Kelas akhir tidak disalin/Lulus).
                    </small>
                  </div>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Dari Tahun Ajaran (Sumber)</label>
                  <select name="from_year_id" class="form-select bg-light" required>
                    @foreach ($years as $y)
                      <option value="{{ $y->id }}">{{ $y->name }} - {{ $y->semester }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Ke Tahun Ajaran (Tujuan)</label>
                  <select name="to_year_id" class="form-select border-primary" required>
                    <option value="" selected disabled>-- Pilih Tahun Baru --</option>
                    @foreach ($years as $y)
                      <option value="{{ $y->id }}">{{ $y->name }} - {{ $y->semester }}</option>
                    @endforeach
                  </select>
                  <div class="form-text text-primary">Pastikan Tahun Ajaran tujuan sudah dibuat di Data Master.</div>
                </div>
              </div>

              <hr class="my-4">

              <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3">
                <i class="bi bi-gear-fill me-2"></i> Jalankan Proses
              </button>
            </form>
          </div>
        </div>

        <div class="alert alert-info mt-4 border-0 shadow-sm">
          <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Catatan:</h6>
          <ul class="mb-0 small">
            <li>Fitur ini akan membuat Kelas Baru di tahun tujuan secara otomatis.</li>
            <li>Siswa dari kelas lama akan otomatis dimasukkan ke kelas baru tersebut.</li>
            <li>Jika ada siswa yang tinggal kelas atau pindah, Anda bisa mengeditnya manual di menu Kelas setelah proses
              ini selesai.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
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
    @endif

    // Notifikasi Error
    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '{{ session('error') }}',
      });
    @endif

    // Konfirmasi Submit Form
    document.getElementById('promotion-form').addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Yakin proses data ini?',
        text: "Pastikan Tahun Ajaran Tujuan sudah benar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Jalankan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  </script>
@endpush
