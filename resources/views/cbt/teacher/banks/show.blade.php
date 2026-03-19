@extends('layouts.app')
@section('title', 'Detail Bank Soal CBT')

@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
    /* Tipografi Umum */
    .text-dynamic, .text-dynamic p {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        font-size: 1.1rem;
        line-height: 1.7;
        color: #334155;
    }

    /* Tipografi Khusus Arab */
    .font-arabic, .font-arabic * {
        font-family: 'Amiri', serif !important;
        font-size: 1.75rem !important;
        line-height: 2.2 !important;
        color: #1e293b;
    }

    /* Opsi Jawaban Benar */
    .correct-answer {
        background-color: rgba(25, 135, 84, 0.08) !important;
        border-color: rgba(25, 135, 84, 0.4) !important;
    }

    /* Garis putus-putus untuk empty state / media */
    .border-dashed {
        border: 2px dashed #dee2e6;
    }

    .icon-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Efek transisi halus pada opsi jawaban */
    .option-card {
        transition: all 0.2s ease;
    }
    .option-card:hover {
        border-color: #adb5bd !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <a href="{{ route('teacher.cbt.banks.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Bank
        </a>
        <a href="{{ route('teacher.cbt.questions.create', $bank->id) }}" class="btn btn-primary rounded-pill shadow-sm px-4">
            <i class="bi bi-plus-lg me-1"></i> Tambah Soal Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
        <div class="bg-primary" style="height: 5px;"></div>
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center gy-4">
                <div class="col-md-7">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-3">
                        <i class="bi bi-upc-scan me-1"></i> Kode: {{ $bank->bank_code }}
                    </span>
                    <h3 class="fw-bolder text-dark mb-2">{{ $bank->subject_name }}</h3>
                    <div class="text-muted d-flex align-items-center gap-3">
                        <span><i class="bi bi-mortarboard me-1"></i> Kelas: <strong class="text-dark">{{ $bank->level }}</strong></span>
                        <span class="text-muted">•</span>
                        <span class="badge {{ $bank->is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-muted' }} rounded-pill">
                            <i class="bi {{ $bank->is_active ? 'bi-check-circle-fill' : 'bi-pencil-fill' }} me-1"></i> 
                            {{ $bank->is_active ? 'Status: Aktif' : 'Status: Draft' }}
                        </span>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="d-flex flex-column flex-sm-row justify-content-md-end gap-3 align-items-center">
                        <div class="text-center px-4 border-end">
                            <h2 class="fw-bolder text-primary mb-0">{{ $bank->questions->count() }}</h2>
                            <small class="text-muted fw-medium">Total Soal</small>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <form action="{{ route('teacher.cbt.banks.toggle_status', $bank->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $bank->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} w-100 rounded-pill fw-medium" title="{{ $bank->is_active ? 'Jadikan Draft' : 'Aktifkan Bank' }}">
                                    <i class="bi {{ $bank->is_active ? 'bi-archive' : 'bi-check-circle' }} me-1"></i>
                                    {{ $bank->is_active ? 'Jadikan Draft' : 'Aktifkan Bank' }}
                                </button>
                            </form>
                            <form id="form-delete-bank" action="{{ route('teacher.cbt.banks.destroy', $bank->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-medium">
                                    <i class="bi bi-trash me-1"></i> Hapus Bank
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mb-4">
        <h5 class="fw-bolder text-dark mb-0">Daftar Pertanyaan</h5>
        <span class="badge bg-light text-muted rounded-pill border">{{ $bank->questions->count() }} Butir</span>
    </div>

    @forelse($bank->questions as $index => $q)
    <div class="card border border-light shadow-sm rounded-4 mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
            <div>
                @if($q->type == 'multiple_choice')
                <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2 fw-medium">Pilihan Ganda</span>
                @else
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2 fw-medium">Essay</span>
                @endif
            </div>
            <div>
                <span class="badge bg-secondary text-white border rounded-pill px-3 py-2 fw-medium">Poin: {{ $q->score_weight }}</span>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="d-flex gap-3">
                <div class="fw-bolder fs-5 text-primary">{{ $index + 1 }}.</div>
                <div class="w-100">
                    
                    @php
                        $isArabic = preg_match('/\p{Arabic}/u', strip_tags($q->question_text));
                    @endphp
                    <div class="text-dynamic mb-3 {{ $isArabic ? 'font-arabic' : '' }}" dir="auto">
                        {!! $q->question_text !!}
                    </div>

                    @if($q->image_file || $q->audio_file)
                    <div class="p-4 mb-4 bg-light rounded-4 border border-dashed text-center">
                        @if($q->image_file)
                        <img src="{{ asset('storage/' . $q->image_file) }}" alt="Gambar Soal" class="img-fluid rounded-3 shadow-sm mb-3" style="max-height: 250px; object-fit: contain;">
                        @endif

                        @if($q->audio_file)
                        <div class="w-100 d-flex justify-content-center">
                            <audio controls class="w-100 shadow-sm rounded-pill" style="max-width: 500px;">
                                <source src="{{ asset('storage/' . $q->audio_file) }}" type="audio/mpeg">
                                Browser Anda tidak mendukung elemen audio.
                            </audio>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($q->type == 'multiple_choice')
                    <div class="row g-3 mt-1">
                        @php $labels = ['A', 'B', 'C', 'D', 'E']; @endphp
                        @foreach($q->options as $optIndex => $option)
                        <div class="col-md-6">
                            <div class="p-3 border rounded-4 h-100 option-card {{ $option->is_correct ? 'correct-answer' : 'bg-white text-dark' }}">
                                <div class="d-flex gap-3 align-items-start h-100">
                                    <strong class="fs-5 {{ $option->is_correct ? 'text-success' : 'text-muted' }}">{{ $labels[$optIndex] ?? '-' }}.</strong>
                                    
                                    <div class="flex-grow-1">
                                        @if($option->option_text)
                                        @php
                                            $isOptArabic = preg_match('/\p{Arabic}/u', $option->option_text);
                                        @endphp
                                        <div class="text-dynamic {{ $isOptArabic ? 'font-arabic' : '' }}" dir="auto">
                                            {{ $option->option_text }}
                                        </div>
                                        @endif

                                        @if($option->image_file)
                                        <img src="{{ asset('storage/' . $option->image_file) }}" alt="Gambar Opsi" class="img-fluid rounded-3 border mt-2 shadow-sm" style="max-height: 120px;">
                                        @endif
                                    </div>

                                    @if($option->is_correct)
                                    <i class="bi bi-check-circle-fill text-success fs-4 ms-2" title="Jawaban Benar"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="card-footer bg-light border-top-0 text-end py-3 px-4 rounded-bottom-4">
            <a href="{{ route('teacher.cbt.questions.edit', $q->id) }}" class="btn btn-white text-primary border shadow-sm btn-sm rounded-pill px-3 me-1 fw-medium">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <form action="{{ route('teacher.cbt.questions.destroy', $q->id) }}" method="POST" class="d-inline form-delete">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-white text-danger border shadow-sm btn-sm rounded-pill px-3 btn-delete fw-medium">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="card border-0 border-dashed rounded-4 bg-light shadow-sm">
        <div class="card-body text-center py-5 my-4">
            <div class="icon-circle bg-white shadow-sm mb-3">
                <i class="bi bi-journal-x text-muted fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark">Belum Ada Soal</h5>
            <p class="text-muted mb-4">Bank soal ini masih kosong. Silakan tambahkan pertanyaan pertama Anda untuk memulai.</p>
            <a href="{{ route('teacher.cbt.questions.create', $bank->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> Tambah Pertanyaan Baru
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // ... (Bagian JavaScript SweetAlert tetap sama persis seperti kode Anda sebelumnya) ...
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Konfirmasi Hapus Soal
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const form = this.closest('.form-delete');
        Swal.fire({
          title: 'Hapus Soal Ini?',
          text: "Soal yang sudah dihapus tidak dapat dikembalikan lagi.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus Saja',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // 2. Konfirmasi Hapus Bank Soal
    const deleteBankForm = document.getElementById('form-delete-bank');
    if (deleteBankForm) {
      deleteBankForm.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Hapus Bank Soal?',
          text: "Apakah Anda yakin ingin menghapus Bank Soal ini secara permanen beserta seluruh soal di dalamnya? Jika bank soal ini sudah pernah diujikan, sistem akan menolak penghapusan ini demi keamanan data.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus Bank',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            this.submit();
          }
        });
      });
    }

    // 3. Flash Message
    @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: "{{ session('success') }}",
      timer: 3000,
      showConfirmButton: false
    });
    @endif
    @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: "{{ session('error') }}"
    });
    @endif
  });
</script>
@endpush