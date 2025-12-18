<?php

namespace App\Http\Controllers\Academic\Report;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentHistoryController extends Controller
{
    public function show(Student $student)
    {
        // 1. Ambil semua kelas yang PERNAH diduduki siswa ini
        // Kita urutkan dari Tahun Ajaran terbaru ke terlama
        $history = $student->classrooms()
            ->with(['academicYear', 'level'])
            ->get()
            ->sortByDesc(function($classroom) {
                return $classroom->academicYear->id; // Asumsi ID tahun ajaran auto increment (semakin baru semakin besar)
            });

        // 2. Ambil Rekap Nilai (ReportCard) & Rata-rata Nilai
        // Kita perlu data tambahan untuk ditampilkan di card (Ranking, Status, Rata-rata)
        foreach ($history as $class) {
            // Ambil data rekap (sakit, izin, catatan)
            $class->report_summary = \App\Models\ReportCard::where('student_id', $student->id)
                ->where('classroom_id', $class->id)
                ->first();

            // Hitung rata-rata nilai di semester itu (Opsional, biar keren)
            $grades = \App\Models\Grade::where('student_id', $student->id)
                ->whereIn('course_id', function($q) use ($class) {
                    $q->select('id')->from('courses')->where('classroom_id', $class->id);
                })->get();

            $class->average_score = $grades->avg('score_final');
        }

        return view('academic.report.student_history', compact('student', 'history'));
    }
}