@extends('layouts.app')
@section('title', 'Detail Kelas')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">

    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-info text-white overflow-hidden position-relative">
      <i class="bi bi-people-fill position-absolute bottom-0 end-0 mb-n3 me-4 opacity-10" style="font-size: 8rem;"></i>

      <div class="card-body p-4 position-relative z-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <a href="{{ route('classrooms.index') }}"
            class="btn btn-sm btn-light bg-secondary bg-opacity-55 text-white border-0 rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill">
            {{ $classroom->academicYear->name }} ({{ $classroom->academicYear->semester }})
          </span>
        </div>

        <div class="row align-items-end" style="padding-right: 120px;">
          <div class="col-md-8">
            <h5 class="text-white-50 mb-1">
              {{ $classroom->level->stage->code }} &bull; {{ $classroom->level->name }}
              @if ($classroom->major)
                &bull; {{ $classroom->major->name }}
              @endif
            </h5>
            <h1 class="fw-bold mb-0 display-5 text-white">{{ $classroom->name }}</h1>
            <p class="mb-0 mt-2 opacity-75"><i class="bi bi-person-badge me-2"></i>Wali Kelas:
              {{ $classroom->homeroom_teacher ?? 'Belum ditentukan' }}</p>
          </div>
          <div class="col-md-4 text-md-end mt-3 mt-md-0 ">
            <div class="d-inline-block text-center bg-white bg-opacity-50 rounded-3 p-3">
              <h2 class="fw-bold mb-0 text-muted">{{ $classroom->students->count() }}</h2>
              <small class="text-muted">Total Siswa</small>
            </div>
            <div class="d-inline-block text-center bg-white bg-opacity-50 rounded-3 p-3 ms-2">
              <h2 class="fw-bold mb-0 text-muted">{{ $classroom->capacity }}</h2>
              <small class="text-muted">Kapasitas</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">

      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-ol me-2 text-primary"></i>Daftar Anggota Kelas</h6>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
              <i class="bi bi-printer me-2"></i>Cetak Absen
            </button>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light text-secondary small text-uppercase">
                <tr>
                  <th class="ps-4" width="50">No</th>
                  <th>Nama Siswa</th>
                  <th>NIS</th>
                  <th>L/P</th>
                  <th class="text-end pe-4">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($classroom->students as $index => $student)
                  <tr>
                    <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                    <td>
                      <div class="fw-bold text-dark"><a href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a></div>
                    </td>
                    <td class="text-muted small">{{ $student->nis }}</td>
                    <td>
                      <span
                        class="badge {{ $student->gender == 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
                        {{ $student->gender }}
                      </span>
                    </td>
                    <td class="text-end pe-4">
                      <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                          data-bs-target="#moveModal{{ $student->id }}" title="Pindah Kelas">
                          <i class="bi bi-arrow-left-right"></i>
                        </button>

                        <form
                          action="{{ route('classrooms.removeStudent', ['classroom' => $classroom->id, 'studentId' => $student->id]) }}"
                          method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-sm btn-light text-danger btn-delete">
                            <i class="bi bi-x-lg"></i>
                          </button>
                        </form>
                      </div>

                      <div class="modal fade text-start" id="moveModal{{ $student->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                          <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                              <h6 class="fw-bold">Pindah Kelas</h6>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form
                              action="{{ route('classrooms.moveStudent', ['classroom' => $classroom->id, 'studentId' => $student->id]) }}"
                              method="POST">
                              @csrf
                              @method('PUT')
                              <div class="modal-body">
                                <p class="small text-muted mb-2">Pindahkan <strong>{{ $student->name }}</strong> ke:</p>
                                <select name="destination_class_id" class="form-select form-select-sm" required>
                                  <option value="" disabled selected>Pilih Kelas Tujuan...</option>
                                  @foreach ($otherClasses as $oc)
                                    <option value="{{ $oc->id }}">
                                      {{ $oc->name }} (Isi: {{ $oc->students_count }})
                                    </option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="modal-footer border-0 pt-0">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Simpan Perpindahan</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5">
                      <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="60"
                        class="opacity-25 mb-3">
                      <p class="text-muted small mb-0">Kelas ini belum memiliki siswa.</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
          <div class="card-body">
            <h6 class="fw-bold mb-3 text-dark">
              <i class="bi bi-person-plus-fill me-2 text-success"></i>Tambah Anggota
            </h6>
            <p class="text-muted small mb-3">
              Hanya menampilkan siswa aktif yang <strong>belum memiliki kelas</strong> pada Tahun Ajaran ini.
            </p>

            <form action="{{ route('classrooms.addStudent', $classroom->id) }}" method="POST">
              @csrf
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="form-label small fw-bold text-muted mb-0">Pilih Siswa</label>
                  @if ($availableStudents->isNotEmpty())
                    <div class="form-check form-check-sm">
                      <input class="form-check-input" type="checkbox" id="checkAll">
                      <label class="form-check-label small text-muted" for="checkAll">Pilih Semua</label>
                    </div>
                  @endif
                </div>
                <div class="border rounded p-2 bg-light" style="max-height: 300px; overflow-y: auto;">
                  @forelse($availableStudents as $as)
                    <div class="form-check mb-1">
                      <input class="form-check-input student-checkbox" type="checkbox" name="student_ids[]"
                        value="{{ $as->id }}" id="s_{{ $as->id }}">
                      <label class="form-check-label small" for="s_{{ $as->id }}">{{ $as->name }} ({{ $as->gender }})</label>
                    </div>
                  @empty
                    <div class="text-center text-muted small py-3">Tidak ada siswa tersedia.</div>
                  @endforelse
                </div>
              </div>
              <button type="submit" class="btn btn-success w-100 fw-bold"
                {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-plus-lg me-2"></i> Masukkan ke Kelas
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const checkAll = document.getElementById('checkAll');
    if (checkAll) {
      checkAll.addEventListener('change', function() {
        document.querySelectorAll('.student-checkbox').forEach(cb => {
          cb.checked = this.checked;
        });
      });
    }

    // Notifikasi Sukses
    {{--   @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      }); --}}
    @if (session('success'))
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
      });
    @endif

    // Notifikasi Error
    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '{{ session('error') }}',
      });
    @endif

    // Konfirmasi Delete (Keluarkan Siswa)
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function() {
        Swal.fire({
          title: 'Keluarkan Siswa?',
          text: "Siswa akan dihapus dari daftar kelas ini.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Keluarkan!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) this.closest('form').submit();
        });
      });
    });
  </script>
@endpush
