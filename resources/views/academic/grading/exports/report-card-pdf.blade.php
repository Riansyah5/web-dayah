<!DOCTYPE html>
<html>

<head>
  <title>Rapor Siswa</title>
  <style>
    body {
      /*font-family: Arial, sans-serif;*/
      font-family: Cambria;
      font-size: 11pt;
      line-height: 1.3;
    }

    /* Header Kop Surat */
    .header {
      text-align: center;
      border-bottom: 3px double #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
      font-family: 'Times New Roman', Times, serif;
    }

    .header h2 {
      margin: 0;
      font-size: 14pt;
      text-transform: uppercase;
    }

    .header h3 {
      margin: 0 0;
      font-size: 14pt;
    }

    .header p {
      margin: 0;
      font-size: 10pt;
    }

    /* Tabel Biodata */
    .biodata-table {
      width: 100%;
      margin-bottom: 15px;
    }

    .biodata-table td {
      padding: 3px;
      vertical-align: top;
    }

    /* Tabel Nilai */
    .table-nilai {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }

    .table-nilai th,
    .table-nilai td {
      border: 1px solid #000;
      padding: 4px;
    }

    .table-nilai th {
      background-color: #f0f0f0;
      text-align: center;
      font-weight: bold;
    }

    /* Helper */
    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .text-bold {
      font-weight: bold;
    }

    .mb-1 {
      margin-bottom: 5px;
    }

    .mt-4 {
      margin-top: 20px;
    }

    /* Footer Tanda Tangan (Agar tidak terpotong halaman) */
    .signature-section {
      page-break-inside: avoid;
      margin-top: 30px;
    }

    .signature-table {
      width: 100%;
      border: none;
    }

    .signature-table td {
      border: none;
      text-align: center;
      vertical-align: top;
    }

    .signature-name {
      text-decoration: underline;
      font-weight: bold;
      margin-top: 70px;
    }

    .watermark {
      position: fixed;
      top: 25%;
      left: 10%;
      width: 80%;
      opacity: 0.08;
      z-index: -1;
    }


    /* SETUP PRINT */
    @media print {

      body,
      .page {
        background: white;
        margin: 0;
        box-shadow: none;
      }

      .page {
        width: 100%;
        padding: 20mm;
        /* Tetap pertahankan padding saat print */
        /* page-break-after: always; */
        /* Ganti halaman tiap rapor siswa */
      }

      @page {
        size: A4;
        margin: 1cm;
      }
    }
  </style>
</head>

<body>
@php
  $logoDayah = $isPdf
      ? public_path('assets/images/logo_dayah.png')
      : asset('assets/images/logo_dayah.png');

  $logoKemenag = $isPdf
      ? public_path('assets/images/logo_kemenag.png')
      : asset('assets/images/logo_kemenag.png');
@endphp

  {{-- <div class="watermark"> --}}
  <img src="{{ $logoDayah}}" class="watermark">
  {{-- </div> --}}
  <div class="header">
    <table width="100%">
      <tr>
        <td width="15%" align="center">
          <img src="{{ $logoKemenag }}" width="70">
        </td>
        <td width="70%" align="center">
          <h2>KEMENTERIAN AGAMA</h2>
          <h3>{{ strtoupper($classroom->level->stage->name ?? '-') }}</h3>
          <h3>Ma'had Ta'limul Qur'an Utsman bin Affan</h3>
          <p>Jl. Line Pipa, Desa Alue Liem, Kec. Blang Mangat, Kota Lhokseumawe</p>
        </td>
        <td width="15%" align="center">
          <img src="{{ $logoDayah }}" width="70">
        </td>
      </tr>
    </table>
  </div>

  <table class="biodata-table">
    <tr>
      <td width="15%">Nama</td>
      <td width="40%">: <strong>{{ $student->name }}</strong></td>
      <td width="20%">Kelas / Semester</td>
      <td width="25%">: {{ $classroom->name }} / {{ $classroom->academicYear->semester }}</td>
    </tr>
    <tr>
      <td>NIS / NISN</td>
      <td>: {{ $student->nis }} / {{ $student->nisn }}</td>
      <td>Tahun Pelajaran</td>
      <td>: {{ $classroom->academicYear->name }}</td>
    </tr>
  </table>

  <h4 class="mb-1">A. Capaian Hasil Belajar</h4>
  <table class="table-nilai">
    <thead>
      <tr>
        <th width="5%">No</th>
        <th width="30%">Mata Pelajaran</th>
        <th width="8%">KKM</th>
        <th width="10%">Nilai</th>
        <th width="10%">Predikat</th>
        <th>Deskripsi Kemampuan</th>
      </tr>
    </thead>
    <tbody>
      @php
        $currentGroup = '';
        $no = 1;

        // Variabel untuk hitung rata-rata
        $totalNilai = 0;
        $jumlahMapel = 0;
      @endphp

      @foreach ($courses as $course)
        {{-- Grouping Header --}}
        @if ($course->subject->group != $currentGroup)
          @php $currentGroup = $course->subject->group; @endphp
          <tr>
            <td colspan="6" class="text-bold" style="background:#eef; padding: 4px 8px;">Muatan {{ $currentGroup }}
            </td>
          </tr>
        @endif

        @php
          $grade = $course->grades->first();
          $finalScore = $grade->score_final ?? 0;

          // Tambahkan ke kalkulasi rata-rata (hanya jika mapel aktif/ada nilai)
          if ($grade) {
              $totalNilai += $finalScore;
              $jumlahMapel++;
          }

          // Ambil Silabus untuk Deskripsi
          $syllabus = \App\Models\Syllabus::where('subject_id', $course->subject_id)
              ->where('level_id', $classroom->level_id)
              ->where('semester', $classroom->academicYear->semester)
              ->first();
          $topics = $syllabus->topics ?? 'materi pembelajaran';
        @endphp

        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td>{{ $course->subject->name }}</td>
          <td class="text-center">{{ $course->kkm }}</td>
          <td class="text-center text-bold">{{ $finalScore }}</td>
          <td class="text-center">{{ $grade->grade_letter ?? '-' }}</td>
          <td style="font-size: 9pt;">
            @if ($finalScore >= 90)
              Sangat baik dalam memahami materi <strong>{{ $topics }}</strong>.
            @elseif($finalScore >= $course->kkm)
              Baik dalam memahami materi <strong>{{ $topics }}</strong>.
            @else
              Perlu bimbingan dalam materi <strong>{{ $topics }}</strong>.
            @endif
          </td>
        </tr>
      @endforeach

      {{-- BARIS RATA-RATA & PREDIKAT --}}
      @php
        $rataRata = $jumlahMapel > 0 ? round($totalNilai / $jumlahMapel, 2) : 0;

        // Tentukan Predikat Rata-rata
        if ($rataRata >= 85) {
            $predikatRata = 'Mumtaz (Istimewa)';
        } elseif ($rataRata >= 70) {
            $predikatRata = 'Jayyid Jiddan (Sangat Baik)';
        } elseif ($rataRata >= 60) {
            $predikatRata = 'Jayyid (Baik)';
        } else {
            $predikatRata = ' - ';
        }
      @endphp
      <tr>
        <td colspan="3" class="text-center text-bold" style="background-color: #f9f9f9;">Jumlah & Rata-rata Nilai
        </td>
        <td class="text-center text-bold" style="background-color: #f9f9f9;">{{ $rataRata }}</td>
        <td colspan="2" class="text-bold" style="background-color: #f9f9f9; padding-left: 10px;">
          {{ $predikatRata }}
        </td>
      </tr>
    </tbody>
  </table>

  <table width="100%" style="border: none; margin-bottom: 20px;">
    <tr>
      <td width="40%" valign="top">
        {{-- Ketidakhadiran --}}
        <table class="table-nilai">
          <tr>
            <th colspan="2">Ketidakhadiran</th>
          </tr>
          <tr>
            <td>Sakit</td>
            <td class="text-center">{{ $reportCard->sick ?? 0 }} hari</td>
          </tr>
          <tr>
            <td>Izin</td>
            <td class="text-center">{{ $reportCard->permission ?? 0 }} hari</td>
          </tr>
          <tr>
            <td>Tanpa Keterangan</td>
            <td class="text-center">{{ $reportCard->absent ?? 0 }} hari</td>
          </tr>
        </table>
      </td>

      <td width="15%"></td> {{-- Spacer --}}

      <td width="45%" valign="top">
        {{-- Predikat --}}
        <table class="table-nilai">
          <tr>
            <th colspan="2">Predikat</th>
          </tr>
          <tr>
            <td>Mumtaz (Istimewa)</td>
            <td class="text-center"> >= 85 </td>
          </tr>
          <tr>
            <td>Jayyid Jiddan (Sangat Baik)</td>
            <td class="text-center"> >= 70 </td>
          </tr>
          <tr>
            <td>Jayyid (Baik)</td>
            <td class="text-center"> >= 60 </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


  <div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">
    <strong>Catatan Wali Kelas:</strong><br>
    <p style="font-style: italic; margin-top:5px;">
      {{ $reportCard->notes ?? 'Tetap semangat dan tingkatkan prestasimu.' }}</p>
  </div>

  {{-- KEPUTUSAN (HANYA MUNCUL DI SEMESTER GENAP) --}}
  @if ($classroom->academicYear->semester == 'Genap')
    <div style="border: 1px solid #000; padding: 10px; margin-bottom: 10px;">
      <strong>Keputusan:</strong><br>
      Berdasarkan hasil pencapaian kompetensi, peserta didik ditetapkan:<br>
      <h3 class="text-center" style="margin: 10px 0;">
        @if ($reportCard->status == 'Lulus' || $reportCard->status == 'Tidak Lulus')
          {{ strtoupper($reportCard->status) }}
        @else
          {{ strtoupper($reportCard->status) }} KE KELAS {{ $classroom->level->next_level ?? '...' }}
        @endif
      </h3>
    </div>
  @endif

  <div class="signature-section">
    <table class="signature-table">
      {{-- Baris 1: Orang Tua & Wali Kelas --}}
      <tr>
        <td width="40%">
          Mengetahui,<br>
          Orang Tua / Wali
          <div class="signature-name">( ..................................... )</div>
        </td>
        <td width="20%"></td> {{-- Spasi Tengah --}}
        <td width="40%">
          {{-- Gabungkan Kota dan Tanggal --}}
          {{ $reportCity }}, {{ \Carbon\Carbon::parse($reportDate)->locale('id')->translatedFormat('d F Y') }}<br>
          Wali Kelas
          <div class="signature-name">{{ $classroom->homeroom_teacher ?? '..........................' }}</div>
          {{-- ... --}}
        </td>
      </tr>

      {{-- Baris 2: Jarak --}}
      <tr>
        <td colspan="3" style="height: 20px;"></td>
      </tr>

      {{-- Baris 3: Kepala Sekolah (Tengah) --}}
      <tr>
        <td colspan="3">
          Mengetahui,<br>
          Kepala Sekolah
          <div class="signature-name">{{ $headmaster }}</div>
          <div>NIP/NIY. {{ $headmasterNip ?? '-' }}</div>
        </td>
      </tr>
    </table>
  </div>

</body>

</html>
