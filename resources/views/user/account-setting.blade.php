
@extends('layouts.app')

@section('title', 'Akun Saya')

@push('styles')
<style>
  :root {
    --profile-primary: #6d4aff;
    --profile-primary-dark: #5433d4;
    --profile-bg: #f6f7fb;
    --profile-text: #20243a;
    --profile-muted: #81879a;
    --profile-border: #eaecf2;
    --profile-radius: 20px;
  }

  /* Page */
  .account-page {
    min-height: 100vh;
    padding: 34px 0 60px;
    color: var(--profile-text);
  }

  .account-page .container {
    max-width: 1240px;
  }

  .account-heading {
    margin-bottom: 30px;
  }

  .account-breadcrumb {
    font-size: 13px;
    margin-bottom: 12px;
  }

  .account-breadcrumb a {
    color: var(--profile-muted);
    text-decoration: none;
  }

  .account-breadcrumb .breadcrumb-item.active {
    color: var(--profile-primary);
    font-weight: 600;
  }

  .account-title {
    font-size: clamp(25px, 3vw, 32px);
    font-weight: 750;
    letter-spacing: -1px;
    margin-bottom: 7px;
  }

  .account-subtitle {
    color: var(--profile-muted);
    font-size: 14px;
    margin-bottom: 0;
  }

  /* Cards */
  .account-card {
    height: 100%;
    background: #fff;
    border: 1px solid var(--profile-border);
    border-radius: var(--profile-radius);
    box-shadow: 0 8px 30px rgba(35, 38, 70, .035);
    overflow: hidden;
  }

  /* Profile card */
  .profile-cover {
    height: 150px;
    position: relative;
    overflow: hidden;
    background:
      radial-gradient(circle at 85% 10%,
        rgba(255,255,255,.30), transparent 25%),
      radial-gradient(circle at 15% 100%,
        rgba(255,255,255,.15), transparent 35%),
      linear-gradient(135deg, #7052f5, #4930bc);
  }

  .profile-cover::before,
  .profile-cover::after {
    content: "";
    position: absolute;
    border: 1px solid rgba(255,255,255,.17);
    border-radius: 50%;
  }

  .profile-cover::before {
    width: 220px;
    height: 220px;
    right: -45px;
    top: -125px;
  }

  .profile-cover::after {
    width: 170px;
    height: 170px;
    right: 30px;
    top: -90px;
  }

  .profile-content {
    padding: 0 25px 28px;
    text-align: center;
  }

  .profile-avatar-wrap {
    position: relative;
    width: 116px;
    height: 116px;
    margin: -58px auto 17px;
    z-index: 1;
  }

  .profile-avatar {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 5px solid #fff;
    border-radius: 50%;
    background: #eee8ff;
    box-shadow: 0 5px 18px rgba(30, 25, 70, .12);
  }

  .profile-online {
    position: absolute;
    width: 19px;
    height: 19px;
    bottom: 6px;
    right: 5px;
    border: 4px solid #fff;
    border-radius: 50%;
    background: #22c55e;
  }

  .profile-name {
    font-size: 21px;
    font-weight: 750;
    letter-spacing: -.5px;
    margin-bottom: 6px;
    overflow-wrap: anywhere;
  }

  .profile-username {
    font-size: 13px;
    color: var(--profile-muted);
    margin-bottom: 17px;
  }

  .profile-badges {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
  }

  .profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 30px;
    padding: 8px 12px;
    font-size: 11px;
    font-weight: 700;
  }

  .profile-badge-active {
    color: #16844a;
    background: #e9f9ef;
  }

  .profile-badge-role {
    color: #6544d9;
    background: #f0ebff;
  }

  .profile-divider {
    height: 1px;
    background: var(--profile-border);
    margin: 24px 0;
  }

  .profile-info-heading {
    font-size: 12px;
    font-weight: 750;
    color: var(--profile-text);
    text-align: left;
    margin-bottom: 18px;
  }

  .profile-info-item {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    text-align: left;
    margin-bottom: 20px;
    min-width: 0;
  }

  .profile-info-item:last-child {
    margin-bottom: 0;
  }

  .profile-info-icon {
    display: flex;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    color: var(--profile-primary);
    background: #f1edff;
    font-size: 15px;
  }

  .profile-info-label {
    display: block;
    color: var(--profile-muted);
    font-size: 11px;
    margin-bottom: 5px;
  }

  .profile-info-value {
    display: block;
    font-size: 13px;
    font-weight: 650;
    line-height: 1.6;
    overflow-wrap: anywhere;
  }

  /* Settings card */
  .settings-header {
    padding: 26px 30px;
    border-bottom: 1px solid var(--profile-border);
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .settings-header-icon {
    display: flex;
    flex: 0 0 48px;
    width: 48px;
    height: 48px;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    color: var(--profile-primary);
    background: #f1edff;
    font-size: 19px;
  }

  .settings-header h5 {
    font-size: 17px;
    font-weight: 750;
    margin-bottom: 5px;
  }

  .settings-header p {
    color: var(--profile-muted);
    font-size: 12px;
    margin-bottom: 0;
  }

  .settings-body {
    padding: 30px;
  }

  .settings-section-title {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 13px;
    font-weight: 750;
    margin-bottom: 20px;
  }

  .settings-section-title i {
    color: var(--profile-primary);
  }

  .account-form .form-label {
    display: block;
    color: #555b70;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 9px;
  }

  .account-form .form-control {
    min-height: 49px;
    border: 1px solid #e4e6ef;
    border-radius: 12px;
    padding: 12px 14px;
    color: var(--profile-text);
    background: #fff;
    font-size: 13px;
    font-weight: 500;
    transition: border-color .2s, box-shadow .2s;
  }

  .account-form .form-control::placeholder {
    color: #adb2c1;
    font-weight: 400;
  }

  .account-form .form-control:focus {
    border-color: var(--profile-primary);
    box-shadow: 0 0 0 4px rgba(109, 74, 255, .10);
  }

  .account-form .form-control[readonly] {
    background: #f7f8fc;
    color: #777d90;
    cursor: not-allowed;
  }

  .account-form .form-text {
    color: var(--profile-muted);
    font-size: 11px;
    margin-top: 7px;
  }

  .account-form .invalid-feedback {
    font-size: 12px;
  }

  .field-icon-wrap {
    position: relative;
  }

  .field-icon-wrap .form-control {
    padding-right: 44px;
  }

  .field-icon-wrap .field-icon {
    position: absolute;
    top: 50%;
    right: 16px;
    transform: translateY(-50%);
    color: #a1a6b7;
    pointer-events: none;
  }

  .password-note {
    display: flex;
    gap: 10px;
    padding: 14px 15px;
    margin-top: 24px;
    background: #f8f6ff;
    border: 1px solid #eee8ff;
    border-radius: 13px;
    color: #71619d;
    font-size: 12px;
    line-height: 1.7;
  }

  .password-note i {
    color: var(--profile-primary);
    margin-top: 2px;
  }

  .settings-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    border-top: 1px solid var(--profile-border);
    margin-top: 28px;
    padding-top: 23px;
  }

  .settings-footer-note {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--profile-muted);
    font-size: 11px;
  }

  .btn-account-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 47px;
    padding: 12px 22px;
    border: 0;
    border-radius: 12px;
    color: #fff;
    background: linear-gradient(135deg,
      var(--profile-primary), var(--profile-primary-dark));
    box-shadow: 0 5px 13px rgba(109, 74, 255, .20);
    font-size: 13px;
    font-weight: 700;
    transition: transform .2s, box-shadow .2s;
  }

  .btn-account-save:hover {
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(109, 74, 255, .28);
  }

  .btn-account-save:active {
    transform: translateY(0);
  }

  .account-footer {
    text-align: center;
    color: #a0a5b4;
    font-size: 11px;
    margin-top: 25px;
  }

  @media (max-width: 991.98px) {
    .account-page {
      padding-top: 24px;
    }

    .settings-header,
    .settings-body {
      padding: 23px;
    }

    .profile-content {
      padding-left: 22px;
      padding-right: 22px;
    }
  }

  @media (max-width: 575.98px) {
    .account-page {
      padding-top: 18px;
      padding-bottom: 30px;
    }

    .account-heading {
      margin-bottom: 22px;
    }

    .profile-cover {
      height: 125px;
    }

    .profile-avatar-wrap {
      width: 104px;
      height: 104px;
      margin-top: -52px;
    }

    .settings-header {
      padding: 20px;
    }

    .settings-body {
      padding: 20px;
    }

    .settings-header-icon {
      width: 42px;
      height: 42px;
      flex-basis: 42px;
    }

    .settings-footer {
      align-items: stretch;
    }

    .btn-account-save {
      width: 100%;
    }

    .settings-footer-note {
      line-height: 1.6;
    }
  }
</style>
@endpush

@section('content')
@php
    $isSuperAdmin = $user->username === 'superadmin';
    $roleLabel = $user->role ?: 'Pengguna';
    $joinedAt = $user->created_at;
@endphp

<div class="account-page">
  <div class="container">

    {{-- Page heading --}}
    <div class="account-heading">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb account-breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ url('/dashboard') }}">
              <i class="fas fa-home me-1"></i> Dashboard
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            Akun Saya
          </li>
        </ol>
      </nav>

      <h1 class="account-title">Pengaturan Akun</h1>
      <p class="account-subtitle">
        Kelola informasi profil dan keamanan akun Anda.
      </p>
    </div>

    <div class="row g-4">

      {{-- Profile card --}}
      <div class="col-lg-4">
        <div class="account-card">

          <div class="profile-cover"></div>

          <div class="profile-content">

            <div class="profile-avatar-wrap">
              <img
                class="profile-avatar"
                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6d4aff&color=ffffff&size=256&bold=true"
                alt="Foto profil {{ $user->name }}"
                loading="lazy"
              >
              @if($user->status === 'aktif')
                <span class="profile-online" title="Akun aktif"></span>
              @endif
            </div>

            <h2 class="profile-name">{{ $user->name }}</h2>

            <p class="profile-username">
              <i class="fas fa-at me-1"></i>{{ $user->username }}
            </p>

            <div class="profile-badges">
              <span class="profile-badge profile-badge-active">
                <i class="fas fa-check-circle"></i>
                {{ $user->status ?: 'Status tidak tersedia' }}
              </span>

              <span class="profile-badge profile-badge-role">
                <i class="fas fa-shield-alt"></i>
                {{ $roleLabel }}
              </span>
            </div>

            <div class="profile-divider"></div>

            <h3 class="profile-info-heading">
              INFORMASI AKUN
            </h3>

            <div class="profile-info-item">
              <div class="profile-info-icon">
                <i class="fas fa-envelope"></i>
              </div>

              <div class="flex-grow-1 min-w-0">
                <span class="profile-info-label">
                  Email terdaftar
                </span>
                <span class="profile-info-value">
                  {{ $user->email ?: 'Belum ada email' }}
                </span>
              </div>
            </div>

            <div class="profile-info-item">
              <div class="profile-info-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>

              <div class="flex-grow-1">
                <span class="profile-info-label">
                  Bergabung sejak
                </span>
                <span class="profile-info-value">
                  {{ $joinedAt
                      ? $joinedAt->locale('id')->translatedFormat('d F Y')
                      : '-' }}
                </span>
              </div>
            </div>

            <div class="profile-info-item">
              <div class="profile-info-icon">
                <i class="fas fa-user-shield"></i>
              </div>

              <div class="flex-grow-1">
                <span class="profile-info-label">
                  Tipe akun
                </span>
                <span class="profile-info-value">
                  {{ $roleLabel }}
                </span>
              </div>
            </div>

          </div>
        </div>
      </div>

      {{-- Account settings --}}
      <div class="col-lg-8">
        <div class="account-card">

          <div class="settings-header">
            <div class="settings-header-icon">
              <i class="fas fa-user-cog"></i>
            </div>

            <div>
              <h5>Informasi Profil</h5>
              <p>Perbarui username, email, dan password akun Anda.</p>
            </div>
          </div>

          <div class="settings-body">

            <form
              action="{{ route('user.update', $user->id) }}"
              method="POST"
              class="account-form"
            >
              @csrf
              @method('PUT')

              <input type="hidden" name="source" value="profile">

              <div class="settings-section-title">
                <i class="fas fa-id-card"></i>
                Informasi Login
              </div>

              <div class="row g-4">

                {{-- Username --}}
                <div class="col-md-6">
                  <label for="username" class="form-label">
                    Username
                  </label>

                  <div class="field-icon-wrap">
                    <input
                      type="text"
                      id="username"
                      name="username"
                      class="form-control @error('username') is-invalid @enderror"
                      value="{{ old('username', $user->username) }}"
                      placeholder="Masukkan username"
                      autocomplete="username"
                      maxlength="255"
                      required
                      {{ $isSuperAdmin ? 'readonly' : '' }}
                    >
                    <i class="fas fa-user field-icon"></i>
                  </div>

                  @error('username')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror

                  @if($isSuperAdmin)
                    <div class="form-text">
                      Username superadmin tidak dapat diubah.
                    </div>
                  @endif
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                  <label for="email" class="form-label">
                    Alamat Email
                  </label>

                  <div class="field-icon-wrap">
                    <input
                      type="email"
                      id="email"
                      name="email"
                      class="form-control @error('email') is-invalid @enderror"
                      value="{{ old('email', $user->email) }}"
                      placeholder="nama@contoh.com"
                      autocomplete="email"
                      maxlength="255"
                    >
                    <i class="fas fa-envelope field-icon"></i>
                  </div>

                  @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

              </div>

              <div class="profile-divider"></div>

              <div class="settings-section-title">
                <i class="fas fa-lock"></i>
                Keamanan Password
              </div>

              <div class="row g-4">

                {{-- New password --}}
                <div class="col-md-6">
                  <label for="password" class="form-label">
                    Password Baru
                  </label>

                  <div class="field-icon-wrap">
                    <input
                      type="password"
                      id="password"
                      name="password"
                      class="form-control @error('password') is-invalid @enderror"
                      placeholder="Masukkan password baru"
                      autocomplete="new-password"
                    >
                    <i class="fas fa-key field-icon"></i>
                  </div>

                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror

                  <div class="form-text">
                    Kosongkan jika tidak ingin mengganti password.
                  </div>
                </div>

                {{-- Password confirmation --}}
                <div class="col-md-6">
                  <label for="password_confirmation" class="form-label">
                    Konfirmasi Password
                  </label>

                  <div class="field-icon-wrap">
                    <input
                      type="password"
                      id="password_confirmation"
                      name="password_confirmation"
                      class="form-control"
                      placeholder="Ulangi password baru"
                      autocomplete="new-password"
                    >
                    <i class="fas fa-shield-alt field-icon"></i>
                  </div>

                  <div class="form-text">
                    Masukkan ulang password baru Anda.
                  </div>
                </div>

              </div>

              <div class="password-note">
                <i class="fas fa-info-circle"></i>
                <div>
                  <strong>Tips keamanan akun</strong><br>
                  Gunakan password yang kuat dan unik. Jangan bagikan
                  password kepada orang lain. Password lama tidak akan
                  berubah jika kolom password dibiarkan kosong.
                </div>
              </div>

              <div class="settings-footer">
                <div class="settings-footer-note">
                  <i class="fas fa-shield-alt"></i>
                  Perubahan akun akan diproses setelah disimpan.
                </div>

                <button type="submit" class="btn btn-account-save">
                  <i class="fas fa-save"></i>
                  Simpan Perubahan
                </button>
              </div>

            </form>
          </div>
        </div>
      </div>

    </div>

    <div class="account-footer">
      <i class="fas fa-lock me-1"></i>
      Halaman pengaturan akun pengguna
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  @if (session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: @json(session('success')),
      timer: 2500,
      timerProgressBar: true,
      showConfirmButton: false
    });
  @endif

  @if (session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: @json(session('error')),
      confirmButtonColor: '#6d4aff'
    });
  @endif
</script>
@endpush
