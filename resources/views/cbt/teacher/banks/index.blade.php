@extends('layouts.app')
@section('title', 'Bank Soal CBT')
@push('link')
@endpush

@push('styles')
<style>
    /* Efek hover untuk card */
    .card-hover-fx {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-hover-fx:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
    }
    
    /* Garis putus-putus untuk empty state */
    .border-dashed {
        border: 2px dashed #dee2e6;
    }

    /* Lingkaran icon untuk estetika */
    .icon-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Efek redup khusus untuk draft */
    .draft-card {
        background-color: #f8f9fa !important; /* bg-light */
        border-color: #e9ecef !important;
        opacity: 0.85;
    }
    .draft-card:hover {
        opacity: 1; /* Kembali normal saat di-hover */
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h3 class="fw-bolder text-dark mb-1">Bank Soal CBT</h3>
            <p class="text-muted mb-0">Kelola dan organisir kumpulan soal ujian santri dengan mudah.</p>
        </div>
        <button class="btn btn-primary btn-lg rounded-pill shadow-sm px-4 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createBankModal">
            <i class="bi bi-plus-circle-fill"></i> Buat Bank Soal
        </button>
    </div>

    <div class="row g-4">
        {{-- Mengurutkan bank soal: Aktif di atas, Draft di bawah --}}
        @forelse($banks->sortByDesc('is_active') as $bank)
        <div class="col-md-6 col-lg-4">
            {{-- Menerapkan class draft-card jika statusnya tidak aktif --}}
            <div class="card shadow-sm rounded-4 h-100 card-hover-fx {{ $bank->is_active ? 'border-light bg-white' : 'draft-card' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                            {{ $bank->bank_code }}
                        </span>
                        
                        <span class="badge {{ $bank->is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary text-muted bg-opacity-10' }} px-3 py-2 rounded-pill fw-semibold">
                            <i class="bi {{ $bank->is_active ? 'bi-check-circle-fill' : 'bi-pencil-fill' }} me-1"></i>
                            {{ $bank->is_active ? 'Aktif' : 'Draft' }}
                        </span>
                    </div>
                    
                    <h5 class="fw-bolder mb-1 {{ $bank->is_active ? 'text-dark' : 'text-muted' }}">{{ $bank->subject_name }}</h5>
                    <div class="text-muted small mb-4">
                        <i class="bi bi-mortarboard me-1"></i> Kelas/Level: <span class="fw-medium">{{ $bank->level }}</span>
                    </div>

                    <div class="d-flex align-items-center text-muted rounded-3 p-2 px-3 mb-3 {{ $bank->is_active ? 'bg-light' : 'bg-white border' }}">
                        <i class="bi bi-stickies text-primary me-2"></i> 
                        <span class="fw-medium {{ $bank->is_active ? 'text-dark' : 'text-muted' }} me-1">{{ $bank->questions_count }}</span> Butir Soal
                    </div>
                </div>
                
                <div class="card-footer bg-transparent border-top-0 px-4 pb-4 pt-0">
                    <a href="{{ route('teacher.cbt.banks.show', $bank->id) }}" class="btn {{ $bank->is_active ? 'btn-outline-primary' : 'btn-outline-secondary' }} w-100 rounded-pill fw-medium">
                        Buka Bank Soal <i class="bi bi-arrow-right-short ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 border-dashed rounded-4 bg-light shadow-sm">
                <div class="card-body text-center py-5 my-4">
                    <div class="icon-circle bg-white shadow-sm mb-3">
                        <i class="bi bi-folder-x text-muted fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Belum ada Bank Soal</h5>
                    <p class="text-muted mb-4">Anda belum memiliki kumpulan soal. Mulai buat bank soal pertama Anda sekarang.</p>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createBankModal">
                        <i class="bi bi-plus-lg me-1"></i> Buat Bank Soal
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="createBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('teacher.cbt.banks.store') }}" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bolder text-dark">Buat Bank Soal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-4">Silakan tentukan mata pelajaran dan tingkat kelas untuk bank soal baru ini.</p>
                
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark small">Mata Pelajaran</label>
                    <select name="subject_name" class="form-select form-select-lg rounded-3 fs-6" required>
                        <option value="" selected disabled>Pilih Mata Pelajaran...</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold text-dark small">Kelas / Tingkat</label>
                    <select name="level" class="form-select form-select-lg rounded-3 fs-6" required>
                        <option value="" selected disabled>Pilih Kelas / Tingkat...</option>
                        @foreach($levels as $level)
                        <option value="{{ $level->name }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1">Simpan Bank Soal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@endpush