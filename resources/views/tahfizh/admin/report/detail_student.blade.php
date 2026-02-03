@extends('layouts.app')
@section('title', 'Rincian Absensi Santri')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="fw-bold mb-0">Rincian Absensi Santri</h5>
      <div class="text-muted">
        {{ $student->name }} <span class="mx-2">•</span> <small class="text-primary">{{ $student->tahfizhHalaqahs[0]->name }}</small>
      </div>
    </div>
    <a href="{{ route('tahfizh.admin.reports.student', ['start_date' => $startDate, 'end_date' => $endDate, 'halaqah_id' => $student->tahfizh_halaqah_id]) }}" class="btn btn-outline-secondary rounded-pill btn-sm">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">Tanggal</th>
              <th>Sesi & Guru</th>
              <th>Status Kehadiran</th>
              <th>Catatan Musyrif</th>
            </tr>
          </thead>
          <tbody>
            @forelse($attendances as $att)
            @php
            $statusColor = match($att->status) {
            'present' => 'success',
            'sick' => 'info',
            'permission' => 'warning text-dark',
            'alpha' => 'danger',
            'late' => 'secondary',
            default => 'secondary'
            };

            $statusLabel = match($att->status) {
            'present' => 'Hadir',
            'sick' => 'Sakit',
            'permission' => 'Izin',
            'alpha' => 'Alpha',
            'late' => 'Telat',
            default => $att->status
            };
            $journal = $att->tahfizhJournal;
            @endphp
            <tr>
              <td class="ps-4" style="min-width: 150px;">
                <div class="fw-bold">{{ \Carbon\Carbon::parse($journal->date)->locale('id')->translatedFormat('l, d M Y') }}</div>
              </td>
              <td>
                <div class="fw-bold small">{{ $journal->schedule->session_name }}</div>
                <div class="text-muted small" style="font-size: 11px;">
                  <i class="bi bi-person"></i> {{ $journal->teacher->name }}
                </div>
              </td>
              <td>
                <span class="badge bg-{{ $statusColor }} rounded-pill px-3 text-uppercase">
                  {{ $statusLabel }}
                </span>
              </td>
              <td>
                @if($att->note)
                <span class="fst-italic text-muted small">"{{ $att->note }}"</span>
                @else
                <span class="text-muted small">-</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center py-5 text-muted">Belum ada data absensi pada periode ini.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
@endpush
