<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
  <meta charset="UTF-8">
  <title>Rapor Tahfizh - {{ $student->name }}</title>
  @if (!$isPdf)
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400..700&display=swap" rel="stylesheet">
  @endif
  <style>
    @page {
      margin: 1cm 1.5cm;
      /* Atur margin halaman */
    }

    body {
      font-family: 'noto-naskh', sans-serif;
      /* Sesuai config controller */
      font-size: 12pt;
      direction: rtl;
      text-align: right;
      line-height: 1.3;
    }

    /* HELPER */
    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .font-bold {
      font-weight: bold;
    }

    .w-100 {
      width: 100%;
    }

    .collapse {
      border-collapse: collapse;
    }

    .mb-10 {
      margin-bottom: 10px;
    }

    /* HEADER DENGAN TABEL (Lebih Rapi di mPDF) */
    .header-table {
      width: 100%;
      border-bottom: 3px double #000;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }

    .header-title h1 {
      font-size: 18pt;
      margin: 0;
      font-weight: bold;
    }

    .header-title p {
      margin: 0;
      font-size: 10pt;
    }

    /* TABEL DATA SISWA */
    .identity-table td {
      padding: 3px 0;
      vertical-align: top;
    }

    /* TABEL NILAI (Gunakan Table layout instead of float) */
    .layout-table {
      width: 100%;
      vertical-align: top;
      border-collapse: collapse; /* Menghilangkan jarak antar sel container */
    }

    .layout-table td {
      padding: 0; /* Menghapus padding default container agar tabel dalam full width */
    }

    .score-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12pt;
    }

    .score-table th,
    .score-table td {
      border: 1px solid #000;
      padding: 10px;
      text-align: center;
    }

    .score-table th {
      background-color: #f0f0f0;
    }

    /*.score-table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }*/


    .page-break {
      page-break-after: always;
    }
  </style>
  {{-- /* WATERMARK --}}
  @if (!$isPdf)
    <style>
      .watermark {
        position: fixed;
        top: 35%;
        left: 25%;
        width: 50%;
        opacity: 0.1;
        z-index: -1000;
      }

      body {
        font-family: 'Noto Naskh Arabic', serif;
        font-size: 12pt;
        line-height: 1.3;
        direction: rtl;
        text-align: right;
      }
    </style>
  @endif
</head>

<body>

  @php
    $arabicNumbers = [
        '0' => '٠',
        '1' => '١',
        '2' => '٢',
        '3' => '٣',
        '4' => '٤',
        '5' => '٥',
        '6' => '٦',
        '7' => '٧',
        '8' => '٨',
        '9' => '٩',
    ];

    // Helper function angka arab
    function toArabic($number, $map)
    {
        if (is_null($number)) {
            return '-';
        }
        return str_replace(array_keys($map), array_values($map), $number);
    }

    $logoDayah = $isPdf ? public_path('assets/images/logo_dayah.png') : asset('assets/images/logo_dayah.png');
    $logoKemenag = $isPdf ? public_path('assets/images/logo_kemenag.png') : asset('assets/images/logo_kemenag.png');
  @endphp

  @if (!$isPdf)
    <!-- Watermark View-->
    <img src="{{ $logoDayah }}" class="watermark">
  @endif

  <table class="header-table">
    <tr>
      <td width="15%" class="text-center">
        <img src="{{ $logoKemenag }}" style="height: 80px;">
      </td>
      <td width="70%" class="text-center header-title">
        <h1>معهد تعليم القرآن </h1>
        <h1>عثمان بن عفان لوكسيماوي</h1>
        {{-- <p>شارع لاين بيبا، قرية الوي ليم، لوكسوماوي</p> --}}
      </td>
      <td width="15%" class="text-center">
        <img src="{{ $logoDayah }}" style="height: 80px;">
      </td>
    </tr>
  </table>

  <table class="identity-table w-100 mb-10">
    <tr>
      <td width="15%">اسم الطالب</td>
      <td width="2%">:</td>
      <td width="40%"><strong>{{ Str::title($student->name) }}</strong></td>
      <td width="15%">العام الدراسي</td>
      <td width="2%">:</td>
      <td width="26%">{{ toArabic($activeYear->name, $arabicNumbers) }}</td>
    </tr>
    <tr>
      <td>رقم القيد</td>
      <td>:</td>
      <td>{{ toArabic($student->nis, $arabicNumbers) }}</td>
      <td>الفصل الدراسي</td>
      <td>:</td>
      <td>{{ $activeYear->semester == 'Ganjil' ? 'الأوّل' : ($activeYear->semester == 'Genap' ? 'الثاني' : $activeYear->semester) }}</td>
    </tr>
  </table>

  <div style="background-color: #ddd; padding: 5px; border: 1px solid #000; margin-bottom: 10px; font-weight: bold;">
    أ. الحفظ (Tahfizh)
  </div>

  @php
    $scoresMap = [];
    if (!empty($report->juz_scores)) {
        foreach ($report->juz_scores as $item) {
            $scoresMap[$item['juz']] = $item['score'];
        }
    }
  @endphp

  <table class="layout-table">
    <tr>
      <td width="49%" style="vertical-align: top;">
        <table class="score-table">
          <thead>
            <tr>
              <th width="30%">الجزء</th>
              <th width="30%">الدرجة</th>
              <th width="40%">التقدير</th>
            </tr>
          </thead>
          <tbody>
            @for ($i = 1; $i <= 15; $i++)
              @php
                $score = $scoresMap[$i] ?? null;
                $predikat = $score ? \App\Models\TahfizhReport::getPredikat($score) : '-';
              @endphp
              <tr>
                <td>الجزء {{ toArabic($i, $arabicNumbers) }}</td>
                <td>{{ toArabic($score, $arabicNumbers) }}</td>
                <td>{{ $predikat }}</td>
              </tr>
            @endfor
          </tbody>
        </table>
      </td>

      <td width="2%"></td>

      <td width="49%" style="vertical-align: top;">
        <table class="score-table">
          <thead>
            <tr>
              <th width="30%">الجزء</th>
              <th width="30%">الدرجة</th>
              <th width="40%">التقدير</th>
            </tr>
          </thead>
          <tbody>
            @for ($i = 16; $i <= 30; $i++)
              @php
                $score = $scoresMap[$i] ?? null;
                $predikat = $score ? \App\Models\TahfizhReport::getPredikat($score) : '-';
              @endphp
              <tr>
                <td>الجزء {{ toArabic($i, $arabicNumbers) }}</td>
                <td>{{ toArabic($score, $arabicNumbers) }}</td>
                <td>{{ $predikat }}</td>
              </tr>
            @endfor
          </tbody>
        </table>
      </td>
    </tr>
  </table>

  <table class="score-table" style="margin-top: 5px;">
    <tr style="background-color: #fafafa;">
      <td class="" style=""><strong>مجموع الحفظ (Total Hafalan)</strong></td>
      <td width="30%"><strong>{{ toArabic($report->total_hafalan, $arabicNumbers) }} أجزاء</strong></td>
    </tr>
    <tr style="background-color: #fafafa;">
      <td class="" style="">الاختبار التحريري (Ujian Tulis)</td>
      <td width="30%">
        {{ toArabic($report->score_tahriri, $arabicNumbers) }}
        ({{ \App\Models\TahfizhReport::getPredikat($report->score_tahriri) }})
      </td>
    </tr>
  </table>

  @if($isPdf)
    <pagebreak />
  @else
    <div class="page-break"></div>
  @endif
  
  <div style="text-align: left; font-size: 9pt; font-style: italic; margin-bottom: 20px;">
    {{ $student->name }} (الصفحة ٢)
  </div>

  <div style="background-color: #ddd; padding: 5px; border: 1px solid #000; margin-bottom: 10px; font-weight: bold;">
    ب. جودة القراءة (Tahsin)
  </div>

  <table class="score-table mb-10">
    <thead>
      <tr>
        <th width="50%">جوانب التقييم</th>
        <th width="20%">الدرجة</th>
        <th width="30%">التقدير</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-right" style="padding-right:10px;">مخارج الحروف</td>
        <td>{{ toArabic($report->score_makhraj, $arabicNumbers) }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_makhraj) }}</td>
      </tr>
      <tr>
        <td class="text-right" style="padding-right:10px;">الغنة</td>
        <td>{{ toArabic($report->score_ghunnah, $arabicNumbers) }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_ghunnah) }}</td>
      </tr>
      <tr>
        <td class="text-right" style="padding-right:10px;">المد</td>
        <td>{{ toArabic($report->score_mad, $arabicNumbers) }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_mad) }}</td>
      </tr>
      <tr>
        <td class="text-right" style="padding-right:10px;">الفصاحة</td>
        <td>{{ toArabic($report->score_fasohah, $arabicNumbers) }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_fasohah) }}</td>
      </tr>
      <tr>
        <td class="text-right" style="padding-right:10px;">الطلاقة</td>
        <td>{{ toArabic($report->score_kelancaran, $arabicNumbers) }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_kelancaran) }}</td>
      </tr>
    </tbody>
  </table>

  <table class="score-table mb-10">
    <tr>
      <th width="50%">ج. ملاحظات للطالب</th>
      <th width="50%">د. ملاحظات لأولياء الأمور</th>
    </tr>
    <tr>
      <td style="height: 80px; vertical-align: top; text-align: right; font-style: italic;">
        {{ $report->note_student ?? '-' }}
      </td>
      <td style="height: 80px; vertical-align: top; text-align: right; font-style: italic;">
        {{ $report->note_parent ?? '-' }}
      </td>
    </tr>
  </table>

  <div style="width: 40%;">
    <table class="score-table">
      <thead>
        <tr>
          <th colspan="2">هـ. الحضور</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-right" style="padding-right:10px;">مريض</td>
          <td>{{ toArabic($report->sick ?? 0, $arabicNumbers) }} أيام</td>
        </tr>
        <tr>
          <td class="text-right" style="padding-right:10px;">إذن</td>
          <td>{{ toArabic($report->permission ?? 0, $arabicNumbers) }} أيام</td>
        </tr>
        <tr>
          <td class="text-right" style="padding-right:10px;">غائب</td>
          <td>{{ toArabic($report->alpha ?? 0, $arabicNumbers) }} أيام</td>
        </tr>
      </tbody>
    </table>
  </div>

  <table class="w-100" style="margin-top: 30px;">
    <tr>
      <td width="33%" class="text-center">
        <strong>{{ $city }}، {{ toArabic($date, $arabicNumbers) }}</strong><br>
        مشرف الحلقة<br>
        <br><br><br><br>
        <strong>{{ $report->teacher->name ?? '...........................' }}</strong>
      </td>
      <td width="33%"></td>
      <td width="33%" class="text-center">
        ولي الأمر<br>
        <br><br><br><br>
        ( ..................................... )
      </td>
    </tr>
  </table>

</body>

</html>
