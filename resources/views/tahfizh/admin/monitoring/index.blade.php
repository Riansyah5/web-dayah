@extends('layouts.app')
@section('title', 'Monitoring Tahfizh Admin')
@push('link')
@endpush
@push('styles')
<style>
  @keyframes blink {
    0% {
      opacity: 1;
    }

    50% {
      opacity: 0.5;
    }

    100% {
      opacity: 1;
    }
  }

  .animate-blink {
    animation: blink 1.5s infinite;
  }

  .card-transition {
    transition: all 0.3s ease;
  }

  /* Fix SweetAlert z-index behind Bootstrap Modal */
  .swal2-container {
    z-index: 2000 !important;
  }

</style>
@endpush
@section('content')
<div class="container-fluid py-4">
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
      <div class="row g-3 align-items-center">
        <div class="col-md-4">
          <h5 class="fw-bold mb-1">Monitoring Tahfizh</h5>
          <div class="d-flex align-items-center gap-2">
            <span id="liveBadge" class="badge bg-danger animate-blink">LIVE REALTIME</span>
            <span class="text-muted small fw-bold" id="currentSessionInfo">Memuat...</span>
          </div>
        </div>

        <div class="col-md-8">
          <div class="d-flex justify-content-md-end gap-2">
            <input type="date" id="dateFilter" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" style="max-width: 150px; box-shadow: 0 0 5px rgba(0,0,0,0.1);">

            <select id="scheduleFilter" class="form-select rounded shadow-sm" style="width: 200px;">
              <option value="">-- Sesi Otomatis --</option>
              @foreach($allSchedules as $s)
              <option value="{{ $s->id }}">{{ $s->session_name }}</option>
              @endforeach
            </select>

            <button class="btn btn-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center" onclick="fetchData()" title="Refresh" style="width: 40px; height: 40px;">
              <i class="bi bi-arrow-clockwise"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3" id="monitoringGrid">
    <div class="col-12 text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="mt-2 text-muted">Mengambil data...</p>
    </div>
  </div>
</div>

<div class="modal fade" id="badalModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content border-0 rounded-4">
      <div class="modal-header border-0 bg-light rounded-top-4 pb-0">
        <h6 class="modal-title fw-bold">Atur Guru Pengganti</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('tahfizh.admin.monitoring.assign_badal') }}" method="POST" id="formAssignBadal">
          @csrf
          <input type="hidden" name="halaqah_id" id="modalHalaqahId">
          <input type="hidden" name="schedule_id" id="modalScheduleId">
          <input type="hidden" name="original_teacher_id" id="modalOriginalId">

          <div class="mb-3 text-center pt-2">
            <small class="text-muted d-block">Menggantikan Ustadz:</small>
            <div class="fw-bold text-dark fs-5" id="modalTeacherName">-</div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Pilih Pengganti</label>
            <select name="substitute_teacher_id" id="modalSubstituteSelect" class="form-select" required>
              <option value="">-- Pilih Guru --</option>
              @foreach($teachers as $t)
              <option value="{{ $t->id }}">{{ $t->name }}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100 rounded-pill mb-2">Simpan</button>
        </form>

        <form action="{{ route('tahfizh.admin.monitoring.remove_badal') }}" method="POST" id="formDeleteBadal" class="d-none">
          @csrf
          @method('DELETE')
          <input type="hidden" name="halaqah_id" id="delHalaqahId">
          <input type="hidden" name="schedule_id" id="delScheduleId">
          <button type="button" class="btn btn-outline-danger w-100 rounded-pill btn-sm" onclick="confirmDeleteBadal()">
            <i class="bi bi-trash3 me-1"></i> Batalkan Badal
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Variable global untuk menyimpan interval polling
  let pollingInterval;

  document.addEventListener("DOMContentLoaded", function() {
    fetchData();
    // Polling setiap 10 detik
    pollingInterval = setInterval(fetchData, 10000);
  });

  // Event Listeners Filter
  document.getElementById('scheduleFilter').addEventListener('change', fetchData);
  document.getElementById('dateFilter').addEventListener('change', function() {
    // Jika user ganti tanggal, data direfresh manual
    fetchData();
    // Opsional: Matikan polling otomatis jika buka data lama (untuk hemat resource)
    // clearInterval(pollingInterval); 
  });

  function fetchData() {
    const scheduleId = document.getElementById('scheduleFilter').value;
    const dateVal = document.getElementById('dateFilter').value; // Ambil nilai tanggal

    const grid = document.getElementById('monitoringGrid');
    const sessionInfo = document.getElementById('currentSessionInfo');
    const liveBadge = document.getElementById('liveBadge');

    // URL dengan Parameter Tanggal
    fetch(`{{ route('tahfizh.admin.monitoring.data') }}?schedule_id=${scheduleId}&date=${dateVal}`)
      .then(response => response.json())
      .then(res => {
        if (res.status === 'empty') {
          grid.innerHTML = `<div class="col-12 text-center text-muted py-5 fw-bold"><i class="bi bi-calendar-x fs-1 d-block mb-3"></i>${res.message}</div>`;
          sessionInfo.innerText = "-";
          return;
        }

        // Update Header Info
        sessionInfo.innerText = `${res.session_name} (${res.session_time})`;

        // Logic Badge LIVE: Hanya muncul jika response is_today = true
        if (res.is_today) {
          liveBadge.classList.remove('d-none');
          liveBadge.innerHTML = 'LIVE REALTIME';
          liveBadge.classList.add('bg-danger');
          liveBadge.classList.remove('bg-secondary');
        } else {
          // Jika data masa lalu, ubah jadi "HISTORY"
          liveBadge.classList.remove('d-none'); // Tetap tampilkan tapi ganti teks
          liveBadge.innerHTML = 'HISTORY';
          liveBadge.classList.remove('bg-danger', 'animate-blink');
          liveBadge.classList.add('bg-secondary');
        }

        // Render Kartu
        let html = '';
        res.data.forEach(item => {
          html += buildCard(item);
        });
        grid.innerHTML = html;
      })
      .catch(err => console.error(`Error polling data: ${err}`));
  }

  function buildCard(item) {
    let actionBtn = '';
    let photoDisplay = '';
    let bgCard = 'bg-white';
    let borderClass = 'border-0';

    const safeName = item.teacher_name.replace(/'/g, "\\'");

    // Styling Kartu
    if (item.status === 'waiting' && item.is_late) {
      bgCard = 'bg-danger bg-opacity-10';
      borderClass = 'border-danger';
    }

    // Tampilan Foto
    if (item.status === 'present' && item.photo_url) {
      photoDisplay = `
                <div class="ratio ratio-1x1 rounded-circle overflow-hidden shadow-sm ms-3" style="width: 50px; height: 50px;">
                    <img src="${item.photo_url}" class="object-fit-cover" onclick="window.open('${item.photo_url}')" style="cursor: pointer">
                </div>
            `;
    }

    // --- LOGIC TOMBOL AKSI ---
    // Tombol hanya muncul jika ADMIN MEMILIKI HAK (Biasanya untuk edit data lama juga boleh)

    // 1. Tombol Approve Izin (Pending)
    if (item.status === 'permission_pending') {
      actionBtn = `
                <div class="mt-2 text-center">
                    <div class="small fw-bold text-muted mb-1 fst-italic">"${item.permission_reason}"</div>
                    <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" 
                        onclick="approvePermission('${item.permission_id}', '${safeName}')">
                        <i class="bi bi-check-lg me-1"></i> Setujui Izin
                    </button>
                </div>
            `;
    }
    // 2. Tombol Edit Badal (Sudah Assign)
    else if (item.status === 'badal_assigned') {
      actionBtn = `
                <div class="mt-2 text-center">
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" 
                        onclick="openBadalModal('${item.halaqah_id}', '${item.schedule_id}', '${item.teacher_id}', '${safeName}', '${item.substitute_id}')">
                        <i class="bi bi-pencil-fill me-1"></i> Ganti Badal
                    </button>
                </div>
            `;
    }
    // 3. Tombol Set Badal (Izin Approved / Telat / Alpha)
    else if (item.status === 'permission_approved' || (item.status === 'waiting' && item.is_late) || item.status === 'late') {
      actionBtn = `
                <div class="mt-2 text-center">
                     ${item.status === 'permission_approved' ? `<div class="small fst-italic text-muted mb-1">"${item.permission_reason}"</div>` : ''}
                    <button class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm" 
                        onclick="openBadalModal('${item.halaqah_id}', '${item.schedule_id}', '${item.teacher_id}', '${safeName}')">
                        <i class="bi bi-person-plus-fill me-1"></i> Set Badal
                    </button>
                </div>
            `;
    }

    return `
            <div class="col-md-4 col-lg-3">
                <div class="card shadow-sm h-100 card-transition ${bgCard} ${borderClass} rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="w-100">
                                <span class="badge ${item.badge_class} mb-2">${item.status_text}</span>
                                <h6 class="fw-bold mb-0 text-truncate" title="${item.teacher_name}">${item.teacher_name}</h6>
                                <small class="text-muted">${item.group_name}</small>
                                
                                ${item.status === 'present' 
                                    ? `<div class="mt-1 text-success fw-bold small"><i class="bi bi-clock"></i> ${item.check_in_time}</div>` 
                                    : ''}
                            </div>
                            ${photoDisplay}
                        </div>
                        <div class="text-center">
                            ${actionBtn}
                        </div>
                    </div>
                </div>
            </div>
        `;
  }

  // Fungsi JS Pendukung (Modal & Approve)
  function openBadalModal(halaqahId, scheduleId, teacherId, teacherName, currentSubId) {
    document.getElementById('modalHalaqahId').value = halaqahId;
    document.getElementById('modalScheduleId').value = scheduleId;
    document.getElementById('modalOriginalId').value = teacherId;
    document.getElementById('modalTeacherName').innerText = teacherName;

    const select = document.getElementById('modalSubstituteSelect');
    select.value = currentSubId || "";

    const formDelete = document.getElementById('formDeleteBadal');
    if (currentSubId) {
      formDelete.classList.remove('d-none');
      document.getElementById('delHalaqahId').value = halaqahId;
      document.getElementById('delScheduleId').value = scheduleId;
    } else {
      formDelete.classList.add('d-none');
    }

    var myModal = new bootstrap.Modal(document.getElementById('badalModal'));
    myModal.show();
  }

  function approvePermission(permId, name) {
    Swal.fire({
      title: 'Setujui Izin?',
      text: `Setujui izin untuk Ustadz ${name}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Setujui',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch(`{{ route('tahfizh.admin.monitoring.approve_permission') }}`, {
            method: 'POST'
            , headers: {
              'Content-Type': 'application/json'
              , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            , body: JSON.stringify({
              permission_id: permId
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire('Berhasil', 'Izin telah disetujui.', 'success');
              fetchData();
            }
          });
      }
    });
  }

  function confirmDeleteBadal() {
    Swal.fire({
      title: 'Batalkan Badal?',
      text: "Guru asli akan kembali tercatat sebagai pengajar.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Batalkan!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('formDeleteBadal').submit();
      }
    });
  }

  @if(session('success'))
  Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
  });
  @endif

  @if(session('error'))
  Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: "{{ session('error') }}",
  });
  @endif

</script>
@endpush
