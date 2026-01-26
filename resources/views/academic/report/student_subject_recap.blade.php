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
        <div class="col-md-3">
          <label class="form-label small text-muted">Kelas</label>
          <select name="classroom_id" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($classrooms as $c)
            <option value="{{ $c->id }}" {{ $request->classroom_id == $c->id ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label small text-muted">Mata Pelajaran</label>
          <select name="subject_id" class="form-select" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach($subjects as $s)
            <option value="{{ $s->id }}" {{ $request->subject_id == $s->id ? 'selected' : '' }}>
              {{ $s->name }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label small text-muted">Periode Waktu</label>
          <select name="period" class="form-select">
            <option value="">Semua (Satu Tahun)</option>
            <optgroup label="Semester">
              <option value="ganjil" {{ $request->period == 'ganjil' ? 'selected' : '' }}>Semester Ganjil (Jul-Des)</option>
              <option value="genap" {{ $request->period == 'genap' ? 'selected' : '' }}>Semester Genap (Jan-Jun)</option>
            </optgroup>
            <optgroup label="Bulanan">
              @foreach(range(1, 12) as $m)
              <option value="{{ $m }}" {{ $request->period == $m ? 'selected' : '' }}>
                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
              </option>
              @endforeach
            </optgroup>
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100 rounded-pill">
            <i class="bi bi-filter me-1"></i> Tampilkan
          </button>
        </div>
      </form>
    </div>
  </div>

  @if (!empty($attendanceRecap))
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <table class="table table-bordered mb-0 align-middle">
        <thead class="bg-light text-center align-middle">
          <tr>
            <th width="5%" rowspan="2">No</th>
            <th width="35%" rowspan="2" class="text-start">Nama Siswa</th>
            <th colspan="5">Rincian Kehadiran</th>
            <th width="10%" rowspan="2">Persentase<br>Kehadiran</th>
          </tr>
          <tr>
            <th width="8%" class="text-success" title="Hadir Tepat Waktu">H</th>
            <th width="8%" class="text-secondary" title="Terlambat">T</th>
            <th width="8%" class="text-primary" title="Sakit">S</th>
            <th width="8%" class="text-warning" title="Izin">I</th>
            <th width="8%" class="text-danger" title="Alpha">A</th>
          </tr>
        </thead>
        <tbody class="text-center">
          @foreach($attendanceRecap as $idx => $student)
          <tr>
            <td>{{ $idx + 1 }}</td>
            <td class="text-start">
              <div class="fw-bold">{{ $student->name }}</div>
              <small class="text-muted">{{ $student->nis }}</small>
            </td>

            <td class="bg-success bg-opacity-10 fw-bold text-success">{{ $student->h }}</td>

            <td class="{{ $student->t > 0 ? 'fw-bold text-secondary bg-warning bg-opacity-10' : 'text-muted' }}">
              {{ $student->t }}
            </td>

            <td>{{ $student->s }}</td>

            <td>{{ $student->i }}</td>

            <td class="{{ $student->a > 0 ? 'bg-danger bg-opacity-10 text-danger fw-bold' : '' }}">
              {{ $student->a }}
            </td>

            <td>
              @if($student->percent < 75) <span class="badge bg-danger w-100">{{ $student->percent }}%</span>
                @elseif($student->percent < 90) <span class="badge bg-warning text-dark w-100">{{ $student->percent }}%</span>
                  @else
                  <span class="badge bg-success w-100">{{ $student->percent }}%</span>
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
