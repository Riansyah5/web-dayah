@extends('layouts.app')
@section('title', 'Detail Laporan Kinerja Guru')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-body">
            <div class="container py-4">

              <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                  <a href="{{ route('academic.report.teacher', ['month' => $month, 'year' => $year]) }}"
                    class="btn btn-light rounded-circle me-3">
                    <i class="bi bi-arrow-left"></i>
                  </a>
                  <div>
                    <h4 class="fw-bold mb-0">{{ $teacher->name }}</h4>
                    <p class="text-muted small mb-0">
                      Laporan Periode: {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
                    </p>
                  </div>
                </div>

                <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4">
                  <i class="bi bi-printer me-2"></i> Cetak Laporan
                </button>
              </div>

              <div class="row g-3 mb-4">
                <div class="col-md-4">
                  <div class="card border-0 shadow-sm bg-primary text-white h-100 rounded-4">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h2 class="fw-bold mb-0">{{ $summary['total_teaching'] }} <span
                              class="fs-6 fw-normal">Sesi</span></h2>
                          <small class="opacity-75">Total Mengajar Reguler</small>
                        </div>
                        <i class="bi bi-person-video3 fs-1 opacity-25"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card border-0 shadow-sm bg-warning text-dark h-100 rounded-4">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h2 class="fw-bold mb-0">{{ $summary['total_substitute'] }} <span
                              class="fs-6 fw-normal">Sesi</span></h2>
                          <small class="opacity-75">Total Mengajar Badal (Pengganti)</small>
                        </div>
                        <i class="bi bi-arrow-repeat fs-1 opacity-25"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card border-0 shadow-sm bg-danger text-white h-100 rounded-4">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h2 class="fw-bold mb-0">{{ $summary['total_absent'] }} <span class="fs-6 fw-normal">Hari</span>
                          </h2>
                          <small class="opacity-75">Total Izin / Sakit</small>
                        </div>
                        <i class="bi bi-calendar-x fs-1 opacity-25"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                  <ul class="nav nav-pills card-header-pills" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active fw-bold" data-bs-toggle="tab" href="#teaching">
                        <i class="bi bi-journal-check me-2"></i> Riwayat Jurnal KBM
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link fw-bold" data-bs-toggle="tab" href="#permission">
                        <i class="bi bi-envelope-paper me-2"></i> Riwayat Izin
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="card-body p-4">
                  <div class="tab-content">

                    <div class="tab-pane fade show active" id="teaching">
                      <div class="table-responsive">
                        <table class="table table-hover align-middle">
                          <thead class="bg-light">
                            <tr>
                              <th>Tanggal & Jam</th>
                              <th>Kelas & Mapel</th>
                              <th>Materi (Topik)</th>
                              <th>Status</th>
                              <th>Bukti</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($journals as $j)
                              <tr>
                                <td>
                                  <div class="fw-bold">{{ $j->date->format('d M Y') }}</div>
                                  <small class="text-muted">
                                    Masuk: {{ $j->clock_in_time->format('H:i') }}
                                    {{-- Indikator Terlambat (Opsional Logic) --}}
                                    @if ($j->clock_in_time->format('H:i') > $j->lessonSchedule->start_time)
                                      <span class="text-danger fw-bold" title="Terlambat">•</span>
                                    @endif
                                  </small>
                                </td>
                                <td>
                                  <div class="fw-bold">{{ $j->lessonSchedule->subject->name }}</div>
                                  <span class="badge bg-light text-dark border">
                                    Kelas {{ $j->lessonSchedule->classroom->name }}
                                  </span>
                                </td>
                                <td>
                                  <div style="max-width: 250px;">
                                    {{ Str::limit($j->topic, 50) }}
                                  </div>
                                  @if ($j->notes)
                                    <small
                                      class="text-muted d-block fst-italic">"{{ Str::limit($j->notes, 30) }}"</small>
                                  @endif
                                </td>
                                <td>
                                  @if ($j->is_substitute)
                                    <span class="badge bg-warning text-dark">BADAL</span>
                                  @else
                                    <span class="badge bg-success bg-opacity-10 text-success">REGULER</span>
                                  @endif
                                </td>
                                <td>
                                  @if ($j->photo_proof)
                                    <a href="{{ asset('storage/' . $j->photo_proof) }}" target="_blank"
                                      class="btn btn-sm btn-outline-primary rounded-pill">
                                      <i class="bi bi-image"></i> Foto
                                    </a>
                                  @else
                                    <span class="text-muted small">-</span>
                                  @endif
                                </td>
                              </tr>
                            @empty
                              <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                  Tidak ada aktivitas mengajar bulan ini.
                                </td>
                              </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="permission">
                      <div class="table-responsive">
                        <table class="table table-hover align-middle">
                          <thead class="bg-light">
                            <tr>
                              <th>Tanggal</th>
                              <th>Tipe Izin</th>
                              <th>Alasan</th>
                              <th>Status Approval</th>
                              <th>Lampiran</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($permissions as $p)
                              <tr>
                                <td>{{ $p->date->format('l, d F Y') }}</td>
                                <td>
                                  @if ($p->type == 'sick')
                                    <span class="badge bg-danger">SAKIT</span>
                                  @elseif($p->type == 'duty')
                                    <span class="badge bg-info text-dark">DINAS</span>
                                  @else
                                    <span class="badge bg-secondary">IZIN PRIBADI</span>
                                  @endif
                                </td>
                                <td>{{ $p->reason }}</td>
                                <td>
                                  @if ($p->status == 'approved')
                                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>
                                      Disetujui</span>
                                  @elseif($p->status == 'rejected')
                                    <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i>
                                      Ditolak</span>
                                  @else
                                    <span class="text-warning fw-bold"><i class="bi bi-clock-fill me-1"></i>
                                      Menunggu</span>
                                  @endif
                                </td>
                                <td>
                                  @if ($p->attachment)
                                    <a href="{{ asset('storage/' . $p->attachment) }}" target="_blank"
                                      class="text-decoration-none">
                                      <i class="bi bi-paperclip"></i> Lihat Surat
                                    </a>
                                  @else
                                    -
                                  @endif
                                </td>
                              </tr>
                            @empty
                              <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                  Alhamdulillah, tidak ada riwayat absen/izin bulan ini.
                                </td>
                              </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">

        @php
          // use App\Models\Teacher;
          // $teacher = Teacher::where('name', Auth::user()->name)->first();

          // Cek apakah sudah ada data evaluasi di database?
          $eval = \App\Models\TeacherMonthlyEvaluation::where('teacher_id', $teacher->id)
              ->where('month', $month)
              ->where('year', $year)
              ->first();

          $isApproved = $eval && $eval->is_approved;
        @endphp

        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px; z-index: 1;">
          <div class="card-header bg-dark text-white py-3 rounded-top-4">
            <h6 class="fw-bold mb-0"><i class="bi bi-trophy me-2"></i> Evaluasi Kinerja</h6>
          </div>
          <div class="card-body p-4">

            @if ($isApproved)
              <div class="alert alert-success text-center">
                <i class="bi bi-lock-fill fs-1 mb-2"></i><br>
                <strong>Laporan Terkunci</strong><br>
                Disetujui oleh: {{ $eval->approver->name ?? '-' }}<br>
                <small class="text-muted">{{ \Carbon\Carbon::parse($eval->approved_at)->format('d M Y H:i') }}</small>
              </div>

              <div class="mb-3">
                <label class="small text-muted fw-bold">Rating Diberikan:</label>
                <div class="text-warning fs-4">
                  @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $eval->rating ? '-fill' : '' }}"></i>
                  @endfor
                </div>
              </div>

              <div class="mb-3">
                <label class="small text-muted fw-bold">Catatan Kepala Sekolah:</label>
                <div class="p-3 bg-light rounded fst-italic border">
                  "{{ $eval->headmaster_note }}"
                </div>
              </div>

              <button class="btn btn-outline-secondary w-100 btn-sm" disabled>Hubungi Admin untuk edit</button>
            @else
              <form action="{{ route('academic.report.teacher.evaluate', $teacher->id) }}" method="POST">
                @csrf
                <input type="hidden" name="month" value="{{ (int) $month }}">
                <input type="hidden" name="year" value="{{ (int) $year }}">
                <input type="hidden" name="action" id="actionInput">

                <div class="mb-3">
                  <label class="form-label fw-bold">Berikan Rating Bulanan</label>
                  <div class="rating-input d-flex gap-2 justify-content-center mb-2">
                    <select name="rating" class="form-select text-center fw-bold text-warning" required>
                      <option value="5" {{ ($eval->rating ?? 5) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (Sangat Baik)</option>
                      <option value="4" {{ ($eval->rating ?? 5) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (Baik)</option>
                      <option value="3" {{ ($eval->rating ?? 5) == 3 ? 'selected' : '' }}>⭐⭐⭐ (Cukup)</option>
                      <option value="2" {{ ($eval->rating ?? 5) == 2 ? 'selected' : '' }}>⭐⭐ (Kurang)</option>
                      <option value="1" {{ ($eval->rating ?? 5) == 1 ? 'selected' : '' }}>⭐ (Sangat Kurang)</option>
                    </select>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Catatan Evaluasi</label>
                  <textarea name="headmaster_note" class="form-control" rows="4" placeholder="Berikan apresiasi atau teguran...">{{ $eval->headmaster_note ?? '' }}</textarea>
                </div>

                <div class="d-grid gap-2">
                  <button type="button" id="btnSave" class="btn btn-light border">
                    Simpan Draft
                  </button>
                  <button type="button" id="btnApprove" class="btn btn-primary fw-bold">
                    <i class="bi bi-check-circle-fill me-2"></i> Setujui & Kunci
                  </button>
                </div>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
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

    @if ($errors->any())
      Swal.fire({
        icon: 'error',
        title: 'Gagal Menyimpan',
        html: '<ul class="text-start">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
      });
    @endif

    const btnApprove = document.getElementById('btnApprove');
    const actionInput = document.getElementById('actionInput');

    if (btnApprove) {
      btnApprove.addEventListener('click', function() {
        const form = this.closest('form');
        Swal.fire({
          title: 'Konfirmasi Persetujuan',
          text: "Apakah Anda yakin? Data akan dikunci dan siap untuk penggajian.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#0d6efd',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Setujui!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            actionInput.value = 'approve';
            form.submit();
          }
        });
      });
    }

    const btnSave = document.getElementById('btnSave');
    if (btnSave) {
      btnSave.addEventListener('click', function() {
        actionInput.value = 'save';
        this.closest('form').submit();
      });
    }
  </script>
@endpush
