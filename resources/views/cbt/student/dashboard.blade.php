<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ujian | CBT Pesantren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-cbt { background-color: #0d6efd; color: white; }
        .exam-card { transition: transform 0.2s, box-shadow 0.2s; }
        .exam-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
        .token-input { text-transform: uppercase; font-size: 1.25rem; letter-spacing: 2px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-cbt shadow-sm py-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-laptop fs-3 me-2"></i>
                <div>
                    <h5 class="fw-bold mb-0">PORTAL CBT SANTRI</h5>
                    <div style="font-size: 12px;" class="opacity-75">Sistem Ujian Berbasis Komputer</div>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-bold fs-6">{{ Auth::guard('cbt')->user()->student->name }}</div>
                    <div style="font-size: 11px;" class="opacity-75"><i class="bi bi-person-badge"></i> {{ Auth::guard('cbt')->user()->username }}</div>
                </div>
                <form action="{{ route('cbt.logout') }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmLogout(this.closest('form'))" class="btn btn-danger btn-sm rounded-pill fw-bold px-3">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold"><i class="bi bi-card-checklist text-primary me-2"></i>Jadwal Ujian Hari Ini</h4>
                <p class="text-muted">Pilih ujian yang tersedia dan masukkan token yang diberikan oleh pengawas.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($availableExams as $exam)
                @php
                    // Cek apakah santri sudah pernah klik mulai / sudah selesai di ujian ini
                    $myExamData = $myExams->get($exam->id);
                    $status = $myExamData ? $myExamData->status : 'not_started';
                @endphp

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 exam-card">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-start">
                            <h5 class="fw-bold text-primary mb-1">{{ $exam->name }}</h5>
                            
                            @if($status == 'finished')
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check2-all me-1"></i> Selesai</span>
                            @elseif($status == 'working')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-pencil-square me-1"></i> Sedang Dikerjakan</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2">Tersedia</span>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="row text-muted small mb-4 mt-2">
                                <div class="col-6 mb-2">
                                    <i class="bi bi-book me-1"></i> {{ $exam->questionBank->subject_name }}
                                </div>
                                <div class="col-6 mb-2">
                                    <i class="bi bi-hourglass-split me-1"></i> {{ $exam->duration }} Menit
                                </div>
                                <div class="col-6">
                                    <i class="bi bi-file-text me-1"></i> {{ $exam->questionBank->questions->count() }} Soal
                                </div>
                                <div class="col-6">
                                    <i class="bi bi-clock-history me-1"></i> Tutup: {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                </div>
                            </div>

                            <hr class="text-muted opacity-25">

                            @if($status == 'finished')
                                <div class="text-center py-2">
                                    <p class="text-success fw-bold mb-1">Anda telah menyelesaikan ujian ini.</p>
                                    @if($exam->show_result)
                                        <div class="fs-1 fw-bold text-dark">{{ round($myExamData->score) }}</div>
                                        <small class="text-muted">Nilai Akhir (Pilihan Ganda)</small>
                                    @else
                                        <small class="text-muted">Nilai akan diumumkan oleh Ustadz/Ustadzah.</small>
                                    @endif
                                </div>
                            
                            @elseif($status == 'working')
                                <div class="text-center py-2">
                                    <p class="text-muted small mb-3">Sistem mendeteksi Anda belum menyelesaikan ujian ini. Silakan lanjutkan pengerjaan.</p>
                                    <a href="{{ route('cbt.engine.show', $myExamData->id) }}" class="btn btn-warning w-100 rounded-pill fw-bold text-dark shadow-sm">
                                        LANJUTKAN UJIAN <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            
                            @else
                                <form action="{{ route('cbt.engine.start', $exam->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted text-center d-block">Masukkan Token Ujian</label>
                                        <input type="text" name="token" class="form-control form-control-lg token-input" placeholder="X X X X X" maxlength="10" required autocomplete="off">
                                    </div>
                                    <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm" onclick="confirmStart(this.closest('form'))">
                                        MULAI UJIAN <i class="bi bi-play-circle ms-1"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                        <h5 class="fw-bold text-muted">Belum Ada Jadwal Ujian</h5>
                        <p class="text-muted mb-0">Saat ini tidak ada jadwal ujian yang aktif untuk kelas Anda.<br>Silakan tunggu arahan dari pengawas ruangan.</p>
                        <button class="btn btn-outline-primary rounded-pill mt-4 mx-auto" style="width: 200px;" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Muat Ulang
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.token-input').forEach(function(input) {
            input.addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase();
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
            });
        @endif

        function confirmLogout(form) {
            Swal.fire({
                title: 'Keluar Aplikasi?',
                text: "Sesi ujian Anda akan berakhir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }

        function confirmStart(form) {
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            Swal.fire({
                title: 'Mulai Ujian?',
                text: "Waktu akan mulai dihitung mundur. Pastikan Anda siap!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Mulai Sekarang!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    </script>
</body>
</html>