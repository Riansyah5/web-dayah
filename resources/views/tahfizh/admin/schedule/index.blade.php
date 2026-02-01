@extends('layouts.app')
@section('title', 'Jadwal Halaqah Tahfizh')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Manajemen Jadwal Tahfizh</h4>
    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createModal">
      <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </button>
  </div>

  @php
  $days = [
  1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
  5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
  ];
  @endphp

  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    @foreach($days as $key => $dayName)
    <li class="nav-item" role="presentation">
      <button class="nav-link rounded-pill {{ $key == 1 ? 'active' : '' }}" id="pills-{{ $key }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $key }}" type="button">
        {{ $dayName }}
      </button>
    </li>
    @endforeach
  </ul>

  <div class="tab-content" id="pills-tabContent">
    @foreach($days as $key => $dayName)
    <div class="tab-pane fade {{ $key == 1 ? 'show active' : '' }}" id="pills-{{ $key }}">

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead class="bg-light">
                <tr>
                  <th>Sesi</th>
                  <th>Waktu</th>
                  <th>Status</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php
                // Ambil jadwal untuk hari ini, atau collection kosong jika tidak ada
                $dailySchedules = $schedules[$key] ?? collect([]);
                @endphp

                @forelse($dailySchedules as $sched)
                <tr class="{{ !$sched->is_active ? 'bg-light text-muted' : '' }}">
                  <td>
                    <span class="fw-bold">{{ $sched->session_name }}</span>
                  </td>
                  <td>
                    <span class="badge bg-light text-dark border">
                      {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }} -
                      {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}
                    </span>
                  </td>
                  <td>
                    @if($sched->is_active)
                    <span class="badge bg-success">Aktif</span>
                    @else
                    <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary rounded-circle me-1" onclick="editSchedule({{ $sched }})">
                      <i class="bi bi-pencil"></i>
                    </button>

                    <form action="{{ route('tahfizh.admin.schedules.toggle', $sched->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm {{ $sched->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-circle me-1" title="{{ $sched->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                        <i class="bi {{ $sched->is_active ? 'bi-pause-fill' : 'bi-play-fill' }}"></i>
                      </button>
                    </form>

                    <form action="{{ route('tahfizh.admin.schedules.destroy', $sched->id) }}" method="POST" class="d-inline" id="delete-form-{{ $sched->id }}">
                      @csrf @method('DELETE')
                      <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="confirmDelete('{{ $sched->id }}')">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted py-4">
                    Tidak ada jadwal di hari {{ $dayName }}.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
    @endforeach
  </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('tahfizh.admin.schedules.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah Jadwal Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama Sesi</label>
          <input type="text" name="session_name" class="form-control" placeholder="Contoh: Qabla Shubuh" required>
        </div>
        <div class="row">
          <div class="col-6 mb-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="start_time" class="form-control" required>
          </div>
          <div class="col-6 mb-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="end_time" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label d-block">Berlaku Hari:</label>
          <div class="btn-group w-100" role="group">
            @foreach($days as $key => $dayName)
            <input type="checkbox" class="btn-check" name="days[]" value="{{ $key }}" id="check{{$key}}" checked>
            <label class="btn btn-outline-primary btn-sm" for="check{{$key}}">{{ substr($dayName, 0, 3) }}</label>
            @endforeach
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary rounded-pill">Simpan Jadwal</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="#" method="POST" id="formEdit" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Sesi</label>
                    <input type="text" name="session_name" id="editName" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time" id="editStart" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="end_time" id="editEnd" class="form-control" required>
                    </div>
                </div>

                <hr class="my-3">

                <div class="p-3 bg-light rounded border">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="update_all_days" value="1" id="checkBulkUpdate">
                        <label class="form-check-label fw-bold" for="checkBulkUpdate">
                            Terapkan ke semua hari?
                        </label>
                    </div>
                    <div class="small text-muted mt-1">
                        Jika dicentang, perubahan waktu/nama akan diterapkan ke semua sesi bernama <strong id="targetSessionName" class="text-primary">...</strong> di hari Senin-Minggu.
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function editSchedule(data) {
    // Set Action URL
    let url = `{{ route('tahfizh.admin.schedules.update', ':id') }}`;
    url = url.replace(':id', data.id);
    document.getElementById('formEdit').action = url;

    // Isi Data
    document.getElementById('editName').value = data.session_name;
    document.getElementById('editStart').value = data.start_time.substring(0, 5);
    document.getElementById('editEnd').value = data.end_time.substring(0, 5);

    // [BARU] Update Text Helper & Reset Checkbox
    if(document.getElementById('targetSessionName')) document.getElementById('targetSessionName').innerText = `"${data.session_name}"`;
    if(document.getElementById('checkBulkUpdate')) document.getElementById('checkBulkUpdate').checked = false; 

    // Show Modal
    new bootstrap.Modal(document.getElementById('editModal')).show();
  }

  function confirmDelete(id) {
    Swal.fire({
      title: 'Hapus Jadwal?',
      text: "Jika sudah ada riwayat absen, jadwal hanya akan dinonaktifkan.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('delete-form-' + id).submit();
      }
    });
  }

  @if(session('success'))
  Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: @json(session('success')),
    timer: 2000,
    showConfirmButton: false
  });
  @endif

  @if(session('error'))
  Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: @json(session('error')),
  });
  @endif

</script>
@endpush
