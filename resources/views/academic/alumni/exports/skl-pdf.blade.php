<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Lulus</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; }
        
        /* KOP SURAT */
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 14pt; text-transform: uppercase; font-weight: bold; }
        .header h3 { margin: 0; font-size: 12pt; font-weight: bold; }
        .header p { margin: 0; font-size: 10pt; font-style: italic; }

        /* Judul Surat */
        .title-section { text-align: center; margin-bottom: 30px; }
        .surat-title { font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        
        /* Isi */
        .content { margin-bottom: 15px; text-align: justify; }
        
        /* Tabel Biodata */
        .data-table { width: 100%; margin: 10px 0 20px 40px; }
        .data-table td { vertical-align: top; padding: 2px 0; }
        .label-col { width: 180px; }
        .sep-col { width: 20px; }

        /* Kotak Keputusan LULUS */
        .decision-box {
            text-align: center;
            margin: 30px auto;
            padding: 10px;
            width: 80%;
        }
        .lulus-text {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* Tanda Tangan */
        .signature { margin-top: 50px; float: right; width: 40%; text-align: center; }
        .signature-name { margin-top: 70px; font-weight: bold; text-decoration: underline; }
        
        /* Foto (Opsional) */
        .photo-area {
            position: absolute;
            bottom: 160px;
            left: 50px;
            width: 3cm;
            height: 4cm;
            border: 1px solid #000;
            text-align: center;
            line-height: 4cm;
            font-size: 10pt;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>YAYASAN PESANTREN AL-HIDAYAH</h2>
        <h3>MADRASAH TSANAWIYAH (MTs) AL-HIDAYAH</h3>
        <p>Jl. Raya Pesantren No. 99, Kota Santri, Indonesia | Telp: (021) 123456</p>
    </div>

    <div class="title-section">
        <div class="surat-title">SURAT KETERANGAN LULUS</div>
        <div>Nomor: {{ $student->exitDetail->sk_number ?? '......./SKL/MTs/AH/20...' }}</div>
    </div>

    <div class="content">
        Yang bertanda tangan di bawah ini, Kepala Madrasah Tsanawiyah Al-Hidayah Kota Santri, menerangkan bahwa:
    </div>

    <table class="data-table">
        <tr>
            <td class="label-col">Nama Peserta Didik</td>
            <td class="sep-col">:</td>
            <td style="font-weight: bold;">{{ strtoupper($student->name) }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>
                {{ $student->birth_place }}, {{ \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td>{{ $student->nis }} / {{ $student->nisn ?? '-' }}</td>
        </tr>
        <tr>
            <td>Asal Kelas</td>
            <td>:</td>
            <td>{{ $lastClass->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="content">
        Berdasarkan hasil Rapat Pleno Dewan Guru tentang Kelulusan Peserta Didik Tahun Pelajaran {{ $student->exitDetail->exit_year ?? date('Y') }}/{{ ($student->exitDetail->exit_year ?? date('Y')) + 1 }}, maka peserta didik tersebut dinyatakan:
    </div>

    <div class="decision-box">
        <span class="lulus-text">L U L U S</span>
    </div>

    <div class="content">
        Surat keterangan ini bersifat sementara sampai ijazah asli diterbitkan. Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    <div class="photo-area">
        Pas Foto 3x4
    </div>

    <div class="signature">
        {{ $city }}, {{ \Carbon\Carbon::parse($student->exitDetail->exit_date)->translatedFormat('d F Y') }}<br>
        Kepala Madrasah,
        
        <div class="signature-name">{{ $headmaster }}</div>
        <div>NIP/NIY. {{ $headmasterNip }}</div>
    </div>

</body>
</html>