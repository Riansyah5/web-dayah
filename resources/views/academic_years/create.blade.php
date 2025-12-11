@extends('layouts.app')
@section('title', 'Tambah Tahun Ajaran')
@push('link')
@endpush
@push('styles')
  
@endpush
@section('content')
<div class="card col-md-6 mx-auto">
    <div class="card-header">Buat Tahun Ajaran Baru</div>
    <div class="card-body">
        <form action="{{ route('academic-years.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label>Tahun Ajaran</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: 2025/2026" required>
            </div>

            <div class="mb-3">
                <label>Semester</label>
                <select name="semester" class="form-select">
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="isActive">
                <label class="form-check-label fw-bold" for="isActive">
                    Set sebagai Tahun Ajaran AKTIF sekarang?
                </label>
                <div class="form-text text-danger">
                    Peringatan: Jika dicentang, tahun ajaran yang sedang aktif saat ini akan otomatis non-aktif.
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('academic-years.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
@push('scripts')
@endpush
