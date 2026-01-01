@extends('layouts.app')
@section('title', 'Akun Saya')
@push('link')
@endpush
@push('styles')
  <style>
    :root {
      --primary-color: #673ab7;
      /* Berry Purple */
      --primary-dark: rgb(94, 53, 177);
      --bg-body: #eef2f6;
      --text-main: #364152;
      --text-secondary: #697586;
      --card-radius: 12px;
      --input-bg: #fafafa;
      --border-color: #e3e8ef;
    }

    body {
      font-family: 'Poppins', sans-serif;
      /* background-color: var(--bg-body); */
      color: var(--text-main);
      min-height: 100vh;
    }

    /* Modern Card */
    .modern-card {
      background: #ffffff;
      border: none;
      border-radius: var(--card-radius);
      box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    /* Profile Specifics */
    .profile-cover {
      height: 120px;
      /* background: linear-gradient(to right, var(--primary-color), var(--primary-dark)); */
      position: relative;
    }

    .avatar-container {
      width: 130px;
      height: 130px;
      margin: -65px auto 15px;
      position: relative;
    }

    .avatar-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
      border: 5px solid #ffffff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload-btn {
      position: absolute;
      bottom: 5px;
      right: 5px;
      width: 38px;
      height: 38px;
      background: white;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.2s;
      border: 3px solid #80ff00ff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload-btn:hover {
      transform: scale(1.1);
    }

    /* Form Styling */
    .form-label {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-secondary);
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-control {
      background-color: var(--input-bg);
      border: 2px solid transparent;
      padding: 0.8rem 1rem;
      border-radius: 12px;
      font-weight: 500;
      color: var(--text-main);
      transition: all 0.3s;
    }

    .form-control:focus {
      background-color: white;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgb(232, 196, 250);
    }

    /* Button */
    .btn-primary-modern {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border: none;
      padding: 12px 30px;
      border-radius: 12px;
      font-weight: 600;
      letter-spacing: 0.5px;
      /* box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4); */
      transition: all 0.3s;
      color: white;
    }

    .btn-primary-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 25px -5px rgb(94, 53, 177);
      color: white;
    }
  </style>
@endpush
@section('content')
  <div class="container py-5">
    <div class="row mb-4">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1 small text-muted">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Akun Saya</li>
          </ol>
        </nav>
        <h3 class="fw-bold text-dark">Pengaturan Akun</h3>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="modern-card text-center h-100 pb-4">
          <div class="profile-cover"></div>

          <div class="avatar-container">
            <img class="avatar-img"
              src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=673ab7&color=fff&size=200"
              id="imagePreview" alt="Profile Picture">
            <label for="imageUpload" class="avatar-upload-btn">
                            <img src="{{ asset('assets/images/logo_dayah.png') }}" alt="" class="img-fluid">
                        </label>
            <!-- <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" hidden /> -->
          </div>

          <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
          <p class="text-muted small mb-3">Administrator Sistem</p>

          <div class="d-flex justify-content-center gap-2 mb-4">
            <span class="badge rounded-pill px-3 py-2" style="background-color: #e8f5e9; color: #2e7d32;">
              <i class="fas fa-check-circle me-1"></i> Akun Aktif
            </span>
            <span class="badge rounded-pill px-3 py-2" style="background-color: #ede7f6; color: #673ab7;">
              <i class="fas fa-shield-alt me-1"></i> {{ $user->role }}
            </span>
          </div>

          <hr class="text-muted opacity-25 mx-4">

          <div class="text-start px-3 mt-4">
            <div class="mb-3">
              <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 11px;">Email
                Terdaftar</small>
              <h3><span class="badge bg-warning text-dark rounded-2">{{ $user->email ? $user->email : '-' }}</span></h3>
            </div>
            <div class="mb-3">
              <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 11px;">Bergabung
                Sejak</small>
              <span class="fw-medium">{{ Auth::user()->created_at->locale('id')->translatedFormat('l, d F Y - H:i') }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="modern-card h-100">
          <div class="card-header bg-transparent p-4 border-bottom" style="border-color: #f1f3f4 !important;">
            <ul class="nav nav-pills card-header-pills" id="myTab" role="tablist">
              <li class="nav-item">
                <span class="btn btn-secondary rounded-pill">Edit Profil</span>
              </li>
            </ul>
          </div>

          <div class="card-body p-4">
            <div class="tab-content" id="myTabContent">

              <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="row g-3">
                    <div class="col-md-6">
                      @php
                        $isSuperAdmin = $user->username === 'superadmin';
                      @endphp
                      {{-- hidden --}}
                      <input type="hidden" name="name" value="{{ $user->name }}">
                      <input type="hidden" name="status" value="{{ $user->status }}">
                      <input type="hidden" name="role" value="{{ $user->role }}">
                      <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                      <input type="hidden" name="source" value="profile">
                      {{--  --}}

                      <label class="form-label">Username</label>
                      <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}" {{ $isSuperAdmin ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                        placeholder="email belum diset">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Password Baru</label>
                      <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Konfirmasi Password</label>
                      <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Konfirmasi password baru">
                    </div>
                  </div>
                  <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary-modern">Simpan Perubahan</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  {{-- sweetAlert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Notifikasi Sukses
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @endif
  </script>
@endpush
