<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Jadwal Pelajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .table-sm td, .table-sm th { padding: 4px; vertical-align: middle; }
        
        /* Styling Kotak Jadwal */
        .sched-box {
            border: 1px solid #dee2e6;
            padding: 2px;
            border-radius: 4px;
            background-color: #f8f9fa;
            text-align: center;
        }
        .sched-subject { font-weight: bold; font-size: 11px; display: block; }
        .sched-teacher { font-size: 10px; color: #555; display: block; }

        /* CSS KHUSUS CETAK */
        @media print {
            @page { size: landscape; margin: 10mm; } /* Kertas Landscape */
            .no-print { display: none !important; } /* Sembunyikan tombol cetak */
            .page-break { page-break-after: always; } /* Ganti halaman tiap hari */
            body { -webkit-print-color-adjust: exact; } /* Agar background warna ikut tercetak */
        }
    </style>
</head>
<body class="bg-white">

    <div class="container-fluid py-3 no-print bg-light border-bottom mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Pratinjau Cetak Master Jadwal</h5>
                <small class="text-muted">Gunakan kertas A4 Landscape untuk hasil terbaik.</small>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-primary fw-bold">
                    <i class="bi bi-printer"></i> Cetak Sekarang
                </button>
                <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
            </div>
        </div>
    </div>

    @foreach($days as $dayNum => $dayName)
    
    <div class="container-fluid mb-4 page-break">
        
        <div class="text-center mb-3">
            <h4 class="fw-bold mb-0">JADWAL PELAJARAN {{ request('stage') ? '- ' . strtoupper(request('stage')) : '' }} - HARI {{ strtoupper($dayName) }}</h4>
            <p class="mb-0">Tahun Ajaran: {{ $activeYear->name }}</p>
        </div>

        <table class="table table-bordered table-sm w-100 border-dark">
            <thead class="table-light text-center border-dark">
                <tr>
                    <th style="width: 80px;">Jam</th>
                    @foreach($classrooms as $class)
                        <th>{{ $class->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($timeSlots as $time)
                    @php 
                        // Cek apakah ada jadwal APAPUN di jam ini pada hari ini (untuk efisiensi baris)
                        // Kalau semua kelas kosong di jam 05:00 pagi, row tidak perlu diprint
                        $hasActivity = false;
                        foreach($classrooms as $c) {
                            if(isset($matrix[$dayNum][$time][$c->id])) $hasActivity = true;
                        }
                    @endphp

                    @if($hasActivity)
                    <tr>
                        <td class="text-center fw-bold bg-light">
                            {{ \Carbon\Carbon::parse($time)->format('H:i') }}
                        </td>
                        
                        @foreach($classrooms as $class)
                            <td>
                                @if(isset($matrix[$dayNum][$time][$class->id]))
                                    @php $item = $matrix[$dayNum][$time][$class->id]; @endphp
                                    <div class="sched-box">
                                        <span class="sched-subject">{{ $item->subject->name }}</span>
                                        <span class="sched-teacher">{{ Str::limit($item->teacher->name, 10, '') }}</span>
                                    </div>
                                @else
                                    <div class="text-center text-muted text-opacity-25 py-2">-</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        
        <div class="row mt-4">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-5">Mengetahui,<br>Kepala Sekolah</p>
                <p class="fw-bold mt-5">_______________________</p>
            </div>
        </div>

    </div>
    @endforeach

</body>
</html>