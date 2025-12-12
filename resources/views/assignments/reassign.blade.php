@extends('layouts.app')
@section('title', 'Atur Ulang Kamar')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="card col-md-8 mx-auto">
    <div class="card-header bg-warning text-dark">
      <h4>Atur Ulang / Pindah Kamar Santri</h4>
      <small>Tahun Ajaran: {{ $activeYear->name }} ({{ $activeYear->semester }})</small>
    </div>
    <div class="card-body">

      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="alert alert-light border">
        <i class="bi bi-info-circle"></i> Halaman ini menampilkan seluruh santri. Gunakan untuk memindahkan santri ke kamar baru secara massal.
      </div>

      <form action="{{ route('assignments.reassign.store') }}" method="POST">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">

        <div class="mb-3">
          <label class="form-label fw-bold">Pilih Santri (Checklist)</label>
          <div class="accordion" id="accordionStudents" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem;">
            @forelse($students->groupBy('class_group') as $classGroup => $groupStudents)
              @php $groupId = 'group_' . \Illuminate\Support\Str::slug($classGroup ?? 'no-class') . '_' . $loop->index; @endphp
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed py-2 bg-light" type="button" data-bs-toggle="collapse"
                    data-bs-target="#{{ $groupId }}">
                    <strong>Kelas: {{ $classGroup ?: 'Belum Ada Kelas' }}</strong>
                    <span class="badge bg-secondary ms-2">{{ $groupStudents->count() }} Santri</span>
                  </button>
                </h2>
                <div id="{{ $groupId }}" class="accordion-collapse collapse" data-bs-parent="#accordionStudents">
                  <div class="accordion-body">
                    <div class="row">
                      @foreach ($groupStudents as $student)
                        <div class="col-md-6">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="student_ids[]"
                              value="{{ $student->id }}" id="s_{{ $student->id }}">
                            <label class="form-check-label" for="s_{{ $student->id }}">
                              {{ $student->name }} <small class="text-muted">({{ $student->nis }})</small>
                            </label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="p-3 text-center text-muted">Tidak ada data santri.</div>
            @endforelse
          </div>
          <div class="form-text">Klik nama kelas untuk melihat daftar santri.</div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Pilih Kamar Tujuan</label>
          <select name="room_id" class="form-select" required>
            <option value="">-- Pilih Kamar Baru --</option>
            @foreach ($rooms as $room)
              @php
                // Hitung sisa kapasitas
                $filled = $room->assignments()->where('academic_year_id', $activeYear->id)->count();
                $remaining = $room->capacity - $filled;
              @endphp

              <option value="{{ $room->id }}" {{ $remaining <= 0 ? 'disabled' : '' }}>
                {{ $room->dorm->name }} - {{ $room->name }}
                (Sisa: {{ $remaining }} Bed)
              </option>
            @endforeach
          </select>
        </div>

        <button type="submit" class="btn btn-warning">Pindahkan Santri</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
@endsection
@push('scripts')
@endpush