@extends('layouts.app')
@section('title', 'Form Cetak Syahadah Tahfizh')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm rounded mb-2">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Konfirmasi Cetak Syahadah Tahfizh</h5>
          </div>
          <div class="card-body p-4">
            <form method="POST" target="_blank">
              @csrf

              <h6 class="fw-bold text-primary mb-3">Identitas Penandatangan (Kepala Lajnah)</h6>

              <div class="mb-3">
                <label class="form-label small text-muted">Nama Lengkap</label>
                <input type="text" name="signer_name" class="form-control" value="Dhiawati, SH.I" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Jabatan</label>
                <input type="text" name="signer_role" class="form-control"
                  value="Kepala Lajnah Al-Qur’an MATAQU Utsman bin Affan" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Alamat Lembaga</label>
                <textarea name="signer_address" class="form-control" rows="2" required>Jln. Line Pipa, Desa Alue Lim, Kec. Blang Mangat, Kota Lhokseumawe</textarea>
              </div>

              <hr class="my-4">

              <h6 class="fw-bold text-primary mb-3">Detail Surat & Hafalan</h6>

              <div class="mb-3">
                <label class="form-label small text-muted">Nomor Surat</label>
                <input type="text" name="letter_number" class="form-control"
                  value=".../SKet-ADM.MATAQU/{{ date('m/Y') }}" placeholder="Nomor Surat Resmi">
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted d-block">Pilih Capaian Juz (Checklist)</label>
                <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded">
                  @for ($i = 1; $i <= 30; $i++)
                    @php
                      // Cek apakah juz ini ada di database setoran siswa
                      $checked = in_array($i, $juzHafalan) ? 'checked' : '';
                      // Highlight juz yang sudah ada
                      $class = in_array($i, $juzHafalan)
                          ? 'btn-outline-success active'
                          : 'btn-outline-secondary opacity-50';
                    @endphp

                    <input type="checkbox" class="btn-check" id="btn-check-{{ $i }}" name="juz_selected[]"
                      value="{{ $i }}" {{ $checked }}>
                    <label
                      class="btn {{ $class }} btn-sm rounded-circle d-flex align-items-center justify-content-center"
                      for="btn-check-{{ $i }}" style="width: 35px; height: 35px;">
                      {{ $i }}
                    </label>
                  @endfor
                </div>
                <small class="text-muted mt-2 d-block">* Angka hijau adalah juz yang terdeteksi di sistem. Anda bisa
                  menambah/mengurangi centang secara manual.</small>
              </div>

              <hr class="my-4">
              {{-- tanda tangan dan stempel --}}
              <h6 class="fw-bold text-primary mb-3">Opsi Tampilan Surat</h6>

              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="show_signature" id="show_signature" value="1"
                  checked>
                <label class="form-check-label" for="show_signature">
                  Tampilkan tanda tangan
                </label>
              </div>

              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="show_stamp" id="show_stamp" value="1" checked>
                <label class="form-check-label" for="show_stamp">
                  Tampilkan stempel
                </label>
              </div>


              <div class="d-grid mt-4">
                <button type="submit" formaction="{{ route('tahfizh.export.preview', $student->id) }}"
                  class="btn btn-success fw-bold">
                  <i class="bi bi-show me-2"></i> Preview PDF
                </button>
                <button type="submit" formaction="{{ route('tahfizh.export.print', $student->id) }}"
                  class="btn btn-primary fw-bold">
                  <i class="bi bi-printer me-2"></i> Generate & Cetak PDF
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
@endpush
