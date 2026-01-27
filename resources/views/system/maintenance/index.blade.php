@extends('layouts.app')
@section('title', 'Pemeliharaan Sistem')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <h4 class="fw-bold mb-4">Pemeliharaan Sistem (Storage)</h4>

  <div class="row">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body bg-light">
          <h6 class="fw-bold">Status Saat Ini</h6>
          <ul class="list-group list-group-flush bg-transparent">
            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
              <span>Total Foto Jurnal</span>
              <span class="fw-bold">{{ $journalCount }} File</span>
            </li>
            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
              <span>Total Lampiran Izin</span>
              <span class="fw-bold">{{ $permissionCount }} File</span>
            </li>
          </ul>
          <small class="text-muted fst-italic mt-2 d-block">
            *Menghapus foto tidak akan menghapus data kehadiran/jurnal. Hanya bukti fisiknya yang hilang.
          </small>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card border-0 shadow-sm rounded-4 border-start border-danger border-5">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold text-danger mb-0"><i class="bi bi-trash3-fill me-2"></i> Bersihkan Arsip Lama</h5>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('system.maintenance.cleanup') }}" method="POST" id="cleanupForm">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-bold">Target Pembersihan</label>
              <select name="target" class="form-select" required>
                <option value="journals">Hanya Foto Jurnal Mengajar</option>
                <option value="permissions">Hanya Lampiran Surat Izin/Sakit</option>
                <option value="all">Semua (Jurnal & Izin)</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Hapus Data Yang Lebih Lama Dari:</label>
              <select name="period" class="form-select" required>
                <option value="3_months">3 Bulan yang lalu</option>
                <option value="6_months">6 Bulan yang lalu (Rekomendasi)</option>
                <option value="1_year">1 Tahun yang lalu</option>
              </select>
              <div class="form-text text-danger">
                Contoh: Jika pilih "6 Bulan", maka foto hari ini sampai 5 bulan lalu AMAN. Yang dihapus adalah bulan ke-6 ke belakang.
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold">
                <i class="bi bi-exclamation-triangle me-2"></i> Eksekusi Penghapusan
              </button>
            </div>
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
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
      });
    @endif

    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
      });
    @endif

    document.getElementById('cleanupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'PERINGATAN KERAS!',
            text: "File fisik yang dihapus TIDAK DAPAT DIKEMBALIKAN. Apakah Anda yakin ingin melanjutkan pembersihan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
@endpush
