@extends('layouts.app')
@section('title', 'Data Pruning & Storage Management Tahfizh')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="row mb-4">
    <div class="col-md-8">
      <h4 class="fw-bold"><i class="bi bi-shield-lock me-2"></i>Data Pruning & Storage Management</h4>
      <p class="text-muted">Kelola ruang penyimpanan dengan membersihkan data absensi dan foto yang sudah lama.</p>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white py-3">
          <h6 class="mb-0 text-white">Konfigurasi Cleanup</h6>
        </div>
        <div class="card-body">
          <form action="{{ route('tahfizh.admin.cleanup.run') }}" method="POST" id="cleanupForm">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-bold small">Apa yang ingin dibersihkan?</label>
              <select name="type" class="form-select shadow-none" required>
                <option value="photos">Hanya Foto Bukti (Data Absen Tetap Ada)</option>
                <option value="all_data">Seluruh Data & Foto (Hapus Permanen)</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold small">Batas Waktu (Data yang lebih tua dari):</label>
              <div class="input-group">
                <input type="number" name="months" class="form-control shadow-none" value="2" min="1">
                <span class="input-group-text">Bulan</span>
              </div>
              <div class="form-text mt-2">
                <i class="bi bi-info-circle"></i> Rekomendasi: <strong>6-12 bulan</strong> untuk menjaga performa.
              </div>
            </div>

            <div class="alert alert-warning border-0 small mb-4">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              Aksi ini akan menghapus file fisik di server. Pastikan Anda sudah mem-backup data jika diperlukan.
            </div>

            <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">
              <i class="bi bi-trash-fill me-2"></i> Jalankan Pembersihan
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="row g-3 mb-4">
        <div class="col-6">
          <div class="p-3 bg-white rounded-4 shadow-sm border-start border-primary border-4">
            <small class="text-muted d-block">Foto > 2 Bulan</small>
            <span class="fs-4 fw-bold">{{ number_format($stats['old_photos']) }}</span> <small>File</small>
          </div>
        </div>
        <div class="col-6">
          <div class="p-3 bg-white rounded-4 shadow-sm border-start border-danger border-4">
            <small class="text-muted d-block">Record > 2 Bulan</small>
            <span class="fs-4 fw-bold">{{ number_format($stats['old_records']) }}</span> <small>Baris</small>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 fw-bold">Riwayat Cleanup Terakhir</div>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="bg-light">
              <tr class="small text-uppercase">
                <th class="ps-3">Tanggal</th>
                <th>Jenis</th>
                <th class="text-center">Jumlah</th>
                <th>Admin</th>
              </tr>
            </thead>
            <tbody class="small">
              @foreach($recentLogs as $log)
              <tr>
                <td class="ps-3">{{ $log->created_at->format('d/m/y H:i') }}</td>
                <td>
                  <span class="badge {{ $log->cleanup_type == 'photos' ? 'bg-info' : 'bg-danger' }}">
                    {{ $log->cleanup_type }}
                  </span>
                </td>
                <td class="fw-bold text-danger text-center">{{ $log->total_deleted }}</td>
                <td>{{ $log->admin->name }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('cleanupForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Mencegah form submit secara langsung
    const form = this;

    Swal.fire({
      title: 'Anda Yakin?',
      html: "Aksi ini akan menghapus data secara <strong>permanen</strong> dan tidak dapat dibatalkan. Pastikan Anda sudah melakukan backup jika diperlukan.<br><br>Lanjutkan?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus Saja!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Jika dikonfirmasi, submit form
      }
    })
  });

  // Menampilkan notifikasi sukses setelah redirect
  @if (session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      showConfirmButton: false,
      timer: 2500
    });
  @endif
</script>
@endpush
