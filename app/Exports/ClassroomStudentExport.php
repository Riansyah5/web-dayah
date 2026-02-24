<?php

namespace App\Exports;

use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ClassroomStudentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $classroom;
    private $rowNumber = 0;

    public function __construct(Classroom $classroom)
    {
        $this->classroom = $classroom;
    }

    public function collection()
    {
        return $this->classroom->students;
    }

    public function headings(): array
    {
        return [
            ['DAFTAR SISWA KELAS ' . strtoupper($this->classroom->name)],
            ['Tahun Ajaran: ' . $this->classroom->academicYear->name . ' (' . $this->classroom->academicYear->semester . ')'],
            ['Wali Kelas: ' . ($this->classroom->homeroom_teacher ?? '-')],
            [], // Baris Kosong
            [
                'No',
                'NIS',
                'NISN',
                'Nama Lengkap',
                'L/P',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Nama Ayah',
                'Nama Ibu',
                'Alamat (Desa)'
            ]
        ];
    }

    public function map($student): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $student->nis,
            $student->nisn,
            $student->name,
            $student->gender,
            $student->birth_place,
            $student->birth_date ? Carbon::parse($student->birth_date)->translatedFormat('d F Y') : '-',
            $student->father_name,
            $student->mother_name,
            $student->village,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style Judul
            1 => ['font' => ['bold' => true, 'size' => 12]],
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            // Style Header Tabel (Baris ke-5)
            5 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE0E0E0']]],
        ];
    }
}