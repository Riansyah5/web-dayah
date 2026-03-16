@extends('layouts.app')
@section('title', 'Hasil & Koreksi Ujian')

@push('styles')
<style>
    /* Premium UI Customization */
    .header-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .icon-circle {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    /* Table Premium Styling */
    .table-premium thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #edf2f7;
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
    }
    .table-premium tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    .table-premium tbody tr:hover {
        background-color: #fcfcfd;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }
    .table-premium tbody td {
        vertical-align: middle;
        padding: 1.25rem 0.5rem;
    }
    
    /* Action Buttons */
    .btn-action {
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;
    }

    /* Custom Badge */
    .badge-soft {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    
    <div class="header-card p-4 mb-4 d-flex align-items-center border-0">
        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
            <i class="bi bi-award-fill fs-3"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1 text-dark">Hasil & Koreksi Ujian</h4>
            <p class="text-muted small mb-0 lh-base">Pilih jadwal ujian di bawah ini untuk melihat rekapitulasi nilai santri dan mengoreksi jawaban essay.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive" style="min-height: 300px;">
            <table class="table table-borderless table-premium align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" width="40%">Nama Ujian & Mata Pelajaran</th>
                        <th width="20%">Tanggal Pelaksanaan</th>
                        <th class="text-center" width="20%">Peserta Terdaftar</th>
                        <th class="text-end pe-4" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-light text-secondary rounded p-2 me-3 mt-1">
                                    <i class="bi bi-file-earmark-text fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-6 mb-1">{{ $exam->name }}</div>
                                    <div class="d-flex align-items-center flex-wrap gap-2 mt-1">
                                        <span class="text-primary small fw-medium">
                                            <i class="bi bi-journal-bookmark me-1"></i> {{ $exam->questionBank->subject_name }}
                                        </span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill" style="font-size: 0.65rem;">
                                            Tingkat: {{ $exam->questionBank->level }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-dark fw-medium">
                                <i class="bi bi-calendar-event text-muted me-2"></i>
                                {{ $exam->start_time->translatedFormat('d M Y') }}
                            </div>
                            <div class="small text-muted ms-4 mt-1">
                                <i class="bi bi-clock me-1"></i> {{ $exam->start_time->format('H:i') }} WIB
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill fs-6">
                                <i class="bi bi-people-fill me-1"></i> {{ $exam->student_exams_count }} Santri
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('teacher.cbt.results.show', $exam->id) }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm btn-action fw-medium">
                                Buka Rekap <i class="bi bi-arrow-right-short fs-5 ms-1 align-middle"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                    <i class="bi bi-folder-x fs-1 text-muted"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Ujian</h6>
                                <p class="text-muted small mb-0">Belum ada jadwal ujian yang menggunakan bank soal Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
@endpush