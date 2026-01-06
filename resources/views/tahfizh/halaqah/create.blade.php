@extends('layouts.app')
@section('title', 'Buat Halaqah Tahfizh Baru')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Buat Halaqah Baru</h5>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('tahfizh.halaqah.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="form-label">Nama Halaqah / Kelompok</label>
                <input type="text" name="name" class="form-control" placeholder="Cth: Halaqah Utsman bin Affan"
                  required>
              </div>

              <div class="mb-3">
                <label class="form-label">Musyrif (Guru Pembimbing)</label>
                <select name="teacher_id" class="form-select" required>
                  <option value="">-- Pilih Musyrif --</option>
                  @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Kategori Gender</label>
                <select name="gender" class="form-select" required>
                  <option value="L">Putra (Laki-laki)</option>
                  <option value="P">Putri (Perempuan)</option>
                </select>
                <div class="form-text">Hanya bisa diisi oleh santri sesuai gender ini.</div>
              </div>

              <div class="mb-4">
                <label class="form-label">Keterangan (Opsional)</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
              </div>

              <button type="submit" class="btn btn-primary w-100">Simpan Halaqah</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
