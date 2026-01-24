@extends('layouts.app')
@section('title', 'Ajukan Izin')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Form Pengajuan Izin</h5>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('academic.permission.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Tanggal Izin</label>
                <input type="date" name="date" id="dateInput" class="form-control" value="{{ date('Y-m-d') }}"
                  required onchange="fetchSchedules()">
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Pilih Jam Pelajaran yang Ditinggalkan</label>

                <div class="card bg-light border-0">
                  <div class="card-body">

                    <div id="loadingSchedule" class="text-center d-none py-3">
                      <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                      <small class="ms-2 text-muted">Memuat jadwal...</small>
                    </div>

                    <div id="scheduleList">
                      <p class="text-muted small mb-0 text-center fst-italic">-- Pilih tanggal terlebih dahulu --</p>
                    </div>

                    <div id="noScheduleMsg" class="d-none text-center text-danger small py-2">
                      Tidak ada jadwal mengajar pada tanggal ini.
                    </div>

                  </div>
                </div>
                <div class="form-text text-danger d-none" id="validationMsg">* Harap pilih minimal satu jadwal.</div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Jenis Izin</label>
                <select name="type" class="form-select" required>
                  <option value="">-- Pilih Jenis --</option>
                  <option value="sick">Sakit</option>
                  <option value="permit">Izin Pribadi / Acara Keluarga</option>
                  <option value="duty">Tugas Dinas Sekolah</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Alasan Detail</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Jelaskan alasan ketidakhadiran..." required></textarea>
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Lampiran Bukti (Opsional)</label>
                <input type="file" name="attachment" class="form-control" accept="image/*,.pdf">
                <div class="form-text">Contoh: Surat Dokter, Undangan, Surat Tugas.</div>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary rounded-pill fw-bold">Ajukan Izin</button>
                <a href="{{ route('academic.permission.index') }}" class="btn btn-light rounded-pill">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: "{{ session('error') }}",
      });
    @endif

    @if ($errors->any())
      Swal.fire({
        icon: 'error',
        title: 'Periksa Kembali Inputan',
        html: `
                <ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
      });
    @endif
  </script>

  <script>
    // Jalankan sekali saat halaman load (siapa tau tanggal default hari ini ada jadwal)
    document.addEventListener("DOMContentLoaded", function() {
      fetchSchedules();
    });

    function fetchSchedules() {
      const dateVal = document.getElementById('dateInput').value;
      if (!dateVal) return;

      const container = document.getElementById('scheduleList');
      const loader = document.getElementById('loadingSchedule');
      const noMsg = document.getElementById('noScheduleMsg');

      // Reset UI
      container.innerHTML = '';
      noMsg.classList.add('d-none');
      loader.classList.remove('d-none');

      // Panggil Controller via AJAX
      fetch(`{{ route('academic.permission.get_schedules') }}?date=${dateVal}`)
        .then(response => response.json())
        .then(data => {
          loader.classList.add('d-none');

          // Handle jika response bukan array (misal error message dari controller)
          //if (!Array.isArray(data)) {
          //  if (data.message) {
          //    container.innerHTML = `<p class="text-danger small text-center">${data.message}</p>`;
          //  } else {
          //    container.innerHTML = '<p class="text-danger small text-center">Gagal memuat data.</p>';
          //  }
          //  return;
          //}

          if (data.length === 0) {
            noMsg.classList.remove('d-none');
            noMsg.innerText = "Anda tidak memiliki jadwal mengajar di tanggal ini.";
            return;
          }

          // Tambahkan Opsi "Pilih Semua" (Opsional tapi membantu)
          let html = `
                    <div class="form-check mb-2 pb-2 border-bottom">
                        <input class="form-check-input" type="checkbox" id="checkAll" onclick="toggleAll(this)">
                        <label class="form-check-label fw-bold" for="checkAll">Pilih Semua (Izin Seharian)</label>
                    </div>
                `;

          // Render Item Jadwal
          data.forEach(item => {
            html += `
                        <div class="form-check mb-2">
                            <input class="form-check-input schedule-item" type="checkbox" name="schedule_ids[]" value="${item.id}" id="sched_${item.id}">
                            <label class="form-check-label d-flex align-items-center gap-2" for="sched_${item.id}">
                                <span class="badge bg-white text-dark border">${item.time}</span>
                                <span class="fw-bold">${item.classroom}</span>
                                <small class="text-muted">(${item.subject})</small>
                            </label>
                        </div>
                    `;
          });

          container.innerHTML = html;
        })
        .catch(error => {
          loader.classList.add('d-none');
          container.innerHTML = '<p class="text-danger small text-center">Gagal memuat jadwal.</p>';
          console.error(error);
        });
    }

    function toggleAll(source) {
      const checkboxes = document.querySelectorAll('.schedule-item');
      checkboxes.forEach(cb => cb.checked = source.checked);
    }
  </script>
@endpush
