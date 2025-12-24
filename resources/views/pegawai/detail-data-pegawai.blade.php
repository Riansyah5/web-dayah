@extends('layouts.app')
@section('title', 'Detail Data Pegawai')

@push('link')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endpush

@push('styles')
  <style>
    :root {
      --primary-soft: #eef2ff;
      --accent-color: #4f46e5;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
    }

    .card {
      border-radius: 16px;
      transition: transform 0.2s ease;
    }

    /* Modern Profile Card */
    .profile-card {
      background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
    }

    .avatar-wrapper {
      position: relative;
      padding: 5px;
      background: white;
      border-radius: 50%;
      display: inline-block;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .section-header {
      border-left: 4px solid var(--accent-color);
      background: var(--primary-soft);
      padding: 12px 16px;
      border-radius: 0 8px 8px 0;
      margin-bottom: 1.5rem;
    }

    /* List styling */
    dt {
      color: #64748b;
      font-weight: 500;
      font-size: 0.9rem;
    }

    dd {
      color: #1e293b;
      font-weight: 600;
    }

    /* Print Styles */
    @media print {
      body * {
        visibility: hidden;
      }

      #printableArea,
      #printableArea * {
        visibility: visible;
      }

      #printableArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
      }

      .no-print {
        display: none !important;
      }
    }

    /* Button hover effect */
    .btn {
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-group .btn {
      padding: 8px 15px;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid py-4">
    <div class="no-print mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="mb-3">
            <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm rounded p-2">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
          <h1 class="h3 fw-bold text-dark mb-1">Detail Pegawai</h1>
          <p class="text-muted">Manajemen informasi profil karyawan secara lengkap.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-md-end bg-transparent p-0">
              <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
              <li class="breadcrumb-item active">Profil Pegawai</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0 profile-card sticky-top" style="top: 1.5rem;">
          <div class="card-body p-4 text-center">
            <div class="avatar-wrapper mb-4">
              <img
                src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&background=4f46e5&color=fff&size=128"
                alt="Avatar" class="rounded-circle" width="110" height="110">
            </div>

            <h2 class="fw-bold text-dark mb-1">{{ $pegawai->nama }}</h2>
            <span class="badge bg-primary-subtle text-primary px-3 py-2 mb-3">{{ $pegawai->jabatan }}</span>
            <p class="text-muted small"><i class="far fa-id-badge me-1"></i> NIK: {{ $pegawai->nik }}</p>

            <hr class="my-4 opacity-50">

            <div class="d-grid gap-3 no-print">
              <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Profil
              </a>

              <div class="row g-2">
                <div class="col-4">
                  <button type="button" class="btn btn-outline-secondary w-100" id="copyBtn" title="Salin">
                    <i class="fas fa-copy"></i> Copy
                  </button>
                </div>
                <div class="col-4">
                  <button type="button" class="btn btn-outline-danger w-100" id="pdfBtn" title="PDF">
                    <i class="fas fa-file-pdf"></i> Pdf
                  </button>
                </div>
                <div class="col-4">
                  <button type="button" class="btn btn-outline-warning w-100" onclick="window.print()" title="Print">
                    <i class="fas fa-print"></i> Print
                  </button>
                </div>
              </div>

              @if ($pegawai->user_id)
                <button class="btn btn-light text-success fw-bold" disabled>
                  <i class="fas fa-check-circle me-2"></i>Akun Terhubung
                </button>
              @else
                <a href="{{ route('tambah-akun', $pegawai->id) }}" class="btn btn-success">
                  <i class="fas fa-user-plus me-2"></i>Aktivasi Akun
                </a>
              @endif
            </div>
          </div>
          <div class="card-footer bg-white border-0 text-center pb-4">
            <div class="p-2 bg-success-subtle rounded-3">
              <small class="text-success fw-bold">Status: {{ $pegawai->status_pegawai }}</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0" id="printableArea">
          <div class="card-body p-4 p-md-5">

            <div class="section-header">
              <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-user-circle me-2 text-primary"></i>Informasi Pribadi
              </h5>
            </div>

            <div class="row g-3 px-2">
              <div class="col-sm-6">
                <dt>NIK</dt>
                <dd>{{ $pegawai->nik }}</dd>
              </div>
              <div class="col-sm-6">
                <dt>Nama Lengkap</dt>
                <dd>{{ $pegawai->nama }}</dd>
              </div>
              <div class="col-sm-6">
                <dt>Jenis Kelamin</dt>
                <dd>{{ $pegawai->jenis_kelamin }}</dd>
              </div>
              <div class="col-sm-6">
                <dt>Tempat, Tanggal Lahir</dt>
                <dd>{{ $pegawai->tempat_lahir }}, {{ $pegawai->tanggal_lahir }}</dd>
              </div>
              <div class="col-sm-6">
                <dt>No. Kartu Keluarga</dt>
                <dd>{{ $pegawai->no_kk }}</dd>
              </div>
              <div class="col-sm-6">
                <dt>No. WhatsApp</dt>
                <dd>{{ $pegawai->no_hp }}</dd>
              </div>
            </div>

            <div class="section-header mt-5">
              <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-briefcase me-2 text-primary"></i>Pekerjaan
              </h5>
            </div>
            <div class="row g-3 px-2">
              <div class="col-sm-6">
                <dt>Status Pegawai</dt>
                <dd>{{ $pegawai->status_pegawai }}</dd>
              </div>
              <div class="col-sm-6">
                <dt>Jabatan</dt>
                <dd>{{ $pegawai->jabatan }}</dd>
              </div>
              <div class="col-sm-6">
                <dt>TMT (Terhitung Mulai Tanggal)</dt>
                <dd><i class="far fa-calendar-alt me-1 text-muted"></i> {{ $pegawai->terhitung_mulai_tanggal }}</dd>
              </div>
            </div>

            <div class="section-header mt-5">
              <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-map-marked-alt me-2 text-primary"></i>Domisili
              </h5>
            </div>
            <div class="row g-3 px-2">
              <div class="col-12">
                <dt>Alamat Lengkap</dt>
                <dd>Desa {{ $pegawai->desa }}, Kec. {{ $pegawai->kecamatan }}, {{ $pegawai->kabupaten }},
                  {{ $pegawai->provinsi }}</dd>
              </div>
            </div>

          </div>
          <div class="card-footer bg-light border-0 text-center py-3">
            <small class="text-muted italic">
              Terakhir diperbarui: {{ $pegawai->updated_at->format('d M Y H:i') }}
            </small>
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
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

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
        const nameEl = document.querySelector('.profile-card h4');
        const employeeName = nameEl ? nameEl.textContent.trim() : 'Pegawai';
        const opt = {
          margin: 0.2,
          filename: `detail-pegawai-${employeeName.replace(/, /g, '_').replace(/\./g, '')}.pdf`,
          image: {
            type: 'jpeg',
            quality: 0.98
          },
          html2canvas: {
            scale: 2,
            useCORS: true
          },
          jsPDF: {
            unit: 'in',
            format: 'a4',
            orientation: 'portrait'
          }
        };
        // Menggunakan html2pdf untuk mengonversi elemen #printableArea ke PDF
        html2pdf().from(printableArea).set(opt).save();
      });

      // 2. Fungsi Copy ke Clipboard
      copyBtn.addEventListener('click', function() {
        let textToCopy = "DETAIL DATA PEGAWAI\n";
        textToCopy += "=====================\n\n";

        // Mengambil data berdasarkan section header dan row berikutnya
        const headers = printableArea.querySelectorAll('.section-header');

        headers.forEach(header => {
          // Judul Section
          textToCopy += `${header.innerText.trim().toUpperCase()}\n`;
          textToCopy += "---------------------\n";

          const row = header.nextElementSibling;
          if (row && row.classList.contains('row')) {
            const dts = row.querySelectorAll('dt');
            const dds = row.querySelectorAll('dd');

            dts.forEach((dt, i) => {
              const label = dt.textContent.trim();
              const value = dds[i] ? dds[i].textContent.replace(/\s+/g, ' ').trim() : '-';
              textToCopy += `${label}: ${value}\n`;
            });
          }
          textToCopy += "\n";
        });

        // Menggunakan Clipboard API untuk menyalin teks
        navigator.clipboard.writeText(textToCopy).then(function() {
          // alert('Data pegawai berhasil disalin ke clipboard!');
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data pegawai berhasil disalin ke clipboard!',
            timer: 2000,
            showConfirmButton: false
          });
        }, function(err) {
          console.error('Gagal menyalin data: ', err);
          // alert('Gagal menyalin data.');
					Swal.fire({
            icon: 'failure',
            title: 'Gagal',
            text: 'Gagal menyalin data.',
            timer: 2000,
            showConfirmButton: false
          });
        });
      });
    });
  </script>
@endpush
