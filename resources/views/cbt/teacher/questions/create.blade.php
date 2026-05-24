@extends('layouts.app')
@section('title', 'Tambah Soal CBT - Premium Interface')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
  :root {
    --glass-bg: rgba(255, 255, 255, 0.95);
    --premium-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    --accent-color: #4e73df;
  }

  body {
    background-color: #f8f9fc;
  }

  .card-premium {
    background: var(--glass-bg);
    border: none;
    border-radius: 20px;
    box-shadow: var(--premium-shadow);
    backdrop-filter: blur(10px);
  }

  /* ARABIC TEXT STYLING */
  .arabic-input {
    direction: rtl !important;
    text-align: right !important;
    font-family: 'Amiri', serif;
    font-size: 1.6rem !important;
    line-height: 2.2;
  }

  /* CUSTOM FORM CONTROLS */
  .form-control,
  .form-select {
    border-radius: 12px;
    border: 1px solid #e3e6f0;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
  }

  .form-control:focus {
    box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    border-color: var(--accent-color);
  }

  /* OPTION CARDS */
  .option-item {
    transition: transform 0.2s ease, border 0.2s ease;
    border: 2px solid transparent;
    background: #ffffff;
  }

  .option-item:hover {
    transform: translateY(-2px);
    border-color: #e3e6f0;
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
    letter-spacing: 0.5px;
    transition: all 0.3s;
  }

  .btn-lang-toggle {
    cursor: pointer;
    transition: 0.3s;
    border: 1px solid #ddd;
    background: white;
    color: #666;
  }

  .btn-lang-toggle:hover {
    background: #f1f1f1;
    color: var(--accent-color);
  }

  /* CKEditor Customization */
  .ck-editor__editable_inline {
    min-height: 250px;
    border-bottom-left-radius: 15px !important;
    border-bottom-right-radius: 15px !important;
  }

  .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
    border-color: #e3e6f0;
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
          <li class="breadcrumb-item active">Tambah Soal</li>
        </ol>
      </nav>
      <h3 class="fw-bold text-dark mb-0">Buat Pertanyaan Baru</h3>
      <p class="text-muted small"><i class="bi bi-journal-text me-1"></i> Mata Pelajaran: <span class="fw-semibold text-primary text-uppercase">{{ $bank->subject_name }}</span></p>
    </div>
    <div class="col-auto">
      <a href="{{ route('teacher.cbt.banks.show', $bank->id) }}" class="btn btn-white bg-white btn-premium border shadow-sm text-muted">
        <i class="bi bi-x-lg me-2"></i>Batalkan
      </a>
    </div>
  </div>

  <form action="{{ route('teacher.cbt.questions.store', $bank->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card card-premium mb-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <label class="form-label fw-bold h6 mb-0 text-dark">Konten Pertanyaan</label>
              <button type="button" class="btn btn-sm btn-lang-toggle rounded-pill px-3" onclick="toggleRtl('q_text')">
                <i class="bi bi-translate me-1"></i> Mode Arab / Indo
              </button>
            </div>

            <textarea name="question_text" id="q_text" class="form-control" placeholder="Tuliskan pertanyaan anda di sini..."></textarea>

            <div class="mt-4 pt-3 border-top">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted">GAMBAR PENDUKUNG</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-image"></i></span>
                    <input type="file" name="image_file" class="form-control border-start-0" accept="image/*">
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted">AUDIO (LISTENING)</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-headphones"></i></span>
                    <input type="file" name="audio_file" class="form-control border-start-0" accept="audio/*">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="multipleChoiceSection">
          <div class="d-flex align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark me-2">Pilihan Jawaban</h6>
            <hr class="flex-grow-1 opacity-10">
          </div>

          @php $labels = ['A', 'B', 'C', 'D']; @endphp
          @foreach($labels as $index => $label)
          <div class="card card-premium option-item mb-3" id="card_opt_{{ $index }}">
            <div class="card-body p-3">
              <div class="d-flex align-items-start gap-3">
                <div class="pt-2">
                  <div class="form-check custom-radio">
                    <input class="form-check-input fs-4" type="radio" name="correct_option" id="radio_{{ $index }}" value="{{ $index }}" {{ $index == 0 ? 'checked' : '' }} onchange="highlightChoice({{ $index }})">
                  </div>
                </div>
                <div class="flex-grow-1">
                  <div class="input-group mb-2">
                    <span class="input-group-text bg-white fw-bold border-0">{{ $label }}.</span>
                    <input type="text" name="options[{{ $index }}]" id="opt_{{ $index }}" class="form-control border-0 bg-light rounded-3" placeholder="Ketik teks jawaban...">
                    <button type="button" class="btn btn-link text-muted" onclick="toggleRtl('opt_{{ $index }}')">
                      <i class="bi bi-translate"></i>
                    </button>
                  </div>
                  <div class="d-flex align-items-center">
                    <input type="file" name="option_images[{{ $index }}]" class="form-control form-control-sm border-0 bg-transparent w-auto" style="font-size: 11px;">
                    <span class="text-muted ms-2" style="font-size: 11px;">*Opsional Gambar Jawaban</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sticky-top" style="top: 20px;">

          <div class="card card-premium">
            <div class="card-body p-4">
              <h6 class="fw-bold mb-3 text-dark">Pengaturan Soal</h6>

              <div class="mb-3">
                <label class="form-label small fw-bold">Tipe Pertanyaan</label>
                <select name="type" id="questionType" class="form-select bg-light" onchange="toggleQuestionType()">
                  <option value="multiple_choice" selected>Pilihan Ganda</option>
                  <option value="essay">Essay / Uraian</option>
                </select>
              </div>

              <div class="mb-0">
                <label class="form-label small fw-bold">Bobot Skor (Point)</label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="bi bi-star-fill text-warning"></i></span>
                  <input type="number" name="score_weight" class="form-control" value="10" min="1">
                </div>
              </div>
            </div>
          </div>

          <div class="card card-premium mb-4 text-white" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border-radius: 20px;">
            <div class="card-body p-4 text-center">
              <i class="bi bi-rocket-takeoff fs-1 mb-3"></i>
              <h5 class="fw-bold">Siap Publikasikan?</h5>
              <p class="small opacity-75">Pastikan kunci jawaban sudah dipilih dengan benar sebelum menyimpan.</p>
              <button type="submit" class="btn btn-light btn-premium w-100 mt-2 shadow-sm">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan Pertanyaan
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
<!-- Menggunakan CKEditor 5 Super Build -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/super-build/ckeditor.js"></script>
<script>
  let questionEditor;

  // 1. Definisikan nama plugin yang dibutuhkan
  const requiredPlugins = [
    'Essentials', 'Paragraph', 'Heading', 'Bold', 'Italic', 
    'Link', 'List', 'BlockQuote', 'Table', 'Undo', 'MathType'
  ];

  // 2. Ambil class/constructor aslinya dari Super Build
  const loadedPlugins = requiredPlugins.map(pluginName => {
    return CKEDITOR.ClassicEditor.builtinPlugins.find(plugin => plugin.pluginName === pluginName);
  }).filter(plugin => plugin !== undefined);

  // 3. Inisialisasi CKEditor
  CKEDITOR.ClassicEditor
    .create(document.querySelector('#q_text'), {
      plugins: loadedPlugins, // Gunakan array class, BUKAN array string
      toolbar: {
        items: [
          'heading', '|', 
          'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
          'MathType', 'ChemType', '|', 
          'blockQuote', 'insertTable', 'undo', 'redo'
        ],
        shouldNotGroupWhenFull: true
      }
    })
    .then(editor => {
      questionEditor = editor;
    })
    .catch(error => {
      console.error('Error saat memuat CKEditor:', error);
    });

  function highlightChoice(index) {
    document.querySelectorAll('.option-item').forEach(card => card.classList.remove('active-choice'));
    document.getElementById(`card_opt_${index}`).classList.add('active-choice');
  }

  function toggleQuestionType() {
    const type = document.getElementById('questionType').value;
    const mcqSection = document.getElementById('multipleChoiceSection');
    mcqSection.style.opacity = '0';
    setTimeout(() => {
      mcqSection.style.display = (type === 'essay') ? 'none' : 'block';
      mcqSection.style.opacity = '1';
    }, 200);
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

  highlightChoice(0);
</script>
@endpush
