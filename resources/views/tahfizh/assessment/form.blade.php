@extends('layouts.app')
@section('title', 'Input Rapor Tahfizh')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-10">

        <div class="d-flex align-items-center mb-4">
          <div class="d-flex align-items-center me-auto">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded me-3"><i class="bi bi-arrow-left"></i></a>
            <div>
              <h4 class="fw-bold mb-0">Input Rapor Tahfizh</h4>
              <p class="text-muted small mb-0">
                Santri: <strong>{{ $student->name }}</strong>
              </p>
              <p class="text-muted small mb-0">Semester: {{ $activeYear->name }} ({{ $activeYear->semester }})</p>
            </div>
          </div>
          <div>
            {{-- Jika data sudah pernah disimpan (ID ada), tampilkan tombol cetak --}}
            @if ($report->id)
              <a href="{{ route('tahfizh.assessment.preview', $student->id) }}" class="btn btn-info rounded"
                target="_blank">
                <i class="bi bi-eye"></i>
              </a>
              <a href="{{ route('tahfizh.assessment.print', $student->id) }}" class="btn btn-danger rounded"
                target="_blank">
                <i class="bi bi-printer"></i> Cetak Rapor
              </a>
            @endif
          </div>
        </div>

        <form action="{{ route('tahfizh.assessment.update', $student->id) }}" method="POST">
          @csrf

          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-primary text-white py-3 rounded-top-4">
              <h6 class="fw-bold mb-0">A. Hafalan (Tahfizh)</h6>
            </div>
            <div class="card-body p-4">

              <label class="form-label fw-bold small text-muted">Nilai Per Juz</label>
              <table class="table table-bordered mb-3" id="juzTable">
                <thead class="bg-light">
                  <tr>
                    <th width="40%">Juz</th>
                    <th width="40%">Nilai (0-100)</th>
                    <th width="20%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody id="juzTableBody">
                  {{-- Loop data yang sudah ada (jika edit) --}}
                  @if ($report->juz_scores)
                    @foreach ($report->juz_scores as $item)
                      <tr>
                        <td>
                          <select name="juz_data[]" class="form-select">
                            @for ($i = 1; $i <= 30; $i++)
                              <option value="{{ $i }}" {{ $item['juz'] == $i ? 'selected' : '' }}>Juz
                                {{ $i }}</option>
                            @endfor
                          </select>
                        </td>
                        <td>
                          <input type="number" name="score_data[]" class="form-control" value="{{ $item['score'] }}"
                            min="0" max="100" placeholder="0-100">
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-danger btn-sm remove-row"><i
                              class="bi bi-trash"></i></button>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                  {{-- Jika kosong, baris ini akan diisi lewat JS saat load atau klik tambah --}}
                </tbody>
              </table>

              <button type="button" class="btn btn-sm btn-outline-primary rounded mb-4" id="addJuzBtn">
                <i class="bi bi-plus-lg me-1"></i> Tambah Juz
              </button>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label small text-muted">Total Hafalan (Teks)</label>
                  <input type="text" name="total_hafalan" class="form-control" value="{{ $report->total_hafalan }}"
                    placeholder="Contoh: 5 Juz">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label small text-muted">Nilai Ujian Tahriri (Tulis)</label>
                  <input type="number" name="score_tahriri" class="form-control" value="{{ $report->score_tahriri }}"
                    min="0" max="100">
                </div>
              </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-success text-white py-3 rounded-top-4">
              <h6 class="fw-bold mb-0">B. Kualitas Bacaan (Tahsin)</h6>
            </div>
            <div class="card-body p-4">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label small text-muted">Makharijul Huruf</label>
                  <input type="number" name="score_makhraj" class="form-control" value="{{ $report->score_makhraj }}"
                    min="0" max="100">
                </div>
                <div class="col-md-4">
                  <label class="form-label small text-muted">Ghunnah</label>
                  <input type="number" name="score_ghunnah" class="form-control" value="{{ $report->score_ghunnah }}"
                    min="0" max="100">
                </div>
                <div class="col-md-4">
                  <label class="form-label small text-muted">Mad</label>
                  <input type="number" name="score_mad" class="form-control" value="{{ $report->score_mad }}"
                    min="0" max="100">
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Kefasihan</label>
                  <input type="number" name="score_fasohah" class="form-control" value="{{ $report->score_fasohah }}"
                    min="0" max="100">
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Kelancaran</label>
                  <input type="number" name="score_kelancaran" class="form-control"
                    value="{{ $report->score_kelancaran }}" min="0" max="100">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8 mb-3">
              <div class="card border-0 shadow-sm rounded-4 mb-4 h-100">
                <div class="card-header bg-warning text-dark py-3 rounded-top-4">
                  <h6 class="fw-bold mb-0">C & D. Catatan</h6>
                </div>
                <div class="card-body p-4">
                  <div class="mb-3">
                    <label class="form-label small text-muted">Catatan Untuk Anak</label>
                    <textarea name="note_student" class="form-control" rows="3">{{ $report->note_student }}</textarea>
                  </div>
                  <div class="mb-0">
                    <label class="form-label small text-muted">Catatan Untuk Orang Tua</label>
                    <textarea name="note_parent" class="form-control" rows="3">{{ $report->note_parent }}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="card border-0 shadow-sm rounded-4 mb-4 h-100">
                <div class="card-header bg-secondary text-white py-3 rounded-top-4">
                  <h6 class="fw-bold mb-0 text-white">E. Kehadiran</h6>
                </div>
                <div class="card-body p-4">
                  <div class="mb-3 row align-items-center">
                    <label class="col-4 col-form-label small text-muted">Sakit</label>
                    <div class="col-8">
                      <input type="number" name="sick" class="form-control" value="{{ $report->sick }}"
                        min="0">
                    </div>
                  </div>
                  <div class="mb-3 row align-items-center">
                    <label class="col-4 col-form-label small text-muted">Izin</label>
                    <div class="col-8">
                      <input type="number" name="permission" class="form-control" value="{{ $report->permission }}"
                        min="0">
                    </div>
                  </div>
                  <div class="mb-0 row align-items-center">
                    <label class="col-4 col-form-label small text-muted">Alpa</label>
                    <div class="col-8">
                      <input type="number" name="alpha" class="form-control" value="{{ $report->alpha }}"
                        min="0">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3 text-end">
              <button type="submit" class="btn btn-success fw-bold rounded">
                <i class="bi bi-save me-2"></i> Simpan Nilai Rapor
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  {{-- SCRIPT SEDERHANA UNTUK ROW JUZ --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tableBody = document.getElementById('juzTableBody');
      const addBtn = document.getElementById('addJuzBtn');

      // Template Row (Hidden di Javascript)
      function createRow(juzVal = '', scoreVal = '') {
        const tr = document.createElement('tr');

        // Build Options 1-30
        let options = '';
        for (let i = 1; i <= 30; i++) {
          let selected = (i == juzVal) ? 'selected' : '';
          options += `<option value="${i}" ${selected}>Juz ${i}</option>`;
        }

        tr.innerHTML = `
                <td>
                    <select name="juz_data[]" class="form-select">
                        <option value="">-- Pilih --</option>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="score_data[]" class="form-control" value="${scoreVal}" min="0" max="100" placeholder="0-100">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                </td>
            `;
        return tr;
      }

      // Add Event
      addBtn.addEventListener('click', function() {
        tableBody.appendChild(createRow());
      });

      // Remove Event (Event Delegation)
      tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
          e.target.closest('tr').remove();
        }
      });

      // Init: Jika tabel kosong (data baru), tambah 1 baris kosong
      if (tableBody.children.length === 0) {
        tableBody.appendChild(createRow());
      }
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
