@extends('layouts.app')
@section('title', 'Data Pegawai')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet">

  <link
    href="https://cdn.datatables.net/v/bs5/dt-2.0.8/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/r-3.0.2/datatables.min.css"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  

  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      /* background-color: #f4f6f9; */
    }

    .card-modern {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }

    .card-header-modern {
      background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
      padding: 1.5rem;
      border-bottom: none;
    }

    .table-modern thead th {
      background-color: #f8fafc;
      color: #475569;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #e2e8f0;
      padding: 1rem;
    }

    .table-modern tbody td {
      padding: 1rem;
      vertical-align: middle;
      color: #334155;
      border-bottom: 1px solid #f1f5f9;
      font-size: 0.875rem;
    }

    .table-modern tbody tr:hover {
      background-color: #ddddddff !important;
    }

    /* Custom DataTables Elements */
    .dt-buttons .btn {
      border-radius: 8px !important;
      font-size: 0.85rem;
      font-weight: 500;
      margin-right: 5px;
      padding: 0.5rem 1rem;
      transition: all 0.2s;
    }

    /* Fix: Atasi konflik warna teks pada tombol DataTables outline. */
    /* .dt-buttons .btn.dt-button {
      color: var(--bs-btn-color);
    } */

    .btn-action {
      width: 32px;
      height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      transition: transform 0.2s;
      border: none;
    }

    .btn-action:hover {
      transform: translateY(-2px);
    }

    .dataTables_length select {
      border-radius: 8px !important;
      padding: 0.5rem 2.5rem 0.5rem 1rem;
      border: 1px solid #e2e8f0;
      background-position: right 0.75rem center;
    }

    .dataTables_filter input {
      border-radius: 8px !important;
      padding: 0.5rem 1rem;
      border: 1px solid #e2e8f0;
    }

    div.dt-container .dt-paging .dt-paging-button.current {
      background: #4f46e5 !important;
      color: white !important;
      border: none !important;
      border-radius: 8px !important;
    }

    /* Efek hover pada tombol utama */
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3) !important;
        opacity: 0.9;
    }

    /* Mempercantik input file */
    input[type="file"]::file-selector-button {
        background: #f1f3f5;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin-right: 1rem;
        font-weight: 600;
        color: #495057;
        transition: 0.2s;
    }

    input[type="file"]:hover::file-selector-button {
        background: #e9ecef;
    }

    /* Animasi masuk modal */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: scale(0.95);
    }
    .modal.show .modal-dialog {
        transform: scale(1);
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid py-5">
    <div class="row justify-content-center">
      <div class="col-12">

        <div class="card card-modern">
          <div class="card-header-modern d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-white">
              <h4 class="mb-1 fw-bold text-white"><i class="bi bi-people-fill me-2 opacity-75"></i>Data Master Pegawai</h4>
              <p class="mb-0 opacity-75 small">Kelola data pegawai anda dengan mudah dan efisien.</p>
            </div>

            <div class="d-flex gap-2">
              <button type="button" class="btn btn-success fw-semibold px-4 py-2 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import Excel
              </button>
              <a href="{{ route('pegawai.create') }}" class="btn btn-light text-primary fw-semibold px-4 py-2 shadow-sm"
                style="border-radius: 10px;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pegawai
              </a>
            </div>
          </div>

          <div class="card-body p-4">
            <div class="table-responsive">
              <table id="completeTable" class="table table-modern w-100">
                <thead>
                  <tr>
                    <th width="2%">No.</th>
                    <th>Nama Lengkap</th>
                    <th width="2%">L/P</th>
                    <th>Tempat Lahir</th>
                    <th>Tgl. Lahir</th>
                    <th>Jabatan</th>
                    <th>Kategori</th>

                    <th>Status Kawin</th>
                    <th>NIK</th>
                    <th>No. KK</th>
                    <th>Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>TMT</th>
                    <th>Status</th>
                    <th width="10%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($pegawais as $pegawai)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>
                        <div class="d-flex align-items-center">
                          {{-- <div
                            class="avatar-initial rounded-circle bg-soft-primary text-primary fw-bold me-2 d-flex align-items-center justify-content-center"
                            style="width: 35px; height: 35px; background: #e0e7ff;">
                            {{ substr($pegawai->nama, 0, 1) }}
                          </div> --}}
                          <a href="{{ route('pegawai.show', $pegawai->id) }}"
                            class="text-decoration-none fw-semibold ">
                            {{ $pegawai->nama }}
                          </a>
                        </div>
                      </td>
                      <td>{{ $pegawai->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                      <td>{{ $pegawai->tempat_lahir }}</td>
                      <td>{{ $pegawai->tanggal_lahir->format('d/m/Y') }}</td>
                      <td>{{ $pegawai->jabatan }}</td>
                      <td>
                        @php
                          $kategoriClass = match ($pegawai->kategori_pegawai) {
                              'PNS', 'TETAP' => 'bg-success',
                              'HONORER', 'KONTRAK', 'TRAINING' => 'bg-warning text-dark',
                              default => 'bg-secondary',
                          };
                        @endphp
                        <span
                          class="badge {{ $kategoriClass }} bg-opacity-75 rounded-pill px-3">{{ $pegawai->kategori_pegawai }}</span>
                      </td>

                      <td>{{ $pegawai->status_perkawinan }}</td>
                      <td>{{ $pegawai->nik }}</td>
                      <td>{{ $pegawai->no_kk }}</td>
                      <td>{{ $pegawai->desa }}</td>
                      <td>{{ $pegawai->kecamatan }}</td>
                      <td>{{ $pegawai->kabupaten }}</td>
                      <td>{{ $pegawai->provinsi }}</td>
                      <td>{{ $pegawai->terhitung_mulai_tanggal->format('d/m/Y') }}</td>
                      <td>
                        @php
                          $statusClass = match ($pegawai->status_pegawai) {
                              'Aktif' => 'bg-success',
                              'Cuti' => 'bg-warning text-dark',
                              'Non-aktif' => 'bg-secondary',
                              'Keluar' => 'bg-danger',
                              default => 'bg-secondary',
                          };
                        @endphp
                        <span
                          class="badge {{ $statusClass }} bg-opacity-75 rounded-pill px-3">{{ $pegawai->status_pegawai }}</span>
                      </td>
                      <td class="text-center" style="position: relative; z-index: 2;">
                        <div class="btn-group shadow-sm" role="group">
                          <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                            class="btn btn-action btn-light text-warning" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                          <button type="button" class="btn btn-action btn-light text-danger delete-btn"
                            title="Hapus" onclick="confirmDelete(event, '{{ $pegawai->id }}')">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                        <form id="delete-form-{{ $pegawai->id }}" action="{{ route('pegawai.destroy', $pegawai->id) }}"
                          method="POST" class="d-none">
                          @csrf @method('DELETE')
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Modal Import Excel --}}
  {{-- <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-4 border-0">
        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title fw-bold" id="importModalLabel">Import Data Pegawai</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="file" class="form-label small text-muted">Pilih File Excel (.xlsx, .xls)</label>
              <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">
            </div>
            <div class="alert alert-info d-flex align-items-center small" role="alert">
              <i class="bi bi-info-circle me-2 fs-5"></i>
              <div>
                Gunakan template yang telah disediakan agar format data sesuai.
                <a href="{{ route('pegawai.template') }}" class="fw-bold text-decoration-none">Download Template</a>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Upload & Import</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> --}}

  {{-- Modal Import Excel Premium --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            
            <div style="height: 6px; background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);"></div>

            <div class="modal-header border-0 pt-4 px-4">
                <div>
                    <h5 class="modal-title fw-bolder text-dark mb-1" id="importModalLabel" style="letter-spacing: -0.5px;">
                        Import Data Pegawai
                    </h5>
                    <p class="text-muted small mb-0">Perbarui database Anda secara instan.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="file" class="form-label fw-semibold small text-secondary ml-1">Pilih File Excel</label>
                        <div class="input-group custom-file-upload">
                            <input type="file" class="form-control form-control-lg border-2 shadow-none" 
                                   id="file" name="file" required accept=".xlsx, .xls"
                                   style="border-radius: 12px; font-size: 0.95rem; border-style: dashed;">
                        </div>
                    </div>

                    <div class="alert border-0 d-flex align-items-center mb-4" 
                         style="background-color: #f8f9fa; border-radius: 16px; padding: 1.2rem;">
                        <div class="icon-box me-3 bg-white shadow-sm d-flex align-items-center justify-content-center" 
                             style="width: 45px; height: 45px; border-radius: 12px; min-width: 45px;">
                            <i class="bi bi-file-earmark-arrow-down text-primary fs-4"></i>
                        </div>
                        <div class="small">
                            <span class="text-dark d-block fw-bold">Belum punya formatnya?</span>
                            <a href="{{ route('pegawai.template') }}" class="text-primary text-decoration-none fw-semibold">
                                Unduh Template Excel <i class="bi bi-chevron-right small"></i>
                            </a>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary border-0 py-3 fw-bold shadow-sm" 
                                style="border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); transition: all 0.3s ease;">
                            <i class="bi bi-cloud-upload me-2"></i> Mulai Proses Import
                        </button>
                        <button type="button" class="btn btn-link text-muted text-decoration-none small py-2" data-bs-dismiss="modal">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script type="text/javascript"
    src="https://cdn.datatables.net/v/bs5/dt-2.0.8/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/r-3.0.2/datatables.min.js">
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script>
    // Custom Delete Confirmation Function
    function confirmDelete(e, id) {
      e.preventDefault();

      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
          popup: 'rounded-4'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
      });
    }

    $(document).ready(function() {
      var table = $('#completeTable').DataTable({
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4"Bf>rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4"ip>',
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, "Semua"]
        ],
        buttons: [{
            extend: 'pageLength',
            className: 'btn btn-outline-success btn-sm mt-2',
            text: '<i class="bi bi-list-ul me-1"></i> Tampilkan'
          },
          {
            extend: 'copyHtml5',
            text: '<i class="bi bi-clipboard me-1"></i> Copy',
            className: 'btn btn-outline-success btn-sm mt-2'
          },
          {
            extend: 'excelHtml5',
            text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
            className: 'btn btn-outline-success btn-sm mt-2',
            title: 'Data Pegawai'
          },
          {
            extend: 'pdfHtml5',
            text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
            className: 'btn btn-outline-danger btn-sm mt-2',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Data Pegawai'
          },
          {
            extend: 'colvis',
            text: '<i class="bi bi-eye me-1"></i> Tampilan Kolom',
            className: 'btn btn-outline-primary btn-sm mt-2'
          }
        ],
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Cari data pegawai........",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pegawai",
          paginate: {
            first: '<i class="bi bi-chevron-double-left"></i>',
            last: '<i class="bi bi-chevron-double-right"></i>',
            next: '<i class="bi bi-chevron-right"></i>',
            previous: '<i class="bi bi-chevron-left"></i>'
          },
          buttons: {
            colvis: 'Atur Kolom'
          }
        },
        columnDefs: [{
          targets: [7, 8, 9, 10, 11, 12, 13, 14], // Indeks kolom yang ingin disembunyikan (NIK, No. KK, Desa, Kecamatan, Kabupaten, Provinsi, TMT)
          visible: false
        }],
        initComplete: function() {
          // Styling tambahan manual untuk search input agar sejajar
          $('.dt-search input').addClass('form-control form-control-sm');
        }
      });

      // SweetAlert Success Notification
      @if (session('success'))
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        });

        Toast.fire({
          icon: 'success',
          title: '{{ session('success') }}'
        });
      @endif
      // @if (session('success'))
      //   Swal.fire({
      //     icon: 'success',
      //     title: 'Berhasil',
      //     text: '{{ session('success') }}',
      //     timer: 1800,
      //     timerProgressBar: true,
      //     showConfirmButton: false
      //   });
      // @endif

      @if (session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: '{{ session('error') }}',
          customClass: { popup: 'rounded-4' }
        });
      @endif
    });
  </script>
@endpush
