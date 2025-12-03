@extends('layouts.app')
@section('title', 'Users')
@push('link')
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
@push('styles')
  <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6; /* Abu-abu sangat muda agar kartu menonjol */
            color: #495057;
        }

        /* Card Styling Modern */
        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: white;
            transition: all 0.3s ease;
        }

        /* Table Styling */
        .table-custom th {
            font-weight: 600;
            color: #8898aa;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f1f3f9;
            padding-bottom: 15px;
        }
        
        .table-custom td {
            vertical-align: middle;
            padding: 16px 10px;
            border-bottom: 1px solid #f1f3f9;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        /* Avatar Styling */
        .avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 12px; /* Rounded square lebih modern drpd circle */
        }

        /* Badge Status Custom */
        .badge-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 8px 12px;
            border-radius: 8px;
        }
        .badge-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 8px 12px;
            border-radius: 8px;
        }
        .badge-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            padding: 8px 12px;
            border-radius: 8px;
        }

        /* Action Buttons */
        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: 0.2s;
        }
        .btn-icon:hover {
            transform: translateY(-2px);
        }
        
        /* Search Input */
        .search-input {
            border-radius: 10px;
            border: 1px solid #e0e6ed;
            padding-left: 40px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23adb5bd' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 12px center;
        }
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark">User Management</h2>
                <p class="text-muted mb-0">Manage access and permissions for your team.</p>
            </div>
            <button class="btn btn-primary rounded-3 px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Add User
            </button>
        </div>

        <div class="card card-modern mb-4">
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control search-input py-2" placeholder="Search users (name or email)...">
                    </div>
                    <div class="col-md-3 ms-auto">
                        <select id="roleFilter" class="form-select border-0 bg-light py-2" style="border-radius: 10px;">
                            <option value="all" selected>All Roles</option>
                            <option value="Admin">Admin</option>
                            <option value="Guru">Guru</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="statusFilter" class="form-select border-0 bg-light py-2" style="border-radius: 10px;">
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
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Updated By</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                          @foreach ($users as $user)
                            <tr class="user-row" data-role="{{ $user->role }}" data-status="{{ $user->status }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random" alt="Avatar" class="avatar me-3 shadow-sm">
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark user-name">{{ $user->name }}</h6>
                                            <small class="text-muted user-email">{{ $user->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-soft-primary fw-normal">{{ $user->role }}</span></td>
                                <td>
                                    <div class="{{ $user->status == 'Aktif' ? 'badge-soft-success' : 'badge-soft-danger' }}" id="badgeStatus{{ $user->id }}">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" role="switch" id="statusSwitch{{ $user->id }}" data-user-id="{{ $user->id }}" {{ $user->status == 'Aktif' ? 'checked' : '' }}>
                                            <label class="form-check-label status-label" for="statusSwitch{{ $user->id }}">{{ ucfirst($user->status) }}</label>
                                        </div>
                                    </div>
                                    
                                </td>
                                <td class="text-muted">{{ $user->created_at }}</td>
                                <td class="text-muted">{{ $user->updated_by }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-icon btn-light text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-light text-danger delete-btn"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                          @endforeach
                            

                            <tr class="user-row" data-role="editor" data-status="inactive">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=John+Wick&background=0D8ABC&color=fff" alt="Avatar" class="avatar me-3 shadow-sm">
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark user-name">John Wick</h6>
                                            <small class="text-muted user-email">john@continental.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border fw-normal"><i class="bi bi-pencil me-1"></i> Editor</span></td>
                                <td><span class="badge-soft-danger fw-normal">Inactive</span></td>
                                <td class="text-muted">12 Oct, 2023</td>
                                <td class="text-end pe-4">
                                    <a href="#" class="btn btn-icon btn-light text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                    <a href="#" class="btn btn-icon btn-light text-danger"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>

                            <tr class="user-row" data-role="viewer" data-status="active">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Bruce+Wayne&background=333&color=fff" alt="Avatar" class="avatar me-3 shadow-sm">
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark user-name">Bruce Wayne</h6>
                                            <small class="text-muted user-email">bruce@wayneent.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border fw-normal"><i class="bi bi-person me-1"></i> Viewer</span></td>
                                <td><span class="badge-soft-success fw-normal">Active</span></td>
                                <td class="text-muted">05 Jan, 2024</td>
                                <td class="text-end pe-4">
                                    <a href="#" class="btn btn-icon btn-light text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                    <a href="#" class="btn btn-icon btn-light text-danger"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                    <small class="text-muted">Showing 1 to 3 of 50 entries</small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link border-0 text-muted" href="#">Prev</a></li>
                            <li class="page-item"><a class="page-link border-0 bg-primary text-white rounded shadow-sm" href="#">1</a></li>
                            <li class="page-item"><a class="page-link border-0 text-muted" href="#">2</a></li>
                            <li class="page-item"><a class="page-link border-0 text-muted" href="#">3</a></li>
                            <li class="page-item"><a class="page-link border-0 text-muted" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    {{-- sweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
    });
    </script>
    @endif

    <script>
      // Script untuk filter
      document.addEventListener('DOMContentLoaded', function() {
          // Ambil elemen input
          const searchInput = document.getElementById('searchInput');
          const roleFilter = document.getElementById('roleFilter');
          const statusFilter = document.getElementById('statusFilter');
          const tableRows = document.querySelectorAll('.user-row');

          // Fungsi utama untuk filter
          function filterTable() {
              // Ambil value dari input dan ubah ke lowercase untuk pencarian
              const searchValue = searchInput.value.toLowerCase();
              const roleValue = roleFilter.value;
              const statusValue = statusFilter.value;

              tableRows.forEach(row => {
                  // Ambil data dari baris saat ini
                  const userName = row.querySelector('.user-name').textContent.toLowerCase();
                  const userEmail = row.querySelector('.user-email').textContent.toLowerCase();
                  const userRole = row.getAttribute('data-role');
                  const userStatus = row.getAttribute('data-status');

                  // Cek Logika (Match Search && Match Role && Match Status)
                  const matchesSearch = userName.includes(searchValue) || userEmail.includes(searchValue);
                  const matchesRole = roleValue === 'all' || userRole === roleValue;
                  const matchesStatus = statusValue === 'all' || userStatus === statusValue;

                  // Jika semua kondisi terpenuhi, tampilkan baris. Jika tidak, sembunyikan.
                  if (matchesSearch && matchesRole && matchesStatus) {
                      row.style.display = ''; // Tampilkan (default table-row)
                  } else {
                      row.style.display = 'none'; // Sembunyikan
                  }
              });
          }

          // Pasang Event Listener agar filter jalan saat diketik atau dipilih
          searchInput.addEventListener('keyup', filterTable);
          roleFilter.addEventListener('change', filterTable);
          statusFilter.addEventListener('change', filterTable);

          // Script untuk update status
          const statusToggles = document.querySelectorAll('.status-toggle');
          statusToggles.forEach(toggle => {
              toggle.addEventListener('change', function() {
                  const userId = this.dataset.userId;
                  const newStatus = this.checked ? 'Aktif' : 'Nonaktif';
                  const statusLabel = this.nextElementSibling; // Ambil elemen label
                  const badgeWrapper = document.getElementById(`badgeStatus${userId}`);

                  // Update label text
                  statusLabel.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                  // Update badge class
                  if (this.checked) {
                      badgeWrapper.classList.replace('badge-soft-danger', 'badge-soft-success');
                  } else {
                      badgeWrapper.classList.replace('badge-soft-success', 'badge-soft-danger');
                  }

                  // Kirim request ke server
                  fetch(`/users/${userId}/status`, {
                      method: 'PATCH',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}' // Penting untuk keamanan Laravel
                      },
                      body: JSON.stringify({
                          status: newStatus
                      })
                  })
                  .then(response => {
                      if (!response.ok) {
                          // Jika gagal, kembalikan toggle ke posisi semula
                          this.checked = !this.checked;
                          statusLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                          // Kembalikan juga class badge
                          if (this.checked) {
                            badgeWrapper.classList.replace('badge-soft-danger', 'badge-soft-success');
                          } else {
                            badgeWrapper.classList.replace('badge-soft-success', 'badge-soft-danger');
                          }
                          alert('Gagal memperbarui status.');
                      }
                      return response.json();
                  })
                  .then(data => {
                      console.log(data.message); // Tampilkan pesan sukses di console
                      // Anda bisa menambahkan notifikasi toast di sini
                  }).catch(error => console.error('Error:', error));
              });
          });

          // Script untuk konfirmasi hapus
          const deleteButtons = document.querySelectorAll('.delete-btn');
          deleteButtons.forEach(button => {
              button.addEventListener('click', function(event) {
                  event.preventDefault(); // Mencegah form submit secara langsung

                  const form = this.closest('form');
                  const userName = form.closest('tr').querySelector('.user-name').textContent.trim();

                  Swal.fire({
                      title: 'Anda yakin?',
                      html: `Akun untuk "<b>${userName}</b>" akan dihapus secara permanen!`,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#dc3545',
                      cancelButtonColor: '#6c757d',
                      confirmButtonText: 'Ya, hapus!',
                      cancelButtonText: 'Batal'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          form.submit(); // Jika dikonfirmasi, submit form
                      }
                  });
              });
          });
      });
    </script>
@endpush
