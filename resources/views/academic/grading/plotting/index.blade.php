@extends('layouts.app')
@section('title', 'Pengaturan KBM (Plotting Guru)')
@push('link')
@endpush
@push('styles')
<style>
input, select {
  cursor: pointer;
}
</style>
@endpush
@section('content')
  <div class="container py-4">
    <div class="mb-4">
      <h4 class="fw-bold mb-1">Pengaturan KBM (Plotting Guru)</h4>
      <p class="text-muted small">Tentukan Mata Pelajaran, Guru Pengampu, dan KKM untuk setiap kelas.</p>
    </div>

    <div class="row">
      <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded">
          <div class="card-header bg-white fw-bold py-3">Pilih Kelas</div>
          <div class="list-group list-group-flush rounded-bottom">
            @forelse($classrooms as $cls)
              <a href="{{ route('grading.plotting.index', ['classroom_id' => $cls->id]) }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedClassroom?->id == $cls->id ? 'active fw-bold' : '' }}">
                {{ $cls->name }}
                @if ($selectedClassroom?->id == $cls->id)
                  <i class="bi bi-chevron-right"></i>
                @endif
              </a>
            @empty
              <div class="p-3 text-muted small text-center">Belum ada kelas aktif.</div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-md-9">
        @if ($selectedClassroom)
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <div>
                <h6 class="fw-bold mb-0">Plotting: {{ $selectedClassroom->name }}</h6>
                <small class="text-muted">{{ $activeYear->name ?? '' }}</small>
              </div>
              {{-- Tombol Import Excel nanti ditaruh disini --}}
              {{-- <button class="btn btn-sm btn-success disabled" title="Fitur akan datang"><i
                  class="bi bi-file-earmark-excel me-1"></i> Import Excel</button> --}}
            </div>

            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th width="5%" class="text-center">Status</th>
                      <th class="ps-3">Mata Pelajaran</th>
                      <th width="30%">Guru Pengampu</th>
                      <th width="15%">KKM</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $currentGroup = ''; @endphp

                    @foreach ($subjects as $subject)
                      @if ($currentGroup != $subject->group)
                        @php $currentGroup = $subject->group; @endphp
                        <tr class="table-secondary">
                          <td colspan="4" class="fw-bold ps-4 small text-uppercase">Muatan {{ $currentGroup }}</td>
                        </tr>
                      @endif

                      @php
                        $courseData = $courses->get($subject->id);
                        $isActive = $courseData ? true : false; // Cek apakah sudah ada di DB
                      @endphp

                      <tr class="plotting-row {{ $isActive ? '' : 'bg-light opacity-75' }}" 
                          data-classroom-id="{{ $selectedClassroom->id }}" 
                          data-subject-id="{{ $subject->id }}">
                          <td class="align-middle text-center">
                            <div class="form-check form-switch d-inline-block">
                              <input class="form-check-input plotting-active" type="checkbox" name="is_active" value="1"
                                {{ $isActive ? 'checked' : '' }}>
                            </div>
                          </td>

                          <td class="ps-3 align-middle">
                            <div class="fw-bold text-dark">{{ $subject->name }}</div>
                            <small class="text-muted">{{ $subject->code }}</small>
                          </td>

                          <td class="align-middle">
                            <select name="teacher_id" class="form-select form-select-sm plotting-input"
                              {{ !$isActive ? 'disabled' : '' }}>
                              <option value="">-- Pilih Guru --</option>
                              @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                  {{ $courseData?->teacher_id == $teacher->id ? 'selected' : '' }}>
                                  {{ $teacher->name }}
                                </option>
                              @endforeach
                            </select>
                          </td>

                          <td class="align-middle">
                            <input type="number" name="kkm" class="form-control form-control-sm text-center plotting-input"
                              value="{{ $courseData?->kkm ?? 75 }}" min="0" max="100"
                              {{ !$isActive ? 'disabled' : '' }}>
                          </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="alert alert-info mt-3 small border-0 shadow-sm">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Tips:</strong> Semua perubahan akan disimpan secara otomatis. Pastikan untuk memilih guru dan
            mengisi nilai KKM mapel tersebut.
          </div>
        @else
          {{-- State Kosong (Belum pilih kelas) --}}
          <div class="card border-0 shadow-sm rounded py-5 text-center">
            <div class="card-body">
              <img src="https://cdn-icons-png.flaticon.com/512/10302/10302224.png" width="80" class="opacity-50 mb-3">
              <h5 class="fw-bold text-muted">Pilih Kelas Terlebih Dahulu</h5>
              <p class="text-muted small">Silakan pilih kelas di menu sebelah kiri untuk mengatur jadwal/plotting guru.
              </p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const updateUrl = "{{ route('grading.plotting.update') }}";

    // Fungsi Toast Sederhana (Menggunakan SweetAlert jika ada, atau console)
    const showToast = (icon, title) => {
        if (typeof Swal !== 'undefined') {
            Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true
            }).fire({ icon: icon, title: title });
        }
    };

    // Handler untuk semua input di dalam row
    const handleUpdate = async (row) => {
        const classroomId = row.dataset.classroomId;
        const subjectId = row.dataset.subjectId;
        const isActiveInput = row.querySelector('.plotting-active');
        const teacherInput = row.querySelector('select[name="teacher_id"]');
        const kkmInput = row.querySelector('input[name="kkm"]');

        const isActive = isActiveInput.checked;

        // Update UI State (Disable/Enable inputs)
        if (isActive) {
            row.classList.remove('bg-light', 'opacity-75');
            teacherInput.disabled = false;
            kkmInput.disabled = false;
        } else {
            row.classList.add('bg-light', 'opacity-75');
            teacherInput.disabled = true;
            kkmInput.disabled = true;
        }

        // Prepare Data
        const payload = {
            classroom_id: classroomId,
            subject_id: subjectId,
            is_active: isActive ? 1 : 0,
            teacher_id: teacherInput.value,
            kkm: kkmInput.value
        };

        try {
            const res = await fetch(updateUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            if(res.ok) showToast('success', 'Disimpan');
            else showToast('error', 'Gagal menyimpan');
        } catch (e) { console.error(e); }
    };

    // Attach Event Listeners
    document.querySelectorAll('.plotting-active, .plotting-input').forEach(el => {
        el.addEventListener('change', function() {
            handleUpdate(this.closest('.plotting-row'));
        });
    });
});
</script>
@endpush
