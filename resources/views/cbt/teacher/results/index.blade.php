@extends('layouts.app')
@section('title', 'Hasil & Koreksi Ujian')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="mb-4">
    <h4 class="fw-bold mb-1">Hasil & Koreksi Ujian</h4>
    <p class="text-muted small">Pilih jadwal ujian untuk melihat nilai santri dan mengoreksi essay.</p>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th class="ps-4">Nama Ujian / Bank Soal</th>
            <th>Tanggal Pelaksanaan</th>
            <th class="text-center">Peserta Ujian</th>
            <th class="text-end pe-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($exams as $exam)
          <tr>
            <td class="ps-4">
              <div class="fw-bold text-primary">{{ $exam->name }}</div>
              <small class="text-muted">{{ $exam->questionBank->subject_name }} ({{ $exam->questionBank->level }})</small>
            </td>
            <td>{{ $exam->start_time->translatedFormat('d M Y') }}</td>
            <td class="text-center">
              <span class="badge bg-success rounded-pill px-3">{{ $exam->student_exams_count }} Santri</span>
            </td>
            <td class="text-end pe-4">
              <a href="{{ route('teacher.cbt.results.show', $exam->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                Buka Rekap Nilai <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center py-5 text-muted">Belum ada jadwal ujian yang menggunakan bank soal Anda.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@push('scripts')
@endpush
