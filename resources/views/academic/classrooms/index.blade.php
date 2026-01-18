@extends('layouts.app')
@section('title', 'Classrooms')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between mb-4">
      <div>
        <h4 class="fw-bold">Daftar Kelas</h4>
        @if ($activeYear)
          <span class="badge bg-success">TA: {{ $activeYear->name }} ({{ $activeYear->semester }})</span>
        @endif
      </div>
      <div class="d-flex gap-2 align-items-center">
        <form action="{{ route('classrooms.index') }}" method="GET">
          <select name="stage_id" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Jenjang</option>
            @foreach ($stages as $s)
              <option value="{{ $s->id }}" {{ request('stage_id') == $s->id ? 'selected' : '' }}>
                {{ $s->code }}
              </option>
            @endforeach
          </select>
        </form>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Kelas</button>
      </div>
    </div>

    @php
      $groupedClassrooms = $classrooms->groupBy(function ($item) {
          return $item->level->name ?? 'Lainnya';
      });
    @endphp

    @foreach ($groupedClassrooms as $levelName => $classroomsInLevel)
      <h4 class="fw-bold mt-4 mb-3 text-secondary border-bottom pb-2"><i class="bi bi-layers-fill me-2"></i>{{ $levelName }}</h4>
      <div class="row g-4">
        @foreach ($classroomsInLevel as $c)
        <div class="col-md-4 col-lg-3">
          <div class="card h-100 border-0 shadow-sm rounded-4">
            <div class="card-body">
              <div class="d-flex justify-content-between mb-2">
                @php
                  $badgeColor = match ($c->level->stage->code) {
                      'SD', 'MI', 'ULA' => 'bg-primary', // biru
                      'SMP', 'MTS', 'WUSTHA' => 'bg-success', // hijau
                      'SMA', 'MA', 'ULYA' => 'bg-warning text-dark', // kuning
                      default => 'bg-primary', // Biru (Default)
                  };
                @endphp
                <span class="badge {{ $badgeColor }}">{{ $c->level->stage->code ?? '' }}</span>
                <div class="dropdown">
                  <button class="btn btn-link btn-sm text-dark p-0" data-bs-toggle="dropdown"><i
                      class="bi bi-three-dots-vertical"></i></button>
                  <ul class="dropdown-menu">
                    <li><a href="{{ route('classrooms.edit', $c->id) }}" class="dropdown-item">Edit</a></li>
                    <li>
                      <form action="{{ route('classrooms.destroy', $c->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="dropdown-item text-danger btn-delete">Hapus</button>
                      </form>
                    </li>
                  </ul>
                </div>
              </div>
              <h5 class="fw-bold">{{ $c->name }}</h5>
              <p class="text-muted small">Wali: {{ $c->homeroom_teacher ?? '-' }}</p>
              <div class="d-flex justify-content-between border-top pt-3 mt-3">
                <small>{{ $c->students_count }} / {{ $c->capacity }} Siswa</small>
                <a href="{{ route('classrooms.show', $c->id) }}"
                  class="btn btn-sm btn-outline-primary rounded-pill">Kelola</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    @endforeach
  </div>

  <div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="fw-bold">Buat Kelas</h5>
        </div>
        <form action="{{ route('classrooms.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-2">
              <label>Tingkat</label>
              <select name="level_id" class="form-select" required>
                @foreach ($levels as $l)
                  <option value="{{ $l->id }}">{{ $l->stage->code }} - {{ $l->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-2">
              <label>Jurusan</label>
              <select name="major_id" class="form-select">
                <option value="">- Umum / Tidak Ada -</option>
                @foreach ($majors as $m)
                  <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-2"><label>Nama Kelas</label><input name="name" class="form-control" required placeholder="Contoh: 1A"></div>
            <div class="mb-2">
              <label>Wali Kelas</label>
              <select name="homeroom_teacher" class="form-select">
                <option value="">Pilih Wali Kelas...</option>
                @foreach ($teachers as $teacher)
                  <option value="{{ $teacher->nama }}">{{ $teacher->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-2"><label>Kapasitas</label><input name="capacity" type="number" class="form-control"
                value="30"></div>
          </div>
          <div class="modal-footer"><button class="btn btn-primary w-100">Simpan</button></div>
        </form>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Notifikasi Sukses
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @endif

    // Konfirmasi Delete
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function() {
        Swal.fire({
          title: 'Hapus Kelas?',
          text: "Data siswa di dalamnya akan dikeluarkan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
          if (result.isConfirmed) this.closest('form').submit();
        });
      });
    });
  </script>
@endpush
