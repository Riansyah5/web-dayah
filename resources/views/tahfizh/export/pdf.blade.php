<!DOCTYPE html>
<html>

<head>
  <title>Surat Keterangan Hafalan</title>
  <style>
    body {
      font-family: 'Times New Roman', serif;
      font-size: 12pt;
      line-height: 1.5;
      margin: 0;
      padding: 0;
    }

    /* KOP SURAT (Disederhanakan, bisa diganti gambar logo) */
    .header {
      text-align: center;
      border-bottom: 3px double #000;
      padding-bottom: 10px;
      margin-bottom: 30px;
      font-family: 'Arial', sans-serif;
      line-height: 1.2;
    }

    .header h2 {
      margin: 0;
      font-size: 12pt;
      /* text-transform: uppercase; */
      font-weight: bold;
    }

    .header p {
      margin: 0;
      font-size: 10pt;
      /* font-style: italic; */
    }

    .title {
      text-align: center;
      font-weight: bold;
      text-decoration: underline;
      margin-bottom: 5px;
      font-size: 12pt;
      line-height: 1;
    }

    .number {
      text-align: center;
      margin-bottom: 30px;
      line-height: 1;
    }

    .content {
      margin-bottom: 15px;
      text-align: justify;
    }

    .indent-table {
      margin-left: 30px;
      margin-bottom: 15px;
      width: 90%;
    }

    .indent-table td {
      vertical-align: top;
      padding-bottom: 5px;
    }

    .hafalan-box {
      border: 2px solid #000;
      padding: 15px;
      text-align: center;
      margin: 20px auto;
      width: 70%;
      font-weight: bold;
    }

    .signature {
      float: right;
      width: 40%;
      text-align: left;
      margin-top: 50px;
    }

    .logo-kemenag {
      position: absolute;
      top: 0;
      left: 10px;
    }

    .logo-dayah {
      position: absolute;
      top: 0;
      right: 10px;
    }
  </style>
</head>

<body>
  @php
    if ($isPdf ?? false) {
        $logoKemenagPath = public_path('assets/images/logo_kemenag.png');
        $logoDayahPath = public_path('assets/images/logo_dayah.png');
    } else {
        $logoKemenagPath = asset('assets/images/logo_kemenag.png');
        $logoDayahPath = asset('assets/images/logo_dayah.png');
  } @endphp

  <div class="header">
    <img src="{{ $logoKemenagPath }}" alt="logo-kemenag" style="height: 80px; margin-bottom: 10px;" class="logo-kemenag">
    <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
    <h2>Ma'had Ta'limul Qur'an(MATAQU)</h2>
    <h2>Utsman Bin Affan Lhokseumawe</h2>
    <p>{{ $data['signer_address'] }}</p>
    <img src="{{ $logoDayahPath }}" alt="logo-dayah" style="height: 80px; margin-bottom: 10px;" class="logo-dayah">
  </div>

  <div class="title">SURAT KETERANGAN HAFALAN</div>
  <div class="number">Nomor: {{ $data['letter_number'] }}</div>

  <div class="content">
    Saya yang bertanda tangan di bawah ini:
  </div>

  <table class="indent-table">
    <tr>
      <td width="100">Nama</td>
      <td width="10">:</td>
      <td style="font-weight: bold;">{{ $data['signer_name'] }}</td>
    </tr>
    <tr>
      <td>Jabatan</td>
      <td>:</td>
      <td>{{ $data['signer_role'] }}</td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>:</td>
      <td>{{ $data['signer_address'] }}</td>
    </tr>
  </table>

  <div class="content">
    Menerangkan dengan sesungguhnya bahwa:
  </div>

  <table class="indent-table">
    <tr>
      <td width="100">Nama</td>
      <td width="10">:</td>
      <td style="font-weight: bold;">{{ strtoupper($student->name) }}</td>
    </tr>
    <tr>
      <td>NIS</td>
      <td>:</td>
      <td>{{ $student->nis }}</td>
    </tr>
    <tr>
      <td>Tempat/Tgl Lahir</td>
      <td>:</td>
      <td>{{ $student->birth_place }}, {{ \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d F Y') }}
      </td>
    </tr>
  </table>

  <div class="content">
    Adalah benar santri aktif di Ma'had Ta'limul Qur'an Utsman bin Affan dan berdasarkan evaluasi terakhir, santri
    tersebut telah menyelesaikan hafalan Al-Qur'an sebanyak:
  </div>

  <div class="hafalan-box">
    <span style="font-size: 24pt;">{{ $totalJuz }} JUZ</span><br>
    <span style="font-weight: normal; font-size: 11pt;">(Juz {{ $rincianJuz }})</span>
  </div>

  <div class="content">
    Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.
  </div>

  <div class="signature">
    Lhokseumawe, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
    Kepala Lajnah,
    <br><br><br><br>
    <strong><u>{{ $data['signer_name'] }}</u></strong>
  </div>

  @if ($data['show_signature'])
    <img src="{{ $isPdf ? public_path('assets/images/ttd_dhiawati.png') : asset('assets/images/ttd_dhiawati.png') }}"
      style="width: 130px; position: absolute; bottom: 110px; right: 160px;" alt="ttd">
  @endif

  @if ($data['show_stamp'])
    <img src="{{ $isPdf ? public_path('assets/images/stempel_dayah.png') : asset('assets/images/stempel_dayah.png') }}"
      style="width: 120px; position: absolute; bottom: 90px; right: 230px;" alt="stempel">
  @endif


</body>

</html>
