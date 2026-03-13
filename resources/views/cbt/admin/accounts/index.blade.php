@extends('layouts.app')
@section('title', 'Manajemen Akun CBT')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* Custom Premium Styles */
    .stat-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 16px;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .icon-box {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.5rem;
    }
    .table-custom thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .table-custom tbody tr {
        transition: background-color 0.2s;
    }
    .table-custom tbody tr:hover {
        background-color: #f8f9fc;
    }
    .btn-action-top {
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .search-input {
        border-radius: 10px 0 0 10px;
        border-right: none;
    }
    .search-input:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
    .search-btn {
        border-radius: 0 10px 10px 0;
        border-left: none;
        background-color: #fff;
        border-color: #ced4da;
    }
    .search-btn:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm mx-0">
        <div class="col-xl-3 col-lg-12 mb-3 mb-xl-0 px-0">
            <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Manajemen Akun CBT</h4>
            <p class="text-muted small mb-0">Kelola akses ujian dan generate akun santri secara otomatis.</p>
        </div>
        <div class="col-xl-9 col-lg-12 text-xl-end px-0 d-flex flex-wrap gap-2 justify-content-xl-end">
            <form action="{{ route('admin.cbt.generate.batch') }}" method="POST" id="form-generate">
                @csrf
                <button type="button" id="btnGenerate" class="btn btn-primary btn-action-top shadow-sm">
                    <i class="bi bi-magic"></i> Generate Akun
                </button>
            </form>

            <a href="{{ route('admin.cbt.accounts.print') }}" target="_blank" class="btn btn-outline-success btn-action-top shadow-sm">
                <i class="bi bi-printer"></i> Cetak Kartu
            </a>

            <form action="{{ route('admin.cbt.accounts.activate_massal') }}" method="POST" id="form-activate-massal">
                @csrf
                <button type="button" class="btn btn-success btn-action-top shadow-sm btn-activate-massal">
                    <i class="bi bi-unlock-fill"></i> Aktifkan Semua
                </button>
            </form>

            <form action="{{ route('admin.cbt.accounts.deactivate_massal') }}" method="POST" id="form-deactivate-massal">
                @csrf
                <button type="button" class="btn btn-outline-danger btn-action-top shadow-sm btn-deactivate-massal">
                    <i class="bi bi-lock-fill"></i> Blokir Semua
                </button>
            </form>

            <form action="{{ route('admin.cbt.reset.batch') }}" method="POST" id="form-reset-massal">
                @csrf
                <button type="button" id="btnReset" class="btn btn-danger btn-action-top shadow-sm">
                    <i class="bi bi-arrow-clockwise"></i> Reset Massal
                </button>
            </form>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="stat-card p-3 bg-white shadow-sm d-flex align-items-center h-100 border-start border-primary border-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Total Santri</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalStudents) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-3 bg-white shadow-sm d-flex align-items-center h-100 border-start border-success border-4">
                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-bold">Santri Aktif</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalActiveStudentAccounts) }}</h3>
                    </div>
                    <div class="text-end border-start ps-3">
                        <div class="text-muted small fw-bold">Non-Aktif</div>
                        <h4 class="fw-bold mb-0 text-danger">{{ number_format($totalInactiveStudentAccounts) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="stat-card p-3 bg-white shadow-sm d-flex align-items-center h-100 border-start border-info border-4">
                <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-hdd-network-fill"></i>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-bold">Akun Siap Ujian</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($activeAccounts) }}</h3>
                    </div>
                    <div class="text-end border-start ps-3 pe-3">
                        <div class="text-muted small fw-bold">Terblokir</div>
                        <h4 class="fw-bold mb-0 text-warning">{{ number_format($inactiveAccounts) }}</h4>
                    </div>
                    <div class="text-end border-start ps-3">
                        <div class="text-muted small fw-bold">Belum Punya</div>
                        <h4 class="fw-bold mb-0 text-danger">{{ number_format($missingAccounts) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h5 class="fw-bold mb-3 mb-md-0 text-dark">Data Akun Ujian Santri</h5>
            
            <form action="{{ url()->current() }}" method="GET" class="d-flex" style="min-width: 300px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0 search-input" placeholder="Cari nama atau username..." value="{{ request('search') }}">
                    <button class="btn border search-btn" type="submit">Cari</button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" width="5%">No</th>
                            <th>Nama Santri</th>
                            <th>Username CBT</th>
                            <th>PIN / Password</th>
                            <th>Status Akses</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        <tr>
                            <td class="ps-4 text-muted">{{ $students->firstItem() + $index }}</td>
                            <td class="fw-bold text-dark">{{ $student->name }}</td>

                            @if($student->cbtAccount)
                            <td>
                                <div class="d-inline-flex align-items-center bg-light px-3 py-1 rounded-pill border">
                                    <i class="bi bi-person-badge text-muted me-2 small"></i>
                                    <span class="font-monospace text-dark">{{ $student->cbtAccount->username }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline-flex align-items-center bg-light px-3 py-1 rounded-pill border border-danger border-opacity-25">
                                    <i class="bi bi-key text-danger me-2 small"></i>
                                    <span class="font-monospace text-danger fw-bold tracking-wide">{{ $student->cbtAccount->raw_pin }}</span>
                                </div>
                            </td>
                            <td>
                                @if($student->cbtAccount->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i> Diblokir</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.cbt.accounts.reset', $student->cbtAccount->id) }}" method="POST" class="d-inline form-reset">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-light text-warning rounded-circle me-1 border shadow-sm btn-reset" title="Reset PIN Baru" data-bs-toggle="tooltip">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.cbt.accounts.toggle', $student->cbtAccount->id) }}" method="POST" class="d-inline form-toggle">
                                    @csrf
                                    <button type="button" class="btn btn-sm border shadow-sm {{ $student->cbtAccount->is_active ? 'btn-light text-danger' : 'btn-light text-success' }} rounded-circle btn-toggle" title="{{ $student->cbtAccount->is_active ? 'Blokir Akses' : 'Buka Blokir' }}" data-status="{{ $student->cbtAccount->is_active ? 'active' : 'inactive' }}" data-bs-toggle="tooltip">
                                        <i class="bi {{ $student->cbtAccount->is_active ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                                    </button>
                                </form>
                            </td>
                            @else
                            <td colspan="4">
                                <div class="text-muted fst-italic py-2 d-flex align-items-center">
                                    <i class="bi bi-exclamation-circle text-warning me-2"></i> Belum di-generate.
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                <h6 class="text-muted fw-bold">Belum ada data atau tidak ditemukan.</h6>
                                @if(request('search'))
                                    <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-primary mt-2">Reset Pencarian</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top-0 py-3 px-4">
            {{ $students->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Script asli JS Anda tetap sama, saya hanya merapikannya sedikit
  document.addEventListener('DOMContentLoaded', function() {
    
    // Inisialisasi Tooltips Bootstrap (opsional jika Anda pakai Bootstrap JS)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // 1. Konfirmasi Generate Akun
    const btnGenerate = document.getElementById('btnGenerate');
    if (btnGenerate) {
      btnGenerate.addEventListener('click', function() {
        Swal.fire({
          title: 'Generate Akun Otomatis?',
          text: "Sistem akan membuatkan akun untuk santri yang belum memiliki secara bertahap.",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#0d6efd',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Mulai Generate',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) startGeneration();
        });
      });
    }

    // 2. Konfirmasi Reset PIN
    document.querySelectorAll('.btn-reset').forEach(btn => {
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
          if (result.isConfirmed) form.submit();
        });
      });
    });

    // 3. Konfirmasi Toggle Status
    document.querySelectorAll('.btn-toggle').forEach(btn => {
      btn.addEventListener('click', function() {
        const form = this.closest('.form-toggle');
        const isActive = this.getAttribute('data-status') === 'active';
        
        Swal.fire({
          title: isActive ? 'Blokir Akses?' : 'Buka Blokir?',
          text: isActive ? "Santri tidak akan bisa login ujian." : "Santri akan diizinkan login kembali.",
          icon: isActive ? 'warning' : 'question',
          showCancelButton: true,
          confirmButtonColor: isActive ? '#dc3545' : '#198754',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Lakukan',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) form.submit();
        });
      });
    });

    // 4. Konfirmasi Reset Massal
    const btnReset = document.getElementById('btnReset');
    if (btnReset) {
      btnReset.addEventListener('click', function() {
        Swal.fire({
          title: 'RESET MASSAL?',
          text: "PERHATIAN KRITIKAL! Ini akan MENGACAK ULANG seluruh PIN dan MENONAKTIFKAN semua akun.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Reset Semua',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) startReset();
        });
      });
    }

    // 5. Konfirmasi Aktifkan Semua
    const btnActivateMassal = document.querySelector('.btn-activate-massal');
    if (btnActivateMassal) {
      btnActivateMassal.addEventListener('click', function() {
        Swal.fire({
          title: 'Aktifkan Semua Akun?',
          text: "Seluruh santri akan diizinkan login menggunakan kartu ujian mereka.",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#198754',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Aktifkan',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) document.getElementById('form-activate-massal').submit();
        });
      });
    }

    // 6. Konfirmasi Nonaktifkan Semua
    const btnDeactivateMassal = document.querySelector('.btn-deactivate-massal');
    if (btnDeactivateMassal) {
      btnDeactivateMassal.addEventListener('click', function() {
        Swal.fire({
          title: 'Blokir Semua Akun?',
          text: "Seluruh santri tidak akan dapat login.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Blokir',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) document.getElementById('form-deactivate-massal').submit();
        });
      });
    }

    // 7. Flash Messages
    @if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
    @endif
    @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
    @endif
  });

  // Fungsi Fetch API Anda dibiarkan utuh di bawah ini
  async function startGeneration() {
    Swal.fire({ title: 'Sedang Memproses...', html: 'Mohon tunggu...<br><b>Jangan tutup halaman ini.</b>', allowOutsideClick: false, showConfirmButton: false, didOpen: () => { Swal.showLoading(); } });
    let isDone = false;
    while (!isDone) {
      try {
        const response = await fetch("{{ route('admin.cbt.generate.batch') }}", {
          method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
        });
        const data = await response.json();
        if (data.status === 'done' || data.remaining === 0) {
          isDone = true;
          Swal.fire({ icon: 'success', title: 'Selesai!', text: 'Semua akun berhasil dibuat.', timer: 2000, showConfirmButton: false }).then(() => { window.location.reload(); });
        } else {
          const content = Swal.getHtmlContainer();
          if (content) { const b = content.querySelector('b'); if (b) b.textContent = `Sisa santri: ${data.remaining}`; }
        }
      } catch (error) {
        isDone = true;
        Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal menghubungi server.' });
        break;
      }
    }
  }

  async function startReset() {
    Swal.fire({ title: 'Sedang Memproses...', html: 'Mohon tunggu...<br><b>Jangan tutup halaman ini.</b>', allowOutsideClick: false, showConfirmButton: false, didOpen: () => { Swal.showLoading(); } });
    let isDone = false; let currentLastId = 0;
    while (!isDone) {
      try {
        const response = await fetch("{{ route('admin.cbt.reset.batch') }}", {
          method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
          body: JSON.stringify({ last_id: currentLastId })
        });
        const data = await response.json();
        if (data.status === 'done' || data.remaining === 0) {
          isDone = true;
          Swal.fire({ icon: 'success', title: 'Selesai!', text: 'Seluruh akun telah direset.', timer: 2000, showConfirmButton: false }).then(() => { window.location.reload(); });
        } else {
          const content = Swal.getHtmlContainer();
          if (content) { const b = content.querySelector('b'); if (b) b.textContent = `Sisa antrean: ${data.remaining} akun`; }
          currentLastId = data.last_id;
        }
      } catch (error) {
        isDone = true;
        Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal menghubungi server.' });
        break;
      }
    }
  }
</script>
@endpush