@extends('layouts.app')
@section('title', 'Rooms Create')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="card col-md-6 mx-auto">
        <div class="card-header">Buat Kamar Baru</div>
        <div class="card-body">
          <form action="{{ route('rooms.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label>Lokasi Gedung</label>
              <select name="dorm_id" class="form-select" required>
                <option value="">-- Pilih Gedung --</option>
                @foreach ($dorms as $dorm)
                  <option value="{{ $dorm->id }}">
                    {{ $dorm->name }} ({{ $dorm->gender == 'L' ? 'Putra' : 'Putri' }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label>Nama/Nomor Kamar</label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: Kamar 101" required>
            </div>

            <div class="mb-3">
              <label>Kapasitas (Orang)</label>
              <input type="number" name="capacity" class="form-control" value="10" required>
            </div>
            <div class="mb-3">
              <label>Wali Kamar / Musyrif</label>
              <select name="warden_id" class="form-select">
                <option value="">-- Pilih Wali Kamar (Opsional) --</option>
                @foreach ($wardens as $warden)
                  <option value="{{ $warden->id }}">
                    {{ $warden->nama }}
                  </option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan Kamar</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
