@extends('layouts.app')
@section('title', 'Laporan Kinerja Guru')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold">Laporan Kinerja Guru (Jurnal KBM)</h4>

      <form class="d-flex gap-2">
        <select name="month" class="form-select" style="min-width: 150px;">
          @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
              {{ DateTime::createFromFormat('!m', $i)->format('F') }}
            </option>
          @endfor
        </select>
        <select name="year" class="form-select">
          <option value="2025">2025</option>
          <option value="2026">2026</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
      </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">No</th>
              <th class="ps-4">Nama Guru</th>
              <th class="text-center">Tatap Muka (Jam)</th>
              <th class="text-center">Badal (Jam)</th>
              <th class="text-center">Total Mengajar</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($recap as $index => $row)
              <tr>
                <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                <td class="ps-4 fw-bold">{{ $row['teacher']->name }}</td>
                <td class="text-center">{{ $row['main_count'] }}</td>
                <td class="text-center text-success fw-bold">
                  {{ $row['sub_count'] > 0 ? '+' . $row['sub_count'] : '-' }}
                </td>
                <td class="text-center fw-bold fs-5">{{ $row['total_count'] }}</td>
                <td class="text-center">
                  <a href="{{ route('academic.report.teacher.detail', ['teacher' => $row['teacher']->id, 'month' => $month, 'year' => $year]) }}"
                    class="btn btn-sm btn-outline-secondary rounded-pill">Detail<i class="bi bi-box-arrow-up-right small ms-1"></i></a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
