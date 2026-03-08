@extends('layouts.app')
@section('title', 'Buat Jadwal Ujian')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Buat Jadwal Ujian</h4>
    </div>
    <a href="{{ route('admin.cbt.exams.index') }}" class="btn btn-light rounded-pill border shadow-sm">
      <i class="bi bi-arrow-left me-1"></i> Batal
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
      <form action="{{ route('admin.cbt.exams.store') }}" method="POST">
        @csrf

        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold small">Nama Ujian (Label)</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: UTS Ganjil - Nahwu Kls 1" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold small">Pilih Bank Soal (Sumber Soal)</label>
            <select name="cbt_question_bank_id" class="form-select" required>
              <option value="" selected disabled>-- Pilih Mata Pelajaran --</option>
              @foreach($banks as $bank)
              <option value="{{ $bank->id }}">
                [{{ $bank->bank_code }}] {{ $bank->subject_name }} - {{ $bank->level }} ({{ $bank->questions_count }} Soal)
              </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row mb-4 bg-light p-3 rounded border">
          <div class="col-md-4 mb-3 mb-md-0">
            <label class="form-label fw-bold small text-muted"><i class="bi bi-clock me-1"></i> Waktu Mulai Ujian</label>
            <input type="datetime-local" name="start_time" class="form-control" required>
          </div>
          <div class="col-md-4 mb-3 mb-md-0">
            <label class="form-label fw-bold small text-muted"><i class="bi bi-clock-history me-1"></i> Waktu Selesai (Otomatis Tutup)</label>
            <input type="datetime-local" name="end_time" class="form-control" required>
            <div class="form-text" style="font-size: 11px;">Batas santri boleh masuk.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-bold small text-muted"><i class="bi bi-hourglass-split me-1"></i> Durasi Pengerjaan</label>
            <div class="input-group">
              <input type="number" name="duration" class="form-control" value="90" min="10" required>
              <span class="input-group-text">Menit</span>
            </div>
          </div>
        </div>

        <div class="mb-4">
          <h6 class="fw-bold mb-3">Pengaturan Keamanan & Tampilan</h6>
          <div class="row">
            <div class="col-md-4 mb-2">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="randomize_questions" id="rq" checked value="1">
                <label class="form-check-label" for="rq">Acak Urutan Soal</label>
              </div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="randomize_options" id="ro" checked value="1">
                <label class="form-check-label" for="ro">Acak Pilihan Ganda (A,B,C,D)</label>
              </div>
            </div>
            <div class="col-md-4 mb-2">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="show_result" id="sr" value="1">
                <label class="form-check-label" for="sr">Tampilkan Nilai ke Santri Selesai Ujian</label>
              </div>
            </div>
          </div>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary rounded-pill btn-lg px-5 shadow">
            <i class="bi bi-calendar-check me-2"></i> Terbitkan Jadwal & Generate Token
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
@endpush
