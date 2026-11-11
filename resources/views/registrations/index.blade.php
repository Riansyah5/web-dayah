@extends('layouts.app')

@section('title', 'Penerimaan Santri Baru (PSB)')

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

    .stat-card {
      position: relative;
      overflow: hidden;
      padding: 1.25rem;
    }
    .stat-card .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }
    .soft-primary { background: var(--berry-primary-light); color: var(--berry-primary); }
    .soft-success { background: #e8f5e9; color: #00a152; }
    .soft-warning { background: var(--berry-warning-light); color: #e69500; }
    .soft-danger  { background: var(--berry-danger-light); color: var(--berry-danger); }
    .soft-secondary { background: var(--berry-secondary-light); color: var(--berry-secondary); }

    .badge-soft {
      font-weight: 500;
      border-radius: 6px;
      padding: .35em .65em;
      font-size: .75rem;
    }

    .table > :not(caption) > * > * {
      padding: .85rem 1rem;
      vertical-align: middle;
      border-color: #f1f3f5;
    }
    .table thead th {
      background: #f8fafc;
      color: #4b5563;
      font-weight: 600;
      font-size: .8125rem;
      text-transform: uppercase;
      letter-spacing: .03em;
      border-bottom: 1px solid var(--berry-border);
    }
  </style>
@endpush

@section('content')
<div class="row">
  <div class="col-12 mb-3">
    <div class="berry-header d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item">Kesiswaan</li>
            <li class="breadcrumb-item active">Penerimaan Santri Baru</li>
          </ol>
        </nav>
        <h4 class="page-title">Penerimaan Santri Baru (PSB)</h4>
      </div>
      <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('psb.register') }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
          <i class="ti ti-external-link"></i>
          <span>Buka Form Pendaftaran</span>
        </a>
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" onclick="copyPsbLink()">
          <i class="ti ti-copy"></i>
          <span>Salin Link PSB</span>
        </button>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="col-12 mb-3">
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 m-0" role="alert">
        <i class="ti ti-circle-check fs-4"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="col-12 mb-3">
      <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 m-0" role="alert">
        <i class="ti ti-alert-circle fs-4"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <!-- STATISTIK PSB -->
  <div class="col-12 mb-4">
    <div class="row g-3">
      <div class="col-12 col-sm-6 col-xl">
        <div class="berry-card stat-card">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-muted small fw-medium">Total Pendaftar</span>
            <span class="stat-icon soft-primary"><i class="ti ti-users"></i></span>
          </div>
          <h3 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h3>
          <span class="text-muted small">Tahun {{ $activeYear?->name ?? 'Semua' }}</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl">
        <div class="berry-card stat-card">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-muted small fw-medium">Menunggu Verifikasi</span>
            <span class="stat-icon soft-warning"><i class="ti ti-clock"></i></span>
          </div>
          <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending'] }}</h3>
          <span class="text-muted small">Perlu ditinjau</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl">
        <div class="berry-card stat-card">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-muted small fw-medium">Berkas Terverifikasi</span>
            <span class="stat-icon soft-primary"><i class="ti ti-file-check"></i></span>
          </div>
          <h3 class="fw-bold mb-0 text-primary">{{ $stats['verified'] }}</h3>
          <span class="text-muted small">Siap seleksi</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl">
        <div class="berry-card stat-card">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-muted small fw-medium">Lulus Seleksi</span>
            <span class="stat-icon soft-success"><i class="ti ti-award"></i></span>
          </div>
          <h3 class="fw-bold mb-0 text-success">{{ $stats['passed'] }}</h3>
          <span class="text-muted small">Menunggu daftar ulang</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl">
        <div class="berry-card stat-card">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-muted small fw-medium">Santri Aktif</span>
            <span class="stat-icon soft-secondary"><i class="ti ti-user-check"></i></span>
          </div>
          <h3 class="fw-bold mb-0 text-secondary">{{ $stats['enrolled'] }}</h3>
          <span class="text-muted small">Sudah dibuatkan NIS</span>
        </div>
      </div>
    </div>
  </div>

  <!-- FILTER & TABEL PENDAFTAR -->
  <div class="col-12">
    <div class="berry-card">
      <div class="p-3 border-bottom">
        <form action="{{ route('registrations.index') }}" method="GET" class="row g-2 align-items-center">
          <div class="col-12 col-md-3">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-muted"></i></span>
              <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama, no. reg, NIK..." value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-6 col-md-2">
            <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
              <option value="">Semua Tahun Ajaran</option>
              @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ (request('academic_year_id', $activeYear?->id) == $year->id) ? 'selected' : '' }}>
                  {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <select name="stage" class="form-select" onchange="this.form.submit()">
              <option value="">Semua Jenjang</option>
              @foreach($stages as $stg)
                <option value="{{ $stg->name }}" {{ request('stage') == $stg->name ? 'selected' : '' }}>{{ $stg->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
              <option value="">Semua Status</option>
              <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
              <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Berkas Terverifikasi</option>
              <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Lulus Seleksi</option>
              <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Tidak Lulus</option>
              <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Santri Aktif</option>
            </select>
          </div>
          <div class="col-6 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('registrations.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="ti ti-refresh"></i></a>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>No. Registrasi</th>
              <th>Calon Santri</th>
              <th>Jenjang & Sekolah Asal</th>
              <th>Kontak Wali / Ortu</th>
              <th>Nilai Seleksi</th>
              <th>Status</th>
              <th class="text-center" style="width: 140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $reg)
              <tr>
                <td>
                  <div class="fw-semibold text-primary font-monospace">{{ $reg->registration_number }}</div>
                  <div class="text-muted small">{{ $reg->created_at->format('d M Y, H:i') }}</div>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2 {{ $reg->gender == 'L' ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning' }}">
                      <i class="ti {{ $reg->gender == 'L' ? 'ti-man' : 'ti-woman' }}"></i>
                    </span>
                    <div>
                      <a href="{{ route('registrations.show', $reg->id) }}" class="fw-semibold text-dark text-decoration-none">
                        {{ $reg->name }}
                      </a>
                      <div class="text-muted small">
                        NIK: {{ $reg->nik ?? '-' }} | NISN: {{ $reg->nisn ?? '-' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-light text-dark border">{{ $reg->target_stage }}</span>
                  <div class="text-muted small mt-1 text-truncate" style="max-width: 180px;">
                    {{ $reg->previous_school ?: '-' }}
                  </div>
                </td>
                <td>
                  @php
                    $phone = $reg->father_phone ?: ($reg->mother_phone ?: $reg->guardian_phone);
                    $parentName = $reg->father_name ?: ($reg->mother_name ?: $reg->guardian_name);
                  @endphp
                  <div class="fw-medium text-dark">{{ $parentName ?: '-' }}</div>
                  @if($phone)
                    <a href="https://wa.me/{{ $phone }}" target="_blank" class="text-success small text-decoration-none d-inline-flex align-items-center gap-1">
                      <i class="bi bi-whatsapp"></i> +{{ $phone }}
                    </a>
                  @else
                    <span class="text-muted small">-</span>
                  @endif
                </td>
                <td>
                  @if($reg->selection_score !== null)
                    <span class="fw-bold fs-6 {{ $reg->selection_score >= 70 ? 'text-success' : 'text-danger' }}">
                      {{ number_format($reg->selection_score, 1) }}
                    </span>
                  @else
                    <span class="text-muted small">Belum diisi</span>
                  @endif
                </td>
                <td>
                  @php
                    $badgeColors = [
                      'pending' => 'bg-warning-subtle text-warning border border-warning-subtle',
                      'verified' => 'bg-info-subtle text-info border border-info-subtle',
                      'passed' => 'bg-success-subtle text-success border border-success-subtle',
                      'rejected' => 'bg-danger-subtle text-danger border border-danger-subtle',
                      'enrolled' => 'bg-primary-subtle text-primary border border-primary-subtle',
                    ];
                  @endphp
                  <span class="badge badge-soft {{ $badgeColors[$reg->status] ?? 'bg-secondary' }}">
                    {{ $reg->status_label }}
                  </span>
                </td>
                <td class="text-center">
                  <div class="d-inline-flex gap-1">
                    <a href="{{ route('registrations.show', $reg->id) }}" class="btn btn-sm btn-icon btn-light" title="Detail & Verifikasi">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="{{ route('psb.slip', $reg->id) }}" target="_blank" class="btn btn-sm btn-icon btn-light" title="Cetak Bukti">
                      <i class="ti ti-printer"></i>
                    </a>
                    <form action="{{ route('registrations.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar {{ $reg->name }}?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-icon btn-light text-danger" title="Hapus">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5">
                  <div class="text-muted">
                    <i class="ti ti-file-search fs-1 d-block mb-2 text-secondary"></i>
                    <p class="mb-0 fw-medium">Belum ada data pendaftar santri baru.</p>
                    <small>Bagikan tautan pendaftaran PSB agar calon santri dapat mendaftar secara online.</small>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($registrations->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
          <div class="text-muted small">
            Menampilkan {{ $registrations->firstItem() }} sampai {{ $registrations->lastItem() }} dari {{ $registrations->total() }} pendaftar
          </div>
          <div>
            {{ $registrations->links() }}
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function copyPsbLink() {
    const link = "{{ route('psb.register') }}";
    navigator.clipboard.writeText(link).then(() => {
      alert('Tautan pendaftaran PSB online berhasil disalin:\n' + link);
    }).catch(err => {
      prompt('Salin tautan formulir PSB di bawah ini:', link);
    });
  }
</script>
@endpush
