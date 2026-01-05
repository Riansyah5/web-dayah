<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Pindah</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; margin: 0; padding: 0; }
        
        /* KOP SURAT */
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 14pt; text-transform: uppercase; font-weight: bold; }
        .header h3 { margin: 0; font-size: 12pt; font-weight: bold; }
        .header p { margin: 0; font-size: 10pt; font-style: italic; }

        /* Detail Surat */
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { vertical-align: top; }

        /* Isi Surat */
        .content { margin-bottom: 20px; text-align: justify; }
        .indent { padding-left: 40px; }

        /* Tabel Data Siswa */
        .data-table { width: 100%; margin: 10px 0 20px 20px; }
        .data-table td { vertical-align: top; padding: 2px 0; }
        .label-col { width: 160px; }
        .sep-col { width: 20px; }

        /* Tanda Tangan */
        .signature { margin-top: 50px; float: right; width: 40%; text-align: center; }
        .signature-name { margin-top: 70px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="header">
        <h2>YAYASAN PESANTREN AL-HIDAYAH</h2>
        <h3>MADRASAH TSANAWIYAH (MTs) AL-HIDAYAH</h3>
        <p>Jl. Raya Pesantren No. 99, Kota Santri, Indonesia | Telp: (021) 123456</p>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <u style="font-weight: bold; font-size: 14pt;">SURAT KETERANGAN PINDAH SEKOLAH</u><br>
        Nomor: {{ $student->exitDetail->sk_number ?? '......./MTs.AH/...../20...' }}
    </div>

    <div class="content">
        Yang bertanda tangan di bawah ini Kepala Madrasah Tsanawiyah Al-Hidayah Kota Santri, menerangkan bahwa:
    </div>

    <table class="data-table">
        <tr>
            <td class="label-col">Nama Peserta Didik</td>
            <td class="sep-col">:</td>
            <td style="font-weight: bold;">{{ strtoupper($student->name) }}</td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td>{{ $student->nis }} / {{ $student->nisn ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Kelas Terakhir</td>
            <td>:</td>
            <td>{{ $lastClass->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Orang Tua</td>
            <td>:</td>
            <td>{{ $student->father_name ?? $student->mother_name ?? '-' }}</td>
        </tr>
    </table>

    <div class="content">
        Telah mengajukan permohonan pindah sekolah ke:
    </div>

    <table class="data-table" style="font-weight: bold;">
        <tr>
            <td class="label-col">Sekolah Tujuan</td>
            <td class="sep-col">:</td>
            <td>{{ $student->exitDetail->destination ?? '........................................' }}</td>
        </tr>
        <tr>
            <td>Alasan Pindah</td>
            <td>:</td>
            <td>{{ $student->exitDetail->reason ?? '-' }}</td>
        </tr>
    </table>

    <div class="content">
        Bersama ini kami sertakan Buku Laporan Pendidikan (Rapor) yang bersangkutan untuk dipergunakan sebagaimana mestinya.
    </div>

    <div class="content">
        Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagai syarat penerimaan di sekolah yang baru. Terima kasih.
    </div>

    <div class="signature">
        {{ $city }}, {{ \Carbon\Carbon::parse($student->exitDetail->exit_date)->translatedFormat('d F Y') }}<br>
        Kepala Madrasah,
        
        <div class="signature-name">{{ $headmaster }}</div>
        <div>NIP/NIY. {{ $headmasterNip }}</div>
    </div>

</body>
</html>