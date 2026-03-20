@extends('layouts.app')
@section('title', 'Buat Jadwal Ujian')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #f6f8fb 0%, #e9f0f7 100%);
        min-height: 100vh;
    }

    /* Glassmorphism Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
    }

    /* Form Styling */
    .form-label {
        color: #4a5568;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.5) !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 12px !important;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        background: white !important;
        border-color: #0d6efd !important;
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.1) !important;
    }

    /* Section highlight */
    .section-premium {
        background: rgba(13, 110, 253, 0.03);
        border: 1px solid rgba(13, 110, 253, 0.08);
        border-radius: 20px;
        padding: 1.5rem;
    }

    /* Custom Switch */
    .form-check-input {
        width: 2.5em !important;
        height: 1.25em !important;
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        padding-top: 2px;
        font-weight: 500;
        color: #2d3748;
    }

    .input-group-text {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 0 12px 12px 0 !important;
        color: #6c757d;
        font-weight: 600;
    }

    .text-gradient-primary {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeIn">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb" style="font-size: 0.75rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.cbt.exams.index') }}" class="text-decoration-none text-muted">JADWAL</a></li>
                    <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">CREATE NEW</li>
                </ol>
            </nav>
            <h2 class="fw-bolder mb-0 text-dark"><i class="bi bi-calendar-plus me-2 text-primary"></i>Buat <span class="text-gradient-primary">Jadwal Ujian</span></h2>
        </div>
        <a href="{{ route('admin.cbt.exams.index') }}" class="btn btn-white shadow-sm border rounded-pill px-4">
            <i class="bi bi-x-lg me-2"></i>Batal
        </a>
    </div>

    <div class="glass-card animate__animated animate__fadeInUp">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('admin.cbt.exams.store') }}" method="POST" id="examForm">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label fw-bold small"><i class="bi bi-tag me-1 text-primary"></i>NAMA JADWAL (LABEL)</label>
                        <input type="text" name="name" class="form-control" placeholder="Misal: Penilaian Harian Nahwu Kls 7" required>
                        <div class="form-text small">Nama ini akan tampil di dashboard santri.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small"><i class="bi bi-database me-1 text-primary"></i>SUMBER BANK SOAL</label>
                        <select name="cbt_question_bank_id" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Paket Soal --</option>
                            @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">
                                [{{ $bank->bank_code }}] {{ $bank->subject_name }} - {{ $bank->level }} ({{ $bank->questions_count }} Soal)
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text small">Pilih paket soal yang sudah diverifikasi.</div>
                    </div>
                </div>

                <div class="section-premium mb-5">
                    <h6 class="fw-bold mb-4 text-primary d-flex align-items-center">
                        <i class="bi bi-clock-history me-2"></i> Konfigurasi Waktu & Durasi
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold small">WAKTU MULAI</label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold small">BATAS LOGIN (SELESAI)</label>
                            <input type="datetime-local" name="end_time" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">DURASI PENGERJAAN</label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form-control" value="90" min="1;10" required>
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-shield-lock me-2 text-primary"></i>Keamanan & Integritas</h6>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-check form-switch p-3 border rounded-3 bg-white bg-opacity-50">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="randomize_questions" id="rq" checked value="1">
                                <label class="form-check-label" for="rq">Acak Urutan Soal</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch p-3 border rounded-3 bg-white bg-opacity-50">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="randomize_options" id="ro" checked value="1">
                                <label class="form-check-label" for="ro">Acak Pilihan Jawaban</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch p-3 border rounded-3 bg-white bg-opacity-50">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="show_result" id="sr" value="1">
                                <label class="form-check-label" for="sr">Tampilkan Nilai Akhir</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-lg">
                        <i class="bi bi-rocket-takeoff me-2"></i> TERBITKAN JADWAL & GENERATE TOKEN
                    </button>
                    <p class="text-center text-muted small mt-3">
                        <i class="bi bi-info-circle"></i> Jadwal yang diterbitkan akan langsung muncul di halaman santri sesuai waktu mulai.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Validasi sederhana sebelum submit
    document.getElementById('examForm').addEventListener('submit', function(e) {
        const start = new Date(this.start_time.value);
        const end = new Date(this.end_time.value);

        if (end <= start) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Waktu Tidak Valid',
                text: 'Waktu selesai harus lebih besar dari waktu mulai!',
                confirmButtonColor: '#0d6efd'
            });
        }
    });
</script>
@endpush