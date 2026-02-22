@extends('layouts.app')
@section('title', 'Tambah Akun')
@push('link')
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

@push('styles')
  <style>
      #tambah-akun-page {
          font-family: 'Poppins', sans-serif;
          color: #495057;
      }

      /* Card Modern Style */
      #tambah-akun-page .card-modern {
          border: none;
          border-radius: 16px;
          box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
          background: white;
          overflow: hidden;
      }

      /* Input Styling */
      #tambah-akun-page .form-control, #tambah-akun-page .form-select {
          padding: 0.75rem 1rem;
          border-radius: 10px;
          border: 1px solid #e0e6ed;
          background-color: #fcfdfe;
          font-size: 0.95rem;
          transition: all 0.2s;
      }

      #tambah-akun-page .form-control:focus, #tambah-akun-page .form-select:focus {
          box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
          border-color: #0d6efd;
          background-color: #fff;
      }

      /* Label Styling */
      #tambah-akun-page .form-label {
          font-weight: 500;
          font-size: 0.85rem;
          color: #6c757d;
          margin-bottom: 0.5rem;
      }

      /* Input Group Text (Icon Mata) */
      #tambah-akun-page .input-group-text {
          background-color: #fcfdfe;
          border: 1px solid #e0e6ed;
          border-left: none;
          border-top-right-radius: 10px;
          border-bottom-right-radius: 10px;
          cursor: pointer;
          color: #adb5bd;
      }
      
      /* Fix border radius for input next to icon */
      #tambah-akun-page .input-group .form-control {
          border-top-right-radius: 0;
          border-bottom-right-radius: 0;
          border-right: none;
      }

      #tambah-akun-page .input-group-text:hover {
          color: #0d6efd;
      }

      /* Button Styling */
      #tambah-akun-page .btn-primary-soft {
          background-color: #0d6efd;
          border: none;
          box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
          padding: 12px;
          font-weight: 500;
          border-radius: 10px;
          transition: 0.2s;
      }
      #tambah-akun-page .btn-primary-soft:hover {
          background-color: #0b5ed7;
          transform: translateY(-2px);
      }

      /* Switch Status Styling */
      #tambah-akun-page .form-switch .form-check-input {
          width: 3em;
          height: 1.5em;
          cursor: pointer;
      }
      #tambah-akun-page .status-text {
          font-weight: 600;
          font-size: 0.9rem;
          margin-left: 10px;
          transition: color 0.3s;
      }
  </style>
@endpush

@section('content')
  <div class="container" id="tambah-akun-page">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <div class="mb-3">
                    <a href="#" class="text-decoration-none text-muted small fw-medium">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke List User
                    </a>
                </div>

                <div class="card card-modern p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-person-plus-fill fs-4"></i>
                            </div>
                            <h4 class="fw-bold">Buat Akun Baru</h4>
                            <p class="text-muted small">Isi formulir di bawah untuk mendaftarkan user baru.</p>
                        </div>

                        <form id="form-tambah-akun" method="POST" action="{{ route('simpan-akun', $pegawai->id) }}">
                          @csrf
                            <div class="mb-3">
                                
                                <input type="hidden" id="nama" name="name" class="form-control" value="{{ $pegawai->nama }}">
                                <input type="hidden" id="updated_by" name="updated_by" class="form-control" value="{{ Auth::user()->name }}">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="masukkan username" value="{{ old('username') }}">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role Pengguna</label>
                                <select class="form-select" id="role" name="role">
                                    <option selected disabled>Pilih Role...</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Guru">Guru</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="masukkan email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
                                    <span class="input-group-text" onclick="togglePassword('password', 'icon-pass')">
                                        <i class="bi bi-eye-slash" id="icon-pass"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword" placeholder="Ulangi password">
                                    <span class="input-group-text" onclick="togglePassword('confirmPassword', 'icon-confirm')">
                                        <i class="bi bi-eye-slash" id="icon-confirm"></i>
                                    </span>
                                </div>
                                <div id="password-error" class="text-danger small mt-1"></div>
                            </div>

                            <div class="mb-4 bg-light p-3 rounded-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <label class="form-label mb-0 d-block text-dark">Status Akun</label>
                                    <small class="text-muted" style="font-size: 0.75rem;">Atur aktif atau nonaktif saat dibuat.</small>
                                </div>
                                <div class="form-check form-switch d-flex align-items-center">
                                    <!-- Input tersembunyi untuk nilai default 'nonaktif' -->
                                    <input type="hidden" name="status" value="Nonaktif">
                                    <!-- Switch yang akan mengirimkan 'aktif' jika dicentang -->
                                    <input class="form-check-input mt-0" type="checkbox" role="switch" id="statusSwitch" name="status" value="Aktif" checked>
                                    <span class="status-text text-success" id="statusLabel">Aktif</span>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-primary-soft text-white">
                                    Simpan User
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
      // 1. Fungsi Toggle Password (Hide/Unhide)
      function togglePassword(inputId, iconId) {
          const passwordInput = document.getElementById(inputId);
          const icon = document.getElementById(iconId);

          if (passwordInput.type === "password") {
              passwordInput.type = "text";
              icon.classList.remove("bi-eye-slash");
              icon.classList.add("bi-eye");
          } else {
              passwordInput.type = "password";
              icon.classList.remove("bi-eye");
              icon.classList.add("bi-eye-slash");
          }
      }

      // 2. Fungsi Ubah Label Status (Aktif/Nonaktif)
      const statusSwitch = document.getElementById('statusSwitch');
      const statusLabel = document.getElementById('statusLabel');

      statusSwitch.addEventListener('change', function() {
          if (this.checked) {
              statusLabel.textContent = "Aktif";
              statusLabel.classList.remove("text-danger");
              statusLabel.classList.add("text-success");
          } else {
              statusLabel.textContent = "Nonaktif";
              statusLabel.classList.remove("text-success");
              statusLabel.classList.add("text-danger");
          }
      });

      // 3. Fungsi Validasi Password Sebelum Submit
      document.getElementById('form-tambah-akun').addEventListener('submit', function(event) {
          // Ambil elemen input
          const passwordInput = document.getElementById('password');
          const confirmPasswordInput = document.getElementById('confirmPassword');
          const errorDiv = document.getElementById('password-error');

          // Ambil nilai dari input
          const password = passwordInput.value;
          const confirmPassword = confirmPasswordInput.value;

          // Cek apakah password sama
          if (password !== confirmPassword) {
              // Hentikan pengiriman form
              event.preventDefault(); 

              // Tampilkan pesan error
              errorDiv.textContent = 'Password dan konfirmasi password tidak sama.';
              confirmPasswordInput.classList.add('is-invalid'); // Tambah border merah
          } else {
              // Jika sama, hapus pesan error (jika ada)
              errorDiv.textContent = '';
              confirmPasswordInput.classList.remove('is-invalid');
          }
      });
  </script>
@endpush
