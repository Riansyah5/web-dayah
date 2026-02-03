@extends('layouts.app')
@section('title', 'Absen Santri')
@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="fw-bold mb-0">Daftar Kehadiran Santri</h5>
            <small class="text-muted">Sesi: {{ $journal->schedule->session_name }}</small>
        </div>
        <a href="{{ route('tahfizh.journal.dashboard') }}" class="btn btn-light border rounded-pill btn-sm">
            <i class="bi bi-house me-1"></i> Dashboard
        </a>
    </div>

    <div class="alert alert-success d-flex align-items-center py-2 px-3 rounded-4 mb-4">
        <div class="me-auto">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Halaqah Sudah Dibuka</strong> <small>({{ $journal->clock_in->format('H:i') }})</small>
        </div>
        </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <form id="attendanceForm" action="{{ route('tahfizh.journal.update_attendance', $journal->id) }}" method="POST">
                @csrf
                
                <div class="list-group list-group-flush rounded-4">
                    @foreach($students as $student)
                        @php
                            // Ambil status tersimpan, default ke 'present' jika belum ada
                            $existing = $attendances[$student->id] ?? null;
                            $status = $existing ? $existing->status : 'present'; 
                            $note = $existing ? $existing->note : '';
                        @endphp

                        <div class="list-group-item p-3">
                            <div class="d-md-flex justify-content-between align-items-center">
                                <div class="mb-2 mb-md-0">
                                    <span class="fw-bold d-block">{{ $student->name }}</span>
                                    <small class="text-muted">{{ $student->nis }}</small>
                                </div>

                                <div class="d-flex flex-column align-items-end gap-2">
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="h_{{ $student->id }}" value="present" {{ $status == 'present' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success btn-sm" for="h_{{ $student->id }}">H</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="t_{{ $student->id }}" value="late" {{ $status == 'late' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary btn-sm fw-bold" for="t_{{ $student->id }}" title="Terlambat">T</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="s_{{ $student->id }}" value="sick" {{ $status == 'sick' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm" for="s_{{ $student->id }}">S</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="i_{{ $student->id }}" value="permission" {{ $status == 'permission' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning btn-sm" for="i_{{ $student->id }}">I</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="a_{{ $student->id }}" value="alpha" {{ $status == 'alpha' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger btn-sm" for="a_{{ $student->id }}">A</label>
                                    </div>
                                    <input type="text" name="student_notes[{{ $student->id }}]" class="form-control form-control-sm" placeholder="Keterangan..." value="{{ $note }}" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 bg-light rounded-bottom-4">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i> Simpan / Update Absensi Santri
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi Flash Message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json(session('error')),
        });
    @endif

    // Konfirmasi Submit
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Absensi?',
            text: "Pastikan data kehadiran santri sudah sesuai.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
@endpush
