<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Ujian CBT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        /* Grid untuk mengatur kartu agar berjajar rapi */
        .card-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 Kartu per baris (Kiri-Kanan) */
            gap: 20px;
        }
        /* Desain Kartu Ujian */
        .exam-card {
            border: 2px dashed #000; /* Garis gunting */
            padding: 15px;
            border-radius: 8px;
            page-break-inside: avoid; /* Mencegah kartu terpotong beda halaman saat diprint */
            background-color: #fcfcfc;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .header h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header h4 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
        }
        .content-table {
            width: 100%;
            font-size: 14px;
        }
        .content-table td {
            padding: 4px 0;
        }
        .content-table .label {
            font-weight: bold;
            width: 35%;
        }
        .content-table .colon {
            width: 5%;
        }
        .credentials {
            margin-top: 15px;
            background-color: #e9ecef;
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .credentials span {
            display: block;
            font-size: 18px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 11px;
            font-style: italic;
        }
        
        /* Hilangkan elemen yang tidak perlu saat diprint */
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Print Kartu Ujian</button>
        <p>Gunakan setelan kertas <strong>A4</strong> dan hapus margin di setting printer browser Anda.</p>
    </div>

    <div class="card-container">
        @foreach($studentsByClass as $className => $students)
            @foreach($students as $student)
            <div class="exam-card">
                <div class="header">
                    <h3>DAYAH MATAQU UTSMAN BIN AFFAN</h3>
                    <h4>Kartu Login Peserta Ujian (CBT)</h4>
                </div>
                
                <table class="content-table">
                    <tr>
                        <td class="label">Nama Santri</td>
                        <td class="colon">:</td>
                        <td><strong>{{ $student->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Kelas / Halaqah</td>
                        <td class="colon">:</td>
                        <td>{{ $className !== 'Tanpa Kelas' ? $className : '.................' }}</td>
                    </tr>
                </table>

                <div class="credentials">
                    <div>Username / No. Peserta:</div>
                    <span>{{ $student->cbtAccount->username }}</span>
                    <div style="margin-top: 10px;">PIN Ujian:</div>
                    <span>{{ $student->cbtAccount->raw_pin }}</span>
                </div>

                <div class="footer">
                    *Simpan kartu ini baik-baik. PIN bersifat rahasia.<br>
                    Link Ujian: <strong>{{ url('/cbt/login') }}</strong>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>

</body>
</html>