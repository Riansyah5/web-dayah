@extends('layouts.app')
@section('title', 'Dorms')
@push('link')
@endpush
@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h4>Daftar Gedung Asrama</h4>
            <a href="{{ route('dorms.create') }}" class="btn btn-primary btn-sm">Tambah Gedung</a>
          </div>
          <div class="card-body">
            @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Nama Gedung</th>
                  <th>Khusus</th>
                  <th>Jumlah Kamar</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($dorms as $dorm)
                  <tr>
                    <td>{{ $dorm->name }}</td>
                    <td>{{ $dorm->gender == 'L' ? 'Putra' : 'Putri' }}</td>
                    <td>{{ $dorm->rooms->count() }} Kamar</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
