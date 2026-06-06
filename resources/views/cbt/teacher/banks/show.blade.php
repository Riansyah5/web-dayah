@extends('layouts.app')
@section('title', 'Detail Bank Soal CBT')

@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #f6f8fb 0%, #e9f0f7 100%);
        min-height: 100vh;
    }

    /* Typography & Content */
    .text-dynamic {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #334155;
    }

    .font-arabic {
        font-family: 'Amiri', serif !important;
        font-size: 2.2rem !important;
        line-height: 2 !important;
        color: #0f172a;
        direction: rtl;
        margin-bottom: 1.5rem;
    }

    /* Glassmorphism Components */
    .glass-header {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
    }

    .question-card {
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 20px;
        transition: transform 0.2s ease;
    }

    .question-card:hover {
        border-color: rgba(13, 110, 253, 0.2);
    }

    /* Answer Options Styling */
    .option-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        height: 100%;
        transition: all 0.2s;
    }

    .option-box.is-correct {
        background: rgba(25, 135, 84, 0.05);
        border: 2px solid #198754;
        position: relative;
    }

    .correct-label {
        background: #198754;
        color: white;
        font-size: 0.65rem;
        padding: 2px 10px;
        border-radius: 50px;
        position: absolute;
        top: -10px;
        right: 15px;
        font-weight: bold;
    }

    /* Stats Indicator */
    .stat-circle {
        width: 70px;
        height: 70px;
        border: 4px solid #a555d0;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.5rem;
        background: white;
    }

    .btn-action-glass {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        transition: all 0.2s;
    }

    .btn-action-glass:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-3 animate__animated animate__fadeIn">
        <a href="{{ route('teacher.cbt.banks.index') }}" class="btn btn-outline-secondary shadow-sm rounded-pill px-4 fw-bold">
            <i class="bi bi-chevron-left me-2"></i>Daftar Bank
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('teacher.cbt.questions.create', $bank->id) }}" class="btn btn-primary rounded-pill shadow px-4 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Tambah Pertanyaan
            </a>
        </div>
    </div>

    <div class="glass-header p-4 p-md-5 mb-5 shadow-sm animate__animated animate__fadeInDown">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm me-3">CODE: {{ $bank->bank_code }}</span>
                    @if($bank->is_active)
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">
                            <i class="bi bi-patch-check-fill me-1"></i>AKTIF
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-muted rounded-pill px-3 py-2 fw-bold">
                            <i class="bi bi-file-earmark-lock-fill me-1"></i>DRAFT
                        </span>
                    @endif
                </div>
                <h1 class="fw-extra-bold text-dark display-6 mb-2">{{ $bank->subject_name }}</h1>
                <p class="text-muted fs-5 mb-0">Kurikulum untuk <span class="text-primary fw-bold">{{ $bank->level }}</span></p>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="d-flex justify-content-lg-end align-items-center gap-4">
                    <div class="text-center">
                        <div class="stat-circle text-secondary mx-auto mb-2">{{ $bank->questions->count() }}</div>
                        <small class="text-uppercase fw-bold text-muted" style="letter-spacing: 1px;">Pertanyaan</small>
                    </div>
                    <div class="vr mx-2 h-100 opacity-25"></div>
                    <div class="d-flex flex-column gap-2">
                        <form action="{{ route('teacher.cbt.banks.toggle_status', $bank->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-glass btn-sm w-100 rounded-pill px-3 fw-bold text-dark">
                                <i class="bi {{ $bank->is_active ? 'bi-archive text-warning' : 'bi-check-circle text-success' }} me-2"></i>
                                {{ $bank->is_active ? 'Set Draft' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form id="form-delete-bank" action="{{ route('teacher.cbt.banks.destroy', $bank->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-action-glass btn-sm w-100 rounded-pill px-3 fw-bold text-danger">
                                <i class="bi bi-trash3 me-2"></i>Hapus Arsip
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4 d-flex align-items-center">
            <h4 class="fw-bolder mb-0 me-3">Daftar Pertanyaan</h4>
            <div class="flex-grow-1 border-bottom opacity-25"></div>
        </div>

        @forelse($bank->questions as $index => $q)
        <div class="col-12 mb-5 animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="question-card shadow-sm overflow-hidden">
                <div class="p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="btn btn-primary rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; pointer-events: none;">
                                {{ $index + 1 }}
                            </span>
                            <span class="badge {{ $q->type == 'multiple_choice' ? 'bg-info bg-opacity-10 text-info' : 'bg-warning bg-opacity-10 text-warning' }} rounded-pill px-3">
                                {{ $q->type == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                            </span>
                        </div>
                        <span class="fw-bold text-muted small"><i class="bi bi-award me-1"></i>Poin: {{ $q->score_weight }}</span>
                    </div>

                    <div class="question-body">
                        @php $isArabic = preg_match('/\p{Arabic}/u', strip_tags($q->question_text)); @endphp
                        <div class="text-dynamic mb-4 {{ $isArabic ? 'font-arabic' : '' }}">
                            {!! $q->question_text !!}
                        </div>

                        @if($q->image_file || $q->audio_file)
                        <div class="bg-light rounded-4 p-4 mb-4 border border-dashed text-center">
                            @if($q->image_file)
                                <img src="{{ asset('storage/' . $q->image_file) }}" class="img-fluid rounded-3 shadow-sm mb-3" style="max-height: 300px;">
                            @endif
                            @if($q->audio_file)
                                <div class="w-100 d-flex justify-content-center">
                                    <audio controls class="w-100 shadow-sm" style="max-width: 500px;">
                                        <source src="{{ asset('storage/' . $q->audio_file) }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            @endif
                        </div>
                        @endif

                        @if($q->type == 'multiple_choice')
                        <div class="row g-3">
                            @php $labels = ['A', 'B', 'C', 'D', 'E']; @endphp
                            @foreach($q->options as $optIndex => $option)
                            <div class="col-md-6">
                                <div class="option-box {{ $option->is_correct ? 'is-correct' : '' }}">
                                    @if($option->is_correct)
                                        <span class="correct-label">JAWABAN BENAR</span>
                                    @endif
                                    <div class="d-flex gap-3">
                                        <div class="fw-bold {{ $option->is_correct ? 'text-success' : 'text-primary' }}">{{ $labels[$optIndex] }}.</div>
                                        <div class="flex-grow-1">
                                            @php $isOptArabic = preg_match('/\p{Arabic}/u', $option->option_text); @endphp
                                            <div class="fw-medium {{ $isOptArabic ? 'font-arabic fs-4 mb-0' : '' }}">
                                                {{ $option->option_text }}
                                            </div>
                                            @if($option->image_file)
                                                <img src="{{ asset('storage/' . $option->image_file) }}" class="img-fluid border rounded-3 mt-2 shadow-sm" style="max-height: 100px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-light bg-opacity-50 px-5 py-3 d-flex justify-content-end gap-2 border-top">
                    <a href="{{ route('teacher.cbt.questions.edit', $q->id) }}" class="btn btn-sm btn-action-glass rounded-pill px-3 fw-bold">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit
                    </a>
                    <form action="{{ route('teacher.cbt.questions.destroy', $q->id) }}" method="POST" class="form-delete d-inline">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-action-glass rounded-pill px-3 fw-bold text-danger btn-delete">
                            <i class="bi bi-trash3 me-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 py-5 text-center animate__animated animate__fadeIn">
            <div class="bg-white glass-header py-5 rounded-5 border-dashed border-2">
                <i class="bi bi-journal-x display-1 text-muted opacity-25 mb-3"></i>
                <h4 class="fw-bold text-dark">Belum Ada Soal</h4>
                <p class="text-muted">Bank soal ini masih kosong. Tambahkan pertanyaan untuk memulai.</p>
                <a href="{{ route('teacher.cbt.questions.create', $bank->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Soal Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Global SweetAlert Style
        const swalConfig = {
            borderRadius: '20px',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#f8f9fa',
            customClass: {
                cancelButton: 'text-dark border rounded-pill px-4 fw-bold',
                confirmButton: 'rounded-pill px-4 fw-bold'
            }
        };

        // Confirm Delete Question
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.form-delete');
                Swal.fire({
                    ...swalConfig,
                    title: 'Hapus Soal?',
                    text: "Tindakan ini tidak bisa dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });

        // Confirm Delete Bank
        const deleteBankForm = document.getElementById('form-delete-bank');
        if (deleteBankForm) {
            deleteBankForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    ...swalConfig,
                    title: 'Hapus Seluruh Bank?',
                    text: "Semua pertanyaan di dalamnya akan ikut terhapus permanen.",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Hapus Sekarang',
                    cancelButtonText: 'Batal'
                }).then((result) => { if (result.isConfirmed) this.submit(); });
            });
        }

        // Auto-close success alerts
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                borderRadius: '20px'
            });
        @endif
    });
</script>
@endpush