@extends('layouts.app')
@section('title', 'Kelulusan Massal - Kelas ' . $classroom->name)
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex align-items-center mb-4">
      <a href="{{ route('graduation.index') }}" class="btn btn-light rounded-circle me-3"><i
          class="bi bi-arrow-left"></i></a>
      <div>
        <h4 class="fw-bold mb-0">Kelulusan Kelas {{ $classroom->name }}</h4>
        <small class="text-muted">Centang siswa yang dinyatakan LULUS.</small>
      </div>
    </div>

    <form action="{{ route('graduation.store', $classroom->id) }}" method="POST" id="gradForm">
      @csrf

      <div class="row">
        <div class="col-md-8">
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkAll">
                <label class="form-check-label fw-bold" for="checkAll">Pilih Semua Siswa</label>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <tbody>
                    @foreach ($students as $student)
                      <tr>
                        <td class="ps-4" style="width: 50px;">
                          <div class="form-check">
                            <input class="form-check-input student-check" type="checkbox" name="student_ids[]"
                              value="{{ $student->id }}">
                          </div>
                        </td>
                        <td>
                          <span class="fw-bold d-block">{{ $student->name }}</span>
                          <small class="text-muted">{{ $student->nis }}</small>
                        </td>
                        <td class="text-end pe-4">
                          <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
            <div class="card-body">
              <h6 class="fw-bold mb-3">Detail Kelulusan</h6>

              <div class="mb-3">
                <label class="form-label small text-muted">Tanggal Kelulusan</label>
                <input type="date" name="exit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Nomor SK Kelulusan (Opsional)</label>
                <input type="text" name="sk_number" class="form-control" placeholder="No. SK Kepala Sekolah...">
                <div class="form-text small">Nomor ini akan diterapkan ke semua siswa terpilih.</div>
              </div>

              <div class="mb-4">
                <label class="form-label small text-muted">Catatan (Opsional)</label>
                <textarea name="note" class="form-control" rows="2" placeholder="Cth: Lulus dengan predikat baik"></textarea>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-success fw-bold py-2">
                  <i class="bi bi-mortarboard-fill me-2"></i> Luluskan Siswa
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.getElementById('checkAll').addEventListener('change', function() {
      var checkboxes = document.querySelectorAll('.student-check');
      for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    });

    document.getElementById('gradForm').addEventListener('submit', function(e) {
      e.preventDefault();

      var selectedCount = document.querySelectorAll('.student-check:checked').length;

      if (selectedCount === 0) {
        Swal.fire('Peringatan', 'Silakan pilih minimal satu siswa.', 'warning');
        return;
      }

      Swal.fire({
        title: 'Konfirmasi Kelulusan',
        text: "Anda akan meluluskan " + selectedCount +
          " siswa terpilih. Tindakan ini tidak dapat dibatalkan dengan mudah.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Luluskan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  </script>
@endpush
