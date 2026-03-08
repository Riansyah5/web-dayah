<!DOCTYPE html>
<html lang="id" data-theme="sunset">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ujian | CBT Pesantren</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <script>
        const savedTheme = localStorage.getItem('cbt_theme') || 'sunset';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>

    <style>
        /* =========================================
           SISTEM VARIABEL TEMA DINAMIS
           ========================================= */
        
        /* 1. SUNSET ENERGY (Default) */
        :root, [data-theme="sunset"] {
            --c-1: #FF5A5A; /* Coral */
            --c-2: #FF8B5A; /* Orange */
            --c-3: #FFA95A; /* Peach */
            --c-4: #FFD45A; /* Yellow */
            --c-4-text: #9c6c00;
            
            --bg-body: #fffaf6;
            --surface: #ffffff;
            --text-main: #2d1b15;
            --text-muted: #857068;
            --border-soft: #f2e9e4;
            --border-hard: #e2d2ca;
            
            --answered-bg: #fff0e5;
            --hover-bg: #fffaf5;
            --shadow-color: rgba(255, 90, 90, 0.2);
            --danger-color: #ef4444;
        }

        /* 2. OCEAN TRUST */
        [data-theme="ocean"] {
            --c-1: #111FA2; /* Navy */
            --c-2: #5478FF; /* Royal Blue */
            --c-3: #53CBF3; /* Cyan */
            --c-4: #FFDE42; /* Yellow */
            --c-4-text: #111FA2;
            
            --bg-body: #f4f7fb;
            --surface: #ffffff;
            --text-main: #0c153b;
            --text-muted: #64748b;
            --border-soft: #e2e8f0;
            --border-hard: #cbd5e1;
            
            --answered-bg: #eaf8fd;
            --hover-bg: #fcfdfe;
            --shadow-color: rgba(84, 120, 255, 0.25);
            --danger-color: #ef4444;
        }

        /* 3. DREAMY CALM */
        [data-theme="dreamy"] {
            --c-1: #6367FF; /* Indigo */
            --c-2: #8494FF; /* Periwinkle */
            --c-3: #C9BEFF; /* Lavender */
            --c-4: #FFDBFD; /* Lilac */
            --c-4-text: #1a1b36;
            
            --bg-body: #f8f9ff;
            --surface: #ffffff;
            --text-main: #1a1b36;
            --text-muted: #6b7280;
            --border-soft: #e5e7eb;
            --border-hard: #C9BEFF;
            
            --answered-bg: rgba(201, 190, 255, 0.2);
            --hover-bg: #fdfdff;
            --shadow-color: rgba(99, 103, 255, 0.25);
            --danger-color: #f43f5e;
        }

        /* =========================================
           GLOBAL STYLING
           ========================================= */
        body { 
            background-color: var(--bg-body); 
            font-family: 'Inter', sans-serif; 
            color: var(--text-main);
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* Premium Navbar Gradient */
        .navbar-premium { 
            background: linear-gradient(135deg, var(--c-1) 0%, var(--c-2) 100%);
            color: white; 
            border-bottom: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 20px var(--shadow-color);
            transition: background 0.4s ease;
        }

        /* Glassmorphism User Profile */
        .user-profile-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 100px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: white; color: var(--c-1); transform: translateY(-1px); }

        /* Elegant Cards */
        .exam-card { 
            background: var(--surface);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-soft) !important;
            box-shadow: 0 4px 15px -5px rgba(0, 0, 0, 0.05) !important;
        }
        
        .exam-card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 20px 25px -5px var(--shadow-color) !important; 
            border-color: var(--c-3) !important;
        }

        .card-header-premium {
            background: transparent;
            border-bottom: 1px dashed var(--border-soft);
            padding-bottom: 1rem;
        }

        /* Premium Token Input */
        .token-input { 
            text-transform: uppercase; 
            font-size: 1.5rem; 
            letter-spacing: 8px; 
            text-align: center; 
            font-weight: 700; 
            background-color: var(--bg-body);
            border: 2px solid var(--border-soft);
            color: var(--text-main);
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        
        .token-input:focus {
            background-color: var(--surface);
            border-color: var(--c-1);
            box-shadow: 0 0 0 4px var(--shadow-color);
            outline: none;
        }

        .token-input::placeholder {
            color: var(--text-light);
            font-weight: 500;
            letter-spacing: 4px;
        }

        /* Custom Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--c-1) 0%, var(--c-2) 100%);
            color: white;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary-custom:hover {
            opacity: 0.9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px var(--shadow-color);
        }

        /* Info Item Styling */
        .info-item {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 600;
        }
        
        .info-item i {
            color: var(--c-1);
            background: var(--answered-bg);
            padding: 6px;
            border-radius: 6px;
            margin-right: 8px;
            transition: all 0.3s;
        }

        /* Dynamic Badges */
        .badge-dynamic-primary {
            background: var(--answered-bg);
            color: var(--c-1);
            border: 1px solid var(--c-3);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--c-3); border-radius: 10px; }

        /* =========================================
           FLOATING THEME SWITCHER
           ========================================= */
        .theme-switcher-wrapper {
            position: fixed; bottom: 30px; left: 30px; z-index: 1050;
        }
        .theme-fab {
            width: 55px; height: 55px; border-radius: 50%;
            background: var(--surface); color: var(--c-1);
            border: 2px solid var(--border-soft);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-fab:hover { transform: scale(1.05) rotate(15deg); border-color: var(--c-3); }
        
        .theme-menu {
            position: absolute; bottom: 70px; left: 0;
            background: var(--surface); border: 1px solid var(--border-soft);
            border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 12px; display: flex; flex-direction: column; gap: 10px;
            opacity: 0; visibility: hidden; transform: translateY(15px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-menu.active { opacity: 1; visibility: visible; transform: translateY(0); }
        
        .theme-option {
            width: 45px; height: 45px; border-radius: 50%; cursor: pointer;
            border: 3px solid transparent; transition: transform 0.2s;
            position: relative;
        }
        .theme-option:hover { transform: scale(1.1); }
        .theme-option.active-theme { border-color: var(--text-main); }
    </style>
</head>
<body>

    <div class="theme-switcher-wrapper">
        <div class="theme-menu" id="themeMenu">
            <div class="theme-option" style="background: linear-gradient(135deg, #FF5A5A, #FF8B5A);" onclick="changeTheme('sunset', this)" title="Sunset Energy"></div>
            <div class="theme-option" style="background: linear-gradient(135deg, #111FA2, #53CBF3);" onclick="changeTheme('ocean', this)" title="Ocean Trust"></div>
            <div class="theme-option" style="background: linear-gradient(135deg, #6367FF, #C9BEFF);" onclick="changeTheme('dreamy', this)" title="Dreamy Calm"></div>
        </div>
        <button class="theme-fab" onclick="toggleThemeMenu()" title="Ubah Warna Tema">
            <i class="bi bi-palette-fill fs-4"></i>
        </button>
    </div>

    <nav class="navbar-premium py-3 mb-5">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background: var(--c-4); color: var(--c-4-text); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <i class="bi bi-laptop fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-white tracking-wide">PORTAL CBT SANTRI</h5>
                    <div style="font-size: 11px; font-weight: 500; color: rgba(255,255,255,0.8); letter-spacing: 0.5px;">SISTEM UJIAN BERBASIS KOMPUTER</div>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="user-profile-badge d-none d-md-flex align-items-center gap-3">
                    <div class="text-center text-white">
                        <div class="fw-bold fs-6 lh-1 mb-1">{{ Auth::guard('cbt')->user()->student->name }}</div>
                        <div style="font-size: 11px; color: var(--c-4);" class="fw-bold">
                            <i class="bi bi-person-badge me-1"></i> {{ Auth::guard('cbt')->user()->username }}
                        </div>
                    </div>
                </div>
                <form action="{{ route('cbt.logout') }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmLogout(this.closest('form'))" class="btn-logout px-4 py-2">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <div class="row mb-4">
            <div class="col-12 text-center text-md-start">
                <h4 class="fw-bolder" style="color: var(--text-main);">
                    <i class="bi bi-journal-text me-2" style="color: var(--c-1);"></i>Jadwal Ujian Hari Ini
                </h4>
                <p style="color: var(--text-muted);" class="fw-medium">Pilih ujian yang tersedia dan masukkan token yang diberikan oleh pengawas ruangan.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($availableExams as $exam)
                @php
                    $myExamData = $myExams->get($exam->id);
                    $status = $myExamData ? $myExamData->status : 'not_started';
                @endphp

                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 h-100 exam-card overflow-hidden">
                        
                        <div style="height: 4px; width: 100%; background: linear-gradient(90deg, var(--c-1) 0%, var(--c-2) 100%);"></div>

                        <div class="card-header bg-white card-header-premium mx-4 mt-3 px-0 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0" style="color: var(--text-main);">{{ $exam->name }}</h5>
                            
                            @if($status == 'finished')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-semibold">
                                    <i class="bi bi-check2-all me-1"></i> Selesai
                                </span>
                            @elseif($status == 'working')
                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-50 rounded-pill px-3 py-2 fw-semibold">
                                    <i class="bi bi-pencil-square me-1"></i> Sedang Dikerjakan
                                </span>
                            @else
                                <span class="badge badge-dynamic-primary rounded-pill px-3 py-2 fw-bold">
                                    Tersedia
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body px-4 pb-4">
                            <div class="row g-3 mb-4 mt-1">
                                <div class="col-6 info-item">
                                    <i class="bi bi-book"></i> {{ $exam->questionBank->subject_name }}
                                </div>
                                <div class="col-6 info-item">
                                    <i class="bi bi-hourglass-split"></i> {{ $exam->duration }} Menit
                                </div>
                                <div class="col-6 info-item">
                                    <i class="bi bi-file-text"></i> {{ $exam->questionBank->questions->count() }} Soal
                                </div>
                                <div class="col-6 info-item">
                                    <i class="bi bi-clock-history"></i> Tutup: {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                </div>
                            </div>

                            @if($status == 'finished')
                                <div class="bg-light rounded-4 p-4 text-center mt-4 border border-light-subtle">
                                    <p class="text-success fw-bold mb-2"><i class="bi bi-check-circle-fill me-1"></i> Ujian Selesai</p>
                                    @if($exam->show_result)
                                        <div class="display-4 fw-bolder" style="color: var(--text-main);">{{ round($myExamData->score) }}</div>
                                        <small class="fw-medium" style="color: var(--text-muted);">Nilai Akhir (Pilihan Ganda)</small>
                                    @else
                                        <div class="text-center">
                                            <i class="bi bi-lock-fill fs-3 text-muted opacity-50 mb-2"></i>
                                            <p class="mb-0 fw-medium small" style="color: var(--text-muted);">Nilai akan diumumkan oleh Ustadz/Ustadzah.</p>
                                        </div>
                                    @endif
                                </div>
                            
                            @elseif($status == 'working')
                                <div class="bg-warning bg-opacity-10 rounded-4 p-4 text-center mt-4 border border-warning border-opacity-25">
                                    <p class="fw-medium small mb-3 text-warning-emphasis">Sistem mendeteksi Anda belum menyelesaikan ujian ini. Silakan lanjutkan pengerjaan.</p>
                                    <a href="{{ route('cbt.engine.show', $myExamData->id) }}" class="btn btn-warning w-100 rounded-pill fw-bold text-dark shadow-sm py-2">
                                        LANJUTKAN UJIAN <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            
                            @else
                                <form action="{{ route('cbt.engine.start', $exam->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-center d-block mb-2" style="color: var(--text-muted);">MASUKKAN TOKEN UJIAN</label>
                                        <input type="text" name="token" class="form-control form-control-lg token-input" placeholder="X X X X X" maxlength="10" required autocomplete="off">
                                    </div>
                                    <button type="button" class="btn btn-primary-custom w-100 rounded-pill fw-bold shadow-sm py-3" onclick="confirmStart(this.closest('form'))">
                                        MULAI UJIAN SEKARANG <i class="bi bi-play-circle-fill ms-1"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 rounded-4 py-5" style="background: var(--surface); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);">
                        <div class="card-body text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: var(--bg-body); border: 2px solid var(--border-soft);">
                                <i class="bi bi-calendar-x fs-1" style="color: var(--text-muted);"></i>
                            </div>
                            <h5 class="fw-bold" style="color: var(--text-main);">Belum Ada Jadwal Ujian</h5>
                            <p class="fw-medium mb-0 mt-2" style="color: var(--text-muted);">Saat ini tidak ada jadwal ujian yang aktif untuk kelas Anda.<br>Silakan tunggu arahan dari pengawas ruangan.</p>
                            <button class="btn btn-outline-secondary rounded-pill mt-4 mx-auto px-4 py-2 fw-semibold" onclick="location.reload()" style="border-color: var(--border-hard);">
                                <i class="bi bi-arrow-clockwise me-1"></i> Muat Ulang Halaman
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // --- SISTEM THEME SWITCHER ---
        function toggleThemeMenu() {
            document.getElementById('themeMenu').classList.toggle('active');
        }

        function changeTheme(themeName, element) {
            document.documentElement.setAttribute('data-theme', themeName);
            localStorage.setItem('cbt_theme', themeName);
            
            document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active-theme'));
            if(element) element.classList.add('active-theme');
            
            document.getElementById('themeMenu').classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = localStorage.getItem('cbt_theme') || 'sunset';
            const options = document.querySelectorAll('.theme-option');
            if(currentTheme === 'sunset') options[0].classList.add('active-theme');
            if(currentTheme === 'ocean') options[1].classList.add('active-theme');
            if(currentTheme === 'dreamy') options[2].classList.add('active-theme');
        });

        document.addEventListener('click', function(event) {
            const wrapper = document.querySelector('.theme-switcher-wrapper');
            if (!wrapper.contains(event.target)) {
                document.getElementById('themeMenu').classList.remove('active');
            }
        });

        // --- FUNGSI HALAMAN ---
        // Auto Uppercase Token Input
        document.querySelectorAll('.token-input').forEach(function(input) {
            input.addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase();
            });
        });

        // Notifikasi Sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-4' }
            });
        @endif

        // Notifikasi Error
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: "{{ session('error') }}",
                confirmButtonColor: 'var(--c-1)',
                customClass: { popup: 'rounded-4', confirmButton: 'btn btn-primary-custom rounded-pill px-4' },
                buttonsStyling: false
            });
        @endif

        function confirmLogout(form) {
            Swal.fire({
                title: 'Keluar Aplikasi?',
                text: "Anda akan keluar dari portal ujian.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'btn btn-danger rounded-pill px-4 mx-2 text-white',
                    cancelButton: 'btn btn-outline-secondary rounded-pill px-4 mx-2'
                },
                buttonsStyling: false
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
                text: "Waktu akan mulai dihitung mundur. Pastikan Anda sudah berdoa dan siap!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Mulai Sekarang!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'btn btn-primary-custom rounded-pill px-4 mx-2',
                    cancelButton: 'btn btn-outline-secondary rounded-pill px-4 mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    </script>
</body>
</html>