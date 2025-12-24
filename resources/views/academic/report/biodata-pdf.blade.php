<!DOCTYPE html>
<html>

<head>
  <title>Biodata Peserta Didik</title>
  <style>
    body {
      font-family: Cambria, sans-serif;
      font-size: 11pt;
    }

    .title {
      text-align: center;
      font-weight: bold;
      margin-bottom: 40px;
      text-transform: uppercase;
    }

    /* Layout Tabel Utama */
    .table-data {
      width: 100%;
      border-collapse: collapse;
      border: none;
    }

    .table-data td {
      vertical-align: top;
      padding: 5px 0;
    }

    /* Kolom Angka (1.) */
    .col-num {
      width: 5%;
      text-align: center;
    }

    /* Kolom Label (Nama) */
    .col-label {
      width: 35%;
    }

    /* Kolom Titik Dua (:) */
    .col-sep {
      width: 3%;
    }

    /* Kolom Isi */
    .col-val {
      width: 57%;
      font-weight: bold;
    }

    /* Foto Box */
    .photo-box {
			display: flex;
			justify-content: center;
			align-items: center;
      width: 3cm;
      height: 4cm;
      border: 1px solid #000;
      text-align: center;
      /* line-height: 4cm; */
      font-size: 10pt;
      color: #aaa;
      margin-right: 20px;
    }

    /* Footer TTD */
    .signature-table {
      width: 100%;
      margin-top: 50px;
    }

    .signature-table td {
      text-align: center;
      vertical-align: top;
    }

    .signature-name {
      text-decoration: underline;
      font-weight: bold;
      margin-top: 80px;
    }

    .watermark {
      position: fixed;
      top: 20%;
      left: 10%;
      width: 80%;
      opacity: 0.08;
      z-index: -1;
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
  <div class="title">
    <h2>KETERANGAN TENTANG DIRI PESERTA DIDIK</h2>
  </div>

  <table class="table-data">
    <tr>
      <td class="col-num">1.</td>
      <td class="col-label">Nama Peserta Didik (Lengkap)</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ strtoupper($student->name) }}</td>
    </tr>
    <tr>
      <td class="col-num">2.</td>
      <td class="col-label">Nomor Induk / NISN</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->nis }} / {{ $student->nisn ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num">3.</td>
      <td class="col-label">Tempat, Tanggal Lahir</td>
      <td class="col-sep">:</td>
      <td class="col-val">
        {{ $student->birth_place ?? '.........' }},
        {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->locale('id')->translatedFormat('d F Y') : '.........' }}
      </td>
    </tr>
    <tr>
      <td class="col-num">4.</td>
      <td class="col-label">Jenis Kelamin</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
    </tr>
    <tr>
      <td class="col-num">5.</td>
      <td class="col-label">Agama</td>
      <td class="col-sep">:</td>
      <td class="col-val">Islam</td>
    </tr>
    <tr>
      <td class="col-num">6.</td>
      <td class="col-label">Status dalam Keluarga</td>
      <td class="col-sep">:</td>
      <td class="col-val">Anak Kandung</td>
    </tr>
    <tr>
      <td class="col-num">7.</td>
      <td class="col-label">Anak ke</td>
      <td class="col-sep">:</td>
      <td class="col-val">......</td>
    </tr>
    <tr>
      <td class="col-num">8.</td>
      <td class="col-label">Alamat Peserta Didik</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->address ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num">9.</td>
      <td class="col-label">Nomor Telepon Rumah/HP</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->phone ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num">10.</td>
      <td class="col-label">Diterima di Madrasah/Sekolah ini</td>
      <td class="col-sep"></td>
      <td class="col-val"></td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">a. Di Kelas</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $acceptedClass }}</td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">b. Pada Tanggal</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ \Carbon\Carbon::parse($acceptedDate)->locale('id')->translatedFormat('d F Y') }}</td>
    </tr>

    {{-- Data Orang Tua --}}
    <tr>
      <td class="col-num">11.</td>
      <td class="col-label">Nama Orang Tua</td>
      <td class="col-sep"></td>
      <td class="col-val"></td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">a. Ayah</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->father_name ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">b. Ibu</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->mother_name ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num">12.</td>
      <td class="col-label">Pekerjaan Orang Tua</td>
      <td class="col-sep"></td>
      <td class="col-val"></td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">a. Ayah</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->father_job ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">b. Ibu</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->mother_job ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num">13.</td>
      <td class="col-label">Alamat Orang Tua</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->address ?? '-' }}</td> {{-- Asumsi sama dengan santri --}}
    </tr>

    {{-- Data Wali --}}
    <tr>
      <td class="col-num">14.</td>
      <td class="col-label">Wali Peserta Didik</td>
      <td class="col-sep"></td>
      <td class="col-val"></td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">a. Nama Wali</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->guardian_name ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">b. Pekerjaan Wali</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->guardian_job ?? '-' }}</td>
    </tr>
    <tr>
      <td class="col-num"></td>
      <td class="col-label" style="padding-left: 20px;">c. Alamat Wali</td>
      <td class="col-sep">:</td>
      <td class="col-val">{{ $student->guardian_address ?? '-' }}</td>
    </tr>
  </table>

  <table class="signature-table">
    <tr>
      <td width="40%"></td>
      <td width="20%" style="">
        <div class="photo-box">
          Pas Foto<br>3 x 4
        </div>
      </td>
      <td width="40%">
        {{ $city }}, {{ \Carbon\Carbon::parse($printDate)->locale('id')->translatedFormat('d F Y') }}<br>
        Kepala Sekolah
        <div class="signature-name">{{ $headmaster }}</div>
        <div>NIP/NIY. {{ $headmasterNip }}</div>
      </td>
    </tr>
  </table>

</body>

</html>
