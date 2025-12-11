@extends('layouts.app')
@section('title', 'Students')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  @push('styles')
    <style>
      /* Google Font Modern */
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

      body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f6f9;
      }

      /* Card Styling */
      .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      }

      /* Avatar Styling */
      .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        color: #fff;
        margin-right: 12px;
      }

      /* Soft Badges (Warna pastel modern) */
      .badge-soft-success {
        background-color: #d1fae5;
        color: #065f46;
      }

      .badge-soft-warning {
        background-color: #fef3c7;
        color: #92400e;
      }

      .badge-soft-danger {
        background-color: #fee2e2;
        color: #991b1b;
      }

      .badge-soft-primary {
        background-color: #dbeafe;
        color: #1e40af;
      }

      .badge-soft-secondary {
        background-color: #f3f4f6;
        color: #374151;
      }

      .table-hover tbody tr:hover {
        background-color: #f9fafb;
        transition: all 0.2s ease-in-out;
      }

      .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
      }
    </style>
  @endpush

  @section('content')
    <div class="container-fluid my-5">
      <div class="row justify-content-center">

        <div class="col-12 col-xl-11">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h4 class="mb-1 fw-bold text-dark">Data Santri</h4>
              <p class="text-muted mb-0 small">Manajemen data seluruh santri aktif dan alumni.</p>
            </div>
            <div>
              <button type="button" class="btn btn-success text-white rounded-3 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#importModal">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i>Import Excel
              </button>
              <a href="{{ route('students.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Santri
              </a>
            </div>
          </div>

          <div class="card card-modern mb-4">
            <div class="card-body p-3">
              <form action="{{ route('students.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3">
                      <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-2 rounded-end-3"
                      placeholder="Cari Nama atau NIS..." value="{{ request('search') }}">
                  </div>
                </div>
                <div class="col-md-3">
                  <select name="status" class="form-select rounded-3" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Skorsing</option>
                  </select>
                </div>
                @if (request('search') || request('status'))
                  <div class="col-auto">
                    <a href="{{ route('students.index') }}" class="btn btn-light text-muted border rounded-3">
                      <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                  </div>
                @endif
              </form>
            </div>
          </div>

          <div class="card card-modern overflow-hidden">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                      <th class="ps-4 py-3 border-0 rounded-start">Nama Santri</th>
                      <th class="py-3 border-0">NIS/NISN</th>
                      <th class="py-3 border-0">Asrama/Kelas</th>
                      <th class="py-3 border-0">Status</th>
                      <th class="py-3 border-0 text-end pe-4 rounded-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($students as $student)
                      <tr>
                        <td class="ps-4 py-3">
                          <div class="d-flex align-items-center">
                            @php
                              // Warna acak untuk avatar berdasarkan huruf pertama
                              $colors = ['#4F46E5', '#059669', '#D97706', '#DC2626', '#7C3AED'];
                              $bg_color = $colors[rand(0, 4)];
                              $initial = strtoupper(substr($student->name, 0, 1));
                            @endphp
                            <div class="avatar-circle shadow-sm" style="background-color: {{ $bg_color }};">
                              {{ $initial }}
                            </div>
                            <div>
                              <div class="fw-bold text-dark">{{ $student->name }}</div>
                              <div class="small text-muted">
                                <i
                                  class="bi bi-gender-{{ $student->gender == 'L' ? 'male text-primary' : 'female text-danger' }} me-1"></i>
                                {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                              </div>
                            </div>
                          </div>
                        </td>

                        <td>
                          <div class="fw-semibold text-dark">{{ $student->nis }}</div>
                          <span class="small text-muted">{{ $student->nisn ?? '-' }}</span>
                        </td>

                        <td>
                          <div class="d-flex flex-column">
                            <span class="text-dark fw-medium">
                              <i class="bi bi-building me-1 text-muted"></i> {{ $student->dormitory ?? 'Non-Mukim' }}
                            </span>
                            <span class="small text-muted ms-3">
                              Kamar: {{ $student->room ?? '-' }}
                            </span>
                          </div>
                        </td>

                        <td>
                          @php
                            $statusBadge = match ($student->status) {
                                'active' => 'badge-soft-success',
                                'graduated' => 'badge-soft-primary',
                                'moved' => 'badge-soft-secondary',
                                'suspended' => 'badge-soft-danger',
                                default => 'badge-soft-secondary',
                            };
                            $statusText = match ($student->status) {
                                'active' => 'Aktif',
                                'graduated' => 'Lulus',
                                'moved' => 'Pindah',
                                'suspended' => 'Skorsing',
                                default => $student->status,
                            };
                          @endphp
                          <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2">
                            {{ $statusText }}
                          </span>
                        </td>

                        <td class="text-end pe-4">
                          <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('students.show', $student->id) }}"
                              class="btn btn-icon btn-light text-primary border" title="Detail">
                              <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}"
                              class="btn btn-icon btn-light text-warning border" title="Edit">
                              <i class="bi bi-pencil-square"></i>
                            </a>

                            <button type="button" class="btn btn-icon btn-light text-danger border" data-bs-toggle="modal"
                              data-bs-target="#deleteModal{{ $student->id }}" title="Hapus">
                              <i class="bi bi-trash"></i>
                            </button>
                          </div>

                          <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content border-0 shadow">
                                <div class="modal-body p-4 text-center">
                                  <div class="mb-3 text-danger display-1">
                                    <i class="bi bi-exclamation-circle"></i>
                                  </div>
                                  <h5 class="fw-bold mb-2">Hapus Data Santri?</h5>
                                  <p class="text-muted">Data <strong>{{ $student->name }}</strong> akan dihapus permanen.
                                    Tindakan ini tidak dapat dibatalkan.</p>
                                  <div class="d-flex justify-content-center gap-2 mt-4">
                                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                          <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="Empty"
                            width="80" class="mb-3 opacity-50">
                          <p class="mb-0">Belum ada data santri ditemukan.</p>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              {{-- @if ($students->hasPages())
              <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-end">
                  {{ $students->links('pagination::bootstrap-5') }}
                </div>
              </div>
            @endif --}}
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Import Data Santri</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">

            <div class="alert alert-light border-start border-4 border-info text-muted small" role="alert">
              <i class="bi bi-info-circle me-1"></i>
              Pastikan format file sesuai. Kolom <strong>NIS</strong> wajib unik. Gunakan template di bawah ini.
            </div>

            <div class="d-grid mb-4">
              <a href="{{ route('students.template') }}" class="btn btn-outline-success border-dashed">
                <i class="bi bi-download me-2"></i> Download Template Excel/CSV
              </a>
            </div>

            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-4">
                <label for="fileImport" class="form-label fw-semibold">Upload File (.xlsx / .csv)</label>
                <input class="form-control form-control-lg" type="file" id="fileImport" name="file" required
                  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4">Import Data</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <style>
      /* Styling khusus untuk tombol download template */
      .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
      }

      .border-dashed:hover {
        background-color: #f0fdf4;
        /* Light green hover */
      }
    </style>
  @endsection
  @push('scripts')

  @endpush
