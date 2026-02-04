@extends('layouts.app')
@section('title', 'Evaluasi Bulanan Guru Tahfizh')
@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Evaluasi Bulanan Guru Tahfizh</h4>

    <form action="{{ route('tahfizh.admin.evaluations.index') }}" method="GET" class="d-flex gap-2">
      <input type="month" name="month" class="form-control" value="{{ $month->format('Y-m') }}" onchange="this.form.submit()">
    </form>
  </div>

  @if($evaluations->isEmpty())
  <div class="text-center py-5 bg-light rounded-4 border border-dashed">
    <i class="bi bi-file-earmark-spreadsheet fs-1 text-muted"></i>
    <p class="mt-2 text-muted">Belum ada data evaluasi untuk bulan <strong>{{ $month->translatedFormat('F Y') }}</strong>.</p>
    <a href="{{ route('tahfizh.admin.evaluations.create', ['month' => $month->format('Y-m')]) }}" class="btn btn-primary rounded-pill mt-2">
      <i class="bi bi-magic me-2"></i> Generate Otomatis
    </a>
  </div>
  @else
  @php $isLocked = $evaluations->first()->is_locked; @endphp

  <div class="alert {{ $isLocked ? 'alert-success' : 'alert-warning' }} d-flex justify-content-between align-items-center rounded-4 shadow-sm mb-4">
    <div>
      @if($isLocked)
      <i class="bi bi-lock-fill me-2"></i> <strong>TUTUP BUKU SELESAI</strong>. Data sudah dikunci dan siap untuk penggajian.
      @else
      <i class="bi bi-unlock-fill me-2"></i> Status: <strong>DRAFT</strong>. Data masih bisa digenerate ulang.
      @endif
    </div>

    @if(!$isLocked)
    <div class="d-flex gap-2">
      <a href="{{ route('tahfizh.admin.evaluations.create', ['month' => $month->format('Y-m')]) }}" class="btn btn-outline-dark btn-sm rounded-pill">
        <i class="bi bi-arrow-clockwise me-1"></i> Generate Ulang
      </a>
      <form action="{{ route('tahfizh.admin.evaluations.lock') }}" method="POST" id="lockForm">
        @csrf
        <input type="hidden" name="month" value="{{ $month->format('Y-m-d') }}">
        <button type="submit" class="btn btn-success btn-sm rounded-pill fw-bold">
          <i class="bi bi-check-circle me-1"></i> Kunci / Tutup Buku
        </button>
      </form>
    </div>
    @endif
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th class="ps-4">Nama Guru</th>
            <th class="text-center">Hadir</th>
            <th class="text-center text-primary">Badal</th>
            <th class="text-center text-warning">Izin</th>
            <th class="text-center text-danger">Alpha</th>
            <th>Catatan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($evaluations as $eval)
          <tr>
            <td class="ps-4 fw-bold">{{ $eval->teacher->name }}</td>
            <td class="text-center">{{ $eval->hadir_count }}</td>
            <td class="text-center fw-bold text-primary">+{{ $eval->badal_count }}</td>
            <td class="text-center">{{ $eval->izin_count }}</td>
            <td class="text-center">{{ $eval->alpha_count }}</td>
            <td class="small text-muted fst-italic">{{ $eval->notes ?? '-' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const lockForm = document.getElementById('lockForm');
  if (lockForm) {
    lockForm.addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Kunci Evaluasi?',
        text: "Yakin ingin Tutup Buku? Data tidak bisa diubah lagi.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kunci!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  }
</script>
@endpush
