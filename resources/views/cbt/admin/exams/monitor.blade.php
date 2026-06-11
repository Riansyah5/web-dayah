@extends('layouts.app')
@section('title', 'Live Monitoring Ujian')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* Animated Background Blobs for Glassmorphism Effect */
    .bg-blobs {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -1; overflow: hidden; pointer-events: none;
        background-color: #f4f7fc;
    }
    .blob {
        position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.7;
    }
    .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #b4d3fe; animation: float 12s infinite ease-in-out; }
    .blob-2 { bottom: -10%; right: -10%; width: 400px; height: 400px; background: #c2f0d9; animation: float 10s infinite ease-in-out reverse; }
    .blob-3 { top: 40%; left: 40%; width: 300px; height: 300px; background: #fce1d4; animation: float 14s infinite ease-in-out; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(30px) scale(1.05); }
    }

    /* Premium Glassmorphism UI */
    .glass-panel {
        background: rgba(255, 255, 255, 0.45) !important;
        backdrop-filter: blur(16px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07) !important;
    }

    .stat-card {
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        background: rgba(255, 255, 255, 0.65) !important;
    }

    .icon-box {
        width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;
        border-radius: 16px; font-size: 1.75rem; background: rgba(255,255,255,0.8);
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.5);
    }
    .server-status-pill {
        color: #1e1e2d; border-radius: 50px; padding: 0.5rem 1.25rem;
    }
    .token-box {
        border: 2px dashed rgba(13, 110, 253, 0.3); background-color: rgba(255,255,255,0.5);
        letter-spacing: 2px; backdrop-filter: blur(5px);
    }

    /* Table Glass Styles */
    .table-custom { background: transparent; }
    .table-custom thead th {
        background-color: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px);
        font-weight: 600; text-transform: uppercase; font-size: 0.75rem;
        letter-spacing: 0.5px; color: #495057; border-bottom: 2px solid rgba(0,0,0,0.05);
        padding: 1rem;
    }
    .table-custom tbody td {
        vertical-align: middle; padding: 1rem; border-bottom: 1px solid rgba(0,0,0,0.03);
    }
    .table-custom tbody tr:hover { background-color: rgba(255, 255, 255, 0.5); }
    
    .progress-slim {
        height: 6px; border-radius: 10px; background-color: rgba(0,0,0,0.05); overflow: hidden;
    }

    /* Scrollbar */
    .table-responsive { scrollbar-width: thin; scrollbar-color: rgba(0,0,0,0.2) transparent; }
    .table-responsive::-webkit-scrollbar { width: 6px; height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.2); border-radius: 10px; }

    /* Animations */
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .live-indicator {
        display: inline-block; width: 12px; height: 12px; background-color: #dc3545;
        border-radius: 50%; animation: pulse-red 2s infinite;
    }

    /* Filter Radio Buttons & Search */
    .filter-btn-group .btn { transition: all 0.3s ease; font-weight: 500; }
    .filter-btn-group .btn-check:checked + .btn { background-color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); color: #0d6efd; font-weight: 600; }
    .search-input:focus { outline: none; box-shadow: none; }

    /* =========================================
       RESPONSIVE CUSTOM STYLES (MOBILE & TABLET)
       ========================================= */
       
    /* Mobile Responsiveness untuk Server Pill */
    @media (max-width: 575.98px) {
        .server-status-pill {
            border-radius: 16px !important;
            padding: 12px !important;
        }

        .border-end-sm { border-right: none !important; }
    }
    @media (min-width: 576px) {
        .border-end-sm { border-right: 1px solid rgba(108, 117, 125, 0.25) !important; }
    }

    /* Sticky First Column (Nama Santri) untuk Mobile/Tablet */
    @media (max-width: 991.98px) {
        .table-custom th:first-child,
        .table-custom td:first-child {
            position: sticky;
            left: 0;
            background: rgba(244, 247, 252, 0.95) !important; 
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            z-index: 2;
        }
        
        .table-custom thead th:first-child {
            z-index: 12 !important;
        }

        .table-custom th:first-child::after,
        .table-custom td:first-child::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            right: -8px;
            width: 8px;
            background: linear-gradient(to right, rgba(0,0,0,0.08), transparent);
            pointer-events: none;
        }
        
        .table-custom td:last-child .btn {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-blobs">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="container-fluid py-4 px-4 position-relative" style="z-index: 1;">
    
    <div class="glass-panel d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-4 gap-3 p-4 rounded-4 mx-0">
        
        <div class="w-100 w-xl-auto">
            <div class="d-flex align-items-center mb-1">
                <span class="live-indicator me-3"></span>
                <h4 class="fw-bold mb-0 text-dark">LIVE MONITORING</h4>
            </div>
            <h5 class="text-muted fw-normal mt-2 mb-0">{{ $exam->name }}</h5>
            <div class="mt-3">
                <span class="text-muted small me-2">Token Ujian:</span>
                <strong class="font-monospace fs-5 text-primary token-box px-3 py-1 rounded-3">{{ $exam->token }}</strong>
            </div>
        </div>
        
        <div class="d-flex gap-2 gap-sm-3 align-items-center flex-wrap flex-lg-nowrap w-100 justify-content-xl-end mt-3 mt-xl-0">
            
            <div class="server-status-pill glass-panel border-0 shadow-sm w-100 w-sm-auto d-flex flex-wrap flex-sm-nowrap align-items-center justify-content-between p-5 p-sm-1 px-sm-3 gap-2" style="min-height: 60px;">
                <div class="d-flex align-items-center flex-grow-1 justify-content-between justify-content-sm-start">
                    <i class="bi bi-hdd-network text-primary me-sm-3 fs-4 d-none d-sm-inline-block"></i>
                    
                    <div class="me-2 pe-2 pe-sm-3 border-end border-secondary border-opacity-25 flex-fill text-center text-sm-start" style="min-width: 80px;">
                        <small class="text-muted d-block" style="font-size: 10px; font-weight: 600;">LATENCY</small>
                        <div class="d-flex align-items-center justify-content-center justify-content-sm-start">
                            <span id="serverLatency" class="fw-bold font-monospace text-dark" style="font-size: 0.9rem;">-- ms</span>
                            <span id="pingDot" class="spinner-grow spinner-grow-sm text-success ms-1 ms-sm-2" style="width: 8px; height: 8px;"></span>
                        </div>
                    </div>
                    
                    <div class="me-sm-3 pe-sm-3 border-end-sm border-secondary border-opacity-25 flex-fill text-center text-sm-start" style="min-width: 80px;">
                        <small class="text-muted d-block" style="font-size: 10px; font-weight: 600;">RAM USAGE</small>
                        <span id="serverMemory" class="fw-bold font-monospace text-dark" style="font-size: 0.9rem;">-- MB</span>
                    </div>
                </div>

                <div class="d-flex justify-content-center w-100 w-sm-auto mt-2 mt-sm-0" style="min-width: 120px; height: 35px;">
                    <canvas id="latencyChart"></canvas>
                </div>
            </div>
            
            <a href="{{ route('admin.cbt.exams.index') }}" class="btn glass-panel text-dark border shadow-sm rounded-pill fw-medium px-4 w-100 w-sm-auto mt-2 mt-sm-0" title="Kembali ke daftar ujian" style="width: 50px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="glass-panel stat-card p-3 h-100 d-flex align-items-center border-start border-primary border-4">
                <div class="icon-box text-primary me-3"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="text-muted small fw-bold">Total Login</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statTotal">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel stat-card p-3 h-100 d-flex align-items-center border-start border-info border-4">
                <div class="icon-box text-info me-3"><i class="bi bi-pencil-square"></i></div>
                <div>
                    <div class="text-muted small fw-bold">Mengerjakan</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statOnline">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel stat-card p-3 h-100 d-flex align-items-center border-start border-danger border-4">
                <div class="icon-box text-danger me-3"><i class="bi bi-wifi-off"></i></div>
                <div>
                    <div class="text-muted small fw-bold">Koneksi Putus/Idle</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statOffline">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel stat-card p-3 h-100 d-flex align-items-center border-start border-success border-4">
                <div class="icon-box text-success me-3"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="text-muted small fw-bold">Selesai Ujian</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statFinished">0</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 px-2 gap-3">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-radar me-2 text-primary"></i>Radar Peserta</h5>
            <button type="button" class="btn btn-sm btn-warning shadow-sm rounded-pill px-3 fw-bold text-dark" onclick="openBroadcastModal()" title="Kirim pesan ke seluruh peserta yang sedang aktif">
                <i class="bi bi-megaphone-fill me-1"></i> Tegur Semua
            </button>
        </div>
        
        <div class="d-flex flex-column flex-sm-row gap-3 align-items-center">
            <div class="glass-panel d-flex align-items-center rounded-pill px-3 py-1 shadow-sm w-100" style="max-width: 300px;">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" id="searchInput" class="form-control search-input border-0 bg-transparent shadow-none p-1" placeholder="Cari nama/username..." autocomplete="off">
            </div>

            <div class="glass-panel filter-btn-group p-1 rounded-pill d-inline-flex shadow-sm w-100 w-sm-auto" role="group">
                <input type="radio" class="btn-check" name="filterBtn" id="btnAll" checked onchange="setFilter('all')">
                <label class="btn btn-outline-secondary border-0 text-muted rounded-pill px-3 px-md-4 flex-fill" for="btnAll">Semua</label>

                <input type="radio" class="btn-check" name="filterBtn" id="btnOnline" onchange="setFilter('online')">
                <label class="btn btn-outline-secondary border-0 text-muted rounded-pill px-3 px-md-4 flex-fill" for="btnOnline">Mengerjakan</label>

                <input type="radio" class="btn-check" name="filterBtn" id="btnFinished" onchange="setFilter('finished')">
                <label class="btn btn-outline-secondary border-0 text-muted rounded-pill px-3 px-md-4 flex-fill" for="btnFinished">Selesai</label>
            </div>
        </div>
    </div>

    <div class="card glass-panel border-0 rounded-4 overflow-hidden shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-custom mb-0">
                    <thead class="position-sticky top-0" style="z-index: 10;">
                        <tr>
                            <th class="ps-4" style="min-width: 200px;">Nama Santri</th>
                            <th>Status Terkini</th>
                            <th width="30%" style="min-width: 200px;">Progress Pengerjaan</th>
                            <th>Aktivitas Terakhir</th>
                            <th class="text-end pe-4" style="min-width: 280px;">Aksi Darurat</th>
                        </tr>
                    </thead>
                    <tbody id="studentGrid">
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <span class="spinner-border spinner-border-sm me-2 text-primary"></span> 
                                Memuat data radar...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100" id="toastContainer"></div>

<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <div class="icon-box text-warning d-inline-flex me-2" style="width: 40px; height: 40px; font-size: 1.2rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    Kirim Teguran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <p class="mb-3 text-dark">Pesan pop-up akan dikirim ke layar ujian santri: <strong id="msgStudentName" class="text-primary">...</strong></p>
                <input type="hidden" id="msgStudentExamId">
                <textarea id="msgContent" class="form-control form-control-lg border shadow-sm bg-light" rows="3" placeholder="Contoh: Harap fokus ke layar dan jangan menoleh ke belakang!" style="resize: none;" required></textarea>
                <div class="mt-3 text-muted d-flex align-items-center" style="font-size: 0.75rem;">
                    <i class="bi bi-info-circle me-1 text-primary"></i> *Pesan terkirim maks. dalam 15 detik sesuai siklus koneksi santri.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4 shadow-sm border" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning rounded-pill fw-bold px-4 shadow-sm text-dark" onclick="submitMessage()">
                    Kirim Pesan <i class="bi bi-send ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="broadcastModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <div class="icon-box text-warning d-inline-flex me-2" style="width: 40px; height: 40px; font-size: 1.2rem;">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    Tegur Semua Peserta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <p class="mb-3 text-dark">Pesan pop-up akan dikirim ke layar <strong>SEMUA</strong> santri yang belum selesai ujian.</p>
                <textarea id="broadcastContent" class="form-control form-control-lg border shadow-sm bg-light" rows="3" placeholder="Contoh: Waktu ujian tersisa 15 menit, silakan periksa kembali jawaban kalian!" style="resize: none;" required></textarea>
                <div class="mt-3 text-muted d-flex align-items-center" style="font-size: 0.75rem;">
                    <i class="bi bi-info-circle me-1 text-primary"></i> *Pesan terkirim maks. dalam 15 detik sesuai siklus koneksi santri.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4 shadow-sm border" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning rounded-pill fw-bold px-4 shadow-sm text-dark" onclick="submitBroadcast()">
                    Kirim ke Semua <i class="bi bi-send ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let previousStates = {};
    let allStudentsData = []; // Menyimpan data master dari API
    let currentFilter = 'all'; // State untuk Filter Status
    let currentSearchQuery = ''; // State untuk Filter Pencarian

    // --- Inisialisasi Chart.js Server Realtime ---
    let latencyData = Array(15).fill(0);
    let latencyLabels = Array(15).fill('');
    let serverChart;

    function initChart() {
        const ctx = document.getElementById('latencyChart').getContext('2d');
        serverChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: latencyLabels,
                datasets: [{
                    data: latencyData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 0,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: {
                    x: { display: false },
                    y: { display: false, min: 0 }
                },
                animation: { duration: 400 }
            }
        });
    }

    function updateChartData(latency) {
        latencyData.push(latency);
        latencyLabels.push('');
        if (latencyData.length > 15) {
            latencyData.shift();
            latencyLabels.shift();
        }
        serverChart.update();
    }

    // --- Event Listener untuk Input Pencarian ---
    document.getElementById('searchInput').addEventListener('input', function(e) {
        currentSearchQuery = e.target.value.toLowerCase().trim();
        renderTable(); // Render ulang tabel saat pengguna mengetik
    });

    // --- Fungsi Update State Filter Status ---
    function setFilter(filterType) {
        currentFilter = filterType;
        renderTable(); // Render ulang tabel saat tombol filter diklik
    }

    function confirmForceFinish(form, studentName) {
        Swal.fire({
            title: 'Paksa Selesai?',
            html: `Sesi ujian <b>${studentName}</b> akan dihentikan paksa dan jawaban akan dikunci.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Paksa Selesai',
            cancelButtonText: 'Batal',
            customClass: { confirmButton: 'rounded-pill px-4', cancelButton: 'rounded-pill px-4' }
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    }

    function showToast(message, type = 'success') {
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        audio.play().catch(e => console.log('Autoplay dicegah:', e));

        const bgClass = type === 'success' ? 'bg-success' : (type === 'danger' ? 'bg-danger' : 'bg-info');
        const icon = type === 'success' ? 'bi-check-circle' : (type === 'danger' ? 'bi-exclamation-octagon' : 'bi-info-circle');
        const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
        
        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 shadow-lg rounded-4 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex p-1">
                    <div class="toast-body fw-medium d-flex align-items-center">
                        <i class="bi ${icon} me-2 fs-5"></i> <span>${message}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.getElementById('toastContainer').insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);
        new bootstrap.Toast(toastElement, { delay: 6000 }).show();
        toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }

    // --- Render Tabel Berdasarkan Data, Status, dan Pencarian ---
    function renderTable() {
        let html = '';
        
        let filteredStudents = allStudentsData.filter(student => {
            // 1. Filter by Status
            let matchStatus = true;
            if (currentFilter === 'online') matchStatus = student.status !== 'finished';
            if (currentFilter === 'finished') matchStatus = student.status === 'finished';
            
            // 2. Filter by Search Query (Mencari di Nama atau Username)
            let matchSearch = true;
            if (currentSearchQuery !== '') {
                matchSearch = student.name.toLowerCase().includes(currentSearchQuery) || 
                              student.username.toLowerCase().includes(currentSearchQuery);
            }

            return matchStatus && matchSearch;
        });

        if(filteredStudents.length === 0) {
            html = `<tr><td colspan="5" class="text-center py-5 text-muted"><img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="80" class="mb-3 opacity-50"><br>Tidak ada data peserta yang cocok.</td></tr>`;
        } else {
            filteredStudents.forEach(student => {
                let badgeClass = `bg-${student.status_color} bg-opacity-10 text-${student.status_color} border border-${student.status_color}`;
                let safeName = student.name.replace(/\\/g, "\\\\").replace(/'/g, "\\'").replace(/"/g, "&quot;");

                html += `
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">${student.name}</div>
                            <div class="small font-monospace text-muted mt-1"><i class="bi bi-person-badge"></i> ${student.username}</div>
                        </td>
                        <td>
                            <span class="badge ${badgeClass} rounded-pill px-3 py-2 fw-medium shadow-sm" style="backdrop-filter: blur(5px);">
                                ${student.is_offline && student.status !== 'finished' ? '<i class="bi bi-wifi-off me-1"></i>' : '<i class="bi bi-circle-fill small me-1" style="font-size:8px;"></i>'} 
                                ${student.status_text}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-between small mb-2 fw-medium text-muted">
                                <span>${student.answered} dijawab</span>
                                <span>${student.total_q} soal</span>
                            </div>
                            <div class="progress progress-slim">
                                <div class="progress-bar bg-${student.status_color}" style="width: ${student.progress}%"></div>
                            </div>
                        </td>
                        <td>
                            <div class="small text-muted"><i class="bi bi-clock-history me-1"></i> ${student.last_active}</div>
                        </td>
                        <td class="text-end pe-4">
                            ${student.status !== 'finished' ? `
                                <button type="button" class="btn btn-sm bg-white text-warning border shadow-sm rounded-pill px-3 me-2" onclick="openMessageModal(${student.id}, '${safeName}')" title="Kirim Teguran">
                                    <i class="bi bi-chat-dots-fill"></i> Tegur
                                </button>
                                <form action="/admin/cbt/exams/{{ $exam->id }}/force-finish/${student.id}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" onclick="confirmForceFinish(this.closest('form'), '${safeName}')" class="btn btn-sm bg-white text-danger border shadow-sm rounded-pill px-3" title="Paksa Selesai">
                                        <i class="bi bi-stop-circle-fill"></i> Stop
                                    </button>
                                </form>
                            ` : '<span class="badge bg-light text-muted border px-3 py-2 rounded-pill"><i class="bi bi-lock-fill me-1"></i> Terkunci</span>'}
                        </td>
                    </tr>
                `;
            });
        }

        document.getElementById('studentGrid').innerHTML = html;
    }

    // --- Fetch Data dari API ---
    function fetchMonitorData() {
        const dot = document.getElementById('pingDot');
        dot.classList.add('text-warning');

        fetch('{{ route('admin.cbt.exams.monitor.api', $exam->id) }}')
            .then(response => response.json())
            .then(data => {
                // UPDATE SERVER
                const latencyEl = document.getElementById('serverLatency');
                latencyEl.innerText = data.server.latency + ' ms';
                
                dot.className = 'spinner-grow spinner-grow-sm ms-1 ms-sm-2 ';
                if(data.server.latency < 500) dot.className += 'text-success';
                else if(data.server.latency < 1000) dot.className += 'text-warning';
                else dot.className += 'text-danger';

                document.getElementById('serverMemory').innerText = data.server.memory + ' MB';
                
                // Update realtime graphic
                updateChartData(data.server.latency);

                // UPDATE STATS
                document.getElementById('statTotal').innerText = data.stats.total;
                document.getElementById('statOnline').innerText = data.stats.online;
                document.getElementById('statOffline').innerText = data.stats.offline;
                document.getElementById('statFinished').innerText = data.stats.finished;

                // Simpan Data Ke Global Variable
                allStudentsData = data.students;

                // Check Notifikasi Toast Tanpa Pengaruh Filter
                allStudentsData.forEach(student => {
                    let oldState = previousStates[student.id];
                    if (oldState) {
                        if (oldState.status !== 'finished' && student.status === 'finished') showToast(`${student.name} telah MENYELESAIKAN ujian.`, 'success');
                        if (!oldState.is_offline && student.is_offline && student.status !== 'finished') showToast(`Peringatan: Koneksi ${student.name} TERPUTUS!`, 'danger');
                        if (oldState.is_offline && !student.is_offline && student.status !== 'finished') showToast(`${student.name} kembali ONLINE.`, 'info');
                    }
                    previousStates[student.id] = student;
                });

                // Eksekusi fungsi Render Tabel
                renderTable();
            })
            .catch(error => {
                dot.className = 'spinner-grow spinner-grow-sm ms-1 ms-sm-2 text-danger';
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
        initChart(); 
        fetchMonitorData();
        setInterval(fetchMonitorData, 10000);
    });

    function openMessageModal(studentExamId, studentName) {
        document.getElementById('msgStudentExamId').value = studentExamId;
        document.getElementById('msgStudentName').innerText = studentName;
        document.getElementById('msgContent').value = '';
        new bootstrap.Modal(document.getElementById('messageModal')).show();
    }

    function submitMessage() {
        const studentExamId = document.getElementById('msgStudentExamId').value;
        const message = document.getElementById('msgContent').value;
        const btn = event.target;

        if(message.trim() === '') {
            Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pesan tidak boleh kosong!', timer: 2000, showConfirmButton: false });
            return; 
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';

        fetch(`/admin/cbt/exams/{{ $exam->id }}/send-message/${studentExamId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('messageModal')).hide();
            showToast(data.msg, 'success');
            btn.disabled = false;
            btn.innerHTML = 'Kirim Pesan <i class="bi bi-send ms-1"></i>';
        }).catch(err => {
            btn.disabled = false;
            btn.innerHTML = 'Kirim Pesan <i class="bi bi-send ms-1"></i>';
            showToast('Gagal mengirim pesan.', 'danger');
        });
    }

    function openBroadcastModal() {
        document.getElementById('broadcastContent').value = '';
        new bootstrap.Modal(document.getElementById('broadcastModal')).show();
    }

    function submitBroadcast() {
        const message = document.getElementById('broadcastContent').value;
        const btn = event.target;

        if(message.trim() === '') {
            Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pesan tidak boleh kosong!', timer: 2000, showConfirmButton: false });
            return; 
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';

        fetch(`/admin/cbt/exams/{{ $exam->id }}/send-message-all`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('broadcastModal')).hide();
            showToast(data.msg, 'success');
            btn.disabled = false;
            btn.innerHTML = 'Kirim ke Semua <i class="bi bi-send ms-1"></i>';
        }).catch(err => {
            btn.disabled = false;
            btn.innerHTML = 'Kirim ke Semua <i class="bi bi-send ms-1"></i>';
            showToast('Gagal mengirim pesan.', 'danger');
        });
    }
</script>
@endpush