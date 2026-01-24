@extends('layouts.app')
@section('title', 'Riwayat Izin Guru')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold">Riwayat Izin Saya</h4>
      <a href="{{ route('academic.permission.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i> Ajukan Baru
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">Tanggal</th>
              <th>Jenis</th>
              <th>Alasan</th>
              <th>Status</th>
              {{-- <th class="text-end pe-4">Bukti</th> --}}
            </tr>
          </thead>
          <tbody>
            @forelse($permissions as $p)
              <tr>
                <td class="ps-4">{{ \Carbon\Carbon::parse($p->date)->format('d M Y') }}</td>
                <td>
                  @if ($p->type == 'sick')
                    Sakit
                  @elseif($p->type == 'duty')
                    Dinas
                  @else
                    Izin
                  @endif
                </td>
                <td>{{ Str::limit($p->reason, 40) }}</td>
                <td>
                  @if ($p->status == 'pending')
                    <span class="badge bg-warning text-dark">Menunggu</span>
                  @elseif($p->status == 'approved')
                    <span class="badge bg-success">Disetujui</span>
                  @else
                    <span class="badge bg-danger">Ditolak</span>
                  @endif
                </td>
                {{-- <td class="text-end pe-4">
                  @if ($p->attachment)
                    <a href="{{ asset('storage/' . $p->attachment) }}" target="_blank" class="btn btn-sm btn-light border">
                      <i class="bi bi-file-earmark-text"></i>
                    </a>
                  @else
                    -
                  @endif
                </td> --}}
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat pengajuan izin.</td>
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
  </script>
@endpush
