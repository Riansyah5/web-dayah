@extends('layouts.app')
@section('title', 'Riwayat Perizinan')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Riwayat Perizinan</h4>
    <a href="{{ route('tahfizh.permission.create') }}" class="btn btn-primary rounded-pill shadow-sm">
      <i class="bi bi-plus-lg me-2"></i> Ajukan Izin Baru
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">Tanggal</th>
              <th>Sesi Ditinggalkan</th>
              <th>Alasan</th>
              <th>Status</th>
              <th>Lampiran</th>
            </tr>
          </thead>
          <tbody>
            @forelse($permissions as $perm)
            <tr>
              <td class="ps-4">
                <span class="fw-bold">{{ $perm->date->format('d M Y') }}</span>
                <div class="small text-muted">{{ $perm->created_at->diffForHumans() }}</div>
              </td>
              <td>
                @foreach($perm->tahfizhDetails as $detail)
                <span class="badge bg-light text-dark border me-1">{{ $detail->session_name }}</span>
                @endforeach
              </td>
              <td>
                <span class="badge bg-secondary mb-1">{{ ucfirst($perm->type) }}</span>
                <div class="small text-muted text-truncate" style="max-width: 200px;">
                  "{{ $perm->reason }}"
                </div>
              </td>
              <td>
                @if($perm->status == 'approved')
                <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle me-1"></i> Disetujui</span>
                @elseif($perm->status == 'rejected')
                <span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i> Ditolak</span>
                @else
                <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-hourglass me-1"></i> Menunggu</span>
                @endif
              </td>
              <td>
                @if($perm->attachment)
                <a href="{{ asset('storage/'.$perm->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                  <i class="bi bi-file-earmark-text"></i> Cek
                </a>
                @else
                <span class="text-muted small">-</span>
                @endif
              </td>
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
    <div class="card-footer bg-white py-3">
      {{ $permissions->links() }}
    </div>
  </div>
</div>
@endsection
@push('scripts')
@endpush
