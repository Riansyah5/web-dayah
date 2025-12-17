@extends('layouts.app')
@section('title', 'Pengaturan KBM (Plotting Guru)')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="mb-4">
      <h4 class="fw-bold mb-1">Pengaturan KBM (Plotting Guru)</h4>
      <p class="text-muted small">Tentukan Mata Pelajaran, Guru Pengampu, dan KKM untuk setiap kelas.</p>
    </div>

    <div class="row">
      <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white fw-bold py-3">Pilih Kelas</div>
          <div class="list-group list-group-flush rounded-bottom-4">
            @forelse($classrooms as $cls)
              <a href="{{ route('grading.plotting.index', ['classroom_id' => $cls->id]) }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedClassroom?->id == $cls->id ? 'active fw-bold' : '' }}">
                {{ $cls->name }}
                @if ($selectedClassroom?->id == $cls->id)
                  <i class="bi bi-chevron-right"></i>
                @endif
              </a>
            @empty
              <div class="p-3 text-muted small text-center">Belum ada kelas aktif.</div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-md-9">
        @if ($selectedClassroom)
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <div>
                <h6 class="fw-bold mb-0">Plotting: {{ $selectedClassroom->name }}</h6>
                <small class="text-muted">{{ $activeYear->name ?? '' }}</small>
              </div>
              {{-- Tombol Import Excel nanti ditaruh disini --}}
              <button class="btn btn-sm btn-success disabled" title="Fitur akan datang"><i
                  class="bi bi-file-earmark-excel me-1"></i> Import Excel</button>
            </div>

            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th class="ps-4">Mata Pelajaran</th>
                      <th width="35%">Guru Pengampu</th>
                      <th width="15%">KKM</th>
                      <th width="10%">Simpan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $currentGroup = ''; @endphp

                    @foreach ($subjects as $subject)
                      {{-- Grouping Header (Kelompok A, B, dll) --}}
                      @if ($currentGroup != $subject->group)
                        @php $currentGroup = $subject->group; @endphp
                        <tr class="table-secondary">
                          <td colspan="4" class="fw-bold ps-4 small text-uppercase">Kelompok {{ $currentGroup }}</td>
                        </tr>
                      @endif

                      {{-- Ambil Data Existing jika ada --}}
                      @php
                        $course = $courses->get($subject->id);
                      @endphp

                      <tr>
                        {{-- Form per baris --}}
                        <form action="{{ route('grading.plotting.update') }}" method="POST">
                          @csrf
                          <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">
                          <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                          <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $subject->name }}</div>
                            <small class="text-muted">{{ $subject->code }}</small>
                          </td>

                          <td>
                            <select name="teacher_id" class="form-select form-select-sm">
                              <option value="">-- Pilih Guru --</option>
                              @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                  {{ $course?->teacher_id == $teacher->id ? 'selected' : '' }}>
                                  {{ $teacher->name }} {{ $teacher->title }}
                                </option>
                              @endforeach
                            </select>
                          </td>

                          <td>
                            <input type="number" name="kkm" class="form-control form-control-sm text-center"
                              value="{{ $course?->kkm ?? 70 }}" min="0" max="100">
                          </td>

                          <td>
                            <button type="submit" class="btn btn-sm btn-primary">
                              <i class="bi bi-check-lg"></i>
                            </button>
                          </td>
                        </form>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="alert alert-info mt-3 small border-0 shadow-sm">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Tips:</strong> Klik tombol centang (<i class="bi bi-check-lg"></i>) di setiap baris untuk menyimpan
            perubahan Guru/KKM mapel tersebut.
          </div>
        @else
          {{-- State Kosong (Belum pilih kelas) --}}
          <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
            <div class="card-body">
              <img src="https://cdn-icons-png.flaticon.com/512/10302/10302224.png" width="80" class="opacity-50 mb-3">
              <h5 class="fw-bold text-muted">Pilih Kelas Terlebih Dahulu</h5>
              <p class="text-muted small">Silakan pilih kelas di menu sebelah kiri untuk mengatur jadwal/plotting guru.
              </p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
