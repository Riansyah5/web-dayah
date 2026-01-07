@extends('layouts.app')
@section('title', 'Manajemen Halaqah Tahfizh')
@push('link')
@endpush
@push('styles')
<style>
.bg-halaqah-l {
    background: linear-gradient(135deg, #00B7B5 0%, #00B7B5 100%);
  }
  .bg-halaqah-p {
    background: linear-gradient(135deg, #FF6F91 0%, #FF6F91 100%);
  }
</style>
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
                  class="avatar-sm rounded-circle d-flex align-items-center justify-content-center text-white fw-bold {{ $halaqah->gender == 'L' ? 'bg-halaqah-l' : 'bg-halaqah-p' }}"
                  style="width: 45px; height: 45px;">
                  {{ $halaqah->gender }}
                </div>
                <div class="dropdown">
                  <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown"><i
                      class="bi bi-three-dots-vertical"></i></button>
                  <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                    <li>
                      <a class="dropdown-item btn-edit" href="#" 
                        data-name="{{ $halaqah->name }}"
                        data-teacher="{{ $halaqah->teacher_id }}"
                        data-gender="{{ $halaqah->gender }}"
                        data-description="{{ $halaqah->description }}"
                        data-url="{{ route('tahfizh.halaqah.update', $halaqah->id) }}">Edit Info</a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      <form action="{{ route('tahfizh.halaqah.destroy', $halaqah->id) }}" method="POST"
                        class="delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">Hapus</button>
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

  <!-- Modal Edit Halaqah -->
  <div class="modal fade" id="editHalaqahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-4 border-0 shadow">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Edit Halaqah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
              <label class="form-label small text-muted">Nama Halaqah</label>
              <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Musyrif</label>
              <select name="teacher_id" id="editTeacher" class="form-select" required>
                @foreach($teachers as $teacher)
                  <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Gender</label>
              <select name="gender" id="editGender" class="form-select" required>
                <option value="L">Putra (Laki-laki)</option>
                <option value="P">Putri (Perempuan)</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted">Deskripsi</label>
              <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      const cards = document.querySelectorAll('.searchable-card');

      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });

    // Handle Edit Modal
    document.querySelectorAll('.btn-edit').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Populate Form
        document.getElementById('editName').value = this.dataset.name;
        document.getElementById('editTeacher').value = this.dataset.teacher;
        document.getElementById('editGender').value = this.dataset.gender;
        document.getElementById('editDescription').value = this.dataset.description || '';
        document.getElementById('editForm').action = this.dataset.url;

        // Show Modal
        new bootstrap.Modal(document.getElementById('editHalaqahModal')).show();
      });
    });

    // SweetAlert untuk Konfirmasi Hapus
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Hapus Halaqah?',
          text: "Data halaqah ini akan dihapus permanen.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            this.submit();
          }
        });
      });
    });

    // SweetAlert untuk Notifikasi Session
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
      });
    @endif

    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
      });
    @endif
  </script>
@endpush
