@extends('layouts.app')
@section('title', 'Isi Jurnal Mengajar')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
          <h5 class="fw-bold mb-0">Isi Jurnal Mengajar</h5>
          <small>{{ $schedule->subject->name }} - Kelas {{ $schedule->classroom->name }}</small>
        </div>
        <div class="card-body p-4">

          <div id="locationStatus" class="alert alert-warning d-flex align-items-center mb-3">
            <div class="spinner-border spinner-border-sm me-3" role="status"></div>
            <div>Mendeteksi lokasi Anda...</div>
          </div>

          <form action="{{ route('academic.journal.store', $schedule->id) }}" method="POST" enctype="multipart/form-data" id="journalForm">
            @csrf

            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="lng">

            <div class="mb-3">
              <label class="form-label fw-bold">Materi / Topik Bahasan</label>
              <input type="text" name="topic" class="form-control" placeholder="Cth: Bab 3 - Persamaan Kuadrat" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Catatan Kejadian (Opsional)</label>
              <textarea name="notes" class="form-control" rows="2" placeholder="Cth: LCD Proyektor mati, pindah ke perpus."></textarea>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Foto Bukti Kegiatan</label>
              <input type="file" name="photo_proof" id="photoInput" class="form-control" accept="image/*" capture="environment" required>
              <div class="form-text text-muted">Ambil foto selfie atau suasana kelas.</div>

              <div id="compressionLoading" class="d-none mt-2 text-primary small">
                <div class="spinner-border spinner-border-sm me-1" role="status"></div>
                Sedang mengkompres foto...
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold" id="submitBtn" disabled>
              <i class="bi bi-send me-2"></i> Simpan & Absen Siswa
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const statusDiv = document.getElementById("locationStatus");
    const submitBtn = document.getElementById("submitBtn");
    const latInput = document.getElementById("lat");
    const lngInput = document.getElementById("lng");

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(position) {
          // Sukses dapat lokasi
          latInput.value = position.coords.latitude;
          lngInput.value = position.coords.longitude;

          statusDiv.className = "alert alert-success d-flex align-items-center mb-3";
          statusDiv.innerHTML = '<i class="bi bi-geo-alt-fill me-2"></i> Lokasi terdeteksi. Silakan lanjut.';
          submitBtn.removeAttribute("disabled");
        }
        , function(error) {
          // Gagal dapat lokasi
          statusDiv.className = "alert alert-danger mb-3";
          statusDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal mendeteksi lokasi. Pastikan GPS aktif dan Browser diizinkan.';
          console.error("Error Code = " + error.code + " - " + error.message);
        }, {
          enableHighAccuracy: true, // Paksa GPS akurasi tinggi
          timeout: 10000
          , maximumAge: 0
        }
      );
    } else {
      statusDiv.innerHTML = "Browser Anda tidak mendukung Geolocation.";
    }
  });
</script>

{{-- Kompres Foto --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>
<script>
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) {
            return;
        }

        // Tampilkan loading & Matikan tombol submit biar user ga buru-buru klik
        const loading = document.getElementById('compressionLoading');
        const btnSubmit = document.getElementById('btnSubmit');
        
        loading.classList.remove('d-none');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses Foto...';

        // PROSES KOMPRESI
        new Compressor(file, {
            quality: 0.6,      // Kualitas 60% (Sangat cukup untuk bukti, size turun drastis)
            maxWidth: 1000,    // Lebar max 1000px (HD 720p). Tidak perlu 4K.
            mimeType: 'image/jpeg', // Paksa jadi JPEG (karena PNG biasanya gede)
            
            success(result) {
                // Saat sukses kompres:
                
                // 1. Kita harus menukar file asli di input dengan file hasil kompres
                // Karena input file read-only, kita pakai DataTransfer API
                const dataTransfer = new DataTransfer();
                
                // File hasil kompres (Blob) perlu dijadikan File object lagi
                const fileConverted = new File([result], file.name, {
                    type: result.type,
                    lastModified: Date.now(),
                });

                dataTransfer.items.add(fileConverted);
                document.getElementById('photoInput').files = dataTransfer.files;

                // 2. Kembalikan UI ke semula
                loading.classList.add('d-none');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-send me-2"></i> Simpan Jurnal';

                console.log('Kompresi Berhasil!');
                console.log('Size Asli: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB');
                console.log('Size Baru: ' + (result.size / 1024 / 1024).toFixed(2) + ' MB');
            },
            
            error(err) {
                console.error(err.message);
                // Jika error, biarkan upload file aslinya saja (fallback)
                loading.classList.add('d-none');
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'Simpan Jurnal';
                alert('Gagal mengkompres foto, file asli akan digunakan.');
            },
        });
    });
</script>
@endsection
@push('scripts')
@endpush
