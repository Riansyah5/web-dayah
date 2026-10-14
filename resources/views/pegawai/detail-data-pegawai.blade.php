@extends('layouts.app')
@section('title', 'Detail Data Pegawai')

@push('link')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@push('styles')
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f8fafc;
      color: #334155;
    }

    /* Modern Card Container */
    .card-modern {
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 20px;
      background: #ffffff;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
      overflow: hidden;
    }

    /* Profile Header Accent */
    .profile-cover {
      height: 90px;
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 50%, #06b6d4 100%);
      position: relative;
    }

    .avatar-wrapper {
      position: relative;
      margin-top: -50px;
      display: inline-block;
    }

    .avatar-img {
      width: 104px;
      height: 104px;
      border-radius: 50%;
      border: 4px solid #ffffff;
      box-shadow: 0 10px 20px -3px rgba(79, 70, 229, 0.2);
      object-fit: cover;
      background: #ffffff;
    }

    /* Section Subheaders */
    .section-badge-header {
      display: flex;
      align-items: center;
      gap: 10px;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid #f1f5f9;
      margin-bottom: 1rem;
    }

    .section-icon-box {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
    }

    /* Data Tile / Info Box */
    .info-tile {
      background: #f8fafc;
      border: 1px solid #edf2f7;
      border-radius: 14px;
      padding: 0.85rem 1rem;
      height: 100%;
      transition: all 0.2s ease;
    }

    .info-tile:hover {
      background: #ffffff;
      border-color: #cbd5e1;
      box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
      transform: translateY(-1px);
    }

    .info-label {
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      margin-bottom: 0.25rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .info-value {
      font-size: 0.95rem;
      font-weight: 700;
      color: #0f172a;
      word-break: break-word;
      margin-bottom: 0;
    }

    /* Status Badges */
    .badge-soft-success { background-color: #dcfce7; color: #15803d; }
    .badge-soft-warning { background-color: #fef9c3; color: #a16207; }
    .badge-soft-danger  { background-color: #fee2e2; color: #b91c1c; }
    .badge-soft-info    { background-color: #e0f2fe; color: #0369a1; }
    .badge-soft-secondary { background-color: #f1f5f9; color: #475569; }

    /* Button Enhancements */
    .btn-action-mobile {
      font-weight: 600;
      border-radius: 12px;
      padding: 0.65rem 1rem;
      font-size: 0.875rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }

    .btn-action-mobile:hover {
      transform: translateY(-2px);
    }

    /* Media Print Optimization */
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
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
      }
      .no-print {
        display: none !important;
      }
    }

    /* Mobile Refinements */
    @media (max-width: 767.98px) {
      .action-grid-buttons {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
      }
      .info-tile {
        padding: 0.75rem 0.85rem;
      }
      .info-value {
        font-size: 0.9rem;
      }
    }
  </style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">

  {{-- Top Navigation & Action --}}
  <div class="no-print mb-3 mb-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('pegawai.index') }}" class="btn btn-white border shadow-sm rounded-3 py-2 px-3 text-secondary">
          <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <div>
          <h4 class="fw-bolder text-dark mb-0 fs-5 fs-md-4">Detail Data Pegawai</h4>
          <span class="text-muted small">Informasi lengkap biodata dan status kepegawaian.</span>
        </div>
      </div>

      <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
          <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}" class="text-decoration-none text-muted">Pegawai</a></li>
          <li class="breadcrumb-item active text-primary fw-semibold">Detail</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row g-3 g-md-4">
    {{-- Left Column: Profile Card & Quick Action --}}
    <div class="col-xl-4 col-lg-5">
      <div class="card-modern sticky-top" style="top: 1.5rem; z-index: 10;">
        <div class="profile-cover"></div>

        <div class="card-body px-3 px-md-4 pt-0 pb-4 text-center">
          <div class="avatar-wrapper mb-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&background=4f46e5&color=fff&size=160&bold=true"
                 alt="Avatar {{ $pegawai->nama }}" class="avatar-img">
          </div>

          <h5 class="fw-bold text-dark mb-1">{{ $pegawai->nama }}</h5>
          <div class="d-flex justify-content-center flex-wrap gap-1 mb-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold">
              <i class="fas fa-briefcase me-1"></i> {{ $pegawai->jabatan }}
            </span>
            @php
              $statusBadge = match(strtolower($pegawai->status_pegawai ?? '')) {
                'aktif' => 'badge-soft-success',
                'cuti' => 'badge-soft-warning',
                'keluar' => 'badge-soft-danger',
                default => 'badge-soft-secondary',
              };
            @endphp
            <span class="badge {{ $statusBadge }} rounded-pill px-3 py-1 fw-semibold">
              ● {{ $pegawai->status_pegawai }}
            </span>
          </div>

          <div class="text-muted small mb-3">
            <span class="d-inline-block px-2 py-1 bg-light rounded-3 text-secondary">
              <i class="far fa-id-card me-1 text-primary"></i> NIK: <strong>{{ $pegawai->nik ?? '-' }}</strong>
            </span>
          </div>

          <hr class="my-3 opacity-25">

          {{-- Action Button Group --}}
          <div class="no-print d-flex flex-column gap-2">
            <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-primary btn-action-mobile shadow-sm w-100 py-2">
              <i class="fas fa-user-edit me-2"></i> Edit Data Pegawai
            </a>

            <div class="action-grid-buttons">
              <button type="button" class="btn btn-light border btn-action-mobile text-secondary" id="copyBtn" title="Salin Data">
                <i class="fas fa-copy me-md-2 text-primary"></i> <span class="d-none d-md-inline">Salin</span>
              </button>
              <button type="button" class="btn btn-light border btn-action-mobile text-secondary" id="pdfBtn" title="Download PDF">
                <i class="fas fa-file-pdf me-md-2 text-danger"></i> <span class="d-none d-md-inline">PDF</span>
              </button>
              <button type="button" class="btn btn-light border btn-action-mobile text-secondary" onclick="window.print()" title="Cetak Dokumen">
                <i class="fas fa-print me-md-2 text-warning"></i> <span class="d-none d-md-inline">Cetak</span>
              </button>
            </div>

            @if (!empty($pegawai->user_id))
              <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small fw-semibold text-center mt-1">
                <i class="fas fa-shield-alt me-1"></i> Akun Sistem Terhubung
              </div>
            @else
              <a href="{{ route('tambah-akun', $pegawai->id) }}" class="btn btn-outline-success btn-action-mobile w-100 mt-1">
                <i class="fas fa-user-plus me-2"></i> Aktivasi Akun Pegawai
              </a>
            @endif
          </div>
        </div>

        <div class="card-footer bg-light border-0 py-2 text-center">
          <small class="text-muted" style="font-size: 0.75rem;">
            Pembaruan terakhir: {{ $pegawai->updated_at ? $pegawai->updated_at->diffForHumans() : '-' }}
          </small>
        </div>
      </div>
    </div>

    {{-- Right Column: Information Body --}}
    <div class="col-xl-8 col-lg-7">
      <div class="card-modern" id="printableArea">
        <div class="card-body p-3 p-md-4">

          {{-- Section 1: Informasi Pribadi --}}
          <div class="section-badge-header">
            <span class="section-icon-box bg-primary-subtle text-primary">
              <i class="fas fa-user"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark">Informasi Pribadi</h6>
          </div>

          <div class="row g-2 g-md-3 mb-4">
            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-id-card text-muted"></i> Nomor Induk Kependudukan (NIK)</div>
                <div class="info-value font-monospace">{{ $pegawai->nik ?? '-' }}</div>
              </div>
            </div>

            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-signature text-muted"></i> Nama Lengkap</div>
                <div class="info-value">{{ $pegawai->nama ?? '-' }}</div>
              </div>
            </div>

            <div class="col-6 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-venus-mars text-muted"></i> Jenis Kelamin</div>
                <div class="info-value">{{ $pegawai->jenis_kelamin ?? '-' }}</div>
              </div>
            </div>

            <div class="col-6 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-heart text-muted"></i> Status Perkawinan</div>
                <div class="info-value">{{ $pegawai->status_perkawinan ?? '-' }}</div>
              </div>
            </div>

            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-birthday-cake text-muted"></i> Tempat, Tanggal Lahir</div>
                <div class="info-value">
                  {{ $pegawai->tempat_lahir ?? '-' }},
                  @if($pegawai->tanggal_lahir)                     {{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}
                    <span class="text-primary small fw-semibold">({{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->age }} Thn)</span>
                  @else
                    -
                  @endif
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-users text-muted"></i> No. Kartu Keluarga (KK)</div>
                <div class="info-value font-monospace">{{ $pegawai->no_kk ?? '-' }}</div>
              </div>
            </div>

            <div class="col-12">
              <div class="info-tile">
                <div class="info-label"><i class="fab fa-whatsapp text-success"></i> Kontak WhatsApp / HP</div>
                <div class="info-value">
                  @if(!empty($pegawai->no_hp))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pegawai->no_hp) }}" target="_blank"
                       class="text-success text-decoration-none d-inline-flex align-items-center gap-2">
                      <i class="fab fa-whatsapp fs-5"></i>
                      <span>{{ preg_replace('/(\d{2})(\d{3})(\d{4})(\d+)/', '$1 $2 $3 $4', $pegawai->no_hp) }}</span>
                      <i class="fas fa-arrow-up-right-from-square small text-muted"></i>
                    </a>
                  @else
                    <span class="text-muted fst-italic">Belum dicantumkan</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Section 2: Pekerjaan & Status Pegawai --}}
          <div class="section-badge-header">
            <span class="section-icon-box bg-success-subtle text-success">
              <i class="fas fa-briefcase"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark">Data Jabatan & Kepegawaian</h6>
          </div>

          <div class="row g-2 g-md-3 mb-4">
            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-user-tag text-muted"></i> Jabatan Utama</div>
                <div class="info-value text-primary">{{ $pegawai->jabatan ?? '-' }}</div>
              </div>
            </div>

            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-layer-group text-muted"></i> Kategori Pegawai</div>
                <div class="info-value">{{ $pegawai->kategori_pegawai ?? '-' }}</div>
              </div>
            </div>

            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-toggle-on text-muted"></i> Status Keaktifan</div>
                <div class="info-value">
                  <span class="badge {{ $statusBadge }} rounded-pill px-2 py-1">
                    {{ $pegawai->status_pegawai ?? '-' }}
                  </span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6">
              <div class="info-tile">
                <div class="info-label"><i class="far fa-calendar-check text-muted"></i> TMT (Mulai Bertugas)</div>
                <div class="info-value">
                  @if($pegawai->terhitung_mulai_tanggal)                     {{ \Carbon\Carbon::parse($pegawai->terhitung_mulai_tanggal)->locale('id')->translatedFormat('d F Y') }}
                  @else
                    -
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Section 3: Domisili --}}
          <div class="section-badge-header">
            <span class="section-icon-box bg-warning-subtle text-warning">
              <i class="fas fa-map-marker-alt"></i>
            </span>
            <h6 class="mb-0 fw-bold text-dark">Alamat & Domisili</h6>
          </div>

          <div class="row g-2 g-md-3">
            <div class="col-12">
              <div class="info-tile">
                <div class="info-label"><i class="fas fa-map text-muted"></i> Alamat Lengkap Sesuai Dokumen</div>
                <div class="info-value text-secondary lh-base">
                  Desa/Kel. {{ $pegawai->desa ?? '-' }},
                  Kec. {{ $pegawai->kecamatan ?? '-' }},
                  Kab/Kota {{ $pegawai->kabupaten ?? '-' }},
                  Provinsi {{ $pegawai->provinsi ?? '-' }}
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="card-footer bg-light border-0 py-3 text-center">
          <small class="text-muted" style="font-size: 0.8rem;">
            Dicetak / Diakses secara digital melalui Sistem Manajemen Pegawai
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const pdfBtn = document.getElementById('pdfBtn');
    const copyBtn = document.getElementById('copyBtn');
    const printableArea = document.getElementById('printableArea');

    // Generate PDF
    if (pdfBtn && printableArea) {
      pdfBtn.addEventListener('click', function() {
        const employeeName = "{{ addslashes($pegawai->nama ?? 'Pegawai') }}";
        const opt = {
          margin: 0.3,
          filename: `Detail_Pegawai_${employeeName.replace(/\s+/g, '_')}.pdf`,
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: { scale: 2, useCORS: true },
          jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().from(printableArea).set(opt).save();
      });
    }

    // Salin Informasi Pegawai
    if (copyBtn && printableArea) {
      copyBtn.addEventListener('click', function() {
        let textToCopy = "INFORMASI DATA PEGAWAI\n";
        textToCopy += "=================================\n\n";

        const tiles = printableArea.querySelectorAll('.info-tile');
        tiles.forEach(tile => {
          const label = tile.querySelector('.info-label')?.innerText.trim() || '';
          const value = tile.querySelector('.info-value')?.innerText.replace(/\s+/g, ' ').trim() || '-';
          if (label) {
            textToCopy += `${label}: ${value}\n`;
          }
        });

        navigator.clipboard.writeText(textToCopy).then(() => {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Berhasil disalin ke clipboard!',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
          });
        }).catch(err => {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Gagal menyalin teks',
            showConfirmButton: false,
            timer: 2000
          });
        });
      });
    }
  });
</script>
@endpush