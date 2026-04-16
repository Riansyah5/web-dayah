@extends('layouts.app')
@section('title', 'Buat Perizinan Santri')
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
            <h5 class="fw-bold mb-0">Buat Surat Izin</h5>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('permissions.store') }}" method="POST">
              @csrf

              <div class="mb-3">
                <label class="form-label fw-bold">Pilih Santri</label>
                <input type="text" id="searchStudent" class="form-control mb-3" placeholder="Cari nama santri atau NIS...">
                
                <div class="accordion" id="accordionStudents" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                  @foreach ($students->groupBy('class_group')->sortKeys() as $classGroup => $groupStudents)
                    @php $groupId = 'class_' . \Illuminate\Support\Str::slug($classGroup ?? 'no-class') . '_' . $loop->index; @endphp
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed py-2 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}">
                          <strong>Kelas: {{ $classGroup ?: 'Belum Ada Kelas' }}</strong>
                          <span class="badge bg-secondary ms-2">{{ $groupStudents->count() }}</span>
                        </button>
                      </h2>
                      <div id="{{ $groupId }}" class="accordion-collapse collapse" data-bs-parent="#accordionStudents">
                        <div class="accordion-body">
                          @foreach ($groupStudents as $student)
                            <div class="form-check student-item">
                              <input class="form-check-input" type="radio" name="student_id" value="{{ $student->id }}" id="s_{{ $student->id }}">
                              <label class="form-check-label w-100" for="s_{{ $student->id }}" style="cursor: pointer;">
                                {{ $student->name }} <small class="text-muted">({{ $student->nis }})</small>
                              </label>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div class="form-text">Klik nama kelas untuk membuka daftar santri.</div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Jenis Izin</label>
                  <select name="type" class="form-select" required>
                    <option value="izin">Izin Keluar (Sebentar)</option>
                    <option value="pulang">Pulang (Menginap)</option>
                    <option value="sakit">Sakit (Rawat Jalan/Inap)</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Alasan</label>
                  <input type="text" name="reason" class="form-control" placeholder="Contoh: Menjenguk Orang Sakit"
                    required>
                </div>
              </div>

              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Waktu Keluar</label>
                  <input type="datetime-local" name="start_date" class="form-control"
                    value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Rencana Kembali</label>
                  <input type="datetime-local" name="end_date" class="form-control" required>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('permissions.index') }}" class="btn btn-outline-danger">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Simpan & Setujui</button>
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
            item.querySelector('.accordion-collapse').classList.add('show');
            item.querySelector('.accordion-button').classList.remove('collapsed');
          }
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
</script>
@endpush
