@extends('layouts.app')
@section('title', 'Rekap Absensi Siswa Per Mapel')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-4">Rekap Absensi Siswa Per Mapel</h4>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-body p-4">
        <form class="row g-3">
          <div class="col-md-5">
            <label class="form-label small text-muted">Kelas</label>
            <select name="classroom_id" class="form-select" required>
              <option value="">-- Pilih Kelas --</option>
              @foreach ($classrooms as $c)
                <option value="{{ $c->id }}" {{ $request->classroom_id == $c->id ? 'selected' : '' }}>
                  {{ $c->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-5">
            <label class="form-label small text-muted">Mata Pelajaran</label>
            <select name="subject_id" class="form-select" required>
              <option value="">-- Pilih Mapel --</option>
              @foreach ($subjects as $s)
                <option value="{{ $s->id }}" {{ $request->subject_id == $s->id ? 'selected' : '' }}>
                  {{ $s->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 rounded-pill">Tampilkan</button>
          </div>
        </form>
      </div>
    </div>

    @if (!empty($attendanceRecap))
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
          <table class="table table-bordered mb-0">
            <thead class="bg-light text-center">
              <tr>
                <th width="5%">No</th>
                <th width="40%" class="text-start">Nama Siswa</th>
                <th width="10%">Hadir</th>
                <th width="10%">Sakit</th>
                <th width="10%">Izin</th>
                <th width="10%">Alpha</th>
                <th width="15%">Persentase</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach ($attendanceRecap as $idx => $student)
                <tr>
                  <td>{{ $idx + 1 }}</td>
                  <td class="text-start fw-bold">{{ $student->name }}</td>
                  <td>{{ $student->h }}</td>
                  <td>{{ $student->s }}</td>
                  <td>{{ $student->i }}</td>
                  <td class="{{ $student->a > 0 ? 'text-danger fw-bold' : '' }}">{{ $student->a }}</td>
                  <td>
                    @if ($student->percent < 75)
                      <span class="badge bg-danger">{{ $student->percent }}%</span>
                    @elseif($student->percent < 90)
                      <span class="badge bg-warning text-dark">{{ $student->percent }}%</span>
                    @else
                      <span class="badge bg-success">{{ $student->percent }}%</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @elseif($request->classroom_id)
      <div class="alert alert-info text-center">Belum ada data jurnal untuk mapel dan kelas ini.</div>
    @endif
  </div>
@endsection
@push('scripts')
@endpush
