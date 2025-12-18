@extends('layouts.app')
@section('title', 'Input Nilai Akademik')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Input Nilai Akademik</h4>
        <p class="text-muted small mb-0">Pilih kelas untuk melihat mata pelajaran yang harus dinilai.</p>
      </div>

      <input type="text" id="searchClass" class="form-control" style="max-width: 250px;" placeholder="Cari Kelas...">
    </div>

    <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionClassroom">
      @forelse($groupedCourses as $classId => $courses)
        @php
          // Ambil info kelas dari item pertama di group
          $classroom = $courses->first()->classroom;
          $totalMapel = $courses->count();

          // Hitung Progress Rata-rata Kelas (Opsional, untuk visual header)
          // Berapa persen mapel yang sudah selesai dinilai di kelas ini?
          $completedSubjects = 0;
          foreach ($courses as $c) {
              if ($c->classroom->students->count() > 0 && $c->grades_count >= $c->classroom->students->count()) {
                  $completedSubjects++;
              }
          }
          $classProgress = $totalMapel > 0 ? round(($completedSubjects / $totalMapel) * 100) : 0;
        @endphp

        <div class="accordion-item border-0 border-bottom class-item">
          <h2 class="accordion-header" id="heading{{ $classId }}">
            <button class="accordion-button collapsed py-3 bg-white" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapse{{ $classId }}">
              <div class="d-flex align-items-center w-100 pe-3">
                <div
                  class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                  style="width: 40px; height: 40px;">
                  <i class="bi bi-people-fill"></i>
                </div>

                <div class="flex-grow-1">
                  <h6 class="fw-bold mb-0 text-dark class-name">{{ $classroom->name }}</h6>
                  <small class="text-muted">{{ $classroom->level->name }} &bull; {{ $totalMapel }} Mata
                    Pelajaran</small>
                </div>

                <div class="text-end d-none d-md-block">
                  <span
                    class="badge {{ $classProgress == 100 ? 'bg-success' : 'bg-light text-dark border' }} rounded-pill">
                    {{ $classProgress }}% Selesai
                  </span>
                </div>
              </div>
            </button>
          </h2>

          <div id="collapse{{ $classId }}" class="accordion-collapse collapse" data-bs-parent="#accordionClassroom">
            <div class="accordion-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-light bg-opacity-10">
                  <thead class="bg-light text-secondary small">
                    <tr>
                      <th class="ps-5">Mata Pelajaran</th>
                      <th class="text-center">KKM</th>
                      <th style="width: 30%;">Status Penilaian</th>
                      <th class="text-end pe-4">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($courses as $course)
                      @php
                        $totalSiswa = $course->classroom->students->count();
                        $sudahDinilai = $course->grades_count;
                        $persen = $totalSiswa > 0 ? round(($sudahDinilai / $totalSiswa) * 100) : 0;

                        $color = 'bg-primary';
                        if ($persen >= 100) {
                            $color = 'bg-success';
                        } elseif ($persen == 0) {
                            $color = 'bg-secondary';
                        }
                      @endphp
                      <tr>
                        <td class="ps-5">
                          <div class="fw-bold">{{ $course->subject->name }}</div>
                          <small class="text-muted text-uppercase" style="font-size: 0.7rem;">
                            {{ $course->subject->code }} &bull; Kelompok {{ $course->subject->group }}
                          </small>
                        </td>
                        <td class="text-center fw-bold text-secondary">{{ $course->kkm }}</td>
                        <td>
                          <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">{{ $sudahDinilai }} / {{ $totalSiswa }} Siswa</span>
                            <span
                              class="fw-bold {{ $persen == 100 ? 'text-success' : 'text-primary' }}">{{ $persen }}%</span>
                          </div>
                          <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $color }}" style="width: {{ $persen }}%"></div>
                          </div>
                        </td>
                        <td class="text-end pe-4">
                          <a href="{{ route('grading.teacher.show', $course->id) }}"
                            class="btn btn-sm btn-primary rounded-pill px-3">
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
          <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="60" class="opacity-25 mb-3">
          <h5 class="fw-bold text-muted">Belum Ada Jadwal</h5>
          <p class="text-muted small">Anda belum di-plotting ke kelas manapun.</p>
        </div>
      @endforelse
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    // Script Search Filter Accordion
    document.getElementById('searchClass').addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      let items = document.querySelectorAll('.class-item');

      items.forEach(function(item) {
        let name = item.querySelector('.class-name').innerText.toLowerCase();
        if (name.includes(filter)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  </script>
@endpush
