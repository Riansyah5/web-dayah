@extends('layouts.app')
@section('title', 'Edit Data Pegawai')
@push('link')
  {{-- Tambahkan link untuk Bootstrap Icons di sini --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
@push('styles')
  <style>
    /* Sedikit kustomisasi agar terlihat lebih modern */
    body {
      background-color: #f8f9fa;
    }

    .card-header {
      background-color: #0d6efd;
      color: white;
    }

    .form-label {
      font-weight: 600;
    }
  </style> 
@endpush
@section('content') {{-- Memulai bagian konten utama --}}

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">

        <div class="card shadow-sm border-0">
          <div class="card-header py-3">
            <h4 class="mb-0 text-white"><i class="bi bi-person-plus-fill me-2"></i>Formulir Data Pegawai</h4>
          </div>
          <div class="card-body p-4 p-md-5">

            <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
              @csrf
              @method('PUT')

              <h5 class="mb-3">Data Diri</h5>
              <hr class="mt-0">

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik"
                      value="{{ old('nik', $pegawai->nik) }}" placeholder="Contoh: 3201..." maxlength="16" required>
                    @error('nik')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="nama" class="form-label">Nama Lengkap</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                      value="{{ old('nama', $pegawai->nama) }}" placeholder="Masukkan nama lengkap" required>
                    @error('nama')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                  <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                    name="jenis_kelamin" required>
                    <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki"
                      {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                    </option>
                    <option value="Perempuan"
                      {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                    </option>
                  </select>
                  @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
                  <select class="form-select @error('status_perkawinan') is-invalid @enderror" id="status_perkawinan"
                    name="status_perkawinan" required>
                    <option value="" disabled>-- Pilih Status --</option>
                    <option value="Belum Menikah"
                      {{ old('status_perkawinan', $pegawai->status_perkawinan) == 'Belum Menikah' ? 'selected' : '' }}>
                      Belum Menikah</option>
                    <option value="Menikah"
                      {{ old('status_perkawinan', $pegawai->status_perkawinan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                  </select>
                  @error('status_perkawinan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                  <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir"
                    name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}"
                    placeholder="Contoh: Jakarta" required>
                  @error('tempat_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                  <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                    id="tanggal_lahir" name="tanggal_lahir"
                    value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d')) }}" required>
                  @error('tanggal_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="no_kk" class="form-label">No. Kartu Keluarga (Opsional)</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                    <input type="text" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk"
                      name="no_kk" value="{{ old('no_kk', $pegawai->no_kk) }}" placeholder="16 digit No. KK" maxlength="16">
                    @error('no_kk')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="no_hp" class="form-label">No. HP</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp"
                      value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="mis: 081234567890" maxlength="16">
                    @error('no_hp')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <h5 class="mt-5 mb-3">Alamat (Opsional)</h5>
              <hr class="mt-0">

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="desa" class="form-label">Desa / Kelurahan</label>
                  <input type="text" class="form-control @error('desa') is-invalid @enderror" id="desa" name="desa"
                    value="{{ old('desa', $pegawai->desa) }}" placeholder="Nama Desa/Kelurahan">
                </div>
                <div class="col-md-6">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" id="kecamatan"
                    name="kecamatan" value="{{ old('kecamatan', $pegawai->kecamatan) }}" placeholder="Nama Kecamatan">
                </div>
                <div class="col-md-6">
                  <label for="kabupaten" class="form-label">Kabupaten / Kota</label>
                  <input type="text" class="form-control @error('kabupaten') is-invalid @enderror" id="kabupaten"
                    name="kabupaten" value="{{ old('kabupaten', $pegawai->kabupaten) }}" placeholder="Nama Kabupaten/Kota">
                </div>
                <div class="col-md-6">
                  <label for="provinsi" class="form-label">Provinsi</label>
                  <input type="text" class="form-control @error('provinsi') is-invalid @enderror" id="provinsi"
                    name="provinsi" value="{{ old('provinsi', $pegawai->provinsi) }}" placeholder="Nama Provinsi">
                </div>
              </div>

              <h5 class="mt-5 mb-3">Status Kepegawaian</h5>
              <hr class="mt-0">

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="status_pegawai" class="form-label">Status Pegawai</label>
                  <select class="form-select @error('status_pegawai') is-invalid @enderror" id="status_pegawai"
                    name="status_pegawai" required>
                    <option value="" disabled>-- Pilih Status --</option>
                    @foreach ($kategoris as $kategori)
                      <option value="{{ $kategori->name }}"
                        {{ old('status_pegawai', $pegawai->status_pegawai) == $kategori->name ? 'selected' : '' }}>
                        {{ $kategori->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('status_pegawai')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="jabatan" class="form-label">Jabatan</label>
                  <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" id="jabatan"
                    required>
                    <option value="" disabled>-- Pilih Jabatan --</option>
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
                  <label for="terhitung_mulai_tanggal" class="form-label">Terhitung Mulai Tanggal (TMT)</label>
                  <input type="date" class="form-control @error('terhitung_mulai_tanggal') is-invalid @enderror"
                    id="terhitung_mulai_tanggal" name="terhitung_mulai_tanggal" value="{{ old('terhitung_mulai_tanggal', \Carbon\Carbon::parse($pegawai->terhitung_mulai_tanggal)->format('Y-m-d')) }}"
                    required>
                  @error('terhitung_mulai_tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <hr class="my-4">

              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                {{-- <button type="reset" class="btn btn-outline-secondary">Reset Form</button> --}}
                <button type="submit" class="btn btn-primary px-4">
                  <i class="bi bi-save-fill me-2"></i>Update Data
                </button>
              </div>

            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
