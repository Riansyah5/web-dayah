@extends('layouts.app')
@section('title', 'Pengaturan Sidebar')

@push('styles')
<style>
    :root { --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .card-header { background: white; border-bottom: 1px solid #f0f0f0; padding: 20px 25px; border-radius: 15px 15px 0 0 !important; }
    .setting-item { 
        transition: all 0.3s ease; 
        border-radius: 10px; 
        margin-bottom: 8px;
        border: 1px solid transparent;
    }
    .setting-item:hover { background-color: #f8f9fa; border-color: #e9ecef; transform: translateX(5px); }
    .menu-icon-wrapper {
        width: 40px; height: 40px;
        background: #f0f2f5;
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px; color: #555; margin-right: 15px;
    }
    .form-switch .form-check-input { width: 2.8em; height: 1.5em; cursor: pointer; }
    .btn-save { 
        background: var(--primary-gradient); border: none; 
        padding: 12px 30px; border-radius: 10px; font-weight: 600;
        transition: transform 0.2s;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); color: white; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Konfigurasi Menu</h5>
                        <small class="text-muted">Atur visibilitas menu sidebar aplikasi Anda</small>
                    </div>
                    <i class="bi bi-gear-wide-connected fs-4 text-muted"></i>
                </div>
                
                <div class="card-body p-4">
                    <form id="sidebarSettingsForm" action="{{ route('sidebar-settings.update') }}" method="POST">
                        @csrf
                        <div class="list-group list-group-flush mb-4">
                            @foreach($settings as $setting)
                            <div class="list-group-item setting-item d-flex align-items-center justify-content-between px-3 py-3 border-0">
                                <div class="d-flex align-items-center">
                                    <div class="menu-icon-wrapper">
                                        <i class="{{ $setting->icon ?? 'bi bi-list-nested' }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $setting->label }}</h6>
                                        <small class="text-muted">ID Menu: #{{ $setting->id }}</small>
                                    </div>
                                </div>
                                
                                <div class="form-check form-switch">
                                    <input type="hidden" name="settings[{{ $setting->id }}]" value="0">
                                    <input class="form-check-input" type="checkbox" 
                                           name="settings[{{ $setting->id }}]" value="1" 
                                           {{ $setting->is_active ? 'checked' : '' }}>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-4">
                            <button type="button" class="btn btn-light px-4 me-md-2" onclick="location.reload()">Batal</button>
                            <button type="submit" class="btn btn-primary btn-save">
                                <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('sidebarSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: "Apakah Anda yakin ingin memperbarui tampilan menu sidebar?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#f8f9fa',
            confirmButtonText: 'Ya, Terapkan',
            cancelButtonText: '<span style="color: #6c757d">Batal</span>',
            reverseButtons: true,
            customClass: {
                confirmButton: 'px-4 py-2 rounded-3',
                cancelButton: 'px-4 py-2 rounded-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Berikan feedback loading pada tombol
                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
                
                form.submit();
            }
        });
    });

    // Notifikasi Toast untuk UX yang lebih tidak mengganggu (non-intrusive)
    @if(session('success'))
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
    });
    @endif
</script>
@endpush