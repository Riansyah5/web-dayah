@extends('layouts.app')
@section('title', 'Riwayat Rapor Tahfizh')
@push('link')
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-light rounded me-3"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="fw-bold mb-0">Riwayat Rapor Tahfizh</h4>
            <p class="text-muted small mb-0">
                Arsip Nilai: <strong>{{ $student->name }}</strong> ({{ $student->nis }})
            </p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tahun Ajaran / Semester</th>
                            <th>Halaqah / Musyrif</th>
                            <th>Capaian Hafalan</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $report->academicYear->name }}</div>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill" style="font-size: 0.75rem;">
                                        {{ ucfirst($report->academicYear->semester) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-dark">{{ $report->teacher->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $report->total_hafalan ?? '-' }}</div>
                                    <small class="text-muted">Tulis: {{ $report->score_tahriri ?? 0 }}</small>
                                </td>
                                <td>
                                    @if($report->is_locked)
                                        <span class="badge bg-success"><i class="bi bi-lock-fill me-1"></i> Final</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('tahfizh.assessment.print', $student->id) }}" class="btn btn-sm btn-outline-danger rounded" target="_blank">
                                        <i class="bi bi-printer me-2"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://illustrations.popsy.co/gray/folder.svg" alt="Empty" width="100" class="mb-3 opacity-50">
                                    <h6 class="text-muted">Belum ada riwayat rapor sebelumnya.</h6>
                                </td>
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
