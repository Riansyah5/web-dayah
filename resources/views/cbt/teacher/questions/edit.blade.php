@extends('layouts.app')
@section('title', 'Edit Soal CBT - Premium Edition')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
  :root {
    --glass-bg: rgba(255, 255, 255, 0.95);
    --premium-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    --accent-color: #4e73df;
    --danger-soft: #fff5f5;
  }

  body {
    background-color: #f8f9fc;
  }

  .card-premium {
    background: var(--glass-bg);
    border: none;
    border-radius: 20px;
    box-shadow: var(--premium-shadow);
  }

  /* ARABIC TEXT STYLING */
  .arabic-input {
    direction: rtl !important;
    text-align: right !important;
    font-family: 'Amiri', serif;
    font-size: 1.6rem !important;
    line-height: 2.2;
  }

  /* CKEditor Customization */
  .ck-editor__editable_inline {
    min-height: 250px;
    border-bottom-left-radius: 15px !important;
    border-bottom-right-radius: 15px !important;
  }

  /* PREVIEW BOXES */
  .media-preview-box {
    background: #f8f9fc;
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    padding: 12px;
    transition: all 0.3s ease;
  }

  .media-preview-box:hover {
    border-color: var(--accent-color);
  }

  /* OPTION CARDS */
  .option-item {
    transition: all 0.2s ease;
    border: 2px solid #f1f1f1;
    background: #ffffff;
  }

  .option-item.active-choice {
    border-color: var(--accent-color);
    background: #f8faff;
  }

  /* BUTTONS */
  .btn-premium {
    border-radius: 12px;
    padding: 10px 24px;
    font-weight: 600;
    transition: all 0.3s;
  }

  .btn-lang-toggle {
    cursor: pointer;
    transition: 0.3s;
    border: 1px solid #ddd;
    background: white;
  }

</style>
@endpush

@section('content')
<div class="container py-5">
  <div class="row mb-4 align-items-center">
    <div class="col">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">CBT</a></li>
          <li class="breadcrumb-item active">Edit Soal</li>
        </ol>
      </nav>
      <h3 class="fw-bold text-dark mb-0">Perbarui Pertanyaan</h3>
      <p class="text-muted small">Modul: <span class="fw-semibold text-primary text-uppercase">{{ $bank->subject_name }}</span></p>
    </div>
    <div class="col-auto">
      <a href="{{ route('teacher.cbt.banks.show', $bank->id) }}" class="btn btn-white bg-white btn-premium border shadow-sm text-muted">
        <i class="bi bi-arrow-left me-2"></i>Kembali
      </a>
    </div>
  </div>

  <form action="{{ route('teacher.cbt.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card card-premium mb-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <label class="form-label fw-bold h6 mb-0">Konten Pertanyaan</label>
              <button type="button" class="btn btn-sm btn-lang-toggle rounded-pill px-3" onclick="toggleRtl('q_text')">
                <i class="bi bi-translate me-1"></i> Mode Arab / Indo
              </button>
            </div>

            <textarea name="question_text" id="q_text" class="form-control">{{ $question->question_text }}</textarea>

            <div class="mt-4 pt-4 border-top">
              <h6 class="fw-bold mb-3 small text-muted text-uppercase">Lampiran Media Saat Ini</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="media-preview-box h-100">
                    <label class="form-label d-block small fw-bold mb-2">Gambar Soal</label>
                    @if($question->image_file)
                    <div class="position-relative mb-2">
                      <img src="{{ asset('storage/' . $question->image_file) }}" class="img-fluid rounded border shadow-sm" style="max-height: 120px;">
                      <div class="mt-2">
                        <div class="form-check form-switch custom-switch-danger">
                          <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="rmImg">
                          <label class="form-check-label text-danger small fw-bold" for="rmImg">Hapus Gambar</label>
                        </div>
                      </div>
                    </div>
                    @else
                    <div class="text-center py-3 border border-dashed rounded text-muted small">Tidak ada gambar</div>
                    @endif
                    <input type="file" name="image_file" class="form-control form-control-sm mt-2 border-0 bg-white shadow-sm" accept="image/*">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media-preview-box h-100">
                    <label class="form-label d-block small fw-bold mb-2">Audio (Listening)</label>
                    @if($question->audio_file)
                    <div class="mb-2 bg-white p-2 rounded shadow-sm">
                      <audio controls class="w-100" style="height: 35px;">
                        <source src="{{ asset('storage/' . $question->audio_file) }}" type="audio/mpeg">
                      </audio>
                      <div class="mt-2">
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" name="remove_audio" value="1" id="rmAud">
                          <label class="form-check-label text-danger small fw-bold" for="rmAud">Hapus Audio</label>
                        </div>
                      </div>
                    </div>
                    @else
                    <div class="text-center py-3 border border-dashed rounded text-muted small">Tidak ada audio</div>
                    @endif
                    <input type="file" name="audio_file" class="form-control form-control-sm mt-2 border-0 bg-white shadow-sm" accept="audio/*">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="multipleChoiceSection" style="display: {{ $question->type == 'essay' ? 'none' : 'block' }};">
          <div class="d-flex align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark me-2">Pilihan Jawaban</h6>
            <hr class="flex-grow-1 opacity-10">
          </div>

          @php
          $labels = ['A', 'B', 'C', 'D'];
          $options = $question->options;
          @endphp

          @foreach($labels as $index => $label)
          @php $opt = $options->get($index); @endphp
          <div class="card card-premium option-item mb-3 {{ ($opt && $opt->is_correct) ? 'active-choice' : '' }}" id="card_opt_{{ $index }}">
            <div class="card-body p-3">
              <div class="d-flex align-items-start gap-3">
                <div class="pt-2">
                  <div class="form-check">
                    <input class="form-check-input fs-4" type="radio" name="correct_option" id="radio_{{ $index }}" value="{{ $index }}" {{ ($opt && $opt->is_correct) ? 'checked' : '' }} onchange="highlightChoice({{ $index }})">
                  </div>
                </div>
                <div class="flex-grow-1">
                  <div class="input-group mb-2 shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-0 fw-bold">{{ $label }}.</span>
                    <input type="text" name="options[{{ $index }}]" id="opt_{{ $index }}" class="form-control border-0 bg-white {{ ($opt && preg_match('/\p{Arabic}/u', $opt->option_text)) ? 'arabic-input' : '' }}" placeholder="Teks jawaban..." value="{{ $opt ? $opt->option_text : '' }}">
                    <button type="button" class="btn btn-white border-0 text-muted" onclick="toggleRtl('opt_{{ $index }}')">
                      <i class="bi bi-translate"></i>
                    </button>
                  </div>

                  @if($opt && $opt->image_file)
                  <div class="d-flex align-items-center gap-3 bg-light p-2 rounded-3 w-auto d-inline-flex border">
                    <img src="{{ asset('storage/' . $opt->image_file) }}" class="rounded" style="max-height: 40px;">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remove_option_image[{{ $index }}]" value="1" id="rmOptImg{{ $index }}">
                      <label class="form-check-label text-danger small" for="rmOptImg{{ $index }}">Hapus Gambar</label>
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sticky-top" style="top: 20px;">

          <div class="card card-premium border-0">
            <div class="card-body p-4">
              <h6 class="fw-bold mb-3">Konfigurasi Soal</h6>
              <div class="mb-3">
                <label class="form-label small fw-bold">Jenis Soal</label>
                <select name="type" id="questionType" class="form-select bg-light border-0" onchange="toggleQuestionType()">
                  <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                  <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>Essay / Uraian</option>
                </select>
              </div>
              <div class="mb-0">
                <label class="form-label small fw-bold">Bobot Nilai</label>
                <div class="input-group">
                  <span class="input-group-text border-0 bg-light"><i class="bi bi-award text-primary"></i></span>
                  <input type="number" name="score_weight" class="form-control border-0 bg-light text-center" value="{{ $question->score_weight }}" min="1">
                </div>
              </div>
            </div>
          </div>

          <div class="card card-premium mb-4 shadow-lg border-0" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white;">
            <div class="card-body p-4 text-center">
              <i class="bi bi-check2-circle fs-1 mb-3"></i>
              <h5 class="fw-bold">Perbarui Soal</h5>
              <p class="small opacity-75">Simpan perubahan Anda untuk memperbarui data soal pada sistem CBT.</p>
              <button type="submit" class="btn btn-light btn-premium w-100 shadow-sm mt-2">
                <i class="bi bi-save-fill me-2"></i>Update Sekarang
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script>
  let questionEditor;

  ClassicEditor
    .create(document.querySelector('#q_text'), {
      toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
    })
    .then(editor => {
      questionEditor = editor;

      // Auto RTL check
      const initialContent = editor.getData();
      if (/[\u0600-\u06FF]/.test(initialContent)) {
        toggleRtl('q_text');
      }
    });

  function highlightChoice(index) {
    document.querySelectorAll('.option-item').forEach(card => card.classList.remove('active-choice'));
    document.getElementById(`card_opt_${index}`).classList.add('active-choice');
  }

  function toggleQuestionType() {
    const type = document.getElementById('questionType').value;
    const mcqSection = document.getElementById('multipleChoiceSection');
    mcqSection.style.display = (type === 'essay') ? 'none' : 'block';
  }

  function toggleRtl(elementId) {
    if (elementId === 'q_text' && questionEditor) {
      const editingView = questionEditor.editing.view;
      const root = editingView.document.getRoot();
      editingView.change(writer => {
        const isArabic = root.hasClass('arabic-input');
        if (isArabic) {
          writer.removeClass('arabic-input', root);
          writer.setAttribute('dir', 'ltr', root);
        } else {
          writer.addClass('arabic-input', root);
          writer.setAttribute('dir', 'rtl', root);
        }
      });
      return;
    }

    const el = document.getElementById(elementId);
    el.classList.toggle('arabic-input');
  }

  document.querySelector('form').addEventListener('submit', () => {
    if (questionEditor) questionEditor.updateSourceElement();
  });

</script>
@endpush
