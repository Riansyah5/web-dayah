@extends('layouts.app')
@section('title', 'Live Monitoring Ujian')
@push('link')
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-uppercase"><i class="bi bi-broadcast text-danger animate-blink me-2"></i>Live Monitoring: {{ $exam->name }}</h4>
            <div class="text-muted small mt-1">
                Token Aktif: <strong class="font-monospace fs-5 text-primary bg-primary bg-opacity-10 px-2 rounded">{{ $exam->token }}</strong>
            </div>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <div class="bg-dark text-white rounded-pill px-4 py-2 shadow-sm d-flex align-items-center">
                <i class="bi bi-hdd-network me-2"></i>
                <div class="me-3 border-end pe-3">
                    <small class="text-muted d-block" style="font-size: 10px;">LATENCY</small>
                    <span id="serverLatency" class="fw-bold font-monospace">-- ms</span>
                    <span id="pingDot" class="spinner-grow spinner-grow-sm text-success ms-1" style="width: 10px; height: 10px;"></span>
                </div>
                <div>
                    <small class="text-muted d-block" style="font-size: 10px;">RAM USAGE</small>
                    <span id="serverMemory" class="fw-bold font-monospace">-- MB</span>
                </div>
            </div>
            
            <a href="{{ route('admin.cbt.exams.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-primary text-white shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="fs-1 opacity-50 me-3"><i class="bi bi-people"></i></div>
                    <div><h3 class="fw-bold mb-0" id="statTotal">0</h3><small>Total Login</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-info text-white shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="fs-1 opacity-50 me-3"><i class="bi bi-pencil-square"></i></div>
                    <div><h3 class="fw-bold mb-0" id="statOnline">0</h3><small>Online / Mengerjakan</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-danger text-white shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="fs-1 opacity-50 me-3"><i class="bi bi-wifi-off"></i></div>
                    <div><h3 class="fw-bold mb-0" id="statOffline">0</h3><small>Koneksi Terputus / Idle</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-success text-white shadow-sm rounded-4 h-100">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="fs-1 opacity-50 me-3"><i class="bi bi-check2-all"></i></div>
                    <div><h3 class="fw-bold mb-0" id="statFinished">0</h3><small>Selesai & Kumpul</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light position-sticky top-0" style="z-index: 10;">
                        <tr>
                            <th class="ps-4">Nama Santri</th>
                            <th>Status Terkini</th>
                            <th width="30%">Progress Mengerjakan</th>
                            <th>Aktivitas Terakhir</th>
                            <th class="text-end pe-4">Aksi Darurat</th>
                        </tr>
                    </thead>
                    <tbody id="studentGrid">
                        <tr><td colspan="5" class="text-center py-5 text-muted"><span class="spinner-border spinner-border-sm me-2"></span> Memuat data radar...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100" id="toastContainer"></div>

@push('styles')
<style>
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    .animate-blink { animation: blink 2s infinite; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let previousStates = {}; // Memori state santri untuk memicu notifikasi

    // FUNGSI KONFIRMASI SWEETALERT
    function confirmForceFinish(form, studentName) {
        Swal.fire({
            title: `Paksa Selesai Ujian <br>${studentName}?`,
            text: "Jawaban santri akan dikumpulkan apa adanya dan sesi akan dihentikan secara paksa.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Paksa Selesai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // FUNGSI MEMBUAT NOTIFIKASI POP-UP (TOAST)
    function showToast(message, type = 'success') {
        // Mainkan Suara Notifikasi (Ganti URL ini dengan file lokal Anda jika perlu)
        // Contoh file lokal: const audio = new Audio("{{ asset('assets/audio/notification.mp3') }}");
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        audio.play().catch(e => console.log('Audio autoplay dicegah browser (interaksi user diperlukan):', e));

        const bgClass = type === 'success' ? 'bg-success' : (type === 'danger' ? 'bg-danger' : 'bg-info');
        const icon = type === 'success' ? 'bi-check-circle' : (type === 'danger' ? 'bi-exclamation-octagon' : 'bi-info-circle');
        
        const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi ${icon} me-2 fs-5"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        document.getElementById('toastContainer').insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 6000 });
        toast.show();
        
        // Bersihkan DOM setelah hilang
        toastElement.addEventListener('hidden.bs.toast', () => { toastElement.remove(); });
    }

    // FUNGSI UTAMA: MENGAMBIL DATA API
    function fetchMonitorData() {
        const dot = document.getElementById('pingDot');
        dot.classList.add('text-warning'); // Indikator sedang fetching

        fetch('{{ route('admin.cbt.exams.monitor.api', $exam->id) }}')
            .then(response => response.json())
            .then(data => {
                // 1. UPDATE SERVER INDIKATOR
                const latencyEl = document.getElementById('serverLatency');
                latencyEl.innerText = data.server.latency + ' ms';
                
                // Ubah warna dot berdasarkan kecepatan server
                dot.className = 'spinner-grow spinner-grow-sm ms-1 ';
                if(data.server.latency < 500) dot.className += 'text-success';
                else if(data.server.latency < 1000) dot.className += 'text-warning';
                else dot.className += 'text-danger';

                document.getElementById('serverMemory').innerText = data.server.memory + ' MB';

                // 2. UPDATE STATISTIK KARTU
                document.getElementById('statTotal').innerText = data.stats.total;
                document.getElementById('statOnline').innerText = data.stats.online;
                document.getElementById('statOffline').innerText = data.stats.offline;
                document.getElementById('statFinished').innerText = data.stats.finished;

                // 3. RENDER TABEL & DETEKSI NOTIFIKASI
                let html = '';
                
                if(data.students.length === 0) {
                    html = `<tr><td colspan="5" class="text-center py-5 text-muted">Belum ada santri yang login ke ujian ini.</td></tr>`;
                }

                data.students.forEach(student => {
                    // Cek Perubahan State untuk Notifikasi
                    let oldState = previousStates[student.id];
                    if (oldState) {
                        // Jika baru saja klik Selesai
                        if (oldState.status !== 'finished' && student.status === 'finished') {
                            showToast(`${student.name} telah MENYELESAIKAN ujian.`, 'success');
                        }
                        // Jika koneksi tiba-tiba terputus
                        if (!oldState.is_offline && student.is_offline && student.status !== 'finished') {
                            showToast(`Peringatan: Koneksi ${student.name} TERPUTUS/Keluar tab!`, 'danger');
                        }
                        // Jika koneksi nyambung lagi
                        if (oldState.is_offline && !student.is_offline && student.status !== 'finished') {
                            showToast(`${student.name} kembali ONLINE.`, 'info');
                        }
                    }
                    // Simpan state terbaru ke memori
                    previousStates[student.id] = student;

                    // Render Baris HTML
                    html += `
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">${student.name}</div>
                                <div class="small font-monospace text-muted">${student.username}</div>
                            </td>
                            <td>
                                <span class="badge bg-${student.status_color} rounded-pill px-3 py-2">
                                    ${student.is_offline && student.status !== 'finished' ? '<i class="bi bi-wifi-off me-1"></i>' : ''} 
                                    ${student.status_text}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>${student.answered} dijawab</span>
                                    <span>${student.total_q} soal</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-${student.status_color}" role="progressbar" style="width: ${student.progress}%" aria-valuenow="${student.progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td><small class="text-muted fst-italic">${student.last_active}</small></td>
                            <td class="text-end pe-4">
                                ${student.status !== 'finished' ? `
                                    <form action="/admin/cbt/exams/{{ $exam->id }}/force-finish/${student.id}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="button" onclick="confirmForceFinish(this.closest('form'), '${student.name}')" class="btn btn-sm btn-outline-danger rounded-pill px-3">Force Finish</button>
                                    </form>
                                ` : '<span class="text-muted small"><i class="bi bi-lock-fill"></i> Terkunci</span>'}
                            </td>
                        </tr>
                    `;
                });

                document.getElementById('studentGrid').innerHTML = html;
            })
            .catch(error => {
                console.error("Terjadi masalah saat mengambil data radar.", error);
                dot.className = 'spinner-grow spinner-grow-sm ms-1 text-danger';
            });
    }

    // Eksekusi pertama kali, lalu ulangi setiap 10 detik
    document.addEventListener("DOMContentLoaded", function() {
        fetchMonitorData();
        setInterval(fetchMonitorData, 10000); // Polling setiap 10 detik
    });
</script>
@endpush
@endsection
@push('scripts')
@endpush
