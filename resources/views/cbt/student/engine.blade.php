<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian CBT | {{ $studentExam->exam->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .navbar-cbt { background-color: #0d6efd; color: white; }
        /* Teks Arab Dynamic */
        .text-dynamic { font-family: 'Segoe UI', Tahoma, 'Traditional Arabic', serif; font-size: 1.4rem; line-height: 1.8; }
        /* Grid Navigasi Soal */
        .nav-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
        .nav-btn { height: 45px; font-weight: bold; border-radius: 8px; }
        .nav-answered { background-color: #198754; color: white; border-color: #198754; }
        .nav-current { background-color: #0d6efd; color: white; border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.25); }
        .nav-empty { background-color: white; border: 1px solid #dee2e6; color: #495057; }
        
        /* Disable text selection untuk anti nyontek */
        .unselectable { user-select: none; -moz-user-select: none; -webkit-user-select: none; }
        
        .option-label { cursor: pointer; padding: 15px; border: 2px solid #e9ecef; border-radius: 12px; transition: all 0.2s; display: block; }
        .option-label:hover { border-color: #0d6efd; background-color: #f8f9fa; }
        .option-radio:checked + .option-label { border-color: #0d6efd; background-color: #e7f1ff; }
    </style>
</head>
<body class="unselectable" oncontextmenu="return false;">

    <nav class="navbar navbar-cbt sticky-top shadow-sm py-2">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-laptop fs-4 me-2"></i>
                <div class="fw-bold d-none d-md-block">{{ $studentExam->exam->name }}</div>
            </div>
            <div class="d-flex align-items-center bg-dark bg-opacity-25 px-4 py-2 rounded-pill">
                <i class="bi bi-stopwatch fs-5 me-2"></i>
                <span id="timerDisplay" class="fw-bold fs-5 font-monospace tracking-wide">00:00:00</span>
            </div>
            <div class="text-end">
                <small class="opacity-75 d-block" style="font-size: 11px;">Peserta:</small>
                <div class="fw-bold">{{ Auth::guard('cbt')->user()->student->name }}</div>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                        <h5 class="fw-bold mb-0 text-primary">SOAL NO. {{ $page }}</h5>
                        <span class="badge bg-secondary rounded-pill">Poin: {{ $currentAnswer->question->score_weight }}</span>
                    </div>
                    <div class="card-body p-4 pt-0">
                        
                        <div class="text-dynamic mb-4" dir="auto">
                            {!! nl2br(e($currentAnswer->question->question_text)) !!}
                        </div>

                        @if($currentAnswer->question->image_file)
                            <img src="{{ asset('storage/'.$currentAnswer->question->image_file) }}" class="img-fluid rounded mb-4 shadow-sm" style="max-height: 300px;">
                        @endif
                        @if($currentAnswer->question->audio_file)
                            <audio controls class="w-100 mb-4"><source src="{{ asset('storage/'.$currentAnswer->question->audio_file) }}" type="audio/mpeg"></audio>
                        @endif

                        <hr class="mb-4 text-muted">

                        @if($currentAnswer->question->type == 'multiple_choice')
                            <div class="row g-3">
                                @foreach($currentAnswer->question->options as $opt)
                                <div class="col-12">
                                    <input type="radio" name="option_choice" id="opt_{{ $opt->id }}" class="d-none option-radio" value="{{ $opt->id }}" 
                                        {{ $currentAnswer->cbt_option_id == $opt->id ? 'checked' : '' }}
                                        onchange="autosaveOption({{ $currentAnswer->id }}, {{ $opt->id }})">
                                    
                                    <label for="opt_{{ $opt->id }}" class="option-label w-100 m-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="fs-4 text-primary"><i class="bi bi-circle"></i></div>
                                            <div class="flex-grow-1 text-dynamic" dir="auto">
                                                {{ $opt->option_text }}
                                                @if($opt->image_file)
                                                    <img src="{{ asset('storage/'.$opt->image_file) }}" class="img-fluid rounded mt-2 d-block" style="max-height: 150px;">
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <textarea id="essayInput" class="form-control text-dynamic" rows="6" dir="auto" placeholder="Ketik jawaban Anda di sini..." onblur="autosaveEssay({{ $currentAnswer->id }})">{{ $currentAnswer->essay_answer }}</textarea>
                            <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle"></i> Jawaban essay akan otomatis tersimpan saat Anda mengklik di luar kotak (atau pindah soal).</small>
                        @endif

                    </div>
                    
                    <div class="card-footer bg-white py-3 border-top d-flex justify-content-between">
                        @if($page > 1)
                            <a href="?no={{ $page - 1 }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-arrow-left me-2"></i> Soal Sebelumnya</a>
                        @else
                            <div></div>
                        @endif

                        @if($page < $totalQuestions)
                            <a href="?no={{ $page + 1 }}" class="btn btn-primary rounded-pill px-5">Soal Berikutnya <i class="bi bi-arrow-right ms-2"></i></a>
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 80px;">
                    <div class="card-header bg-white py-3 text-center border-bottom-0">
                        <h6 class="fw-bold mb-0">NAVIGASI SOAL</h6>
                    </div>
                    <div class="card-body p-3 pt-0">
                        
                        <div class="nav-grid mb-4">
                            @foreach($studentExam->answers as $ans)
                                @php
                                    $isCurrent = ($ans->question_order == $page);
                                    $isAnswered = ($ans->cbt_option_id !== null || !empty($ans->essay_answer));
                                    
                                    $btnClass = 'nav-empty';
                                    if ($isCurrent) $btnClass = 'nav-current';
                                    elseif ($isAnswered) $btnClass = 'nav-answered';
                                @endphp
                                <a href="?no={{ $ans->question_order }}" class="btn {{ $btnClass }} nav-btn d-flex align-items-center justify-content-center" id="navbox_{{ $ans->question_order }}">
                                    {{ $ans->question_order }}
                                </a>
                            @endforeach
                        </div>

                        <div class="alert alert-light border small text-center mb-4 py-2" id="saveIndicator">
                            <i class="bi bi-cloud-check text-success me-1"></i> Jawaban tersimpan otomatis.
                        </div>

                        <form id="finishForm" action="{{ route('cbt.engine.finish', $studentExam->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmFinish()" class="btn btn-danger w-100 rounded-pill fw-bold py-2 shadow-sm">
                                <i class="bi bi-check2-all me-1"></i> SELESAI & KUMPULKAN
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // --- 1. COUNTDOWN TIMER SCRIPT ---
        let remainingSeconds = {{ $remainingSeconds }};
        const timerDisplay = document.getElementById('timerDisplay');

        function formatTime(sec) {
            let h = Math.floor(sec / 3600);
            let m = Math.floor((sec % 3600) / 60);
            let s = Math.floor(sec % 60);
            return [h, m, s].map(v => v < 10 ? "0" + v : v).join(":");
        }

        const countdown = setInterval(() => {
            if (remainingSeconds <= 0) {
                clearInterval(countdown);
                timerDisplay.innerText = "WAKTU HABIS";
                
                Swal.fire({
                    title: 'Waktu Habis!',
                    text: "Waktu ujian telah habis! Jawaban Anda akan otomatis dikumpulkan.",
                    icon: 'warning',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    document.getElementById('finishForm').submit();
                });
            } else {
                timerDisplay.innerText = formatTime(remainingSeconds);
                if(remainingSeconds < 300) { // 5 Menit Terakhir
                    timerDisplay.classList.add('text-warning');
                }
                remainingSeconds--;
            }
        }, 1000);


        // --- 2. AUTOSAVE AJAX SCRIPT ---
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const saveIndicator = document.getElementById('saveIndicator');

        function updateNavBoxToAnswered() {
            const navbox = document.getElementById('navbox_{{ $page }}');
            if(!navbox.classList.contains('nav-current')){
                navbox.classList.remove('nav-empty');
                navbox.classList.add('nav-answered');
            }
        }

        function showSavingStatus(isSaving) {
            if(isSaving) {
                saveIndicator.innerHTML = '<span class="spinner-border spinner-border-sm text-primary" role="status"></span> Menyimpan...';
            } else {
                saveIndicator.innerHTML = '<i class="bi bi-cloud-check text-success me-1"></i> Tersimpan!';
                setTimeout(() => { saveIndicator.innerHTML = '<i class="bi bi-cloud-check text-success me-1"></i> Jawaban tersimpan otomatis.'; }, 2000);
            }
        }

        // Simpan Pilihan Ganda
        function autosaveOption(answerId, optionId) {
            showSavingStatus(true);
            
            // Ubah UI Radio styling
            document.querySelectorAll('.option-label .bi').forEach(icon => {
                icon.classList.remove('bi-check-circle-fill');
                icon.classList.add('bi-circle');
            });
            const selectedIcon = document.querySelector(`label[for="opt_${optionId}"] .bi`);
            selectedIcon.classList.remove('bi-circle');
            selectedIcon.classList.add('bi-check-circle-fill');

            fetch(`/cbt/exam/autosave/${answerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ option_id: optionId })
            }).then(res => res.json()).then(data => {
                if(data.status === 'success') {
                    showSavingStatus(false);
                    updateNavBoxToAnswered();
                }
            });
        }

        // Simpan Essay (OnBlur)
        function autosaveEssay(answerId) {
            const text = document.getElementById('essayInput').value;
            showSavingStatus(true);
            
            fetch(`/cbt/exam/autosave/${answerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ essay_text: text })
            }).then(res => res.json()).then(data => {
                if(data.status === 'success') {
                    showSavingStatus(false);
                    if(text.trim() !== '') updateNavBoxToAnswered();
                }
            });
        }

        function confirmFinish() {
            Swal.fire({
                title: 'Selesai Ujian?',
                text: "Apakah Anda yakin ingin mengakhiri ujian? Jawaban tidak bisa diubah lagi.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('finishForm').submit();
                }
            });
        }
    </script>
</body>
</html>