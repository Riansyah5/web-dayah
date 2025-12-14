<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin - {{ $permission->student->name }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 20px; color: #000; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .subtitle { font-size: 12px; }
        .content { margin-bottom: 30px; line-height: 1.6; }
        .table-info { width: 100%; margin-bottom: 15px; }
        .table-info td { vertical-align: top; padding: 3px 0; }
        .label { width: 120px; font-weight: bold; }
        .footer { display: flex; justify-content: space-between; margin-top: 50px; text-align: center; }
        .signature { margin-top: 60px; font-weight: bold; text-decoration: underline; }
        
        @media print {
            @page { size: A4 portrait; margin: 1cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <div class="title">Ma'had Ta'limul Qur'an Utsman bin Affan</div>
        <div class="subtitle">Desa Alue Lim, Kec. Blang Mangat, Kota Lhokseumawe</div>
    </div>

    <div style="text-align: center; font-weight: bold; margin-bottom: 20px; text-decoration: underline;">
        SURAT IZIN KELUAR PONDOK
    </div>

    <div class="content">
        <p>Diberikan izin kepada santri:</p>
        <table class="table-info">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $permission->student->name }}</td>
            </tr>
            <tr>
                <td class="label">NIS / Asrama</td>
                <td>: {{ $permission->student->nis }} / {{ $permission->student->room ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Keperluan</td>
                <td>: {{ $permission->reason }} ({{ ucfirst($permission->type) }})</td>
            </tr>
        </table>

        <p>Untuk keluar pondok pada waktu sebagai berikut:</p>
        <table class="table-info">
            <tr>
                <td class="label">Waktu Keluar</td>
                <td>: {{ $permission->start_date->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Batas Kembali</td>
                <td>: <strong>{{ $permission->end_date->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB</strong></td>
            </tr>
        </table>
        
        <p style="font-size: 11px; font-style: italic;">Catatan: Surat ini wajib dibawa dan diserahkan kembali ke pos keamanan saat kembali ke pondok.</p>
    </div>

    <div class="footer">
        <div>
            <p>Mengetahui,<br>Keamanan Pondok</p>
            <div class="signature">___________________</div>
        </div>
        <div>
            <p>{{ now()->locale('id')->translatedFormat('d F Y') }}<br>Pengurus Perizinan</p>
            <div class="signature">Admin Pondok</div>
        </div>
    </div>

    <button class="no-print" style="margin-top: 20px; padding: 10px 20px; cursor: pointer;" onclick="window.close()">Tutup Jendela</button>

</body>
</html>