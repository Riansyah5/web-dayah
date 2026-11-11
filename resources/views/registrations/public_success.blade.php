<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran Berhasil - Dayah Mataqu</title>

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
      --iz-success: #00a152;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f4f6f9;
      color: var(--iz-body);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .success-card {
      max-width: 620px;
      margin: auto;
      background: #ffffff;
      border: 1px solid var(--iz-line);
      border-radius: 20px;
      padding: 2.5rem 2rem;
      box-shadow: 0 10px 30px rgba(18, 25, 38, .05);
      text-align: center;
    }

    .icon-badge {
      width: 72px;
      height: 72px;
      background: #e8f5e9;
      color: var(--iz-success);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      margin-bottom: 1.25rem;
    }

    .reg-number-box {
      background: #f8fafc;
      border: 2px dashed #b0bec5;
      border-radius: 12px;
      padding: 1.25rem;
      margin: 1.5rem 0;
    }
    .reg-number {
      font-family: monospace;
      font-size: 1.65rem;
      font-weight: 700;
      color: var(--iz-secondary);
      letter-spacing: .08em;
    }

    .info-list {
      text-align: left;
      background: #fafbfc;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
      border: 1px solid var(--iz-line);
      font-size: .9rem;
    }
    .info-list .row { margin-bottom: .4rem; }
    .info-list .row:last-child { margin-bottom: 0; }
  </style>
</head>
<body>

  <div class="success-card">
    <div class="icon-badge">
      <i class="bi bi-check2-circle"></i>
    </div>

    <h3 class="fw-bold text-dark mb-1">Pendaftaran Berhasil Dikirim!</h3>
    <p class="text-muted small mb-3">Terima kasih telah mendaftar di Dayah Mataqu. Data formulir calon santri telah tersimpan di sistem.</p>

    <!-- BOX NOMOR PENDAFTARAN -->
    <div class="reg-number-box">
      <div class="text-muted small fw-medium mb-1">NOMOR REGISTRASI PENDAFTARAN</div>
      <div class="reg-number" id="regNumText">{{ $registration->registration_number }}</div>
      <button type="button" class="btn btn-sm btn-link text-decoration-none mt-1" onclick="copyRegNum()">
        <i class="bi bi-clipboard"></i> Salin Nomor Registrasi
      </button>
    </div>

    <!-- RINGKASAN DATA -->
    <div class="info-list">
      <div class="row">
        <div class="col-5 text-muted">Nama Calon Santri:</div>
        <div class="col-7 fw-semibold text-dark">{{ $registration->name }}</div>
      </div>
      <div class="row">
        <div class="col-5 text-muted">Jenjang Tujuan:</div>
        <div class="col-7 fw-semibold text-dark">{{ $registration->target_stage }}</div>
      </div>
      <div class="row">
        <div class="col-5 text-muted">Jenis Kelamin:</div>
        <div class="col-7 fw-semibold text-dark">{{ $registration->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
      </div>
      <div class="row">
        <div class="col-5 text-muted">Tanggal Daftar:</div>
        <div class="col-7 text-dark">{{ $registration->created_at->format('d F Y, H:i') }} WIB</div>
      </div>
    </div>

    <div class="alert alert-warning text-start small mb-4">
      <i class="bi bi-info-circle-fill me-1"></i>
      <strong>Penting:</strong> Simpan atau unduh Bukti Pendaftaran ini sebagai tanda bukti resmi saat verifikasi berkas dan pelaksanaan tes seleksi.
    </div>

    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
      <a href="{{ route('psb.slip', $registration->id) }}" target="_blank" class="btn btn-primary px-4 py-2">
        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / Unduh Bukti PDF
      </a>
      <a href="{{ route('psb.check', ['keyword' => $registration->registration_number]) }}" class="btn btn-outline-secondary px-4 py-2">
        <i class="bi bi-search me-1"></i> Pantau Status Seleksi
      </a>
    </div>
  </div>

  <footer class="text-center mt-4 text-muted small">
    &copy; {{ date('Y') }} Dayah Mataqu. Penerimaan Santri Baru Online.
  </footer>

  <script>
    function copyRegNum() {
      const num = document.getElementById('regNumText').innerText.trim();
      navigator.clipboard.writeText(num).then(() => {
        alert('Nomor registrasi disalin: ' + num);
      }).catch(() => {
        prompt('Salin nomor registrasi:', num);
      });
    }
  </script>
</body>
</html>
