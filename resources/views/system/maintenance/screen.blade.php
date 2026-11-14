<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $maintenance['title'] ?? 'Pemeliharaan Sistem' }} - Dayah Mataqu</title>

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
      --iz-secondary: #5e35b1;
      --iz-primary: #2196f3;
      --iz-warning: #ffab00;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: radial-gradient(circle at top right, #f3e5f5, #f4f6f9 50%, #e3f2fd 100%);
      color: var(--iz-body);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 2rem 1rem;
      margin: 0;
    }

    .maintenance-card {
      max-width: 650px;
      width: 100%;
      background: #ffffff;
      border: 1px solid var(--iz-line);
      border-radius: 24px;
      padding: 3rem 2.5rem;
      box-shadow: 0 20px 40px rgba(18, 25, 38, .07);
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .top-badge-line {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, #ff9800, #f44336, #673ab7);
    }

    .icon-wrapper {
      width: 90px;
      height: 90px;
      background: #fff8e1;
      color: #ff8f00;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      margin-bottom: 1.5rem;
      border: 2px dashed #ffe082;
      animation: pulse 2.5s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.04); }
      100% { transform: scale(1); }
    }

    .brand-logo-text {
      font-size: .85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--iz-secondary);
      margin-bottom: .6rem;
    }

    .main-title {
      font-size: 1.65rem;
      font-weight: 700;
      color: var(--iz-heading);
      margin-bottom: 1rem;
      line-height: 1.3;
    }

    .main-message {
      font-size: .96rem;
      color: var(--iz-muted);
      line-height: 1.6;
      margin-bottom: 1.75rem;
    }

    .estimate-box {
      background: #f8fafc;
      border: 1px solid var(--iz-line);
      border-radius: 12px;
      padding: .85rem 1.25rem;
      margin-bottom: 2rem;
      display: inline-flex;
      align-items: center;
      gap: .75rem;
      font-size: .88rem;
    }

    .btn-custom {
      font-weight: 600;
      padding: .65rem 1.4rem;
      border-radius: 10px;
      font-size: .92rem;
    }

    .footer-note {
      margin-top: 2rem;
      font-size: .8rem;
      color: var(--iz-muted);
    }
  </style>
</head>
<body>

  <div class="maintenance-card">
    <div class="top-badge-line"></div>

    <div class="icon-wrapper">
      <i class="bi bi-tools"></i>
    </div>

    <div class="brand-logo-text">
      <i class="bi bi-mortarboard-fill me-1"></i> Dayah Mataqu
    </div>

    <h2 class="main-title">{{ $maintenance['title'] ?? 'Sistem Sedang Dalam Pemeliharaan' }}</h2>

    <p class="main-message">
      {{ $maintenance['message'] ?? 'Saat ini sistem sedang dinonaktifkan sementara untuk pemeliharaan berkala atau pembaruan sistem. Kami akan segera kembali online setelah proses selesai.' }}
    </p>

    @if(!empty($maintenance['end_at']))
      <div class="estimate-box">
        <i class="bi bi-clock-history text-primary fs-5"></i>
        <div class="text-start">
          <div class="text-muted small">Perkiraan Selesai:</div>
          <strong class="text-dark">{{ \Carbon\Carbon::parse($maintenance['end_at'])->translatedFormat('d F Y, H:i') }} WIB</strong>
        </div>
      </div>
    @endif

    <div class="d-flex flex-wrap justify-content-center gap-2">
      <button type="button" class="btn btn-primary btn-custom d-inline-flex align-items-center gap-2" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise"></i> Cek Kembali
      </button>

      @if(!empty($maintenance['contact_phone']))
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $maintenance['contact_phone']) }}" target="_blank" class="btn btn-outline-success btn-custom d-inline-flex align-items-center gap-2">
          <i class="bi bi-whatsapp"></i> Hubungi Bantuan
        </a>
      @endif
    </div>

    <div class="footer-note">
      <a href="{{ route('login') }}" class="text-decoration-none text-muted">
        <i class="bi bi-shield-lock me-1"></i> Khusus Superadmin: Masuk ke Sistem
      </a>
    </div>
  </div>

</body>
</html>
