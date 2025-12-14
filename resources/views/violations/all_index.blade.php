@extends('layouts.app')
@section('title', 'Kedisiplinan Santri')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush
@push('styles')
  <style>
    .dataTables_filter,
    .dataTables_length {
      display: none;
    }
  </style>
@endpush
@section('content')
  <div class="container py-4">
    <div class="row g-4 mb-4">
      <div class="col-md-8">
        <h4 class="fw-bold text-dark mb-1">Monitoring Kedisiplinan</h4>
        <p class="text-muted small mb-0">Pantau poin pelanggaran dan ketertiban seluruh santri.</p>
      </div>

      <div class="col-md-2">
        <div class="card border-0 shadow-sm bg-danger text-white h-100 rounded-3 overflow-hidden">
          <div class="card-body p-3 position-relative">
            <h2 class="fw-bold mb-0">{{ $totalViolationsThisMonth }}</h2>
            <small class="text-white-50">Kasus Bulan Ini</small>
            <i class="bi bi-graph-up position-absolute bottom-0 end-0 mb-n2 me-n2 opacity-25"
              style="font-size: 3rem;"></i>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100 rounded-3 overflow-hidden">
          <div class="card-body p-3 position-relative">
            <h6 class="fw-bold mb-0 text-truncate">{{ $highestPointStudent->name ?? '-' }}</h6>
            <small class="text-dark-50">Poin Tertinggi ({{ $highestPointStudent->violations_sum_points ?? 0 }})</small>
            <i class="bi bi-exclamation-triangle position-absolute bottom-0 end-0 mb-n2 me-n2 opacity-25"
              style="font-size: 3rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-4">
      <div class="card-body p-3">
        <div class="row g-2 align-items-center">
          <div class="col-md-5">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-pill">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" id="customSearchBox" class="form-control border-start-0 rounded-end-pill ps-2"
                placeholder="Cari Nama / NIS...">
            </div>
          </div>
          <div class="col-md-3 ms-auto">
            <select id="customLengthChange" class="form-select rounded-pill">
              <option value="10">10 Baris</option>
              <option value="25">25 Baris</option>
              <option value="50">50 Baris</option>
              <option value="-1">Semua</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="violationTable" class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary small text-uppercase">
              <tr>
                <th class="ps-4 py-3">Nama Santri</th>
                <th>Kelas/Asrama</th>
                <th class="text-center">Total Kasus</th>
                <th>Akumulasi Poin</th>
                <th class="text-end pe-4">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($students as $student)
                <tr>
                  <td class="ps-4">
                  {{-- icon nama santri --}}
                    {{-- <div class="d-flex align-items-center">
                      @php
                        $initial = strtoupper(substr($student->name, 0, 1));
                        $bg = ['#6366f1', '#10b981', '#f59e0b', '#ef4444'][rand(0, 3)];
                      @endphp
                      <div
                        class="rounded-circle text-white d-flex align-items-center justify-content-center me-3 shadow-sm"
                        style="width: 40px; height: 40px; background-color: {{ $bg }}; font-weight:bold;">
                        {{ $initial }}
                      </div> --}}
                      <div>
                        <div class="fw-bold text-dark"><a href="{{ route('violations.index', $student->id) }}">{{ $student->name }}</a></div>
                        <div class="small text-muted">{{ $student->nis }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="small fw-medium text-dark">{{ $student->class_group ?? '-' }}</div>
                    <div class="small text-muted">
                      {{ $student->dormitory ? $student->dormitory . ' - ' . $student->room : 'Non-Asrama' }}</div>
                  </td>
                  <td class="text-center" data-order="{{ $student->violations_count }}">
                    <span class="badge bg-light text-dark border rounded-pill px-3">
                      {{ $student->violations_count }} x
                    </span>
                  </td>
                  <td data-order="{{ $student->violations_sum_points ?? 0 }}">
                    @php
                      $points = $student->violations_sum_points ?? 0;
                      $barColor = 'bg-success';
                      $textColor = 'text-success';
                      if ($points > 20) {
                          $barColor = 'bg-warning';
                          $textColor = 'text-warning';
                      }
                      if ($points > 50) {
                          $barColor = 'bg-danger';
                          $textColor = 'text-danger';
                      }
                    @endphp
                    <div class="d-flex align-items-center" style="width: 150px;">
                      <div class="progress flex-grow-1 me-2" style="height: 6px;">
                        <div class="progress-bar {{ $barColor }}" role="progressbar"
                          style="width: {{ min($points, 100) }}%"></div>
                      </div>
                      <span class="fw-bold small {{ $textColor }}">{{ $points }}</span>
                    </div>
                  </td>
                  <td class="text-end pe-4">
                    <a href="{{ route('violations.index', $student->id) }}"
                      class="btn btn-sm btn-outline-primary rounded-pill px-3">
                      Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="60"
                      class="opacity-25 mb-3">
                    <p class="text-muted small">Data santri tidak ditemukan.</p>
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
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      var table = $('#violationTable').DataTable({
        dom: 'rtip', // Hide default controls
        pageLength: 10,
        ordering: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        }
      });
      $('#customSearchBox').on('keyup', function() {
        table.search(this.value).draw();
      });
      $('#customLengthChange').on('change', function() {
        table.page.len(this.value).draw();
      });
    });
  </script>
@endpush
