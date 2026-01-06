@extends('layouts.app')
@section('title', 'Manajemen Halaqah Tahfizh')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-3">
      <div>
        <h4 class="fw-bold mb-1">Manajemen Halaqah</h4>
        <p class="text-muted small">Tahun Ajaran Aktif: {{ $activeYear->name }} ({{ $activeYear->semester }})</p>
      </div>
      <div class="d-flex gap-2">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari halaqah atau musyrif..." style="width: 250px;">
        <a href="{{ route('tahfizh.halaqah.create') }}" class="btn btn-primary shadow-sm text-nowrap">
          <i class="bi bi-plus-lg me-2"></i> Halaqah Baru
        </a>
      </div>
    </div>

    <div class="row g-4">
      @forelse($halaqahs as $halaqah)
        <div class="col-md-4 searchable-card">
          <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div
                  class="avatar-sm rounded-circle d-flex align-items-center justify-content-center text-white fw-bold {{ $halaqah->gender == 'L' ? 'bg-primary' : 'bg-success' }}"
                  style="width: 45px; height: 45px;">
                  {{ $halaqah->gender }}
                </div>
                <div class="dropdown">
                  <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown"><i
                      class="bi bi-three-dots-vertical"></i></button>
                  <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                    <li><a class="dropdown-item" href="{{ route('tahfizh.halaqah.edit', $halaqah->id) }}">Edit Info</a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      <form action="{{ route('tahfizh.halaqah.destroy', $halaqah->id) }}" method="POST"
                        onsubmit="return confirm('Hapus kelompok ini?')">
                        @csrf @method('DELETE')
                        <button class="dropdown-item text-danger">Hapus</button>
                      </form>
                    </li>
                  </ul>
                </div>
              </div>

              <h5 class="fw-bold text-dark mb-1">{{ $halaqah->name }}</h5>
              <p class="text-muted small mb-3">Musyrif: {{ $halaqah->teacher->name ?? '-' }}</p>

              <div class="d-flex justify-content-between align-items-center bg-light rounded-3 p-2 mb-3">
                <span class="small text-muted">Jumlah Anggota</span>
                <span class="fw-bold">{{ $halaqah->students_count }} Santri</span>
              </div>

              <a href="{{ route('tahfizh.halaqah.show', $halaqah->id) }}"
                class="btn btn-outline-primary w-100 rounded-pill">
                Kelola Anggota
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="text-muted">Belum ada kelompok halaqah yang dibuat.</div>
        </div>
      @endforelse
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      const cards = document.querySelectorAll('.searchable-card');

      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
  </script>
@endpush
