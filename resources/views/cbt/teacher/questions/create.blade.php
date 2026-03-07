@extends('layouts.app')
@section('title', 'Tambah Soal CBT')
@push('link')
@endpush
@push('styles')
<style>
  /* CSS KHUSUS UNTUK TEKS ARAB */
  .arabic-input {
    direction: rtl;
    text-align: right;
    font-family: 'Traditional Arabic', 'Amiri', 'Scheherazade', serif;
    font-size: 1.8rem;
    /* Font dibesarkan agar harakat jelas */
    line-height: 2;
  }

  /* Tombol Toggle RTL/LTR */
  .btn-lang-toggle {
    cursor: pointer;
    font-weight: bold;
  }

</style>
@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Tambah Soal Baru</h4>
      <small class="text-muted">Bank Soal: <span class="text-primary">{{ $bank->subject_name }}</span></small>
    </div>
    <a href="{{ route('teacher.cbt.banks.show', $bank->id) }}" class="btn btn-light rounded-pill border shadow-sm">
      <i class="bi bi-arrow-left me-1"></i> Batal
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
      <form action="{{ route('teacher.cbt.questions.store', $bank->id) }}" method="POST">
        @csrf

        <div class="row mb-4 bg-light p-3 rounded">
          <div class="col-md-6">
            <label class="form-label fw-bold small">Jenis Soal</label>
            <select name="type" id="questionType" class="form-select" onchange="toggleQuestionType()">
              <option value="multiple_choice">Pilihan Ganda</option>
              <option value="essay">Essay / Uraian</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small">Bobot Nilai</label>
            <input type="number" name="score_weight" class="form-control" value="1" min="1" required>
          </div>
        </div>

        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-end mb-2">
            <label class="form-label fw-bold mb-0">Teks Pertanyaan</label>
            <span class="badge bg-secondary btn-lang-toggle" onclick="toggleRtl('q_text')">
              <i class="bi bi-translate me-1"></i> Arab / Indo
            </span>
          </div>
          <textarea name="question_text" id="q_text" class="form-control arabic-input" rows="4" placeholder="Ketik soal di sini..." required></textarea>
        </div>

        <div class="row g-3 bg-light p-3 rounded border border-dashed">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted"><i class="bi bi-image me-1"></i> Lampirkan Gambar (Opsional)</label>
              <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*">
              <div class="form-text" style="font-size: 11px;">Maks 2MB (JPG, PNG). Cocok untuk grafik/tabel.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted"><i class="bi bi-volume-up me-1"></i> Lampirkan Audio (Opsional)</label>
              <input type="file" name="audio_file" class="form-control form-control-sm" accept="audio/*">
              <div class="form-text" style="font-size: 11px;">Maks 5MB (MP3). Cocok untuk soal Istima' (Listening).</div>
            </div>
          </div>

        <div id="multipleChoiceSection">
          <hr class="my-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-ui-radios me-2"></i>Pilihan Jawaban (A, B, C, D)</h6>
          <div class="alert alert-info py-2 small">
            Pilih *Radio Button* di sebelah kiri untuk menentukan **Kunci Jawaban**.
          </div>

          @php $labels = ['A', 'B', 'C', 'D']; @endphp
          @foreach($labels as $index => $label)
          <div class="mb-3 d-flex align-items-start gap-3">
            <div class="form-check pt-2">
              <input class="form-check-input fs-4" type="radio" name="correct_option" value="{{ $index }}" {{ $index == 0 ? 'checked' : '' }} required>
            </div>

            <div class="flex-grow-1">
              <div class="input-group">
                <span class="input-group-text fw-bold">{{ $label }}</span>
                <input type="text" name="options[{{ $index }}]" id="opt_{{ $index }}" class="form-control arabic-input" placeholder="Jawaban {{ $label }}">
                <button type="button" class="btn btn-outline-secondary" onclick="toggleRtl('opt_{{ $index }}')" title="Ubah Arab/Indo">
                  <i class="bi bi-translate"></i>
                </button>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="d-grid mt-5">
          <button type="submit" class="btn btn-primary rounded-pill btn-lg shadow">
            <i class="bi bi-save me-2"></i> Simpan Soal
          </button>
        </div>

      </form>

      {{-- <form action="{{ route('teacher.cbt.questions.store', $bank->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-end mb-2">
            <label class="form-label fw-bold mb-0">Teks Pertanyaan</label>
            <span class="badge bg-secondary btn-lang-toggle" onclick="toggleRtl('q_text')">
              <i class="bi bi-translate me-1"></i> Arab / Indo
            </span>
          </div>
          <textarea name="question_text" id="q_text" class="form-control arabic-input mb-3" rows="4" placeholder="Ketik soal di sini..." required></textarea>

          <div class="row g-3 bg-light p-3 rounded border border-dashed">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted"><i class="bi bi-image me-1"></i> Lampirkan Gambar (Opsional)</label>
              <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*">
              <div class="form-text" style="font-size: 11px;">Maks 2MB (JPG, PNG). Cocok untuk grafik/tabel.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted"><i class="bi bi-volume-up me-1"></i> Lampirkan Audio (Opsional)</label>
              <input type="file" name="audio_file" class="form-control form-control-sm" accept="audio/*">
              <div class="form-text" style="font-size: 11px;">Maks 5MB (MP3). Cocok untuk soal Istima' (Listening).</div>
            </div>
          </div>
        </div>

        <div id="multipleChoiceSection">
          <hr class="my-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-ui-radios me-2"></i>Pilihan Jawaban (A, B, C, D)</h6>
          <div class="alert alert-info py-2 small">
            Pilih *Radio Button* di sebelah kiri untuk menentukan **Kunci Jawaban**. Teks bisa dikosongkan jika jawaban hanya berupa gambar.
          </div>

          @php $labels = ['A', 'B', 'C', 'D']; @endphp
          @foreach($labels as $index => $label)
          <div class="mb-4 d-flex align-items-start gap-3 p-3 border rounded-4 bg-white shadow-sm">
            <div class="form-check pt-2">
              <input class="form-check-input fs-4" type="radio" name="correct_option" value="{{ $index }}" {{ $index == 0 ? 'checked' : '' }} required>
            </div>

            <div class="flex-grow-1">
              <div class="input-group mb-2">
                <span class="input-group-text fw-bold">{{ $label }}</span>
                <input type="text" name="options[{{ $index }}]" id="opt_{{ $index }}" class="form-control arabic-input" placeholder="Teks Jawaban {{ $label }}">
                <button type="button" class="btn btn-outline-secondary" onclick="toggleRtl('opt_{{ $index }}')" title="Ubah Arab/Indo">
                  <i class="bi bi-translate"></i>
                </button>
              </div>

              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-image text-muted"></i>
                <input type="file" name="option_images[{{ $index }}]" class="form-control form-control-sm w-50" accept="image/*">
                <small class="text-muted" style="font-size: 11px;">*(Opsional) Jika jawaban berupa gambar.</small>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="d-grid mt-5">
          <button type="submit" class="btn btn-primary rounded-pill btn-lg shadow">
            <i class="bi bi-save me-2"></i> Simpan Soal
          </button>
        </div>
      </form> --}}

    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  // Fungsi untuk menyembunyikan opsi A,B,C,D jika soal Essay
  function toggleQuestionType() {
    const type = document.getElementById('questionType').value;
    const mcqSection = document.getElementById('multipleChoiceSection');

    if (type === 'essay') {
      mcqSection.style.display = 'none';
    } else {
      mcqSection.style.display = 'block';
    }
  }

  // Fungsi canggih untuk toggle input teks biasa ke teks Arab (RTL)
  function toggleRtl(elementId) {
    const el = document.getElementById(elementId);
    if (el.classList.contains('arabic-input')) {
      // Ubah ke Latin (Kiri ke Kanan)
      el.classList.remove('arabic-input');
      el.style.direction = 'ltr';
      el.style.textAlign = 'left';
      el.style.fontSize = '1rem';
      el.style.fontFamily = 'inherit';
    } else {
      // Ubah ke Arab (Kanan ke Kiri)
      el.classList.add('arabic-input');
      el.style.direction = 'rtl';
      el.style.textAlign = 'right';
      el.style.fontSize = '1.8rem';
      el.style.fontFamily = "'Traditional Arabic', 'Amiri', 'Scheherazade', serif";
    }
  }

</script>
@endpush
