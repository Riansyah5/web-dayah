<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradeTemplateExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function collection()
    {
        // Ambil semua siswa di kelas ini
        return $this->course->classroom->students;
    }

    public function map($student): array
    {
        // Ambil nilai yang sudah ada (jika ada), agar saat didownload ulang nilainya tidak hilang
        $grade = $this->course->grades->where('student_id', $student->id)->first();

        return [
            $student->id,           // Kolom A: ID (JANGAN DIEDIT USER)
            $student->nis,          // Kolom B: NIS
            $student->name,         // Kolom C: Nama
            $grade->score_harian ?? 0, // Kolom D: Harian
            $grade->score_uts ?? 0,    // Kolom E: UTS
            $grade->score_uas ?? 0,    // Kolom F: UAS
        ];
    }

    public function headings(): array
    {
        return [
            'SYSTEM_ID (JANGAN DIEDIT)', // Header A
            'NIS',                       // Header B
            'NAMA SISWA',                // Header C
            'NILAI HARIAN',              // Header D
            'NILAI UTS',                 // Header E
            'NILAI UAS',                 // Header F
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold Header
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}