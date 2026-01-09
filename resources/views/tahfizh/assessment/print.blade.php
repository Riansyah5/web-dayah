<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
  <meta charset="UTF-8">
  <title>Rapor Tahfizh - {{ $student->name }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400..700&display=swap" rel="stylesheet">
  <style>
    @page {
      margin: 1.5cm;
    }

    body {
      /*font-family: 'Times New Roman', serif;*/
      font-family: 'Noto Naskh Arabic', sans-serif;
      font-size: 11pt;
      direction: rtl;
      text-align: right;
      line-height: 1.4;
    }

    /* KOP SURAT */
    .header {
      text-align: center;
      border-bottom: 3px double #000;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }

    .header h1 {
      font-size: 18pt;
      margin: 0;
      font-weight: bold;
    }

    .header p {
      margin: 0;
      font-size: 11pt;
    }

    /* IDENTITAS */
    .identity-table {
      width: 100%;
      margin-bottom: 15px;
      font-size: 11pt;
    }

    .identity-table td {
      padding: 3px 0;
      vertical-align: top;
    }

    /* TABEL NILAI UTAMA (2 KOLOM) */
    .column-container {
      width: 100%;
      clear: both;
      overflow: hidden;
    }

    .column-left {
      width: 49%;
      float: left;
    }

    .column-right {
      width: 49%;
      float: right;
    }

    .score-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
      font-size: 10pt;
    }

    .score-table th,
    .score-table td {
      border: 1px solid #000;
      padding: 5px;
      text-align: center;
    }

    .score-table th {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    .text-right {
      text-align: right !important;
      padding-right: 10px !important;
    }

    /* TABEL KEHADIRAN & CATATAN */
    .box-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }

    .box-table td {
      padding: 10px;
      vertical-align: top;
      border: 1px solid #000;
    }

    .box-header {
      background-color: #f0f0f0;
      font-weight: bold;
      border-bottom: 1px solid #000;
      padding: 5px;
      margin-bottom: 5px;
      text-align: center;
    }

    /* TANDA TANGAN */
    .signature-table {
      width: 100%;
      margin-top: 20px;
      page-break-inside: avoid;
    }

    .signature-table td {
      text-align: center;
      vertical-align: top;
      width: 33%;
    }

    .sign-space {
      height: 60px;
    }

    .page-break {
      page-break-after: always;
    }

    .clear {
      clear: both;
    }
  </style>
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
  @endphp
  <div class="header">
    <h1>معهد تحفيظ القرآن الكريم</h1>
    <h1>عثمان بن عفان</h1>
    <p>شارع لاين بيبا، قرية الوي ليم، لوكسوماوي</p>
  </div>

  <div style="text-align: center; font-weight: bold; font-size: 15pt; margin-bottom: 15px; text-decoration: underline;">
    تقرير نتائج تعلم تحفيظ القرآن
  </div>

  <table class="identity-table">
    <tr>
      <td width="15%">اسم الطالب</td>
      <td width="2%">:</td>
      <td width="40%"><strong>{{ strtoupper($student->name) }}</strong></td>
      <td width="15%">العام الدراسي</td>
      <td width="2%">:</td>
      <td width="26%">{{ $activeYear->name }}</td>
    </tr>
    <tr>
      <td>رقم القيد</td>
      <td>:</td>
      <td>{{ $student->nis }}</td>
      <td>الفصل الدراسي</td>
      <td>:</td>
      <td>{{ $activeYear->semester }}</td>
    </tr>
  </table>

  <div style="background-color: #ddd; padding: 5px; font-weight: bold; border: 1px solid #000; margin-bottom: 10px;">
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

  <div class="column-container">
    {{-- KOLOM KANAN (Juz 1 - 15) --}}
    <div class="column-right">
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
              <td>الجزء {{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $i) }}</td>
              <td>{{ $score ? str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $score) : '-' }}
              </td>
              <td>{{ $predikat }}</td>
            </tr>
          @endfor
        </tbody>
      </table>
    </div>

    {{-- KOLOM KIRI (Juz 16 - 30) --}}
    <div class="column-left">
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
              <td>الجزء {{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $i) }}</td>
              <td>{{ $score ? str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $score) : '-' }}
              </td>
              <td>{{ $predikat }}</td>
            </tr>
          @endfor
        </tbody>
      </table>
    </div>
  </div>

  <table class="score-table" style="margin-top: 5px;">
    <tr style="background-color: #fafafa;">
      <td class="text-right"><strong>مجموع الحفظ (Total Hafalan)</strong></td>
      <td width="30%"><strong>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->total_hafalan ?? '-') }}</strong></td>
    </tr>
    <tr style="background-color: #fafafa;">
      <td class="text-right">الاختبار التحريري (Ujian Tulis)</td>
      <td width="30%">{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->score_tahriri ?? '-') }}
        ({{ \App\Models\TahfizhReport::getPredikat($report->score_tahriri) }})</td>
    </tr>
  </table>

  <div class="page-break"></div>

  <div style="text-align: left; font-size: 9pt; font-style: italic; margin-bottom: 20px;">
    {{ $student->name }} (الصفحة ٢)
  </div>

  <div style="background-color: #ddd; padding: 5px; font-weight: bold; border: 1px solid #000; margin-bottom: 10px;">
    ب. جودة القراءة (Tahsin)
  </div>

  <table class="score-table">
    <thead>
      <tr>
        <th width="50%">جوانب التقييم</th>
        <th width="20%">الدرجة</th>
        <th width="30%">التقدير</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-right">مخارج الحروف</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->score_makhraj ?? '-') }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_makhraj) }}</td>
      </tr>
      <tr>
        <td class="text-right">الغنة</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->score_ghunnah ?? '-') }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_ghunnah) }}</td>
      </tr>
      <tr>
        <td class="text-right">المد</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->score_mad ?? '-') }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_mad) }}</td>
      </tr>
      <tr>
        <td class="text-right">الفصاحة</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->score_fasohah ?? '-') }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_fasohah) }}</td>
      </tr>
      <tr>
        <td class="text-right">الطلاقة</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->score_kelancaran ?? '-') }}</td>
        <td>{{ \App\Models\TahfizhReport::getPredikat($report->score_kelancaran) }}</td>
      </tr>
    </tbody>
  </table>

  <br>

  <table class="box-table">
    <tr>
      <td width="50%">
        <div class="box-header">ج. ملاحظات للطالب</div>
        <div style="min-height: 80px; font-style: italic;">{{ $report->note_student ?? '-' }}</div>
      </td>
      <td width="50%">
        <div class="box-header">د. ملاحظات لأولياء الأمور</div>
        <div style="min-height: 80px; font-style: italic;">{{ $report->note_parent ?? '-' }}</div>
      </td>
    </tr>
  </table>

  <div style="width: 40%;">
    <div class="box-header" style="border: 1px solid #000;">هـ. الحضور</div>
    <table class="score-table">
      <tr>
        <td class="text-right">مريض</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->sick) ?? '-' }} أيام</td>
      </tr>
      <tr>
        <td class="text-right">إذن</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->permission) ?? '-' }} أيام</td>
      </tr>
      <tr>
        <td class="text-right">غائب</td>
        <td>{{ str_replace(array_keys($arabicNumbers), array_values($arabicNumbers), $report->alpha) ?? '-' }} أيام</td>
      </tr>
    </table>
  </div>

  <table class="signature-table">
    <tr>
      <td>
        {{ $city }}، {{ $date }}<br>
        مشرف الحلقة<br>
        <div class="sign-space"></div>
        <strong>{{ $report->teacher->name ?? '...........................' }}</strong>
      </td>
      <td>
        {{-- ولي الأمر<br>
        <div class="sign-space"></div>
        ( ..................................... ) --}}
      </td>
      <td>
        ولي الأمر<br>
        <div class="sign-space"></div>
        ( ..................................... )
      </td>
    </tr>
  </table>

</body>

</html>
