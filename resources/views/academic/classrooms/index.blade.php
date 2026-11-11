@extends('layouts.app')

@section('title', 'Classrooms')

@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@push('styles')
  <style>
    /* ===== Berry-style helpers (aman dipakai walau class bawaan template berbeda) ===== */
    :root {
      --berry-primary: #2196f3;
      --berry-primary-light: #e3f2fd;
      --berry-secondary: #5e35b1;
      --berry-secondary-light: #ede7f6;
      --berry-success: #00c853;
      --berry-success-light: #b9f6ca;
      --berry-warning: #ffab00;
      --berry-warning-light: #fff8e1;
      --berry-danger: #f44336;
      --berry-danger-light: #fbe9e7;
      --berry-border: #e3e8ef;
      --berry-heading: #121926;
      --berry-text: #697586;
    }

    .berry-header {
      background: #fff;
      border-radius: 12px;
      border: 1px solid var(--berry-border);
      padding: 1.25rem 1.5rem;
    }
    .berry-header .page-title { color: var(--berry-heading); font-weight: 600; margin: 0; }
    .berry-header .breadcrumb { margin: 0 0 .35rem; font-size: .8125rem; }
    .berry-header .breadcrumb-item a { color: var(--berry-text); text-decoration: none; }
    .berry-header .breadcrumb-item.active { color: var(--berry-secondary); }

    .berry-card {
      background: #fff;
      border: 1px solid var(--berry-border);
      border-radius: 12px;
      box-shadow: none;
      transition: box-shadow .2s ease, transform .2s ease;
    }
    .berry-card.hoverable:hover {
      box-shadow: 0 8px 24px rgba(33, 150, 243, .12);
      transform: translateY(-2px);
    }

    .level-title {
      display: flex; align-items: center; gap: .6rem;
      color: var(--berry-heading); font-weight: 600; font-size: 1.05rem;
    }
    .level-title .level-icon {
      width: 34px; height: 34px; border-radius: 8px;
      display: inline-flex; align-items: center; justify-content: center;
      background: var(--berry-secondary-light); color: var(--berry-secondary);
    }
    .level-title .level-count {
      font-size: .75rem; font-weight: 500;
      background: var(--berry-primary-light); color: var(--berry-primary);
      border-radius: 20px; padding: .15rem .6rem;
    }

    .avtar {
      width: 44px; height: 44px; border-radius: 10px;
      display: inline-flex; align-items: center; justify-content: center;
      font-size: 1.25rem;
    }
    .soft-primary { background: var(--berry-primary-light); color: var(--berry-primary); }
    .soft-success { background: #e8f5e9; color: #00a152; }
    .soft-warning { background: var(--berry-warning-light); color: #e69500; }
    .soft-danger  { background: var(--berry-danger-light); color: var(--berry-danger); }
    .soft-secondary { background: var(--berry-secondary-light); color: var(--berry-secondary); }

    .badge-soft { font-weight: 500; border-radius: 6px; padding: .35em .65em; }

    .class-name { color: var(--berry-heading); font-weight: 600; }
    .class-meta { color: var(--berry-text); font-size: .8125rem; }

    .progress.berry-progress { height: 6px; border-radius: 10px; background: #eef2f6; }

    .btn-light-primary {
      background: var(--berry-primary-light); color: var(--berry-primary);
      border: 0; font-weight: 500; border-radius: 8px;
    }
    .btn-light-primary:hover { background: var(--berry-primary); color: #fff; }

    .btn-berry {
      background: var(--berry-secondary); color: #fff; border: 0;
      border-radius: 8px; font-weight: 500;
    }
    .btn-berry:hover { background: #4527a0; color: #fff; }

    .btn-icon-soft {
      width: 32px; height: 32px; padding: 0; border-radius: 8px;
      display: inline-flex; align-items: center; justify-content: center;
      background: transparent; border: 0; color: var(--berry-text);
    }
    .btn-icon-soft:hover { background: #eef2f6; color: var(--berry-heading); }

    .form-select, .form-control { border-radius: 8px; border-color: #dbe0e5; }
    .form-select:focus, .form-control:focus {
      border-color: var(--berry-secondary);
      box-shadow: 0 0 0 .2rem rgba(94, 53, 177, .12);
    }

    .dropdown-menu { border: 1px solid var(--berry-border); border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.08); padding: .4rem; }
    .dropdown-item { border-radius: 6px; font-size: .875rem; }

    .modal-content { border: 0; border-radius: 14px; }
    .modal-header { border-bottom: 1px solid var(--berry-border); }
    .modal-footer { border-top: 1px solid var(--berry-border); }
    .form-label { font-weight: 500; font-size: .8125rem; color: var(--berry-heading); }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--berry-text); }
    .empty-state i { font-size: 2.5rem; color: #cdd5df; }
  </style>
@endpush

@section('content')
  <div class="container-fluid py-4">

    {{-- ===== Page Header ===== --}}
    <div class="berry-header mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
              <li class="breadcrumb-item active" aria-current="page">Kelas</li>
            </ol>
          </nav>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <h4 class="page-title">Daftar Kelas</h4>
            @if ($activeYear)
              <span class="badge badge-soft soft-success">
                <i class="bi bi-calendar-check me-1"></i>TA: {{ $activeYear->name }} ({{ $activeYear->semester }})
              </span>
            @endif
          </div>
        </div>

        <div class="d-flex gap-2 align-items-center">
          <form action="{{ route('classrooms.index') }}" method="GET">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;">
                <i class="bi bi-funnel text-muted"></i>
              </span>
              <select name="stage_id" class="form-select border-start-0" onchange="this.form.submit()">
                <option value="">Semua Jenjang</option>
                @foreach ($stages as $s)
                  <option value="{{ $s->id }}" {{ request('stage_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->code }}
                  </option>
                @endforeach
              </select>
            </div>
          </form>

          @can('kelola-kelas')
            <button class="btn btn-berry" data-bs-toggle="modal" data-bs-target="#createModal">
              <i class="bi bi-plus-lg me-1"></i> Kelas
            </button>
          @endcan
        </div>
      </div>
    </div>

    @php
      $groupedClassrooms = $classrooms->groupBy(function ($item) {
          return $item->level->name ?? 'Lainnya';
      });
    @endphp

    @forelse ($groupedClassrooms as $levelName => $classroomsInLevel)
      <div class="level-title mt-4 mb-3">
        <span class="level-icon"><i class="bi bi-layers-fill"></i></span>
        <span>{{ $levelName }}</span>
        <span class="level-count">{{ $classroomsInLevel->count() }} Kelas</span>
      </div>

      <div class="row g-3">
        @foreach ($classroomsInLevel as $c)
          @php
            $stageCode = $c->level->stage->code ?? '';
            $tone = match ($stageCode) {
                'SD', 'MI', 'ULA' => 'primary',
                'SMP', 'MTS', 'WUSTHA' => 'success',
                'SMA', 'MA', 'ULYA' => 'warning',
                default => 'primary',
            };

            $capacity = (int) ($c->capacity ?? 0);
            $count = (int) ($c->students_count ?? 0);
            $pct = $capacity > 0 ? min(100, round(($count / $capacity) * 100)) : 0;
            $barColor = $pct >= 100 ? 'bg-danger' : ($pct >= 80 ? 'bg-warning' : 'bg-success');
          @endphp

          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="berry-card hoverable h-100">
              <div class="card-body p-3">

                <div class="d-flex justify-content-between align-items-start mb-3">
                  <span class="avtar soft-{{ $tone }}">
                    <i class="bi bi-mortarboard-fill"></i>
                  </span>

                  <div class="d-flex align-items-center gap-1">
                    <span class="badge badge-soft soft-{{ $tone }}">{{ $stageCode }}</span>
                    <div class="dropdown">
                      <button class="btn-icon-soft" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                          <a href="{{ route('classrooms.edit', $c->id) }}" class="dropdown-item">
                            <i class="bi bi-pencil-square me-2"></i>Edit
                          </a>
                        </li>
                        <li>
                          <form action="{{ route('classrooms.destroy', $c->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="dropdown-item text-danger btn-delete">
                              <i class="bi bi-trash me-2"></i>Hapus
                            </button>
                          </form>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

                <h5 class="class-name mb-1">{{ $c->name }}</h5>
                <p class="class-meta mb-3">
                  <i class="bi bi-person-badge me-1"></i>Wali: {{ $c->homeroom_teacher ?? '-' }}
                </p>

                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="class-meta">Kapasitas</small>
                  <small class="fw-semibold" style="color: var(--berry-heading)">
                    {{ $count }} / {{ $capacity }} Siswa
                  </small>
                </div>
                {{-- <div class="progress berry-progress mb-3">
                  <div class="progress-bar {{ $barColor }}" role="progressbar"
                    style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0"
                    aria-valuemax="100"></div>
                </div> --}}
                <div class="d-flex justify-content-end">
                  <a href="{{ route('classrooms.show', $c->id) }}" class="btn btn-outline-primary btn-sm w-30">
                    <i class="bi bi-gear me-1"></i>Kelola
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @empty
      <div class="berry-card">
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <p class="mt-2 mb-0">Belum ada data kelas.</p>
        </div>
      </div>
    @endforelse
  </div>

  {{-- ===== Modal Buat Kelas ===== --}}
  <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="fw-bold mb-0">Buat Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('classrooms.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Tingkat</label>
              <select name="level_id" class="form-select" required>
                @foreach ($levels as $l)
                  <option value="{{ $l->id }}">{{ $l->stage->code }} - {{ $l->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Jurusan</label>
              <select name="major_id" class="form-select">
                <option value="">- Umum / Tidak Ada -</option>
                @foreach ($majors as $m)
                  <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Kelas</label>
              <input name="name" class="form-control" required placeholder="Contoh: 1A">
            </div>
            <div class="mb-3">
              <label class="form-label">Wali Kelas</label>
              <select name="homeroom_teacher" class="form-select">
                <option value="">Pilih Wali Kelas...</option>
                @foreach ($teachers as $teacher)
                  <option value="{{ $teacher->nama }}">{{ $teacher->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-1">
              <label class="form-label">Kapasitas</label>
              <input name="capacity" type="number" class="form-control" value="30" min="1">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-berry px-4">Simpan</button>
          </div>
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
        text: @json(session('success')),
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
          confirmButtonColor: '#f44336',
          cancelButtonColor: '#697586',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) this.closest('form').submit();
        });
      });
    });
  </script>
@endpush