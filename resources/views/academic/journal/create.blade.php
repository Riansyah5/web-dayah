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
                            <label class="form-label fw-bold">Bukti Foto Kelas (Wajib)</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" capture="environment" required>
                            <div class="form-text">Silakan foto suasana kelas / selfie dengan siswa.</div>
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
                },
                function(error) {
                    // Gagal dapat lokasi
                    statusDiv.className = "alert alert-danger mb-3";
                    statusDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal mendeteksi lokasi. Pastikan GPS aktif dan Browser diizinkan.';
                    console.error("Error Code = " + error.code + " - " + error.message);
                },
                {
                    enableHighAccuracy: true, // Paksa GPS akurasi tinggi
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            statusDiv.innerHTML = "Browser Anda tidak mendukung Geolocation.";
        }
    });
</script>
@endsection
@push('scripts')
@endpush
