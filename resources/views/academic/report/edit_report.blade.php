@extends('layouts.app')
@section('title', 'Edit Rapor - ' . $student->name)

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
@endpush

@push('styles')
<style>
    .score-input {
        width: 75px;
        text-align: center;
        font-weight: 600;
        border-radius: 8px;
    }

    .badge-grade {
        min-width: 32px;
        padding: 5px 8px;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 6px;
    }

    .bg-grade-a { background-color: #10b981; color: #fff; }
    .bg-grade-b { background-color: #3b82f6; color: #fff; }
    .bg-grade-c { background-color: #f59e0b; color: #fff; }
    .bg-grade-d { background-color: #f97316; color: #fff; }
    .bg-grade-e { background-color: #ef4444; color: #fff; }

    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* Mobile Subject Card */
    .mobile-subject-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        padding: 1rem;
        margin-bottom: 0.85rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .score-chip-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    /* Sticky Bottom Submit Bar */
    .sticky-action-bar {
        position: sticky;
        bottom: 1rem;
        z-index: 1020;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 767.98px) {
        .desktop-table-view {
            display: none !important;
        }
        .mobile-cards-view {
            display: block !important;
        }
        .sticky-action-bar {
            bottom: 0.5rem;
            border-radius: 12px;
        }
    }

    @media (min-width: 768px) {
        .mobile-cards-view {
            display: none !important;
        }
        .desktop-table-view {
            display: block !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="ti ti-edit text-warning me-2"></i>Edit Nilai & Rekap Rapor Santri
            </h4>
            <p class="text-muted small mb-0">Ubah nilai harian, ujian, presensi, serta catatan wali kelas.</p>
        </div>
        <a href="{{ route('student.history', $student->id) }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3 shadow-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Jejak Studi
        </a>
    </div>

    <!-- Student & Classroom Banner -->
    <div class="card card-modern border-0 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-7">
                    <div class="d-flex align-items-center">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm flex-shrink-0" 
                             style="width: 52px; height: 52px; font-size: 1.35rem;">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div class="text-truncate">
                            <h5 class="fw-bold mb-1 text-white text-truncate">{{ $student->name }}</h5>
                            <span class="opacity-75 small">
                                NIS: {{ $student->nis ?? '-' }} &bull; {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 text-md-end border-top border-md-top-0 border-md-start border-white border-opacity-25 pt-2 pt-md-0 ps-md-4">
                    <span class="badge bg-white text-primary rounded-pill mb-1 fw-bold shadow-sm">
                        {{ $classroom->academicYear->name ?? '-' }} ({{ $classroom->academicYear->semester ?? '-' }})
                    </span>
                    <h6 class="fw-bold text-white mb-0">{{ $classroom->name }}</h6>
                    <small class="opacity-75 d-block">Wali Kelas: {{ $classroom->homeroom_teacher ?? '-' }}</small>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('student.report.update', ['student' => $student->id, 'classroom' =>$classroom->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Bagian Nilai Mata Pelajaran -->
        <div class="card card-modern mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Daftar Nilai Mata Pelajaran</h6>
                    <small class="text-muted">Formula: (Harian &times; 40%) + (UTS &times; 30%) + (UAS &times; 30%)</small>
                </div>
                <div class="bg-light px-3 py-1 rounded-pill border d-flex align-items-center gap-2 shadow-sm">
                    <small class="text-muted fw-semibold">Rata-rata:</small>
                    <strong class="text-primary fs-6" id="avg-score">0.0</strong>
                </div>
            </div>

            <div class="card-body p-0 p-md-3">
                <!-- 1. Tampilan Desktop (Table View) -->
                <div class="desktop-table-view table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 40px;">No</th>
                                <th>Mata Pelajaran</th>
                                <th class="text-center" style="width: 65px;">KKM</th>
                                <th class="text-center" style="width: 95px;">Harian (40%)</th>
                                <th class="text-center" style="width: 95px;">UTS (30%)</th>
                                <th class="text-center" style="width: 95px;">UAS (30%)</th>
                                <th class="text-center" style="width: 95px;">Akhir</th>
                                <th class="text-center" style="width: 65px;">Predikat</th>
                                <th class="pe-3" style="min-width: 200px;">Deskripsi / Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $index =>$course)
                                @php
                                    $grade =$grades->get($course->id);$harian = $grade ? $grade->score_harian : 0;
                                    $uts = $grade ? $grade->score_uts : 0;
                                    $uas = $grade ? $grade->score_uas : 0;
                                    $final = $grade ? $grade->score_final : round(($harian * 0.4) + ($uts * 0.3) + ($uas * 0.3), 1);$letter = $grade ? $grade->grade_letter : 'E';
                                @endphp
                                <tr class="course-row desktop-row" data-course-id="{{ $course->id }}">
                                    <td class="ps-3 text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $course->subject->name ?? '-' }}</span>
                                        <small class="text-muted">{{ $course->subject->code ?? '-' }} &bull; Kelompok {{ $course->subject->group ?? 'A' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">{{ $course->kkm }}</span>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" step="0.1" min="0" max="100" 
                                               name="grades[{{ $course->id }}][harian]" 
                                               class="form-control form-control-sm score-input input-harian mx-auto" 
                                               value="{{ $harian }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" step="0.1" min="0" max="100" 
                                               name="grades[{{ $course->id }}][uts]" 
                                               class="form-control form-control-sm score-input input-uts mx-auto" 
                                               value="{{ $uts }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" step="0.1" min="0" max="100" 
                                               name="grades[{{ $course->id }}][uas]" 
                                               class="form-control form-control-sm score-input input-uas mx-auto" 
                                               value="{{ $uas }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" step="0.1" min="0" max="100" 
                                               name="grades[{{ $course->id }}][final]" 
                                               class="form-control form-control-sm score-input input-final mx-auto fw-bold text-primary" 
                                               value="{{ $final }}">
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-grade badge-predikat">{{ $letter }}</span>
                                    </td>
                                    <td class="pe-3">
                                        <input type="text" name="grades[{{ $course->id }}][notes]" 
                                               class="form-control form-control-sm rounded-3 input-notes" 
                                               placeholder="Capaian kompetensi..." 
                                               value="{{ $grade->description ?? '' }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        Belum ada mata pelajaran yang diplot untuk kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- 2. Tampilan Mobile (Card View) -->
                <div class="mobile-cards-view p-2">
                    @forelse($courses as $index =>$course)
                        @php
                            $grade =$grades->get($course->id);$harian = $grade ? $grade->score_harian : 0;
                            $uts = $grade ? $grade->score_uts : 0;
                            $uas = $grade ? $grade->score_uas : 0;
                            $final = $grade ? $grade->score_final : round(($harian * 0.4) + ($uts * 0.3) + ($uas * 0.3), 1);$letter = $grade ? $grade->grade_letter : 'E';
                        @endphp
                        <div class="mobile-subject-card course-row mobile-row" data-course-id="{{ $course->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-light text-secondary border me-1">#{{ $index + 1 }}</span>
                                    <strong class="text-dark">{{ $course->subject->name ?? '-' }}</strong>
                                    <div class="small text-muted">{{ $course->subject->code ?? '-' }} &bull; KKM: {{ $course->kkm }}</div>
                                </div>
                                <span class="badge badge-grade badge-predikat">{{ $letter }}</span>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-3 text-center">
                                    <div class="score-chip-label">Harian</div>
                                    <input type="number" step="0.1" min="0" max="100" 
                                           class="form-control form-control-sm text-center fw-semibold input-harian" 
                                           value="{{ $harian }}">
                                </div>
                                <div class="col-3 text-center">
                                    <div class="score-chip-label">UTS</div>
                                    <input type="number" step="0.1" min="0" max="100" 
                                           class="form-control form-control-sm text-center fw-semibold input-uts" 
                                           value="{{ $uts }}">
                                </div>
                                <div class="col-3 text-center">
                                    <div class="score-chip-label">UAS</div>
                                    <input type="number" step="0.1" min="0" max="100" 
                                           class="form-control form-control-sm text-center fw-semibold input-uas" 
                                           value="{{ $uas }}">
                                </div>
                                <div class="col-3 text-center">
                                    <div class="score-chip-label text-primary">Akhir</div>
                                    <input type="number" step="0.1" min="0" max="100" 
                                           class="form-control form-control-sm text-center fw-bold text-primary border-primary input-final" 
                                           value="{{ $final }}">
                                </div>
                            </div>

                            <div>
                                <input type="text" class="form-control form-control-sm rounded-2 input-notes" 
                                       placeholder="Catatan / Capaian kompetensi..." 
                                       value="{{ $grade->description ?? '' }}">
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada mata pelajaran.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Rekapitulasi Kehadiran & Catatan Wali Kelas -->
        <div class="row g-3 g-md-4 mb-4">
            <!-- Kehadiran & Keputusan -->
            <div class="col-12 col-lg-5">
                <div class="card card-modern h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="ti ti-calendar-stats me-2 text-primary"></i>Rekapitulasi Kehadiran
                        </h6>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="row g-2 g-md-3">
                            <div class="col-4">
                                <label class="form-label small fw-bold text-muted mb-1">Sakit (Hari)</label>
                                <input type="number" min="0" name="report[sick]" class="form-control form-control-sm text-center fw-bold" value="{{ $reportCard->sick ?? 0 }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label small fw-bold text-muted mb-1">Izin (Hari)</label>
                                <input type="number" min="0" name="report[permission]" class="form-control form-control-sm text-center fw-bold" value="{{ $reportCard->permission ?? 0 }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label small fw-bold text-muted mb-1">Alpa (Hari)</label>
                                <input type="number" min="0" name="report[absent]" class="form-control form-control-sm text-center fw-bold text-danger border-danger-subtle" value="{{ $reportCard->absent ?? 0 }}">
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <label class="form-label small fw-bold text-muted">Keputusan Semester / Kenaikan Kelas</label>
                            <select name="report[status]" class="form-select form-select-sm rounded-3">
                                <option value="Naik Kelas" {{ ($reportCard->status ?? '') == 'Naik Kelas' ? 'selected' : '' }}>Naik Kelas</option>
                                <option value="Lulus" {{ ($reportCard->status ?? '') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="Tinggal di Kelas" {{ ($reportCard->status ?? '') == 'Tinggal di Kelas' ? 'selected' : '' }}>Tinggal di Kelas</option>
                                <option value="Tidak Lulus" {{ ($reportCard->status ?? '') == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                                <option value="-" {{ ($reportCard->status ?? '') == '-' ? 'selected' : '' }}>- (Semester Ganjil)</option>
                            </select>
                            <small class="text-muted d-block mt-1">Dicetak pada kolom keputusan lembar rapor santri.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Wali Kelas -->
            <div class="col-12 col-lg-7">
                <div class="card card-modern h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="ti ti-notes me-2 text-primary"></i>Catatan Wali Kelas
                        </h6>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="mb-2">
                            <label class="form-label small fw-bold text-muted">Pesan, Nasihat, dan Perkembangan Karakter</label>
                            <textarea name="report[notes]" class="form-control rounded-3" rows="5" placeholder="Tuliskan catatan evaluasi dan motivasi untuk santri ini...">{{ $reportCard->notes ?? '' }}</textarea>
                        </div>
                        <small class="text-muted">Catatan ini akan tampil di bagian bawah rapor cetak.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Floating Action Bar -->
        <div class="card sticky-action-bar mb-3">
            <div class="card-body p-2 p-md-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('student.history', $student->id) }}" class="btn btn-light rounded-pill px-3 px-md-4 text-muted border">
                    <i class="ti ti-arrow-left me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-warning rounded-pill px-4 px-md-5 fw-bold shadow-sm text-dark">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function getGradeLetter(score) {
            if (score >= 85) return 'A';
            if (score >= 75) return 'B';
            if (score >= 60) return 'C';
            if (score >= 50) return 'D';
            return 'E';
        }

        function getBadgeClass(letter) {
            switch(letter) {
                case 'A': return 'bg-grade-a';
                case 'B': return 'bg-grade-b';
                case 'C': return 'bg-grade-c';
                case 'D': return 'bg-grade-d';
                default: return 'bg-grade-e';
            }
        }

        function calculateScore(harian, uts, uas) {
            return Math.round(((harian * 0.4) + (uts * 0.3) + (uas * 0.3)) * 10) / 10;
        }

        function syncAndCalculate(courseId, sourceElement, fieldType) {
            // Dapatkan kedua elemen (desktop row & mobile card)
            const rows = document.querySelectorAll(`.course-row[data-course-id="${courseId}"]`);
            const val = sourceElement.value;

            // 1. Sinkronkan nilai input ke elemen pasangannya (Desktop <-> Mobile)
            rows.forEach(r => {
                const targetInput = r.querySelector(`.input-${fieldType}`);
                if (targetInput && targetInput !== sourceElement) {
                    targetInput.value = val;
                }
            });

            // 2. Hitung ulang nilai akhir & predikat untuk kedua tampilan
            rows.forEach(r => {
                const harian = parseFloat(r.querySelector('.input-harian').value) || 0;
                const uts = parseFloat(r.querySelector('.input-uts').value) || 0;
                const uas = parseFloat(r.querySelector('.input-uas').value) || 0;
                const finalInput = r.querySelector('.input-final');
                const badge = r.querySelector('.badge-predikat');

                let finalScore;
                if (fieldType === 'final') {
                    finalScore = parseFloat(val) || 0;
                } else {
                    finalScore = calculateScore(harian, uts, uas);
                    finalInput.value = finalScore;
                }

                const letter = getGradeLetter(finalScore);
                badge.textContent = letter;
                badge.className = 'badge badge-grade badge-predikat ' + getBadgeClass(letter);
            });

            updateAverage();
        }

        function updateAverage() {
            let total = 0;
            let count = 0;
            // Hitung hanya dari input desktop agar tidak terhitung ganda
            document.querySelectorAll('.desktop-row .input-final').forEach(input => {
                total += parseFloat(input.value) || 0;
                count++;
            });

            const avg = count > 0 ? (total / count).toFixed(1) : '0.0';
            const avgElement = document.getElementById('avg-score');
            if (avgElement) {
                avgElement.textContent = avg;
            }
        }

        // Setup event listener untuk sinkronisasi form desktop dan mobile
        document.querySelectorAll('.course-row').forEach(row => {
            const courseId = row.getAttribute('data-course-id');

            ['harian', 'uts', 'uas', 'final'].forEach(type => {
                const input = row.querySelector(`.input-${type}`);
                if (input) {
                    input.addEventListener('input', () => syncAndCalculate(courseId, input, type));
                }
            });

            const noteInput = row.querySelector('.input-notes');
            if (noteInput) {
                noteInput.addEventListener('input', () => {
                    const val = noteInput.value;
                    document.querySelectorAll(`.course-row[data-course-id="${courseId}"] .input-notes`).forEach(n => {
                        if (n !== noteInput) n.value = val;
                    });
                });
            }

            // Inisialisasi tampilan awal badge predikat
            const finalInput = row.querySelector('.input-final');
            if (finalInput) {
                const finalVal = parseFloat(finalInput.value) || 0;
                const letter = getGradeLetter(finalVal);
                const badge = row.querySelector('.badge-predikat');
                if (badge) {
                    badge.textContent = letter;
                    badge.className = 'badge badge-grade badge-predikat ' + getBadgeClass(letter);
                }
            }
        });

        updateAverage();
    });
</script>
@endpush