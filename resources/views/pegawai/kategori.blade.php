@extends('layouts.app')
@section('title', 'Kategori')
@section('content')
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-8">

        <h2 class="text-center fw-bold mb-4">Manajemen Kategori Pegawai</h2>

        <div class="card modern-card">
          @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          {{-- Jika Anda juga ingin menangani ERROR --}}
          @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          <div class="card-header d-flex justify-content-between align-items-center ">
            <h5 class="card-title mb-0">Daftar Kategori</h5>
            {{-- form tambah kategori --}}
            <form action="{{ route('kategori.store') }}" method="post" class="ms-auto">
              @csrf
              <div class="input-group">
                <input type="text" name="name" id="category" class="form-control"
                  placeholder="Nama kategori baru..." aria-label="Nama kategori baru" required>
                <button type="submit" class="btn btn-primary" id="btnTambahKategori"><i class="bi bi-plus-lg"></i>
                  Tambah</button>
              </div>
            </form>
            {{-- END/ form tambah kategori --}}
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th scope="col" style="width: 10%;">No.</th>
                    <th scope="col">Nama Kategori</th>
                    <th scope="col" class="text-end" style="width: 25%;">Aksi</th>
                  </tr>
                </thead>
                <tbody id="kategoriTableBody">
                  @forelse ($categories as $category)
                    <tr data-id="{{ $category->id }}">
                      <td>{{ $loop->iteration }}</td>
                      <td class="nama-kategori">{{ $category->name }}</td>
                      <td class="text-end">
                        <div class="btn-group" role="group">
                          <form action="{{ route('kategori.destroy', $category->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm btn-hapus"
                              onclick="return confirm('Apakah anda akan menghapus kategori ini?')">
                              <i class="bi bi-trash-fill"></i> Hapus
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center">Data masih kosong</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
