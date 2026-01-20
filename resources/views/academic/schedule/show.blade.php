@extends('layouts.app')
@section('title', 'Atur Jadwal Pelajaran')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
      <div class="d-flex align-items-center">
        <a href="{{ route('academic.schedule.index') }}" class="btn btn-light rounded-circle me-3"><i
            class="bi bi-arrow-left"></i></a>
        <div>
          <h4 class="fw-bold mb-0">Atur Jadwal: {{ $classroom->name }}</h4>
          <p class="text-muted small mb-0">Tahun Ajaran: {{ $classroom->academicYear->name }}</p>
        </div>
      </div>
      <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
        <i class="bi bi-plus-lg me-2"></i> Tambah Jadwal
      </button>
    </div>

    <div class="row g-4">
      @php
        $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
      @endphp

      @foreach ($days as $dayNum => $dayName)
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-header bg-white py-3 border-bottom fw-bold d-flex justify-content-between">
              <span>{{ $dayName }}</span>
              <span
                class="badge bg-light text-dark rounded-pill">{{ isset($schedules[$dayNum]) ? count($schedules[$dayNum]) : 0 }}
                Mapel</span>
            </div>
            <div class="list-group list-group-flush rounded-bottom-4">
              @if (isset($schedules[$dayNum]))
                @foreach ($schedules[$dayNum] as $item)
                  <div class="list-group-item p-3 border-bottom-0 border-top">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <span class="badge bg-primary mb-1">
                          {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} -
                          {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                        </span>
                        <h6 class="fw-bold mb-0 text-dark mt-1">{{ $item->subject->name }}</h6>
                        <small class="text-muted d-block">{{ $item->teacher->name }}</small>
                      </div>

                      <form action="{{ route('academic.schedule.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('Hapus jadwal ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-light text-danger rounded-circle p-2 lh-1">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="p-4 text-center text-muted small">
                  <em>Belum ada jadwal</em>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content rounded-4 border-0">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold">Tambah Jadwal Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('academic.schedule.store', $classroom->id) }}" method="POST">
          @csrf
          <div class="modal-body">

            <div class="mb-3">
              <label class="form-label small text-muted">Hari</label>
              <select name="day_of_week" class="form-select bg-light" required>
                <option value="">-- Pilih Hari --</option>
                <option value="1">Senin</option>
                <option value="2">Selasa</option>
                <option value="3">Rabu</option>
                <option value="4">Kamis</option>
                <option value="5">Jumat</option>
                <option value="6">Sabtu</option>
              </select>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small text-muted">Jam Mulai</label>
                <input type="time" name="start_time" class="form-control" required>
              </div>
              <div class="col-6">
                <label class="form-label small text-muted">Jam Selesai</label>
                <input type="time" name="end_time" class="form-control" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label small text-muted">Mata Pelajaran</label>
              <select name="subject_id" class="form-select select2" required style="width: 100%;">
                <option value="">-- Cari Mapel --</option>
                @foreach ($subjects as $sub)
                  <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label small text-muted">Guru Pengampu</label>
              <select name="teacher_id" class="form-select select2" required style="width: 100%;">
                <option value="">-- Cari Guru --</option>
                @foreach ($teachers as $t)
                  <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
              </select>
            </div>

          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Jadwal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
