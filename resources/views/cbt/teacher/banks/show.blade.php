@extends('layouts.app')
@section('title', 'Detail Bank Soal CBT')
@push('link')
@endpush
@push('styles')
<style>
  /* Font fallback agar tulisan Arab terbaca indah dan besar */
  .text-dynamic {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, 'Traditional Arabic', 'Amiri', serif;
    font-size: 1.25rem;
    line-height: 1.8;
  }

  .correct-answer {
    background-color: #d1e7dd;
    /* Hijau muda */
    border-color: #badbcc;
    font-weight: bold;
  }

</style>
@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('teacher.cbt.banks.index') }}" class="btn btn-light rounded-pill border shadow-sm">
      <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Bank
    </a>
    <a href="{{ route('teacher.cbt.questions.create', $bank->id) }}" class="btn btn-primary rounded-pill shadow-sm">
      <i class="bi bi-plus-circle me-1"></i> Tambah Soal Baru
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
    <div class="card-body p-4 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="fw-bold mb-1">{{ $bank->subject_name }}</h4>
        <div class="opacity-75">
          <i class="bi bi-tags me-1"></i> Kelas: {{ $bank->level }} |
          <i class="bi bi-upc-scan ms-2 me-1"></i> Kode: {{ $bank->bank_code }}
        </div>
      </div>
      <div class="text-end">
        <div class="fs-2 fw-bold">{{ $bank->questions->count() }}</div>
        <small class="opacity-75">Total Soal</small>
      </div>
    </div>
  </div>

  <h5 class="fw-bold mb-3">Daftar Pertanyaan</h5>

  @forelse($bank->questions as $index => $q)
  <div class="card border-0 shadow-sm rounded-4 mb-4 position-relative overflow-hidden">
    <div class="position-absolute top-0 end-0 p-2">
      @if($q->type == 'multiple_choice')
      <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">Pilihan Ganda</span>
      @else
      <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill">Essay</span>
      @endif
      <span class="badge bg-secondary rounded-pill ms-1">Poin: {{ $q->score_weight }}</span>
    </div>

    <div class="card-body p-4">
      <div class="d-flex gap-3">
        <div class="fw-bold fs-5 text-primary">{{ $index + 1 }}.</div>

        <div class="w-100">
          <div class="text-dynamic mb-3" dir="auto">
            {!! nl2br(e($q->question_text)) !!}
          </div>

          @if($q->type == 'multiple_choice')
          <div class="row g-3 mt-2">
            @php $labels = ['A', 'B', 'C', 'D']; @endphp
            @foreach($q->options as $optIndex => $option)
            <div class="col-md-6">
              <div class="p-3 border rounded-3 {{ $option->is_correct ? 'correct-answer text-success' : 'bg-light text-muted' }}">
                <div class="d-flex gap-2 align-items-start">
                  <strong class="{{ $option->is_correct ? 'text-success' : 'text-dark' }}">{{ $labels[$optIndex] ?? '-' }}.</strong>
                  <div class="text-dynamic flex-grow-1" dir="auto">
                    {{ $option->option_text }}
                  </div>
                  @if($option->is_correct)
                  <i class="bi bi-check-circle-fill text-success fs-5"></i>
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

    <div class="card-footer bg-white border-top-0 text-end py-3">
      <form action="{{ route('teacher.cbt.questions.destroy', $q->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus soal ini?');">
          <i class="bi bi-trash me-1"></i> Hapus Soal
        </button>
      </form>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4 bg-light border-dashed">
    <div class="card-body text-center py-5">
      <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
      <h5 class="fw-bold text-muted">Belum Ada Soal</h5>
      <p class="text-muted mb-4">Bank soal ini masih kosong. Silakan tambahkan soal pertama Anda.</p>
      <a href="{{ route('teacher.cbt.questions.create', $bank->id) }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i> Tambah Pertanyaan
      </a>
    </div>
  </div>
  @endforelse

</div>
@endsection
@push('scripts')
@endpush
