@extends('layouts.app')
@section('title', 'Detail Laporan Kehadiran Guru')
@push('link')
@endpush
@push('styles')
<style>
  .custom-scrollbar {
    scrollbar-width: thin;
    /* Firefox */
    scrollbar-color: rgba(255, 255, 255, .4) transparent;
    /* Firefox */
  }

  /* Chrome, Edge, Safari */
  .custom-scrollbar::-webkit-scrollbar {
    width: 6px;
  }

  .custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
  }

  .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, .4);
    border-radius: 10px;
  }

  .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, .6);
  }

</style>
@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="fw-bold mb-0">Detail Absensi Guru</h5>
      <div class="text-muted">{{ $teacher->name }}</div>
    </div>
    <a href="{{ route('tahfizh.admin.reports.teacher', ['month' => \Carbon\Carbon::parse($startDate)->format('Y-m')]) }}" class="btn btn-outline-secondary rounded-pill btn-sm">
      <i class="bi bi-arrow-left"></i> Kembali ke Rekap
    </a>
  </div>

  <div class="alert alert-light border shadow-sm mb-4">
    <i class="bi bi-calendar-range me-2 text-primary"></i>
    Menampilkan data periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</strong> s.d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</strong>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-8">
      <div class="row g-2">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">
              <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                <i class="bi bi-check-circle-fill text-success fs-3"></i>
              </div>
              <div>
                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Hadir</h6>
                <h3 class="mb-0 fw-bold">{{ $journals->filter(fn($j) => !($j->original_teacher_id && $j->original_teacher_id != $teacher->id))->count() }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">
              <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                <i class="bi bi-envelope-paper-fill text-warning fs-3"></i>
              </div>
              <div>
                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Izin</h6>
                <h3 class="mb-0 fw-bold">{{ $permissions->count() }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">
              <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                <i class="bi bi-arrow-repeat text-primary fs-3"></i>
              </div>
              <div>
                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Badal</h6>
                <h3 class="mb-0 fw-bold">{{ $journals->filter(fn($j) => $j->original_teacher_id && $j->original_teacher_id != $teacher->id)->count() }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning text-white">
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-0 px-2">
          @if(isset($totalHours))
          <div class="row">
            <div class="col-md-6">
              <h6 class="text-white text-uppercase fw-bold mb-1">Total Jam Terhitung</h6>
              <h5 class="fw-bold mb-1 display-6">{{ $totalHours->total_hours ?? 0 }} <span class="fs-4">Jam</span></h5>
              <button type="button" class="btn btn-info btn-sm rounded px-1 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#hoursModal">
                <i class="bi bi-pencil-square me-1"></i> Edit jam
              </button>
            </div>
            <div class="col-md-6">
              <h6 class="text-white fw-bold">Catatan:</h6>
              <div class="px-2 py-1 rounded bg-black bg-opacity-10 text-white small custom-scrollbar" style="font-size: 0.75em; max-height: 75px; overflow: auto;">
                <i class="bi bi-journal-text me-1"></i> {{ $totalHours->notes ?: 'tidak ada catatan.......' }}
              </div>
            </div>
          </div>
          @else
          <h6 class="text-white text-uppercase fw-bold mb-2">Total Jam Halaqah</h6>
          <button type="button" class="btn btn-light btn-lg rounded-pill px-4 text-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#hoursModal">
            <i class="bi bi-calculator me-2"></i> Input Jam
          </button>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-header bg-white py-3 fw-bold">
          <i class="bi bi-check-circle-fill text-success me-2"></i> Riwayat Kehadiran (Mengajar)
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th>Tanggal & Sesi</th>
                <th>Jam Mulai</th>
                <th>Jam Masuk</th>
                <th>Status</th>
                <th class="text-center">Bukti</th>
              </tr>
            </thead>
            <tbody>
              @forelse($journals as $j)
              <tr>
                <td>
                  <div class="fw-bold">{{ \Carbon\Carbon::parse($j->date)->translatedFormat('d M Y') }}</div>
                  <small class="text-muted">{{ $j->schedule->session_name }}</small>
                </td>
                <td>
                  <span class="font-monospace fs-6">{{ \Carbon\Carbon::parse($j->schedule->start_time)->format('H:i') }}</span>
                </td>
                <td>
                  <span class="font-monospace fs-6">{{ $j->clock_in->format('H:i') }}</span>
                  @php
                  $scheduleStart = \Carbon\Carbon::parse($j->date->toDateString() . ' ' . $j->schedule->start_time);
                  @endphp
                  @if($j->clock_in->gt($scheduleStart))
                  @php
                  // Hitung selisih menit dari jam mulai yang sebenarnya
                  $lateMinutes = $scheduleStart->diffInMinutes($j->clock_in);
                  @endphp
                  <span class="badge bg-danger ms-1" style="font-size: 0.7em;"><i class="bi bi-clock"></i> {{ $lateMinutes }} menit</span>
                  @else
                  <span class="badge bg-success ms-1" style="font-size: 0.7em;">Tepat Waktu</span>
                  @endif
                </td>
                <td>
                  @if($j->original_teacher_id && $j->original_teacher_id != $teacher->id)
                  <span class="badge bg-primary">Badal</span>
                  <div style="font-size: 10px;" class="text-muted">Ganti Ust. {{ $j->original_teacher_id }}</div>
                  @else
                  <span class="badge bg-success">Hadir</span>
                  @endif
                </td>
                <td class="text-center">
                  @if ($j->photo_proof || ($j->latitude && $j->longitude))
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#proofModal{{ $j->id }}">
                    <i class="bi bi-eye-fill me-1"></i> Lihat
                  </button>

                  <!-- Modal -->
                  <div class="modal fade" id="proofModal{{ $j->id }}" tabindex="-1" aria-labelledby="proofModalLabel{{ $j->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content rounded-4">
                        <div class="modal-header">
                          <h5 class="modal-title" id="proofModalLabel{{ $j->id }}">Bukti Kehadiran</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          @if ($j->photo_proof != null)
                          <p class="fw-bold small text-muted mb-2">FOTO BUKTI</p>
                          <a href="{{ asset('storage/' . $j->photo_proof) }}" target="_blank">
                            <img src="{{ asset('storage/' . $j->photo_proof) }}" class="img-fluid rounded shadow-sm mb-3" alt="Bukti Foto">
                          </a>
                          @else
                          <span class="text-muted small">Foto telah dihapus. Pemeliharaan sistem.</span>
                          @endif

                          @if ($j->latitude && $j->longitude)
                          <p class="fw-bold small text-muted mb-2">LOKASI GPS</p>
                          <a href="https://www.google.com/maps/search/?api=1&query={{ $j->latitude }},{{ $j->longitude }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="bi bi-geo-alt-fill me-2"></i> Buka di Google Maps
                          </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                  @else
                  <span class="text-muted small">-</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data kehadiran.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-header bg-white py-3 fw-bold">
          <i class="bi bi-envelope-paper-fill text-warning me-2"></i> Riwayat Izin
        </div>
        <ul class="list-group list-group-flush">
          @forelse($permissions as $p)
          <li class="list-group-item p-3">
            <div class="d-flex justify-content-between mb-1">
              <span class="fw-bold small">{{ \Carbon\Carbon::parse($p->date)->translatedFormat('d M Y') }}</span>
              @if($p->status == 'approved')
              <span class="badge bg-success">Disetujui</span>
              @else
              <span class="badge bg-secondary">{{ $p->status }}</span>
              @endif
            </div>
            <p class="mb-0 small text-muted fst-italic">"{{ $p->reason }}"</p>
          </li>
          @empty
          <li class="list-group-item text-center text-muted py-4">Tidak ada data izin.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Modal Input Jam Halaqah -->
<div class="modal fade" id="hoursModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Input Total Jam Halaqah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tahfizh.admin.reports.store_hours', $teacher->id) }}" method="POST">
        @csrf
        <input type="hidden" name="period" value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m') }}">
        <div class="modal-body">
          <div class="mb-3">
            <label for="total_hours" class="form-label text-muted small fw-bold">TOTAL JAM</label>
            <div class="input-group">
              <input type="number" class="form-control" id="total_hours" name="total_hours" value="{{ isset($totalHours) ? $totalHours->total_hours : ($journals->count() + $permissions->count()) }}" required>
              <span class="input-group-text bg-light">Jam</span>
            </div>
            <label for="note" class="form-label text-muted small fw-bold">CATATAN</label>
            <textarea class="form-control" id="note" name="note" rows="6">{{ old('note', $totalHours->notes ?? '') }}</textarea>
            <div class="form-text">Estimasi otomatis (Hadir + Badal + Izin): {{ $journals->count() + $permissions->count() }}. Silakan sesuaikan manual (misal: hanya izin sakit/tugas).</div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-info text-white rounded-pill px-4">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  @if(session('success'))
  Swal.fire({
    icon: 'success'
    , title: 'Berhasil!'
    , text: "{{ session('success') }}"
    , timer: 3000
    , showConfirmButton: false
  });
  @endif

  @if(session('error'))
  Swal.fire({
    icon: 'error'
    , title: 'Gagal!'
    , text: "{{ session('error') }}"
  , });
  @endif

</script>
@endpush
