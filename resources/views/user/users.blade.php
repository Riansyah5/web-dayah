@extends('layouts.app')
@section('title', 'Users')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        /* Menggabungkan Inter untuk teks biasa, Poppins untuk Heading */
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        color: #334155;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
    }

    /* Card Styling Modern - SaaS Style */
    .card-modern {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        background: white;
        transition: all 0.3s ease;
    }

    /* Table Styling */
    .table-custom {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-custom th {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: 1px solid #e2e8f0;
        padding: 18px 15px;
        background-color: #f8fafc;
    }

    .table-custom th:first-child {
        border-top-left-radius: 16px;
    }

    .table-custom th:last-child {
        border-top-right-radius: 16px;
    }

    .table-custom td {
        vertical-align: middle;
        padding: 16px 15px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
    }

    .user-row:hover td {
        background-color: #f8fafc;
    }

    .table-custom tr:last-child td {
        border-bottom: none;
    }

    /* Avatar Styling */
    .avatar {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Badge Status Custom */
    .badge-soft-success {
        background-color: #ecfdf5;
        color: #059669;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        border: 1px solid #a7f3d0;
    }

    .badge-soft-danger {
        background-color: #fef2f2;
        color: #dc2626;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        border: 1px solid #fecaca;
    }

    .badge-soft-primary {
        background-color: #eff6ff;
        color: #2563eb;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        border: 1px solid #bfdbfe;
    }

    /* Form Controls & Search */
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        font-size: 0.9rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    .search-input {
        padding-left: 42px;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 14px center;
        background-color: #fff;
    }

    /* Action Buttons (Individual Soft Buttons) */
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        background: transparent;
        transition: all 0.2s ease;
        color: #64748b;
    }

    .btn-action.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .btn-action-edit:hover:not(.disabled) {
        background-color: #eff6ff;
        color: #2563eb;
    }
    
    .btn-action-role:hover:not(.disabled) {
        background-color: #fefce8;
        color: #ca8a04;
    }

    .btn-action-delete:hover:not(.disabled) {
        background-color: #fef2f2;
        color: #dc2626;
    }

    /* Primary Add Button */
    .btn-primary-custom {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        color: white;
    }

    /* Custom iOS-like Switch */
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.25em;
        margin-top: 0.1em;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
    .status-label {
        margin-left: 8px;
        cursor: pointer;
        user-select: none;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">User Management</h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Manage access and permissions for your team.</p>
        </div>
        <button class="btn btn-primary-custom rounded-3 px-4 py-2 d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Add User
        </button>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-5 col-lg-4">
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search users (name or email)...">
                </div>
                <div class="col-md-7 col-lg-8 d-flex justify-content-md-end gap-3">
                    <select id="roleFilter" class="form-select" style="width: 150px;">
                        <option value="all" selected>All Roles</option>
                        <option value="Admin">Admin</option>
                        <option value="Guru">Guru</option>
                    </select>
                    <select id="statusFilter" class="form-select" style="width: 150px;">
                        <option value="all" selected>All Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="ps-4">User Details</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Updated By</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @foreach ($users as $user)
                        @php
                            $isSuperAdmin = $user->role === 'Superadmin';
                            $super = $isSuperAdmin && Auth::user()->role !== 'Superadmin';
                            if ($isSuperAdmin && Auth::user()->role !== 'Superadmin') {
                                continue;
                            }
                        @endphp
                        <tr class="user-row" data-role="{{ $user->role }}" data-status="{{ $user->status }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&rounded=true" alt="Avatar" class="avatar me-3">
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark user-name" style="font-size: 0.95rem;">{{ $user->name }}</h6>
                                        <small class="text-muted user-email">{{ $user->username }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-soft-primary">{{ $user->role }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center {{ $user->status == 'Aktif' ? 'badge-soft-success' : 'badge-soft-danger' }}" id="badgeStatus{{ $user->id }}" style="width: max-content;">
                                    <div class="form-check form-switch mb-0 pb-0 d-flex align-items-center">
                                        <input class="form-check-input status-toggle m-0" type="checkbox" role="switch" id="statusSwitch{{ $user->id }}" data-user-id="{{ $user->id }}" {{ $user->status == 'Aktif' ? 'checked' : '' }} {{ $isSuperAdmin ? 'disabled' : '' }}>
                                        <label class="form-check-label status-label mb-0" for="statusSwitch{{ $user->id }}">{{ ucfirst($user->status) }}</label>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                            <td class="text-muted">{{ $user->updated_by ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ $isSuperAdmin ? 'javascript:void(0)' : route('users.editRole', $user->id) }}" class="btn-action btn-action-role {{ $isSuperAdmin ? 'disabled' : '' }}" data-bs-toggle="tooltip" title="Ubah Role">
                                        <i class="bi bi-shield-lock fs-5"></i>
                                    </a>

                                    <a href="{{ $isSuperAdmin ? 'javascript:void(0)' : route('user.edit', $user->id) }}" class="btn-action btn-action-edit {{ $isSuperAdmin ? 'disabled' : '' }}" data-bs-toggle="tooltip" title="Edit Profil">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </a>

                                    <button type="button" class="btn-action btn-action-delete delete-btn {{ $isSuperAdmin ? 'disabled' : '' }}" data-id="{{ $user->id }}" {{ $isSuperAdmin ? 'disabled' : '' }} data-bs-toggle="tooltip" title="Hapus User">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-white" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                <small class="text-muted">Showing data entries</small>
                <nav>
                    <ul class="pagination pagination-sm mb-0 gap-1">
                        <li class="page-item disabled"><a class="page-link border-0 text-muted rounded" href="#">Prev</a></li>
                        <li class="page-item active"><a class="page-link border-0 bg-primary text-white rounded shadow-sm" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 text-muted rounded" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 text-muted rounded" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session("success") }}',
        timer: 2000,
        showConfirmButton: false,
        customClass: {
            popup: 'rounded-4'
        }
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Script untuk filter tabel
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        const tableRows = document.querySelectorAll('.user-row');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const roleValue = roleFilter.value;
            const statusValue = statusFilter.value;

            tableRows.forEach(row => {
                const userName = row.querySelector('.user-name').textContent.toLowerCase();
                const userEmail = row.querySelector('.user-email').textContent.toLowerCase();
                const userRole = row.getAttribute('data-role');
                const userStatus = row.getAttribute('data-status');

                const matchesSearch = userName.includes(searchValue) || userEmail.includes(searchValue);
                const matchesRole = roleValue === 'all' || userRole === roleValue;
                const matchesStatus = statusValue === 'all' || userStatus === statusValue;

                if (matchesSearch && matchesRole && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('keyup', filterTable);
        roleFilter.addEventListener('change', filterTable);
        statusFilter.addEventListener('change', filterTable);

        // Script untuk update status (AJAX)
        const statusToggles = document.querySelectorAll('.status-toggle');
        statusToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const newStatus = this.checked ? 'Aktif' : 'Nonaktif';
                const statusLabel = this.nextElementSibling;
                const badgeWrapper = document.getElementById(`badgeStatus${userId}`);

                statusLabel.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                if (this.checked) {
                    badgeWrapper.classList.replace('badge-soft-danger', 'badge-soft-success');
                } else {
                    badgeWrapper.classList.replace('badge-soft-success', 'badge-soft-danger');
                }

                fetch(`/users/${userId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        this.checked = !this.checked;
                        statusLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                        if (this.checked) {
                            badgeWrapper.classList.replace('badge-soft-danger', 'badge-soft-success');
                        } else {
                            badgeWrapper.classList.replace('badge-soft-success', 'badge-soft-danger');
                        }
                        Swal.fire('Oops!', 'Gagal memperbarui status.', 'error');
                    }
                    return response.json();
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // Script untuk konfirmasi hapus
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const userId = this.getAttribute('data-id');
                const form = document.getElementById(`delete-form-${userId}`);
                const userName = this.closest('tr').querySelector('.user-name').textContent.trim();

                Swal.fire({
                    title: 'Hapus Pengguna?',
                    html: `Akun <b>${userName}</b> akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush