@extends('layouts.app')
@section('title', 'Input Nilai Akademik')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
      <div>
        <h4 class="fw-bold mb-1">Input Nilai Akademik</h4>
        <p class="text-muted small mb-0">Klik pada nama kelas untuk melihat daftar pelajaran.</p>
      </div>

      <div class="input-group shadow-sm" style="max-width: 400px;">
        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control border-start-0 ps-0"
          placeholder="Cari Kelas atau Mapel...">
      </div>
    </div>

    <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionClassroom">
      @forelse($groupedCourses as $classId => $courses)
        @php
          // Ambil info kelas dari item pertama
          $classroom = $courses->first()->classroom;
          $totalMapel = $courses->count();

          // Hitung Progress Global Kelas (Rata-rata)
          // Agar di Header kelihatan kelas mana yang "Belum Disentuh"
          $totalSiswaKelas = $classroom->students->count();
          
          // Hitung total nilai masuk yang valid (hanya siswa aktif)
          $studentIds = $classroom->students->pluck('id')->toArray();
          $totalNilaiMasuk = $courses->sum(function($c) use ($studentIds) {
              return $c->grades->whereIn('student_id', $studentIds)->count();
          });

          $targetNilai = $totalSiswaKelas * $totalMapel; // Target ideal (semua siswa x semua mapel)

          $classProgress = $targetNilai > 0 ? round(($totalNilaiMasuk / $targetNilai) * 100) : 0;

          // Warna Badge Header
          $badgeClass = 'bg-light text-dark border';
          if ($classProgress >= 100) {
              $badgeClass = 'bg-success text-white';
          } elseif ($classProgress >= 50) {
              $badgeClass = 'bg-warning text-dark';
          }
        @endphp

        <div class="accordion-item border-0 border-bottom class-item">
          <h2 class="accordion-header" id="heading{{ $classId }}">
            <button class="accordion-button collapsed py-3 bg-white" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapse{{ $classId }}">
              <div class="d-flex align-items-center w-100 pe-3">
                <div
                  class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                  style="width: 45px; height: 45px;">
                  <i class="bi bi-journal-text fs-5"></i>
                </div>

                <div class="flex-grow-1">
                  <h6 class="fw-bold mb-0 text-dark class-name">{{ $classroom->name }}</h6>
                  <small class="text-muted class-detail">
                    {{ $classroom->level->name }} &bull; {{ $totalMapel }} Mapel &bull; {{ $totalSiswaKelas }} Siswa
                  </small>
                </div>

                <div class="text-end d-none d-md-block">
                  <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                    {{ $classProgress }}% Tuntas
                  </span>
                </div>
              </div>
            </button>
          </h2>

          <div id="collapse{{ $classId }}" class="accordion-collapse collapse" data-bs-parent="#accordionClassroom">
            <div class="accordion-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-light bg-opacity-10">
                  <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                      <th class="ps-5" style="width: 35%;">Mata Pelajaran</th>
                      <th class="text-center" style="width: 10%;">KKM</th>
                      <th style="width: 35%;">Progress Penilaian</th>
                      <th class="text-end pe-4" style="width: 20%;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($courses as $course)
                      @php
                        $jmlSiswa = $course->classroom->students->count();
                        // Hitung nilai hanya untuk siswa yang ada di kelas ini
                        $jmlDinilai = $course->grades->whereIn('student_id', $studentIds)->count();

                        // Kalkulasi Persen per Mapel
                        if ($jmlSiswa > 0) {
                            $persen = round(($jmlDinilai / $jmlSiswa) * 100);
                        } else {
                            $persen = 0; // Jika tidak ada siswa
                        }

                        // Warna Progress Bar
                        $barColor = 'bg-primary';
                        if ($persen >= 100) {
                            $barColor = 'bg-success';
                        } elseif ($persen == 0) {
                            $barColor = 'bg-secondary';
                        }
                      @endphp
                      <tr class="subject-row">
                        <td class="ps-5">
                          <div class="fw-bold text-dark subject-name">{{ $course->subject->name }}</div>
                          <small class="text-muted" style="font-size: 0.75rem;">
                            {{ $course->subject->code }} <span class="mx-1">&bull;</span> Muatan
                            {{ $course->subject->group }}
                          </small>
                        </td>
                        <td class="text-center fw-bold text-secondary">{{ $course->kkm }}</td>
                        <td>
                          <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">{{ $jmlDinilai }} / {{ $jmlSiswa }} Siswa</span>
                            <span
                              class="fw-bold {{ $persen == 100 ? 'text-success' : 'text-dark' }}">{{ $persen }}%</span>
                          </div>
                          <div class="progress rounded-pill" style="height: 6px;">
                            <div class="progress-bar {{ $barColor }}" role="progressbar"
                              style="width: {{ $persen }}%"></div>
                          </div>
                        </td>
                        <td class="text-end pe-4">
                          <a href="{{ route('grading.teacher.show', $course->id) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold">
                            Input Nilai
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-5">
          <div class="mb-3">
            <i class="bi bi-clipboard-x display-1 text-muted opacity-25"></i>
          </div>
          <h5 class="fw-bold text-muted">Belum Ada Jadwal Mengajar</h5>
          <p class="text-muted small">Anda belum di-plotting ke kelas manapun pada semester ini.<br>Silakan hubungi Admin
            Kurikulum.</p>
        </div>
      @endforelse
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      let classItems = document.querySelectorAll('.class-item');

      classItems.forEach(function(item) {
        // Ambil teks Nama Kelas & Detailnya
        let className = item.querySelector('.class-name').innerText.toLowerCase();
        let classDetail = item.querySelector('.class-detail').innerText.toLowerCase();

        // Cek juga nama pelajaran di dalam tabel (opsional, biar makin canggih)
        let subjects = item.querySelectorAll('.subject-name');
        let foundSubject = false;
        subjects.forEach(sub => {
          if (sub.innerText.toLowerCase().includes(filter)) foundSubject = true;
        });

        // Logika: Tampilkan jika Nama Kelas COCOK atau ada Pelajaran yang COCOK
        if (className.includes(filter) || classDetail.includes(filter) || foundSubject) {
          item.style.display = '';

          // Fitur UX: Jika user mencari nama pelajaran, otomatis buka accordion-nya
          if (foundSubject && filter.length > 2) {
            let collapseElement = item.querySelector('.accordion-collapse');
            let bsCollapse = new bootstrap.Collapse(collapseElement, {
              toggle: false
            });
            bsCollapse.show();
          }
        } else {
          item.style.display = 'none';
        }
      });
    });
  </script>
@endpush
