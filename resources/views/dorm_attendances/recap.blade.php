@extends('layouts.app')
@section('title', 'Rekap Absensi Pengasuhan Asrama')

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
    .stat-pill-sm {
        font-size: 0.72rem;
        padding: 3px 7px;
        border-radius: 6px;
        font-weight: 600;
        white-space: nowrap;
    }
    .progress-bar-custom {
        height: 6px;
        border-radius: 4px;
        background-color: #e9ecef;
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
        .d-none.d-md-block {
            display: block !important;
        }
        .d-md-none {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 py-3">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2 no-print">
        <div>
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="ti ti-report-analytics text-primary me-2 fs-4"></i>Rekapitulasi Absensi Asrama
            </h5>
            <small class="text-muted">Akumulasi presensi per santri pada periode terpilih.</small>
        </div>
        <div class="d-flex gap-2 w-100 w-sm-auto">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3 flex-fill flex-sm-grow-0 shadow-sm">
                <i class="ti ti-printer me-1"></i> Cetak Laporan
            </button>
            <a href="{{ route('dorm-attendance.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 flex-fill flex-sm-grow-0 shadow-sm">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-3 no-print">
        <div class="card-body p-3">
            <form action="{{ route('dorm-attendance.recap') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control form-control-sm rounded-3 py-2" value="{{ $startDate }}" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control form-control-sm rounded-3 py-2" value="{{ $endDate }}" required>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Gedung</label>
                    <select name="dorm_id" class="form-select form-select-sm rounded-3 py-2" id="recap_dorm_id">
                        <option value="">Semua Asrama</option>
                        @foreach($dorms as $dorm)
                            <option value="{{ $dorm->id }}" {{ $selectedDormId == $dorm->id ? 'selected' : '' }}>
                                {{ $dorm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Kamar</label>
                    <select name="room_id" class="form-select form-select-sm rounded-3 py-2" id="recap_room_id">
                        <option value="">Semua Kamar</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" data-dorm="{{ $room->dorm_id }}" {{ $selectedRoomId == $room->id ? 'selected' : '' }}>
                                {{ $room->dorm->name ?? '' }} - {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 w-100 py-2">
                        <i class="ti ti-search me-1"></i> Terapkan
                    </button>
                    @if(request()->anyFilled(['dorm_id', 'room_id']))
                        <a href="{{ route('dorm-attendance.recap', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-2" title="Reset">
                            <i class="ti ti-refresh"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Overall Statistics Cards -->
    @php
        $totalRecord = $overallStats->total ?? 0;
        $presentCount = $overallStats->present ?? 0;
        $sickCount = $overallStats->sick ?? 0;
        $permitCount = $overallStats->permission ?? 0;
        $alphaCount = $overallStats->alpha ?? 0;
        $attendanceRate = $totalRecord > 0 ? round(($presentCount / $totalRecord) * 100, 1) : 0;
    @endphp
    <div class="row g-2 g-md-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block">Hadir (Rata-rata)</span>
                <h4 class="fw-bold text-success mb-0 mt-1">{{ $presentCount }}</h4>
                <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">{{ $attendanceRate }}% dari total sesi</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block">Total Sakit</span>
                <h4 class="fw-bold text-primary mb-0 mt-1">{{ $sickCount }}</h4>
                <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">Sesi pengasuhan</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block">Total Izin</span>
                <h4 class="fw-bold text-warning mb-0 mt-1">{{ $permitCount }}</h4>
                <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">Izin keluar / pulang</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block">Total Alpa</span>
                <h4 class="fw-bold text-danger mb-0 mt-1">{{ $alphaCount }}</h4>
                <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">Tanpa keterangan</small>
            </div>
        </div>
    </div>

    <!-- Student Recap Content Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-3 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-1">
            <div>
                <h6 class="fw-bold mb-0 text-dark">Data Rekap Santri</h6>
                <small class="text-muted" style="font-size: 0.75rem;">
                    Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                </small>
            </div>
            @if(method_exists($studentRecap, 'total'))
                <span class="badge bg-light text-secondary border">Total {{ $studentRecap->total() }} Santri</span>
            @endif
        </div>

        <!-- 1. Tampilan Desktop (Tabel Matriks Lengkap) -->
        <div class="d-none d-md-block table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Santri</th>
                        <th>Asrama & Kamar</th>
                        <th class="text-center">Total</th>
                        <th class="text-center text-success">Hadir</th>
                        <th class="text-center text-primary">Sakit</th>
                        <th class="text-center text-warning">Izin</th>
                        <th class="text-center text-danger">Alpa</th>
                        <th class="text-end pe-4" style="width: 150px;">Tingkat Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentRecap as $index => $row)
                        @php
                            $rate = $row->total_session > 0 ? round(($row->present_count / $row->total_session) * 100) : 0;
                            $rateColor = $rate >= 80 ? 'success' : ($rate >= 50 ? 'warning' : 'danger');
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ method_exists($studentRecap, 'firstItem') ? $studentRecap->firstItem() + $index : $index + 1 }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3" style="width: 34px; height: 34px; min-width: 34px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($row->student->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $row->student->name ?? '-' }}</span>
                                        <small class="text-muted">NIS: {{ $row->student->nis ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border d-inline-block mb-1">
                                    <i class="ti ti-door me-1 text-primary"></i>
                                    {{ $row->room->dorm->name ?? '-' }} - {{ $row->room->name ?? '-' }}
                                </span>
                                <small class="text-muted d-block">{{ $row->student->class_group ?? '-' }}</small>
                            </td>
                            <td class="text-center fw-semibold text-secondary">{{ $row->total_session }}</td>
                            <td class="text-center fw-bold text-success">{{ $row->present_count }}</td>
                            <td class="text-center fw-bold text-primary">{{ $row->sick_count }}</td>
                            <td class="text-center fw-bold text-warning">{{ $row->permission_count }}</td>
                            <td class="text-center fw-bold text-danger">
                                @if($row->alpha_count > 0)
                                    <span class="badge bg-danger rounded-pill px-2">{{ $row->alpha_count }}</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <div class="progress flex-grow-1 progress-bar-custom" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $rateColor }}" style="width: {{ $rate }}%"></div>
                                    </div>
                                    <span class="small fw-bold text-{{ $rateColor }}">{{ $rate }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="ti ti-chart-bar fs-1 opacity-50 d-block mb-2"></i>
                                Tidak ada data absensi santri pada periode tanggal dan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. Tampilan Ponsel (Card Stack dengan Mini Metrics) -->
        <div class="d-md-none p-2 bg-light bg-opacity-50">
            <div class="d-flex flex-column gap-2">
                @forelse($studentRecap as $index => $row)
                    @php
                        $rate = $row->total_session > 0 ? round(($row->present_count / $row->total_session) * 100) : 0;
                        $rateColor = $rate >= 80 ? 'success' : ($rate >= 50 ? 'warning' : 'danger');
                    @endphp
                    <div class="bg-white p-3 rounded-3 border shadow-none">
                        <!-- Profil Santri & Progress Bar -->
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2">
                                    {{ strtoupper(substr($row->student->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 lh-sm">{{ $row->student->name ?? '-' }}</h6>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.7rem;">
                                            NIS: {{ $row->student->nis ?? '-' }}
                                        </span>
                                        <span class="badge bg-light text-primary border px-2 py-1" style="font-size: 0.7rem;">
                                            <i class="ti ti-door me-1"></i>{{ $row->room->name ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <span class="badge bg-{{ $rateColor }}-subtle text-{{ $rateColor }} border border-{{ $rateColor }} fw-bold" style="font-size: 0.8rem;">
                                    {{ $rate }}%
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar Visual -->
                        <div class="progress progress-bar-custom mb-3" style="height: 5px;">
                            <div class="progress-bar bg-{{ $rateColor }}" style="width: {{ $rate }}%"></div>
                        </div>

                        <!-- Mini Matrix Status Grid -->
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light text-center">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Total</small>
                                <span class="fw-bold text-secondary">{{ $row->total_session }}</span>
                            </div>
                            <div class="border-start ps-2">
                                <small class="text-success d-block" style="font-size: 0.7rem;">Hadir</small>
                                <span class="fw-bold text-success">{{ $row->present_count }}</span>
                            </div>
                            <div class="border-start ps-2">
                                <small class="text-primary d-block" style="font-size: 0.7rem;">Sakit</small>
                                <span class="fw-bold text-primary">{{ $row->sick_count }}</span>
                            </div>
                            <div class="border-start ps-2">
                                <small class="text-warning d-block" style="font-size: 0.7rem;">Izin</small>
                                <span class="fw-bold text-warning">{{ $row->permission_count }}</span>
                            </div>
                            <div class="border-start ps-2">
                                <small class="text-danger d-block" style="font-size: 0.7rem;">Alpa</small>
                                <span class="fw-bold text-danger">{{ $row->alpha_count }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted bg-white rounded-3 border p-3">
                        <i class="ti ti-chart-bar fs-1 opacity-50 d-block mb-2"></i>
                        <small>Tidak ada data rekapitulasi absensi untuk filter ini.</small>
                    </div>
                @endforelse
            </div>
        </div>

        @if(method_exists($studentRecap, 'hasPages') && $studentRecap->hasPages())
            <div class="card-footer bg-white py-3 border-top no-print">
                <div class="d-flex justify-content-center justify-content-md-end">
                    {{ $studentRecap->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dormSelect = document.getElementById('recap_dorm_id');
        const roomSelect = document.getElementById('recap_room_id');
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
    });
</script>
@endpush