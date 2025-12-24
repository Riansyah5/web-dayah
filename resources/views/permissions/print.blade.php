
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Izin - {{ $permission->student->name }}</title>
    <style>
        /* Reset dan Basic */
        body {
            font-family: 'Times New Roman', serif;
            color: #000;
            font-size: 12pt;
        }
        
        /* Layout Halaman */
        @page {
            margin: 0.5cm 1cm 1cm 1cm;
            size: A4 portrait;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .header img {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px; /* Sesuaikan ukuran logo */
            height: auto;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            margin-bottom: 10px;
        }

        /* Judul Surat */
        .letter-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
            font-size: 14pt;
        }

        /* Tabel Informasi */
        .table-info {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .table-info td {
            vertical-align: top;
            padding: 4px 0;
        }
        .label {
            width: 130px;
            font-weight: bold;
        }
        .colon {
            width: 15px;
            text-align: center;
        }

        /* Footer / Tanda Tangan (PENGGANTI FLEXBOX) */
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border: none;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }
        .signature-space {
            height: 70px; /* Ruang untuk tanda tangan */
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 10%;
            left: 10%;
            width: 80%;
            opacity: 0.1;
            z-index: -1000;
        }
    </style>
</head>
<body>

    @php
    $logoDayah = $isPdf ? public_path('assets/images/logo_dayah.png') : asset('assets/images/logo_dayah.png');

    $logoKemenag = $isPdf ? public_path('assets/images/logo_kemenag.png') : asset('assets/images/logo_kemenag.png');
  @endphp
  {{-- <div class="watermark"> --}}
  <img src="{{ $logoDayah }}" class="watermark">

    <div class="header">
        <img src="{{ $logoDayah }}" style="width: 50px; position: absolute; left: 0; top: 0;" alt="Logo Dayah">
        
        <div class="title">Ma'had Ta'limul Qur'an Utsman bin Affan</div>
        <div class="subtitle">Desa Alue Lim, Kec. Blang Mangat, Kota Lhokseumawe</div>
    </div>

    <div class="letter-title">
        SURAT IZIN KELUAR PONDOK
    </div>

    <div class="content">
        <p>Diberikan izin kepada santri:</p>
        
        <table class="table-info">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $permission->student->name }}</td>
            </tr>
            <tr>
                <td class="label">NIS / Asrama</td>
                <td class="colon">:</td>
                <td>{{ $permission->student->nis }} / {{ $permission->student->room ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Keperluan</td>
                <td class="colon">:</td>
                <td>{{ $permission->reason }} ({{ ucfirst($permission->type) }})</td>
            </tr>
        </table>

        <p>Untuk keluar pondok pada waktu sebagai berikut:</p>
        
        <table class="table-info">
            <tr>
                <td class="label">Waktu Keluar</td>
                <td class="colon">:</td>
                <td>{{ $permission->start_date->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Batas Kembali</td>
                <td class="colon">:</td>
                <td><strong>{{ $permission->end_date->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB</strong></td>
            </tr>
        </table>
        
        <p style="font-size: 11px; font-style: italic; margin-top: 10px; border: 1px dashed #000; padding: 5px;">
            <strong>Catatan:</strong> Surat ini wajib dibawa dan diserahkan kembali ke pos keamanan saat kembali ke pondok.
        </p>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <p>Mengetahui,<br>Keamanan Pondok</p>
                <div class="signature-space"></div>
                <div class="signature-name">___________________</div>
            </td>
            <td>
                <p>
                    Lhokseumawe, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
                    Pengurus Perizinan
                </p>
                <div class="signature-space"></div>
                <div class="signature-name">Admin Pondok</div>
            </td>
        </tr>
    </table>

</body>
</html>