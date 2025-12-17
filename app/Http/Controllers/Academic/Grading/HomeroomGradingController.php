<?php

namespace App\Http\Controllers\Academic\Grading;

use App\Models\Grade;
use App\Models\Course;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\ReportCard; // Pastikan model ini ada

class HomeroomGradingController extends Controller
{
    // Halaman List Kelas (Pilih Kelas)
    public function index()
    {
        $classrooms = Classroom::with(['academicYear', 'level', 'major'])
            ->orderBy('academic_year_id', 'desc')
            ->orderBy('level_id')
            ->get();

        return view('academic.homeroom.index', compact('classrooms'));
    }

    // Halaman Detail Leger (Show)
    public function show(Classroom $classroom)
    {
        $classroom->load(['students']);
        
        $students = $classroom->students->sortBy('name');
        
        // Ambil courses secara manual karena relasi di model Classroom tidak ditemukan
        $courses = Course::with('subject')->where('classroom_id', $classroom->id)->get();

        // Ambil data grades secara manual (grouped by student_id)
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');

        // Ambil data report cards secara manual (keyed by student_id)
        $reportCards = ReportCard::whereIn('student_id', $students->pluck('id'))
            ->where('classroom_id', $classroom->id)
            ->get()
            ->keyBy('student_id');

        return view('academic.homeroom.show', compact('classroom', 'students', 'courses', 'grades', 'reportCards'));
    }

    // Simpan Data Leger (Absensi & Catatan)
    public function update(Request $request)
    {
        $data = $request->report;
        $classroomId = $request->classroom_id;

        foreach ($data as $studentId => $reportData) {
            // Simpan data rapor (Sakit, Izin, Alpha, Catatan, Status)
            // Asumsi model ReportCard ada dan memiliki relasi ke Student
            ReportCard::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'classroom_id' => $classroomId,
                ],
                [
                    'sick' => $reportData['sick'] ?? 0,
                    'permission' => $reportData['permission'] ?? 0,
                    'absent' => $reportData['absent'] ?? 0,
                    'notes' => $reportData['notes'],
                    'status' => $reportData['status'],
                ]
            );
        }

        return back()->with('success', 'Data Leger berhasil disimpan.');
    }

    public function print($studentId, $classroomId)
    {
        $student = Student::findOrFail($studentId);
        $classroom = Classroom::findOrFail($classroomId);
        
        // Ambil Data Nilai
        $courses = Course::with(['subject', 'grades' => function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        }])->where('classroom_id', $classroomId)
           ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
           ->orderBy('subjects.group')
           ->select('courses.*')
           ->get();

        // Ambil Data Absensi
        $reportCard = ReportCard::where('student_id', $studentId)
                        ->where('classroom_id', $classroomId)
                        ->first();

        $pdf = Pdf::loadView('academic.grading.exports.report-card-pdf', compact('student', 'classroom', 'courses', 'reportCard'));
        
        return $pdf->stream('Rapor_' . $student->name . '.pdf');
    
    }
}