<!DOCTYPE html>
<html>

<head>
  <title>Rapor Siswa</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }

    .table th,
    .table td {
      border: 1px solid #000;
      padding: 5px 8px;
    }

    .table th {
      background-color: #f0f0f0;
    }

    .no-border td {
      border: none;
      padding: 2px;
    }

    .text-center {
      text-align: center;
    }

    .text-bold {
      font-weight: bold;
    }

    .footer {
      margin-top: 30px;
    }
  </style>
</head>

<body>

  <div class="header">
    <h2 style="margin:0;">YAYASAN PESANTREN AL-HIDAYAH</h2>
    <h3 style="margin:5px 0;">LAPORAN HASIL BELAJAR SISWA</h3>
    <p style="margin:0;">Jl. Pesantren No. 123, Kota Santri</p>
  </div>

  <table class="table no-border" style="margin-bottom: 20px;">
    <tr>
      <td width="15%">Nama Siswa</td>
      <td width="35%">: <strong>{{ $student->name }}</strong></td>
      <td width="15%">Kelas</td>
      <td width="35%">: {{ $classroom->name }}</td>
    </tr>
    <tr>
      <td>NIS/NISN</td>
      <td>: {{ $student->nis }}</td>
      <td>Semester</td>
      <td>: {{ $classroom->academicYear->semester }} {{ $classroom->academicYear->name }}</td>
    </tr>
  </table>

  <h4 style="margin-bottom: 5px;">A. Nilai Akademik</h4>
  <table class="table">
    <thead>
      <tr>
        <th width="5%">No</th>
        <th>Mata Pelajaran</th>
        <th width="10%">KKM</th>
        <th width="10%">Nilai</th>
        <th width="10%">Predikat</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      @php
        $currentGroup = '';
        $no = 1;
      @endphp
      @foreach ($courses as $course)
        @if ($course->subject->group != $currentGroup)
          @php $currentGroup = $course->subject->group; @endphp
          <tr>
            <td colspan="6" class="text-bold" style="background:#eef;">Kelompok {{ $currentGroup }}</td>
          </tr>
        @endif

        @php
          $grade = $course->grades->first(); // Karena sudah difilter di controller
        @endphp

        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td>{{ $course->subject->name }}</td>
          <td class="text-center">{{ $course->kkm }}</td>
          <td class="text-center text-bold">{{ $grade->score_final ?? '-' }}</td>
          <td class="text-center">{{ $grade->grade_letter ?? '-' }}</td>
          <td>
            @php
              // 1. Ambil Silabus yang cocok dengan Kelas & Semester ini
              // Kita cari syllabus berdasarkan subject_id, level_id, dan semester
              $syllabus = \App\Models\Syllabus::where('subject_id', $course->subject_id)
                  ->where('level_id', $classroom->level_id)
                  ->where('semester', $classroom->academicYear->semester)
                  ->first();

              $topics = $syllabus->topics ?? 'materi pembelajaran'; // Default text jika silabus belum diisi
              $score = $grade->score_final ?? 0;
            @endphp

            {{-- 2. Generate Narasi Otomatis --}}
            @if ($score >= 90)
              Ananda <strong>sangat menguasai</strong> {{ $topics }}.
            @elseif($score >= $course->kkm)
              Ananda <strong>telah menguasai</strong> {{ $topics }}.
            @else
              Ananda <strong>perlu bimbingan</strong> dalam materi {{ $topics }}.
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h4 style="margin-bottom: 5px;">B. Ketidakhadiran</h4>
  <table class="table" style="width: 50%;">
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

  <div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">
    <strong>Catatan Wali Kelas:</strong><br>
    <p style="font-style: italic;">{{ $reportCard->notes ?? 'Tetap semangat dan tingkatkan prestasimu.' }}</p>
  </div>

  <div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">
    <strong>Keputusan:</strong><br>
    Berdasarkan hasil pencapaian kompetensi, peserta didik ditetapkan:<br>
    <h3 class="text-center">{{ strtoupper($reportCard->status ?? 'NAIK KELAS') }}</h3>
  </div>

  <table class="table no-border footer">
    <tr>
      <td width="33%" class="text-center">
        Orang Tua / Wali,<br><br><br><br>
        ( ..................................... )
      </td>
      <td width="33%" class="text-center">
        <br><br><br><br>
      </td>
      <td width="33%" class="text-center">
        Ditetapkan di: Kota Santri<br>
        Tanggal: {{ now()->format('d F Y') }}<br>
        Wali Kelas,<br><br><br><br>
        <strong>{{ $classroom->homeroom_teacher }}</strong>
      </td>
    </tr>
  </table>

</body>

</html>
