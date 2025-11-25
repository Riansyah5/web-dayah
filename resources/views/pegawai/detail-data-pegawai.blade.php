@extends('layouts.app')
@section('title', 'Detail Data Pegawai')
@push('link')
  <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@push('styles')
  <style>
    @media print {
      body * { visibility: hidden; }
      #printableArea, #printableArea * { visibility: visible; }
      #printableArea { position: absolute; left: 0; top: 0; width: 100%; }
      .no-print { display: none !important; }
    }
  </style>
@endpush
@section('content')
  <div class="container-fluid">
        <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-dark-emphasis">Detail Pegawai</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Kepegawaian</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pegawai</li>
                </ol>
            </nav>
        </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow border-0 sticky-top " style="top: 1.5rem;">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                            {{-- <i class="fas fa-user fa-3x"></i> --}}
                            <img src="https://ui-avatars.com/api/?name={{ $pegawai->nama }}&background=random" alt="Avatar" class="avatar me-3 shadow-sm rounded-circle">
                            </div>
                        
                        <h4 class="mb-1 text-dark-emphasis">{{ $pegawai->nama }}</h4>
                        <p class="text-primary fw-medium mb-1">{{ $pegawai->jabatan }}</p>
                        <p class="text-body-secondary small mb-3">{{ $pegawai->nik }}</p>

                        <div class="d-grid gap-2 no-print">
                            <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-primary">
                                <i class="fas fa-pencil-alt me-1"></i>
                                Edit Data Pegawai
                            </a>
                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary" id="copyBtn" title="Salin ke Clipboard">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="pdfBtn" title="Unduh sebagai PDF">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="window.print()" title="Cetak Halaman">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                            @if($pegawai->user_id != null)
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-pencil-alt me-1"></i>
                                Akun Sudah Dibuat
                            </button>
                            @else
                            <a href="{{ route('tambah-akun', $pegawai->id) }}" class="btn btn-success">
                                <i class="fas fa-pencil-alt me-1"></i>
                                Buat Akun
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer border-0 text-center">
                        <span class="badge bg-success-subtle text-success-emphasis py-2 px-3">
                            <i class="fas fa-check-circle me-1"></i>
                            Status: {{ $pegawai->status_pegawai }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow border-0" id="printableArea">
                    <div class="card-body p-4 p-md-5">
                        <div class="rounded bg-warning d-flex align-items-center p-3 mb-4">
                          <h5 class="text-dark-emphasis mb-0">
                              <i class="fas fa-address-card text-success me-2"></i>
                              Informasi Pribadi
                          </h5>
                        </div>
                        
                        <dl class="row g-3">
                            <dt class="col-sm-4 text-body-secondary">NIK</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->nik }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Nama Lengkap</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->nama }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Jenis Kelamin</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->jenis_kelamin }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Tempat Lahir</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->tempat_lahir }}</dd>
                            
                            <dt class="col-sm-4 text-body-secondary">Tanggal Lahir</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->tanggal_lahir }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Status Perkawinan</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->status_perkawinan }}</dd>
                            
                            <dt class="col-sm-4 text-body-secondary">No. Kartu Keluarga</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->no_kk }}</dd>

                            <dt class="col-sm-4 text-body-secondary">No. HP</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->no_hp }}</dd>
                        </dl>

                        <hr class="my-4">

                        <div class="rounded bg-warning d-flex align-items-center p-3 mb-4">
                          <h5 class="text-dark-emphasis mb-0">
                              <i class="fas fa-briefcase text-primary me-2"></i>
                              Informasi Kepegawaian
                          </h5>
                        </div>
                        
                        <dl class="row g-3">
                            <dt class="col-sm-4 text-body-secondary">Status Pegawai</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->status_pegawai }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Jabatan</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->jabatan }}</dd>

                            <dt class="col-sm-4 text-body-secondary">TMT (Terhitung Mulai Tanggal)</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->terhitung_mulai_tanggal }}</dd>
                        </dl>

                        <hr class="my-4">

                        <div class="rounded bg-warning d-flex align-items-center p-3 mb-4">
                          <h5 class="text-dark-emphasis mb-0">
                              <i class="fas fa-map-marker-alt text-primary me-2"></i>
                              Alamat Domisili
                          </h5>
                        </div>

                        <dl class="row g-3">
                            <dt class="col-sm-4 text-body-secondary">Desa/Kelurahan</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->desa }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Kecamatan</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->kecamatan }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Kabupaten/Kota</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->kabupaten }}</dd>

                            <dt class="col-sm-4 text-body-secondary">Provinsi</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $pegawai->provinsi }}</dd>
                        </dl>
                        
                    </div>
                    <div class="card-footer bg-body-tertiary text-center text-body-secondary small p-3">
                        Data dibuat pada: {{ $pegawai->created_at }} | Terakhir diperbarui: {{ $pegawai->updated_at }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- sweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
    });
    </script>
    @endif
    <!-- Pustaka untuk generate PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!-- Bootstrap JavaScript Libraries -->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>

    {{-- <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"></script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pdfBtn = document.getElementById('pdfBtn');
            const copyBtn = document.getElementById('copyBtn');
            const printableArea = document.getElementById('printableArea');

            // 1. Fungsi Generate PDF
            pdfBtn.addEventListener('click', function() {
                const employeeName = document.querySelector('h4.text-dark-emphasis').textContent.trim();
                const opt = {
                    margin:       0.2,
                    filename:     `detail-pegawai-${employeeName.replace(/, /g, '_').replace(/\./g, '')}.pdf`,
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2, useCORS: true },
                    jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
                };
                // Menggunakan html2pdf untuk mengonversi elemen #printableArea ke PDF
                html2pdf().from(printableArea).set(opt).save();
            });

            // 2. Fungsi Copy ke Clipboard
            copyBtn.addEventListener('click', function() {
                let textToCopy = "DETAIL DATA PEGAWAI\n";
                textToCopy += "=====================\n\n";

                // Mengambil data dari setiap baris <dl>
                const detailLists = printableArea.querySelectorAll('dl.row');
                
                detailLists.forEach((dl, index) => {
                    const title = dl.previousElementSibling;
                    if (title && title.tagName === 'H5') {
                        textToCopy += `${title.innerText.trim().toUpperCase()}\n`;
                        textToCopy += "---------------------\n";
                    }

                    const dts = dl.querySelectorAll('dt');
                    const dds = dl.querySelectorAll('dd');

                    dts.forEach((dt, i) => {
                        const label = dt.textContent.trim();
                        const value = dds[i] ? dds[i].textContent.trim() : 'N/A';
                        textToCopy += `${label}: ${value}\n`;
                    });
                    textToCopy += "\n";
                });

                // Menggunakan Clipboard API untuk menyalin teks
                navigator.clipboard.writeText(textToCopy).then(function() {
                    alert('Data pegawai berhasil disalin ke clipboard!');
                }, function(err) {
                    console.error('Gagal menyalin data: ', err);
                    alert('Gagal menyalin data.');
                });
            });
        });
    </script>
@endpush