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
      --mataqu-green:#05902D; --mataqu-dark:#056D25; --mataqu-lime:#C1E543;
      --mataqu-soft:#F2FBE7; --mataqu-heading:#12351F; --mataqu-body:#344B3A;
      --mataqu-muted:#718276; --mataqu-line:#E1EADF;
    }
    * { box-sizing:border-box; }
    body {
      font-family:'Plus Jakarta Sans',sans-serif; color:var(--mataqu-body); min-height:100vh;
      display:flex; flex-direction:column;
      background:radial-gradient(circle at 8% 8%,rgba(193,229,67,.16),transparent 24rem),
      radial-gradient(circle at 94% 25%,rgba(5,144,45,.10),transparent 26rem),
      linear-gradient(180deg,#FBFDF8 0%,#F3F9EF 48%,#F8FBF6 100%);
    }
    .site-header {
      background:rgba(255,255,255,.94); border-bottom:1px solid var(--mataqu-line);
      box-shadow:0 5px 24px rgba(18,53,31,.055); backdrop-filter:blur(14px);
    }
    .brand-mark {
      width:44px;height:44px;padding:5px;border-radius:12px;display:flex;align-items:center;justify-content:center;
      background-color: rgb(211, 255, 211);
    }
    .brand-mark img { max-height:34px;object-fit:contain; }
    .brand-name { color:var(--mataqu-heading);font-weight:800;letter-spacing:.025em; }
    .brand-caption { color:var(--mataqu-muted);font-size:.75rem; }
    .btn-brand-outline {
      color:var(--mataqu-dark);border:1px solid #A9D68E;background:#F5FBE9;border-radius:999px;
      padding:.55rem 1rem;font-weight:700;transition:.2s ease;
    }
    .btn-brand-outline:hover { color:#fff;border-color:var(--mataqu-green);background:var(--mataqu-green);transform:translateY(-1px); }
    .main-box { max-width:720px;margin:2.5rem auto 3rem;padding:0 1rem;width:100%; }
    .page-heading { text-align:center;margin-bottom:1.5rem; }
    .heading-icon {
      width:58px;height:58px;margin:0 auto .9rem;display:flex;align-items:center;justify-content:center;
      border-radius:18px;color:#fff;font-size:1.55rem;background:linear-gradient(135deg,var(--mataqu-green),#82B72D);
      box-shadow:0 9px 20px rgba(5,144,45,.18);
    }
    .page-heading h2 { color:var(--mataqu-heading);font-weight:800;letter-spacing:-.035em;font-size:clamp(1.35rem,3vw,1.8rem); }
    .page-heading p { color:var(--mataqu-muted);font-size:.88rem;max-width:550px;margin:.5rem auto 0;line-height:1.7; }
    .search-card,.result-card {
      position:relative;background:rgba(255,255,255,.97);border:1px solid var(--mataqu-line);
      border-radius:18px;padding:1.6rem;box-shadow:0 12px 34px rgba(18,53,31,.065);margin-bottom:1.25rem;
    }
    .search-card { overflow:hidden; }
    .search-card::before {
      content:"";position:absolute;height:4px;top:0;left:0;right:0;
      background:linear-gradient(90deg,var(--mataqu-green),var(--mataqu-lime));
    }
    .search-title { color:var(--mataqu-heading);font-weight:700;font-size:.95rem;margin-bottom:.85rem; }
    .search-input {
      min-height:52px;border:1px solid #D4E1D1;border-radius:11px 0 0 11px;
      font-size:.9rem;padding:.75rem 1rem;
    }
    .search-input:focus { border-color:var(--mataqu-green);box-shadow:0 0 0 .2rem rgba(5,144,45,.12); }
    .btn-search {
      border:0;color:#fff;font-weight:700;padding:0 1.4rem;border-radius:0 11px 11px 0;
      background:linear-gradient(105deg,var(--mataqu-dark),var(--mataqu-green));box-shadow:0 5px 12px rgba(5,144,45,.14);
    }
    .btn-search:hover { color:#fff;background:linear-gradient(105deg,#04591E,#087B2B); }
    .search-hint { color:var(--mataqu-muted);font-size:.75rem;margin-top:.75rem; }
    .result-card { padding:1.6rem 1.75rem; }
    .result-top { padding-bottom:1rem;margin-bottom:1.1rem;border-bottom:1px solid #E7EFE3; }
    .result-label { color:var(--mataqu-muted);font-size:.75rem;display:block;margin-bottom:.25rem; }
    .registration-number { color:var(--mataqu-dark);font-weight:800;font-size:1.2rem;letter-spacing:.015em;overflow-wrap:anywhere; }
    .status-badge-lg { font-size:.82rem;padding:.55rem .9rem;border-radius:50rem;font-weight:700;white-space:normal; }
    .detail-grid { display:grid;grid-template-columns:minmax(145px,.8fr) minmax(0,1.5fr);gap:.85rem 1rem;font-size:.85rem; }
    .detail-label { color:var(--mataqu-muted); }
    .detail-value { color:#263F2D;font-weight:600;overflow-wrap:anywhere; }
    .section-caption { color:var(--mataqu-heading);font-weight:700;font-size:.9rem;margin:0 0 1rem; }
    .payment-note { color:#8A5A13;background:#FFFAE8;border:1px solid #F1E2A9;border-radius:10px;padding:.65rem .8rem;margin-top:.55rem; }
    .committee-note { color:#36533B;background:#F3F9EF;border:1px solid #DCEBD4;border-radius:12px; }
    .btn-download {
      color:var(--mataqu-dark);border:1px solid #A9D68E;background:#F5FBE9;border-radius:9px;font-weight:700;
    }
    .btn-download:hover { color:#fff;border-color:var(--mataqu-green);background:var(--mataqu-green); }
    .empty-state { text-align:center;padding:2rem 1.25rem; }
    .empty-icon {
      width:64px;height:64px;margin:0 auto 1rem;border-radius:20px;display:flex;align-items:center;justify-content:center;
      color:var(--mataqu-muted);background:#F1F5ED;font-size:1.8rem;
    }
    .btn-register {
      color:#fff;border:0;border-radius:9px;font-weight:700;background:linear-gradient(105deg,var(--mataqu-dark),var(--mataqu-green));padding:.65rem 1rem;
    }
    .btn-register:hover { color:#fff;background:#056D25; }
    footer { color:var(--mataqu-muted)!important; }
    @media(max-width:575.98px) {
      .main-box { margin:1.6rem auto 2rem; }
      .search-card,.result-card { padding:1.25rem 1rem; }
      .search-input { min-width:0;font-size:.82rem;padding:.65rem .7rem; }
      .btn-search { padding:0 .85rem;font-size:.85rem; }
      .detail-grid { grid-template-columns:1fr;gap:.2rem; }
      .detail-value { margin-bottom:.55rem; }
      .result-top { align-items:flex-start!important; }
      .result-top>div:last-child { margin-top:.25rem; }
      .btn-download { width:100%; }
    }
  </style>
</head>
<body>
  <header class="site-header py-3">
    <div class="container d-flex justify-content-between align-items-center gap-3">
      <div class="d-flex align-items-center gap-2">
        <div class="brand-mark">
          <img src="{{ asset('assets/images/logo_dayah.png') }}" alt="Logo Dayah Mataqu" class="img-fluid">
        </div>
        <div>
          <div class="brand-name fs-5">DAYAH MATAQU</div>
          <div class="brand-caption">Penerimaan Santri Baru Online</div>
        </div>
      </div>
      <a href="{{ route('psb.register') }}" class="btn btn-brand-outline btn-sm text-nowrap">
        <i class="bi bi-pencil-square me-1"></i><span class="d-none d-sm-inline">Form Pendaftaran</span><span class="d-sm-none">Daftar</span>
      </a>
    </div>
  </header>

  <main class="main-box">
    <div class="page-heading">
      <div class="heading-icon"><i class="bi bi-clipboard2-check"></i></div>
      <h2>Cek Status Pendaftaran</h2>
      <p>Pantau proses pendaftaran calon santri Dayah MATAQU dengan memasukkan nomor registrasi atau NIK.</p>
    </div>

    <!-- PENCARIAN -->
    <div class="search-card">
      <div class="search-title"><i class="bi bi-search me-2" style="color:var(--mataqu-green)"></i>Temukan Data Pendaftaran</div>
      <form action="{{ route('psb.check') }}" method="GET">
        <div class="input-group">
          <input type="text" name="keyword" class="form-control search-input" placeholder="Nomor Registrasi atau NIK..." value="{{ request('keyword') }}" required>
          <button type="submit" class="btn btn-search"><i class="bi bi-search me-1"></i> Cari</button>
        </div>
        <div class="search-hint"><i class="bi bi-info-circle me-1"></i>Contoh nomor registrasi: PSB-202611-0001 atau masukkan 16 digit NIK calon santri.</div>
      </form>
    </div>

    <!-- HASIL PENCARIAN -->
    @if(request()->filled('keyword'))
      @if($registration)
        <div class="result-card">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 result-top">
            <div>
              <span class="result-label">Nomor Registrasi</span>
              <div class="registration-number font-monospace">{{ $registration->registration_number }}</div>
            </div>
            <div>
              @php
                $badgeClasses = [
                  'pending' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                  'verified' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                  'passed' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
                  'rejected' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle',
                  'enrolled' => 'bg-success text-white',
                ];
              @endphp
              <span class="badge status-badge-lg {{ $badgeClasses[$registration->status] ?? 'bg-secondary' }}">{{ $registration->status_label }}</span>
            </div>
          </div>

          <h6 class="section-caption"><i class="bi bi-person-vcard me-2" style="color:var(--mataqu-green)"></i>Informasi Calon Santri</h6>
          <div class="detail-grid mb-4">
            <div class="detail-label">Nama Calon Santri</div>
            <div class="detail-value">{{ $registration->name }}</div>
            <div class="detail-label">Jenjang Tujuan</div>
            <div class="detail-value">{{ $registration->target_stage }}</div>
            <div class="detail-label">Asal Sekolah</div>
            <div class="detail-value">{{ $registration->previous_school ?: '-' }}</div>
            <div class="detail-label">Tanggal Daftar</div>
            <div class="detail-value">{{ $registration->created_at->format('d F Y, H:i') }} WIB</div>
            <div class="detail-label">Status Pembayaran</div>
            <div class="detail-value">
              {!! $registration->payment_status_badge !!}
              @if($registration->payment_notes)
                <div class="payment-note small"><i class="bi bi-info-circle me-1"></i>{{ $registration->payment_notes }}</div>
              @endif
            </div>
            @if($registration->selection_score !== null)
              <div class="detail-label">Nilai Seleksi</div>
              <div class="detail-value {{ $registration->selection_score >= 70 ? 'text-success' : 'text-danger' }}">{{ number_format($registration->selection_score, 1) }}</div>
            @endif
          </div>

          @if($registration->notes)
            <div class="alert committee-note small p-3 mb-3">
              <strong class="d-block mb-1" style="color:var(--mataqu-heading)"><i class="bi bi-chat-left-text me-1"></i>Catatan Panitia</strong>
              {{ $registration->notes }}
            </div>
          @endif

          <div class="d-flex justify-content-end pt-3 border-top" style="border-color:var(--mataqu-line)!important">
            <a href="{{ route('psb.slip', $registration->id) }}" target="_blank" class="btn btn-download btn-sm px-3 py-2">
              <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Bukti Pendaftaran PDF
            </a>
          </div>
        </div>
      @else
        <div class="result-card empty-state">
          <div class="empty-icon"><i class="bi bi-search"></i></div>
          <h5 class="fw-bold" style="color:var(--mataqu-heading)">Data Tidak Ditemukan</h5>
          <p class="text-muted small mb-3">
            Tidak ada data pendaftaran dengan kata kunci "<strong>{{ request('keyword') }}</strong>". Pastikan nomor registrasi atau NIK yang Anda masukkan sudah benar.
          </p>
          <a href="{{ route('psb.register') }}" class="btn btn-register btn-sm"><i class="bi bi-person-plus me-1"></i> Daftar Santri Baru Sekarang</a>
        </div>
      @endif
    @endif
  </main>

  <footer class="text-center py-4 mt-auto small">
    &copy; {{ date('Y') }} Dayah MATAQU. Sistem Penerimaan Santri Baru Online.
  </footer>
</body>
</html>
