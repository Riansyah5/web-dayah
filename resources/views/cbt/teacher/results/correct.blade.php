@extends('layouts.app')
@section('title', 'Koreksi Lembar Jawaban')
@push('link')
@endpush
@push('styles')
<style>
  .text-dynamic,
  .text-dynamic p {
    font-family: 'Segoe UI', Tahoma, 'Traditional Arabic', serif;
    font-size: 1.25rem;
    line-height: 1.8;
  }

  .student-answer-box {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
    padding: 15px;
    border-radius: 0 8px 8px 0;
  }

</style>
@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Koreksi Lembar Jawaban</h4>
      <div class="text-muted">
        Santri: <strong class="text-dark">{{ $studentExam->cbtAccount->student->name }}</strong> |
        Ujian: <strong class="text-dark">{{ $studentExam->exam->name }}</strong>
      </div>
    </div>
    <a href="{{ route('teacher.cbt.results.show', $studentExam->exam->id) }}" class="btn btn-light border rounded-pill shadow-sm">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card bg-primary text-white border-0 shadow-sm rounded-4 h-100">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
          <div>
            <h6 class="mb-1 opacity-75">Nilai Akhir Saat Ini (Skala 100)</h6>
            <small>*(Gabungan PG dan Essay yang sudah dinilai)</small>
          </div>
          <div class="fs-1 fw-bold">{{ round($studentExam->score) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
        <div class="card-body p-4 d-flex align-items-center">
          <i class="bi bi-info-circle fs-3 text-primary me-3"></i>
          <div>
            <h6 class="fw-bold mb-1">Poin Pilihan Ganda: <span class="text-success">{{ $pgScore }} Poin</span></h6>
            <small class="text-muted d-block">Nilai akhir otomatis berubah (rekalkulasi) ketika Anda menyimpan nilai essay di bawah ini.</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if($essayAnswers->isEmpty())
  <div class="alert alert-success rounded-4 text-center py-5 shadow-sm border-0">
    <i class="bi bi-check-circle-fill fs-1 text-success d-block mb-3"></i>
    <h5 class="fw-bold">Tidak Ada Essay</h5>
    <p class="mb-0 text-muted">Ujian ini tidak memiliki soal essay yang perlu dikoreksi manual.</p>
  </div>
  @else
  <form action="{{ route('teacher.cbt.results.store_correction', $studentExam->id) }}" method="POST">
    @csrf

    <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Daftar Jawaban Essay Santri</h5>

    @foreach($essayAnswers as $index => $ans)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-body p-4">

        <div class="d-flex justify-content-between mb-3">
          <span class="badge bg-secondary rounded-pill">Bobot Maksimal Soal: {{ $ans->question->score_weight }} Poin</span>
        </div>
        <div class="fw-bold mb-2">Pertanyaan:</div>
        <div class="text-dynamic mb-4 border-bottom pb-3" dir="auto">
          {{-- {!! nl2br(e($ans->question->question_text)) !!} --}}
          {!! $ans->question->question_text!!}
        </div>

        <div class="fw-bold mb-2 text-primary">Jawaban Santri:</div>
        <div class="student-answer-box text-dynamic mb-4" dir="auto">
          @if($ans->essay_answer)
          {!! nl2br(e($ans->essay_answer)) !!}
          @else
          <span class="text-muted fst-italic">Santri mengosongkan jawaban ini (Tidak dijawab).</span>
          @endif
        </div>

        <div class="bg-warning bg-opacity-10 p-3 rounded border border-warning d-flex align-items-center justify-content-between">
          <div>
            <h6 class="fw-bold text-dark mb-0">Berikan Nilai untuk Jawaban Ini:</h6>
            <small class="text-muted">Isi dari 0 sampai {{ $ans->question->score_weight }}</small>
          </div>
          <div class="input-group" style="width: 150px;">
            <input type="number" name="scores[{{ $ans->id }}]" class="form-control form-control-lg text-center fw-bold" value="{{ $ans->score ?? 0 }}" min="0" max="{{ $ans->question->score_weight }}" step="0.5" required>
            <span class="input-group-text">Poin</span>
          </div>
        </div>

      </div>
    </div>
    @endforeach

    <div class="card border-0 shadow-sm rounded-4 position-sticky bottom-0 mb-4" style="z-index: 100;">
      <div class="card-body bg-white rounded-4 p-3 d-flex justify-content-between align-items-center">
        <span class="text-muted small"><i class="bi bi-exclamation-circle me-1"></i> Pastikan semua essay sudah diberi nilai.</span>
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
          <i class="bi bi-save me-2"></i> Simpan Nilai & Rekalkulasi
        </button>
      </div>
    </div>

  </form>
  @endif

</div>
@endsection
@push('scripts')
@endpush
