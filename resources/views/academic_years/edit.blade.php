@extends('layouts.app')
@section('title', 'Edit Tahun Ajaran')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="card col-md-6 mx-auto">
    <div class="card-header">Edit Tahun Ajaran</div>
    <div class="card-body">

      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <form action="{{ route('academic-years.update', $academicYear->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label>Tahun Ajaran</label>
          <input type="text" name="name" class="form-control" value="{{ $academicYear->name }}" required>
        </div>

        <div class="mb-3">
          <label>Semester</label>
          <select name="semester" class="form-select">
            <option value="Ganjil" {{ $academicYear->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ $academicYear->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
          </select>
        </div>

        <div class="form-check mb-3 bg-light p-2 border rounded">
          <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
            {{ $academicYear->is_active ? 'checked' : '' }}>
          <label class="form-check-label fw-bold" for="isActive">
            Status Aktif
          </label>
          <div class="form-text">
            Centang untuk mengaktifkan tahun ini (tahun lain akan otomatis mati).
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('academic-years.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
