@extends('layouts.app')
@section('title', 'Koreksi Lembar Jawaban')

@push('link')
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
    /* Typography - Diperkecil agar lebih standar */
    .text-dynamic, .text-dynamic p {
        font-family: 'Segoe UI', Tahoma, 'Traditional Arabic', serif;
        font-size: 1.05rem; /* Lebih standar, sebelumnya 1.15rem */
        line-height: 1.6;
        color: #2b2b2b;
        margin-bottom: 0;
    }
    .font-arabic {
        font-family: 'Amiri', 'Traditional Arabic', serif !important;
        font-size: 1.5rem !important; /* Diperkecil sedikit */
        line-height: 2 !important;
    }

    /* Premium UI Elements */
    .card-premium {
        border: none;
        border-radius: 12px; /* Melengkung standar */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
    }
    .score-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
    }
    .student-answer-box {
        background-color: #f8faff;
        border-left: 4px solid #0d6efd;
        padding: 1rem; /* Lebih padat */
        border-radius: 6px 10px 10px 6px;
        position: relative;
    }
    .student-answer-box::before {
        content: '\F6B0';
        font-family: 'Bootstrap-icons';
        position: absolute;
        top: 5px;
        right: 15px;
        font-size: 1.5rem;
        color: rgba(13, 110, 253, 0.08);
    }
    
    /* Option Boxes - Lebih compact */
    .option-box {
        transition: all 0.2s;
        border-width: 1px !important;
    }
    
    /* Sticky Bottom Bar */
    .floating-action-bar {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        z-index: 1000;
    }

    /* Input Grading */
    .grading-input-group {
        width: 150px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .grading-input-group input {
        font-size: 1.1rem;
        color: #0d6efd;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-journal-check fs-5 text-primary"></i>
                <h5 class="fw-bold mb-0 text-dark">Koreksi Lembar Jawaban</h5>
            </div>
            <div class="text-muted small mt-1">
                Santri: <strong class="text-dark bg-light px-2 py-1 rounded border">{{ $studentExam->cbtAccount->student->name }}</strong> &bull; 
                Ujian: <strong class="text-dark">{{ $studentExam->exam->name }}</strong>
            </div>
        </div>
        <a href="{{ route('teacher.cbt.results.show', $studentExam->exam->id) }}" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-5">
            <div class="card-premium score-card h-100 p-3 d-flex justify-content-between align-items-center overflow-hidden position-relative">
                <i class="bi bi-award position-absolute" style="font-size: 6rem; right: -15px; top: -10px; opacity: 0.1;"></i>
                <div class="position-relative z-1">
                    <h6 class="mb-0 text-white-50 fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.85rem;">Total Nilai Akhir</h6>
                    <div class="small text-white-50 mb-0" style="font-size: 0.75rem;">*Gabungan PG & Essay</div>
                </div>
                <div class="display-4 fw-bold position-relative z-1">{{ round($studentExam->score) }}</div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card-premium bg-white h-100 p-3 d-flex align-items-center border-start border-4 border-success">
                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3 text-success">
                    <i class="bi bi-ui-checks fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Poin Pilihan Ganda: <span class="text-success">{{ $pgScore }} Poin</span></h6>
                    <p class="text-muted mb-0 lh-sm" style="font-size: 0.8rem;">Nilai akhir di samping akan otomatis dikalkulasi ulang setelah Anda menyimpan penilaian essay di bawah.</p>
                </div>
            </div>
        </div>
    </div>

    @if($pgAnswersList->isNotEmpty())
    <div class="d-flex align-items-center mb-3 mt-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-ui-radios text-primary me-2"></i>Review Pilihan Ganda</h6>
        <span class="badge bg-light text-muted border ms-2 fw-normal" style="font-size: 0.75rem;"><i class="bi bi-robot me-1"></i> Dinilai Otomatis</span>
    </div>

    <div class="row g-3 mb-4">
        @foreach($pgAnswersList as $index => $ans)
        <div class="col-12">
            <div class="card-premium border border-light" style="background-color: #fbfcfd;">
                <div class="card-body p-3 p-md-4">
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 border-bottom pb-2">
                        <span class="fw-bold text-secondary small">
                            <i class="bi bi-hash"></i> SOAL {{ $index + 1 }} <span class="text-muted fw-normal ms-1">| Bobot: {{ $ans->question->score_weight }} Poin</span>
                        </span>

                        @if($ans->is_correct)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded"><i class="bi bi-check-circle-fill me-1"></i> Benar</span>
                        @elseif($ans->cbt_option_id === null)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1 rounded"><i class="bi bi-dash-circle-fill me-1"></i> Kosong</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded"><i class="bi bi-x-circle-fill me-1"></i> Salah</span>
                        @endif
                    </div>
                    
                    @php $isPgArabic = preg_match('/\p{Arabic}/u', $ans->question->question_text); @endphp
                    <div class="text-dynamic mb-3 {{ $isPgArabic ? 'font-arabic' : '' }}" dir="auto">
                        {!! $ans->question->question_text !!}

                        @if($ans->question->image_file)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$ans->question->image_file) }}" class="img-fluid rounded border shadow-sm" style="max-height: 150px; object-fit: contain;">
                        </div>
                        @endif
                    </div>

                    <div class="row g-2">
                        @foreach($ans->question->options as $opt)
                        @php
                            $bgClass = 'bg-white text-muted';
                            $border = 'border-light';
                            $icon = '';
                            $badge = '';

                            if ($opt->is_correct) {
                                if ($ans->cbt_option_id == $opt->id) {
                                    $bgClass = 'bg-success bg-opacity-10 text-success fw-bold';
                                    $border = 'border-success';
                                    $icon = '<i class="bi bi-check-circle-fill fs-5 text-success"></i>';
                                    $badge = '<span class="badge bg-success mt-1 d-inline-block" style="font-size:0.65rem;">Jawaban Santri</span>';
                                } else {
                                    $bgClass = 'bg-white text-dark fw-bold';
                                    $border = 'border-success border-opacity-50';
                                    $icon = '<i class="bi bi-check-circle fs-5 text-success opacity-75"></i>';
                                    $badge = '<span class="badge bg-light text-success border border-success mt-1 d-inline-block" style="font-size:0.65rem;">Kunci Jawaban</span>';
                                }
                            } elseif ($ans->cbt_option_id == $opt->id) {
                                $bgClass = 'bg-danger bg-opacity-10 text-danger fw-bold';
                                $border = 'border-danger';
                                $icon = '<i class="bi bi-x-circle-fill fs-5 text-danger"></i>';
                                $badge = '<span class="badge bg-danger mt-1 d-inline-block" style="font-size:0.65rem;">Jawaban Santri</span>';
                            }
                        @endphp

                        <div class="col-md-6">
                            <div class="px-3 py-2 border rounded-3 d-flex align-items-center h-100 option-box {{ $bgClass }} {{ $border }}">
                                @php $isOptArabic = preg_match('/\p{Arabic}/u', $opt->option_text); @endphp
                                <div class="flex-grow-1">
                                    <div class="text-dynamic {{ $isOptArabic ? 'font-arabic' : '' }}" dir="auto">
                                        {{ $opt->option_text }}
                                        @if($opt->image_file)
                                            <div class="mt-1">
                                                <img src="{{ asset('storage/'.$opt->image_file) }}" class="img-fluid rounded border" style="max-height: 80px; object-fit: contain;">
                                            </div>
                                        @endif
                                    </div>
                                    {!! $badge !!}
                                </div>
                                <div class="ms-2">
                                    {!! $icon !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($essayAnswers->isEmpty())
    <div class="card-premium text-center py-4 mb-4">
        <div class="py-3">
            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2 text-success">
                <i class="bi bi-check-lg fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark">Tidak Ada Essay</h5>
            <p class="text-muted mb-0 small">Ujian ini hanya terdiri dari Pilihan Ganda. Tidak ada koreksi manual yang diperlukan.</p>
        </div>
    </div>
    @else
    
    <form action="{{ route('teacher.cbt.results.store_correction', $studentExam->id) }}" method="POST" id="correctionForm">
        @csrf
        
        <div class="d-flex align-items-center mb-3 mt-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Penilaian Essay</h6>
            <span class="badge bg-warning text-dark ms-2 px-2 py-1 rounded" style="font-size: 0.7rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Perlu Dinilai</span>
        </div>

        <div class="row g-3 mb-5">
            @foreach($essayAnswers as $index => $ans)
            <div class="col-12">
                <div class="card-premium border border-primary border-opacity-10">
                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <span class="fw-bold text-secondary small">ESSAY #{{ $index + 1 }}</span>
                            <span class="badge bg-light text-primary border border-primary px-2 py-1 rounded">Bobot: {{ $ans->question->score_weight }}</span>
                        </div>
                        
                        <div class="mb-3">
                            @php $isEssayArabic = preg_match('/\p{Arabic}/u', $ans->question->question_text); @endphp
                            <div class="text-dynamic {{ $isEssayArabic ? 'font-arabic' : '' }}" dir="auto">
                                {!! $ans->question->question_text !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="fw-bold mb-1 text-primary small d-flex align-items-center">
                                <i class="bi bi-chat-left-text me-1"></i> Jawaban Santri:
                            </div>
                            @php $isAnswerArabic = $ans->essay_answer && preg_match('/\p{Arabic}/u', $ans->essay_answer); @endphp
                            <div class="student-answer-box text-dynamic {{ $isAnswerArabic ? 'font-arabic' : '' }}" dir="auto">
                                @if($ans->essay_answer)
                                    {!! nl2br(e($ans->essay_answer)) !!}
                                @else
                                    <span class="text-danger fw-medium"><i class="bi bi-dash-circle me-1"></i> Santri mengosongkan jawaban ini.</span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 border d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-6"><i class="bi bi-check2-square text-success me-1"></i>Nilai Jawaban</h6>
                                <p class="text-muted mb-0" style="font-size: 0.75rem;">Rentang: <strong>0</strong> s/d <strong>{{ $ans->question->score_weight }}</strong></p>
                            </div>
                            <div class="input-group grading-input-group">
                                <input type="number" name="scores[{{ $ans->id }}]" class="form-control text-center fw-bold border-primary" value="{{ $ans->score ?? 0 }}" min="0" max="{{ $ans->question->score_weight }}" step="0.1" required>
                                <span class="input-group-text bg-primary text-white border-primary px-2" style="font-size: 0.85rem;">Poin</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="floating-action-bar position-sticky bottom-0 mb-3 d-flex justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center text-muted">
                <i class="bi bi-info-circle-fill text-primary me-2 fs-5 d-none d-sm-block"></i>
                <small class="fw-medium lh-sm" style="font-size: 0.75rem;">Sistem otomatis merekap <br class="d-none d-sm-block">total nilai setelah disimpan.</small>
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Menyimpan...'; this.classList.add('disabled'); this.form.submit();">
                <i class="bi bi-save2 me-2"></i> Simpan Nilai
            </button>
        </div>

    </form>
    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scoreInputs = document.querySelectorAll('input[type="number"]');
        scoreInputs.forEach(input => {
            input.addEventListener('input', function() {
                let max = parseFloat(this.getAttribute('max'));
                let val = parseFloat(this.value);
                if (val > max) {
                    this.value = max;
                } else if (val < 0) {
                    this.value = 0;
                }
            });
        });
    });
</script>
@endpush