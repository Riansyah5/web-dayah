@extends('layouts.app')
@section('title', 'Halaman Dashboard')
@push('link')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  {{-- Pindahkan CSS ke file terpisah untuk kerapian dan pengelolaan yang lebih baik --}}
  {{-- <link rel="stylesheet" href="{{ asset('assets/css/dashboard-berry.css') }}"> --}}
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush
@push('styles')
  <style>
    /* REKOMENDASI: Pindahkan semua style ini ke file CSS terpisah (e.g., public/assets/css/dashboard-berry.css) */
    :root {
      --berry-bg: #eef2f6;
      --berry-primary: #673ab7;
      --berry-primary-light: #ede7f6;
      --berry-secondary: #2196f3;
      --berry-secondary-light: #e3f2fd;
      --berry-orange: #ff9800;
      --berry-orange-light: #fff8e1;
      --berry-dark: #364152;
      --berry-grey: #697586;
      --radius-card: 12px;
    }

    /* Buat selector lebih spesifik agar tidak bentrok dengan halaman lain */
    #dashboard-page {
      font-family: 'Roboto', sans-serif;
      background-color: var(--berry-bg);
      color: var(--berry-dark);
    }

    #dashboard-page .card-head-text {
      color: var(--berry-grey);
    }

    /* --- Berry Card Styles --- */
    #dashboard-page .card-berry {
      border: none;
      border-radius: var(--radius-card);
      box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.05);
      background: #fff;
      transition: all 0.3s ease-in-out;
      position: relative;
      overflow: hidden;
      /* margin-bottom: 24px; */
    }

    #dashboard-page .card-berry:hover {
      box-shadow: 0px 8px 15px 0px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }

    /* Hero Card (Full Color) - Total Pegawai */
    #dashboard-page .card-purple-gradient {
      background: linear-gradient(to right, #673ab7, #5e35b1);
      color: #fff;
    }

    #dashboard-page .card-purple-gradient .text-muted {
      color: #d1c4e9 !important;
    }

    /* Decorative Circles for Hero Card */
    #dashboard-page .card-purple-gradient::after {
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

    #dashboard-page .card-purple-gradient::before {
      content: "";
      position: absolute;
      width: 210px;
      height: 210px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: -125px;
      right: -15px;
      z-index: 1;
    }

    /* Hero Card (Full Color) - Total Santri */
    #dashboard-page .card-santri-gradient {
      background: linear-gradient(to right, #2b88cbff, #5e35b1);
      color: #fff;
    }

    #dashboard-page .card-santri-gradient .text-muted {
      color: #d1c4e9 !important;
    }

    /* Decorative Circles for Hero Card */
    #dashboard-page .card-santri-gradient::after {
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

    #dashboard-page .card-santri-gradient::before {
      content: "";
      position: absolute;
      width: 210px;
      height: 210px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: -125px;
      right: -15px;
      z-index: 1;
    }

    /* Icon Wrapper Styles */
    #dashboard-page .icon-wrapper {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 15px;
    }

    #dashboard-page .bg-light-primary {
      background-color: var(--berry-primary-light);
      color: var(--berry-primary);
    }

    #dashboard-page .bg-light-secondary {
      background-color: var(--berry-secondary-light);
      color: var(--berry-secondary);
    }

    #dashboard-page .bg-light-orange {
      background-color: var(--berry-orange-light);
      color: var(--berry-orange);
    }

    /* Typography */
    #dashboard-page .card-head-text {
      font-size: 0.9rem;
      font-weight: 500;
      margin-bottom: 5px;
    }

    #dashboard-page .card-main-number {
      font-size: 2.1rem;
      font-weight: 700;
      margin-bottom: 10px;
    }

    /* Gender List Styling */
    #dashboard-page .gender-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 8px 0;
      border-top: 1px solid rgba(0, 0, 0, 0.05);
      margin-top: 10px;
      font-size: 0.85rem;
    }

    #dashboard-page .card-purple-gradient .gender-row {
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    #dashboard-page .gender-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    /* Copy Button Styling */
    #dashboard-page .copy-btn {
      color: var(--berry-grey);
      cursor: pointer;
      transition: all 0.2s ease;
      font-size: 1.2rem;
      /* Disesuaikan agar lebih seimbang dengan angka */
    }

    #dashboard-page .card-purple-gradient .copy-btn {
      color: rgba(255, 255, 255, 0.7);
    }

    #dashboard-page .copy-btn:hover {
      transform: scale(1.1);
      color: var(--berry-dark);
    }

    #dashboard-page .card-purple-gradient .copy-btn:hover {
      color: #fff;
    }
  </style>
@endpush
@section('content')

  <div id="dashboard-page" class="container-fluid px-md-4 py-4">

    <div class="row align-items-center mb-4">
      <div class="col-md-8">
        <h3 class="fw-bold text-dark">Dashboard</h3>
        <p class="text-muted small mb-0">Selamat datang kembali, {{ Auth::user()->name }}.</p>
      </div>
      <div class="col-md-4 text-end">
        <button class="btn btn-primary rounded-3 shadow-sm" style="background-color: var(--berry-primary); border:none;">
          <i class="fas fa-plus me-2"></i> Tambah Santri
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-berry card-purple-gradient p-4 ">
          <div style="position: relative; z-index: 2;">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="text-muted card-head-text">Pegawai Aktif</div>
                <div class="d-flex align-items-center">
                  <div class="card-main-number text-white" data-target="{{ $totalPegawai }}">0</div>
                  <i class="fas fa-copy copy-btn ms-3" title="Salin Data"></i>
                </div>
              </div>
              <div class="icon-wrapper" style="background: rgba(0,0,0,0.2); color: white;">
                <i class="fas fa-briefcase"></i>
              </div>
            </div>
            <div class="gender-row">
              <span class="gender-badge text-white"><i class="fas fa-mars text-success"></i> {{ $pegawaiLaki }} Laki-laki</span>
              <span class="gender-badge text-white"><i class="fas fa-venus text-danger"></i> {{ $pegawaiPerempuan }} Perempuan</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-berry card-santri-gradient bg-opacity-75 p-4 ">
          <div style="position: relative; z-index: 2;">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="text-muted card-head-text">Total Santri</div>
                <div class="d-flex align-items-center">
                  <div class="card-main-number text-white" data-target="{{ $totalSantri }}">0</div>
                  <i class="fas fa-copy copy-btn ms-3" title="Salin Data"></i>
                </div>
              </div>
              <div class="icon-wrapper" style="background: rgba(0,0,0,0.2); color: white;">
                <i class="fas fa-users"></i>
              </div>
            </div>

            <div class="gender-row">
              <span class="gender-badge"><i class="fas fa-mars text-primary"></i> {{ $santriLaki }} Laki-laki</span>
              <span class="gender-badge"><i class="fas fa-venus text-danger"></i> {{ $santriPerempuan }} Perempuan</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-berry p-4 ">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted card-head-text">Santri Wustha</div>
              <div class="d-flex align-items-center">
                <div class="card-main-number text-dark" data-target="{{ $santriSMP }}">0</div>
                <i class="fas fa-copy copy-btn ms-3" title="Salin Data"></i>
              </div>
            </div>
            <div class="icon-wrapper bg-light-success">
              <i class="fas fa-book-reader"></i>
            </div>
          </div>
          <div class="gender-row">
            <span class="gender-badge text-muted"><i class="fas fa-mars text-primary"></i> {{ $santriSMPlaki }} Laki-laki</span>
            <span class="gender-badge text-muted"><i class="fas fa-venus text-danger"></i> {{ $santriSMPperempuan }} Perempuan</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-berry p-4 ">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted card-head-text">Santri Ulya</div>
              <div class="d-flex align-items-center">
                <div class="card-main-number text-dark" data-target="{{ $santriSMA }}">0</div>
                <i class="fas fa-copy copy-btn ms-3" title="Salin Data"></i>
              </div>
            </div>
            <div class="icon-wrapper bg-light-warning">
              <i class="fas fa-graduation-cap"></i>
            </div>
          </div>
          <div class="gender-row">
            <span class="gender-badge text-muted"><i class="fas fa-mars text-primary"></i> {{ $santriSMAlaki }} Laki-laki</span>
            <span class="gender-badge text-muted"><i class="fas fa-venus text-danger"></i> {{ $santriSMAperempuan }} Perempuan</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-md-8">
        <div class="card card-berry p-4">
          <div class="d-flex justify-content-between mb-4">
            <div>
              <h5 class="fw-bold mb-1">Pertumbuhan Santri</h5>
              <p class="text-muted small mb-0">Statistik per tahun akademik</p>
            </div>
            <div>
              <select class="form-select form-select-sm bg-light border-0">
                <option>Tahun Ini</option>
                <option>Tahun Lalu</option>
              </select>
            </div>
          </div>
          <div id="chart-growth"></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-berry p-4">
          <h5 class="fw-bold mb-4">Sebaran Unit</h5>
          <div id="chart-pie"></div>
          <div class="text-center mt-3">
            <div class="d-flex justify-content-center gap-3">
              <div class="small"><i class="fas fa-circle" style="color: #25f795ff"></i> SMP</div>
              <div class="small"><i class="fas fa-circle" style="color: #ffc107"></i> SMA</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // --- Animasi Angka Naik (Count Up) ---
      const counters = document.querySelectorAll('.card-main-number');
      const speed = 50; // Semakin kecil, semakin cepat

      counters.forEach(counter => {
        const animate = () => {
          const target = +counter.getAttribute('data-target');
          const count = +counter.innerText.replace(/,/g, '');
          const increment = Math.max(1, Math.ceil(target / speed));

          if (count < target) {
            counter.innerText = Math.min(count + increment, target).toLocaleString('id-ID');
            setTimeout(animate, 20);
          } else {
            counter.innerText = target.toLocaleString('id-ID');
          }
        };
        animate();
      });

      // --- Fungsionalitas Tombol Salin ---
      const copyButtons = document.querySelectorAll('.copy-btn');
      copyButtons.forEach(button => {
        button.addEventListener('click', (e) => {
          e.stopPropagation(); // Mencegah event lain terpicu
          const card = button.closest('.card-berry');
          if (!card) return;

          const title = card.querySelector('.card-head-text')?.innerText || '';
          const mainValue = card.querySelector('.card-main-number')?.getAttribute('data-target') || '';
          const genderRow = card.querySelector('.gender-row');

          let textToCopy = `${title}: ${mainValue}`;

          if (genderRow) {
            const genderBadges = genderRow.querySelectorAll('.gender-badge');
            const maleText = genderBadges[0]?.innerText.trim() || '';
            const femaleText = genderBadges[1]?.innerText.trim() || '';
            if (maleText && femaleText) {
              textToCopy += `\n${maleText}\n${femaleText}`;
            }
          }

          navigator.clipboard.writeText(textToCopy).then(() => {
            // Feedback visual
            const originalIcon = button.className;
            button.className = 'fas fa-check-circle copy-btn';
            button.style.color = '#28a745'; // Warna hijau sukses

            setTimeout(() => {
              button.className = originalIcon;
              button.style.color = ''; // Kembali ke warna semula
            }, 2000);
          }).catch(err => {
            console.error('Gagal menyalin data:', err);
          });
        });
      });
    });

    // --- ApexCharts Configuration (Berry Style) ---

    // 1. Bar Chart (Pertumbuhan)
    var optionsGrowth = {
      series: [{
        name: 'Total Santri',
        data: [800, 950, 1050, 1150, 1250]
      }],
      chart: {
        type: 'bar',
        height: 300,
        toolbar: {
          show: false
        },
        fontFamily: 'Poppins, sans-serif'
      },
      colors: ['#3aa3ffff'], // Warna Ungu Berry
      plotOptions: {
        bar: {
          borderRadius: 4,
          columnWidth: '45%',
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['2021', '2022', '2023', '2024', '2025'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      fill: {
        opacity: 1
      },
      grid: {
        strokeDashArray: 4,
        borderColor: '#e2e2e2ff'
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return val + " Santri"
          }
        }
      }
    };

    var chartGrowth = new ApexCharts(document.querySelector("#chart-growth"), optionsGrowth);
    chartGrowth.render();

    // 2. Pie Chart (Unit)
    var optionsPie = {
      series: [{{ $santriSMP }}, {{ $santriSMA }}],
      labels: ['SMP', 'SMA'],
      chart: {
        type: 'donut',
        height: 300,
        fontFamily: 'Poppins, sans-serif'
      },
      colors: ['#25f795ff', '#ffc107'], // Ungu dan Biru
      legend: {
        show: false
      }, // Kita custom legend di HTML
      dataLabels: {
        enabled: false
      },
      plotOptions: {
        pie: {
          donut: {
            size: '65%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total Santri',
                fontSize: '16px',
                fontWeight: 600,
                color: '#364152'
              }
            }
          }
        }
      }
    };

    var chartPie = new ApexCharts(document.querySelector("#chart-pie"), optionsPie);
    chartPie.render();
  </script>
@endpush
