@extends('layouts.app')
@section('title', 'Edit Absensi Pengasuhan')

@push('styles')
<style>
    /* Segmented Control Radio Buttons */
    .status-segmented-control {
        display: flex;
        background-color: #f1f3f5;
        border-radius: 12px;
        padding: 3px;
        gap: 3px;
        width: 100%;
    }
    .status-segmented-control .status-item {
        flex: 1;
        margin: 0;
    }
    .status-segmented-control .btn-check {
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }
    .status-segmented-control .status-label {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 8px 0;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 9px;
        cursor: pointer;
        color: #6c757d;
        transition: all 0.15s ease-in-out;
        user-select: none;
        margin-bottom: 0;
    }

    /* Checked States */
    .status-segmented-control .btn-check:checked + .label-h {
        background-color: #198754;
        color: #fff;
        box-shadow: 0 2px 6px rgba(25, 135, 84, 0.35);
    }
    .status-segmented-control .btn-check:checked + .label-s {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.35);
    }
    .status-segmented-control .btn-check:checked + .label-i {
        background-color: #f59f00;
        color: #fff;
        box-shadow: 0 2px 6px rgba(245, 159, 0, 0.35);
    }
    .status-segmented-control .btn-check:checked + .label-a {
        background-color: #dc3545;
        color: #fff;
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.35);
    }

    /* Counter Badges */
    .counter-pill {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 8px;
        white-space: nowrap;
    }

    /* Avatar */
    .avatar-circle {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 50%;
        background-color: #fff3cd;
        color: #856404;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .note-input-wrapper input {
        font-size: 0.82rem;
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .note-input-wrapper input:focus {
        background-color: #fff;
    }

    @media (min-width: 768px) {
        .status-segmented-control {
            max-width: 250px;
            margin: 0 auto;
        }
        .status-segmented-control .status-label {
            padding: 5px 0;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 py-3">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="ti ti-edit text-warning me-2 fs-4"></i>Edit Absensi Pengasuhan
            </h5>
            <small class="text-muted">
                {{ \Carbon\Carbon::parse($dormAttendance->date)->translatedFormat('l, d M Y') }} &bull; Sesi: <strong>{{$dormAttendance->session_label }}</strong>
            </small>
        </div>
        <a href="{{ route('dorm-attendance.show', $dormAttendance->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm align-self-stretch align-self-sm-auto text-center">
            <i class="ti ti-arrow-left me-1"></i> Batal & Kembali
        </a>
    </div>

    <form action="{{ route('dorm-attendance.update', $dormAttendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Info Parameter Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <div class="row g-2 text-muted small align-items-center">
                    <div class="col-6 col-md-4">
                        <i class="ti ti-building me-1 text-primary"></i> Asrama: 
                        <strong class="text-dark">{{ $dormAttendance->dorm->name ?? '-' }}</strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <i class="ti ti-door me-1 text-primary"></i> Kamar: 
                        <strong class="text-dark">{{ $dormAttendance->room->name ?? 'Seluruh Kamar' }}</strong>
                    </div>
                    <div class="col-12 col-md-4 text-start text-md-end pt-1 pt-md-0 border-top border-md-top-0">
                        Total Terdata: <strong class="text-dark">{{ $dormAttendance->details->count() }} Santri</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance List Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <!-- Sticky Header Live Counter & Search -->
            <div class="card-header bg-white p-3 border-bottom sticky-top" style="z-index: 1020; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <!-- Badges -->
                    <div class="d-flex flex-wrap align-items-center gap-1">
                        <span class="counter-pill bg-light text-dark border">
                            Total: <strong id="count-total">{{ $dormAttendance->details->count() }}</strong>
                        </span>
                        <span class="counter-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                            H: <strong id="count-present">0</strong>
                        </span>
                        <span class="counter-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                            S: <strong id="count-sick">0</strong>
                        </span>
                        <span class="counter-pill bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25">
                            I: <strong id="count-permit">0</strong>
                        </span>
                        <span class="counter-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                            A: <strong id="count-alpha">0</strong>
                        </span>
                    </div>

                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 text-nowrap fw-semibold shadow-sm" id="btnAllPresent">
                        <i class="ti ti-check me-1"></i> Semua Hadir
                    </button>
                </div>

                <!-- Realtime Search -->
                <div class="position-relative">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="searchStudent" class="form-control form-control-sm rounded-pill ps-5 bg-light border-0" placeholder="Cari santri berdasarkan nama / NIS...">
                </div>
            </div>

            <!-- Content Area: Desktop Table & Mobile Cards -->
            <div class="p-2 p-md-0">
                <!-- 1. Tampilan Desktop (Table View) -->
                <div class="d-none d-md-block table-responsive">
                    <table class="table align-middle mb-0" id="desktopTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Nama Santri</th>
                                <th style="width: 160px;">Kamar</th>
                                <th class="text-center" style="width: 250px;">Status Kehadiran</th>
                                <th class="pe-4" style="width: 220px;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dormAttendance->details as $index =>$detail)
                                <tr class="student-row desktop-row" data-name="{{ strtolower($detail->student->name ?? '') }}" data-nis="{{ $detail->student->nis ?? '' }}">
                                    <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 34px; height: 34px; min-width: 34px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($detail->student->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ $detail->student->name ?? '-' }}</span>
                                                <small class="text-muted">NIS: {{ $detail->student->nis ?? '-' }}</small>
                                                @if($detail->permission)
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning d-inline-block mt-1">
                                                        <i class="ti ti-door-exit me-1"></i>Izin: {{ $detail->permission->reason }}
                                                    </span>
                                                @endif
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
                                        <div class="status-segmented-control">
                                            <div class="status-item">
                                                <input type="radio" class="btn-check status-radio" name="attendance[{{ $detail->id }}]" id="h_desk_{{ $detail->id }}" value="present" {{ $detail->status == 'present' ? 'checked' : '' }}>
                                                <label class="status-label label-h" for="h_desk_{{ $detail->id }}">H</label>
                                            </div>
                                            <div class="status-item">
                                                <input type="radio" class="btn-check status-radio" name="attendance[{{ $detail->id }}]" id="s_desk_{{ $detail->id }}" value="sick" {{ $detail->status == 'sick' ? 'checked' : '' }}>
                                                <label class="status-label label-s" for="s_desk_{{ $detail->id }}">S</label>
                                            </div>
                                            <div class="status-item">
                                                <input type="radio" class="btn-check status-radio" name="attendance[{{ $detail->id }}]" id="i_desk_{{ $detail->id }}" value="permission" {{ $detail->status == 'permission' ? 'checked' : '' }}>
                                                <label class="status-label label-i" for="i_desk_{{ $detail->id }}">I</label>
                                            </div>
                                            <div class="status-item">
                                                <input type="radio" class="btn-check status-radio" name="attendance[{{ $detail->id }}]" id="a_desk_{{ $detail->id }}" value="alpha" {{ $detail->status == 'alpha' ? 'checked' : '' }}>
                                                <label class="status-label label-a" for="a_desk_{{ $detail->id }}">A</label>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        <input type="text" name="student_notes[{{ $detail->id }}]" class="form-control form-control-sm rounded-3 desktop-note" placeholder="Keterangan..." value="{{ $detail->notes }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 2. Tampilan Ponsel (Mobile Stack Cards) -->
                <div class="d-md-none d-flex flex-column gap-2" id="mobileCardsContainer">
                    @foreach($dormAttendance->details as $index =>$detail)
                        <div class="student-card bg-white rounded-3 p-3 border shadow-none" data-name="{{ strtolower($detail->student->name ?? '') }}" data-nis="{{ $detail->student->nis ?? '' }}">
                            <!-- Bagian Atas: Info Santri -->
                            <div class="d-flex align-items-center justify-content-between mb-2">
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
                            </div>

                            @if($detail->permission)
                                <div class="mb-2">
                                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning text-wrap text-start w-100 p-2">
                                        <i class="ti ti-door-exit me-1"></i> Izin Tercatat: <strong>{{ $detail->permission->reason }}</strong>
                                    </span>
                                </div>
                            @endif

                            <!-- Bagian Tengah: Segmented Control Status -->
                            <div class="status-segmented-control mb-2">
                                <div class="status-item">
                                    <input type="radio" class="btn-check status-radio" name="attendance_mob[{{ $detail->id }}]" id="h_mob_{{ $detail->id }}" value="present" data-target="h_desk_{{ $detail->id }}" {{ $detail->status == 'present' ? 'checked' : '' }}>
                                    <label class="status-label label-h" for="h_mob_{{ $detail->id }}">Hadir</label>
                                </div>
                                <div class="status-item">
                                    <input type="radio" class="btn-check status-radio" name="attendance_mob[{{ $detail->id }}]" id="s_mob_{{ $detail->id }}" value="sick" data-target="s_desk_{{ $detail->id }}" {{ $detail->status == 'sick' ? 'checked' : '' }}>
                                    <label class="status-label label-s" for="s_mob_{{ $detail->id }}">Sakit</label>
                                </div>
                                <div class="status-item">
                                    <input type="radio" class="btn-check status-radio" name="attendance_mob[{{ $detail->id }}]" id="i_mob_{{ $detail->id }}" value="permission" data-target="i_desk_{{ $detail->id }}" {{ $detail->status == 'permission' ? 'checked' : '' }}>
                                    <label class="status-label label-i" for="i_mob_{{ $detail->id }}">Izin</label>
                                </div>
                                <div class="status-item">
                                    <input type="radio" class="btn-check status-radio" name="attendance_mob[{{ $detail->id }}]" id="a_mob_{{ $detail->id }}" value="alpha" data-target="a_desk_{{ $detail->id }}" {{ $detail->status == 'alpha' ? 'checked' : '' }}>
                                    <label class="status-label label-a" for="a_mob_{{ $detail->id }}">Alpa</label>
                                </div>
                            </div>

                            <!-- Bagian Bawah: Note Input Mobile -->
                            <div class="note-input-wrapper">
                                <input type="text" class="form-control form-control-sm rounded-2 mobile-note" data-target="{{ $detail->id }}" placeholder="Tulis keterangan khusus jika ada..." value="{{ $detail->notes }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Card Footer & Submit Actions -->
            <div class="card-footer bg-light p-3 p-md-4 border-top">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Catatan Sesi Pengasuhan</label>
                    <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="Catatan umum sesi pengasuhan...">{{ $dormAttendance->notes }}</textarea>
                </div>
                <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2">
                    <a href="{{ route('dorm-attendance.show', $dormAttendance->id) }}" class="btn btn-light border rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm text-dark" id="btnSubmit">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sinkronisasi input form Desktop dan Mobile jika form disubmit
        document.querySelectorAll('.mobile-note').forEach(input => {
            input.addEventListener('input', function() {
                const detailId = this.getAttribute('data-target');
                const deskInput = document.querySelector(`input[name="student_notes[${detailId}]"]`);
                if (deskInput) deskInput.value = this.value;
            });
        });

        document.querySelectorAll('input[name^="attendance_mob"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const deskRadio = document.getElementById(targetId);
                if (deskRadio) {
                    deskRadio.checked = true;
                    updateCounters();
                }
            });
        });

        // Realtime Live Counter
        function updateCounters() {
            let present = 0, sick = 0, permit = 0, alpha = 0;
            // Hitung berdasarkan status desktop (form submit sesungguhnya)
            document.querySelectorAll('#desktopTable .status-radio:checked').forEach(radio => {
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

        document.querySelectorAll('#desktopTable .status-radio').forEach(radio => {
            radio.addEventListener('change', updateCounters);
        });
        updateCounters();

        // Tombol "Semua Hadir"
        const btnAllPresent = document.getElementById('btnAllPresent');
        if (btnAllPresent) {
            btnAllPresent.addEventListener('click', function () {
                // Set Desktop radio
                document.querySelectorAll('#desktopTable .student-row').forEach(row => {
                    if (row.style.display !== 'none') {
                        const radio = row.querySelector('input[value="present"]');
                        if (radio) radio.checked = true;
                    }
                });
                // Set Mobile radio
                document.querySelectorAll('.student-card').forEach(card => {
                    if (card.style.display !== 'none') {
                        const radio = card.querySelector('input[value="present"]');
                        if (radio) radio.checked = true;
                    }
                });
                updateCounters();
            });
        }

        // Live Search Santri
        const searchInput = document.getElementById('searchStudent');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const keyword = this.value.toLowerCase().trim();

                // Filter tabel desktop
                document.querySelectorAll('#desktopTable .student-row').forEach(row => {
                    const name = row.getAttribute('data-name') || '';
                    const nis = row.getAttribute('data-nis') || '';
                    row.style.display = (name.includes(keyword) || nis.includes(keyword)) ? '' : 'none';
                });

                // Filter kartu mobile
                document.querySelectorAll('.student-card').forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const nis = card.getAttribute('data-nis') || '';
                    card.style.setProperty('display', (name.includes(keyword) || nis.includes(keyword)) ? '' : 'none', 'important');
                });
            });
        }
    });
</script>
@endpush