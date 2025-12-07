@extends('layouts.app')
@section('title', 'Pegawai')
@push('link')
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <link
    href="https://cdn.datatables.net/v/bs5/dt-2.0.8/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/r-3.0.2/datatables.min.css"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

@section('content')
  <div class="container-fluid my-5">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-header card-header-custom p-3 d-flex justify-content-between align-items-center bg-primary">
            <h4 class="mb-0 text-white"><i class="bi bi-people-fill me-2"></i>Data Master Pegawai</h4>

            <a href="{{ route('pegawai.create') }}" class="btn btn-light ms-auto">
              <i class="bi bi-plus-circle-fill me-1"></i> Tambah Pegawai
            </a>
          </div>
          <div class="card-body">

            <table id="completeTable" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Jenis Kelamin</th>
                  <th>Jabatan</th>
                  <th>Status Pegawai</th>
                  <th>Aksi</th>

                  <th>Tempat Lahir</th>
                  <th>Tgl. Lahir</th>
                  <th>Status Perkawinan</th>
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
                    <td>{{ $pegawai->nik }}</td>
                    <td><a href="{{ route('pegawai.show', $pegawai->id) }}">{{ $pegawai->nama }}</a></td>
                    <td>{{ $pegawai->jenis_kelamin }}</td>
                    <td>{{ $pegawai->jabatan }}</td>
                    <td>{{ $pegawai->status_pegawai }}</td>

                    <td class="text-center">
                      <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-icon btn-light text-primary" title="Edit"><i
                          class="bi bi-pencil-square"></i></a>
                      <a href="#" class="btn btn-icon btn-light text-danger delete-btn" title="Hapus"
                        onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash-fill"></i></a>
                    </td>

                    <td>{{ $pegawai->tempat_lahir }}</td>
                    <td>{{ $pegawai->tanggal_lahir->format('d-m-Y') }}</td>
                    <td>{{ $pegawai->status_perkawinan }}</td>
                    <td>{{ $pegawai->no_kk }}</td>
                    <td>{{ $pegawai->desa }}</td>
                    <td>{{ $pegawai->kecamatan }}</td>
                    <td>{{ $pegawai->kabupaten }}</td>
                    <td>{{ $pegawai->provinsi }}</td>
                    <td>{{ $pegawai->terhitung_mulai_tanggal->format('d-m-Y') }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>No.</th>
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Jenis Kelamin</th>
                  <th>Jabatan</th>
                  <th>Status Pegawai</th>
                  <th>Aksi</th>

                  <th>Tempat Lahir</th>
                  <th>Tgl. Lahir</th>
                  <th>Status Perkawinan</th>
                  <th>No. KK</th>
                  <th>Desa</th>
                  <th>Kecamatan</th>
                  <th>Kabupaten</th>
                  <th>Provinsi</th>
                  <th>TMT</th>
                </tr>
              </tfoot>
            </table>

          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
@push('scripts')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js">
  </script>
  <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>

  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js">
  </script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js">
  </script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js">
  </script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js">
  </script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js">
  </script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#completeTable').DataTable({
        // Konfigurasi tombol Buttons
        dom: 'lBfrtip', // Menentukan posisi elemen: Length, Buttons, Filter, processing, info, pagination
        buttons: [
          'copyHtml5', // Tombol Copy
          'excelHtml5', // Tombol Export Excel
          'csvHtml5', // Tombol Export CSV
          'pdfHtml5', // Tombol Export PDF
          'colvis' // Tombol Column Visibility
        ],
        // Menggunakan Bootstrap 5 untuk styling
        responsive: true,
        language: {
          // Opsional: Menggunakan bahasa Indonesia
          url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/id.json',
          buttons: {
            colvis: 'Sembunyikan Kolom' // Ganti teks tombol Column Visibility
          }
        },
        // Menentukan kolom mana yang akan disembunyikan secara default
        columnDefs: [{
          targets: [7, 8, 9, 10, 11, 12, 13, 14, 15], // Indeks kolom dimulai dari 0
          visible: false
        }]
      });
      
      @if (session('success'))
        Swal.fire({
          position: 'top',
          title: 'Berhasil!',
          text: '{{ session('success') }}', // Ambil pesan dari session
          icon: 'success',
          showConfirmButton: false,
          timer: 1500 // Durasi pesan dalam milidetik
        });
      @endif
    });
  </script>
@endpush
