<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Kelas {{ $classroom->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        .meta-info { width: 100%; margin-bottom: 15px; font-size: 10pt; }
        .meta-info td { padding: 2px; vertical-align: top; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #000; padding: 5px; font-size: 10pt; }
        table.data th { background-color: #f0f0f0; text-align: center; }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        .footer { margin-top: 40px; width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR HADIR SISWA</h2>
        <p>Tahun Ajaran: {{ $classroom->academicYear->name }} ({{ $classroom->academicYear->semester }})</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Kelas</strong></td>
            <td width="35%">: {{ $classroom->name }}</td>
            <td width="15%"><strong>Wali Kelas</strong></td>
            <td width="35%">: {{ $classroom->homeroom_teacher ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tingkat</strong></td>
            <td>: {{ $classroom->level->name }}</td>
            <td><strong>Jumlah Siswa</strong></td>
            <td>: {{ $classroom->students->count() }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIS</th>
                <th width="30%">Nama Siswa</th>
                <th width="5%">L/P</th>
                {{-- Kolom kosong untuk paraf/kehadiran (misal 16 pertemuan) --}}
                @for($i=1; $i<=16; $i++)
                    <th></th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($classroom->students as $index => $student)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $student->nis }}</td>
                <td class="text-left">{{ $student->name }}</td>
                <td class="text-center">{{ $student->gender }}</td>
                @for($i=1; $i<=16; $i++)
                    <td></td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>...................., {{ date('d F Y') }}</p>
            <p>Wali Kelas,</p>
            <br><br><br><br>
            <p><strong>{{ $classroom->homeroom_teacher ?? '(................................)' }}</strong></p>
        </div>
    </div>
</body>
</html>