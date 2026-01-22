@extends('layouts.app')
@section('title', 'Monitoring Jam Pelajaran')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Monitoring Piket & Badal</h4>
        <p class="text-muted small mb-0">Kelola kehadiran guru dan guru pengganti harian.</p>
      </div>

      <form action="{{ route('academic.picket.index') }}" method="GET" class="d-flex gap-2">
        <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}"
          onchange="this.form.submit()">
      </form>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
          <div class="card-body">
            <h2 class="fw-bold mb-0">{{ $schedules->count() }}</h2>
            <small>Total Jam Pelajaran</small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white">
          <div class="card-body">
            <h2 class="fw-bold mb-0">{{ $permissions->count() }}</h2>
            <small>Guru Izin</small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark">
          <div class="card-body">
            <h2 class="fw-bold mb-0">{{ $substitutes->count() }}</h2>
            <small>Jadwal Dibadalkan</small>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Jam</th>
                <th>Kelas / Mapel</th>
                <th>Guru Asli</th>
                <th>Status Kehadiran</th>
                <th>Guru Pengganti (Badal)</th>
              </tr>
            </thead>
            <tbody>
              @forelse($schedules as $schedule)
                @php
                  // Cek apakah Guru Asli Izin?
                  $isAbsent = isset($permissions[$schedule->teacher_id]);
                  $permission = $isAbsent ? $permissions[$schedule->teacher_id] : null;

                  // Cek apakah sudah ada Badal?
                  $hasSubstitute = isset($substitutes[$schedule->id]);
                  $substituteData = $hasSubstitute ? $substitutes[$schedule->id] : null;

                  // Styling Row
                  $rowClass = '';
                  if ($isAbsent && !$hasSubstitute) {
                      $rowClass = 'table-danger';
                  } // Izin tapi blm ada badal (Bahaya)
                  if ($hasSubstitute) {
                      $rowClass = 'table-warning';
                  } // Sudah ada badal (Aman)
                @endphp

                <tr class="{{ $rowClass }}">
                  <td class="ps-4">
                    <span class="badge bg-light text-dark border">
                      {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                      {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </span>
                  </td>
                  <td>
                    <div class="fw-bold">{{ $schedule->classroom->name }}</div>
                    <small class="text-muted">{{ $schedule->subject->name }}</small>
                  </td>
                  <td>
                    {{ $schedule->teacher->name }}
                  </td>
                  <td>
                    @if ($isAbsent)
                      @if ($permission->status == 'pending')
                        <div class="d-flex gap-1 align-items-center">
                          <span class="badge bg-warning text-dark me-2">PENDING</span>

                          {{-- Tombol Approve --}}
                          <form action="{{ route('academic.picket.permission.update', $permission->id) }}"
                            method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button class="btn btn-sm btn-success py-0 px-2" title="Setujui"><i
                                class="bi bi-check"></i></button>
                          </form>

                          {{-- Tombol Reject --}}
                          <form action="{{ route('academic.picket.permission.update', $permission->id) }}"
                            method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-sm btn-danger py-0 px-2" title="Tolak"><i
                                class="bi bi-x"></i></button>
                          </form>

                          {{-- Tombol Lihat Bukti --}}
                          @if ($permission->attachment)
                            <a href="{{ asset('storage/' . $permission->attachment) }}" target="_blank"
                              class="btn btn-sm btn-light border py-0 px-2" title="Lihat Surat"><i
                                class="bi bi-eye"></i></a>
                          @endif
                        </div>
                        <div style="font-size: 0.7rem; margin-top: 4px;">{{ Str::limit($permission->reason, 20) }}</div>
                      @elseif($permission->status == 'approved')
                        <span class="badge bg-danger">IZIN: {{ strtoupper($permission->type) }}</span>
                        <div style="font-size: 0.7rem;">(Disetujui)</div>
                      @else
                        <span class="badge bg-secondary text-decoration-line-through">IZIN DITOLAK</span>
                        <div style="font-size: 0.7rem;">Wajib Hadir</div>
                      @endif
                    @else
                      <span class="badge bg-success bg-opacity-10 text-success">Hadir (Jadwal)</span>
                    @endif
                  </td>
                  <td>
                    @if ($hasSubstitute)
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <span class="badge bg-dark">Badal:</span>
                          <span class="fw-bold">{{ $substituteData->substituteTeacher->name }}</span>
                        </div>
                        <form action="{{ route('academic.picket.remove', $substituteData->id) }}" method="POST"
                          class="delete-form">
                          @csrf @method('DELETE')
                          <button class="btn btn-sm text-danger ms-2" title="Hapus"><i
                              class="bi bi-x-circle-fill"></i></button>
                        </form>
                      </div>
                    @else
                      <form action="{{ route('academic.picket.assign') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="lesson_schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">

                        <select name="substitute_teacher_id" class="form-select form-select-sm" style="max-width: 200px;"
                          required>
                          <option value="">-- Pilih Badal --</option>
                          @foreach ($allTeachers as $t)
                            {{-- Jangan tampilkan guru asli di dropdown --}}
                            @if ($t->id != $schedule->teacher_id)
                              <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endif
                          @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Set</button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-5 text-muted">Tidak ada jadwal pelajaran hari ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // 1. Handle Flash Message (Success/Error) dari Controller
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
      });
    @endif

    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
      });
    @endif

    // 2. Konfirmasi Hapus Badal
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Batalkan Badal?',
          text: "Guru asli akan kembali tercatat pada jadwal ini.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Batalkan!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            this.submit();
          }
        });
      });
    });
  </script>
@endpush
