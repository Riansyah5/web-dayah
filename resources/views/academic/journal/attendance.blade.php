@extends('layouts.app')
@section('title', 'Presensi Siswa')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-1">Presensi Siswa</h5>
            <p class="mb-0 opacity-75">
              {{ $journal->lessonSchedule->subject->name }} -
              Kelas {{ $journal->lessonSchedule->classroom->name }}
            </p>
          </div>
        </div>

        <form action="{{ route('academic.journal.store_attendance', $journal->id) }}" method="POST">
          @csrf

          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="list-group list-group-flush rounded-4">

              @foreach ($students as $student)
                @php
                  // Ambil data lama jika ada (untuk edit), default 'present'
                  $oldData = $existingAttendance[$student->id] ?? null;
                  $status = $oldData ? $oldData->status : 'present';
                  $note = $oldData ? $oldData->note : '';
                @endphp

                <div class="list-group-item p-3">
                  <div class="d-md-flex justify-content-between align-items-center">

                    <div class="mb-2 mb-md-0">
                      <div class="fw-bold">{{ $student->name }}</div>
                      <small class="text-muted">{{ $student->nis }}</small>
                    </div>

                    <div class="d-flex flex-column align-items-end gap-2">
                      <div class="btn-group w-100" role="group">

                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]"
                          id="h_{{ $student->id }}" value="present" {{ $status == 'present' ? 'checked' : '' }}>
                        <label class="btn btn-outline-success" for="h_{{ $student->id }}">H</label>

                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]"
                          id="s_{{ $student->id }}" value="sick" {{ $status == 'sick' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="s_{{ $student->id }}">S</label>

                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]"
                          id="i_{{ $student->id }}" value="permission" {{ $status == 'permission' ? 'checked' : '' }}>
                        <label class="btn btn-outline-warning" for="i_{{ $student->id }}">I</label>

                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]"
                          id="a_{{ $student->id }}" value="alpha" {{ $status == 'alpha' ? 'checked' : '' }}>
                        <label class="btn btn-outline-danger" for="a_{{ $student->id }}">A</label>
                      </div>

                      <input type="text" name="note[{{ $student->id }}]" class="form-control form-control-sm"
                        placeholder="Keterangan..." value="{{ $note }}" style="max-width: 200px;">
                    </div>

                  </div>
                </div>
              @endforeach

            </div>
          </div>

          <div class="d-grid pb-5">
            <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold shadow">
              <i class="bi bi-check-circle-fill me-2"></i> Simpan Semua Data
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
