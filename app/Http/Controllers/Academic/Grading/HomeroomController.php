<?php

namespace App\Http\Controllers\Academic\Grading;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\ReportCard;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Kita akan install ini nanti

class HomeroomController extends Controller
{
    // Halaman Leger & Input Absensi
    public function index(Request $request)
    {
        // Untuk demo, kita pakai classroom_id dari request.
        // Nanti di production, bisa otomatis detect: Auth::user()->teacher->classroom
        $classroomId = $request->classroom_id;
        
        if (!$classroomId) {
            // Jika belum pilih kelas, redirect atau tampilkan error (sederhana saja dulu)
            return redirect()->back()->with('error', 'Pilih kelas terlebih dahulu.');
        }

        $classroom = Classroom::with(['academicYear', 'level'])->findOrFail($classroomId);
        
        // 1. Ambil Semua Mapel di Kelas ini (Untuk Header Tabel)
        $courses = Course::with('subject')
            ->where('classroom_id', $classroomId)
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->orderBy('subjects.group') // Urutkan Kelompok A, B, C
            ->select('courses.*') // Hindari konflik nama kolom
            ->get();

        // 2. Ambil Siswa + Nilai Mereka
        // Kita eager load 'grades' supaya tidak query berulang-ulang (N+1 Problem)
        $students = Student::whereHas('classrooms', function($q) use ($classroomId) {
            $q->where('classroom_id', $classroomId);
        })->with(['grades' => function($q) use ($courses) {
            $q->whereIn('course_id', $courses->pluck('id'));
        }, 'reportCards' => function($q) use ($classroomId) {
            $q->where('classroom_id', $classroomId);
        }])->orderBy('name')->get();

        return view('academic.grading.homeroom.index', compact('classroom', 'courses', 'students'));
    }

    // Simpan Data Non-Akademik (Sakit/Izin/Alpa/Catatan)
    public function update(Request $request)
    {
        $data = $request->input('report'); // Array student_id => [sick, permission, notes, etc]

        foreach ($data as $studentId => $row) {
            ReportCard::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'classroom_id' => $request->classroom_id
                ],
                [
                    'sick' => $row['sick'] ?? 0,
                    'permission' => $row['permission'] ?? 0,
                    'absent' => $row['absent'] ?? 0,
                    'notes' => $row['notes'] ?? null,
                    'status' => $row['status'] ?? 'Naik Kelas',
                ]
            );
        }

        return back()->with('success', 'Data Rapor berhasil disimpan.');
    }

    // Cetak PDF Rapor
    public function printPdf($studentId, $classroomId)
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