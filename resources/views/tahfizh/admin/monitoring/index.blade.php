@extends('layouts.app')
@section('title', 'Live Monitoring Tahfizh')
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

</style>
@endpush
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Live Monitoring Tahfizh</h4>
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-danger animate-blink">LIVE</span>
        <span class="text-muted small" id="currentSessionInfo">Memuat sesi...</span>
      </div>
    </div>

    <div class="d-flex gap-2">
      <select id="scheduleFilter" class="form-select rounded-pill shadow-sm" style="width: 200px;">
        <option value="">-- Otomatis --</option>
        @foreach($allSchedules as $s)
        <option value="{{ $s->id }}">{{ $s->session_name }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary rounded-circle shadow-sm" onclick="fetchData()">
        <i class="bi bi-arrow-clockwise"></i>
      </button>
    </div>
  </div>

  <div class="row g-3" id="monitoringGrid">
    <div class="col-12 text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="mt-2 text-muted">Menghubungkan ke server...</p>
    </div>
  </div>
</div>

<div class="modal fade" id="badalModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content border-0 rounded-4">
      <div class="modal-header border-0 bg-light rounded-top-4">
        <h6 class="modal-title fw-bold">Pilih Guru Badal</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('tahfizh.admin.monitoring.assign_badal') }}" method="POST">
          @csrf
          <input type="hidden" name="halaqah_id" id="modalHalaqahId">
          <input type="hidden" name="schedule_id" id="modalScheduleId">
          <input type="hidden" name="original_teacher_id" id="modalOriginalId">

          <div class="mb-3">
            <label class="form-label small text-muted">Menggantikan:</label>
            <div class="fw-bold text-dark" id="modalTeacherName">-</div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold">Pilih Pengganti</label>
            <select name="substitute_teacher_id" class="form-select" required>
              <option value="">-- Cari Guru --</option>
              @foreach($teachers as $t)
              <option value="{{ $t->id }}">{{ $t->name }}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100 rounded-pill">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>



@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Notifikasi Flash Message (Laravel Session)
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
      });
    @endif

    fetchData(); // Panggil pertama kali
    setInterval(fetchData, 10000); // Panggil ulang tiap 10 detik
  });

  // Event Listener Filter Manual
  document.getElementById('scheduleFilter').addEventListener('change', fetchData);

  function fetchData() {
    const scheduleId = document.getElementById('scheduleFilter').value;
    const grid = document.getElementById('monitoringGrid');
    const sessionInfo = document.getElementById('currentSessionInfo');

    // Panggil API
    fetch(`{{ route('tahfizh.admin.monitoring.data') }}?schedule_id=${scheduleId}`)
      .then(response => response.json())
      .then(res => {
        if (res.status === 'empty') {
          grid.innerHTML = `<div class="col-12 text-center text-muted py-5">${res.message}</div>`;
          return;
        }

        // Update Info Header
        sessionInfo.innerText = `${res.session_name} (${res.session_time})`;

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
    // Logic Tampilan Kartu
    let actionBtn = '';
    let photoDisplay = '';
    let bgCard = 'bg-white';
    let borderClass = 'border-0';

    // KASUS 1: IZIN PENDING (Muncul Tombol Approve)
    if (item.status === 'permission_pending') {
      actionBtn = `
                <div class="mt-2 text-center">
                    <div class="small fw-bold text-muted mb-1">"${item.permission_reason}"</div>
                    <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" 
                        onclick="approvePermission(${item.permission_id}, '${item.teacher_name}')">
                        <i class="bi bi-check-lg me-1"></i> Setujui Izin
                    </button>
                </div>
            `;
    }
    // KASUS 2: IZIN APPROVED / TELAT (Muncul Tombol Set Badal)
    else if (item.status === 'permission_approved' || (item.status === 'waiting' && item.is_late) || item.status === 'late') {
      actionBtn = `
                <div class="mt-2 text-center">
                     ${item.status === 'permission_approved' ? `<div class="small fst-italic text-muted mb-1">"${item.permission_reason}"</div>` : ''}
                    <button class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm" 
                        onclick="openBadalModal(${item.halaqah_id}, ${item.schedule_id}, ${item.teacher_id}, '${item.teacher_name}')">
                        <i class="bi bi-person-plus-fill me-1"></i> Set Badal
                    </button>
                </div>
            `;
    }

    // Jika BELUM MASUK & TELAT -> Kasih warna merah pudar
    if (item.status === 'waiting' && item.is_late) {
      bgCard = 'bg-danger bg-opacity-10';
      borderClass = 'border-danger';
    }

    // Tampilan Foto (Jika sudah masuk)
    if (item.status === 'present' && item.photo_url) {
      photoDisplay = `
                <div class="ratio ratio-1x1 rounded-circle overflow-hidden shadow-sm ms-3" style="width: 50px; height: 50px;">
                    <img src="${item.photo_url}" class="object-fit-cover">
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
                                <h6 class="fw-bold mb-0 text-truncate">${item.teacher_name}</h6>
                                <small class="text-muted">${item.group_name}</small>
                                
                                ${item.status === 'present' 
                                    ? `<div class="mt-1 text-success fw-bold small"><i class="bi bi-clock"></i> ${item.check_in_time}</div>` 
                                    : ''}

                                ${item.status === 'permission' 
                                    ? `<div class="mt-1 text-danger small fst-italic">"${item.permission_reason}"</div>` 
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

  // Fungsi Buka Modal
  function openBadalModal(halaqahId, scheduleId, teacherId, teacherName) {
    document.getElementById('modalHalaqahId').value = halaqahId;
    document.getElementById('modalScheduleId').value = scheduleId;
    document.getElementById('modalOriginalId').value = teacherId;
    document.getElementById('modalTeacherName').innerText = teacherName;

    var myModal = new bootstrap.Modal(document.getElementById('badalModal'));
    myModal.show();
  }

  // Fungsi Approve Izin
  function approvePermission(permissionId, teacherName) {
    Swal.fire({
      title: 'Setujui Izin?',
      text: `Apakah Anda yakin ingin menyetujui izin untuk Ustadz ${teacherName}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Setujui!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch(`{{ route('tahfizh.admin.monitoring.approve_permission') }}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              permission_id: permissionId
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Izin berhasil disetujui.',
                timer: 1500,
                showConfirmButton: false
              });
              fetchData(); // Refresh data
            } else {
              Swal.fire('Gagal', data.message, 'error');
            }
          })
          .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Terjadi kesalahan koneksi.', 'error');
          });
      }
    });
  }
</script>
@endpush
