@extends('layouts.app')
@section('title', 'Pengajuan Izin Tahfizh')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold mb-0">Pengajuan Izin Tahfizh</h5>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('tahfizh.permission.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-bold">Tanggal Izin</label>
              <input type="date" name="date" id="dateInput" class="form-control" required onchange="fetchSchedules()">
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Pilih Sesi yang Ditinggalkan</label>
              <div class="card bg-light border-0">
                <div class="card-body">
                  <div id="loadingSchedule" class="d-none text-center py-2">
                    <div class="spinner-border spinner-border-sm text-primary"></div> Memuat sesi...
                  </div>
                  <div id="scheduleList">
                    <small class="text-muted fst-italic">-- Pilih tanggal dulu --</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Jenis Izin</label>
              <select name="type" class="form-select" required>
                <option value="sick">Sakit</option>
                <option value="permission">Izin Pribadi(Kepentingan Lain)</option>
                <option value="duty">Tugas Sekolah</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Alasan</label>
              <textarea name="reason" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Lampiran Bukti (Opsional)</label>
              <input type="file" name="attachment" class="form-control">
              <div class="form-text">Surat dokter atau bukti pendukung lainnya.</div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary rounded-pill fw-bold">Ajukan Izin</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@push('scripts')
<script>
  function fetchSchedules() {
    const dateVal = document.getElementById('dateInput').value;
    if (!dateVal) return;

    const container = document.getElementById('scheduleList');
    const loader = document.getElementById('loadingSchedule');

    container.innerHTML = '';
    loader.classList.remove('d-none');

    fetch(`{{ route('tahfizh.permission.get_schedules') }}?date=${dateVal}`)
      .then(res => res.json())
      .then(data => {
        loader.classList.add('d-none');

        if (data.length === 0) {
          container.innerHTML = '<span class="text-danger small">Tidak ada jadwal tahfizh di hari ini.</span>';
          return;
        }

        let html = '';
        data.forEach(item => {
          html += `
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="schedule_ids[]" value="${item.id}" id="sess_${item.id}">
                            <label class="form-check-label d-flex align-items-center gap-2" for="sess_${item.id}">
                                <span class="fw-bold text-dark">${item.session_name}</span>
                                <span class="badge bg-white text-dark border">${item.time}</span>
                            </label>
                        </div>
                    `;
        });
        container.innerHTML = html;
      });
  }

</script>
@endpush
