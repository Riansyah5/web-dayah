@extends('layouts.app')
@section('title', 'Edit Data Pegawai')

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

      --iz-primary: #2196f3;
      --iz-primary-light: #e3f2fd;
      --iz-secondary: #673ab7;
      --iz-secondary-dark: #5e35b1;
      --iz-secondary-light: #ede7f6;
      --iz-success: #00a152;
      --iz-success-light: #e3f9ec;
      --iz-warning: #b26a00;
      --iz-warning-light: #fff4d6;
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
      gap: .4rem;
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

    /* ---------- Identitas pegawai yang sedang diedit ---------- */
    .iz-identity {
      display: flex;
      align-items: center;
      gap: .9rem;
      padding: 1rem 1.25rem;
    }
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
    .iz-chip--warn { background: var(--iz-warning-light); color: var(--iz-warning); }
    .iz-chip--late { background: var(--iz-danger-light); color: var(--iz-danger); }
    .iz-chip--muted { background: #eef2f6; color: var(--iz-muted); }

    /* ---------- Judul bagian formulir ---------- */
    .iz-section-head {
      display: flex;
      align-items: center;
      gap: .85rem;
      padding: 1rem 1.25rem;
      border-bottom: 1px solid var(--iz-line);
    }
    .iz-section-icon {
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
    .iz-section-title { font-size: 1rem; font-weight: 600; color: var(--iz-heading); margin: 0; line-height: 1.25; }
    .iz-section-sub { color: var(--iz-muted); font-size: .8rem; margin: 0; }
    .iz-tag {
      margin-left: auto;
      font-size: .72rem;
      font-weight: 600;
      color: var(--iz-muted);
      background: #eef2f6;
      border-radius: 6px;
      padding: .25rem .55rem;
    }
    .iz-section-body { padding: 1.25rem; }

    /* ---------- Kontrol formulir ---------- */
    .iz-form .form-label {
      font-size: .85rem;
      font-weight: 500;
      color: var(--iz-heading);
      margin-bottom: .4rem;
    }
    .iz-form .form-control,
    .iz-form .form-select,
    .iz-form .input-group-text {
      border: 1px solid #cdd5df;
      border-radius: 8px;
      font-size: .92rem;
      color: var(--iz-body);
    }
    .iz-form .form-control,
    .iz-form .form-select { padding: .65rem .95rem; background-color: var(--iz-surface); }
    .iz-form .input-group-text { background: var(--iz-soft); color: var(--iz-muted); padding: 0 .9rem; }
    .iz-form .input-group > .form-control:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
    .iz-form .input-group > .input-group-text:first-child { border-top-right-radius: 0; border-bottom-right-radius: 0; }
    .iz-form .form-control::placeholder { color: #9aa4b2; }
    .iz-form .form-control:focus,
    .iz-form .form-select:focus {
      border-color: var(--iz-secondary);
      box-shadow: 0 0 0 .2rem rgba(103, 58, 183, .15);
    }
    .iz-form .is-invalid { border-color: var(--iz-danger) !important; }
    .iz-form .invalid-feedback { font-size: .8rem; }
    .iz-form .form-text { font-size: .78rem; color: var(--iz-muted); }

    .iz-alert {
      display: flex;
      gap: .75rem;
      background: var(--iz-danger-light);
      color: #b71c1c;
      border: 1px solid #f7c6c4;
      border-radius: 10px;
      padding: .85rem 1rem;
      margin-bottom: 1rem;
      font-size: .9rem;
    }
    .iz-alert .bi { font-size: 1.1rem; margin-top: .1rem; }
    .iz-alert ul { margin: .35rem 0 0; padding-left: 1.1rem; }

    /* ---------- Bar aksi ---------- */
    .iz-actions {
      display: flex;
      justify-content: flex-end;
      gap: .6rem;
      padding: .25rem 0 1rem;
    }

    /* ---------- Mobile ---------- */
    @media (max-width: 767.98px) {
      .iz-page .page-header-title h2 { font-size: 1.25rem; }
      .iz-identity { padding: .9rem 1rem; }
      .iz-section-head { padding: .9rem 1rem; }
      .iz-section-body { padding: 1rem; }

      /* 16px mencegah iOS memperbesar layar saat input difokuskan */
      .iz-form .form-control,
      .iz-form .form-select { font-size: 16px; padding: .75rem .95rem; }

      /* Tombol simpan menempel di bawah layar */
      .iz-actions {
        position: sticky;
        bottom: 0;
        z-index: 10;
        margin: 0 -.75rem;
        padding: .75rem .75rem calc(.75rem + env(safe-area-inset-bottom, 0px));
        background: var(--iz-surface);
        border-top: 1px solid var(--iz-line);
        box-shadow: 0 -4px 16px rgba(18, 25, 38, .06);
      }
      .iz-actions .iz-btn { flex: 1 1 0; padding: .8rem 1rem; }
      .iz-actions .iz-btn--save { flex: 2 1 0; }
    }

    @media (prefers-reduced-motion: reduce) {
      .iz-page * { transition: none !important; }
    }
  </style>
@endpush

@section('content')
  @php
    $statusCls = match ($pegawai->status_pegawai) {
        'Aktif' => 'ok',
        'Cuti' => 'warn',
        'Keluar' => 'late',
        default => 'muted',
    };
    $tanggalLahir = $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d') : '';
    $tmt = $pegawai->terhitung_mulai_tanggal ? \Carbon\Carbon::parse($pegawai->terhitung_mulai_tanggal)->format('Y-m-d') : '';
  @endphp

  {{-- Tanpa wrapper .container: template Berry sudah membungkus konten dengan .pc-container / .pc-content --}}
  <div class="iz-page">

    {{-- Header halaman (gaya Berry) --}}
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-end g-3">
          <div class="col">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
              <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Data Pegawai</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ul>
          </div>
        </div>
        <div class="row align-items-end g-3">
          <div class="col">
            <div class="page-header-title">
              <h2>Edit Data Pegawai</h2>
            </div>
          </div>
          <div class="col-auto d-none d-md-block">
            <a href="{{ route('pegawai.index') }}" class="iz-btn iz-btn--light">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">

        {{-- Identitas pegawai yang sedang diedit --}}
        {{-- <div class="card iz-identity">
          <div class="iz-avatar">{{ mb_strtoupper(mb_substr($pegawai->nama, 0, 1)) }}</div>
          <div class="min-w-0">
            <div class="iz-identity-name">{{ $pegawai->nama }}</div>
            <div class="iz-identity-meta">{{ $pegawai->jabatan ?: '-' }} &middot; NIK {{ $pegawai->nik ?: '-' }}</div>
          </div>
          <span class="iz-chip iz-chip--{{ $statusCls }}">{{ $pegawai->status_pegawai }}</span>
        </div> --}}

        @if ($errors->any())
          <div class="iz-alert" role="alert" id="errorSummary">
            <i class="bi bi-exclamation-circle"></i>
            <div>
              <strong>Periksa kembali isian Anda.</strong>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="iz-form" id="pegawaiForm">
          @csrf
          @method('PUT')

          {{-- ============ DATA DIRI ============ --}}
          <div class="card">
            <div class="iz-section-head">
              <div class="iz-section-icon"><i class="bi bi-person-vcard"></i></div>
              <div>
                <h5 class="iz-section-title">Data diri</h5>
                <p class="iz-section-sub">Identitas pegawai sesuai KTP</p>
              </div>
            </div>
            <div class="iz-section-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                  <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                      name="nik" value="{{ old('nik', $pegawai->nik) }}" placeholder="Contoh: 3201..."
                      maxlength="16" inputmode="numeric" autocomplete="off" data-digits required>
                    @error('nik')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-text text-end"><span id="count-nik">0</span>/16 digit</div>
                </div>

                <div class="col-md-6">
                  <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                      name="nama" value="{{ old('nama', $pegawai->nama) }}" placeholder="Masukkan nama lengkap"
                      autocomplete="name" required>
                    @error('nama')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                  <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                    name="jenis_kelamin" required>
                    <option value="" disabled {{ old('jenis_kelamin', $pegawai->jenis_kelamin) ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki"
                      {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan"
                      {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="status_perkawinan" class="form-label">Status Perkawinan <span class="text-danger">*</span></label>
                  <select class="form-select @error('status_perkawinan') is-invalid @enderror" id="status_perkawinan"
                    name="status_perkawinan" required>
                    <option value="" disabled {{ old('status_perkawinan', $pegawai->status_perkawinan) ? '' : 'selected' }}>-- Pilih Status --</option>
                    <option value="Belum Menikah"
                      {{ old('status_perkawinan', $pegawai->status_perkawinan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                    <option value="Menikah"
                      {{ old('status_perkawinan', $pegawai->status_perkawinan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                  </select>
                  @error('status_perkawinan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir"
                    name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}"
                    placeholder="Contoh: Jakarta" required>
                  @error('tempat_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                  <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                    id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $tanggalLahir) }}" required>
                  @error('tanggal_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="no_kk" class="form-label">No. Kartu Keluarga <span class="text-muted fw-normal">(opsional)</span></label>
                  <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                    <input type="text" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk"
                      name="no_kk" value="{{ old('no_kk', $pegawai->no_kk) }}" placeholder="16 digit No. KK"
                      maxlength="16" inputmode="numeric" autocomplete="off" data-digits>
                    @error('no_kk')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-text text-end"><span id="count-no_kk">0</span>/16 digit</div>
                </div>

                <div class="col-md-6">
                  <label for="no_hp" class="form-label">No. HP</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                      name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="mis: 081234567890"
                      maxlength="16" inputmode="tel" autocomplete="tel">
                    @error('no_hp')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ============ ALAMAT ============ --}}
          <div class="card">
            <div class="iz-section-head">
              <div class="iz-section-icon"><i class="bi bi-geo-alt"></i></div>
              <div>
                <h5 class="iz-section-title">Alamat</h5>
                <p class="iz-section-sub">Alamat tempat tinggal pegawai</p>
              </div>
              <span class="iz-tag">Opsional</span>
            </div>
            <div class="iz-section-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="desa" class="form-label">Desa / Kelurahan</label>
                  <input type="text" class="form-control @error('desa') is-invalid @enderror" id="desa"
                    name="desa" value="{{ old('desa', $pegawai->desa) }}" placeholder="Nama Desa/Kelurahan">
                  @error('desa')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" id="kecamatan"
                    name="kecamatan" value="{{ old('kecamatan', $pegawai->kecamatan) }}" placeholder="Nama Kecamatan">
                  @error('kecamatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="kabupaten" class="form-label">Kabupaten / Kota</label>
                  <input type="text" class="form-control @error('kabupaten') is-invalid @enderror" id="kabupaten"
                    name="kabupaten" value="{{ old('kabupaten', $pegawai->kabupaten) }}"
                    placeholder="Nama Kabupaten/Kota">
                  @error('kabupaten')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="provinsi" class="form-label">Provinsi</label>
                  <input type="text" class="form-control @error('provinsi') is-invalid @enderror" id="provinsi"
                    name="provinsi" value="{{ old('provinsi', $pegawai->provinsi) }}" placeholder="Nama Provinsi">
                  @error('provinsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- ============ STATUS KEPEGAWAIAN ============ --}}
          <div class="card">
            <div class="iz-section-head">
              <div class="iz-section-icon"><i class="bi bi-briefcase"></i></div>
              <div>
                <h5 class="iz-section-title">Status kepegawaian</h5>
                <p class="iz-section-sub">Kategori, jabatan, dan masa kerja</p>
              </div>
            </div>
            <div class="iz-section-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="kategori_pegawai" class="form-label">Kategori Pegawai <span class="text-danger">*</span></label>
                  <select class="form-select @error('kategori_pegawai') is-invalid @enderror" id="kategori_pegawai"
                    name="kategori_pegawai" required>
                    <option value="" disabled {{ old('kategori_pegawai', $pegawai->kategori_pegawai) ? '' : 'selected' }}>-- Pilih Kategori --</option>
                    @foreach ($kategoris as $kategori)
                      <option value="{{ $kategori->name }}"
                        {{ old('kategori_pegawai', $pegawai->kategori_pegawai) == $kategori->name ? 'selected' : '' }}>
                        {{ $kategori->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('kategori_pegawai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="status_pegawai" class="form-label">Status Pegawai <span class="text-danger">*</span></label>
                  <select class="form-select @error('status_pegawai') is-invalid @enderror" id="status_pegawai"
                    name="status_pegawai" required>
                    <option value="" disabled {{ old('status_pegawai', $pegawai->status_pegawai) ? '' : 'selected' }}>-- Pilih Status --</option>
                    @foreach (['Aktif', 'Non-Aktif', 'Cuti', 'Keluar'] as $status)
                      <option value="{{ $status }}"
                        {{ old('status_pegawai', $pegawai->status_pegawai) == $status ? 'selected' : '' }}>
                        {{ $status }}
                      </option>
                    @endforeach
                  </select>
                  @error('status_pegawai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                  <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" id="jabatan" required>
                    <option value="" disabled {{ old('jabatan', $pegawai->jabatan) ? '' : 'selected' }}>-- Pilih Jabatan --</option>
                    @foreach ($jabatans as $jabatan)
                      <option value="{{ $jabatan->name }}"
                        {{ old('jabatan', $pegawai->jabatan) == $jabatan->name ? 'selected' : '' }}>
                        {{ $jabatan->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="terhitung_mulai_tanggal" class="form-label">Terhitung Mulai Tanggal (TMT) <span class="text-danger">*</span></label>
                  <input type="date" class="form-control @error('terhitung_mulai_tanggal') is-invalid @enderror"
                    id="terhitung_mulai_tanggal" name="terhitung_mulai_tanggal"
                    value="{{ old('terhitung_mulai_tanggal', $tmt) }}" required>
                  @error('terhitung_mulai_tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Tombol aksi (menempel di bawah layar pada mobile) --}}
          <div class="iz-actions">
            <a href="{{ route('pegawai.index') }}" class="iz-btn iz-btn--light">Batal</a>
            <button type="submit" class="btn btn-secondary iz-btn iz-btn--save" id="btnSubmit">
              <i class="bi bi-save"></i> Update data
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Kolom angka (NIK, No. KK): hanya digit + penghitung karakter
      document.querySelectorAll('input[data-digits]').forEach(function(input) {
        const counter = document.getElementById('count-' + input.id);

        const sync = function() {
          input.value = input.value.replace(/\D/g, '');
          if (counter) counter.textContent = input.value.length;
        };

        input.addEventListener('input', sync);
        sync();
      });

      // Cegah klik ganda saat menyimpan
      const form = document.getElementById('pegawaiForm');
      const btn = document.getElementById('btnSubmit');
      if (form && btn) {
        form.addEventListener('submit', function() {
          if (!form.checkValidity()) return;
          btn.disabled = true;
          btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
        });
      }

      // Jika ada error validasi dari server, arahkan ke isian pertama yang salah
      const firstInvalid = document.querySelector('.is-invalid');
      if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstInvalid.focus({ preventScroll: true });
      }
    });
  </script>
@endpush