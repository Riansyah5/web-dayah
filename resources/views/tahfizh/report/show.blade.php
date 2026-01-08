@extends('layouts.app')
@section('title', 'Laporan Tahfizh Santri')
@push('link')
@endpush
@push('styles')
  <style>
    .card-total-setoran {
      background-color: #696FC7;
      position: relative;
      overflow: hidden;
    }

    .card-total-setoran::after {
      content: "";
      position: absolute;
      width: 210px;
      height: 210px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: -85px;
      right: -95px;
      z-index: 1;
    }

    .card-total-setoran::before {
      content: "";
      position: absolute;
      width: 210px;
      height: 210px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: -135px;
      right: -15px;
      z-index: 1;
    }

    .card-ziyadah {
      background-color: #FFCF71;
      position: relative;
      overflow: hidden;
    }

    .card-ziyadah::after {
      content: "";
      position: absolute;
      width: 210px;
      height: 210px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      top: -85px;
      right: -95px;
      z-index: 1;
    }

    .card-ziyadah::before {
      content: "";
      position: absolute;
      width: 210px;
      height: 210px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      top: -135px;
      right: -15px;
      z-index: 1;
    }


    /* Animasi Gelombang Air */
    .wave-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      background: rgba(255, 255, 255, 1);
      /* Warna area kosong (lebih solid) */
      z-index: 0;
    }

    .wave-overlay::before,
    .wave-overlay::after {
      content: "";
      position: absolute;
      width: 130%;
      /* Diperkecil agar gelombang lebih halus */
      padding-bottom: 120%;
      top: 100%;
      /* Mulai dari batas bawah overlay (permukaan air) */
      left: 50%;
      background: rgba(255, 255, 255, 1);
      border-radius: 40%;
      transform: translate(-50%, -95%);
      /* Posisi pas di garis batas */
      animation: wave-rotate 6s linear infinite;
    }

    .wave-overlay::after {
      background: rgba(255, 255, 255, 0.5);
      border-radius: 45%;
      animation: wave-rotate-reverse 10s linear infinite;
      /* Durasi beda biar acak */
    }

    @keyframes wave-rotate {
      0% {
        transform: translate(-50%, -95%) rotate(0deg);
      }

      100% {
        transform: translate(-50%, -95%) rotate(360deg);
      }
    }

    @keyframes wave-rotate-reverse {
      0% {
        transform: translate(-50%, -95%) rotate(0deg);
      }

      100% {
        transform: translate(-50%, -95%) rotate(-360deg);
      }
    }
  </style>
@endpush
@section('content')
  <div class="container py-4">
    {{-- // Header Laporan Santri // --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex align-items-center">
        <div class="d-flex align-items-center">
          <div
            class="avatar-md bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold"
            style="width: 50px; height: 50px; font-size: 1.2rem;">
            {{ substr($student->name, 0, 1) }}
          </div>
          <div>
            <h4 class="fw-bold mb-0">{{ $student->name }}</h4>
            <small class="text-muted">Laporan Perkembangan Tahfizh</small>
          </div>
        </div>
      </div>
      <div>
        {{-- tombol cetak SKH --}}
        <a href="{{ route('tahfizh.export.form', $student->id) }}" class="btn btn-sm btn-outline-danger rounded px-3"
          title="Cetak Syahadah">
          <i class="bi bi-printer-fill"></i> SKH
        </a>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm rounded">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
      </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 card-total-setoran text-white h-100">
          <div class="card-body">
            <small class="">Total Setoran</small>
            <h2 class="fw-bold mb-0 text-white">{{ $totalSetoran }} <span class="fs-6 fw-normal">kali</span></h2>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 card-ziyadah text-white h-100">
          <div class="card-body">
            <small class="">Hafalan Baru (Ziyadah)</small>
            <h2 class="fw-bold mb-0 text-white">{{ $totalZiyadah }} <span class="fs-6 fw-normal">kali</span></h2>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body">
            <small class="text-muted">Posisi Terakhir</small>
            @if ($lastSetoran)
              <div class="fw-bold text-dark">{{ $lastSetoran->location }}</div>
              <small class="text-muted">{{ $lastSetoran->date->locale('id')->translatedFormat('d F Y') }}</small>
            @else
              <div class="fw-bold">-</div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Tren Keaktifan (6 Bulan Terakhir)</h6>
          </div>
          <div class="card-body">
            <div style="height: 300px; position: relative;">
              <canvas id="activityChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Distribusi Kualitas</h6>
          </div>
          <div class="card-body">
            <div style="height: 250px; position: relative;">
              <canvas id="qualityChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Peta Kemajuan Hafalan -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Peta Kemajuan Hafalan (30 Juz)</h6>

        <div class="d-flex gap-2 small">
          <span class="badge bg-success">Selesai (Khatam)</span>
          <span class="badge bg-warning text-dark">Sedang Berjalan</span>
        </div>
      </div>
      <div class="card-body">

        <div class="alert alert-light border d-flex flex-column flex-md-row align-items-md-center mb-4">
          <div class="me-4 mb-2 mb-md-0">
            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Posisi Terakhir</small>
            <span class="fw-bold text-dark fs-5">
              @if ($lastSetoran && $lastSetoran->type == 'ziyadah')
                {{ $lastSetoran->location }}
              @else
                - Belum ada Ziyadah -
              @endif
            </span>
          </div>
          <div class="vr d-none d-md-block me-4"></div>
          <div class="me-4 mb-2 mb-md-0">
            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Total Akumulasi Ayat</small>
            <span class="fw-bold text-primary fs-5">{{ number_format($totalVersesHafal) }} Ayat</span>
          </div>
          <div class="vr d-none d-md-block me-4"></div>
          <div>
            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Estimasi Juz</small>
            <span class="fw-bold text-success fs-5">{{ number_format(($totalVersesHafal / 6236) * 30, 1) }} Juz</span>
          </div>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-center">
          @for ($i = 1; $i <= 30; $i++)
            @php
              $data = $juzStatus[$i];
            @endphp

            <div class="text-center position-relative tooltip-container" style="width: 48px;">
              <div
                class="btn {{ $data['color'] }} rounded-circle fw-bold d-flex align-items-center justify-content-center shadow-sm mb-1 position-relative overflow-hidden"
                style="width: 42px; height: 42px; cursor: default;"
                title="Juz {{ $i }}: {{ $data['percent'] }}% Terhafal">

                <span style="z-index: 2; position: relative;">{{ $i }}</span>

                @if ($data['status'] == 'process')
                  <div class="wave-overlay" style="height: {{ 100 - $data['percent'] }}%; pointer-events: none;"></div>
                @endif
              </div>

              @if ($data['status'] != 'none')
                <small style="font-size: 0.6rem;"
                  class="d-block fw-bold {{ $data['status'] == 'khatam' ? 'text-success' : 'text-warning' }}">
                  {{ $data['percent'] }}%
                </small>
              @else
                <small style="font-size: 0.6rem;" class="text-muted">Juz</small>
              @endif
            </div>
          @endfor
        </div>

        <div class="mt-4 pt-3 border-top text-center">
          <small class="text-muted fst-italic" style="font-size: 0.8rem;">
            * Perhitungan berdasarkan akumulasi jumlah ayat yang disetorkan (Ziyadah).
          </small>
        </div>

      </div>
    </div>

    {{-- // Riwayat Setoran Terakhir --}}
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">10 Riwayat Setoran Terakhir</h6>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light small text-muted">
            <tr>
              <th class="ps-4">Tanggal</th>
              <th>Jenis</th>
              <th>Hafalan</th>
              <th>Kualitas</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($history as $h)
              <tr>
                <td class="ps-4 fw-bold">{{ $h->date->format('d/m/Y') }}</td>
                <td>
                  @if ($h->type == 'ziyadah')
                    <span class="badge bg-success bg-opacity-10 text-success">Ziyadah</span>
                  @else
                    <span class="badge bg-warning bg-opacity-10 text-warning">Muraja'ah</span>
                  @endif
                </td>
                <td>{{ $h->location }}</td>
                <td>
                  @if ($h->quality == 'lancar')
                    <span class="badge bg-primary">Lancar</span>
                  @elseif($h->quality == 'kurang')
                    <span class="badge bg-warning text-dark">Kurang</span>
                  @else
                    <span class="badge bg-danger">Ulang</span>
                  @endif
                </td>
                <td class="text-muted small">{{ $h->note ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">Belum ada data setoran.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // 1. Config Grafik Keaktifan (Line Chart)
    const ctxActivity = document.getElementById('activityChart');
    new Chart(ctxActivity, {
      type: 'line',
      data: {
        labels: @json($months), // Data Bulan dari Controller
        datasets: [{
          label: 'Jumlah Setoran',
          data: @json($counts), // Data Jumlah dari Controller
          borderColor: '#79C9C5',
          backgroundColor: 'rgba(13, 110, 253, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4 // Garis melengkung halus
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });

    // 2. Config Grafik Kualitas (Doughnut Chart)
    const ctxQuality = document.getElementById('qualityChart');
    new Chart(ctxQuality, {
      type: 'doughnut',
      data: {
        labels: ['Lancar', 'Kurang Lancar', 'Ulang'],
        datasets: [{
          data: @json($pieData), // [10, 5, 2]
          backgroundColor: [
            '#198754', // Hijau (Lancar)
            '#ffc107', // Kuning
            '#dc3545' // Merah
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  </script>
@endpush
