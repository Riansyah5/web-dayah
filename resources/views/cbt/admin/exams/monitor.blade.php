@extends('layouts.app')
@section('title', 'Live Monitoring Ujian')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* Premium UI Customization */
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }
    .icon-box {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 1.75rem;
    }
    .server-status-pill {
        background: #1e1e2d;
        color: #fff;
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .token-box {
        border: 2px dashed #cfe2ff;
        background-color: #f8faff;
        letter-spacing: 2px;
    }
    /* Table Premium Styles */
    .table-custom thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
        padding: 1rem;
    }
    .table-custom tbody td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f1f1f4;
    }
    .table-custom tbody tr:hover {
        background-color: #fcfcfd;
    }
    .progress-slim {
        height: 6px;
        border-radius: 10px;
        background-color: #e9ecef;
        overflow: hidden;
    }
    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }
    /* Animations */
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .live-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        background-color: #dc3545;
        border-radius: 50%;
        animation: pulse-red 2s infinite;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-4 gap-3 bg-white p-4 rounded-4 shadow-sm mx-0">
        <div>
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
        
        <div class="d-flex gap-3 align-items-center flex-wrap">
            <div class="server-status-pill d-flex align-items-center">
                <i class="bi bi-hdd-network text-info me-3 fs-5"></i>
                <div class="me-3 border-end border-secondary pe-3">
                    <small class="text-secondary d-block" style="font-size: 10px; font-weight: 600;">LATENCY</small>
                    <div class="d-flex align-items-center">
                        <span id="serverLatency" class="fw-bold font-monospace">-- ms</span>
                        <span id="pingDot" class="spinner-grow spinner-grow-sm text-success ms-2" style="width: 8px; height: 8px;"></span>
                    </div>
                </div>
                <div>
                    <small class="text-secondary d-block" style="font-size: 10px; font-weight: 600;">RAM USAGE</small>
                    <span id="serverMemory" class="fw-bold font-monospace">-- MB</span>
                </div>
            </div>
            
            <a href="{{ route('admin.cbt.exams.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-medium px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card p-3 shadow-sm h-100 d-flex align-items-center border-start border-primary border-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Total Login</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statTotal">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 shadow-sm h-100 d-flex align-items-center border-start border-info border-4">
                <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Mengerjakan</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statOnline">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 shadow-sm h-100 d-flex align-items-center border-start border-danger border-4">
                <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                    <i class="bi bi-wifi-off"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Koneksi Putus/Idle</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statOffline">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 shadow-sm h-100 d-flex align-items-center border-start border-success border-4">
                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Selesai Ujian</div>
                    <h3 class="fw-bold mb-0 text-dark" id="statFinished">0</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-custom mb-0">
                    <thead class="position-sticky top-0 shadow-sm" style="z-index: 10; backdrop-filter: blur(5px);">
                        <tr>
                            <th class="ps-4">Nama Santri</th>
                            <th>Status Terkini</th>
                            <th width="30%">Progress Pengerjaan</th>
                            <th>Aktivitas Terakhir</th>
                            <th class="text-center pe-4">Aksi Darurat</th>
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
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning d-inline-flex me-2" style="width: 40px; height: 40px; font-size: 1.2rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    Kirim Teguran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <p class="mb-3 text-muted">Pesan pop-up akan dikirim ke layar ujian santri: <strong id="msgStudentName" class="text-dark">...</strong></p>
                <input type="hidden" id="msgStudentExamId">
                <textarea id="msgContent" class="form-control form-control-lg bg-light border-0" rows="3" placeholder="Contoh: Harap fokus ke layar dan jangan menoleh ke belakang!" style="resize: none;" required></textarea>
                <div class="mt-3 text-muted d-flex align-items-center" style="font-size: 0.75rem;">
                    <i class="bi bi-info-circle me-1 text-primary"></i> *Pesan terkirim maks. dalam 15 detik sesuai siklus koneksi santri.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 bg-white">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning rounded-pill fw-bold px-4 shadow-sm" onclick="submitMessage()">
                    Kirim Pesan <i class="bi bi-send ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let previousStates = {};

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
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 shadow-lg rounded-3 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
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

    function fetchMonitorData() {
        const dot = document.getElementById('pingDot');
        dot.classList.add('text-warning');

        fetch('{{ route('admin.cbt.exams.monitor.api', $exam->id) }}')
            .then(response => response.json())
            .then(data => {
                // UPDATE SERVER
                const latencyEl = document.getElementById('serverLatency');
                latencyEl.innerText = data.server.latency + ' ms';
                
                dot.className = 'spinner-grow spinner-grow-sm ms-2 ';
                if(data.server.latency < 500) dot.className += 'text-success';
                else if(data.server.latency < 1000) dot.className += 'text-warning';
                else dot.className += 'text-danger';

                document.getElementById('serverMemory').innerText = data.server.memory + ' MB';

                // UPDATE STATS
                document.getElementById('statTotal').innerText = data.stats.total;
                document.getElementById('statOnline').innerText = data.stats.online;
                document.getElementById('statOffline').innerText = data.stats.offline;
                document.getElementById('statFinished').innerText = data.stats.finished;

                // RENDER TABLE
                let html = '';
                if(data.students.length === 0) {
                    html = `<tr><td colspan="5" class="text-center py-5 text-muted"><img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="80" class="mb-3 opacity-50"><br>Belum ada santri yang login.</td></tr>`;
                }

                data.students.forEach(student => {
                    let oldState = previousStates[student.id];
                    if (oldState) {
                        if (oldState.status !== 'finished' && student.status === 'finished') showToast(`${student.name} telah MENYELESAIKAN ujian.`, 'success');
                        if (!oldState.is_offline && student.is_offline && student.status !== 'finished') showToast(`Peringatan: Koneksi ${student.name} TERPUTUS!`, 'danger');
                        if (oldState.is_offline && !student.is_offline && student.status !== 'finished') showToast(`${student.name} kembali ONLINE.`, 'info');
                    }
                    previousStates[student.id] = student;

                    // Mengatur warna modern untuk badge (menggunakan bg-opacity-10 dari Bootstrap)
                    let badgeClass = `bg-${student.status_color} bg-opacity-10 text-${student.status_color} border border-${student.status_color}`;
                    // Khusus warning agar teksnya lebih mudah dibaca (text-dark) jika diinginkan, tapi text-warning bootstrap cukup gelap di bg putih.

                    html += `
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">${student.name}</div>
                                <div class="small font-monospace text-muted mt-1"><i class="bi bi-person-badge"></i> ${student.username}</div>
                            </td>
                            <td>
                                <span class="badge ${badgeClass} rounded-pill px-3 py-2 fw-medium">
                                    ${student.is_offline && student.status !== 'finished' ? '<i class="bi bi-wifi-off me-1"></i>' : '<i class="bi bi-circle-fill small me-1" style="font-size:8px;"></i>'} 
                                    ${student.status_text}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between small mb-2 fw-medium text-secondary">
                                    <span>${student.answered} dijawab</span>
                                    <span>${student.total_q} soal</span>
                                </div>
                                <div class="progress progress-slim">
                                    <div class="progress-bar bg-${student.status_color}" style="width: ${student.progress}%"></div>
                                </div>
                            </td>
                            <td>
                                <div class="small text-secondary"><i class="bi bi-clock-history me-1"></i> ${student.last_active}</div>
                            </td>
                            <td class="text-end pe-4">
                                ${student.status !== 'finished' ? `
                                    <button type="button" class="btn btn-sm btn-light text-warning border shadow-sm rounded-pill px-3 me-2" onclick="openMessageModal(${student.id}, '${student.name}')" title="Kirim Teguran">
                                        <i class="bi bi-chat-dots-fill"></i> Tegur
                                    </button>
                                    <form action="/admin/cbt/exams/{{ $exam->id }}/force-finish/${student.id}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="button" onclick="confirmForceFinish(this.closest('form'), '${student.name}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-pill px-3" title="Paksa Selesai">
                                            <i class="bi bi-stop-circle-fill"></i> Stop
                                        </button>
                                    </form>
                                ` : '<span class="badge bg-light text-secondary border px-3 py-2 rounded-pill"><i class="bi bi-lock-fill me-1"></i> Terkunci</span>'}
                            </td>
                        </tr>
                    `;
                });

                document.getElementById('studentGrid').innerHTML = html;
            })
            .catch(error => {
                dot.className = 'spinner-grow spinner-grow-sm ms-2 text-danger';
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
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
</script>
@endpush