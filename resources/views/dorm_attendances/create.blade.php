@extends('layouts.app')
@section('title', 'Input Absensi Pengasuhan')

@push('styles')
<style>
    /* Styling Segmented Button H / S / I / A */
    .status-picker {
        display: flex;
        background: #f1f3f5;
        border-radius: 12px;
        padding: 3px;
        gap: 2px;
    }
    .status-picker .btn-status {
        flex: 1;
        text-align: center;
        padding: 8px 0;
        font-size: 0.82rem;
        font-weight: 700;
        border-radius: 9px;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease-in-out;
        color: #6c757d;
        margin: 0;
    }
    .status-radio:checked + .lbl-h {
        background-color: #198754;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(25, 135, 84, 0.35);
    }
    .status-radio:checked + .lbl-s {
        background-color: #0d6efd;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.35);
    }
    .status-radio:checked + .lbl-i {
        background-color: #ffc107;
        color: #111 !important;
        box-shadow: 0 2px 6px rgba(255, 193, 7, 0.35);
    }
    .status-radio:checked + .lbl-a {
        background-color: #dc3545;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.35);
    }

    /* Counter Badge */
    .stat-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 8px;
    }

    /* Avatar */
    .avatar-circle {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        color: #495057;
    }

    /* Sticky Counter Header */
    .sticky-panel {
        position: sticky;
        top: 0;
        z-index: 1020;
        background: #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 mb-md-4 gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="ti ti-calendar-check text-primary me-2"></i>Input Absensi Pengasuhan
            </h4>
            <p class="text-muted small mb-0">Tahun Ajaran: <span class="fw-semibold text-dark">{{ $activeYear->name }} ({{ $activeYear->semester }})</span></p>
        </div>
        <a href="{{ route('dorm-attendance.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm align-self-stretch align-self-sm-auto text-center">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Filter & Parameter Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('dorm-attendance.create') }}" method="GET" id="paramForm" class="row g-2 g-md-3 align-items-end">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label fw-bold small text-muted mb-1">Tanggal Absensi</label>
                    <input type="date" name="date" class="form-control form-control-sm rounded-3 py-2" value="{{ $date }}" required>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label fw-bold small text-muted mb-1">Sesi Pengasuhan</label>
                    <select name="session" class="form-select form-select-sm rounded-3 py-2" required>
                        <option value="subuh" {{ $session == 'subuh' ? 'selected' : '' }}>Subuh / Bangun Pagi</option>
                        <option value="maghrib" {{ $session == 'maghrib' ? 'selected' : '' }}>Maghrib</option>
                        <option value="isya" {{ $session == 'isya' ? 'selected' : '' }}>Isya</option>
                        <option value="malam" {{ $session == 'malam' ? 'selected' : '' }}>Malam / Apel Tidur</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label fw-bold small text-muted mb-1">Gedung Asrama</label>
                    <select name="dorm_id" class="form-select form-select-sm rounded-3 py-2" id="select_dorm" required>
                        <option value="">-- Pilih Asrama --</option>
                        @foreach($dorms as $dorm)
                            <option value="{{ $dorm->id }}" {{ $selectedDormId == $dorm->id ? 'selected' : '' }}>
                                {{ $dorm->name }} ({{ $dorm->gender == 'L' ? 'Putra' : 'Putri' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label fw-bold small text-muted mb-1">Kamar Santri</label>
                    <div class="input-group input-group-sm">
                        <select name="room_id" class="form-select rounded-start-3 py-2" id="select_room">
                            <option value="">Semua Kamar</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ $selectedRoomId == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }} ({{ $room->capacity }} santri)
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary rounded-end-3 px-3 py-2">
                            <i class="ti ti-refresh me-1"></i> Muat
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($existingAttendance)
        <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3 mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-circle fs-3 text-warning me-2"></i>
                    <div>
                        <strong class="d-block text-dark">Sesi Ini Sudah Pernah Disimpan</strong>
                        <span class="small text-muted">Oleh {{ $existingAttendance->recorder->name ?? 'User' }} ({{ $existingAttendance->created_at->format('d/m/Y H:i') }})</span>
                    </div>
                </div>
                <div class="d-flex gap-2 w-100 w-sm-auto">
                    <a href="{{ route('dorm-attendance.show', $existingAttendance->id) }}" class="btn btn-sm btn-outline-dark rounded-pill flex-fill">Lihat Data</a>
                    <a href="{{ route('dorm-attendance.edit', $existingAttendance->id) }}" class="btn btn-sm btn-warning rounded-pill flex-fill">Edit Data</a>
                </div>
            </div>
        </div>
    @endif

    @if($selectedDormId)
        @if($students->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
                <i class="ti ti-users-minus fs-1 opacity-50 mb-2"></i>
                <h5 class="fw-bold text-dark">Tidak ada santri terdaftar</h5>
                <p class="small mb-3">Pastikan santri sudah ditempatkan di asrama/kamar ini.</p>
                <div>
                    <a href="{{ route('assignments.create') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="ti ti-bed me-1"></i> Ke Penempatan Kamar
                    </a>
                </div>
            </div>
        @else
            <!-- Form Absensi Santri -->
            <form action="{{ route('dorm-attendance.store') }}" method="POST" id="storeAttendanceForm">
                @csrf
                <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                <input type="hidden" name="dorm_id" value="{{ $selectedDormId }}">
                <input type="hidden" name="room_id" value="{{ $selectedRoomId }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="session" value="{{ $session }}">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <!-- Sticky Summary & Live Counters -->
                    <div class="card-header border-bottom sticky-panel p-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-auto">
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="stat-badge bg-light text-dark border">Tot: <b id="count-total">{{ $students->count() }}</b></span>
                                    <span class="stat-badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">H: <b id="count-present">0</b></span>
                                    <span class="stat-badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">S: <b id="count-sick">0</b></span>
                                    <span class="stat-badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25">I: <b id="count-permit">0</b></span>
                                    <span class="stat-badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">A: <b id="count-alpha">0</b></span>
                                </div>
                            </div>
                            <div class="col-12 col-md d-flex gap-2 justify-content-md-end">
                                <input type="text" id="searchStudent" class="form-control form-control-sm rounded-pill" placeholder="Cari santri..." style="max-width: 200px;">
                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 text-nowrap" id="btnAllPresent">
                                    <i class="ti ti-check me-1"></i> Semua H
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- List Konten Santri -->
                    <div class="p-2 p-md-3">
                        @foreach($students as $index => $student)
                            @php
                                $hasPermit = $activePermissions->has($student->id);
                                $permit = $hasPermit ? $activePermissions->get($student->id) : null;
                                $assignment = $student->roomAssignments->first();
                                $room = $assignment->room ?? null;
                                $defaultStatus = $hasPermit ? 'permission' : 'present';
                            @endphp

                            <input type="hidden" name="student_room_ids[{{ $student->id }}]" value="{{ $room->id ?? '' }}">
                            @if($hasPermit)
                                <input type="hidden" name="permission_ids[{{ $student->id }}]" value="{{ $permit->id }}">
                            @endif

                            <div class="student-card border rounded-3 p-3 mb-2 bg-white" data-name="{{ strtolower($student->name) }}" data-nis="{{ $student->nis }}">
                                <div class="row g-2 align-items-center">
                                    <!-- Identitas Santri -->
                                    <div class="col-12 col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                            <div class="overflow-hidden">
                                                <div class="fw-bold text-dark text-truncate">{{ $student->name }}</div>
                                                <div class="d-flex flex-wrap gap-1 align-items-center mt-1">
                                                    <span class="badge bg-light text-muted border px-1" style="font-size: 0.72rem;">{{ $student->nis }}</span>
                                                    <span class="badge bg-light text-dark border px-1" style="font-size: 0.72rem;">{{ $room->name ?? 'Tanpa Kamar' }}</span>
                                                </div>
                                                @if($hasPermit)
                                                    <span class="badge bg-warning text-dark border mt-1 d-inline-block text-truncate" style="max-width: 100%;">
                                                        <i class="ti ti-door-exit me-1"></i>{{ $permit->reason }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Status H / S / I / A -->
                                    <div class="col-12 col-md-4">
                                        <div class="status-picker">
                                            <input type="radio" class="d-none status-radio" name="attendance[{{ $student->id }}]" id="h_{{ $student->id }}" value="present" {{ $defaultStatus == 'present' ? 'checked' : '' }}>
                                            <label class="btn-status lbl-h" for="h_{{ $student->id }}">Hadir</label>

                                            <input type="radio" class="d-none status-radio" name="attendance[{{ $student->id }}]" id="s_{{ $student->id }}" value="sick" {{ $defaultStatus == 'sick' ? 'checked' : '' }}>
                                            <label class="btn-status lbl-s" for="s_{{ $student->id }}">Sakit</label>

                                            <input type="radio" class="d-none status-radio" name="attendance[{{ $student->id }}]" id="i_{{ $student->id }}" value="permission" {{ $defaultStatus == 'permission' ? 'checked' : '' }}>
                                            <label class="btn-status lbl-i" for="i_{{ $student->id }}">Izin</label>

                                            <input type="radio" class="d-none status-radio" name="attendance[{{ $student->id }}]" id="a_{{ $student->id }}" value="alpha" {{ $defaultStatus == 'alpha' ? 'checked' : '' }}>
                                            <label class="btn-status lbl-a" for="a_{{ $student->id }}">Alpa</label>
                                        </div>
                                    </div>

                                    <!-- Keterangan Tambahan -->
                                    <div class="col-12 col-md-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted border-end-0">
                                                <i class="ti ti-notes"></i>
                                            </span>
                                            <input type="text" name="student_notes[{{ $student->id }}]" class="form-control form-control-sm border-start-0" placeholder="Keterangan..." value="{{ $hasPermit ? 'Izin: ' . $permit->reason : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer & Catatan Umum -->
                    <div class="card-footer bg-light p-3 p-md-4 border-top">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Catatan Sesi Pengasuhan (Opsional)</label>
                            <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="Catatan kebersihan kamar, musyrif, atau kejadian khusus..."></textarea>
                        </div>
                        <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2">
                            <a href="{{ route('dorm-attendance.index') }}" class="btn btn-light border rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Absensi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto reload saat ganti dorm
        const selectDorm = document.getElementById('select_dorm');
        if (selectDorm) {
            selectDorm.addEventListener('change', function () {
                document.getElementById('paramForm').submit();
            });
        }

        // Live Counter Status
        function updateCounters() {
            let present = 0, sick = 0, permit = 0, alpha = 0;
            document.querySelectorAll('.status-radio:checked').forEach(radio => {
                if (radio.value === 'present') present++;
                else if (radio.value === 'sick') sick++;
                else if (radio.value === 'permission') permit++;
                else if (radio.value === 'alpha') alpha++;
            });

            const elPresent = document.getElementById('count-present');
            const elSick = document.getElementById('count-sick');
            const elPermit = document.getElementById('count-permit');
            const elAlpha = document.getElementById('count-alpha');

            if (elPresent) elPresent.innerText = present;
            if (elSick) elSick.innerText = sick;
            if (elPermit) elPermit.innerText = permit;
            if (elAlpha) elAlpha.innerText = alpha;
        }

        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', updateCounters);
        });
        updateCounters();

        // Tombol Semua Hadir
        const btnAllPresent = document.getElementById('btnAllPresent');
        if (btnAllPresent) {
            btnAllPresent.addEventListener('click', function () {
                document.querySelectorAll('.student-card').forEach(card => {
                    if (card.style.display !== 'none') {
                        const radioPresent = card.querySelector('input[value="present"]');
                        if (radioPresent) {
                            radioPresent.checked = true;
                        }
                    }
                });
                updateCounters();
            });
        }

        // Realtime Search
        const searchInput = document.getElementById('searchStudent');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const keyword = this.value.toLowerCase().trim();
                document.querySelectorAll('.student-card').forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const nis = card.getAttribute('data-nis') || '';
                    if (name.includes(keyword) || nis.includes(keyword)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush