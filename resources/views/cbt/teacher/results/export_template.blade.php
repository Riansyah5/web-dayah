<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai CBT</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header-kop { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header-kop h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header-kop h4 { margin: 5px 0; font-size: 14px; }
        .info-table { width: 100%; margin-bottom: 20px; font-weight: bold; }
        .info-table td { padding: 3px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px; }
        .data-table th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .class-title { background-color: #e9ecef; font-weight: bold; padding: 8px; border: 1px solid #000; margin-top: 15px; }
    </style>
</head>
<body>

    <div class="header-kop">
        <h2>DAYAH MATAQU UTSMAN BIN AFFAN</h2>
        <h4>Laporan Hasil Ujian Berbasis Komputer (CBT)</h4>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Ujian</td>
            <td width="2%">:</td>
            <td width="33%">{{ $exam->name }}</td>
            <td width="15%">Mata Pelajaran</td>
            <td width="2%">:</td>
            <td width="33%">{{ $exam->questionBank->subject_name }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($exam->start_time)->locale('id')->translatedFormat('d F Y') }}</td>
            <td>Guru Pengampu</td>
            <td>:</td>
            <td>{{ $exam->questionBank->teacher->name }}</td>
        </tr>
    </table>

    @foreach($groupedExams as $className => $students)
        <div class="class-title">KELAS: {{ strtoupper($className) }}</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Username CBT</th>
                    <th width="45%">Nama Santri</th>
                    <th width="15%">Status</th>
                    <th width="15%">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $se)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $se->cbtAccount->username }}</td>
                    <td>{{ $se->cbtAccount->student->name }}</td>
                    <td class="text-center">{{ $se->status == 'finished' ? 'Selesai' : 'Mengerjakan' }}</td>
                    <td class="text-center"><strong>{{ round($se->score) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <table width="100%" style="margin-top: 40px;">
        <tr>
            <td width="70%"></td>
            <td width="30%" class="text-center">
                Mengetahui,<br>
                Guru Pengampu<br><br><br><br>
                <strong>{{ $exam->questionBank->teacher->name }}</strong>
            </td>
        </tr>
    </table>

</body>
</html>