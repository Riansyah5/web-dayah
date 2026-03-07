@extends('layouts.app')
@section('title', 'Manajemen Akun CBT')
@push('link')
@endpush
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@section('content')
<div class="container py-4">
  <div class="row align-items-center mb-4">
    <div class="col-md-6">
      <h4 class="fw-bold mb-1"><i class="bi bi-people-fill text-primary me-2"></i>Manajemen Akun CBT</h4>
      <p class="text-muted small mb-0">Kelola akses login ujian untuk seluruh santri.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
      <form action="{{ route('admin.cbt.accounts.generate') }}" method="POST" class="d-inline" id="form-generate">
        @csrf
        <button type="button" class="btn btn-primary rounded-pill shadow-sm btn-generate" {{ $missingAccounts == 0 ? 'disabled' : '' }}>
          <i class="bi bi-magic me-1"></i> Generate {{ $missingAccounts }} Akun Kosong
        </button>
      </form>
      <button class="btn btn-outline-success rounded-pill shadow-sm ms-1" onclick="Swal.fire('Info', 'Fitur cetak kartu akan segera hadir!', 'info')">
        <i class="bi bi-printer me-1"></i> Cetak Kartu
      </button>
    </div>
  </div>

  <div class="row mb-4 g-3">
    <div class="col-md-4">
      <div class="p-3 bg-white border-start border-primary border-4 rounded shadow-sm">
        <div class="text-muted small fw-bold">Total Santri</div>
        <h3 class="fw-bold mb-0">{{ number_format($totalStudents) }}</h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="p-3 bg-white border-start border-success border-4 rounded shadow-sm">
        <div class="text-muted small fw-bold">Sudah Punya Akun</div>
        <h3 class="fw-bold mb-0">{{ number_format($totalAccounts) }}</h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="p-3 bg-white border-start border-danger border-4 rounded shadow-sm">
        <div class="text-muted small fw-bold">Belum Punya Akun</div>
        <h3 class="fw-bold mb-0 text-danger">{{ number_format($missingAccounts) }}</h3>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4" width="5%">No</th>
              <th>Nama Santri</th>
              <th>Username CBT</th>
              <th>PIN / Password</th>
              <th>Status Akses</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($students as $index => $student)
            <tr>
              <td class="ps-4">{{ $students->firstItem() + $index }}</td>
              <td class="fw-bold">{{ $student->name }}</td>

              @if($student->cbtAccount)
              <td><span class="font-monospace px-2 py-1 bg-light border rounded">{{ $student->cbtAccount->username }}</span></td>
              <td><span class="font-monospace px-2 py-1 bg-light border rounded text-danger fw-bold">{{ $student->cbtAccount->raw_pin }}</span></td>
              <td>
                @if($student->cbtAccount->is_active)
                <span class="badge bg-success rounded-pill">Aktif (Bisa Ujian)</span>
                @else
                <span class="badge bg-danger rounded-pill">Diblokir</span>
                @endif
              </td>
              <td class="text-center">
                <form action="{{ route('admin.cbt.accounts.reset', $student->cbtAccount->id) }}" method="POST" class="d-inline form-reset">
                  @csrf
                  <button type="button" class="btn btn-sm btn-outline-warning rounded-circle me-1 btn-reset" title="Reset PIN Baru">
                    <i class="bi bi-arrow-clockwise"></i>
                  </button>
                </form>

                <form action="{{ route('admin.cbt.accounts.toggle', $student->cbtAccount->id) }}" method="POST" class="d-inline form-toggle">
                  @csrf
                  <button type="button" class="btn btn-sm {{ $student->cbtAccount->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-circle btn-toggle" title="{{ $student->cbtAccount->is_active ? 'Blokir Akses' : 'Buka Blokir' }}" data-status="{{ $student->cbtAccount->is_active ? 'active' : 'inactive' }}">
                    <i class="bi {{ $student->cbtAccount->is_active ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                  </button>
                </form>
              </td>
              @else
              <td colspan="4" class="text-muted fst-italic"><i class="bi bi-info-circle me-1"></i> Belum di-generate. Klik tombol biru di atas.</td>
              @endif
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-muted">Belum ada data santri di database.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-white py-3">
      {{ $students->links() }}
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Konfirmasi Generate Akun
    const btnGenerate = document.querySelector('.btn-generate');
    if(btnGenerate) {
      btnGenerate.addEventListener('click', function() {
        Swal.fire({
          title: 'Generate Akun?',
          text: "Sistem akan membuatkan akun otomatis untuk santri yang belum memiliki akun.",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#0d6efd',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Generate Sekarang',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('form-generate').submit();
          }
        });
      });
    }

    // 2. Konfirmasi Reset PIN
    const resetButtons = document.querySelectorAll('.btn-reset');
    resetButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const form = this.closest('.form-reset');
        Swal.fire({
          title: 'Reset PIN?',
          text: "PIN lama akan diganti dengan yang baru secara acak.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ffc107',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Reset PIN',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // 3. Konfirmasi Toggle Status (Blokir/Buka)
    const toggleButtons = document.querySelectorAll('.btn-toggle');
    toggleButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const form = this.closest('.form-toggle');
        const isActive = this.getAttribute('data-status') === 'active';
        const actionText = isActive ? 'Blokir Akses' : 'Buka Blokir';
        const color = isActive ? '#dc3545' : '#198754';

        Swal.fire({
          title: actionText + '?',
          text: isActive ? "Santri tidak akan bisa login ujian." : "Santri akan diizinkan login kembali.",
          icon: isActive ? 'warning' : 'question',
          showCancelButton: true,
          confirmButtonColor: color,
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Lakukan',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // 4. Flash Message Success/Error
    @if(session('success'))
      Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
    @endif
    @if(session('error'))
      Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
    @endif
  });
</script>
@endpush
