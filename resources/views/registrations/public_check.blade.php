<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cek Status Pendaftaran Santri Baru - Dayah Mataqu</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">

  <style>
    :root {
      --iz-heading: #121926;
      --iz-body: #364152;
      --iz-muted: #697586;
      --iz-line: #e3e8ef;
      --iz-surface: #ffffff;
      --iz-secondary: #673ab7;
      --iz-primary: #2196f3;
      --iz-success: #00a152;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f4f6f9;
      color: var(--iz-body);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .main-box {
      max-width: 680px;
      margin: 3rem auto;
      padding: 0 1rem;
      width: 100%;
    }

    .search-card {
      background: #ffffff;
      border: 1px solid var(--iz-line);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(18, 25, 38, .04);
      margin-bottom: 1.5rem;
    }

    .result-card {
      background: #ffffff;
      border: 1px solid var(--iz-line);
      border-radius: 16px;
      padding: 1.75rem 2rem;
      box-shadow: 0 4px 20px rgba(18, 25, 38, .04);
    }

    .status-badge-lg {
      font-size: .95rem;
      padding: .5rem 1rem;
      border-radius: 50rem;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <header class="bg-white border-bottom py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
          <i class="bi bi-mortarboard-fill"></i>
        </div>
        <span class="fw-bold fs-5 text-dark">Dayah Mataqu</span>
      </div>
      <a href="{{ route('psb.register') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-pencil-square me-1"></i> Form Pendaftaran
      </a>
    </div>
  </header>

  <div class="main-box">
    <div class="text-center mb-4">
      <h3 class="fw-bold text-dark">Cek Status Pendaftaran Santri</h3>
      <p class="text-muted small">Masukkan Nomor Registrasi (misal: PSB-202611-0001) atau 16 digit NIK Calon Santri.</p>
    </div>

    <!-- PENCARIAN -->
    <div class="search-card">
      <form action="{{ route('psb.check') }}" method="GET">
        <div class="input-group">
          <input type="text" name="keyword" class="form-control form-control-lg fs-6" placeholder="Nomor Registrasi atau NIK..." value="{{ request('keyword') }}" required>
          <button type="submit" class="btn btn-primary px-4 fw-semibold">
            <i class="bi bi-search me-1"></i> Cari
          </button>
        </div>
      </form>
    </div>

    <!-- HASIL PENCARIAN -->
    @if(request()->filled('keyword'))
      @if($registration)
        <div class="result-card">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pb-3 border-bottom mb-3">
            <div>
              <span class="text-muted small">Nomor Registrasi:</span>
              <div class="fw-bold fs-5 text-primary font-monospace">{{ $registration->registration_number }}</div>
            </div>
            <div>
              @php
                $badgeClasses = [
                  'pending' => 'bg-warning-subtle text-warning border border-warning-subtle',
                  'verified' => 'bg-info-subtle text-info border border-info-subtle',
                  'passed' => 'bg-success-subtle text-success border border-success-subtle',
                  'rejected' => 'bg-danger-subtle text-danger border border-danger-subtle',
                  'enrolled' => 'bg-primary-subtle text-primary border border-primary-subtle',
                ];
              @endphp
              <span class="badge status-badge-lg {{ $badgeClasses[$registration->status] ?? 'bg-secondary' }}">
                {{ $registration->status_label }}
              </span>
            </div>
          </div>

          <div class="row g-2 mb-3 small">
            <div class="col-sm-4 text-muted">Nama Calon Santri:</div>
            <div class="col-sm-8 fw-semibold text-dark">{{ $registration->name }}</div>

            <div class="col-sm-4 text-muted">Jenjang Tujuan:</div>
            <div class="col-sm-8 fw-semibold text-dark">{{ $registration->target_stage }}</div>

            <div class="col-sm-4 text-muted">Asal Sekolah:</div>
            <div class="col-sm-8 text-dark">{{ $registration->previous_school ?: '-' }}</div>

            <div class="col-sm-4 text-muted">Tanggal Daftar:</div>
            <div class="col-sm-8 text-dark">{{ $registration->created_at->format('d F Y, H:i') }} WIB</div>

            <div class="col-sm-4 text-muted">Status Pembayaran:</div>
            <div class="col-sm-8">
              {!! $registration->payment_status_badge !!}
              @if($registration->payment_notes)
                <div class="text-danger small mt-1"><em>Catatan: {{ $registration->payment_notes }}</em></div>
              @endif
            </div>

            @if($registration->selection_score !== null)
              <div class="col-sm-4 text-muted">Nilai Seleksi:</div>
              <div class="col-sm-8 fw-bold {{ $registration->selection_score >= 70 ? 'text-success' : 'text-danger' }}">
                {{ number_format($registration->selection_score, 1) }}
              </div>
            @endif
          </div>

          @if($registration->notes)
            <div class="alert alert-light border small p-3 mb-3">
              <strong class="d-block mb-1 text-dark"><i class="bi bi-chat-left-text me-1"></i> Catatan Panitia:</strong>
              {{ $registration->notes }}
            </div>
          @endif

          <div class="d-flex justify-content-end gap-2 pt-2 border-top">
            <a href="{{ route('psb.slip', $registration->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Bukti Pendaftaran PDF
            </a>
          </div>
        </div>
      @else
        <div class="result-card text-center py-4">
          <i class="bi bi-search text-muted fs-1 d-block mb-2"></i>
          <h5 class="fw-semibold text-dark">Data Tidak Ditemukan</h5>
          <p class="text-muted small mb-3">
            Tidak ada data pendaftaran dengan kata kunci "<strong>{{ request('keyword') }}</strong>". Pastikan nomor registrasi atau NIK yang Anda masukkan sudah benar.
          </p>
          <a href="{{ route('psb.register') }}" class="btn btn-primary btn-sm">
            Daftar Santri Baru Sekarang
          </a>
        </div>
      @endif
    @endif
  </div>

  <footer class="text-center py-4 mt-auto text-muted small">
    &copy; {{ date('Y') }} Dayah Mataqu. Sistem PSB Online.
  </footer>

</body>
</html>
