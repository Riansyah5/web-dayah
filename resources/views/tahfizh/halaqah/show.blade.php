@extends('layouts.app')
@section('title', 'Detail Halaqah Tahfizh')
@push('link')
@endpush
@push('styles')
  <style>
    .bg-halaqah-l {
      background: linear-gradient(135deg, #00B7B5 0%, #00B7B5 100%);
    }

    .bg-halaqah-p {
      background: linear-gradient(135deg, #FF6F91 0%, #FF6F91 100%);
    }
  </style>
@endpush
@section('content')
  @php
    if ($halaqah->gender == 'L') {
        $bgHalaqah = 'bg-halaqah-l';
    } else {
        $bgHalaqah = 'bg-halaqah-p';
    }
  @endphp
  <div class="container py-4">
    <a href="{{ route('tahfizh.halaqah.index') }}" class="btn btn-outline-secondary btn-sm rounded mb-2"><i
        class="bi bi-arrow-left"></i> Kembali</a>
    <div class="card border-0 shadow-sm rounded-4 mb-4 {{ $bgHalaqah }} text-white overflow-hidden">
      {{-- <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded me-3"><i class="bi bi-arrow-left"></i></a> --}}
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="opacity-75 text-uppercase letter-spacing-1 mb-1">Halaqah Tahfizh</h6>
            <h2 class="fw-bold mb-1 text-white"><i class=""></i> {{ $halaqah->name }}</h2>
            <p class="mb-0 text-white opacity-75"><i class="bi bi-person-badge me-2"></i>Musyrif:
              {{ $halaqah->teacher->name ?? '-' }}</p>
          </div>
          <div class="text-end">
            <h1 class="display-4 fw-bold mb-0 text-white">{{ $halaqah->students->count() }}</h1>
            <span class="opacity-75">Anggota</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Daftar Santri</h6>
          </div>
          <div class="card-body p-0 position-relative">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-4">Nama Santri</th>
                    <th>Setoran Terakhir</th>
                    <th class="text-end pe-4">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($halaqah->students as $student)
                    <tr>
                      <td class="ps-4">
                        <div class="fw-bold"><a href="{{ route('students.show', $student->id) }}">{{ $student->name }}</a>
                        </div>
                        <small class="text-muted">{{ $student->nis }}</small>
                      </td>
                      <td>
                        @php
                          $last = \App\Models\TahfizhSetoran::where('student_id', $student->id)
                              ->latest('date')
                              ->latest('id')
                              ->first();
                        @endphp
                        @if ($last)
                          <small class="d-block text-dark">{{ $last->date->format('d/m/y') }} -
                            {{ ucfirst($last->type) }}</small>
                          <small class="text-muted"
                            style="font-size: 0.75rem;">{{ Str::limit($last->location, 25) }}</small>
                        @else
                          <small class="text-muted fst-italic">- Belum ada data -</small>
                        @endif
                      </td>
                      <td class="text-end pe-4">
                        {{-- Tampilan Desktop --}}
                        <div class="d-none d-md-block">
                          <a href="{{ route('tahfizh.setoran.create', $student->id) }}"
                            class="btn btn-sm btn-success rounded me-1" title="Input Setoran Baru">
                            <i class="bi bi-journal-plus me-1"></i>
                          </a>
                          <a href="{{ route('tahfizh.report.show', $student->id) }}"
                            class="btn btn-sm btn-info text-white rounded me-1" title="Lihat Grafik">
                            <i class="bi bi-bar-chart-line"></i>
                          </a>

                          <a href="{{ route('tahfizh.assessment.edit', $student->id) }}"
                            class="btn btn-sm btn-warning text-dark rounded me-1" title="Input Rapor">
                            <i class="bi bi-pencil-square"></i>
                          </a>

                          <form
                            action="{{ route('tahfizh.halaqah.remove-member', ['halaqah' => $halaqah->id, 'student' => $student->id]) }}"
                            method="POST" class="d-inline delete-form">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-circle" title="Keluarkan"><i
                                class="bi bi-x-lg"></i></button>
                          </form>
                        </div>

                        {{-- Tampilan Mobile (Dropdown) --}}
                        <div class="d-md-none dropdown position-static">
                          <button class="btn btn-sm rounded btn-light border shadow-sm" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                            <li><a class="dropdown-item" href="{{ route('tahfizh.setoran.create', $student->id) }}"><i class="bi bi-journal-plus me-2 text-success"></i>Input Setoran</a></li>
                            <li><a class="dropdown-item" href="{{ route('tahfizh.report.show', $student->id) }}"><i class="bi bi-bar-chart-line me-2 text-info"></i>Lihat Grafik</a></li>
                            <li><a class="dropdown-item" href="{{ route('tahfizh.assessment.edit', $student->id) }}"><i class="bi bi-pencil-square me-2 text-warning"></i>Input Rapor</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                              <form action="{{ route('tahfizh.halaqah.remove-member', ['halaqah' => $halaqah->id, 'student' => $student->id]) }}" method="POST" class="delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Keluarkan</button>
                              </form>
                            </li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center py-5 text-muted">Belum ada anggota di halaqah ini.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-3">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Tambah Anggota</h6>
            <form action="{{ route('tahfizh.halaqah.add-member', $halaqah->id) }}" method="POST">
              @csrf
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="form-label small text-muted mb-0">Pilih Santri ({{ $halaqah->gender }})</label>
                  <div class="form-check form-check-inline m-0">
                    <input class="form-check-input" type="checkbox" id="checkAll">
                    <label class="form-check-label small" for="checkAll">Pilih Semua</label>
                  </div>
                </div>
                <div class="border rounded p-2 bg-light" style="max-height: 300px; overflow-y: auto;">
                  @forelse ($availableStudents as $s)
                    <div class="form-check mb-2 border-bottom pb-2">
                      <input class="form-check-input student-check" type="checkbox" name="student_ids[]"
                        value="{{ $s->id }}" id="s_{{ $s->id }}">
                      <label class="form-check-label lh-sm" for="s_{{ $s->id }}">
                        <span class="fw-bold d-block small">{{ $s->name }}</span>
                        <span class="text-muted" style="font-size: 0.75rem;">{{ $s->nis }}</span>
                      </label>
                    </div>
                  @empty
                    <div class="text-center text-muted small py-3">
                      Tidak ada santri tersedia.
                    </div>
                  @endforelse
                </div>
                <div class="form-text small mt-2">
                  Hanya menampilkan santri aktif yang belum masuk kelompok manapun.
                </div>
              </div>
              <button type="submit" class="btn btn-primary w-100 rounded-pill">
                <i class="bi bi-person-plus me-2"></i> Masukkan Terpilih
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
    document.getElementById('checkAll').addEventListener('change', function() {
      const checkboxes = document.querySelectorAll('.student-check');
      checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
      });
    });

    // SweetAlert untuk Konfirmasi Hapus Anggota
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Keluarkan Santri?',
          text: "Santri ini akan dihapus dari daftar anggota halaqah.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Keluarkan!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            this.submit();
          }
        });
      });
    });

    // SweetAlert untuk Notifikasi Session (Success/Error)
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
      });
    @endif

    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
      });
    @endif
  </script>
@endpush
