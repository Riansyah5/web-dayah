@extends('layouts.app')
@section('title', 'Input Setoran Hafalan')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <div
              class="avatar-lg bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
              style="width: 50px; height: 50px; font-size: 1.5rem;">
              {{ substr($student->name, 0, 1) }}
            </div>
            <div>
              <h5 class="fw-bold mb-0">Input Setoran Hafalan</h5>
              <div class="text-muted">{{ $student->name }} ({{ $student->nis }})</div>
            </div>
          </div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm ms-auto">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>


        @if ($lastSetoran)
          <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center mt-2">
            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
            <div>
              <small class="text-uppercase fw-bold opacity-75" style="font-size: 0.7rem;">Setoran Terakhir
                ({{ $lastSetoran->date->format('d M Y') }})</small><br>
              <span class="fw-bold">{{ $lastSetoran->type == 'ziyadah' ? 'Ziyadah' : 'Muraja\'ah' }}:</span>
              {{ $lastSetoran->location }}
            </div>
          </div>
        @else
          <div class="alert alert-secondary border-0 shadow-sm rounded-3">
            <i class="bi bi-stars me-2"></i> Belum ada riwayat setoran. Ini adalah setoran pertama.
          </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <form action="{{ route('tahfizh.setoran.store', $student->id) }}" method="POST">
              @csrf

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label small text-muted">Tanggal</label>
                  <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Jenis Setoran</label>
                  <select name="type" class="form-select bg-light fw-bold">
                    <option value="ziyadah">Ziyadah (Hafalan Baru)</option>
                    <option value="murajaah">Muraja'ah (Mengulang)</option>
                  </select>
                </div>
              </div>

              <hr class="my-4 border-light">

              <h6 class="fw-bold text-success mb-3"><i class="bi bi-play-circle me-2"></i>Mulai Dari</h6>
              <div class="row mb-3">
                <div class="col-md-3">
                  <label class="form-label small text-muted">Juz</label>
                  <input type="number" name="juz" class="form-control" min="1" max="30"
                    placeholder="1-30" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Nama Surat</label>
                  <select name="surah_start_id" class="form-select surah-select" id="surahStart" required>
                    <option value="">Pilih Surat...</option>
                    @foreach ($surahs as $surah)
                      <option value="{{ $surah->id }}" data-verses="{{ $surah->total_verses }}">
                        {{ $surah->id }}. {{ $surah->name_latin }} ({{ $surah->total_verses }} ayat)
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small text-muted">Ayat</label>
                  <input type="number" name="ayat_start" class="form-control" placeholder="Ayat ke..." required>
                </div>
              </div>

              <h6 class="fw-bold text-danger mb-3"><i class="bi bi-stop-circle me-2"></i>Sampai Dengan</h6>
              <div class="row mb-3">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Nama Surat</label>
                  <select name="surah_end_id" class="form-select surah-select" id="surahEnd" required>
                    <option value="">Pilih Surat...</option>
                    @foreach ($surahs as $surah)
                      <option value="{{ $surah->id }}">
                        {{ $surah->id }}. {{ $surah->name_latin }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small text-muted">Ayat</label>
                  <input type="number" name="ayat_end" class="form-control" placeholder="Ayat ke..." required>
                </div>
              </div>

              <div class="form-check mb-4 ms-md-auto text-md-start">
                <input class="form-check-input" type="checkbox" id="sameSurahCheck" checked>
                <label class="form-check-label small text-muted" for="sameSurahCheck">
                  Surat Akhir sama dengan Surat Awal
                </label>
              </div>

              <hr class="my-4 border-light">

              <div class="mb-3">
                <label class="form-label small text-muted">Kualitas Bacaan (Predikat)</label>
                <div class="btn-group w-100" role="group">
                  <input type="radio" class="btn-check" name="quality" id="q1" value="lancar" checked>
                  <label class="btn btn-outline-success" for="q1">Lancar (Mumtaz)</label>

                  <input type="radio" class="btn-check" name="quality" id="q2" value="kurang">
                  <label class="btn btn-outline-warning" for="q2">Kurang Lancar</label>

                  <input type="radio" class="btn-check" name="quality" id="q3" value="ulang">
                  <label class="btn btn-outline-danger" for="q3">Ulang (Remidi)</label>
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label small text-muted">Catatan Musyrif (Opsional)</label>
                <textarea name="note" class="form-control" rows="2" placeholder="Contoh: Perbaiki makhraj huruf 'Ain'"></textarea>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary py-2 fw-bold">Simpan Hafalan</button>
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
    // Notifikasi Sukses
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @elseif ($errors->has('ayat_end'))
      Swal.fire({
        icon: 'error',
        title: 'Kesalahan Input',
        html: '{{ $errors->first('ayat_end') }}',
        showConfirmButton: true
      });
    @elseif ($errors->has('type'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: '{{ $errors->first('type') }}',
        showConfirmButton: true
      });
    @endif
  </script>

  <script>
    // Script Helper Sederhana
    document.addEventListener('DOMContentLoaded', function() {
      const startSelect = document.getElementById('surahStart');
      const endSelect = document.getElementById('surahEnd');
      const checkSame = document.getElementById('sameSurahCheck');

      // Saat Surat Awal dipilih, otomatis set Surat Akhir jika dicentang
      startSelect.addEventListener('change', function() {
        if (checkSame.checked) {
          endSelect.value = this.value;
        }
      });

      // Saat checkbox dicentang/uncentang
      checkSame.addEventListener('change', function() {
        if (this.checked) {
          endSelect.value = startSelect.value;
          // endSelect.setAttribute('disabled', 'disabled'); // Opsional: disable input
        } else {
          // endSelect.removeAttribute('disabled');
        }
      });
    });
  </script>
@endpush
