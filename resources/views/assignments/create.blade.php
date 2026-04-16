@extends('layouts.app')
@section('title', 'Assignments Room')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="card col-md-8 mx-auto">
    <div class="card-header bg-primary text-white">
      <h4>Penempatan & Atur Ulang Kamar</h4>
      <small>Tahun Ajaran: {{ $activeYear->name }} ({{ $activeYear->semester }})</small>
    </div>
    <div class="card-body">

      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <form action="{{ route('assignments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">

        <div class="mb-3">
          <label class="form-label fw-bold">Pilih Santri</label>
          <input type="text" id="searchStudent" class="form-control mb-3" placeholder="Cari nama santri atau NIS...">
          
          <ul class="nav nav-tabs" id="studentTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="no-room-tab" data-bs-toggle="tab" data-bs-target="#no-room-pane" type="button" role="tab">
                Belum Ada Kamar <span class="badge bg-danger rounded-pill ms-1">{{ $studentsNoRoom->count() }}</span>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="has-room-tab" data-bs-toggle="tab" data-bs-target="#has-room-pane" type="button" role="tab">
                Sudah Ada Kamar <span class="badge bg-success rounded-pill ms-1">{{ $studentsHasRoom->count() }}</span>
              </button>
            </li>
          </ul>

          <div class="tab-content border border-top-0 p-3 rounded-bottom" id="studentTabsContent" style="max-height: 500px; overflow-y: auto;">
            
            {{-- TAB 1: BELUM ADA KAMAR --}}
            <div class="tab-pane fade show active" id="no-room-pane" role="tabpanel">
              <div class="accordion" id="accordionNoRoom">
                @forelse($studentsNoRoom->groupBy('class_group') as $classGroup => $groupStudents)
                  @php $groupId = 'nr_' . \Illuminate\Support\Str::slug($classGroup ?? 'no-class') . '_' . $loop->index; @endphp
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed py-2 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}">
                        <strong>Kelas: {{ $classGroup ?: 'Belum Ada Kelas' }}</strong>
                        <span class="badge bg-secondary ms-2">{{ $groupStudents->count() }}</span>
                      </button>
                    </h2>
                    <div id="{{ $groupId }}" class="accordion-collapse collapse" data-bs-parent="#accordionNoRoom">
                      <div class="accordion-body">
                        <div class="row">
                          @foreach ($groupStudents as $student)
                            <div class="col-md-6 student-item">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="nr_{{ $student->id }}">
                                <label class="form-check-label" for="nr_{{ $student->id }}">
                                  {{ $student->name }} <small class="text-muted">({{ $student->nis }})</small>
                                </label>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="text-center text-muted py-3">Semua santri sudah memiliki kamar.</div>
                @endforelse
              </div>
            </div>

            {{-- TAB 2: SUDAH ADA KAMAR --}}
            <div class="tab-pane fade" id="has-room-pane" role="tabpanel">
              <div class="alert alert-info py-2 mb-2"><small><i class="bi bi-info-circle"></i> Checklist santri di sini untuk memindahkan mereka ke kamar baru.</small></div>
              <div class="accordion" id="accordionHasRoom">
                @forelse($studentsHasRoom->groupBy('class_group') as $classGroup => $groupStudents)
                  @php $groupId = 'hr_' . \Illuminate\Support\Str::slug($classGroup ?? 'no-class') . '_' . $loop->index; @endphp
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed py-2 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}">
                        <strong>Kelas: {{ $classGroup ?: 'Belum Ada Kelas' }}</strong>
                        <span class="badge bg-secondary ms-2">{{ $groupStudents->count() }}</span>
                      </button>
                    </h2>
                    <div id="{{ $groupId }}" class="accordion-collapse collapse" data-bs-parent="#accordionHasRoom">
                      <div class="accordion-body">
                        <div class="row">
                          @foreach ($groupStudents as $student)
                            @php $currentRoom = $student->roomAssignments->first()->room; @endphp
                            <div class="col-md-6 mb-1 student-item">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="hr_{{ $student->id }}">
                                <label class="form-check-label" for="hr_{{ $student->id }}">
                                  {{ $student->name }} 
                                  <br><small class="text-primary fst-italic"> <i class="ti ti-bed"></i> {{ $currentRoom->dorm->name ?? '-' }} - {{ $currentRoom->name ?? '-' }}</small>
                                </label>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="text-center text-muted py-3">Belum ada santri yang menempati kamar.</div>
                @endforelse
              </div>
            </div>

          </div>
          <div class="form-text">Anda dapat memilih santri dari kedua tab sekaligus untuk ditempatkan ke kamar tujuan yang sama.</div>
        </div>

        <div class="mb-3">
          <label class="fw-bold">Pilih Kamar Tujuan</label>
          <select name="room_id" class="form-select" required>
            <option value="">-- Pilih Kamar --</option>
            @foreach ($rooms as $room)
              @php
                $filled = $room->assignments()->where('academic_year_id', $activeYear->id)->count();
                $remaining = $room->capacity - $filled;
              @endphp

              <option value="{{ $room->id }}" {{ $remaining <= 0 ? 'disabled' : '' }}>
                {{ $room->dorm->name }} - {{ $room->name }} 
                ({{ $room->warden->nama ?? 'Tanpa Wali' }}) - Sisa: {{ $remaining }} bed
              </option>
            @endforeach
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Penempatan</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
@endsection
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchStudent');
    const accordionItems = document.querySelectorAll('.accordion-item');

    searchInput.addEventListener('input', function (e) {
      const searchTerm = e.target.value.toLowerCase();

      accordionItems.forEach(item => {
        const students = item.querySelectorAll('.student-item');
        let hasVisibleStudent = false;

        students.forEach(student => {
          const text = student.textContent.toLowerCase();
          if (text.includes(searchTerm)) {
            student.style.display = 'block';
            hasVisibleStudent = true;
          } else {
            student.style.display = 'none';
          }
        });

        // Tampilkan/Sembunyikan Group Kelas beserta Accordion-nya
        if (hasVisibleStudent) {
          item.style.display = 'block';
          if (searchTerm !== '') {
            item.querySelector('.accordion-collapse')?.classList.add('show');
            item.querySelector('.accordion-button')?.classList.remove('collapsed');
          }
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
</script>
@endpush
