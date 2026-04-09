@extends('layouts.app')
@section('title', 'Edit User Access')
@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7f6;
    color: #495057;
  }

  .card-modern {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    background: white;
  }

  .avatar-lg {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 16px;
  }

  .perm-card {
    border: 1px solid #f1f3f9;
    border-radius: 12px;
    padding: 15px;
    transition: 0.2s;
  }

  .perm-card:hover {
    border-color: #0d6efd;
    background-color: #f8fbff;
  }

  /* Custom Switch Size */
  .form-switch .form-check-input {
    width: 2.5em;
    height: 1.25em;
    cursor: pointer;
  }

  .form-switch .form-check-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

</style>
@endpush

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-1 text-dark">Kelola Hak Akses</h2>
      <p class="text-muted mb-0">Atur role dan pengecualian hak akses untuk pengguna ini.</p>
    </div>
    <a href="{{ route('user.index') }}" class="btn btn-light rounded-3 px-4 py-2 border shadow-sm">
      <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
  </div>

  <div class="row">
    <div class="col-md-4 mb-4">
      <div class="card card-modern h-100">
        <div class="card-body text-center p-4">
          <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=128" alt="Avatar" class="avatar-lg mb-3 shadow-sm">
          <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
          <p class="text-muted mb-3">{{ $user->username }}</p>

          <hr class="text-muted">

          <div class="text-start mt-3">
            <label class="form-label fw-semibold text-dark">Role Utama (Jabatan)</label>
            <select id="roleSelect" class="form-select border-0 bg-light py-2 shadow-sm" style="border-radius: 10px;" data-user-id="{{ $user->id }}">
              <option value="-" disabled selected>pilih role</option>
              @foreach($roles as $role)
              <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
              @endforeach
            </select>
            <small class="text-muted mt-2 d-block">
              <i class="bi bi-info-circle"></i> Mengubah role akan memuat ulang halaman untuk menyesuaikan hak akses bawaan.
            </small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card card-modern">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">Pengaturan Izin Khusus (Direct Permissions)</h5>
            <div id="syncStatus" class="d-none small fw-semibold"></div>
          </div>
          <p class="text-muted mb-4">Berikan hak akses tambahan yang tidak ada di Role utama pengguna ini. Perubahan langsung tersimpan tanpa tombol Save.</p>

          <div class="permission-wrapper">
            @foreach($groupedPermissions as $groupName => $groupPerms)
            @if($groupPerms->count() > 0)
            <div class="permission-group mb-4">
              <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom">
                <i class="bi bi-folder2-open me-2"></i>{{ $groupName }}
              </h6>

              <div class="row g-3">
                @foreach($groupPerms as $perm)
                @php
                $isInherited = in_array($perm->name, $rolePermissions);
                $isDirect = in_array($perm->name, $directPermissions);
                $isChecked = $isInherited || $isDirect;
                @endphp
                <div class="col-md-6">
                  <div class="perm-card d-flex justify-content-between align-items-center">
                    <div>
                      <label class="fw-semibold text-dark d-block mb-0" for="perm-{{ $perm->id }}" style="cursor: pointer;">
                        {{ ucwords(str_replace('-', ' ', $perm->name)) }}
                      </label>
                      @if($isInherited)
                      <span class="badge bg-secondary opacity-75 mt-1" style="font-size: 0.7rem;">Bawaan Role</span>
                      @else
                      <span class="badge bg-light text-primary border mt-1" style="font-size: 0.7rem;">Akses Khusus</span>
                      @endif
                    </div>

                    <div class="form-check form-switch ms-3 mb-0">
                      <input class="form-check-input permission-toggle" type="checkbox" id="perm-{{ $perm->id }}" data-permission="{{ $perm->name }}" data-user-id="{{ $user->id }}" {{ $isChecked ? 'checked' : '' }} {{ $isInherited ? 'disabled' : '' }}>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    const Toast = Swal.mixin({
      toast: true
      , position: 'top-end'
      , showConfirmButton: false
      , timer: 2000
      , timerProgressBar: true
    , });

    // 1. Logika Update Role
    const roleSelect = document.getElementById('roleSelect');
    roleSelect.addEventListener('change', function() {
      const userId = this.dataset.userId;
      const newRole = this.value;

      Swal.fire({
        title: 'Ubah Role?'
        , text: "Hak akses bawaan pengguna akan disesuaikan dengan role baru."
        , icon: 'warning'
        , showCancelButton: true
        , confirmButtonColor: '#0d6efd'
        , cancelButtonColor: '#6c757d'
        , confirmButtonText: 'Ya, Ubah!'
        , cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {

          fetch(`/users/${userId}/update-role`, {
              method: 'PATCH'
              , headers: {
                'Content-Type': 'application/json'
                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
              , body: JSON.stringify({
                role: newRole
              })
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                Swal.fire({
                  icon: 'success'
                  , title: 'Berhasil'
                  , text: 'Role diubah. Memuat ulang hak akses...'
                  , showConfirmButton: false
                  , timer: 1500
                }).then(() => {
                  window.location.reload(); // Reload agar UI Checkbox menyesuaikan Role baru
                });
              } else {
                throw new Error(data.message);
              }
            })
            .catch(error => {
              Swal.fire('Error', error.message, 'error');
              setTimeout(() => window.location.reload(), 2000); // revert pilihan dropdown
            });
        } else {
          // Jika batal, kembalikan ke pilihan semula (opsional, perlu simpan state awal)
          window.location.reload();
        }
      });
    });

    // 2. Logika Toggle Direct Permission
    const permissionToggles = document.querySelectorAll('.permission-toggle:not(:disabled)');
    const syncStatus = document.getElementById('syncStatus');

    permissionToggles.forEach(toggle => {
      toggle.addEventListener('change', function() {
        const userId = this.dataset.userId;
        const permissionName = this.dataset.permission;
        const isChecked = this.checked;

        // Animasi status saving
        syncStatus.classList.remove('d-none');
        syncStatus.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
        syncStatus.classList.replace('text-success', 'text-warning');

        fetch(`/users/${userId}/toggle-permission`, {
            method: 'PATCH'
            , headers: {
              'Content-Type': 'application/json'
              , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            , body: JSON.stringify({
              permission: permissionName
              , state: isChecked
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              // Ubah indikator menjadi sukses
              syncStatus.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Tersimpan';
              syncStatus.classList.replace('text-warning', 'text-success');

              Toast.fire({
                icon: 'success'
                , title: isChecked ? 'Akses diberikan' : 'Akses dicabut'
              });

              // Sembunyikan indikator setelah 2 detik
              setTimeout(() => syncStatus.classList.add('d-none'), 2000);
            } else {
              throw new Error(data.message);
            }
          })
          .catch(error => {
            // Revert toggle jika error
            this.checked = !isChecked;
            syncStatus.classList.add('d-none');
            Swal.fire('Gagal Menyimpan', error.message, 'error');
          });
      });
    });

  });

</script>
@endpush
