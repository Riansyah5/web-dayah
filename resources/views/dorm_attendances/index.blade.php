@extends('layouts.app')
@section('title', 'Absensi Pengasuhan Asrama')

@push('styles')
<style>
    .card-stat {
        border-radius: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-stat:hover {
        transform: translateY(-2px);
    }
    .badge-session {
        font-size: 0.72rem;
        padding: 4px 10px;
        border-radius: 50rem;
        font-weight: 600;
        display: inline-block;
    }
    .stat-pill {
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
    }
    .icon-box {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    @media (max-width: 576px) {
        .icon-box {
            width: 36px;
            height: 36px;
        }
        .icon-box i {
            font-size: 1.15rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="bi bi-card-checklist text-primary me-2 fs-3"></i>Absensi Pengasuhan Asrama
            </h4>
            <p class="text-muted small mb-0">Kelola dan pantau kehadiran santri di asrama per sesi sholat & apel malam.</p>
        </div>
        <div class="d-flex w-100 w-sm-auto gap-2">
            <a href="{{ route('dorm-attendance.recap') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 flex-fill flex-sm-grow-0 text-nowrap shadow-sm">
                <i class="bi bi-bar-chart me-1"></i> Rekap
            </a>
            <a href="{{ route('dorm-attendance.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 flex-fill flex-sm-grow-0 text-nowrap shadow-sm">
                <i class="bi bi-plus me-1"></i> Input Absensi
            </a>
        </div>
    </div>

    <!-- Quick Stats Card -->
    <div class="row g-2 g-md-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card card-stat shadow-sm border-0 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Hadir</span>
                        <h4 class="fw-bold text-success mb-0">{{ $todaySummary->present ?? 0 }}</h4>
                    </div>
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="ti ti-user-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-stat shadow-sm border-0 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Sakit</span>
                        <h4 class="fw-bold text-primary mb-0">{{ $todaySummary->sick ?? 0 }}</h4>
                    </div>
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-bandaid fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-stat shadow-sm border-0 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Izin / Pulang</span>
                        <h4 class="fw-bold text-warning mb-0">{{ $todaySummary->permission ?? 0 }}</h4>
                    </div>
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-box-arrow-right fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-stat shadow-sm border-0 bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Alpa / Gaib</span>
                        <h4 class="fw-bold text-danger mb-0">{{ $todaySummary->alpha ?? 0 }}</h4>
                    </div>
                    <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section (Collapsible on Mobile) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex d-md-none justify-content-between align-items-center mb-2" data-bs-toggle="collapse" data-bs-target="#filterSection" style="cursor: pointer;">
                <span class="fw-semibold small text-muted"><i class="bi bi-funnel me-1"></i> Filter Pencarian</span>
                <i class="bi bi-chevron-down text-muted"></i>
            </div>
            
            <div class="collapse d-md-block" id="filterSection">
                <form action="{{ route('dorm-attendance.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Tanggal</label>
                        <input type="date" name="date" class="form-control form-control-sm rounded-3" value="{{ request('date') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Gedung Asrama</label>
                        <select name="dorm_id" class="form-select form-select-sm rounded-3" id="filter_dorm_id">
                            <option value="">Semua Asrama</option>
                            @foreach($dorms as $dorm)
                                <option value="{{ $dorm->id }}" {{ request('dorm_id') == $dorm->id ? 'selected' : '' }}>
                                    {{ $dorm->name }} ({{ $dorm->gender == 'L' ? 'Putra' : 'Putri' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Kamar</label>
                        <select name="room_id" class="form-select form-select-sm rounded-3" id="filter_room_id">
                            <option value="">Semua Kamar</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-dorm="{{ $room->dorm_id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->dorm->name ?? '' }} - {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Sesi</label>
                        <select name="session" class="form-select form-select-sm rounded-3">
                            <option value="">Semua Sesi</option>
                            <option value="subuh" {{ request('session') == 'subuh' ? 'selected' : '' }}>Subuh</option>
                            <option value="maghrib" {{ request('session') == 'maghrib' ? 'selected' : '' }}>Maghrib</option>
                            <option value="isya" {{ request('session') == 'isya' ? 'selected' : '' }}>Isya</option>
                            <option value="malam" {{ request('session') == 'malam' ? 'selected' : '' }}>Malam / Apel</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 w-100 py-1">
                            <i class="bi bi-funnel me-1"></i> Terapkan
                        </button>
                        @if(request()->anyFilled(['date', 'dorm_id', 'room_id', 'session']))
                            <a href="{{ route('dorm-attendance.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3" title="Reset Filter">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Attendance Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light border-bottom border-1">
                    <tr>
                        <th class="ps-3 ps-md-4 py-3">Tanggal & Sesi</th>
                        <th class="py-3">Asrama / Kamar</th>
                        <th class="py-3">Pengabsen</th>
                        <th class="py-3">Detail Kehadiran</th>
                        <th class="text-end pe-3 pe-md-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($attendances as $item)
                        @php
                            $sessionColors = [
                                'subuh' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
                                'maghrib' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                'isya' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                'malam' => 'bg-dark bg-opacity-10 text-dark border border-dark border-opacity-25',
                            ];
                        @endphp
                        <tr>
                            <td class="ps-3 ps-md-4">
                                <span class="fw-semibold text-dark d-block text-truncate" style="max-width: 170px;">
                                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                                </span>
                                <span class="badge {{ $sessionColors[$item->session] ?? 'bg-secondary' }} badge-session mt-1">
                                    {{ $item->session_label }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">
                                    <i class="bi bi-building me-1 text-muted"></i>{{ $item->dorm->name ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    @if($item->room)
                                        <i class="bi bi-door-closed me-1"></i>{{ $item->room->name }}
                                        @if($item->room->warden)
                                            <span class="text-truncate d-inline-block align-bottom" style="max-width: 140px;">
                                                ({{ $item->room->warden->nama }})
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-secondary border">Seluruh Kamar</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-dark small fw-medium">
                                    <i class="bi bi-person me-1 text-muted"></i>{{ $item->recorder->name ?? 'Sistem' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    {{ $item->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="stat-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25" title="Hadir">{{ $item->present_count }} H</span>
                                    <span class="stat-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" title="Sakit">{{ $item->sick_count }} S</span>
                                    <span class="stat-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25" title="Izin">{{ $item->permit_count }} I</span>
                                    <span class="stat-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25" title="Alpa">{{ $item->alpha_count }} A</span>
                                    <span class="badge bg-secondary-subtle text-secondary ms-1 fw-normal">Tot: {{ $item->total_students }}</span>
                                </div>
                            </td>
                            <td class="text-end pe-3 pe-md-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dorm-attendance.show', $item->id) }}" class="btn btn-light text-info border" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('dorm-attendance.edit', $item->id) }}" class="btn btn-light text-warning border" title="Edit Absensi">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-light text-danger border delete-btn" data-url="{{ route('dorm-attendance.destroy', $item->id) }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1 opacity-50 d-block mb-2"></i>
                                <span>Belum ada riwayat absensi pengasuhan asrama yang sesuai dengan filter.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                <div class="d-flex justify-content-center justify-content-md-end">
                    {{ $attendances->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Perhatian',
                text: @json(session('error')),
            });
        @endif

        // Dynamic Room Filter
        const dormSelect = document.getElementById('filter_dorm_id');
        const roomSelect = document.getElementById('filter_room_id');
        if (dormSelect && roomSelect) {
            dormSelect.addEventListener('change', function () {
                const selectedDorm = this.value;
                Array.from(roomSelect.options).forEach(opt => {
                    if (!opt.value) return;
                    const optDorm = opt.getAttribute('data-dorm');
                    opt.style.display = (!selectedDorm || optDorm === selectedDorm) ? 'block' : 'none';
                });
                if (selectedDorm && roomSelect.selectedOptions[0] && roomSelect.selectedOptions[0].getAttribute('data-dorm') !== selectedDorm) {
                    roomSelect.value = '';
                }
            });
        }

        // SweetAlert Delete Confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                Swal.fire({
                    title: 'Hapus Sesi Absensi?',
                    text: 'Seluruh data absensi santri pada sesi ini akan terhapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteForm');
                        form.action = url;
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush