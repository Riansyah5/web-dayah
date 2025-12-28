@extends('layouts.app')
@section('title', 'Rooms')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h2><i class="bi bi-house-gear-fill"></i> Data Kamar Santri</h2>
          <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm rounded p-2"><i class="bi bi-plus-circle"></i>
            Tambah Kamar</a>
        </div>
        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <table class="table">
            <thead>
              <tr>
                <th>Nama Kamar</th>
                <th>Lokasi Gedung</th>
                <th>Kapasitas</th>
                <th>Musyrif/ah</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php
                $groupedRooms = $rooms->groupBy(function($item) {
                    return $item->dorm->name ?? 'Tanpa Gedung';
                });
              @endphp
              @forelse($groupedRooms as $dormName => $group)
                <tr>
                  <td colspan="5" class="fw-bold"><span class="btn btn-info btn-sm rounded"><i class="bi bi-houses-fill me-2"></i>{{ $dormName }}</span></td>
                </tr>
                @foreach($group as $room)
                <tr>
                  <td>{{ $loop->iteration }}. {{ $room->name }}</td>
                  <td>
                    <span class="badge bg-secondary">{{ $room->dorm->name ?? 'Tanpa Gedung' }}</span>
                  </td>
                  <td>{{ $room->capacity }} Orang</td>
                  <td>{{ $room->warden->nama ?? 'Belum Ditentukan' }}</td>
                  <td><a href="#" class="btn btn-sm btn-success rounded"><i class="bi bi-pencil-square"></i>
                      Edit</a></td>
                </tr>
                @endforeach
              @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada data kamar.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          {{ $rooms->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  {{-- sweetAlert2 --}}
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
