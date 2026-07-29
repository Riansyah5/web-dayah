@extends('layouts.app')
@section('title', 'Direktori Alumni & Mutasi')
@push('link')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Direktori Alumni & Mutasi</h4>
        <p class="text-muted small">Arsip data santri yang telah lulus atau pindah sekolah.</p>
      </div>
      <button class="btn btn-outline-success btn-sm" id="btnExportExcel">
        <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-body p-3">
        <form action="{{ route('alumni.index') }}" method="GET" class="row g-3">

          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIS..."
              value="{{ request('search') }}">
          </div>

          <div class="col-md-3">
            <select name="year" class="form-select">
              <option value="">-- Semua Tahun --</option>
              @foreach ($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                  Tahun {{ $year }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <select name="category" class="form-select">
              <option value="">-- Semua Status --</option>
              <option value="graduated" {{ request('category') == 'graduated' ? 'selected' : '' }}>Lulus (Alumni)</option>
              <option value="moved" {{ request('category') == 'moved' ? 'selected' : '' }}>Pindah (Mutasi)</option>
              <option value="suspended" {{ request('category') == 'suspended' ? 'selected' : '' }}>Dikeluarkan</option>
            </select>
          </div>

          <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">Filter Data</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">
        <div class="p-3">
          <table id="alumniTable" class="table table-hover align-middle mb-0" style="width:100%">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Nama Santri</th>
                <th>Tahun Keluar</th>
                <th>Status</th>
                <th>Keterangan / Tujuan</th>
                <th class="text-end pe-4">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($alumni as $student)
                <tr>
                  <td class="ps-4">
                    <div class="fw-bold text-dark"><a
                        href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a></div>
                    <small class="text-muted">NIS: {{ $student->nis }}</small>
                  </td>
                  <td>
                    <div class="fw-bold">{{ $student->exitDetail->exit_year ?? '-' }}</div>
                    <small class="text-muted">
                      {{ $student->exitDetail ? $student->exitDetail->exit_date->translatedFormat('d M Y') : '-' }}
                    </small>
                  </td>
                  <td>
                    @php
                      $badges = [
                          'graduated' => 'bg-success',
                          'moved' => 'bg-warning text-dark',
                          'suspended' => 'bg-danger',
                          'deceased' => 'bg-dark',
                      ];
                      $labels = [
                          'graduated' => 'LULUS',
                          'moved' => 'PINDAH',
                          'suspended' => 'DO/KELUAR',
                          'deceased' => 'MENINGGAL',
                      ];
                    @endphp
                    <span class="badge rounded-pill {{ $badges[$student->status] ?? 'bg-secondary' }}">
                      {{ $labels[$student->status] ?? strtoupper($student->status) }}
                    </span>
                  </td>
                  <td>
                    @if ($student->status == 'graduated')
                      <small class="text-muted d-block">No. SK/Ijazah:</small>
                      <span class="fw-bold text-dark">{{ $student->exitDetail->sk_number ?? '-' }}</span>
                    @elseif($student->status == 'moved')
                      <small class="text-muted d-block">Pindah Ke:</small>
                      <span class="fw-bold text-dark">{{ $student->exitDetail->destination ?? '-' }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-end pe-4">
                    <div class="dropdown">
                      <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                          <a class="dropdown-item" href="{{ route('students.show', $student->id) }}">
                            <i class="bi bi-person me-2 text-primary"></i> Lihat Profil
                          </a>
                        </li>
                        @if ($student->status == 'graduated')
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item" href="{{ route('students.exit.print-skl', $student->id) }}"
                              target="_blank">
                              <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Cetak SKL
                            </a>
                          </li>
                        @elseif($student->status == 'moved')
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item" href="{{ route('students.exit.print', $student->id) }}"
                              target="_blank">
                              <i class="bi bi-envelope-paper me-2 text-warning"></i> Cetak Surat Pindah
                            </a>
                          </li>
                        @endif
                      </ul>
                    </div>
                  </td>
                </tr>
              @empty
                {{-- <tr>
                  <td colspan="5" class="text-center py-5">
                    <div class="text-muted opacity-50 mb-2">
                      <i class="bi bi-archive display-4"></i>
                    </div>
                    <h6 class="text-muted">Data alumni tidak ditemukan.</h6>
                  </td>
                </tr> --}}
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  
  {{-- DataTables Buttons & JSZip (Wajib untuk Excel) --}}
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

  <script>
    $(document).ready(function() {
      // Inisialisasi DataTables tanpa fitur-fitur yang bentrok dengan paginasi server
      $('#alumniTable').DataTable({
        responsive: true,
        paging: false,    // Matikan paginasi dari DataTables
        searching: false, // Matikan pencarian dari DataTables
        info: false,      // Matikan info "Showing x to y of z entries"
        ordering: false,  // Matikan sorting dari DataTables
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        }
      });
    });
  </script>
@endpush
