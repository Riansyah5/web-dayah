@extends('layouts.app')
@section('title', 'Students')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  {{-- 1. CSS DataTables & Bootstrap 5 --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush
@push('styles')
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

    body {
      font-family: 'Inter', sans-serif;
      {{-- background-color: #f4f6f9; --}}
    }

    .card-modern {
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .avatar-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 14px;
      color: #fff;
      margin-right: 12px;
    }

    .badge-soft-success {
      background-color: #d1fae5;
      color: #065f46;
    }

    .badge-soft-warning {
      background-color: #fef3c7;
      color: #92400e;
    }

    .badge-soft-danger {
      background-color: #fee2e2;
      color: #991b1b;
    }

    .badge-soft-primary {
      background-color: #dbeafe;
      color: #1e40af;
    }

    .badge-soft-secondary {
      background-color: #f3f4f6;
      color: #374151;
    }

    .btn-icon {
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
    }

    /* Menyembunyikan elemen default DataTables agar kita bisa pakai UI Custom */
    .dataTables_filter,
    .dataTables_length {
      display: none;
    }

    /* Custom Styling Pagination DataTables */
    .page-item.active .page-link {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .dataTables_info {
      color: #6c757d;
      font-size: 0.875rem;
      margin-top: 1rem;
    }

    /* Fix SweetAlert z-index over Bootstrap Modal */
    .swal2-container {
      z-index: 2000 !important;
    }
  </style>
@endpush

@section('content')

  <div class="container py-4">
    <div class="row justify-content-center">

      <div class="col-12 col-xl-11">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="mb-1 fw-bold text-dark">Data Santri</h4>
            <p class="text-muted mb-0 small">Manajemen data seluruh santri aktif dan alumni.</p>
          </div>
          <div class="d-flex gap-2">

            {{-- Dropdown Export (Akan mentrigger fungsi JS) --}}
            <div class="dropdown">
              <button class="btn btn-outline-success dropdown-toggle shadow-sm rounded-3" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download me-2"></i>Export
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('students.export', ['format' => 'xlsx']) }}"><i
                      class="bi bi-file-earmark-excel text-success me-2"></i>Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('students.export', ['format' => 'csv']) }}"><i
                      class="bi bi-filetype-csv text-primary me-2"></i>CSV</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#" id="btnExportPrint"><i
                      class="bi bi-printer text-secondary me-2"></i>Print</a></li>
              </ul>
            </div>

            <button type="button" class="btn btn-success text-white rounded-3 shadow-sm" data-bs-toggle="modal"
              data-bs-target="#importModal">
              <i class="bi bi-file-earmark-spreadsheet me-2"></i>Import
            </button>

            <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#checkNisnModal">
              <i class="bi bi-plus-lg me-2"></i>Tambah
            </button>
          </div>
        </div>

        <div class="card card-modern mb-4">
          <div class="card-body p-3">
            <div class="row g-3 align-items-center">
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3">
                    <i class="bi bi-search"></i>
                  </span>
                  {{-- Input Search Custom --}}
                  <input type="text" id="customSearchBox" class="form-control border-start-0 ps-2 rounded-end-3"
                    placeholder="Cari Nama, NIS, dll...">
                </div>
              </div>
              <div class="col-md-3">
                {{-- Filter Status Custom --}}
                <select id="customStatusFilter" class="form-select rounded-3">
                  <option value="">Semua Status</option>
                  <option value="Aktif">Aktif</option>
                  <option value="Lulus">Lulus</option>
                  <option value="Pindah">Pindah</option>
                  <option value="Skorsing">Skorsing</option>
                </select>
              </div>
              <div class="col-md-2">
                {{-- Length Change Custom --}}
                <select id="customLengthChange" class="form-select rounded-3">
                  <option value="10">10 Baris</option>
                  <option value="25">25 Baris</option>
                  <option value="50">50 Baris</option>
                  <option value="-1">Semua</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-modern overflow-hidden">
          <div class="card-body p-0">
            <div class="table-responsive">
              {{-- ID Table ditambahkan --}}
              <table id="myTable" class="table table-hover align-middle mb-0 w-100">
                <thead class="bg-light text-muted small text-uppercase">
                  <tr>
                    <th class="ps-4 py-3 border-0 rounded-start">No</th>
                    <th class="py-3 border-0 text-center">Nama Santri</th>
                    <th class="py-3 border-0 text-center">NISN/NIS</th>
                    <th class="py-3 border-0 text-center">Tempat/Tgl Lahir</th>
                    <th class="py-3 border-0 text-center">Asrama/Kelas</th>
                    <th class="py-3 border-0 text-center">Jenjang</th>
                    <th class="py-3 border-0 text-center">Status</th>
                    <th class="py-3 border-0 text-end pe-4 rounded-end" style="min-width: 100px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($students as $student)
                    <tr>
                      <td class="ps-4 py-3">{{ $loop->iteration }}</td>
                      <td class="ps-4 py-3">
                        <div class="d-flex align-items-center">
                          {{-- @php
                            $colors = ['#4F46E5', '#059669', '#D97706', '#DC2626', '#7C3AED'];
                            $bg_color = $colors[rand(0, 4)];
                            $initial = strtoupper(substr($student->name, 0, 1));
                          @endphp
                          <div class="avatar-circle shadow-sm" style="background-color: {{ $bg_color }};">
                            {{ $initial }}
                          </div> --}}
                          <div>
                            <div class="fw-bold text-dark student-name"><a href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a></div>
                            <div class="small text-muted">
                              {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </div>
                          </div>
                        </div>
                      </td>

                      <td>
                        <div class="fw-semibold text-dark">{{ $student->nisn ?? '-' }}</div>
                        <span class="small text-muted">{{ $student->nis }}</span>
                      </td>
                      <td>
                        {{ $student->birth_place ?? '-' }},
                        {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->locale('id')->translatedFormat('d F Y') : '-' }}
                      </td>
                      <td>
                        {{ $student->dormitory ?? 'Non-Mukim' }}
                        <div class="small text-muted">Kamar: {{ $student->room ?? '-' }}</div>
                      </td>
                      <td>{{ $student->education_level ?? '-' }}</td>
                      <td>
                        @php
                          $statusBadge = match ($student->status) {
                              'active' => 'badge-soft-success',
                              'graduated' => 'badge-soft-primary',
                              'suspended' => 'badge-soft-danger',
                              default => 'badge-soft-secondary',
                          };
                          $statusText = match ($student->status) {
                              'active' => 'Aktif',
                              'graduated' => 'Lulus',
                              'suspended' => 'Skorsing',
                              'moved' => 'Pindah',
                              default => $student->status,
                          };
                        @endphp
                        {{-- Text di dalam span ini yang akan dibaca oleh Filter Search --}}
                        <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2">{{ $statusText }}</span>
                      </td>

                      <td class="text-end pe-4">
                        {{-- Action buttons (No changes needed) --}}
                        <div class="btn-group" role="group">
                          <a href="{{ route('students.show', $student->id) }}"
                            class="btn btn-sm btn-light text-primary border" title="Detail">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="{{ route('students.edit', $student->id) }}"
                            class="btn btn-sm btn-light text-warning border" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                          <form action="{{ route('students.destroy', $student->id) }}" method="post"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                              class="btn btn-sm btn-light text-danger border delete-btn rounded-start-0" title="Hapus">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          {{-- Tempat Pagination DataTables akan muncul --}}
          <div class="card-footer bg-white border-0 py-3">
            <div id="customPagination" class="d-flex justify-content-between align-items-center">
              {{-- Info dan Paging akan dirender disini via JS --}}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Cek NISN --}}
    <div class="modal fade" id="checkNisnModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Cek Ketersediaan NISN</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <form id="checkNisnForm">
              <div class="mb-3">
                <label for="checkNisnInput" class="form-label fw-semibold">Masukkan NISN</label>
                <input type="text" class="form-control form-control-lg" id="checkNisnInput" required placeholder="Nomor Induk Siswa Nasional">
                <div class="form-text text-muted">Sistem akan mengecek apakah NISN sudah terdaftar.</div>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Cek & Lanjut</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Import Data Santri</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">

            <div class="alert alert-light border-start border-4 border-info text-muted small" role="alert">
              <i class="bi bi-info-circle me-1"></i>
              Pastikan format file sesuai. Kolom <strong>NIS</strong> wajib unik. Gunakan template di bawah ini.
            </div>

            <div class="d-grid mb-4">
              <a href="{{ route('students.template') }}" class="btn btn-outline-success border-dashed">
                <i class="bi bi-download me-2"></i> Download Template Excel/CSV
              </a>
            </div>

            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-4">
                <label for="fileImport" class="form-label fw-semibold">Upload File (.xlsx / .csv)</label>
                <input class="form-control form-control-lg" type="file" id="fileImport" name="file" required
                  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4">Import Data</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <style>
      /* Styling khusus untuk tombol download template */
      .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
      }

      .border-dashed:hover {
        background-color: #f0fdf4;
        /* Light green hover */
      }
    </style>
  </div>

  {{-- @include('components.import-modal')  --}}
@endsection

@push('scripts')
  {{-- sweetAlert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  {{-- 2. JS DataTables Libraries --}}
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

  {{-- Library untuk Export --}}
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> {{-- Penting untuk Excel --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

  <script>
    // Notifikasi Sukses
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @elseif (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: '{{ session('error') }}',
        showConfirmButton: true
      });
    @endif

    $(document).ready(function() {
      // Inisialisasi DataTable
      var table = $('#myTable').DataTable({
        dom: 'Brtip', // Layout: Buttons, Processing, Table, Info, Pagination
        buttons: [{
            extend: 'excel',
            className: 'd-none',
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          }, // Hide default button
          {
            extend: 'csv',
            className: 'd-none',
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          },
          {
            extend: 'print',
            className: 'd-none',
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          }
        ],
        paging: true,
        pageLength: 10,
        ordering: true,
        info: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" // Bahasa Indonesia
        },
        // Memindahkan element pagination ke div custom kita
        initComplete: function() {
          $('.dataTables_info').appendTo('#customPagination');
          $('.dataTables_paginate').appendTo('#customPagination').addClass('ms-auto');
        }
      });

      // 1. Konek Custom Search ke DataTables
      $('#customSearchBox').on('keyup', function() {
        table.search(this.value).draw();
      });

      $('#btnExportPrint').on('click', function() {
        table.button('.buttons-print').trigger();
      });

      // 3. Konek Custom Filter Status
      $('#customStatusFilter').on('change', function() {
        table.column(6).search(this.value).draw(); // Kolom index 6 adalah Status
      });

      // 4. Konek Custom Page Length
      $('#customLengthChange').on('change', function() {
        table.page.len(this.value).draw();
      });

      // Script Check NISN
      $('#checkNisnModal').on('shown.bs.modal', function () {
        $('#checkNisnInput').focus();
      });

      $('#checkNisnForm').on('submit', function(e) {
        e.preventDefault();
        var nisn = $('#checkNisnInput').val().trim();
        // Ambil daftar NISN dari data students yang ada di view (Client-side check)
        var existingNisns = @json($students->pluck('nisn')->filter()->values());

        if (existingNisns.includes(nisn)) {
          Swal.fire({
            icon: 'error',
            title: 'Data Sudah Ada',
            text: 'NISN ' + nisn + ' sudah terdaftar dalam sistem.',
            confirmButtonColor: '#d33'
          });
        } else {
          // Redirect ke halaman create dengan membawa parameter NISN
          window.location.href = "{{ route('students.create') }}?nisn=" + encodeURIComponent(nisn);
        }
      });
    });

    // Script untuk konfirmasi hapus (Event Delegation untuk DataTables)
    $(document).on('click', '.delete-btn', function(e) {
      e.preventDefault();
      var form = $(this).closest('form');
      var name = $(this).closest('tr').find('.student-name').text();

      Swal.fire({
        title: 'Hapus Data Santri?',
        html: `Data santri <strong>${name}</strong> akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // Jika dikonfirmasi, submit form
        }
      });
    });
  </script>
@endpush
