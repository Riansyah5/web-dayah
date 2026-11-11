<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran Santri Baru (PSB Online) - Dayah Mataqu</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">

  <style>
    :root {
      --iz-heading: #121926;
      --iz-body: #364152;
      --iz-muted: #697586;
      --iz-line: #e3e8ef;
      --iz-surface: #ffffff;
      --iz-soft: #f8fafc;
      --iz-secondary: #673ab7;
      --iz-secondary-dark: #5e35b1;
      --iz-secondary-light: #ede7f6;
      --iz-primary: #2196f3;
      --iz-primary-light: #e3f2fd;
      --iz-success: #00a152;
      --iz-success-light: #e3f9ec;
      --iz-danger: #e53935;
      --iz-danger-light: #ffebee;
      --circle: 42px;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f4f6f9;
      color: var(--iz-body);
      margin: 0;
      padding: 0;
    }

    .psb-header {
      background: #ffffff;
      border-bottom: 1px solid var(--iz-line);
      padding: 1.15rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .psb-header .brand-title {
      font-weight: 700;
      font-size: 1.15rem;
      color: var(--iz-heading);
      margin: 0;
    }
    .psb-header .brand-sub {
      font-size: .8rem;
      color: var(--iz-muted);
      margin: 0;
    }

    .main-wrapper {
      max-width: 860px;
      margin: 2rem auto 3rem;
      padding: 0 1rem;
    }

    .psb-hero {
      background: linear-gradient(135deg, var(--iz-secondary) 0%, #3f51b5 100%);
      border-radius: 16px;
      color: #fff;
      padding: 2rem 2.25rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 10px 30px rgba(103, 58, 183, .15);
    }
    .psb-hero h2 { font-weight: 700; font-size: 1.75rem; margin-bottom: .4rem; }
    .psb-hero p { margin-bottom: 0; opacity: .9; font-size: .95rem; }

    .card-form {
      background: var(--iz-surface);
      border: 1px solid var(--iz-line);
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(18, 25, 38, .04);
      overflow: hidden;
    }

    /* STEP WIZARD */
    .psb-steps {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      padding: 1.5rem 2rem 1.25rem;
      background: #ffffff;
    }
    .psb-steps-line {
      position: absolute;
      left: 3rem;
      right: 3rem;
      top: calc(1.5rem + (var(--circle) / 2) - 1.5px);
      height: 3px;
      background: var(--iz-line);
      z-index: 1;
    }
    .psb-step {
      position: relative;
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: transparent;
      border: 0;
      padding: 0;
      cursor: pointer;
      color: var(--iz-muted);
    }
    .psb-step-circle {
      width: var(--circle);
      height: var(--circle);
      border-radius: 50%;
      background: var(--iz-soft);
      border: 2px solid var(--iz-line);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--iz-muted);
      transition: all .25s ease;
    }
    .psb-step-text {
      font-size: .8rem;
      font-weight: 600;
      margin-top: .4rem;
      transition: color .25s ease;
    }
    .psb-step.active .psb-step-circle {
      background: var(--iz-secondary);
      border-color: var(--iz-secondary);
      color: #fff;
      box-shadow: 0 0 0 4px var(--iz-secondary-light);
    }
    .psb-step.active .psb-step-text { color: var(--iz-secondary); }
    .psb-step.completed .psb-step-circle {
      background: var(--iz-success);
      border-color: var(--iz-success);
      color: #fff;
    }
    .psb-step.completed .psb-step-text { color: var(--iz-success); }

    .form-body {
      padding: 2rem;
      border-top: 1px solid var(--iz-line);
    }

    .step-section-head {
      display: flex;
      align-items: center;
      gap: .85rem;
      margin-bottom: 1.5rem;
    }
    .step-section-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: var(--iz-secondary-light);
      color: var(--iz-secondary);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }
    .step-section-title {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--iz-heading);
      margin: 0;
    }
    .step-section-desc {
      font-size: .85rem;
      color: var(--iz-muted);
      margin: 0;
    }

    .form-label {
      font-weight: 600;
      font-size: .85rem;
      color: var(--iz-heading);
      margin-bottom: .35rem;
    }
    .form-control, .form-select {
      border: 1px solid #cdd5df;
      border-radius: 8px;
      padding: .65rem .95rem;
      font-size: .92rem;
      color: var(--iz-body);
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }

    .parent-card {
      background: #fafbfc;
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      padding: 1.25rem;
      margin-bottom: 1.25rem;
    }

    .action-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 2rem;
      padding-top: 1.25rem;
      border-top: 1px solid var(--iz-line);
    }

    .btn-secondary-custom {
      background: var(--iz-secondary);
      border-color: var(--iz-secondary);
      color: #fff;
      font-weight: 600;
      padding: .65rem 1.4rem;
      border-radius: 8px;
      transition: all .2s;
    }
    .btn-secondary-custom:hover {
      background: var(--iz-secondary-dark);
      border-color: var(--iz-secondary-dark);
      color: #fff;
    }

    @media (max-width: 767.98px) {
      .psb-hero { padding: 1.5rem 1.25rem; }
      .psb-steps { padding: 1rem .5rem; }
      .psb-step-text { font-size: .7rem; }
      .form-body { padding: 1.25rem 1rem; }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="psb-header">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.35rem;">
          <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
          <h1 class="brand-title">DAYAH MATAQU</h1>
          <p class="brand-sub">Sistem Penerimaan Santri Baru (PSB Online)</p>
        </div>
      </div>
      <div>
        <a href="{{ route('psb.check') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
          <i class="bi bi-search"></i>
          <span class="d-none d-sm-inline">Cek Status Pendaftaran</span>
        </a>
      </div>
    </div>
  </header>

  <!-- MAIN CONTAINER -->
  <div class="main-wrapper">
    <!-- HERO BANNER -->
    <div class="psb-hero">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
          <h2>Formulir Pendaftaran Santri Baru</h2>
          <p>Tahun Ajaran {{ $activeYear?->name ?? '2026/2027' }} &bull; Daftarkan calon santri secara mudah dan praktis.</p>
        </div>
        <span class="badge bg-white text-dark px-3 py-2 fw-semibold rounded-pill">
          <i class="bi bi-check2-circle text-success me-1"></i> Gelombang Aktif
        </span>
      </div>
    </div>

    <!-- CARD FORMULIR -->
    <div class="card-form">
      <!-- STEP INDICATOR -->
      <div class="psb-steps">
        <div class="psb-steps-line"></div>
        <button type="button" class="psb-step active" data-step="1">
          <div class="psb-step-circle">1</div>
          <div class="psb-step-text">Data Diri</div>
        </button>
        <button type="button" class="psb-step" data-step="2">
          <div class="psb-step-circle">2</div>
          <div class="psb-step-text">Alamat</div>
        </button>
        <button type="button" class="psb-step" data-step="3">
          <div class="psb-step-circle">3</div>
          <div class="psb-step-text">Orang Tua</div>
        </button>
        <button type="button" class="psb-step" data-step="4">
          <div class="psb-step-circle">4</div>
          <div class="psb-step-text">Konfirmasi</div>
        </button>
      </div>

      <!-- FORM BODY -->
      <div class="form-body">
        @if ($errors->any())
          <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
            <div>
              <strong class="d-block mb-1">Ada data yang belum lengkap atau tidak valid:</strong>
              <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <form id="psbForm" action="{{ route('psb.store') }}" method="POST">
          @csrf

          @php
            $occupations = [
              'PNS', 'PPPK', 'TNI', 'POLRI', 'Pegawai BUMN/BUMD', 'Karyawan Swasta', 'Wiraswasta', 'Pedagang',
              'Petani', 'Nelayan', 'Peternak', 'Buruh', 'Sopir/Driver', 'Tukang', 'Guru/Dosen', 'Tenaga Kesehatan',
              'TKI/PMI', 'Ibu Rumah Tangga', 'Tidak Bekerja', 'Lainnya',
            ];

            $educations = [
              'Tidak Sekolah', 'Tidak Tamat SD', 'SD/MI', 'SMP/MTs', 'SMA/MA/SMK',
              'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3',
            ];
          @endphp

          <!-- =================== STEP 1: DATA DIRI & JENJANG =================== -->
          <div class="form-step" id="step1">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-person-badge"></i></div>
              <div>
                <h5 class="step-section-title">Pilihan Jenjang & Data Calon Santri</h5>
                <p class="step-section-desc">Identitas lengkap calon santri sesuai Akta Kelahiran / KK</p>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Pilihan Jenjang Pendidikan <span class="text-danger">*</span></label>
                <select name="target_stage" class="form-select" required>
                  <option value="" disabled {{ old('target_stage') ? '' : 'selected' }}>-- Pilih Jenjang --</option>
                  @foreach($stages as $stg)
    <option value="{{ $stg->name }}"
        {{ old('target_stage') == $stg->name ? 'selected' : '' }}>
        {{ match($stg->name) {
            'SALAFIYAH WUSTHA' => 'Wustha (SMP/MTs)',
            'SALAFIYAH ULYA' => 'Ulya (SMA/MA)',
            default => $stg->name
        } }}
    </option>
@endforeach
                  @if($stages->isEmpty())
                    <option value="MTS">MTS / SMP</option>
                    <option value="MA">MA / SMA</option>
                  @endif
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Sekolah Asal <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="text" name="previous_school" class="form-control" placeholder="Contoh: SDN 1 Banda Aceh / MIS" value="{{ old('previous_school') }}">
              </div>
              <div class="col-12">
                <label class="form-label">Nama Lengkap Santri <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Nama lengkap sesuai KK / Akta" value="{{ old('name') }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="gender" class="form-select" required>
                  <option value="" disabled {{ old('gender') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                  <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Anak Ke- <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="number" name="child_order" min="1" class="form-control" placeholder="Contoh: 1" value="{{ old('child_order') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-muted fw-normal">(16 digit)</span></label>
                <input type="text" name="nik" maxlength="16" class="form-control" placeholder="16 digit NIK santri" value="{{ old('nik') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" name="nisn" maxlength="20" class="form-control" placeholder="NISN dari sekolah asal" value="{{ old('nisn') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                <input type="text" name="birth_place" class="form-control" placeholder="Kota / Kabupaten tempat lahir" value="{{ old('birth_place') }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
              </div>
            </div>
          </div>

          <!-- =================== STEP 2: ALAMAT & IDENTITAS KK =================== -->
          <div class="form-step d-none" id="step2">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-geo-alt"></i></div>
              <div>
                <h5 class="step-section-title">Alamat & Identitas Domisili</h5>
                <p class="step-section-desc">Alamat tempat tinggal keluarga calon santri saat ini</p>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Nomor Kartu Keluarga (KK)</label>
                <input type="text" name="family_card_number" maxlength="16" class="form-control" placeholder="16 digit nomor kartu keluarga" value="{{ old('family_card_number') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Provinsi</label>
                <input type="text" name="province" class="form-control" placeholder="Contoh: Aceh" value="{{ old('province', 'Aceh') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Kabupaten / Kota</label>
                <input type="text" name="regency" class="form-control" placeholder="Contoh: Aceh Besar" value="{{ old('regency') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Kecamatan</label>
                <input type="text" name="district" class="form-control" placeholder="Contoh: Darul Imarah" value="{{ old('district') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Gampong / Desa</label>
                <input type="text" name="village" class="form-control" placeholder="Contoh: Garot" value="{{ old('village') }}">
              </div>
            </div>
          </div>

          <!-- =================== STEP 3: ORANG TUA =================== -->
          <div class="form-step d-none" id="step3">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-people"></i></div>
              <div>
                <h5 class="step-section-title">Data Orang Tua Kandung</h5>
                <p class="step-section-desc">Identitas ayah dan ibu kandung calon santri</p>
              </div>
            </div>

            <!-- AYAH -->
            <div class="parent-card">
              <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-primary">
                <i class="bi bi-gender-male"></i> Data Ayah Kandung
              </h6>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <label class="form-label">Status Ayah</label>
                  <select name="father_status" class="form-select">
                    <option value="alive" {{ old('father_status') == 'alive' ? 'selected' : '' }}>Masih Hidup</option>
                    <option value="deceased" {{ old('father_status') == 'deceased' ? 'selected' : '' }}>Meninggal Dunia</option>
                  </select>
                </div>
                <div class="col-12 col-md-8">
                  <label class="form-label">Nama Ayah Kandung</label>
                  <input type="text" name="father_name" class="form-control" placeholder="Nama lengkap ayah" value="{{ old('father_name') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">No. Telepon / WhatsApp Ayah</label>
                  <input type="tel" name="father_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('father_phone') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pendidikan Terakhir Ayah</label>
                  <select name="father_education" class="form-select">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach($educations as $edu)
                      <option value="{{ $edu }}" {{ old('father_education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pekerjaan Ayah</label>
                  <select name="father_occupation" class="form-select">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($occupations as $job)
                      <option value="{{ $job }}" {{ old('father_occupation') == $job ? 'selected' : '' }}>{{ $job }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Detail Pekerjaan Ayah</label>
                  <input type="text" name="father_occupation_detail" class="form-control" placeholder="Instansi / Jenis Usaha" value="{{ old('father_occupation_detail') }}">
                </div>
              </div>
            </div>

            <!-- IBU -->
            <div class="parent-card">
              <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-danger">
                <i class="bi bi-gender-female"></i> Data Ibu Kandung
              </h6>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <label class="form-label">Status Ibu</label>
                  <select name="mother_status" class="form-select">
                    <option value="alive" {{ old('mother_status') == 'alive' ? 'selected' : '' }}>Masih Hidup</option>
                    <option value="deceased" {{ old('mother_status') == 'deceased' ? 'selected' : '' }}>Meninggal Dunia</option>
                  </select>
                </div>
                <div class="col-12 col-md-8">
                  <label class="form-label">Nama Ibu Kandung</label>
                  <input type="text" name="mother_name" class="form-control" placeholder="Nama lengkap ibu" value="{{ old('mother_name') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">No. Telepon / WhatsApp Ibu</label>
                  <input type="tel" name="mother_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('mother_phone') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pendidikan Terakhir Ibu</label>
                  <select name="mother_education" class="form-select">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach($educations as $edu)
                      <option value="{{ $edu }}" {{ old('mother_education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pekerjaan Ibu</label>
                  <select name="mother_occupation" class="form-select">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($occupations as $job)
                      <option value="{{ $job }}" {{ old('mother_occupation') == $job ? 'selected' : '' }}>{{ $job }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Detail Pekerjaan Ibu</label>
                  <input type="text" name="mother_occupation_detail" class="form-control" placeholder="Instansi / Jenis Usaha" value="{{ old('mother_occupation_detail') }}">
                </div>
              </div>
            </div>
          </div>

          <!-- =================== STEP 4: WALI & KONFIRMASI =================== -->
          <div class="form-step d-none" id="step4">
            <div class="step-section-head">
              <div class="step-section-icon"><i class="bi bi-shield-check"></i></div>
              <div>
                <h5 class="step-section-title">Data Wali (Opsional) & Konfirmasi</h5>
                <p class="step-section-desc">Lengkapi jika tinggal bersama wali dan setujui ketentuan</p>
              </div>
            </div>

            <div class="parent-card mb-4">
              <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-secondary">
                <i class="bi bi-person-badge"></i> Data Wali (Jika Ada)
              </h6>
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">Nama Wali</label>
                  <input type="text" name="guardian_name" class="form-control" placeholder="Nama wali santri" value="{{ old('guardian_name') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">No. Telepon / WhatsApp Wali</label>
                  <input type="tel" name="guardian_phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('guardian_phone') }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Pekerjaan Wali</label>
                  <select name="guardian_occupation" class="form-select">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($occupations as $job)
                      <option value="{{ $job }}" {{ old('guardian_occupation') == $job ? 'selected' : '' }}>{{ $job }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Detail Pekerjaan Wali</label>
                  <input type="text" name="guardian_occupation_detail" class="form-control" placeholder="Instansi / Usaha wali" value="{{ old('guardian_occupation_detail') }}">
                </div>
              </div>
            </div>

            <!-- PERNYATAAN -->
            <div class="alert alert-light border p-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="agreementCheck" required>
                <label class="form-check-label small" for="agreementCheck">
                  Saya menyatakan bahwa seluruh data yang diisikan dalam formulir pendaftaran ini adalah <strong>benar dan dapat dipertanggungjawabkan</strong>. Saya bersedia mengikuti seluruh tata tertib dan tahapan seleksi yang ditentukan oleh panitia PSB Dayah Mataqu.
                </label>
              </div>
            </div>
          </div>

          <!-- ACTION BAR -->
          <div class="action-bar">
            <button type="button" class="btn btn-outline-secondary" id="btnPrev" style="display: none;">
              <i class="bi bi-arrow-left me-1"></i> Sebelumnya
            </button>
            <div class="ms-auto d-flex gap-2">
              <button type="button" class="btn btn-secondary-custom" id="btnNext">
                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
              </button>
              <button type="submit" class="btn btn-success px-4 fw-semibold" id="btnSubmit" style="display: none;">
                <i class="bi bi-send-check me-1"></i> Kirim Pendaftaran
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <footer class="text-center py-4 text-muted small">
    &copy; {{ date('Y') }} Dayah Mataqu. Seluruh hak cipta dilindungi.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let currentStep = 1;
      const totalSteps = 4;

      const btnPrev = document.getElementById('btnPrev');
      const btnNext = document.getElementById('btnNext');
      const btnSubmit = document.getElementById('btnSubmit');

      function showStep(step) {
        // Sembunyikan semua step
        for (let i = 1; i <= totalSteps; i++) {
          const stepEl = document.getElementById('step' + i);
          const stepBtn = document.querySelector(`.psb-step[data-step="${i}"]`);
          
          if (i === step) {
            stepEl.classList.remove('d-none');
            stepBtn.classList.add('active');
            stepBtn.classList.remove('completed');
          } else if (i < step) {
            stepEl.classList.add('d-none');
            stepBtn.classList.remove('active');
            stepBtn.classList.add('completed');
          } else {
            stepEl.classList.add('d-none');
            stepBtn.classList.remove('active', 'completed');
          }
        }

        // Tampilan tombol
        btnPrev.style.display = (step === 1) ? 'none' : 'inline-block';
        if (step === totalSteps) {
          btnNext.style.display = 'none';
          btnSubmit.style.display = 'inline-block';
        } else {
          btnNext.style.display = 'inline-block';
          btnSubmit.style.display = 'none';
        }

        window.scrollTo({ top: document.querySelector('.card-form').offsetTop - 30, behavior: 'smooth' });
      }

      function validateStep(step) {
        const stepEl = document.getElementById('step' + step);
        const requiredInputs = stepEl.querySelectorAll('input[required], select[required]');
        let valid = true;

        requiredInputs.forEach(input => {
          if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
          } else {
            input.classList.remove('is-invalid');
          }
        });

        if (!valid) {
          alert('Harap lengkapi semua kolom yang bertanda bintang (*) sebelum melanjutkan.');
        }

        return valid;
      }

      btnNext.addEventListener('click', function () {
        if (validateStep(currentStep)) {
          if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
          }
        }
      });

      btnPrev.addEventListener('click', function () {
        if (currentStep > 1) {
          currentStep--;
          showStep(currentStep);
        }
      });

      // Klik langsung ke step wizard
      document.querySelectorAll('.psb-step').forEach(btn => {
        btn.addEventListener('click', function () {
          const target = parseInt(this.getAttribute('data-step'));
          if (target < currentStep) {
            currentStep = target;
            showStep(currentStep);
          } else if (target > currentStep) {
            if (validateStep(currentStep)) {
              currentStep = target;
              showStep(currentStep);
            }
          }
        });
      });

      // Hapus kelas is-invalid saat pengguna mulai mengisi
      document.querySelectorAll('#psbForm input, #psbForm select').forEach(input => {
        input.addEventListener('input', () => input.classList.remove('is-invalid'));
        input.addEventListener('change', () => input.classList.remove('is-invalid'));
      });
    });
  </script>
</body>
</html>
