<table>
    <tr>
        <td colspan="5" style="text-align: center; font-size: 16px; font-weight: bold;">
            DAYAH MATAQU UTSMAN BIN AFFAN
        </td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: center; font-size: 14px; font-weight: bold;">
            Laporan Hasil Ujian Berbasis Komputer (CBT)
        </td>
    </tr>
    <tr>
        <td colspan="5"></td> </tr>

    <tr>
        <td style="font-weight: bold;">Nama Ujian</td>
        <td colspan="2">: {{ $exam->name }}</td>
        <td style="font-weight: bold;">Mata Pelajaran</td>
        <td>: {{ $exam->questionBank->subject_name }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Tanggal</td>
        <td colspan="2">: {{ \Carbon\Carbon::parse($exam->start_time)->translatedFormat('d F Y') }}</td>
        <td style="font-weight: bold;">Guru Pengampu</td>
        <td>: {{ $exam->questionBank->teacher->name }}</td>
    </tr>
    <tr>
        <td colspan="5"></td> </tr>

    @foreach($groupedExams as $className => $students)
        <tr>
            <td colspan="5" style="background-color: #e9ecef; font-weight: bold; border: 1px solid #000000;">
                KELAS: {{ strtoupper($className) }}
            </td>
        </tr>
        <tr>
            <td style="background-color: #f2f2f2; font-weight: bold; text-align: center; border: 1px solid #000000;">No</td>
            <td style="background-color: #f2f2f2; font-weight: bold; text-align: center; border: 1px solid #000000;">Username CBT</td>
            <td style="background-color: #f2f2f2; font-weight: bold; text-align: center; border: 1px solid #000000;">Nama Santri</td>
            <td style="background-color: #f2f2f2; font-weight: bold; text-align: center; border: 1px solid #000000;">Status</td>
            <td style="background-color: #f2f2f2; font-weight: bold; text-align: center; border: 1px solid #000000;">Nilai Akhir</td>
        </tr>
        
        @foreach($students as $index => $se)
        <tr>
            <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $se->cbtAccount->username }}</td>
            <td style="border: 1px solid #000000;">{{ $se->cbtAccount->student->name }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $se->status == 'finished' ? 'Selesai' : 'Mengerjakan' }}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{ round($se->score) }}</td>
        </tr>
        @endforeach
        
        <tr>
            <td colspan="5"></td> </tr>
    @endforeach

    <tr>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="2" style="text-align: center;">Mengetahui,</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="2" style="text-align: center;">Guru Pengampu</td>
    </tr>
    <tr>
        <td colspan="5"></td> </tr>
    <tr>
        <td colspan="5"></td> </tr>
    <tr>
        <td colspan="5"></td> </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="2" style="text-align: center; font-weight: bold;">
            {{ $exam->questionBank->teacher->name }}
        </td>
    </tr>
</table>