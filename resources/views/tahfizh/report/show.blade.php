@extends('layouts.app')
@section('title', 'Laporan Tahfizh Santri')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex align-items-center mb-4">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded me-3"><i class="bi bi-arrow-left"></i></a>
      <div class="d-flex align-items-center">
        <div
          class="avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold"
          style="width: 50px; height: 50px; font-size: 1.2rem;">
          {{ substr($student->name, 0, 1) }}
        </div>
        <div>
          <h4 class="fw-bold mb-0">{{ $student->name }}</h4>
          <small class="text-muted">Laporan Perkembangan Tahfizh</small>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-secondary text-white h-100">
          <div class="card-body">
            <small class="">Total Setoran</small>
            <h2 class="fw-bold mb-0 text-white">{{ $totalSetoran }} <span class="fs-6 fw-normal">kali</span></h2>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-info text-white h-100">
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
              <small class="text-muted">{{ $lastSetoran->date->translatedFormat('d F Y') }}</small>
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
