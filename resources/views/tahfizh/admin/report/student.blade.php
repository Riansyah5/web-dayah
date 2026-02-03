@extends('layouts.app')
@section('title', 'Laporan Kehadiran Santri')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <h4 class="fw-bold mb-4">Laporan Kehadiran Santri</h4>

  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
      <form action="{{ route('tahfizh.admin.reports.student') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label small text-muted fw-bold">Pilih Bulan</label>
          <input type="month" name="month" class="form-control" value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}">
        </div>
        <div class="col-md-5">
          <label class="form-label small text-muted fw-bold">Pilih Halaqah</label>
          <select name="halaqah_id" class="form-select" required>
            <option value="">-- Pilih Kelompok --</option>
            @foreach($halaqahs as $h)
            <option value="{{ $h->id }}" {{ $halaqahId == $h->id ? 'selected' : '' }}>
              {{ $h->name }} ({{ $h->teacher->name }})
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100 rounded-pill">
            <i class="bi bi-search"></i> Cari
          </button>
        </div>
      </form>
    </div>
  </div>

  @if($halaqahId && $selectedHalaqah)
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-0 fw-bold">{{ $selectedHalaqah->name }}</h5>
          <small class="text-muted">Musyrif: {{ $selectedHalaqah->teacher->name }}</small>
        </div>
        <div>
          <span class="badge bg-success me-1">H: Hadir</span>
          <span class="badge bg-info me-1">S: Sakit</span>
          <span class="badge bg-warning text-dark me-1">I: Izin</span>
          <span class="badge bg-danger">A: Alpha</span>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-striped">
          <thead class="bg-light">
            <tr>
              <th class="ps-4" width="5%">No</th>
              <th>Nama Santri</th>
              <th class="text-center" width="10%">Hadir</th>
              <th class="text-center" width="10%">Sakit</th>
              <th class="text-center" width="10%">Izin</th>
              <th class="text-center" width="10%">Alpha</th>
              <th class="text-center" width="10%">Telat</th>
              <th class="text-center" width="10%">%</th>
              <th class="text-center" width="10%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($students as $index => $s)
            <tr>
              <td class="ps-4">{{ $index + 1 }}</td>
              <td class="fw-bold">{{ $s->name }}</td>
              <td class="text-center fw-bold text-success">{{ $s->hadir }}</td>
              <td class="text-center">{{ $s->sakit > 0 ? $s->sakit : '-' }}</td>
              <td class="text-center">{{ $s->izin > 0 ? $s->izin : '-' }}</td>
              <td class="text-center fw-bold {{ $s->alpha > 0 ? 'text-danger' : 'text-muted' }}">
                {{ $s->alpha > 0 ? $s->alpha : '-' }}
              </td>
              <td class="text-center fw-bold {{ $s->telat > 0 ? 'text-secondary' : 'text-muted' }}">
                {{ $s->telat > 0 ? $s->telat : '-' }}
              </td>
              <td class="text-center">
                @php
                $bg = 'bg-success';
                if($s->persentase < 50) $bg='bg-danger' ; elseif($s->persentase < 80) $bg='bg-warning text-dark' ; @endphp <span class="badge {{ $bg }}">{{ $s->persentase }}%</span>
              </td>
              <td class="text-center">
                <a href="{{ route('tahfizh.admin.reports.student_detail', ['id' => $s->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                  Detail <i class="bi bi-box-arrow-up-right small ms-1"></i>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-5 text-muted">Tidak ada data santri di halaqah ini.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @elseif(!$halaqahId)
  <div class="text-center py-5 text-muted">
    <i class="bi bi-arrow-up-circle fs-1 mb-3 d-block"></i>
    Silakan pilih halaqah dan bulan untuk melihat laporan.
  </div>
  @endif
</div>
@endsection
@push('scripts')
@endpush
