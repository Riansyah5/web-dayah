@extends('layouts.app')
@section('title', 'Jadwal & Token Ujian')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    body {
        background: linear-gradient(135deg, #f6f8fb 0%, #e9f0f7 100%);
        min-height: 100vh;
    }

    /* Glassmorphism Core */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
    }

    /* Header Styling */
    .text-gradient-primary {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .header-icon-box {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 16px;
        box-shadow: 0 12px 20px -5px rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    /* Search & Filter Styling */
    .input-search-glass {
        background: rgba(255, 255, 255, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.9) !important;
        border-radius: 12px !important;
        padding-left: 40px !important;
        transition: all 0.3s;
    }
    
    .input-search-glass:focus {
        background: white !important;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.08) !important;
    }

    .filter-pill {
        cursor: pointer;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
        border: 1px solid transparent;
        background: rgba(255, 255, 255, 0.5);
        color: #6c757d;
    }

    .filter-pill.active {
        background: #0d6efd;
        color: white;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }

    /* Token & Table Design */
    .token-display {
        background: rgba(13, 110, 253, 0.05);
        border: 2px dashed #0d6efd;
        color: #0d6efd;
        font-family: 'Monaco', monospace;
        padding: 5px 12px;
        border-radius: 10px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .table thead th {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        color: #8898aa;
        padding: 1.5rem 1rem;
    }

    .animate-pulse-live {
        animation: pulse-live 2s infinite;
    }

    @keyframes pulse-live {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <div class="d-flex align-items-center">
                <div class="header-icon-box me-3 animate__animated animate__fadeInLeft">
                    <i class="bi bi-shield-check fs-3"></i>
                </div>
                <div>
                    <h2 class="fw-bolder mb-0 text-dark">Jadwal & <span class="text-gradient-primary">Token</span></h2>
                    <p class="text-muted small mb-0">Manajemen akses ujian Computer Based Test (CBT)</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
            <a href="{{ route('admin.cbt.exams.create') }}" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-2"></i>Jadwal Baru
            </a>
        </div>
    </div>

    <div class="row mb-4 g-3 align-items-center">
        <div class="col-md-4">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="examSearch" class="form-control input-search-glass" placeholder="Cari nama ujian atau mata pelajaran...">
            </div>
        </div>
        <div class="col-md-8">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="statusFilters">
                <div class="filter-pill active" data-filter="all">Semua</div>
                <div class="filter-pill" data-filter="running">Sedang Berjalan</div>
                <div class="filter-pill" data-filter="upcoming">Belum Mulai</div>
                <div class="filter-pill" data-filter="finished">Selesai</div>
            </div>
        </div>
    </div>

    <div class="glass-card overflow-hidden animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="examTable">
                <thead>
                    <tr>
                        <th class="ps-4">Informasi Utama</th>
                        <th>Waktu Pelaksanaan</th>
                        <th class="text-center">Token</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                    @php
                        $now = \Carbon\Carbon::now();
                        $isOngoing = $now->between($exam->start_time, $exam->end_time);
                        $isFinished = $now->greaterThan($exam->end_time);
                        
                        // Data Attribute untuk Filter
                        $statusAttr = 'upcoming';
                        if($isFinished) $statusAttr = 'finished';
                        elseif($isOngoing) $statusAttr = 'running';
                    @endphp
                    <tr data-status="{{ $statusAttr }}">
                        <td class="ps-4 py-4">
                            <div class="fw-bold text-dark fs-6">{{ $exam->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-book me-1"></i>{{ $exam->questionBank->subject_name }} ({{ $exam->questionBank->level }})
                            </div>
                        </td>
                        <td>
                            <div class="small fw-bold text-dark mb-1">{{ $exam->start_time->translatedFormat('d M Y') }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }} ({{ $exam->duration }}m)
                            </div>
                        </td>
                        <td class="text-center">
                            @if($isFinished)
                                <span class="text-muted small italic text-uppercase" style="letter-spacing: 1px;">Closed</span>
                            @else
                                <div class="d-inline-flex align-items-center gap-2">
                                    <span class="token-display shadow-sm">{{ $exam->token }}</span>
                                    <form action="{{ route('admin.cbt.exams.refresh_token', $exam->id) }}" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-sm text-warning p-0" onclick="confirmRefresh(this.closest('form'))">
                                            <i class="bi bi-arrow-repeat fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($isFinished)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill border">Selesai</span>
                            @elseif($isOngoing)
                                <span class="badge bg-success px-3 py-2 rounded-pill animate-pulse-live shadow-sm">
                                    <i class="bi bi-record-fill me-1"></i> Running
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill border border-warning border-opacity-25">Upcoming</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.cbt.exams.monitor', $exam->id) }}" class="btn btn-sm btn-white border shadow-sm px-3 rounded-3 fw-bold text-primary" target="_blank">
                                    <i class="bi bi-activity me-1"></i> Monitor
                                </a>
                                <form action="{{ route('admin.cbt.exams.destroy', $exam->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-3" onclick="confirmDelete(this.closest('form'))">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted opacity-50 mb-2"><i class="bi bi-inbox fs-1"></i></div>
                            <p class="text-muted">Tidak ada data jadwal ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Live Search & Filter Logic
    const searchInput = document.getElementById('examSearch');
    const filterPills = document.querySelectorAll('.filter-pill');
    const rows = document.querySelectorAll('#examTable tbody tr');

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector('.filter-pill.active').dataset.filter;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.dataset.status;
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = (activeFilter === 'all' || status === activeFilter);

            if (matchesSearch && matchesStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    searchInput.addEventListener('keyup', applyFilters);

    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            filterPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            applyFilters();
        });
    });

    // 2. SweetAlert Global Style
    const swalConfig = {
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,50,0.1)`,
        confirmButtonColor: '#0d6efd',
        borderRadius: '20px'
    };

    @if(session('success'))
        Swal.fire({...swalConfig, icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false});
    @endif

    function confirmRefresh(form) {
        Swal.fire({
            ...swalConfig,
            title: 'Ganti Token?',
            text: "Token baru akan diperlukan untuk siswa yang belum login.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset Token'
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    }

    function confirmDelete(form) {
        Swal.fire({
            ...swalConfig,
            title: 'Hapus Jadwal?',
            text: "Data ujian ini tidak bisa dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Hapus Sekarang'
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    }
</script>
@endpush