@extends('layouts.app')
@section('title', 'Detail Laporan Kehadiran Guru')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="fw-bold mb-0">Detail Absensi Guru</h5>
      <div class="text-muted">{{ $teacher->name }}</div>
    </div>
    <a href="{{ route('tahfizh.admin.reports.teacher', ['month' => \Carbon\Carbon::parse($startDate)->format('Y-m')]) }}" class="btn btn-outline-secondary rounded-pill btn-sm">
      <i class="bi bi-arrow-left"></i> Kembali ke Rekap
    </a>
  </div>

  <div class="alert alert-light border shadow-sm mb-4">
    <i class="bi bi-calendar-range me-2 text-primary"></i>
    Menampilkan data periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</strong> s.d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</strong>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-header bg-white py-3 fw-bold">
          <i class="bi bi-check-circle-fill text-success me-2"></i> Riwayat Kehadiran (Mengajar)
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th>Tanggal & Sesi</th>
                <th>Jam Masuk</th>
                <th>Status</th>
                <th>Bukti Foto</th>
              </tr>
            </thead>
            <tbody>
              @forelse($journals as $j)
              <tr>
                <td>
                  <div class="fw-bold">{{ \Carbon\Carbon::parse($j->date)->translatedFormat('d M Y') }}</div>
                  <small class="text-muted">{{ $j->schedule->session_name }}</small>
                </td>
                <td>
                  <span class="font-monospace fs-6">{{ $j->clock_in->format('H:i') }}</span>
                </td>
                <td>
                  @if($j->original_teacher_id && $j->original_teacher_id != $teacher->id)
                  <span class="badge bg-primary">Badal</span>
                  <div style="font-size: 10px;" class="text-muted">Ganti Ust. {{ $j->original_teacher_id }}</div>
                  @else
                  <span class="badge bg-success">Hadir</span>
                  @endif
                </td>
                <td>
                  @if($j->photo_proof)
                  <a href="{{ asset('storage/' . $j->photo_proof) }}" target="_blank">
                    <img src="{{ asset('storage/' . $j->photo_proof) }}" class="rounded shadow-sm object-fit-cover" width="40" height="40">
                  </a>
                  @else
                  <span class="text-muted small">-</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data kehadiran.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-header bg-white py-3 fw-bold">
          <i class="bi bi-envelope-paper-fill text-warning me-2"></i> Riwayat Izin
        </div>
        <ul class="list-group list-group-flush">
          @forelse($permissions as $p)
          <li class="list-group-item p-3">
            <div class="d-flex justify-content-between mb-1">
              <span class="fw-bold small">{{ \Carbon\Carbon::parse($p->date)->translatedFormat('d M Y') }}</span>
              @if($p->status == 'approved')
              <span class="badge bg-success">Disetujui</span>
              @else
              <span class="badge bg-secondary">{{ $p->status }}</span>
              @endif
            </div>
            <p class="mb-0 small text-muted fst-italic">"{{ $p->reason }}"</p>
          </li>
          @empty
          <li class="list-group-item text-center text-muted py-4">Tidak ada data izin.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
@endpush
