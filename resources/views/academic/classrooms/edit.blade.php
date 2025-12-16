@extends('layouts.app')
@section('title', 'Edit Kelas')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="fw-bold text-dark mb-0">Edit Data Kelas</h4>
          <a href="{{ route('classrooms.index') }}" class="btn btn-light border text-muted">
            <i class="bi bi-x-lg me-1"></i> Batal
          </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">

            <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="alert alert-light border d-flex align-items-center mb-4">
                <i class="bi bi-calendar-event text-primary fs-4 me-3"></i>
                <div>
                  <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Tahun Ajaran</small>
                  <span class="fw-bold text-dark">
                    {{ $classroom->academicYear->name }} ({{ $classroom->academicYear->semester }})
                  </span>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Tingkat Pendidikan</label>
                <select name="level_id" class="form-select" required>
                  <option value="" disabled>Pilih Tingkat...</option>
                  @foreach ($levels as $lvl)
                    <option value="{{ $lvl->id }}"
                      {{ old('level_id', $classroom->level_id) == $lvl->id ? 'selected' : '' }}>
                      {{ $lvl->stage->code }} - {{ $lvl->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Jurusan / Peminatan</label>
                <select name="major_id" class="form-select">
                  <option value="">- Umum / Tidak Ada -</option>
                  @foreach ($majors as $mjr)
                    <option value="{{ $mjr->id }}"
                      {{ old('major_id', $classroom->major_id) == $mjr->id ? 'selected' : '' }}>
                      {{ $mjr->name }} ({{ $mjr->code }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-8">
                  <label class="form-label small fw-bold text-muted">Nama Kelas</label>
                  <input type="text" name="name" class="form-control" value="{{ old('name', $classroom->name) }}"
                    required>
                </div>
                <div class="col-4">
                  <label class="form-label small fw-bold text-muted">Kapasitas</label>
                  <input type="number" name="capacity" class="form-control"
                    value="{{ old('capacity', $classroom->capacity) }}">
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Wali Kelas</label>
                <select name="homeroom_teacher" class="form-select">
                  <option value="">- Pilih Wali Kelas -</option>
                  @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->nama }}"
                      {{ old('homeroom_teacher', $classroom->homeroom_teacher) == $teacher->nama ? 'selected' : '' }}>
                      {{ $teacher->nama }}
                    </option>
                  @endforeach
                </select>
              </div>

              <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                Simpan Perubahan
              </button>

            </form>
          </div>
        </div>

        <div class="text-center mt-4">
          <form id="delete-form" action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" id="btn-delete-permanent" class="btn btn-link text-danger text-decoration-none btn-sm">
              Hapus Kelas Permanen
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('btn-delete-permanent').addEventListener('click', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Hapus Kelas Permanen?',
        text: "Tindakan ini tidak dapat dibatalkan. Semua siswa di dalamnya akan dikeluarkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Permanen!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form').submit();
        }
      });
    });
  </script>
@endpush
