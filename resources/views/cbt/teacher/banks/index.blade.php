@extends('layouts.app')
@section('title', 'Bank Soal CBT')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Bank Soal CBT</h4>
      <small class="text-muted">Kelola kumpulan soal untuk ujian santri.</small>
    </div>
    <button class="btn btn-primary rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#createBankModal">
      <i class="bi bi-plus-lg me-1"></i> Buat Bank Soal Baru
    </button>
  </div>

  <div class="row g-4">
    @forelse($banks as $bank)
    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span class="badge bg-primary bg-opacity-10 text-primary">{{ $bank->bank_code }}</span>
            <span class="badge {{ $bank->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $bank->is_active ? 'Aktif' : 'Draft' }}</span>
          </div>
          <h5 class="fw-bold mb-1">{{ $bank->subject_name }}</h5>
          <div class="text-muted small mb-3"><i class="bi bi-tags me-1"></i> Kelas/Level: {{ $bank->level }}</div>

          <div class="d-flex align-items-center text-muted small mb-3">
            <i class="bi bi-file-text me-2"></i> {{ $bank->questions_count }} Butir Soal
          </div>
        </div>
        <div class="card-footer bg-white border-top-0 pb-3 pt-0">
          <a href="{{ route('teacher.cbt.banks.show', $bank->id) }}" class="btn btn-outline-primary w-100 rounded-pill">
            <i class="bi bi-folder2-open me-1"></i> Buka Bank Soal
          </a>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
      <i class="bi bi-inboxes fs-1 d-block mb-2"></i>
      Anda belum memiliki bank soal. Klik tombol di atas untuk membuat.
    </div>
    @endforelse
  </div>
</div>

<div class="modal fade" id="createBankModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('teacher.cbt.banks.store') }}" method="POST" class="modal-content border-0 rounded-4">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Bank Soal Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold small">Mata Pelajaran</label>
          <select name="subject_name" class="form-select" required>
            <option value="" selected disabled>Pilih Mata Pelajaran</option>
            @foreach($subjects as $subject)
            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold small">Kelas / Tingkat</label>
          <select name="level" class="form-select" required>
            <option value="" selected disabled>Pilih Kelas / Tingkat</option>
            @foreach($levels as $level)
            <option value="{{ $level->name }}">{{ $level->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary rounded-pill w-100">Simpan Bank Soal</button>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
@endpush
