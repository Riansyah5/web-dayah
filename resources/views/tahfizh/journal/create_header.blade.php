@extends('layouts.app')
@section('title', 'Absen Guru')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <a href="{{ route('tahfizh.journal.dashboard') }}" class="btn btn-light rounded-pill mb-3 border shadow-sm">
    <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
  </a>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-primary text-white py-3 px-4 rounded-top-4">
      <h5 class="fw-bold mb-0">Buka Halaqah: {{ $schedule->session_name }}</h5>
      <small class="opacity-75">Langkah 1: Absensi Musyrif & Lokasi</small>
    </div>

    <div class="card-body p-4">
      @if ($errors->any())
      <div class="alert alert-danger rounded-4 mb-4">
        <ul class="mb-0 small">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('tahfizh.journal.store_header', $schedule->id) }}" method="POST" enctype="multipart/form-data" id="headerForm">
        @csrf

        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lng">

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="p-3 bg-light rounded border h-100">
              <label class="form-label fw-bold text-dark">
                <i class="bi bi-camera-fill me-1 text-primary"></i> Foto Dokumentasi
              </label>

              <input type="file" name="photo_proof" id="photoInput" class="form-control" accept="image/*" capture="environment" required>

              <div class="form-text small text-muted">
                Ambil foto selfie atau suasana halaqah.
              </div>

              <div id="compressionLoading" class="d-none mt-2 text-primary small fw-bold">
                <div class="spinner-border spinner-border-sm me-1"></div>
                Sedang memperkecil ukuran foto...
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="p-3 bg-light rounded border h-100 d-flex flex-column justify-content-center align-items-center text-center position-relative overflow-hidden">
              <label class="form-label fw-bold text-dark mb-2">
                <i class="bi bi-geo-alt-fill me-1 text-danger"></i> Lokasi GPS
              </label>

              <div id="locationStatus" class="mb-2">
                <div class="spinner-grow text-primary" role="status" style="width: 2rem; height: 2rem;"></div>
                <div class="small text-muted mt-2 fw-bold animate-pulse">Mencari titik lokasi...</div>
              </div>

              <div id="locationResult" class="d-none">
                <div class="mb-1">
                  <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                </div>
                <h6 class="text-success fw-bold mb-0">Lokasi Terkunci!</h6>
                <div class="small text-muted font-monospace bg-white px-2 py-1 rounded border mt-2" id="coordText">
                  - , -
                </div>
              </div>

              <button type="button" class="btn btn-sm btn-link text-decoration-none mt-2" onclick="getLocation()">
                <i class="bi bi-arrow-clockwise"></i> Refresh GPS
              </button>
            </div>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-bold">Catatan Pembuka (Opsional)</label>
          <textarea name="note" class="form-control" rows="2" placeholder="Contoh: Memulai halaqah di Masjid lantai 2..."></textarea>
        </div>

        <div class="d-grid">
          <button type="submit" id="btnSubmit" class="btn btn-primary rounded-pill btn-lg fw-bold shadow" disabled>
            Buka Sesi & Lanjut Absen Santri <i class="bi bi-arrow-right ms-2"></i>
          </button>

          <div id="submitWarning" class="text-center text-danger small mt-2 fw-bold">
            <i class="bi bi-exclamation-circle me-1"></i> Menunggu GPS terkunci...
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>

<script>
  // --- VARIABEL STATE ---
  let isCompressing = false;
  let isLocationLocked = false;

  // --- 1. LOGIC GPS (Jalankan saat load) ---
  document.addEventListener("DOMContentLoaded", function() {
    getLocation();
  });

  function getLocation() {
    const statusDiv = document.getElementById('locationStatus');
    const resultDiv = document.getElementById('locationResult');
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const coordText = document.getElementById('coordText');

    // Reset UI ke mode loading
    isLocationLocked = false;
    statusDiv.classList.remove('d-none');
    resultDiv.classList.add('d-none');
    checkSubmitButton(); // Cek tombol

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        // SUKSES
        function(position) {
          latInput.value = position.coords.latitude;
          lngInput.value = position.coords.longitude;

          statusDiv.classList.add('d-none');
          resultDiv.classList.remove('d-none');
          coordText.innerText = position.coords.latitude.toFixed(6) + ", " + position.coords.longitude.toFixed(6);

          isLocationLocked = true;
          checkSubmitButton();
        },
        // ERROR
        function(error) {
          let msg = "Gagal mengambil lokasi.";
          switch (error.code) {
            case error.PERMISSION_DENIED:
              msg = "Akses lokasi ditolak. Mohon izinkan GPS browser.";
              break;
            case error.POSITION_UNAVAILABLE:
              msg = "Sinyal GPS tidak ditemukan.";
              break;
            case error.TIMEOUT:
              msg = "Waktu permintaan lokasi habis.";
              break;
          }
          statusDiv.innerHTML = `<div class='text-danger fw-bold'><i class='bi bi-x-circle'></i> ${msg}</div>`;
          isLocationLocked = false;
          checkSubmitButton();
        }, {
          enableHighAccuracy: true
          , timeout: 15000
          , maximumAge: 0
        }
      );
    } else {
      statusDiv.innerHTML = "<span class='text-danger'>Browser ini tidak mendukung GPS.</span>";
    }
  }

  // --- 2. LOGIC KOMPRESI FOTO ---
  document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    isCompressing = true;
    checkSubmitButton(); // Matikan tombol

    document.getElementById('compressionLoading').classList.remove('d-none');

    new Compressor(file, {
      quality: 0.6, // Kualitas 60%
      maxWidth: 1000, // Resize lebar maks 1000px
      mimeType: 'image/jpeg'
      , success(result) {
        // Ganti file di input dengan file hasil kompres
        const dataTransfer = new DataTransfer();
        const fileConverted = new File([result], file.name, {
          type: result.type
          , lastModified: Date.now()
        });
        dataTransfer.items.add(fileConverted);
        document.getElementById('photoInput').files = dataTransfer.files;

        isCompressing = false;
        document.getElementById('compressionLoading').classList.add('d-none');
        checkSubmitButton(); // Cek tombol lagi
      }
      , error(err) {
        console.error(err.message);
        isCompressing = false;
        document.getElementById('compressionLoading').classList.add('d-none');
        alert("Gagal mengkompres foto. Menggunakan file asli.");
        checkSubmitButton();
      }
    , });
  });

  // --- 3. LOGIC TOMBOL SUBMIT ---
  function checkSubmitButton() {
    const btn = document.getElementById('btnSubmit');
    const warn = document.getElementById('submitWarning');
    const fileInput = document.getElementById('photoInput');

    // Syarat tombol aktif: 
    // 1. Lokasi terkunci (Lat/Lng terisi)
    // 2. Tidak sedang kompres foto
    // 3. File foto sudah dipilih (optional check, html required handles it usually)

    if (isLocationLocked && !isCompressing) {
      btn.disabled = false;
      warn.classList.add('d-none');
    } else {
      btn.disabled = true;
      warn.classList.remove('d-none');

      if (isCompressing) {
        warn.innerText = "Tunggu sebentar, sedang memproses foto...";
      } else if (!isLocationLocked) {
        warn.innerText = "Menunggu GPS terkunci...";
      }
    }
  }

</script>
@endpush
