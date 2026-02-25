@extends('layouts.app')
@section('title', 'Data Kamar Santri')

@push('link')
<style>
    /* Custom Styling for Premium Look */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.5rem;
    }
    .table thead th {
        background-color: #fcfcfc;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05rem;
        font-weight: 700;
        color: #8c98a4;
        border-top: none;
        padding: 1rem;
    }
    /* Styling Group Header */
    .group-header {
        background-color: #f8fbff !important;
    }
    .badge-building {
        background: linear-gradient(45deg, #0d6efd, #0099ff);
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.15);
    }
    .room-name {
        font-weight: 600;
        color: #2d3748;
    }
    .warden-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .avatar-placeholder {
        width: 30px;
        height: 30px;
        background-color: #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: #718096;
    }
    .btn-edit {
        transition: all 0.2s;
        border-radius: 8px;
    }
    .btn-edit:hover {
        background-color: #198754;
        color: white;
        transform: scale(1.05);
    }
    /* Pagination Styling */
    .pagination {
        margin-top: 1.5rem;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold"><i class="bi bi-door-open-fill text-primary me-2"></i>Data Kamar Santri</h4>
                        <p class="text-muted small mb-0">Total kamar terdaftar: <strong>{{ $rooms->total() }}</strong> unit</p>
                    </div>
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary px-4 py-2 shadow-sm rounded-pill fw-bold">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kamar
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">No. & Nama Kamar</th>
                                    <th>Kapasitas</th>
                                    <th>Musyrif / Musyrifah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $groupedRooms = $rooms->groupBy(function($item) {
                                        return $item->dorm->name ?? 'Tanpa Gedung';
                                    });
                                @endphp

                                @forelse($groupedRooms as $dormName => $group)
                                    <tr class="group-header">
                                        <td colspan="4" class="py-3 ps-4">
                                            <span class="badge badge-building text-white shadow-sm">
                                                <i class="bi bi-building me-2"></i>GEDUNG: {{ strtoupper($dormName) }}
                                            </span>
                                        </td>
                                    </tr>

                                    @foreach($group as $index => $room)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <span class="text-muted me-3 small">{{ $loop->iteration }}.</span>
                                                <span class="room-name">{{ $room->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-people me-2 text-muted"></i>
                                                <span>{{ $room->capacity }} <span class="text-muted small">Orang</span></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="warden-info">
                                                <div class="avatar-placeholder">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-medium">{{ $room->warden->nama ?? 'Belum Ditentukan' }}</span>
                                                    @if($room->warden)
                                                        <span class="text-muted" style="font-size: 0.75rem;">Pembimbing</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu shadow-sm border-0">
                                                    <li>
                                                        <button type="button" class="dropdown-item btn-edit-room"
                                                            data-id="{{ $room->id }}"
                                                            data-name="{{ $room->name }}"
                                                            data-capacity="{{ $room->capacity }}"
                                                            data-dorm-id="{{ $room->dorm_id }}"
                                                            data-warden-id="{{ $room->warden_id }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
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
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            {{-- <img src="https://illustrations.popsy.co/flat/empty-box.svg" alt="Empty" style="width: 150px;" class="mb-3"> --}}
                                            <p class="text-muted">Belum ada data kamar yang tersedia.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 pb-4">
                    {{ $rooms->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Room -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Edit Data Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRoomForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label fw-medium">Nama Kamar</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_capacity" class="form-label fw-medium">Kapasitas</label>
                            <input type="number" class="form-control" id="edit_capacity" name="capacity" required>
                        </div>
                        {{-- Pastikan variabel $dorms dan $wardens dikirim dari controller --}}
                        @if(isset($dorms))
                        <div class="mb-3">
                            <label for="edit_dorm_id" class="form-label fw-medium">Gedung Asrama</label>
                            <select class="form-select" id="edit_dorm_id" name="dorm_id" required>
                                @foreach($dorms as $dorm)
                                    <option value="{{ $dorm->id }}">{{ $dorm->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(isset($wardens))
                        <div class="mb-3">
                            <label for="edit_warden_id" class="form-label fw-medium">Musyrif/Wali Kamar</label>
                            <select class="form-select" id="edit_warden_id" name="warden_id">
                                <option value="">-- Pilih Musyrif --</option>
                                @foreach($wardens as $warden)
                                    <option value="{{ $warden->id }}">{{ $warden->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
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
        // Handle Edit Button
        const editButtons = document.querySelectorAll('.btn-edit-room');
        const editModal = new bootstrap.Modal(document.getElementById('editRoomModal'));
        const editForm = document.getElementById('editRoomForm');
        const nameInput = document.getElementById('edit_name');
        const capacityInput = document.getElementById('edit_capacity');
        const dormSelect = document.getElementById('edit_dorm_id');
        const wardenSelect = document.getElementById('edit_warden_id');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const capacity = this.dataset.capacity;
                const dormId = this.dataset.dormId;
                const wardenId = this.dataset.wardenId;

                nameInput.value = name;
                capacityInput.value = capacity;
                if(dormSelect) dormSelect.value = dormId;
                if(wardenSelect) wardenSelect.value = wardenId;

                let url = "{{ route('rooms.update', ':id') }}";
                editForm.action = url.replace(':id', id);

                editModal.show();
            });
        });

        // Handle Delete Button
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Hapus Kamar?',
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
            title: 'Berhasil',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2500,
            background: '#ffffff',
            customClass: {
                popup: 'rounded-20'
            }
        });
    @endif
</script>
@endpush