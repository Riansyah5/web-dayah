<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pelanggaran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 4px; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content-table th, .content-table td { border: 1px solid #999; padding: 8px; text-align: left; }
        .content-table th { background-color: #eee; }
        .group-header { background-color: #dbeafe; font-weight: bold; }
        .sub-group-header { background-color: #f3f4f6; font-style: italic; }
        .text-danger { color: red; }
        .text-right { text-align: right; }
        .total-box { text-align: right; margin-top: 10px; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin:0">LAPORAN KEDISIPLINAN SANTRI</h2>
        <p style="margin:0">Pondok Pesantren Al-Hidayah</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="15%"><strong>Nama Santri</strong></td>
            <td>: {{ $student->name }}</td>
            <td width="15%"><strong>Periode</strong></td>
            <td>: 
                @if(request('start_date'))
                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                @else
                    Seluruh Riwayat
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>NIS</strong></td>
            <td>: {{ $student->nis }}</td>
            <td><strong>Total Poin</strong></td>
            <td>: {{ $student->violations->sum('points') }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="40%">Pelanggaran</th>
                <th width="15%">Kategori</th>
                <th width="20%">Hukuman</th>
                <th width="10%" class="text-right">Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($violations as $academic => $months)
                <tr class="group-header">
                    <td colspan="5">{{ $academic }}</td>
                </tr>
                
                @foreach($months as $month => $items)
                    <tr class="sub-group-header">
                        <td colspan="5">{{ $month }}</td>
                    </tr>

                    @foreach($items as $violation)
                    <tr>
                        <td>{{ $violation->violation_date->format('d/m/Y') }}</td>
                        <td>{{ $violation->description }}</td>
                        <td>{{ $violation->category }}</td>
                        <td>{{ $violation->punishment ?? '-' }}</td>
                        <td class="text-right text-danger">+{{ $violation->points }}</td>
                    </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Total Poin Periode Ini: {{ $violations->flatten()->sum('points') }}
    </div>

    <div style="margin-top: 50px; text-align: right; margin-right: 30px;">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Bagian Kesantrian</strong></p>
    </div>

</body>
</html>