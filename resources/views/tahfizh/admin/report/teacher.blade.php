@extends('layouts.app')
@section('title', 'Laporan Kehadiran Guru')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <h4 class="fw-bold mb-4">Laporan Kehadiran Guru Qur'an</h4>

  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
      <form action="{{ route('tahfizh.admin.reports.teacher') }}" method="GET" class="row g-3 align-items-end justify-content-end">
        <div class="col-md-5">
          <label class="form-label small text-muted fw-bold">Pilih Bulan</label>
          <input type="month" name="month" class="form-control" value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1">
            <i class="bi bi-filter"></i> Tampilkan
          </button>
          <button type="button" class="btn btn-outline-success rounded-pill" onclick="window.print()">
            <i class="bi bi-printer"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">No</th>
              <th>Nama Guru</th>
              <th class="text-center text-success">Hadir</th>
              <th class="text-center text-warning">Izin (Approved)</th>
              <th class="text-center text-primary">Jadi Badal</th>
              <th class="text-center fw-bold">Total Log</th>
              <th class="text-center fw-bold">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reportData as $index => $data)
            <tr>
              <td class="ps-4">{{ $index + 1 }}</td>
              <td class="fw-bold">{{ $data->name }}</td>
              <td class="text-center">
                @if($data->hadir > 0)
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $data->hadir }}</span>
                @else
                <span class="text-muted">-</span>
                @endif
              </td>
              <td class="text-center">
                @if($data->izin > 0)
                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">{{ $data->izin }}</span>
                @else
                <span class="text-muted">-</span>
                @endif
              </td>
              <td class="text-center">
                @if($data->badal > 0)
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $data->badal }}</span>
                @else
                <span class="text-muted">-</span>
                @endif
              </td>
              <td class="text-center fw-bold">{{ $data->total_aktivitas }}</td>
              <td class="text-center">
                <a href="{{ route('tahfizh.admin.reports.teacher_detail', ['id' => $data->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                  Detail <i class="bi bi-box-arrow-up-right small ms-1"></i>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-muted">Data guru tidak ditemukan.</td>
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
