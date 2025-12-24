@extends('layouts.app')
@section('title', 'Leger & Rapor Wali Kelas')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <a href="{{ route('grading.homeroom.index') }}"
              class="text-decoration-none btn btn-outline-secondary btn-sm rounded mb-2">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
        <h4 class="fw-bold mb-1">Leger & Rapor: {{ $classroom->name }}</h4>
        <small class="text-muted">Tahun Ajaran: {{ $classroom->academicYear->name }}
          ({{ $classroom->academicYear->semester }})</small>
      </div>
      <button onclick="document.getElementById('formReport').submit()" class="btn btn-primary shadow-sm">
        <i class="bi bi-save me-2"></i> Simpan Perubahan
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-body p-0">
        <form action="{{ route('grading.homeroom.update') }}" method="POST" id="formReport">
          @csrf
          <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.85rem;">
              <thead class="bg-light text-center align-middle">
                <tr>
                  <th rowspan="2" class="px-3"
                    style="min-width:200px; position:sticky; left:0; z-index:5; background:#f8f9fa;">Nama Siswa</th>

                  {{-- Loop Header Mapel --}}
                  @foreach ($courses as $course)
                    <th rowspan="2" style="min-width:60px;" title="{{ $course->subject->name }}">
                      {{ $course->subject->code }}
                    </th>
                  @endforeach

                  <th colspan="3">Ketidakhadiran</th>
                  <th rowspan="2" style="min-width:150px;">Catatan Wali Kelas</th>
                  @if ($classroom->academicYear->semester == 'Genap')
                    <th rowspan="2" style="min-width:120px;">Keputusan</th>
                  @endif
                  <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                  <th width="50" class="text-success">S</th>
                  <th width="50" class="text-primary">I</th>
                  <th width="50" class="text-danger">A</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($students as $student)
                  @php
                    $report = $reportCards[$student->id] ?? null;
                  @endphp
                  <tr>
                    {{-- Nama Siswa (Sticky Left) --}}
                    <td class="fw-bold bg-white" style="position:sticky; left:0; z-index:2;">
                      {{ $student->name }}
                    </td>

                    {{-- Nilai Mapel --}}
                    @foreach ($courses as $course)
                      @php
                        // Cari nilai mapel ini di koleksi grades siswa
                        $studentGrades = $grades[$student->id] ?? collect();
                        $grade = $studentGrades->where('course_id', $course->id)->first();
                      @endphp
                      <td
                        class="text-center {{ $grade && $grade->score_final < $course->kkm ? 'text-danger fw-bold' : '' }}">
                        {{ $grade->score_final ?? '-' }}
                      </td>
                    @endforeach

                    {{-- Input Absensi --}}
                    <td><input type="number" name="report[{{ $student->id }}][sick]" value="{{ $report->sick ?? 0 }}"
                        class="form-control form-control-sm text-center border-0 bg-light p-0" size="4"></td>
                    <td><input type="number" name="report[{{ $student->id }}][permission]"
                        value="{{ $report->permission ?? 0 }}"
                        class="form-control form-control-sm text-center border-0 bg-light p-0"></td>
                    <td><input type="number" name="report[{{ $student->id }}][absent]"
                        value="{{ $report->absent ?? 0 }}"
                        class="form-control form-control-sm text-center border-0 bg-light p-0"></td>

                    {{-- Catatan --}}
                    <td>
                      <textarea name="report[{{ $student->id }}][notes]" class="form-control form-control-sm border-0 bg-light"
                        rows="1">{{ $report->notes ?? '' }}</textarea>
                    </td>

                    {{-- Status --}}
                    @if ($classroom->academicYear->semester == 'Genap')
                      <td>
                        <select name="report[{{ $student->id }}][status]"
                          class="form-select form-select-sm border-0 bg-light">
                          <option {{ ($report->status ?? '') == 'Naik Kelas' ? 'selected' : '' }}>Naik Kelas</option>
                          <option {{ ($report->status ?? '') == 'Tinggal Kelas' ? 'selected' : '' }}
                            value="Tinggal Kelas" class="text-danger">Tinggal Kelas</option>
                          <option {{ ($report->status ?? '') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                        </select>
                      </td>
                    @endif

                    {{-- Tombol Lihat dan Cetak --}}
                    <td class="text-center">
                      <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('grading.homeroom.preview', ['studentId' => $student->id, 'classroomId' => $classroom->id]) }}"
                          target="_blank" class="btn btn-outline-info" title="Lihat Rapor">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('grading.homeroom.print', ['studentId' => $student->id, 'classroomId' => $classroom->id]) }}"
                          target="_blank" class="btn btn-outline-dark" title="Cetak PDF">
                          <i class="bi bi-printer"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  {{-- sweetAlert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Notifikasi Sukses
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @endif
  </script>
@endpush
