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

            <a href="{{ route('pegawai.create') }}" class="btn btn-light text-primary fw-semibold px-4 py-2 shadow-sm"
              style="border-radius: 10px;">
              <i class="bi bi-plus-lg me-1"></i> Tambah Pegawai
            </a>
          </div>

          <div class="card-body p-4">
            <div class="table-responsive">
              <table id="completeTable" class="table table-modern w-100">
                <thead>
                  <tr>
                    <th width="5%">No.</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>L/P</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th width="10%" class="text-center">Aksi</th>

                    <th>Tempat Lahir</th>
                    <th>Tgl. Lahir</th>
                    <th>Status Kawin</th>
                    <th>No. KK</th>
                    <th>Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>TMT</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($pegawais as $pegawai)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><span class="">{{ $pegawai->nik }}</span></td>
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
                      <td>{{ $pegawai->jabatan }}</td>
                      <td>
                        @php
                          $statusClass = match ($pegawai->status_pegawai) {
                              'PNS', 'Tetap' => 'bg-success',
                              'Honorer', 'Kontrak' => 'bg-warning text-dark',
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
                            title="Hapus" onclick="confirmDelete(event, '{{ route('pegawai.destroy', $pegawai->id) }}')">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                        <form id="delete-form-{{ $pegawai->id }}" action="{{ route('pegawai.destroy', $pegawai->id) }}"
                          method="POST" class="d-none">
                          @csrf @method('DELETE')
                        </form>
                      </td>

                      <td>{{ $pegawai->tempat_lahir }}</td>
                      <td>{{ $pegawai->tanggal_lahir->format('d/m/Y') }}</td>
                      <td>{{ $pegawai->status_perkawinan }}</td>
                      <td>{{ $pegawai->no_kk }}</td>
                      <td>{{ $pegawai->desa }}</td>
                      <td>{{ $pegawai->kecamatan }}</td>
                      <td>{{ $pegawai->kabupaten }}</td>
                      <td>{{ $pegawai->provinsi }}</td>
                      <td>{{ $pegawai->terhitung_mulai_tanggal->format('d/m/Y') }}</td>
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
    function confirmDelete(e, url) { // Note: Ini hanya contoh, sesuaikan dengan logic delete Anda (form submit atau link)
      e.preventDefault();
      // Jika link GET: window.location.href = url;
      // Jika Form Delete (Standard Laravel):
      // document.getElementById('delete-form-' + id).submit();

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
          // Cari form delete terdekat atau redirect
          // window.location.href = url; // Gunakan ini jika delete pakai GET
          // Atau submit form logic di sini jika pakai DELETE method
          alert('Silakan sesuaikan logic submit form delete di script ini sesuai route Anda');
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
            className: 'btn btn-outline-success btn-sm',
            text: '<i class="bi bi-list-ul me-1"></i> Tampilkan'
          },
          {
            extend: 'copyHtml5',
            text: '<i class="bi bi-clipboard me-1"></i> Copy',
            className: 'btn btn-outline-success btn-sm'
          },
          {
            extend: 'excelHtml5',
            text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
            className: 'btn btn-outline-success btn-sm',
            title: 'Data Pegawai'
          },
          {
            extend: 'pdfHtml5',
            text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
            className: 'btn btn-outline-danger btn-sm',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Data Pegawai'
          },
          {
            extend: 'colvis',
            text: '<i class="bi bi-eye me-1"></i> Tampilan Kolom',
            className: 'btn btn-outline-primary btn-sm'
          }
        ],
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Cari data pegawai...",
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
          targets: [7, 8, 9, 10, 11, 12, 13, 14, 15],
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
    });
  </script>
@endpush
