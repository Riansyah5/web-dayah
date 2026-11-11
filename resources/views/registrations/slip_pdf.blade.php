<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Bukti Pendaftaran - {{ $registration->registration_number }}</title>
  <style>
    @page {
      margin: 1.5cm;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10pt;
      line-height: 1.4;
      color: #222;
    }
    .header {
      border-bottom: 2px solid #222;
      padding-bottom: 8px;
      margin-bottom: 18px;
      text-align: center;
    }
    .header h2 {
      font-size: 14pt;
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .header h3 {
      font-size: 12pt;
      margin: 3px 0;
      color: #444;
    }
    .header p {
      font-size: 8.5pt;
      margin: 0;
      color: #666;
    }
    .doc-title {
      text-align: center;
      margin-bottom: 15px;
    }
    .doc-title h4 {
      font-size: 12pt;
      margin: 0;
      text-decoration: underline;
      text-transform: uppercase;
    }
    .reg-box {
      border: 1px solid #000;
      background: #f7f9fa;
      padding: 8px 12px;
      margin-bottom: 15px;
      width: 100%;
      box-sizing: border-box;
    }
    .reg-box table {
      width: 100%;
    }
    .reg-box td {
      vertical-align: middle;
    }
    .section-title {
      font-weight: bold;
      font-size: 10pt;
      background: #eef2f5;
      padding: 4px 8px;
      margin: 10px 0 6px 0;
      border-left: 3px solid #2196f3;
      text-transform: uppercase;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }
    table.data-table td {
      padding: 3px 0;
      vertical-align: top;
      font-size: 9.5pt;
    }
    .col-label { width: 32%; }
    .col-sep { width: 3%; text-align: center; }
    .col-val { width: 65%; font-weight: bold; }

    .note-box {
      border: 1px dashed #777;
      padding: 8px 10px;
      margin-top: 15px;
      font-size: 8.5pt;
      background: #fafafa;
    }
    .note-box ul {
      margin: 4px 0 0 15px;
      padding: 0;
    }

    .sign-table {
      width: 100%;
      margin-top: 25px;
    }
    .sign-table td {
      width: 50%;
      text-align: center;
      font-size: 9.5pt;
    }
    .sign-space {
      height: 60px;
    }
  </style>
</head>
<body>

  <!-- KOP SURAT -->
  <div class="header">
    <h2>PANITIA PENERIMAAN SANTRI BARU (PSB)</h2>
    <h3>DAYAH MATAQU</h3>
    <p>Jl. Mataqu No. 01, Aceh &bull; Email: psb@mataqu.sch.id &bull; Website: www.mataqu.sch.id</p>
  </div>

  <!-- JUDUL DOKUMEN -->
  <div class="doc-title">
    <h4>TANDA BUKTI PENDAFTARAN SANTRI BARU</h4>
    <span style="font-size: 9pt;">Tahun Ajaran: {{ $registration->academicYear?->name ?? date('Y') . '/' . (date('Y') + 1) }}</span>
  </div>

  <!-- KOTAK NO REGISTRASI -->
  <div class="reg-box">
    <table>
      <tr>
        <td style="width: 60%;">
          <span style="font-size: 8.5pt; color: #555;">NOMOR REGISTRASI:</span><br>
          <strong style="font-size: 14pt; letter-spacing: 1px; color: #1565c0;">{{ $registration->registration_number }}</strong>
        </td>
        <td style="width: 40%; text-align: right;">
          <span style="font-size: 8.5pt; color: #555;">TANGGAL PENDAFTARAN:</span><br>
          <strong>{{ $registration->created_at->format('d/m/Y - H:i') }} WIB</strong>
        </td>
      </tr>
    </table>
  </div>

  <!-- SECTION 1: DATA CALON SANTRI -->
  <div class="section-title">A. Data Calon Santri</div>
  <table class="data-table">
    <tr>
      <td class="col-label">Nama Lengkap</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->name }}</td>
    </tr>
    <tr>
      <td class="col-label">Pilihan Jenjang Pendidikan</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->target_stage }}</td>
    </tr>
    <tr>
      <td class="col-label">Jenis Kelamin</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
    </tr>
    <tr>
      <td class="col-label">NIK / NISN</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->nik ?: '-' }} / {{ $registration->nisn ?: '-' }}</td>
    </tr>
    <tr>
      <td class="col-label">Tempat, Tanggal Lahir</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->birth_place }}, {{ $registration->birth_date ? \Carbon\Carbon::parse($registration->birth_date)->translatedFormat('d F Y') : '-' }}</td>
    </tr>
    <tr>
      <td class="col-label">Anak Ke-</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->child_order ? 'Anak ke-' . $registration->child_order : '-' }}</td>
    </tr>
    <tr>
      <td class="col-label">Asal Sekolah</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->previous_school ?: '-' }}</td>
    </tr>
    <tr>
      <td class="col-label">Alamat Domisili</td>
      <td class="col-sep">:</td>
      <td class="col-val">
        {{ $registration->village ? 'Gampong ' . $registration->village . ', ' : '' }}
        {{ $registration->district ? 'Kec. ' . $registration->district . ', ' : '' }}
        {{ $registration->regency ?: '' }}
        {{ $registration->province ? '- ' . $registration->province : '' }}
      </td>
    </tr>
  </table>

  <!-- SECTION 2: DATA ORANG TUA / WALI -->
  <div class="section-title">B. Data Orang Tua / Wali</div>
  <table class="data-table">
    <tr>
      <td class="col-label">Nama Ayah Kandung</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->father_name ?: '-' }} ({{ $registration->father_status == 'deceased' ? 'Alm.' : 'Hidup' }})</td>
    </tr>
    <tr>
      <td class="col-label">Pekerjaan Ayah</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->father_occupation ?: '-' }} {{ $registration->father_occupation_detail ? '(' . $registration->father_occupation_detail . ')' : '' }}</td>
    </tr>
    <tr>
      <td class="col-label">Nama Ibu Kandung</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->mother_name ?: '-' }} ({{ $registration->mother_status == 'deceased' ? 'Almh.' : 'Hidup' }})</td>
    </tr>
    <tr>
      <td class="col-label">Pekerjaan Ibu</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->mother_occupation ?: '-' }} {{ $registration->mother_occupation_detail ? '(' . $registration->mother_occupation_detail . ')' : '' }}</td>
    </tr>
    <tr>
      <td class="col-label">No. Telepon / WhatsApp</td>
      <td class="col-sep">:</td>
      <td class="col-val">+{{ $registration->father_phone ?: ($registration->mother_phone ?: $registration->guardian_phone ?: '-') }}</td>
    </tr>
    @if($registration->guardian_name)
    <tr>
      <td class="col-label">Nama Wali</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $registration->guardian_name }} (+{{ $registration->guardian_phone ?: '-' }})</td>
    </tr>
    @endif
  </table>

  <!-- PETUNJUK & CATATAN -->
  <div class="note-box">
    <strong>Petunjuk & Kelengkapan Berkas Seleksi:</strong>
    <ul>
      <li>Simpan lembar bukti pendaftaran ini dan bawa saat verifikasi berkas dan pelaksanaan tes seleksi.</li>
      <li>Membawa fotokopi Kartu Keluarga (KK), Akta Kelahiran, dan Ijazah / Surat Keterangan Lulus (SKL) dari sekolah asal.</li>
      <li>Informasi jadwal tes dan pengumuman hasil seleksi dapat dicek berkala di portal resmi: <strong>{{ url('/psb/cek-status') }}</strong>.</li>
    </ul>
  </div>

  <!-- TANDA TANGAN -->
  <table class="sign-table">
    <tr>
      <td>
        Calon Santri / Orang Tua Wali,
        <div class="sign-space"></div>
        <strong>( .................................................... )</strong>
      </td>
      <td>
        Panitia Penerimaan Santri Baru,
        <div class="sign-space"></div>
        <strong>( Panitia PSB Mataqu )</strong>
      </td>
    </tr>
  </table>

</body>
</html>
