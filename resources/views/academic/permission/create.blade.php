@extends('layouts.app')
@section('title', 'Ajukan Izin')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Form Pengajuan Izin</h5>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('academic.permission.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Tanggal Izin</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Jenis Izin</label>
                <select name="type" class="form-select" required>
                  <option value="">-- Pilih Jenis --</option>
                  <option value="sick">Sakit</option>
                  <option value="permit">Izin Pribadi / Acara Keluarga</option>
                  <option value="duty">Tugas Dinas Sekolah</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Alasan Detail</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Jelaskan alasan ketidakhadiran..." required></textarea>
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Lampiran Bukti (Opsional)</label>
                <input type="file" name="attachment" class="form-control" accept="image/*,.pdf">
                <div class="form-text">Contoh: Surat Dokter, Undangan, Surat Tugas.</div>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary rounded-pill fw-bold">Ajukan Izin</button>
                <a href="{{ route('academic.permission.index') }}" class="btn btn-light rounded-pill">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Periksa Kembali Inputan',
            html: `
                <ul style="text-align: left;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
        });
    @endif
</script>
@endpush
