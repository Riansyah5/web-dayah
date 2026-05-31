@extends('layouts.app')
@section('title', 'Bank Soal CBT')

@push('styles')
<style>
    body {
        /*background: linear-gradient(135deg, #f6f8fb 0%, #e9f0f7 100%);
        min-height: 100vh;*/
    }

    /* Glassmorphism Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
        transform: translateY(-8px);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        border-color: rgba(13, 110, 253, 0.2);
    }

    /* Status Badges */
    .badge-premium {
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    /* Header Design */
    .text-gradient-primary {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .header-icon-box {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 16px;
        box-shadow: 0 12px 20px -5px rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    /* Stats Indicator */
    .stats-pill {
        background: rgba(13, 110, 253, 0.05);
        border: 1px solid rgba(13, 110, 253, 0.1);
        border-radius: 14px;
        padding: 12px;
        transition: all 0.3s;
    }

    .glass-card:hover .stats-pill {
        background: #0d6efd;
        color: white !important;
    }

    .glass-card:hover .stats-pill i {
        color: white !important;
    }

    /* Modal Glassmorphism */
    .modal-content.glass-modal {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 28px;
    }

    /* Empty State */
    .border-dashed-premium {
        border: 2px dashed rgba(0,0,0,0.1);
        background: rgba(255, 255, 255, 0.4);
        border-radius: 24px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-4 animate__animated animate__fadeIn">
        <div class="col-md-7">
            <div class="d-flex align-items-center">
                <div class="header-icon-box me-3">
                    <i class="bi bi-folder2-open fs-3"></i>
                </div>
                <div>
                    <h2 class="fw-bolder mb-0 text-dark">Arsip <span class="text-gradient-primary">Bank Soal</span></h2>
                    <p class="text-muted small mb-0">Organisir pustaka soal ujian Anda dengan standar profesional.</p>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end mt-4 mt-md-0">
            <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createBankModal">
                <i class="bi bi-plus-lg me-2"></i>Tambah Bank Soal
            </button>
        </div>
    </div>
    
        <div class="row g-4 mb-5 animate__animated animate__fadeIn">
        <div class="col-md-4">
            <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary bg-opacity-10 text-primary me-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-collection fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bolder mb-0 text-dark">{{ $banks->count() }}</h3>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Total Bank Soal</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-success bg-opacity-10 text-success me-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bolder mb-0 text-dark">{{ $banks->where('is_active', true)->count() }}</h3>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Status Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-4 bg-warning bg-opacity-10 text-warning me-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-pencil-square fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bolder mb-0 text-dark">{{ $banks->where('is_active', false)->count() }}</h3>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Status Draft</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($banks->sortByDesc('is_active') as $bank)
        <div class="col-md-6 col-lg-4 animate__animated animate__zoomIn">
            <div class="glass-card h-100 d-flex flex-column shadow-sm overflow-hidden border-0">
                <div class="p-4 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-white shadow-sm text-primary border rounded-pill px-3 py-2 fw-bold small">
                            #{{ $bank->bank_code }}
                        </span>
                        
                        @if($bank->is_active)
                            <span class="badge-premium bg-success bg-opacity-10 text-success">
                                <i class="bi bi-check-circle-fill me-1"></i> AKTIF
                            </span>
                        @else
                            <span class="badge-premium bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-pencil-square me-1"></i> DRAFT
                            </span>
                        @endif
                    </div>
                    
                    <h5 class="fw-extra-bold text-dark mb-2" style="letter-spacing: -0.5px;">{{ $bank->subject_name }}</h5>
                    <div class="d-flex align-items-center text-muted small">
                        <div class="bg-light rounded-circle p-1 me-2 d-flex">
                            <i class="bi bi-mortarboard-fill text-secondary"></i>
                        </div>
                        Tingkat: <span class="fw-bold ms-1 text-dark">{{ $bank->level }}</span>
                    </div>

                    <div class="d-flex align-items-center text-muted small mb-2">
                        <div class="bg-light rounded-circle p-1 me-2 d-flex">
                            <i class="bi bi-person-fill text-secondary"></i>
                        </div>
                        Guru: <span class="fw-bold ms-1 text-dark">{{ $bank->teacher->name ?? Auth::user()->name }}</span>
                    </div> 

                    <div class="stats-pill d-flex align-items-center justify-content-between text-primary">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-stack me-2 fs-5"></i>
                            <span class="fw-bold fs-5">{{ $bank->questions_count }}</span>
                        </div>
                        <span class="small fw-semibold opacity-75">Butir Soal</span>
                    </div>
                </div>
                
                <div class="px-4 pb-4">
                    <a href="{{ route('teacher.cbt.banks.show', $bank->id) }}" class="btn btn-white border shadow-sm w-100 rounded-pill fw-bold text-dark hover-primary py-2">
                        Kelola Soal <i class="bi bi-arrow-right-short ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 animate__animated animate__fadeIn">
            <div class="border-dashed-premium py-5 text-center">
                <img src="https://illustrations.popsy.co/gray/folder-is-empty.svg" style="width: 180px;" class="mb-4 opacity-75" alt="Empty">
                <h4 class="fw-bold text-dark">Pustaka Soal Kosong</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">Belum ada bank soal yang dibuat. Mulai susun kurikulum ujian Anda sekarang.</p>
                <button class="btn btn-primary rounded-pill px-4 mt-2" data-bs-toggle="modal" data-bs-target="#createBankModal">
                    Mulai Buat <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="createBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal shadow-lg border-0">
            <form action="{{ route('teacher.cbt.banks.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <h4 class="fw-extra-bold text-dark mb-0">Bank Soal Baru</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small ms-1 text-uppercase">Mata Pelajaran</label>
                        <select name="subject_name" class="form-select border-0 shadow-sm rounded-4 py-3 px-3 fs-6" style="background: rgba(255,255,255,0.8)" required>
                            <option value="" selected disabled>Pilih mata pelajaran...</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark small ms-1 text-uppercase">Target Tingkatan/Kelas</label>
                        <select name="level" class="form-select border-0 shadow-sm rounded-4 py-3 px-3 fs-6" style="background: rgba(255,255,255,0.8)" required>
                            <option value="" selected disabled>Pilih tingkatan...</option>
                            @foreach($levels as $level)
                            <option value="{{ $level->name }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1 fw-bold shadow">
                        Simpan Arsip <i class="bi bi-check-lg ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi tooltip atau animasi tambahan jika diperlukan
</script>
@endpush