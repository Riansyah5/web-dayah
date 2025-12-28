@extends('layouts.app')
@section('title', 'Perizinan Santri')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold text-dark">Perizinan Santri</h4>
      <a href="{{ route('permissions.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>Buat Izin Baru
      </a>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger text-white rounded-4">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-0 text-white">Sedang Di Luar</h6>
                <h1 class="fw-bold mb-0 text-white">{{ $activePermissions->count() }}</h1>
              </div>
              <i class="bi bi-box-arrow-right fs-1 opacity-75"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <button class="nav-link active rounded-pill px-4" id="pills-active-tab" data-bs-toggle="pill"
          data-bs-target="#pills-active" type="button">
          Sedang Izin ({{ $activePermissions->count() }})
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link rounded-pill px-4" id="pills-history-tab" data-bs-toggle="pill"
          data-bs-target="#pills-history" type="button">
          Riwayat Izin
        </button>
      </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">

      <div class="tab-pane fade show active" id="pills-active">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-4">Nama Santri</th>
                    <th>Jenis</th>
                    <th>Petugas</th>
                    <th>Waktu Keluar</th>
                    <th>Batas Kembali</th>
                    <th>Sisa Waktu</th>
                    <th class="text-end pe-4">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($activePermissions as $perm)
                    <tr>
                      <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $perm->student->name }}</div>
                        <div class="small text-muted">{{ $perm->student->class_group ?? '-' }}</div>
                      </td>
                      <td>
                        <span class="badge bg-warning text-dark">{{ ucfirst($perm->type) }}</span>
                      </td>
                      <td>
                        <strong>{{ $perm->user->name ?? '-' }}</strong>
                      </td>
                      <td>{{ $perm->start_date->locale('id')->translatedFormat('d M H:i') }}</td>
                      <td>
                        <div class="fw-bold text-danger">{{ $perm->end_date->locale('id')->translatedFormat('d M H:i') }}</div>
                      </td>
                      <td>
                        @if (now()->gt($perm->end_date))
                          <span class="text-danger fw-bold">Telat
                            {{ $perm->end_date->locale('id')->diffForHumans(null, true) }}</span>
                        @else
                          <span class="text-success">Sisa
                            {{ $perm->end_date->locale('id')->diffForHumans(null, true) }}</span>
                        @endif
                      </td>
                      <td class="text-end pe-4">
                        <a href="{{ route('permissions.print', $perm->id) }}" target="_blank"
                          class="btn btn-sm btn-outline-secondary me-1" title="Lihat dan Cetak Surat">
                          <i class="bi bi-printer"></i>
                        </a>
                        <a href="{{ route('permissions.downloadpdf', $perm->id) }}" target="_blank"
                          class="btn btn-sm btn-outline-warning me-1" title="Download PDF Surat Izin">
                          <i class="bi bi-download"></i>
                        </a>
                        <form action="{{ route('permissions.return', $perm->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <button type="submit" class="btn btn-sm btn-success text-white btn-return"
                            data-name="{{ $perm->student->name }}">
                            <i class="bi bi-check-lg me-1"></i> Kembali
                          </button>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center py-5 text-muted">Tidak ada santri yang sedang izin di luar.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="pills-history">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-4">Nama Santri</th>
                    <th>Jenis</th>
                    <th>Petugas</th>
                    <th>Alasan</th>
                    <th>Tgl Keluar</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($historyPermissions as $perm)
                    <tr>
                      <td class="ps-4 fw-medium">{{ $perm->student->name }}</td>
                      <td>{{ ucfirst($perm->type) }}</td>
                      <td><strong>{{ $perm->user->name ?? '-' }}</strong></td>
                      <td>{{ Str::limit($perm->reason, 30) }}</td>
                      <td>{{ $perm->start_date->format('d/m/y H:i') }}</td>
                      <td>{{ $perm->returned_at ? $perm->returned_at->format('d/m/y H:i') : '-' }}</td>
                      <td>
                        @if ($perm->status == 'late')
                          <span class="badge bg-danger">Terlambat</span>
                        @elseif($perm->status == 'returned')
                          <span class="badge bg-success">Tepat Waktu</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="p-3">
              {{ $historyPermissions->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  {{-- sweetAlert2 --}}
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

    // SweetAlert Konfirmasi Kembali
    document.querySelectorAll('.btn-return').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const name = this.getAttribute('data-name');

        Swal.fire({
          title: 'Konfirmasi Kembali',
          html: `Apakah santri <strong>${name}</strong> sudah kembali ke asrama?`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#198754',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Sudah',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>
@endpush
