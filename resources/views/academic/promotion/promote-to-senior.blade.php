@extends('layouts.app')
@section('title', 'Lanjut Jenjang Siswa')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
          <i class="bi bi-info-circle-fill fs-4 me-3"></i>
          <div>
            <strong>Fitur Lanjut Jenjang (Internal)</strong><br>
            Gunakan fitur ini untuk mendaftarkan kembali alumni (SMP) ke jenjang berikutnya (SMA) tanpa menghapus data
            lama. Data Tahfizh & Keuangan akan tetap tersambung.
          </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Form Penerimaan Siswa Internal</h5>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('students.promotion.store') }}" method="POST">
              @csrf

              <div class="mb-4">
                <label class="form-label fw-bold">Cari Nama Alumni (Status: Lulus)</label>
                <select class="form-select" id="alumniSelect" name="student_id" required style="width: 100%;">
                  <option value="">Ketik Nama atau NIS Lama...</option>
                </select>
                <div class="form-text text-muted">Hanya menampilkan siswa yang statusnya 'Graduated'.</div>
              </div>

              <div id="studentInfo" class="p-3 bg-light rounded mb-4 d-none">
                <div class="d-flex align-items-center">
                  <div
                    class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold">
                    <i class="bi bi-person"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-0" id="infoName">-</h6>
                    <small class="text-muted">NIS Lama: <span id="infoNis">-</span></small>
                  </div>
                </div>
              </div>

              <hr class="my-4">

              <h6 class="fw-bold text-primary mb-3">Data Jenjang Baru</h6>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">NIS Baru (SMA)</label>
                  <input type="text" name="new_nis" class="form-control" placeholder="Input NIS Baru" required>
                  <div class="form-text">NIS lama akan ditimpa dengan NIS ini.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tanggal Masuk</label>
                  <input type="date" name="join_date" class="form-control" value="{{ date('Y-07-15') }}" required>
                </div>
                <div class="col-12">
                  <label class="form-label">Kelas Tujuan</label>
                  <select name="classroom_id" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($targetClasses as $class)
                      <option value="{{ $class->id }}">
                        {{ $class->name }} (Level {{ $class->level->name ?? '-' }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="d-grid mt-5">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                  <i class="bi bi-check-circle-fill me-2"></i> Proses Lanjut Siswa
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endsection
@push('scripts')
  <script>
    $(document).ready(function() {
      $('#alumniSelect').select2({
        theme: 'bootstrap-5',
        ajax: {
          url: '{{ route('students.promotion.search') }}',
          dataType: 'json',
          delay: 250,
          processResults: function(data) {
            return {
              results: $.map(data, function(item) {
                return {
                  text: item.name + ' (' + item.nis + ')',
                  id: item.id,
                  // Kirim data tambahan untuk ditampilkan di info
                  student: item
                }
              })
            };
          },
          cache: true
        },
        placeholder: 'Cari Nama Alumni...',
        minimumInputLength: 3,
      });

      // Event saat siswa dipilih
      $('#alumniSelect').on('select2:select', function(e) {
        var data = e.params.data.student;

        // Tampilkan Info Card
        $('#studentInfo').removeClass('d-none');
        $('#infoName').text(data.name);
        $('#infoNis').text(data.nis);
      });
    });
  </script>
@endpush
