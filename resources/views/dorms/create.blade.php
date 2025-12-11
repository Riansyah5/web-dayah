@extends('layouts.app')
@section('title', 'Dorms')
@push('link')
@endpush
@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="card col-md-6">
        <div class="card-header">Tambah Gedung Asrama</div>
        <div class="card-body">
          <form action="{{ route('dorms.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label>Nama Gedung</label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: Gedung Umar bin Khattab"
                required>
            </div>
            <div class="mb-3">
              <label>Peruntukan</label>
              <select name="gender" class="form-select">
                <option value="L">Putra (Ikhwan)</option>
                <option value="P">Putri (Akhwat)</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('dorms.index') }}" class="btn btn-secondary">Kembali</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
