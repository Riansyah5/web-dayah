@extends('layouts.app')
@section('title', 'Monitoring Jam Pelajaran')
@push('link')
@endpush
@push('styles')
<style>
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }
    .animate-blink {
        animation: blink 1.5s infinite;
    }
</style>
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

    <div class="row g-3 mb-4" id="summary-stats">
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

    @php
      $groupedSchedules = $schedules
          ->groupBy(function ($item) {
              return $item->classroom->level->stage->code ?? 'Umum';
          })
          ->sortKeys();
    @endphp

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0" style="z-index: 1; position: relative;">
        <ul class="nav nav-tabs card-header-tabs" id="picketTabs" role="tablist">
          @foreach ($groupedSchedules as $stage => $items)
            <li class="nav-item" role="presentation">
              <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ Str::slug($stage) }}"
                data-bs-toggle="tab" data-bs-target="#content-{{ Str::slug($stage) }}" type="button" role="tab"
                aria-controls="content-{{ Str::slug($stage) }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                <span class="fw-bold">{{ $stage }}</span> <span class="badge bg-secondary ms-1">{{ $items->count() }}</span>
              </button>
            </li>
          @endforeach
        </ul>
      </div>
      <div class="card-body p-0">
        <div class="tab-content" id="picketTabsContent">
          @forelse ($groupedSchedules as $stage => $items)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ Str::slug($stage) }}"
              role="tabpanel" aria-labelledby="tab-{{ Str::slug($stage) }}">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 mt-5">
                  <thead class="bg-light">
                    <tr>
                      <th class="ps-4">Jam</th>
                      <th>Kelas / Mapel</th>
                      <th>Guru Asli</th>
                      <th>Status Kehadiran</th>
                    </tr>
                  </thead>
                  <tbody id="realtime-schedule-body-{{ Str::slug($stage) }}" class="realtime-body">
                    @foreach ($items as $schedule)
                @php
                  // 1. DATA PENDUKUNG
                  // Cek Izin
                  $isAbsent = isset($permissions[$schedule->teacher_id]);
                  $permission = $isAbsent ? $permissions[$schedule->teacher_id] : null;

                  // Cek Badal
                  $hasSubstitute = isset($substitutes[$schedule->id]);
                  $substituteData = $hasSubstitute ? $substitutes[$schedule->id] : null;

                  // Cek Realisasi Jurnal (Apakah sudah ada guru masuk?)
                  $journal = isset($journals[$schedule->id]) ? $journals[$schedule->id] : null;

                  // 2. LOGIKA INDIKATOR WAKTU (Realtime)
                  $now = \Carbon\Carbon::now();
                  $startTime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
                  $endTime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);

                  $statusRealtime = 'waiting';

                  if ($journal) {
                      $statusRealtime = 'present'; // Guru ada di kelas
                  } elseif ($isAbsent && !$hasSubstitute) {
                      // Jika izin disetujui tapi belum ada badal = BAHAYA
                      if ($permission->status == 'approved') {
                          $statusRealtime = 'absent_empty';
                      } else {
                          $statusRealtime = 'waiting_approval';
                      }
                  } elseif ($now > $startTime && $now < $endTime) {
                      $statusRealtime = 'late'; // Telat
                  } elseif ($now > $endTime) {
                      $statusRealtime = 'alpha'; // Lewat jam
                  }
                @endphp

                <tr
                  class="{{ $statusRealtime == 'late' || $statusRealtime == 'absent_empty' ? 'bg-danger bg-opacity-10' : '' }}">

                  <td class="ps-4">
                    <span class="badge bg-light text-dark border">
                      {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                    </span>

                    <div class="mt-2">
                      @if ($statusRealtime == 'present')
                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Masuk</span>
                        <div style="font-size: 0.7rem;" class="text-success fw-bold mt-1">
                          @ {{ $journal->clock_in_time->format('H:i') }}
                        </div>
                      @elseif($statusRealtime == 'late')
                        <span class="badge bg-danger animate-blink"><i class="bi bi-exclamation-circle-fill me-1"></i>
                          BELUM MASUK</span>
                        <div style="font-size: 0.7rem;" class="text-danger mt-1">
                          Telat {{ number_format($startTime->floatDiffInMinutes($now), 1) }} mnt
                        </div>
                      @elseif($statusRealtime == 'absent_empty')
                        <span class="badge bg-danger animate-blink">BUTUH BADAL</span>
                      @elseif($statusRealtime == 'alpha')
                        <span class="badge bg-dark">TIDAK HADIR</span>
                        <div style="font-size: 0.7rem;" class="text-muted mt-1">Tanpa Keterangan</div>
                      @elseif($statusRealtime == 'waiting_approval')
                        <span class="badge bg-warning text-dark">Menunggu Admin</span>
                      @else
                        <span class="badge bg-light text-muted border">Belum Mulai</span>
                      @endif
                    </div>
                  </td>

                  <td>
                    <div class="fw-bold">{{ $schedule->classroom->name }}</div>
                    <small class="text-muted">{{ $schedule->subject->name }}</small>
                  </td>

                  <td>
                    <div class="fw-bold text-dark">{{ $schedule->teacher->name }}</div>

                    @if ($isAbsent)
                      <div class="mt-2 p-2 bg-white border rounded shadow-sm">

                        {{-- KASUS 1: Izin Masih Pending --}}
                        @if ($permission->status == 'pending')
                          <small class="d-block text-warning fw-bold mb-1"><i class="bi bi-envelope"></i> Pengajuan
                            Izin:</small>
                          <div class="small fst-italic mb-2 text-muted">"{{ Str::limit($permission->reason, 20) }}"</div>

                          <div class="d-flex gap-1">
                            {{-- Tombol Approve --}}
                            <form action="{{ route('academic.picket.permission.update', $permission->id) }}"
                              method="POST">
                              @csrf @method('PATCH')
                              <input type="hidden" name="status" value="approved">
                              <button class="btn btn-sm btn-success py-1 px-2" title="Setujui"><i
                                  class="bi bi-check-lg"></i> ACC</button>
                            </form>

                            {{-- Tombol Reject --}}
                            <form action="{{ route('academic.picket.permission.update', $permission->id) }}"
                              method="POST">
                              @csrf @method('PATCH')
                              <input type="hidden" name="status" value="rejected">
                              <button class="btn btn-sm btn-danger py-1 px-2" title="Tolak"><i class="bi bi-x-lg"></i>
                                Tolak</button>
                            </form>

                            @if ($permission->attachment)
                              <a href="{{ asset('storage/' . $permission->attachment) }}" target="_blank"
                                class="btn btn-sm btn-light border" title="Lihat Bukti"><i
                                  class="bi bi-file-earmark"></i></a>
                            @endif
                          </div>

                          {{-- KASUS 2: Sudah Approved --}}
                        @elseif($permission->status == 'approved')
                          <span class="badge bg-danger mb-1">IZIN: {{ strtoupper($permission->type) }}</span>
                          <div style="font-size: 0.7rem;">(Disetujui)</div>

                          {{-- KASUS 3: Ditolak --}}
                        @else
                          <span class="badge bg-secondary text-decoration-line-through">IZIN DITOLAK</span>
                          <div style="font-size: 0.7rem;">Wajib Hadir</div>
                        @endif
                      </div>
                    @endif
                  </td>

                  <td>
                    @if ($journal)
                      @if ($journal->is_substitute)
                        <div class="alert alert-warning py-2 px-2 mb-0 border-warning small">
                          <i class="bi bi-person-badge-fill me-1"></i>
                          Badal: <strong>{{ $journal->teacher->name }}</strong>
                        </div>
                      @else
                        <div class="alert alert-success py-2 px-2 mb-0 border-success small">
                          <i class="bi bi-person-check-fill me-1"></i> Guru Asli Hadir
                        </div>
                      @endif
                      <div class="text-muted small mt-1 fst-italic">
                        Materi: "{{ Str::limit($journal->topic, 20) }}"
                      </div>
                    @else
                      @if ($hasSubstitute)
                        <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded border">
                          <div>
                            <span class="badge bg-dark mb-1">Jadwal Badal:</span><br>
                            <span class="fw-bold text-dark small">{{ $substituteData->substituteTeacher->name }}</span>
                          </div>
                          <form action="{{ route('academic.picket.remove', $substituteData->id) }}" method="POST"
                            onsubmit="return confirm('Batalkan badal ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm text-danger ms-1"><i class="bi bi-x-circle-fill"></i></button>
                          </form>
                        </div>
                        <div class="text-muted small mt-1 fst-italic text-center">Menunggu Guru Badal...</div>
                      @elseif($isAbsent && $permission->status == 'approved')
                        <div class="p-2 border border-danger bg-danger bg-opacity-10 rounded">
                          <small class="text-danger fw-bold d-block mb-2"><i class="bi bi-exclamation-triangle"></i> Pilih
                            Guru Pengganti:</small>

                          <form action="{{ route('academic.picket.assign') }}" method="POST" class="d-flex gap-1">
                            @csrf
                            <input type="hidden" name="lesson_schedule_id" value="{{ $schedule->id }}">
                            <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">

                            <select name="substitute_teacher_id" class="form-select form-select-sm"
                              style="width: 140px;" required>
                              <option value="">-- Pilih --</option>
                              @foreach ($allTeachers as $t)
                                @if ($t->id != $schedule->teacher_id)
                                  <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endif
                              @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Set</button>
                          </form>
                        </div>
                      @else
                        @if ($statusRealtime == 'late')
                          <small class="text-danger fw-bold d-block text-center">Guru Terlambat!</small>
                        @else
                          <small class="text-muted d-block text-center">-</small>
                        @endif
                      @endif
                    @endif
                  </td>
                </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @empty
            <div class="text-center py-5 text-muted">
              Tidak ada jadwal pelajaran pada tanggal ini.
            </div>
          @endforelse
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

    // 3. Realtime Updates (Polling)
    setInterval(function() {
        // Jangan update jika user sedang berinteraksi dengan form (input/select)
        if (document.activeElement.tagName === 'INPUT' || 
            document.activeElement.tagName === 'SELECT' || 
            document.activeElement.tagName === 'TEXTAREA') {
            return;
        }

        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update Summary Stats
                const newStats = doc.getElementById('summary-stats');
                if (newStats) {
                    document.getElementById('summary-stats').innerHTML = newStats.innerHTML;
                }

                // Update Table Body
                doc.querySelectorAll('.realtime-body').forEach(newTbody => {
                    const currentTbody = document.getElementById(newTbody.id);
                    if (currentTbody) {
                        currentTbody.innerHTML = newTbody.innerHTML;
                    }
                });
            })
            .catch(err => console.error('Gagal memuat update realtime:', err));
    }, 5000); // Update setiap 5 detik
  </script>
@endpush
