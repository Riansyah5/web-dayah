@extends('layouts.app')
@section('title', 'Edit Santri')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@push('styles')
  <style>
    /* Palet mengikuti Berry Dashboard. Font mengikuti template (Public Sans). */
    .iz-page {
      --iz-heading: #121926;
      --iz-body: #364152;
      --iz-muted: #697586;
      --iz-line: #e3e8ef;
      --iz-surface: #ffffff;
      --iz-soft: #f8fafc;

      --iz-secondary: #673ab7;
      --iz-secondary-dark: #5e35b1;
      --iz-secondary-light: #ede7f6;
      --iz-primary-light: #e3f2fd;
      --iz-primary-dark: #1e88e5;
      --iz-success: #00a152;
      --iz-success-light: #e3f9ec;
      --iz-danger: #e53935;
      --iz-danger-light: #ffebee;
    }

    /* ---------- Kartu & header halaman ---------- */
    .iz-page .card {
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      box-shadow: 0 1px 2px rgba(18, 25, 38, .04);
      margin-bottom: 1rem;
    }
    .iz-page .page-header { margin-bottom: 1.25rem; }
    .iz-page .page-header .breadcrumb { margin-bottom: .35rem; font-size: .85rem; }
    .iz-page .page-header-title h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--iz-heading);
      margin: 0;
    }

    .iz-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .45rem;
      border-radius: 8px;
      font-weight: 500;
      font-size: .92rem;
      padding: .65rem 1.25rem;
      border: 0;
      white-space: nowrap;
      text-decoration: none;
      transition: background-color .15s ease;
    }
    .iz-btn--light { background: #eef2f6; color: var(--iz-body); }
    .iz-btn--light:hover { background: #e3e8ef; color: var(--iz-heading); }
    .iz-btn--ok { background: var(--iz-success); color: #fff; }
    .iz-btn--ok:hover:not(:disabled) { background: #008a45; color: #fff; }

    /* ---------- Identitas santri yang sedang diedit ---------- */
    .iz-identity { display: flex; align-items: center; gap: .9rem; padding: 1rem 1.25rem; }
    .iz-avatar {
      flex: 0 0 auto;
      width: 3rem;
      height: 3rem;
      border-radius: 50%;
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
      font-weight: 600;
      font-size: 1.1rem;
      display: grid;
      place-items: center;
    }
    .iz-identity-name { font-weight: 600; color: var(--iz-heading); line-height: 1.25; overflow-wrap: anywhere; }
    .iz-identity-meta { color: var(--iz-muted); font-size: .82rem; }
    .iz-chip {
      margin-left: auto;
      display: inline-flex;
      align-items: center;
      font-size: .76rem;
      font-weight: 600;
      padding: .28rem .6rem;
      border-radius: 6px;
      white-space: nowrap;
    }
    .iz-chip--ok { background: var(--iz-success-light); color: var(--iz-success); }
    .iz-chip--primary { background: var(--iz-primary-light); color: var(--iz-primary-dark); }
    .iz-chip--late { background: var(--iz-danger-light); color: var(--iz-danger); }
    .iz-chip--muted { background: #eef2f6; color: var(--iz-muted); }

    /* ---------- Stepper ---------- */
    .iz-steps {
      --circle: 40px;
      position: relative;
      display: flex;
      padding: 1.25rem 1rem 1rem;
    }
    .iz-steps-line {
      position: absolute;
      top: calc(1.25rem + (var(--circle) / 2) - 1.5px);
      left: 12.5%;
      right: 12.5%;
      height: 3px;
      border-radius: 999px;
      background: var(--iz-line);
      z-index: 0;
    }
    .iz-steps-fill {
      display: block;
      width: 0;
      height: 100%;
      border-radius: inherit;
      background: var(--iz-secondary);
      transition: width .3s ease;
    }
    .iz-step {
      position: relative;
      z-index: 1;
      flex: 1 1 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .5rem;
      padding: 0;
      border: 0;
      background: transparent;
      color: #9aa4b2;
      cursor: pointer;
    }
    .iz-step-circle {
      width: var(--circle);
      height: var(--circle);
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: var(--iz-surface);
      border: 2px solid var(--iz-line);
      font-size: 1.05rem;
      transition: all .25s ease;
    }
    .iz-step:hover .iz-step-circle { border-color: var(--iz-secondary); }
    .iz-step-text { font-size: .82rem; font-weight: 500; }
    .iz-step .icon-done { display: none; }

    .iz-step.active { color: var(--iz-secondary-dark); }
    .iz-step.active .iz-step-circle {
      background: var(--iz-secondary);
      border-color: var(--iz-secondary);
      color: #fff;
      box-shadow: 0 0 0 4px rgba(103, 58, 183, .18);
    }
    .iz-step.active .iz-step-text { font-weight: 600; }

    .iz-step.completed { color: var(--iz-secondary-dark); }
    .iz-step.completed .iz-step-circle {
      background: var(--iz-secondary-light);
      border-color: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
    }
    .iz-step.completed .icon-main { display: none; }
    .iz-step.completed .icon-done { display: inline; }

    .iz-step.has-error .iz-step-circle {
      border-color: var(--iz-danger);
      color: var(--iz-danger);
      background: var(--iz-danger-light);
    }
    .iz-step.has-error .iz-step-text { color: var(--iz-danger); }

    /* ---------- Isi formulir ---------- */
    .iz-form-body { padding: 1.5rem; }
    .iz-step-head {
      display: flex;
      align-items: center;
      gap: .85rem;
      margin-bottom: 1.25rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--iz-line);
    }
    .iz-step-icon {
      flex: 0 0 auto;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 10px;
      background: var(--iz-secondary-light);
      color: var(--iz-secondary-dark);
      display: grid;
      place-items: center;
      font-size: 1.15rem;
    }
    .iz-step-title { font-size: 1.05rem; font-weight: 600; color: var(--iz-heading); margin: 0; line-height: 1.25; }
    .iz-step-sub { color: var(--iz-muted); font-size: .8rem; margin: 0; }

    .iz-subhead {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-size: .9rem;
      font-weight: 600;
      color: var(--iz-heading);
      margin: 0 0 .85rem;
    }
    .iz-subhead .bi { color: var(--iz-secondary); }
    .iz-parent {
      background: var(--iz-soft);
      border: 1px solid var(--iz-line);
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 1rem;
    }
    .iz-parent:last-of-type { margin-bottom: 0; }

    .iz-form .form-label {
      font-size: .85rem;
      font-weight: 500;
      color: var(--iz-heading);
      margin-bottom: .4rem;
    }
    .iz-form .form-control,
    .iz-form .form-select {
      border: 1px solid #cdd5df;
      border-radius: 8px;
      font-size: .92rem;
      color: var(--iz-body);
      padding: .65rem .95rem;
      background-color: var(--iz-surface);
    }
    .iz-form .form-control::placeholder { color: #9aa4b2; }
    .iz-form .form-control:focus,
    .iz-form .form-select:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }
    .iz-form .is-invalid { border-color: var(--iz-danger) !important; }
    .iz-form .invalid-feedback { font-size: .8rem; }
    .iz-form .form-text { font-size: .78rem; color: var(--iz-muted); }
    .iz-form .form-control:disabled,
    .iz-form .form-select:disabled {
      background-color: var(--iz-soft);
      color: var(--iz-muted);
      border-color: var(--iz-line);
      opacity: 1;
    }

    .iz-alert {
      display: flex;
      gap: .75rem;
      background: var(--iz-danger-light);
      color: #b71c1c;
      border: 1px solid #f7c6c4;
      border-radius: 10px;
      padding: .85rem 1rem;
      margin-bottom: 1.25rem;
      font-size: .9rem;
    }
    .iz-alert .bi { font-size: 1.1rem; margin-top: .1rem; }
    .iz-alert ul { margin: .35rem 0 0; padding-left: 1.1rem; }

    .iz-note {
      display: flex;
      gap: .7rem;
      background: var(--iz-primary-light);
      color: #1565c0;
      border-radius: 10px;
      padding: .8rem 1rem;
      font-size: .86rem;
      margin-top: 1.25rem;
    }
    .iz-note .bi { margin-top: .1rem; }

    /* ---------- Tombol navigasi ---------- */
    .iz-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: .6rem;
      margin-top: 1.75rem;
      padding-top: 1.1rem;
      border-top: 1px solid var(--iz-line);
    }
    .iz-actions .ms-auto-btn { margin-left: auto; }

    /* ---------- Mobile ---------- */
    @media (max-width: 767.98px) {
      .iz-page .page-header-title h2 { font-size: 1.25rem; }
      .iz-identity { padding: .9rem 1rem; }
      .iz-steps { --circle: 34px; padding: 1rem .5rem .85rem; }
      .iz-steps-line { top: calc(1rem + (var(--circle) / 2) - 1.5px); }
      .iz-step-circle { font-size: .95rem; }
      .iz-step-text { font-size: .72rem; }

      .iz-form-body { padding: 1rem; }
      .iz-parent { padding: .85rem; }

      /* 16px mencegah iOS memperbesar layar saat input difokuskan */
      .iz-form .form-control,
      .iz-form .form-select { font-size: 16px; padding: .75rem .95rem; }

      /* Tombol navigasi menempel di bawah layar */
      .iz-actions {
        position: sticky;
        bottom: 0;
        z-index: 10;
        margin: 1.5rem -1rem -1rem;
        padding: .75rem 1rem calc(.75rem + env(safe-area-inset-bottom, 0px));
        background: var(--iz-surface);
        border-top: 1px solid var(--iz-line);
        box-shadow: 0 -4px 16px rgba(18, 25, 38, .06);
        border-radius: 0 0 12px 12px;
      }
      .iz-actions .iz-btn { flex: 1 1 0; padding: .8rem 1rem; }
      .iz-actions .iz-btn.grow { flex: 2 1 0; }
    }

    @media (prefers-reduced-motion: reduce) {
      .iz-page * { transition: none !important; }
    }
  </style>
@endpush

@section('content')
  @php
    // Penanda kelas error untuk setiap kolom
    $inv = fn($field) => $errors->has($field) ? 'is-invalid' : '';

    $occupations = [
        'PNS', 'PPPK', 'TNI', 'POLRI', 'Pegawai BUMN/BUMD', 'Karyawan Swasta', 'Wiraswasta', 'Pedagang',
        'Petani', 'Nelayan', 'Peternak', 'Buruh', 'Sopir/Driver', 'Tukang', 'Guru/Dosen', 'Tenaga Kesehatan',
        'TKI/PMI', 'Ibu Rumah Tangga', 'Tidak Bekerja', 'Lainnya',
    ];

    $educations = [
        'Tidak Sekolah', 'Tidak Tamat SD', 'SD/MI', 'SMP/MTs', 'SMA/MA/SMK',
        'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3',
    ];

    // Jika nilai tersimpan tidak ada di daftar (mis. diketik bebas di form tambah), tetap tampilkan
    // sebagai pilihan agar tidak hilang saat disimpan ulang.
    $withCurrent = function (array $list, $current) {
        return ($current !== null && $current !== '' && !in_array($current, $list, true))
            ? array_merge([$current], $list)
            : $list;
    };

    $parents = [
        ['key' => 'father', 'label' => 'Ayah', 'icon' => 'bi-gender-male', 'hasStatus' => true],
        ['key' => 'mother', 'label' => 'Ibu', 'icon' => 'bi-gender-female', 'hasStatus' => true],
        ['key' => 'guardian', 'label' => 'Wali', 'icon' => 'bi-person-badge', 'hasStatus' => false],
    ];

    $statusCls = match ($student->status) {
        'active' => 'ok',
        'graduated' => 'primary',
        'suspended' => 'late',
        default => 'muted',
    };
    $statusText = match ($student->status) {
        'active' => 'Aktif',
        'graduated' => 'Lulus',
        'suspended' => 'Skorsing',
        'moved' => 'Pindah',
        default => $student->status,
    };
  @endphp

  {{-- Tanpa wrapper .container: template Berry sudah membungkus konten dengan .pc-container / .pc-content --}}
  <div class="iz-page">

    {{-- Header halaman (gaya Berry) --}}
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-end g-3">
          <div class="col">
            <div class="row">
              <div class="col">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Data Santri</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ul>
              </div>
            </div> <br>
            <div class="page-header-title">
              <h2>Edit Data Santri</h2>
            </div>
          </div>
          <div class="col-auto d-none d-md-block">
            <a href="{{ route('students.index') }}" class="iz-btn iz-btn--light">
              <i class="bi bi-arrow-left"></i> Batal
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">

        {{-- Identitas santri yang sedang diedit --}}
        {{-- <div class="card iz-identity">
          <div class="iz-avatar">{{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}</div>
          <div class="min-w-0">
            <div class="iz-identity-name">{{ $student->name }}</div>
            <div class="iz-identity-meta">NIS {{ $student->nis ?: '-' }} &middot; NISN {{ $student->nisn ?: '-' }}</div>
          </div>
          <span class="iz-chip iz-chip--{{ $statusCls }}">{{ $statusText }}</span>
        </div> --}}

        <div class="card">

          {{-- Stepper (semua langkah bisa diklik langsung) --}}
          <div class="iz-steps" id="stepper">
            <div class="iz-steps-line"><span class="iz-steps-fill" id="progressLine"></span></div>

            <button type="button" class="iz-step active" data-step="1">
              <span class="iz-step-circle"><i class="bi bi-person icon-main"></i><i class="bi bi-check-lg icon-done"></i></span>
              <span class="iz-step-text">Biodata</span>
            </button>
            <button type="button" class="iz-step" data-step="2">
              <span class="iz-step-circle"><i class="bi bi-geo-alt icon-main"></i><i class="bi bi-check-lg icon-done"></i></span>
              <span class="iz-step-text">Alamat</span>
            </button>
            <button type="button" class="iz-step" data-step="3">
              <span class="iz-step-circle"><i class="bi bi-people icon-main"></i><i class="bi bi-check-lg icon-done"></i></span>
              <span class="iz-step-text">Orang Tua</span>
            </button>
            <button type="button" class="iz-step" data-step="4">
              <span class="iz-step-circle"><i class="bi bi-book icon-main"></i><i class="bi bi-check-lg icon-done"></i></span>
              <span class="iz-step-text">Akademik</span>
            </button>
          </div>

          <div class="iz-form-body border-top">

            {{-- Ringkasan error --}}
            @if ($errors->any())
              <div class="iz-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                  <strong>Gagal menyimpan perubahan!</strong>
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            @endif

            <form id="studentForm" action="{{ route('students.update', $student->id) }}" method="POST"
              class="iz-form" novalidate>
              @csrf
              @method('PUT')

              {{-- ============ LANGKAH 1: BIODATA ============ --}}
              <div class="form-step" id="step1">
                <div class="iz-step-head">
                  <div class="iz-step-icon"><i class="bi bi-person"></i></div>
                  <div>
                    <h5 class="iz-step-title">Biodata utama</h5>
                    <p class="iz-step-sub">Identitas dasar santri</p>
                  </div>
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="nis" class="form-label">NIS (Nomor Induk Santri) <span class="text-danger">*</span></label>
                    <input type="text" name="nis" id="nis" class="form-control {{ $inv('nis') }}"
                      placeholder="Masukkan NIS" value="{{ old('nis', $student->nis) }}" autocomplete="off" required>
                    <div class="invalid-feedback">{{ $errors->first('nis') ?: 'NIS wajib diisi.' }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="nisn" class="form-label">NISN</label>
                    <input type="text" name="nisn" id="nisn" class="form-control {{ $inv('nisn') }}"
                      placeholder="Masukkan NISN" value="{{ old('nisn', $student->nisn) }}" inputmode="numeric"
                      autocomplete="off">
                    <div class="invalid-feedback">{{ $errors->first('nisn') }}</div>
                  </div>
                  <div class="col-12">
                    <label for="name" class="form-label">Nama Lengkap Santri <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control {{ $inv('name') }}"
                      placeholder="Masukkan nama lengkap" value="{{ old('name', $student->name) }}"
                      autocomplete="name" required>
                    <div class="invalid-feedback">{{ $errors->first('name') ?: 'Nama wajib diisi.' }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="gender" id="gender" class="form-select {{ $inv('gender') }}" required>
                      <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                      <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="child_order" class="form-label">Anak ke- <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="number" name="child_order" id="child_order"
                      class="form-control {{ $inv('child_order') }}" placeholder="Contoh: 2"
                      value="{{ old('child_order', $student->child_order) }}" min="1" inputmode="numeric">
                    <div class="invalid-feedback">{{ $errors->first('child_order') }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" name="birth_place" id="birth_place"
                      class="form-control {{ $inv('birth_place') }}" placeholder="Contoh: Medan"
                      value="{{ old('birth_place', $student->birth_place) }}" required>
                    <div class="invalid-feedback">{{ $errors->first('birth_place') ?: 'Tempat lahir wajib diisi.' }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    {{-- Format harus Y-m-d agar terbaca input date --}}
                    <input type="date" name="birth_date" id="birth_date" class="form-control {{ $inv('birth_date') }}"
                      value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}" required>
                    <div class="invalid-feedback">{{ $errors->first('birth_date') ?: 'Tanggal lahir wajib diisi.' }}</div>
                  </div>
                </div>
              </div>

              {{-- ============ LANGKAH 2: ALAMAT & IDENTITAS ============ --}}
              <div class="form-step d-none" id="step2">
                <div class="iz-step-head">
                  <div class="iz-step-icon"><i class="bi bi-geo-alt"></i></div>
                  <div>
                    <h5 class="iz-step-title">Alamat &amp; identitas</h5>
                    <p class="iz-step-sub">NIK, No. KK, dan alamat tinggal</p>
                  </div>
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="nik" class="form-label">NIK (16 digit)</label>
                    <input type="text" name="nik" id="nik" class="form-control {{ $inv('nik') }}"
                      placeholder="16 digit NIK" value="{{ old('nik', $student->nik) }}" maxlength="16"
                      inputmode="numeric" autocomplete="off" data-digits>
                    <div class="invalid-feedback">{{ $errors->first('nik') }}</div>
                    <div class="form-text text-end"><span id="count-nik">0</span>/16 digit</div>
                  </div>
                  <div class="col-md-6">
                    <label for="kk" class="form-label">Nomor Kartu Keluarga</label>
                    <input type="text" name="family_card_number" id="kk"
                      class="form-control {{ $inv('family_card_number') }}" placeholder="16 digit No. KK"
                      value="{{ old('family_card_number', $student->family_card_number) }}" maxlength="16"
                      inputmode="numeric" autocomplete="off" data-digits>
                    <div class="invalid-feedback">{{ $errors->first('family_card_number') }}</div>
                    <div class="form-text text-end"><span id="count-kk">0</span>/16 digit</div>
                  </div>

                  <div class="col-md-6">
                    <label for="province" class="form-label">Provinsi</label>
                    <input type="text" name="province" id="province" class="form-control {{ $inv('province') }}"
                      placeholder="Nama provinsi" value="{{ old('province', $student->province) }}">
                    <div class="invalid-feedback">{{ $errors->first('province') }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="regency" class="form-label">Kabupaten/Kota</label>
                    <input type="text" name="regency" id="regency" class="form-control {{ $inv('regency') }}"
                      placeholder="Nama kabupaten/kota" value="{{ old('regency', $student->regency) }}">
                    <div class="invalid-feedback">{{ $errors->first('regency') }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="district" class="form-label">Kecamatan</label>
                    <input type="text" name="district" id="district" class="form-control {{ $inv('district') }}"
                      placeholder="Nama kecamatan" value="{{ old('district', $student->district) }}">
                    <div class="invalid-feedback">{{ $errors->first('district') }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="village" class="form-label">Desa/Kelurahan</label>
                    <input type="text" name="village" id="village" class="form-control {{ $inv('village') }}"
                      placeholder="Nama desa/kelurahan" value="{{ old('village', $student->village) }}">
                    <div class="invalid-feedback">{{ $errors->first('village') }}</div>
                  </div>
                </div>
              </div>

              {{-- ============ LANGKAH 3: ORANG TUA & WALI ============ --}}
              <div class="form-step d-none" id="step3">
                <div class="iz-step-head">
                  <div class="iz-step-icon"><i class="bi bi-people"></i></div>
                  <div>
                    <h5 class="iz-step-title">Data orang tua &amp; wali</h5>
                    <p class="iz-step-sub">Semua isian pada langkah ini bersifat opsional</p>
                  </div>
                </div>

                @foreach ($parents as $p)
                  @php
                    $k = $p['key'];
                    $eduValue = old($k . '_education', $student->{$k . '_education'});
                    $jobValue = old($k . '_occupation', $student->{$k . '_occupation'});
                    $statusValue = old($k . '_status', $student->{$k . '_status'});
                  @endphp

                  <div class="iz-parent">
                    <h6 class="iz-subhead"><i class="bi {{ $p['icon'] }}"></i> Data {{ strtolower($p['label']) }}</h6>

                    <div class="row g-3">
                      @if ($p['hasStatus'])
                        <div class="col-md-4">
                          <label for="{{ $k }}_status" class="form-label">Status {{ $p['label'] }}</label>
                          <select name="{{ $k }}_status" id="{{ $k }}_status"
                            class="form-select {{ $inv($k . '_status') }}">
                            <option value="alive" {{ $statusValue == 'alive' ? 'selected' : '' }}>Hidup</option>
                            <option value="deceased" {{ $statusValue == 'deceased' ? 'selected' : '' }}>Meninggal</option>
                          </select>
                        </div>
                      @endif

                      <div class="col-md-8">
                        <label for="{{ $k }}_name" class="form-label">Nama {{ $p['label'] }}</label>
                        <input type="text" name="{{ $k }}_name" id="{{ $k }}_name"
                          class="form-control {{ $inv($k . '_name') }}" placeholder="Nama {{ strtolower($p['label']) }}"
                          value="{{ old($k . '_name', $student->{$k . '_name'}) }}">
                        <div class="invalid-feedback">{{ $errors->first($k . '_name') }}</div>
                      </div>

                      <div class="{{ $p['hasStatus'] ? 'col-md-4' : 'col-md-4' }}">
                        <label for="{{ $k }}_phone" class="form-label">No. HP/WA {{ $p['label'] }}</label>
                        <input type="tel" name="{{ $k }}_phone" id="{{ $k }}_phone"
                          class="form-control {{ $inv($k . '_phone') }}" placeholder="08xxxxxxxxxx" inputmode="tel"
                          value="{{ old($k . '_phone', $student->{$k . '_phone'}) }}">
                        <div class="invalid-feedback">{{ $errors->first($k . '_phone') }}</div>
                      </div>

                      <div class="col-md-4">
                        <label for="{{ $k }}_education" class="form-label">Pendidikan</label>
                        <select name="{{ $k }}_education" id="{{ $k }}_education"
                          class="form-select {{ $inv($k . '_education') }}">
                          <option value="">-- Pilih pendidikan --</option>
                          @foreach ($withCurrent($educations, $eduValue) as $edu)
                            <option value="{{ $edu }}" {{ $eduValue == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback">{{ $errors->first($k . '_education') }}</div>
                      </div>

                      <div class="col-md-4">
                        <label for="{{ $k }}_occupation" class="form-label">Pekerjaan</label>
                        <select name="{{ $k }}_occupation" id="{{ $k }}_occupation"
                          class="form-select {{ $inv($k . '_occupation') }}">
                          <option value="">-- Pilih pekerjaan --</option>
                          @foreach ($withCurrent($occupations, $jobValue) as $job)
                            <option value="{{ $job }}" {{ $jobValue == $job ? 'selected' : '' }}>{{ $job }}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback">{{ $errors->first($k . '_occupation') }}</div>
                      </div>

                      <div class="col-12">
                        <label for="{{ $k }}_occupation_detail" class="form-label">Detail Pekerjaan {{ $p['label'] }}</label>
                        <input type="text" name="{{ $k }}_occupation_detail" id="{{ $k }}_occupation_detail"
                          class="form-control {{ $inv($k . '_occupation_detail') }}"
                          placeholder="Contoh: nama instansi atau jenis usaha"
                          value="{{ old($k . '_occupation_detail', $student->{$k . '_occupation_detail'}) }}">
                        <div class="invalid-feedback">{{ $errors->first($k . '_occupation_detail') }}</div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              {{-- ============ LANGKAH 4: AKADEMIK & ASRAMA ============ --}}
              <div class="form-step d-none" id="step4">
                <div class="iz-step-head">
                  <div class="iz-step-icon"><i class="bi bi-book"></i></div>
                  <div>
                    <h5 class="iz-step-title">Akademik &amp; asrama</h5>
                    <p class="iz-step-sub">Status saat ini dan tanggal masuk</p>
                  </div>
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="level" class="form-label">Jenjang Pendidikan</label>
                    <select name="education_level" id="level" class="form-select" disabled>
                      <option value="{{ $student->education_level }}" selected>{{ $student->education_level ?: '-' }}</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="class_group" class="form-label">Rombel / Kelas</label>
                    <input type="text" name="class_group" id="class_group" class="form-control"
                      value="{{ old('class_group', $student->class_group) }}" placeholder="-" disabled>
                  </div>
                  <div class="col-md-6">
                    <label for="dormitory" class="form-label">Gedung Asrama</label>
                    <input type="text" name="dormitory" id="dormitory" class="form-control"
                      value="{{ old('dormitory', $student->dormitory) }}" placeholder="-" disabled>
                  </div>
                  <div class="col-md-6">
                    <label for="room" class="form-label">Nama Kamar</label>
                    <input type="text" name="room" id="room" class="form-control"
                      value="{{ old('room', $student->room) }}" placeholder="-" disabled>
                  </div>
                  <div class="col-md-6">
                    <label for="acceptance_date" class="form-label">Tanggal Masuk</label>
                    <input type="date" name="acceptance_date" id="acceptance_date"
                      class="form-control {{ $inv('acceptance_date') }}"
                      value="{{ old('acceptance_date', $student->acceptance_date?->format('Y-m-d')) }}">
                    <div class="invalid-feedback">{{ $errors->first('acceptance_date') }}</div>
                  </div>
                  <div class="col-md-6">
                    <label for="status" class="form-label">Status Saat Ini <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select {{ $inv('status') }}" required>
                      <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                      <option value="moved" {{ old('status', $student->status) == 'moved' ? 'selected' : '' }}>Pindah</option>
                      <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Lulus</option>
                      <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>Skorsing</option>
                    </select>
                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                  </div>
                </div>

                <div class="iz-note" role="note">
                  <i class="bi bi-info-circle"></i>
                  <div>Kolom berwarna abu-abu tidak dapat diubah dari halaman ini.</div>
                </div>
              </div>

              {{-- Tombol navigasi (menempel di bawah layar pada mobile) --}}
              <div class="iz-actions">
                <button type="button" class="iz-btn iz-btn--light d-none" id="prevBtn">
                  <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary iz-btn ms-auto-btn grow" id="nextBtn">
                  Selanjutnya <i class="bi bi-arrow-right"></i>
                </button>
                <button type="submit" class="iz-btn iz-btn--ok ms-auto-btn grow d-none" id="submitBtn">
                  <i class="bi bi-save"></i> Simpan perubahan
                </button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    (function () {
      let currentStep = 1;
      const totalSteps = 4;

      const form = document.getElementById('studentForm');
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const submitBtn = document.getElementById('submitBtn');
      const stepItems = document.querySelectorAll('.iz-step');

      // Tampilkan langkah tertentu dan perbarui stepper + tombol
      function goTo(step) {
        currentStep = Math.min(Math.max(step, 1), totalSteps);

        document.querySelectorAll('.form-step').forEach(function (el, i) {
          el.classList.toggle('d-none', i + 1 !== currentStep);
        });

        stepItems.forEach(function (item) {
          const num = parseInt(item.dataset.step, 10);
          item.classList.remove('active', 'completed');
          if (num < currentStep) item.classList.add('completed');
          else if (num === currentStep) item.classList.add('active');
          if (num === currentStep) item.setAttribute('aria-current', 'step');
          else item.removeAttribute('aria-current');
        });

        document.getElementById('progressLine').style.width =
          ((currentStep - 1) / (totalSteps - 1)) * 100 + '%';

        prevBtn.classList.toggle('d-none', currentStep === 1);
        nextBtn.classList.toggle('d-none', currentStep === totalSteps);
        submitBtn.classList.toggle('d-none', currentStep !== totalSteps);
      }

      // Validasi isian wajib pada langkah tertentu
      function validateStep(step) {
        const fields = document.getElementById('step' + step).querySelectorAll('input[required], select[required]');
        for (const field of fields) {
          if (!field.checkValidity()) {
            // Langkah harus tampil dulu agar pesan validasi browser bisa muncul
            if (step !== currentStep) goTo(step);
            field.reportValidity();
            return false;
          }
        }
        return true;
      }

      function scrollToStepper() {
        document.getElementById('stepper').scrollIntoView({ behavior: 'smooth', block: 'start' });
      }

      prevBtn.addEventListener('click', function () { goTo(currentStep - 1); scrollToStepper(); });
      nextBtn.addEventListener('click', function () {
        if (!validateStep(currentStep)) return;
        goTo(currentStep + 1);
        scrollToStepper();
      });

      // Pada halaman edit semua langkah boleh langsung dibuka lewat stepper
      stepItems.forEach(function (item) {
        item.addEventListener('click', function () {
          goTo(parseInt(item.dataset.step, 10));
          scrollToStepper();
        });
      });

      // Enter pada kolom isian = lanjut ke langkah berikutnya (bukan langsung menyimpan)
      form.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && currentStep < totalSteps) {
          e.preventDefault();
          if (validateStep(currentStep)) goTo(currentStep + 1);
        }
      });

      // Validasi semua langkah saat simpan, lalu cegah klik ganda.
      // (Form memakai novalidate: isian wajib di langkah yang tersembunyi tidak lagi membuat form "diam" saja.)
      form.addEventListener('submit', function (e) {
        for (let s = 1; s <= totalSteps; s++) {
          if (!validateStep(s)) {
            e.preventDefault();
            return;
          }
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
      });

      // Kolom angka (NIK, No. KK): hanya digit + penghitung karakter
      document.querySelectorAll('input[data-digits]').forEach(function (input) {
        const counter = document.getElementById('count-' + (input.id === 'kk' ? 'kk' : input.id));
        const sync = function () {
          input.value = input.value.replace(/\D/g, '');
          if (counter) counter.textContent = input.value.length;
        };
        input.addEventListener('input', sync);
        sync();
      });

      // Jika ada error dari server: tandai langkah yang bermasalah dan langsung buka yang pertama
      const invalidFields = document.querySelectorAll('.is-invalid');
      if (invalidFields.length) {
        invalidFields.forEach(function (field) {
          const stepEl = field.closest('.form-step');
          if (!stepEl) return;
          const num = stepEl.id.replace('step', '');
          const marker = document.querySelector('.iz-step[data-step="' + num + '"]');
          if (marker) marker.classList.add('has-error');
        });

        const firstStep = invalidFields[0].closest('.form-step');
        goTo(firstStep ? parseInt(firstStep.id.replace('step', ''), 10) : 1);
        invalidFields[0].focus({ preventScroll: true });
        invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else {
        goTo(1);
      }
    })();
  </script>
@endpush