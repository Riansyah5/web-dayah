@extends('layouts.app')
@section('title', 'Rekap Nilai & Koreksi Essay')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Rekap Nilai: <span class="text-primary">{{ $exam->name }}</span></h4>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-success rounded-pill shadow-sm" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Cetak Rekap
      </button>
      <a href="{{ route('teacher.cbt.results.index') }}" class="btn btn-light rounded-pill border shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th class="ps-4" width="5%">No</th>
            <th>Nama Santri</th>
            <th>Waktu Pengerjaan</th>
            <th>Status</th>
            <th class="text-center">Nilai Akhir</th>
            <th class="text-end pe-4">Koreksi Essay</th>
          </tr>
        </thead>
        <tbody>
          @forelse($studentExams as $index => $se)
          <tr>
            <td class="ps-4">{{ $index + 1 }}</td>
            <td class="fw-bold">{{ $se->cbtAccount->student->name }}</td>
            <td>
              <small class="d-block text-muted">Mulai: {{ $se->started_at->format('H:i') }}</small>
              <small class="d-block text-muted">Selesai: {{ $se->finished_at ? $se->finished_at->format('H:i') : '-' }}</small>
            </td>
            <td>
              @if($se->status == 'finished')
              <span class="badge bg-success rounded-pill">Selesai</span>
              @else
              <span class="badge bg-warning text-dark rounded-pill">Masih Mengerjakan</span>
              @endif
            </td>
            <td class="text-center">
              @php
              $scoreColor = $se->score >= 70 ? 'text-success' : 'text-danger';
              @endphp
              <div class="fs-5 fw-bold {{ $scoreColor }}">{{ round($se->score) }}</div>
            </td>
            <td class="text-end pe-4">
              @if($se->status == 'finished')
              <a href="{{ route('teacher.cbt.results.correct', $se->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="bi bi-pencil-square me-1"></i> Lihat / Koreksi
              </a>
              @else
              <span class="text-muted small fst-italic">Menunggu Selesai</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">Belum ada santri yang mengumpulkan ujian ini.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@push('scripts')
@endpush
