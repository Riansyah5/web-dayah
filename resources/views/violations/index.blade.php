@extends('layouts.app')
@section('title', 'Pelanggaran Santri')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold text-dark mb-1">Buku Saku Pelanggaran</h4>
        <p class="text-muted small mb-0">Catatan kedisiplinan santri: <strong>{{ $student->name }}</strong></p>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-danger text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addViolationModal">
          <i class="bi bi-exclamation-triangle me-2"></i>Catat Pelanggaran
        </button>
        <button class="btn btn-outline-secondary shadow-sm" data-bs-toggle="modal" data-bs-target="#downloadModal">
          <i class="bi bi-file-earmark-pdf me-2"></i>Cetak / PDF
        </button>
      </div>
    </div>

    <div class="card border-0 shadow-sm bg-white mb-4 rounded-4 overflow-hidden">
      <div class="card-body p-4">
        <div class="d-flex align-items-center">
          <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger me-3">
            <i class="bi bi-lightning-charge-fill fs-3"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total Poin Pelanggaran</h6>
            <h2 class="fw-bold mb-0 text-danger">{{ $student->violations->sum('points') }} Poin</h2>
          </div>
        </div>
      </div>
    </div>

    @forelse($violations as $academicGroup => $months)
      <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light fw-bold py-3">
          <i class="bi bi-calendar-range me-2"></i> Tahun Ajaran: {{ $academicGroup }}
        </div>
        <div class="card-body p-0">
          @foreach ($months as $month => $items)
            <div class="p-3 border-bottom">
              <h6 class="text-primary fw-bold mb-3 ms-2">{{ $month }}</h6>

              <div class="table-responsive">
                <table class="table table-hover table-sm align-middle mb-0">
                  <thead class="text-muted small">
                    <tr>
                      <th class="ps-3">Tanggal</th>
                      <th>Pelanggaran</th>
                      <th>Kategori</th>
                      <th>Hukuman</th>
                      <th class="text-end pe-3">Poin</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($items as $violation)
                      <tr>
                        <td class="ps-3">{{ $violation->violation_date->format('d/m') }}</td>
                        <td>{{ $violation->description }}</td>
                        <td>
                          @php
                            $badge = match ($violation->category) {
                                'Berat' => 'bg-danger',
                                'Sedang' => 'bg-warning text-dark',
                                default => 'bg-info text-white',
                            };
                          @endphp
                          <span class="badge {{ $badge }} fw-normal">{{ $violation->category }}</span>
                        </td>
                        <td class="text-muted small fst-italic">{{ $violation->punishment ?? '-' }}</td>
                        <td class="text-end pe-3 fw-bold text-danger">+{{ $violation->points }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @empty
      <div class="text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="opacity-50 mb-3">
        <p class="text-muted">Alhamdulillah, santri ini belum memiliki catatan pelanggaran.</p>
      </div>
    @endforelse

  </div>

  <div class="modal fade" id="addViolationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Input Pelanggaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('violations.store') }}" method="POST">
          @csrf
          <input type="hidden" name="student_id" value="{{ $student->id }}">
          <div class="modal-body">
            @php
              $now = now();
              $year = $now->year;
              $month = $now->month;
              $academicYear = $month >= 7 ? "$year/" . ($year + 1) : $year - 1 . "/$year";
              $semester = $month >= 7 ? 'Ganjil' : 'Genap';
            @endphp

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small text-muted">Tahun Ajaran</label>
                <input type="text" name="academic_year" class="form-control" value="{{ $academicYear }}" readonly>
              </div>
              <div class="col-6">
                <label class="form-label small text-muted">Semester</label>
                <input type="text" name="semester" class="form-control" value="{{ $semester }}" readonly>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Tanggal Kejadian</label>
              <input type="date" name="violation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-8">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select" required>
                  <option value="Ringan">Ringan (Terlambat, Tidak Piket)</option>
                  <option value="Sedang">Sedang (Merokok, Kabur)</option>
                  <option value="Berat">Berat (Berkelahi, Mencuri)</option>
                </select>
              </div>
              <div class="col-4">
                <label class="form-label">Poin</label>
                <input type="number" name="points" class="form-control" placeholder="10" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi Pelanggaran</label>
              <textarea name="description" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Hukuman / Takzir</label>
              <input type="text" name="punishment" class="form-control" placeholder="Contoh: Membersihkan Masjid">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Simpan Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="downloadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Opsi Cetak Laporan</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('violations.index', $student->id) }}" method="GET">
            <input type="hidden" name="export_pdf" value="true">

            <div class="mb-3">
              <label class="form-label small text-muted">Pilih Periode</label>
              <select class="form-select mb-2" id="periodSelect" onchange="toggleCustomDate(this)">
                <option value="all">Semua Data (Full)</option>
                <option value="current_month">Bulan Ini</option>
                <option value="custom">Pilih Tanggal Manual</option>
              </select>
            </div>

            <div id="customDateBox" class="d-none bg-light p-2 rounded mb-3">
              <small class="d-block mb-1">Dari Tanggal:</small>
              <input type="date" name="start_date" class="form-control form-control-sm mb-2">
              <small class="d-block mb-1">Sampai Tanggal:</small>
              <input type="date" name="end_date" class="form-control form-control-sm">
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-download me-2"></i>Download PDF
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


@endsection
@push('scripts')
  <script>
    function toggleCustomDate(select) {
      const box = document.getElementById('customDateBox');
      if (select.value === 'custom') {
        box.classList.remove('d-none');
      } else {
        box.classList.add('d-none');
        // Reset nilai date jika bukan custom
        box.querySelectorAll('input').forEach(i => i.value = '');
      }
    }
  </script>
@endpush
