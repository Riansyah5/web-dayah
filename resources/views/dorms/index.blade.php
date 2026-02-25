@extends('layouts.app')
@section('title', 'Daftar Gedung Asrama')

@push('link')
<style>
    /* Desain Premium Custom */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.5rem;
    }
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 600;
        color: #6c757d;
        border: none;
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem;
        color: #495057;
    }
    .badge-gender {
        padding: 0.5em 1em;
        border-radius: 50px;
        font-weight: 500;
    }
    .bg-soft-primary { background-color: #e7f1ff; color: #0d6efd; }
    .bg-soft-danger { background-color: #ffe5e5; color: #dc3545; }
    
    .btn-create {
        transition: all 0.3s ease;
        border-radius: 8px;
        font-weight: 500;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10"> {{-- Diperlebar agar lebih lega --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-building-fill text-primary me-2"></i> Manajemen Asrama
                        </h4>
                        <small class="text-muted">Kelola data gedung dan kapasitas hunian</small>
                    </div>
                    <a href="{{ route('dorms.create') }}" class="btn btn-primary btn-create px-4">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Gedung
                    </a>
                </div>
                
                <div class="card-body p-0"> {{-- P-0 untuk tabel full-width ke pinggir card --}}
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Nama Gedung</th>
                                    <th>Kategori/Khusus</th>
                                    <th>Kapasitas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dorms as $dorm)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-shape bg-light p-2 rounded me-3">
                                                    <i class="bi bi-door-closed text-secondary"></i>
                                                </div>
                                                <span class="fw-bold">{{ $dorm->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($dorm->gender == 'L')
                                                <span class="badge badge-gender bg-soft-primary text-uppercase">
                                                    <i class="bi bi-gender-male"></i> Putra
                                                </span>
                                            @else
                                                <span class="badge badge-gender bg-soft-danger text-uppercase">
                                                    <i class="bi bi-gender-female"></i> Putri
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium">{{ $dorm->rooms->count() }}</span>
                                            <span class="text-muted small"> Unit Kamar</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu shadow-sm border-0">
                                                    <li>
                                                        <button type="button" class="dropdown-item btn-edit" 
                                                            data-id="{{ $dorm->id }}" 
                                                            data-name="{{ $dorm->name }}" 
                                                            data-gender="{{ $dorm->gender }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('dorms.destroy', $dorm->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger btn-delete">
                                                                <i class="bi bi-trash me-2"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            Belum ada data gedung yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editDormModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Edit Data Asrama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDormForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label fw-medium">Nama Gedung</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_gender" class="form-label fw-medium">Kategori Penghuni</label>
                            <select class="form-select" id="edit_gender" name="gender" required>
                                <option value="L">Putra</option>
                                <option value="P">Putri</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Edit Button Click
        const editButtons = document.querySelectorAll('.btn-edit');
        const editModal = new bootstrap.Modal(document.getElementById('editDormModal'));
        const editForm = document.getElementById('editDormForm');
        const nameInput = document.getElementById('edit_name');
        const genderInput = document.getElementById('edit_gender');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const gender = this.getAttribute('data-gender');

                // Populate form
                nameInput.value = name;
                genderInput.value = gender;

                // Update form action URL dynamically
                let urlTemplate = "{{ route('dorms.update', ':id') }}";
                editForm.action = urlTemplate.replace(':id', id);

                editModal.show();
            });
        });

        // Handle Delete Button Click
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Gedung?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2500,
            background: '#fff',
            iconColor: '#0d6efd',
            customClass: {
                popup: 'rounded-20'
            }
        });
    @endif
</script>
@endpush