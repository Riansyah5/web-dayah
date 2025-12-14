<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perizinan Santri</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 0; font-size: 14px; }
        
        .meta-info { width: 100%; margin-bottom: 20px; }
        .meta-info td { padding: 3px; vertical-align: top; }
        .label { font-weight: bold; width: 130px; }

        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-data th, .table-data td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        .table-data th { background-color: #f0f0f0; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .table-data tr:nth-child(even) { background-color: #fafafa; }
        
        .group-header { background-color: #e0e7ff !important; font-weight: bold; color: #333; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; display: inline-block; }
        .badge-late { color: #dc2626; border: 1px solid #dc2626; }
        .badge-returned { color: #16a34a; border: 1px solid #16a34a; }
        .badge-active { color: #d97706; border: 1px solid #d97706; }

        .footer { margin-top: 40px; text-align: right; page-break-inside: avoid; }
        .signature-box { display: inline-block; text-align: center; width: 200px; }
        .signature-line { border-bottom: 1px solid #333; margin-top: 60px; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Riwayat Perizinan</h2>
        <p>Ma'had Ta'limul Qur'an Utsman bin Affan</p>
    </div>

    <table class="meta-info">
        <tr>
            <td class="label">Nama Santri</td>
            <td>: {{ $student->name }}</td>
            <td class="label">Periode Laporan</td>
            <td>: 
                @if($period == 'current_month')
                    {{ now()->translatedFormat('F Y') }}
                @elseif($period == 'custom' && $startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                @else
                    Seluruh Riwayat
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">NIS / Kelas</td>
            <td>: {{ $student->nis }} / {{ $student->class_group ?? '-' }}</td>
            <td class="label">Total Izin</td>
            <td>: {{ $permissions->flatten()->count() }} Kali</td>
        </tr>
        <tr>
            <td class="label">Asrama</td>
            <td>: {{ $student->dormitory ?? '-' }} - {{ $student->room ?? '-' }}</td>
            <td class="label">Dicetak Tanggal</td>
            <td>: {{ now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th width="20%">Waktu Keluar</th>
                <th width="20%">Rencana Kembali</th>
                <th width="15%">Jenis</th>
                <th width="25%">Keperluan</th>
                <th width="20%">Status / Realisasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permissions as $month => $items)
                <tr class="group-header">
                    <td colspan="5">{{ $month }}</td>
                </tr>
                @foreach($items as $perm)
                <tr>
                    <td>{{ $perm->start_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $perm->end_date->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($perm->type) }}</td>
                    <td>{{ $perm->reason }}</td>
                    <td>
                        @if($perm->returned_at)
                            <div>{{ $perm->returned_at->format('d/m/Y H:i') }}</div>
                            @if($perm->status == 'late')
                                <span class="badge badge-late">Terlambat</span>
                            @else
                                <span class="badge badge-returned">Tepat Waktu</span>
                            @endif
                        @else
                            <span class="badge badge-active">Belum Kembali</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data perizinan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Mengetahui,<br>Bagian Perizinan</p>
            <div class="signature-line"></div>
            <p>( .................................... )</p>
        </div>
    </div>
</body>
</html>