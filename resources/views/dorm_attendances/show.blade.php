@extends('layouts.app')
@section('title', 'Detail Absensi Pengasuhan')

@push('styles')
<style>
    .avatar-circle {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 50%;
        background-color: #e7f1ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .stat-box {
        border-radius: 12px;
        transition: transform 0.2s ease;
    }
    .stat-box:hover {
        transform: translateY(-2px);
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            break-inside: avoid;
        }
        .student-card {
            border-bottom: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 mb-md-4 gap-3 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center">
                <i class="ti ti-file-text text-primary me-2 fs-3"></i>Detail Absensi Asrama
            </h4>
            <p class="text-muted small mb-0">Rincian status kehadiran santri pada sesi pengasuhan.</p>
        </div>
        <div class="d-flex flex-wrap w-100 w-sm-auto gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3 flex-fill flex-sm-grow-0 shadow-sm">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
            <a href="{{ route('dorm-attendance.edit', $dormAttendance->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 flex-fill flex-sm-grow-0 fw-semibold shadow-sm text-dark">
                <i class="ti ti-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('dorm-attendance.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 flex-fill flex-sm-grow-0 shadow-sm">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Banner Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-3 mb-md-4">
        <div class="card-body p-3 p-md-4">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted small d-block">Tanggal</span>
                    <strong class="text-dark small fs-md-6 d-block mt-1">
                        {{ \Carbon\Carbon::parse($dormAttendance->date)->translatedFormat('l, d M Y') }}
                    </strong>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted small d-block">Sesi Pengasuhan</span>
                    <strong class="text-dark small fs-md-6 d-block mt-1">
                        {{ $dormAttendance->session_label }}
                    </strong>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted small d-block">Gedung / Kamar</span>
                    <strong class="text-dark small fs-md-6 d-block mt-1 text-truncate">
                        {{ $dormAttendance->dorm->name ?? '-' }}
                        @if($dormAttendance->room)
                            - {{ $dormAttendance->room->name }}
                        @else
                            <span class="badge bg-light text-secondary border fw-normal">Semua Kamar</span>
                        @endif
                    </strong>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted small d-block">Pencatat (Musyrif)</span>
                    <strong class="text-dark small fs-md-6 d-block mt-1 text-truncate">
                        {{ $dormAttendance->recorder->name ?? 'Sistem' }}
                    </strong>
                    <span class="text-muted" style="font-size: 0.72rem;">
                        {{ $dormAttendance->created_at->format('H:i') }} WIB
                    </span>
                </div>
            </div>

            @if($dormAttendance->notes)
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted small fw-bold d-block mb-1">Catatan Pengasuhan Sesi Ini:</span>
                    <div class="bg-light p-2 p-md-3 rounded-3 small text-dark border-start border-3 border-primary">
                        {{ $dormAttendance->notes }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-6 col-md-3">
            <div class="card stat-box border-0 shadow-sm bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">Hadir</span>
                        <h4 class="fw-bold text-success mb-0 mt-1">{{ $summary['present'] }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success p-2 p-md-3 rounded-circle">
                        <i class="ti ti-user-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-box border-0 shadow-sm bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">Sakit</span>
                        <h4 class="fw-bold text-primary mb-0 mt-1">{{ $summary['sick'] }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary p-2 p-md-3 rounded-circle">
                        <i class="ti ti-first-aid-kit fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-box border-0 shadow-sm bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">Izin Keluar</span>
                        <h4 class="fw-bold text-warning mb-0 mt-1">{{ $summary['permission'] }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning p-2 p-md-3 rounded-circle">
                        <i class="ti ti-door-exit fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-box border-0 shadow-sm bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">Alpa / Gaib</span>
                        <h4 class="fw-bold text-danger mb-0 mt-1">{{ $summary['alpha'] }}</h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger p-2 p-md-3 rounded-circle">
                        <i class="ti ti-alert-triangle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data List: Desktop Table & Mobile Stack Cards -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-3 px-md-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                Daftar Santri <span class="badge bg-light text-secondary border ms-1">{{ $summary['total'] }} Orang</span>
            </h6>
        </div>

        <!-- 1. Tampilan Desktop (Tabel Standar) -->
        <div class="d-none d-md-block table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Santri</th>
                        <th>Kamar & Rombel</th>
                        <th class="text-center" style="width: 140px;">Status</th>
                        <th class="pe-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dormAttendance->details as $index => $detail)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ strtoupper(substr($detail->student->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $detail->student->name ?? '-' }}</span>
                                        <small class="text-muted">NIS: {{ $detail->student->nis ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border d-inline-block mb-1">
                                    <i class="ti ti-door me-1 text-primary"></i>{{ $detail->room->name ?? ($dormAttendance->room->name ?? '-') }}
                                </span>
                                <small class="text-muted d-block">{{ $detail->student->class_group ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                {!! $detail->status_badge !!}
                            </td>
                            <td class="pe-4">
                                @if($detail->notes)
                                    <span class="text-dark small">{{ $detail->notes }}</span>
                                @elseif($detail->permission)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning">
                                        <i class="ti ti-receipt me-1"></i>Izin: {{ $detail->permission->reason }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 2. Tampilan Layar HP (Kartu Vertikal Rapi) -->
        <div class="d-md-none p-2 bg-light bg-opacity-50">
            <div class="d-flex flex-column gap-2">
                @foreach($dormAttendance->details as $index => $detail)
                    <div class="student-card bg-white p-3 rounded-3 border shadow-none">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2">
                                    {{ strtoupper(substr($detail->student->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 lh-sm">{{ $detail->student->name ?? '-' }}</h6>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.7rem;">
                                            NIS: {{ $detail->student->nis ?? '-' }}
                                        </span>
                                        <span class="badge bg-light text-primary border px-2 py-1" style="font-size: 0.7rem;">
                                            <i class="ti ti-door me-1"></i>{{ $detail->room->name ?? ($dormAttendance->room->name ?? '-') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                {!! $detail->status_badge !!}
                            </div>
                        </div>

                        @if($detail->notes || $detail->permission)
                            <div class="pt-2 mt-2 border-top">
                                @if($detail->notes)
                                    <div class="small text-muted">
                                        <i class="ti ti-notes me-1"></i>{{ $detail->notes }}
                                    </div>
                                @endif
                                @if($detail->permission)
                                    <div class="badge bg-warning-subtle text-warning-emphasis border border-warning text-wrap text-start w-100 p-2 mt-1">
                                        <i class="ti ti-receipt me-1"></i>Izin Resmi: {{ $detail->permission->reason }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection