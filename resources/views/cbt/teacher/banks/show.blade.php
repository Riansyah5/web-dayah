@extends('layouts.app')
@section('title', 'Detail Bank Soal CBT')
@push('link')
@endpush
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        {{-- teks soal --}}
        <div class="w-100">
          <div class="text-dynamic mb-3" dir="auto">
            {!! nl2br(e($q->question_text)) !!}
          </div>

          {{-- media soal (gambar/audio) --}}
          @if($q->image_file || $q->audio_file)
          <div class="p-3 mb-3 bg-light rounded-4 border border-dashed text-center">
            @if($q->image_file)
            <img src="{{ asset('storage/' . $q->image_file) }}" alt="Gambar Soal" class="img-fluid rounded mb-2 shadow-sm" style="max-height: 250px;">
            @endif

            @if($q->audio_file)
            <div class="mt-2 w-100 d-flex justify-content-center">
              <audio controls class="w-75 shadow-sm rounded-pill">
                <source src="{{ asset('storage/' . $q->audio_file) }}" type="audio/mpeg">
                Browser Anda tidak mendukung elemen audio.
              </audio>
            </div>
            @endif
          </div>
          @endif

          @if($q->type == 'multiple_choice')
          <div class="row g-3 mt-2">
            @php $labels = ['A', 'B', 'C', 'D']; @endphp
            @foreach($q->options as $optIndex => $option)
            <div class="col-md-6">
              <div class="p-3 border rounded-4 h-100 {{ $option->is_correct ? 'correct-answer text-success border-success' : 'bg-light text-muted' }}">
                <div class="d-flex gap-2 align-items-start h-100">
                  <strong class="{{ $option->is_correct ? 'text-success' : 'text-dark' }} fs-5">{{ $labels[$optIndex] ?? '-' }}.</strong>

                  <div class="flex-grow-1">
                    @if($option->option_text)
                    <div class="text-dynamic mb-2" dir="auto">
                      {{ $option->option_text }}
                    </div>
                    @endif

                    @if($option->image_file)
                    <img src="{{ asset('storage/' . $option->image_file) }}" alt="Gambar Opsi" class="img-fluid rounded border shadow-sm" style="max-height: 120px;">
                    @endif
                  </div>

                  @if($option->is_correct)
                  <i class="bi bi-check-circle-fill text-success fs-4"></i>
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
      <form action="{{ route('teacher.cbt.questions.destroy', $q->id) }}" method="POST" class="d-inline form-delete">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 btn-delete">
          <i class="bi bi-trash me-1"></i> Hapus
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Konfirmasi Hapus Soal
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const form = this.closest('.form-delete');
        Swal.fire({
          title: 'Hapus Soal Ini?'
          , text: "Soal yang sudah dihapus tidak dapat dikembalikan lagi."
          , icon: 'warning'
          , showCancelButton: true
          , confirmButtonColor: '#dc3545'
          , cancelButtonColor: '#6c757d'
          , confirmButtonText: 'Ya, Hapus Saja'
          , cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // 2. Flash Message
    @if(session('success'))
    Swal.fire({
      icon: 'success'
      , title: 'Berhasil'
      , text: "{{ session('success') }}"
      , timer: 3000
      , showConfirmButton: false
    });
    @endif
    @if(session('error'))
    Swal.fire({
      icon: 'error'
      , title: 'Gagal'
      , text: "{{ session('error') }}"
    });
    @endif

  });

</script>
@endpush
