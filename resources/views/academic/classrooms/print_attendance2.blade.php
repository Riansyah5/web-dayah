<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Kedatangan Santri - {{ $classroom->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        
        .meta-info { width: 100%; margin-bottom: 15px; font-size: 10pt; }
        .meta-info td { padding: 2px; vertical-align: top; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #000; padding: 0px 5px; font-size: 10pt; }
        table.data th { background-color: #f0f0f0; text-align: center; text-transform: uppercase; }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* Gaya khusus untuk tanda tangan zig-zag */
        .signature-cell { width: 150px; height: 40px; vertical-align: top; position: relative; }
        .sig-number { font-size: 8pt; color: #555; text-align: left; }
        .sig-line { border-bottom: 1px dotted #000; width: 80%; margin-top: 15px; }

        .footer { margin-top: 30px; width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; }
        
        /* Clearfix untuk footer */
        .footer::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR HADIR KEDATANGAN SANTRI</h2>
        <p>Tahun Ajaran: {{ $classroom->academicYear->name }} ({{ $classroom->academicYear->semester }})</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Kelas</strong></td>
            <td width="35%">: {{ $classroom->name }}</td>
            <td width="15%"><strong>Hari / Tanggal</strong></td>
            <td width="35%">: ...................................</td>
        </tr>
        <tr>
            <td><strong>Wali Kelas</strong></td>
            <td>: {{ $classroom->homeroom_teacher ?? '-' }}</td>
            <td><strong>Jumlah Santri</strong></td>
            <td>: {{ $classroom->students->count() }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="7%">NIS</th>
                <th width="33%">Nama Santri</th>
                <th width="25%">Waktu Datang</th>
                <th width="30%">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classroom->students as $index => $student)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $student->nis }}</td>
                <td class="text-left">{{ $student->name }}</td>
                <td class="text-center">....... : .......</td>
                <td class="signature-cell">
                    @if(($index + 1) % 2 != 0)
                        {{-- Baris Ganjil: Tanda tangan di kiri --}}
                        <div class="sig-number">{{ $index + 1 }}.</div>
                    @else
                        {{-- Baris Genap: Tanda tangan di kanan/tengah --}}
                        <div class="sig-number" style="text-align: center;">{{ $index + 1 }}.</div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div class="footer">
        <div class="signature-box">
            <p>...................., {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
            <p>Wali Kelas,</p>
            <br><br><br><br>
            <p><strong>{{ $classroom->homeroom_teacher ?? '(................................)' }}</strong></p>
        </div>
    </div> --}}
</body>
</html>