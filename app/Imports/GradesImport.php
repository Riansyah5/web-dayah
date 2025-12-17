<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GradesImport implements ToCollection, WithHeadingRow
{
    protected $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Kita cari siswa berdasarkan ID di Excel (Kolom A)
            // Pastikan key array sesuai dengan heading (slugified oleh library excel)
            // 'SYSTEM_ID (JANGAN DIEDIT)' menjadi 'system_id_jangan_diedit'
            
            $studentId = $row['system_id_jangan_diedit'] ?? null;

            if ($studentId) {
                $grade = Grade::updateOrCreate(
                    [
                        'course_id' => $this->courseId,
                        'student_id' => $studentId,
                    ],
                    [
                        'score_harian' => $row['nilai_harian'] ?? 0,
                        'score_uts'    => $row['nilai_uts'] ?? 0,
                        'score_uas'    => $row['nilai_uas'] ?? 0,
                    ]
                );

                // Hitung Nilai Akhir & Predikat (Method ini ada di Model Grade yang kita buat sebelumnya)
                $grade->calculateFinal();
            }
        }
    }
}