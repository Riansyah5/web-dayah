@extends('layouts.app')
@section('title', 'Edit Soal CBT')

@push('styles')
<style>
  /* CSS KHUSUS UNTUK TEKS ARAB */
  .arabic-input {
    direction: rtl;
    text-align: right;
    font-family: 'Traditional Arabic', 'Amiri', 'Scheherazade', serif;
    font-size: 1.8rem;
    line-height: 2;
  }

  /* Tombol Toggle RTL/LTR */
  .btn-lang-toggle {
    cursor: pointer;
    font-weight: bold;
  }

  /* CKEditor Height & Custom Style */
  .ck-editor__editable_inline {
    min-height: 200px;
  }

  /* Memastikan konten di dalam CKEditor mengikuti style arabic jika class ditambahkan */
  .ck-content.arabic-input {
    direction: rtl !important;
    text-align: right !important;
    font-size: 1.8rem !important;
  }
</style>
@endpush

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Edit Soal</h4>
      <small class="text-muted">Bank Soal: <span class="text-primary">{{ $bank->subject_name }}</span></small>
    </div>
    <a href="{{ route('teacher.cbt.banks.show', $bank->id) }}" class="btn btn-light rounded-pill border shadow-sm">
      <i class="bi bi-arrow-left me-1"></i> Batal
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
      <form action="{{ route('teacher.cbt.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-4 bg-light p-3 rounded">
          <div class="col-md-6">
            <label class="form-label fw-bold small">Jenis Soal</label>
            <select name="type" id="questionType" class="form-select" onchange="toggleQuestionType()">
              <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
              <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>Essay / Uraian</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small">Bobot Nilai</label>
            <input type="number" name="score_weight" class="form-control" value="{{ $question->score_weight }}" min="1" required>
          </div>
        </div>

        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-end mb-2">
            <label class="form-label fw-bold mb-0">Teks Pertanyaan</label>
            <span class="badge bg-secondary btn-lang-toggle" onclick="toggleRtl('q_text')">
              <i class="bi bi-translate me-1"></i> Arab / Indo
            </span>
          </div>
          <textarea name="question_text" id="q_text" class="form-control mb-3" rows="4">{{ $question->question_text }}</textarea>

          <div class="row g-3 bg-light p-3 rounded border border-dashed">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted"><i class="bi bi-image me-1"></i> Gambar Soal</label>
              @if($question->image_file)
              <div class="mb-2 d-flex align-items-center gap-2">
                <img src="{{ asset('storage/' . $question->image_file) }}" class="rounded border" style="max-height: 50px;">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="rmImg">
                  <label class="form-check-label text-danger small" for="rmImg">Hapus gambar ini</label>
                </div>
              </div>
              @endif
              <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*">
              <div class="form-text" style="font-size: 11px;">Upload baru untuk mengganti gambar lama.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted"><i class="bi bi-volume-up me-1"></i> Audio Soal</label>
              @if($question->audio_file)
              <div class="mb-2 d-flex align-items-center gap-2">
                <audio controls style="height: 30px; width: 150px;">
                  <source src="{{ asset('storage/' . $question->audio_file) }}" type="audio/mpeg">
                </audio>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="remove_audio" value="1" id="rmAud">
                  <label class="form-check-label text-danger small" for="rmAud">Hapus audio</label>
                </div>
              </div>
              @endif
              <input type="file" name="audio_file" class="form-control form-control-sm" accept="audio/*">
              <div class="form-text" style="font-size: 11px;">Upload baru untuk mengganti audio lama.</div>
            </div>
          </div>
        </div>

        <div id="multipleChoiceSection" style="display: {{ $question->type == 'multiple_choice' ? 'block' : 'none' }};">
          <hr class="my-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-ui-radios me-2"></i>Pilihan Jawaban (A, B, C, D)</h6>

          @php
            $labels = ['A', 'B', 'C', 'D'];
            $options = $question->options;
          @endphp

          @foreach($labels as $index => $label)
            @php
              $opt = $options->get($index);
            @endphp
            <div class="mb-4 d-flex align-items-start gap-3 p-3 border rounded-4 bg-white shadow-sm">
              <div class="form-check pt-2">
                <input class="form-check-input fs-4" type="radio" name="correct_option" value="{{ $index }}" 
                {{ ($opt && $opt->is_correct) || ($index == 0 && !$opt) ? 'checked' : '' }} required>
              </div>

              <div class="flex-grow-1">
                <div class="input-group mb-2">
                  <span class="input-group-text fw-bold">{{ $label }}</span>
                  <input type="text" name="options[{{ $index }}]" id="opt_{{ $index }}" 
                         class="form-control {{ ($opt && preg_match('/\p{Arabic}/u', $opt->option_text)) ? 'arabic-input' : '' }}" 
                         placeholder="Teks Jawaban {{ $label }}" value="{{ $opt ? $opt->option_text : '' }}">
                  <button type="button" class="btn btn-outline-secondary" onclick="toggleRtl('opt_{{ $index }}')">
                    <i class="bi bi-translate"></i>
                  </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                  @if($opt && $opt->image_file)
                    <img src="{{ asset('storage/' . $opt->image_file) }}" class="rounded border ms-2" style="max-height: 30px;">
                    <div class="form-check ms-2">
                      <input class="form-check-input" type="checkbox" name="remove_option_image[{{ $index }}]" value="1" id="rmOptImg{{ $index }}">
                      <label class="form-check-label text-danger small" for="rmOptImg{{ $index }}">Hapus Gambar</label>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="d-grid mt-5">
          <button type="submit" class="btn btn-primary rounded-pill btn-lg shadow">
            <i class="bi bi-save me-2"></i> Update Perubahan Soal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script>
  let questionEditor;

  // Inisialisasi CKEditor 5
  ClassicEditor
    .create(document.querySelector('#q_text'))
    .then(editor => {
      questionEditor = editor;
      
      // Jika teks awal mengandung karakter Arab, set ke RTL otomatis saat load
      const initialContent = editor.getData();
      if (/[\u0600-\u06FF]/.test(initialContent)) {
        const editingView = editor.editing.view;
        const root = editingView.document.getRoot();
        editingView.change(writer => {
          writer.addClass('arabic-input', root);
          writer.setAttribute('dir', 'rtl', root);
        });
      }
    })
    .catch(error => {
      console.error(error);
    });

  // Sinkronisasi data CKEditor ke textarea sebelum submit form
  document.querySelector('form').addEventListener('submit', function() {
    if (questionEditor) {
      questionEditor.updateSourceElement();
    }
  });

  // Toggle tampilan Pilihan Ganda vs Essay
  function toggleQuestionType() {
    const type = document.getElementById('questionType').value;
    document.getElementById('multipleChoiceSection').style.display = type === 'essay' ? 'none' : 'block';
  }

  // Fungsi Toggle RTL/Arab untuk CKEditor dan Input Biasa
  function toggleRtl(elementId) {
    // Penanganan khusus jika element adalah textarea yang sudah jadi CKEditor
    if (elementId === 'q_text' && questionEditor) {
      const editingView = questionEditor.editing.view;
      const root = editingView.document.getRoot();

      editingView.change(writer => {
        if (root.hasClass('arabic-input')) {
          writer.removeClass('arabic-input', root);
          writer.setAttribute('dir', 'ltr', root);
        } else {
          writer.addClass('arabic-input', root);
          writer.setAttribute('dir', 'rtl', root);
        }
      });
      return;
    }

    // Penanganan untuk input teks biasa (Pilihan A, B, C, D)
    const el = document.getElementById(elementId);
    if (el.classList.contains('arabic-input')) {
      el.classList.remove('arabic-input');
      el.style.direction = 'ltr';
      el.style.textAlign = 'left';
      el.style.fontSize = '1rem';
      el.style.fontFamily = 'inherit';
    } else {
      el.classList.add('arabic-input');
      el.style.direction = 'rtl';
      el.style.textAlign = 'right';
      el.style.fontSize = '1.8rem';
      el.style.fontFamily = "'Traditional Arabic', 'Amiri', 'Scheherazade', serif";
    }
  }
</script>
@endpush