@extends('layouts.app')
@section('title', 'Detail Kelas')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@push('styles')
  <style>
    :root {
      --berry-primary: #2196f3;
      --berry-primary-light: #e3f2fd;
      --berry-secondary: #5e35b1;
      --berry-secondary-light: #ede7f6;
      --berry-success: #00c853;
      --berry-success-light: #e8f5e9;
      --berry-warning: #ffab00;
      --berry-danger: #f44336;
      --berry-danger-light: #fbe9e7;
      --berry-border: #e3e8ef;
      --berry-heading: #121926;
      --berry-text: #697586;
      --berry-bg: #eef2f6;
    }

    .berry-header {
      background: #fff; border: 1px solid var(--berry-border);
      border-radius: 12px; padding: 1.25rem 1.5rem;
    }
    .berry-header .page-title { color: var(--berry-heading); font-weight: 600; margin: 0; }
    .berry-header .breadcrumb { margin: 0 0 .35rem; font-size: .8125rem; }
    .berry-header .breadcrumb-item a { color: var(--berry-text); text-decoration: none; }
    .berry-header .breadcrumb-item.active { color: var(--berry-secondary); }

    /* Banner kelas */
    .class-hero {
      position: relative; overflow: hidden; color: #fff;
      border-radius: 12px; border: 0;
      background: linear-gradient(135deg, #5e35b1 0%, #2196f3 100%);
    }
    .class-hero::before, .class-hero::after {
      content: ""; position: absolute; border-radius: 50%;
      background: rgba(255, 255, 255, .08);
    }
    .class-hero::before { width: 260px; height: 260px; top: -120px; right: -60px; }
    .class-hero::after { width: 200px; height: 200px; bottom: -110px; right: 120px; background: rgba(255,255,255,.06); }
    .class-hero .hero-body { position: relative; z-index: 1; padding: 1.75rem; }
    .class-hero .hero-sub { color: rgba(255,255,255,.75); font-size: .95rem; }
    .class-hero .hero-title { font-weight: 700; font-size: 2.25rem; margin: 0; color: #fff; }
    .class-hero .hero-meta { color: rgba(255,255,255,.85); font-size: .9rem; }

    .hero-stat {
      display: inline-block; min-width: 100px; text-align: center;
      background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22);
      backdrop-filter: blur(4px); border-radius: 10px; padding: .75rem 1rem;
    }
    .hero-stat h3 { margin: 0; font-weight: 700; color: #fff; }
    .hero-stat small { color: rgba(255,255,255,.8); }

    .btn-hero {
      background: rgba(255,255,255,.18); color: #fff; border: 1px solid rgba(255,255,255,.3);
      border-radius: 8px; font-weight: 500;
    }
    .btn-hero:hover { background: #fff; color: var(--berry-secondary); }
    .badge-hero {
      background: #fff; color: var(--berry-secondary);
      border-radius: 20px; font-weight: 600; padding: .5rem .9rem;
    }

    /* Card */
    .berry-card {
      background: #fff; border: 1px solid var(--berry-border);
      border-radius: 12px; box-shadow: none;
    }
    .berry-card .card-head {
      padding: 1rem 1.25rem; border-bottom: 1px solid var(--berry-border);
      display: flex; justify-content: space-between; align-items: center; gap: .5rem;
    }
    .berry-card .card-head h6 { margin: 0; font-weight: 600; color: var(--berry-heading); }

    /* Tabel */
    .berry-table thead th {
      background: #f8fafc; color: var(--berry-text);
      font-size: .72rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: .04em; border-bottom: 1px solid var(--berry-border);
      padding-top: .8rem; padding-bottom: .8rem;
    }
    .berry-table tbody td { border-color: var(--berry-border); padding-top: .75rem; padding-bottom: .75rem; }
    .berry-table tbody tr:hover { background: #f8fafc; }
    .student-link { color: var(--berry-heading); font-weight: 600; text-decoration: none; }
    .student-link:hover { color: var(--berry-secondary); }

    .avtar {
      width: 38px; height: 38px; border-radius: 10px; font-weight: 600; font-size: .85rem;
      display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .soft-primary { background: var(--berry-primary-light); color: var(--berry-primary); }
    .soft-danger { background: var(--berry-danger-light); color: var(--berry-danger); }
    .soft-success { background: var(--berry-success-light); color: #00a152; }
    .soft-secondary { background: var(--berry-secondary-light); color: var(--berry-secondary); }
    .badge-soft { font-weight: 500; border-radius: 6px; padding: .35em .65em; }

    .btn-icon-soft {
      width: 32px; height: 32px; padding: 0; border-radius: 8px; border: 0;
      display: inline-flex; align-items: center; justify-content: center;
      background: var(--berry-bg); color: var(--berry-text);
    }
    .btn-icon-soft.primary:hover { background: var(--berry-primary); color: #fff; }
    .btn-icon-soft.danger:hover { background: var(--berry-danger); color: #fff; }

    .btn-berry {
      background: var(--berry-secondary); color: #fff; border: 0;
      border-radius: 8px; font-weight: 500;
    }
    .btn-berry:hover { background: #4527a0; color: #fff; }
    .btn-berry-success {
      background: var(--berry-success); color: #fff; border: 0;
      border-radius: 8px; font-weight: 500;
    }
    .btn-berry-success:hover { background: #00a152; color: #fff; }
    .btn-berry-success:disabled { opacity: .5; }
    .btn-light-danger {
      background: var(--berry-danger-light); color: var(--berry-danger);
      border: 0; border-radius: 8px; font-weight: 500;
    }
    .btn-light-danger:hover, .btn-light-danger.show { background: var(--berry-danger); color: #fff; }

    /* Form & daftar siswa tersedia */
    .form-select, .form-control { border-radius: 8px; border-color: #dbe0e5; }
    .form-select:focus, .form-control:focus {
      border-color: var(--berry-secondary);
      box-shadow: 0 0 0 .2rem rgba(94, 53, 177, .12);
    }
    .form-check-input:checked { background-color: var(--berry-secondary); border-color: var(--berry-secondary); }
    .available-list {
      max-height: 300px; overflow-y: auto; background: #f8fafc;
      border: 1px solid var(--berry-border); border-radius: 10px; padding: .5rem;
    }
    .student-item { padding: .4rem .5rem; border-radius: 8px; margin: 0 !important; }
    .student-item:hover { background: var(--berry-secondary-light); }
    .progress.berry-progress { height: 6px; border-radius: 10px; background: var(--berry-bg); }

    .dropdown-menu { border: 1px solid var(--berry-border); border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); padding: .4rem; }
    .dropdown-item { border-radius: 6px; font-size: .875rem; }
    .modal-content { border: 0; border-radius: 14px; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--berry-text); }
    .empty-state i { font-size: 2.5rem; color: #cdd5df; }
  </style>
@endpush

@section('content')
  @php
    $total = $classroom->students->count();
    $capacity = (int) ($classroom->capacity ?? 0);
    $pct = $capacity > 0 ? min(100, round(($total / $capacity) * 100)) : 0;
    $barColor = $pct >= 100 ? 'bg-danger' : ($pct >= 80 ? 'bg-warning' : 'bg-success');
  @endphp

  <div class="container-fluid py-4">

    {{-- ===== Breadcrumb ===== --}}
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">Kelas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Kelas</li>
      </ol>
    </nav>
    
    {{-- ===== Banner Kelas ===== --}}
    <div class="class-hero mb-4">
      <div class="hero-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <a href="{{ route('classrooms.index') }}" class="btn btn-sm btn-hero px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <span class="badge badge-hero">
            <i class="bi bi-calendar-check me-1"></i>{{ $classroom->academicYear->name }} ({{ $classroom->academicYear->semester }})
          </span>
        </div>

        <div class="row align-items-end g-3">
          <div class="col-md-7">
            <div class="hero-sub mb-1">
              {{ $classroom->level->stage->code }} &bull; {{ $classroom->level->name }}
              @if ($classroom->major)
                &bull; {{ $classroom->major->name }}
              @endif
            </div>
            <h1 class="hero-title">{{ $classroom->name }}</h1>
            <p class="hero-meta mb-0 mt-2">
              <i class="bi bi-person-badge me-2"></i>Wali Kelas:
              {{ $classroom->homeroom_teacher ?? 'Belum ditentukan' }}
            </p>
          </div>
          <div class="col-md-5 text-md-end">
            <div class="hero-stat">
              <h3>{{ $total }}</h3>
              <small>Total Siswa</small>
            </div>
            <div class="hero-stat ms-2">
              <h3>{{ $classroom->capacity }}</h3>
              <small>Kapasitas</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">

      {{-- ===== Daftar Anggota ===== --}}
      <div class="col-lg-8">
        <div class="berry-card h-100">
          <div class="card-head">
            <h6><i class="bi bi-list-ol me-2" style="color: var(--berry-secondary)"></i>Daftar Anggota Kelas</h6>
            <div class="dropdown">
              <button class="btn btn-sm btn-light-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-printer me-1"></i> Menu Cetak
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="{{ route('classrooms.print-attendance', $classroom->id) }}" target="_blank">
                    <i class="bi bi-file-pdf me-2 text-danger"></i> Cetak Absen Kelas
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('classrooms.print-attendance2', $classroom->id) }}" target="_blank">
                    <i class="bi bi-file-pdf me-2 text-danger"></i> Cetak Absen Kedatangan
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="#">
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i> Cetak Rekap Bulanan
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="{{ route('classrooms.export', $classroom->id) }}">
                    <i class="bi bi-file-earmark-excel me-2 text-success"></i> Ekspor ke Excel
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table berry-table align-middle mb-0">
              <thead>
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
                      <div class="d-flex align-items-center gap-2">
                        <span class="avtar {{ $student->gender == 'L' ? 'soft-primary' : 'soft-danger' }}">
                          {{ strtoupper(mb_substr($student->name, 0, 1)) }}
                        </span>
                        <a href="{{ route('students.show', $student->id) }}" class="student-link">{{ $student->name }}</a>
                      </div>
                    </td>
                    <td class="text-muted small">{{ $student->nis }}</td>
                    <td>
                      <span class="badge badge-soft {{ $student->gender == 'L' ? 'soft-primary' : 'soft-danger' }}">
                        {{ $student->gender }}
                      </span>
                    </td>
                    <td class="text-end pe-4">
                      <div class="d-inline-flex gap-1">
                        <button type="button" class="btn-icon-soft primary" data-bs-toggle="modal"
                          data-bs-target="#moveModal{{ $student->id }}" title="Pindah Kelas">
                          <i class="bi bi-arrow-left-right"></i>
                        </button>
                        @can('kelola-kelas')
                          <form action="{{ route('classrooms.removeStudent', ['classroom' => $classroom->id, 'studentId' => $student->id]) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-icon-soft danger btn-delete" title="Keluarkan">
                              <i class="bi bi-x-lg"></i>
                            </button>
                          </form>
                        @endcan
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5">
                      <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <p class="mt-2 mb-0 small">Kelas ini belum memiliki siswa.</p>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- ===== Tambah Anggota ===== --}}
      <div class="col-lg-4">
        <div class="berry-card">
          <div class="card-head">
            <h6><i class="bi bi-person-plus-fill me-2 text-success"></i>Tambah Anggota</h6>
          </div>
          <div class="p-3">
            {{-- Kapasitas --}}
            <div class="d-flex justify-content-between align-items-center mb-1">
              <small class="text-muted">Kapasitas terisi</small>
              <small class="fw-semibold" style="color: var(--berry-heading)">{{ $total }} / {{ $capacity }}</small>
            </div>
            <div class="progress berry-progress mb-3">
              <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $pct }}%"
                aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="alert alert-light border small py-2 px-3 mb-3" style="border-radius:8px;">
              <i class="bi bi-info-circle me-1 text-primary"></i>
              Hanya menampilkan siswa aktif yang <strong>belum memiliki kelas</strong> pada Tahun Ajaran ini.
            </div>

            <form action="{{ route('classrooms.addStudent', $classroom->id) }}" method="POST">
              @csrf
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-bold text-muted mb-0">Pilih Siswa</label>
                @if ($availableStudents->isNotEmpty())
                  <div class="form-check form-check-sm mb-0">
                    <input class="form-check-input" type="checkbox" id="checkAll">
                    <label class="form-check-label small text-muted" for="checkAll">Pilih Semua</label>
                  </div>
                @endif
              </div>

              <div class="mb-2">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                  <input type="text" id="searchAvailableStudent" class="form-control border-start-0"
                    placeholder="Cari nama santri...">
                </div>
              </div>

              <div class="available-list mb-3 px-4">
                @forelse($availableStudents as $as)
                  <div class="form-check student-item">
                    <input class="form-check-input student-checkbox" type="checkbox" name="student_ids[]"
                      value="{{ $as->id }}" id="s_{{ $as->id }}">
                    <label class="form-check-label small student-name w-100" for="s_{{ $as->id }}">
                      {{ $as->name }} ({{ $as->gender }})
                    </label>
                  </div>
                @empty
                  <div class="text-center text-muted small py-3">Tidak ada siswa tersedia.</div>
                @endforelse
              </div>

              <button type="submit" class="btn btn-berry-success w-100" {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-plus-lg me-2"></i> Masukkan ke Kelas
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- ===== Modal Pindah Kelas (di luar tabel) ===== --}}
  @foreach ($classroom->students as $student)
    <div class="modal fade" id="moveModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header border-0 pb-0">
            <h6 class="fw-bold mb-0">Pindah Kelas</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('classrooms.moveStudent', ['classroom' => $classroom->id, 'studentId' => $student->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
              <p class="small text-muted mb-2">Pindahkan <strong>{{ $student->name }}</strong> ke:</p>
              <select name="destination_class_id" class="form-select form-select-sm" required>
                <option value="" disabled selected>Pilih Kelas Tujuan...</option>
                @foreach ($otherClasses as $oc)
                  <option value="{{ $oc->id }}">{{ $oc->name }} (Isi: {{ $oc->students_count }})</option>
                @endforeach
              </select>
            </div>
            <div class="modal-footer border-0 pt-0">
              <button type="submit" class="btn btn-berry btn-sm w-100">Simpan Perpindahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Logika Check All (hanya item yang sedang tampil/terfilter)
    const checkAll = document.getElementById('checkAll');
    if (checkAll) {
      checkAll.addEventListener('change', function() {
        document.querySelectorAll('.student-item').forEach(item => {
          if (item.style.display !== 'none') {
            item.querySelector('.student-checkbox').checked = this.checked;
          }
        });
      });
    }

    // Notifikasi Sukses (Toast)
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
        title: @json(session('success'))
      });
    @endif

    // Notifikasi Error
    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('error')),
        confirmButtonColor: '#5e35b1'
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
          confirmButtonColor: '#f44336',
          cancelButtonColor: '#697586',
          confirmButtonText: 'Ya, Keluarkan!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) this.closest('form').submit();
        });
      });
    });

    // Pencarian Santri di Form Tambah Anggota
    const searchInput = document.getElementById('searchAvailableStudent');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('.student-item').forEach(function(item) {
          const name = item.querySelector('.student-name').textContent.toLowerCase();
          item.style.display = name.includes(filter) ? '' : 'none';
        });
        if (checkAll) checkAll.checked = false;
      });
    }
  </script>
@endpush