@extends('layouts.app')
@section('title', 'Classrooms Master Data')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-4">Data Master Sekolah</h4>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-header bg-white p-0">
        <ul class="nav nav-tabs nav-fill" role="tablist">
          <li class="nav-item"><button class="nav-link active py-3" data-bs-toggle="tab"
              data-bs-target="#tab-stages">Jenjang (Unit)</button></li>
          <li class="nav-item"><button class="nav-link py-3" data-bs-toggle="tab" data-bs-target="#tab-levels">Tingkat
              Kelas</button></li>
          <li class="nav-item"><button class="nav-link py-3" data-bs-toggle="tab"
              data-bs-target="#tab-majors">Jurusan</button></li>
          <li class="nav-item"><button class="nav-link py-3 fw-bold text-primary" data-bs-toggle="tab"
              data-bs-target="#tab-years">Tahun Ajaran</button></li>
          <li class="nav-item"><button class="nav-link py-3" data-bs-toggle="tab" data-bs-target="#tab-teachers">Data
              Guru</button></li>
          <li class="nav-item"><button class="nav-link py-3" data-bs-toggle="tab" data-bs-target="#tab-subjects">Mata
              Pelajaran</button></li>
        </ul>
      </div>
      <div class="card-body p-4">
        <div class="tab-content">

          <div class="tab-pane fade show active" id="tab-stages">
            <form action="{{ route('master.stages.store') }}" method="POST" class="row g-2 mb-4 bg-light p-3 rounded">
              @csrf
              <div class="col-md-5"><input name="name" class="form-control"
                  placeholder="Nama Jenjang (Misal: MADRASAH ALIYAH)" required></div>
              <div class="col-md-3"><input name="code" class="form-control" placeholder="Kode (Misal: MA)" required>
              </div>
              <div class="col-md-4"><button class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>
                  Tambah</button></div>
            </form>

            <table class="table table-hover align-middle">
              <thead class="bg-light">
                <tr>
                  <th>Nama Jenjang</th>
                  <th>Kode</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($stages as $s)
                  <tr>
                    <td>{{ $s->name }}</td>
                    <td><span class="badge bg-secondary">{{ $s->code }}</span></td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal"
                        data-bs-target="#editStageModal{{ $s->id }}">
                        <i class="bi bi-pencil"></i>
                      </button>

                      <!-- Modal Edit -->
                      <div class="modal fade" id="editStageModal{{ $s->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Edit Jenjang</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('master.stages.update', $s->id) }}" method="POST">
                              @csrf @method('PUT')
                              <div class="modal-body text-start">
                                <div class="mb-3">
                                  <label class="form-label">Nama Jenjang</label>
                                  <input type="text" name="name" class="form-control" value="{{ $s->name }}" required>
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">Kode</label>
                                  <input type="text" name="code" class="form-control" value="{{ $s->code }}" required>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <form action="{{ route('master.stages.destroy', $s->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                          data-text="Hapus Jenjang ini?"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <span class="text-danger"><i>* Pastikan nama jenjang menggunakan huruf Kapital</i></span>
          </div>

          <div class="tab-pane fade" id="tab-levels">
            <form action="{{ route('master.levels.store') }}" method="POST" class="row g-2 mb-4 bg-light p-3 rounded">
              @csrf
              <div class="col-md-4">
                <select name="stage_id" class="form-select" required>
                  <option value="">Pilih Jenjang...</option>
                  @foreach ($stages as $s)
                    <option value="{{ $s->id }}">{{ $s->code }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4"><input name="name" class="form-control"
                  placeholder="Nama Tingkat (Misal: Kelas 10)" required></div>
              <div class="col-md-2"><input name="alias" class="form-control" placeholder="Alias (10)" required></div>
              <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>
                  Simpan</button></div>
            </form>

            <table class="table table-hover align-middle">
              <thead class="bg-light">
                <tr>
                  <th>Unit</th>
                  <th>Nama Tingkat</th>
                  <th>Alias</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($levels as $l)
                  <tr>
                    <td>
                      @php
                        $badgeColor = match ($l->stage->code) {
                            'SD', 'MI', 'ULA' => 'bg-primary', // biru
                            'SMP', 'MTS', 'WUSTHA' => 'bg-success', // hijau
                            'SMA', 'MA', 'ULYA' => 'bg-warning text-dark', // kuning
                            default => 'bg-primary', // Biru (Default)
                        };
                      @endphp
                      <span class="badge {{ $badgeColor }}">{{ $l->stage->code }}</span>
                    </td>
                    <td>{{ $l->name }}</td>
                    <td>{{ $l->alias }}</td>
                    <td class="text-end">
                      <form action="{{ route('master.levels.destroy', $l->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                          data-text="Hapus Tingkat ini?"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="tab-pane fade" id="tab-majors">
            <form action="{{ route('master.majors.store') }}" method="POST" class="row g-2 mb-4 bg-light p-3 rounded">
              @csrf
              <div class="col-md-6"><input name="name" class="form-control" placeholder="Nama Jurusan" required>
              </div>
              <div class="col-md-3"><input name="code" class="form-control" placeholder="Kode (IPA)" required>
              </div>
              <div class="col-md-3"><button class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>
                  Simpan</button></div>
            </form>

            <table class="table table-hover align-middle">
              <thead class="bg-light">
                <tr>
                  <th>Nama Jurusan</th>
                  <th>Kode</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($majors as $m)
                  <tr>
                    <td>{{ $m->name }}</td>
                    <td><span class="badge bg-info text-dark">{{ $m->code }}</span></td>
                    <td class="text-end">
                      <form action="{{ route('master.majors.destroy', $m->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                          data-text="Hapus Jurusan ini?"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="tab-pane fade" id="tab-years">
            <form action="{{ route('master.academic-years.store') }}" method="POST"
              class="row g-2 mb-4 bg-light p-3 rounded">
              @csrf
              <div class="col-md-4"><input name="name" class="form-control" placeholder="2024/2025" required></div>
              <div class="col-md-3">
                <select name="semester" class="form-select">
                  <option>Ganjil</option>
                  <option>Genap</option>
                </select>
              </div>
              <div class="col-md-3">
                <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="is_active"
                    id="activeCheck"><label class="form-check-label" for="activeCheck">Set Aktif</label></div>
              </div>
              <div class="col-md-2"><button class="btn btn-primary w-100">Buat Baru</button></div>
            </form>
            <table class="table table-hover align-middle">
              <thead class="bg-light">
                <tr>
                  <th>Tahun</th>
                  <th>Semester</th>
                  <th>Status</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($academicYears as $y)
                  <tr class="{{ $y->is_active ? 'table-success' : '' }}">
                    <td class="fw-bold">{{ $y->name }}</td>
                    <td>{{ $y->semester }}</td>
                    <td>
                      @if ($y->is_active)
                        <span class="badge bg-success">AKTIF</span>
                      @else
                        <form action="{{ route('master.academic-years.activate', $y->id) }}" method="POST"
                          class="d-inline">
                          @csrf @method('PUT')
                          <button class="btn btn-sm btn-outline-secondary">Aktifkan</button>
                        </form>
                      @endif
                    </td>
                    <td class="text-end">
                      @if (!$y->is_active)
                        {{-- Hanya boleh hapus jika tidak aktif --}}
                        <form action="{{ route('master.academic-years.destroy', $y->id) }}" method="POST">
                          @csrf @method('DELETE')
                          <button type="button" class="btn btn-sm btn-light text-danger btn-delete"
                            data-text="Hapus Tahun Ajaran ini? Data kelas terkait mungkin akan error jika tidak ditangani."><i
                              class="bi bi-trash"></i></button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="tab-pane fade" id="tab-teachers">
            <form action="{{ route('master.teachers.store') }}" method="POST"
              class="row g-2 mb-4 bg-light p-3 rounded">
              @csrf
              <div class="col-md-5">
                <select name="name" class="form-select" required>
                  <option value="">Pilih Guru...</option>
                  @foreach ($pegawais as $p)
                    <option value="{{ $p->nama }}">{{ $p->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3"><input name="title" class="form-control" placeholder="Gelar (S.Pd)"></div>
              <div class="col-md-2"><input name="nip" class="form-control" placeholder="NIP/NIY"></div>
              <div class="col-md-2"><button class="btn btn-primary w-100">Simpan</button></div>
            </form>
            <table class="table table-hover">
              <thead class="bg-light">
                <tr>
                  <th>Nama Guru</th>
                  <th>NIP</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($teachers as $t)
                  <tr>
                    <td>{{ $t->name }} {{ $t->title }}</td>
                    <td>{{ $t->nip ?? '-' }}</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>
                      <form action="{{ route('master.teachers.destroy', $t->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-light text-danger btn-delete" data-text="Hapus Guru ini?"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="tab-pane fade" id="tab-subjects">
            <form action="{{ route('master.subjects.store') }}" method="POST"
              class="row g-2 mb-4 bg-light p-3 rounded">
              @csrf
              <div class="col-md-3">
                <label class="small text-muted mb-1">Nama Mapel</label>
                <input name="name" class="form-control" placeholder="Contoh: Tematik" required>
              </div>
              <div class="col-md-2">
                <label class="small text-muted mb-1">Kode</label>
                <input name="code" class="form-control" placeholder="TMT" required>
              </div>
              <div class="col-md-3">
                <label class="small text-muted mb-1">Kelompok</label>
                <select name="group" class="form-select">
                  <option value="Umum">Muatan Umum</option>
                  <option value="Syari">Muatan Syar'i</option>
                  <option value="Mulok">Mulok/Ekstrakurikuler</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="small text-muted mb-1">Tersedia Untuk Jenjang:</label>
                <div class="d-flex gap-2 flex-wrap bg-white p-2 border rounded">
                  @foreach ($stages as $stage)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="stages[]" value="{{ $stage->id }}"
                        id="stage{{ $stage->id }}">
                      <label class="form-check-label small" for="stage{{ $stage->id }}">
                        {{ $stage->code }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="col-12 mt-2">
                <button class="btn btn-primary w-100">Simpan Mapel</button>
              </div>
            </form>


            <table class="table table-hover">
              <thead class="bg-light">
                <tr>
                  <th>Kode</th>
                  <th>Nama Mapel</th>
                  <th>Kelompok</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($subjects as $s)
                  <tr>
                    <td><span class="badge bg-secondary">{{ $s->code }}</span></td>
                    <td>
                      {{ $s->name }}
                      <br>
                      @foreach ($s->stages as $stage)
                        @if ($stage->code == 'WUSTHA')
                        <span class="badge bg-success text-white border"
                          style="font-size: 0.6rem;">{{ $stage->code }}</span>
                        @elseif ($stage->code == 'ULYA')
                          <span class="badge bg-warning text-dark border"
                          style="font-size: 0.6rem;">{{ $stage->code }}</span>
                        @endif
                      @endforeach
                    </td>
                    <td>{{ $s->group }}</td>
                    <td>
                      <div class="d-flex gap-1 justify-content-center">
                        <form action="{{ route('master.subjects.destroy', $s->id) }}" method="POST">
                          @csrf 
                          @method('DELETE')
                          <button type="button" class="btn btn-sm btn-light text-danger btn-delete" data-text="Hapus Mapel ini?"><i class="bi bi-trash"></i></button>
                        </form>
                        <a href="{{ route('master.syllabus.index', $s->id) }}" class="btn btn-sm btn-info">Materi</a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
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
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
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

    // Konfirmasi Delete
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function() {
        const text = this.getAttribute('data-text') || "Data akan dihapus permanen!";
        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: text,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) this.closest('form').submit();
        });
      });
    });

    // Auto-Activate Tab from Session or Query Param
    @php
      $activeTab = session('active_tab') ?? request('active_tab');
    @endphp
    @if($activeTab)
      var triggerEl = document.querySelector('button[data-bs-target="#{{ $activeTab }}"]');
      if(triggerEl) {
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
      }
    @endif
  </script>
@endpush
