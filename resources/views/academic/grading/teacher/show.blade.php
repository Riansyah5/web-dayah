@extends('layouts.app')
@section('title', 'Input Nilai - ' . $course->subject->name . ' (' . $course->classroom->name . ')')
@push('link')
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('grading.teacher.index') }}" class="text-muted small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali</a>
            <h4 class="fw-bold mt-1 mb-0">{{ $course->subject->name }} - {{ $course->classroom->name }}</h4>
            <small class="text-muted">Guru: {{ $course->teacher->name ?? '-' }} | KKM: {{ $course->kkm }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('grading.teacher.export', $course->id) }}" class="btn btn-success text-white shadow-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Download Template
            </a>
            <button class="btn btn-outline-success shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i> Upload Nilai
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <form action="{{ route('grading.teacher.update', $course->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th class="text-start ps-4">Siswa</th>
                                <th width="15%">Nilai Harian</th>
                                <th width="15%">Nilai UTS</th>
                                <th width="15%">Nilai UAS</th>
                                <th width="10%">Akhir</th>
                                <th width="10%">Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->classroom->students as $student)
                                @php
                                    $grade = $grades->get($student->id);
                                @endphp
                                <tr>
                                    <td class="text-start ps-4">
                                        <div class="fw-bold">{{ $student->name }}</div>
                                        <small class="text-muted">{{ $student->nis }}</small>
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" name="grades[{{ $student->id }}][harian]" 
                                            class="form-control text-center bg-light border-0" 
                                            value="{{ $grade->score_harian ?? 0 }}">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" name="grades[{{ $student->id }}][uts]" 
                                            class="form-control text-center bg-light border-0" 
                                            value="{{ $grade->score_uts ?? 0 }}">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" name="grades[{{ $student->id }}][uas]" 
                                            class="form-control text-center bg-light border-0" 
                                            value="{{ $grade->score_uas ?? 0 }}">
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        {{ $grade->score_final ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $grade->grade_letter ?? '-' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer bg-white p-3 text-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-save me-2"></i> Simpan Semua Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Upload File Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('grading.teacher.import', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File (.xlsx)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-1"></i> Pastikan Anda menggunakan file template yang baru saja didownload dari tombol "Download Template". Jangan merubah ID Sistem.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">Proses Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@endpush
