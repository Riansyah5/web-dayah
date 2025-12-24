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
    // Ambil Tahun Aktif
    $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();

    // 1. Ambil Kelas Aktif -> Grouping by Level Name (Misal: "Kelas 7", "Kelas 8")
    $activeClasses = \App\Models\Classroom::with(['level', 'academicYear'])
      ->withCount('students')
      ->where('academic_year_id', $activeYear->id)
      ->orderBy('level_id') // Urutkan biar level 7 paling atas
      ->orderBy('name')
      ->get()
      ->groupBy(function ($item) {
        return $item->level->name; // Key Grouping: "Kelas 7"
      });

    // 2. Ambil Riwayat -> Grouping by Tahun Ajaran + Semester
    $historyClasses = \App\Models\Classroom::with(['level', 'academicYear'])
      ->withCount('students')
      ->where('academic_year_id', '!=', $activeYear->id)
      ->orderByDesc('academic_year_id') // Tahun terbaru paling atas
      ->get()
      ->groupBy(function ($item) {
        // Key Grouping: "2023/2024 - Ganjil"
        return $item->academicYear->name . ' (' . $item->academicYear->semester . ')';
      });

    return view('academic.grading.homeroom.index', compact('activeClasses', 'historyClasses', 'activeYear'));
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

    return view('academic.grading.homeroom.show', compact('classroom', 'students', 'courses', 'grades', 'reportCards'));
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
          'status' => $reportData['status'] ?? "-",
        ]
      );
    }

    return back()->with('success', 'Data Leger berhasil disimpan.');
  }

  // File: app/Http/Controllers/Academic/Grading/HomeroomController.php

  public function print($studentId, $classroomId)
  {
    $isPdf = true; // Set true untuk generate PDF

    // Ambil Data Student dan Classroom
    $student = Student::findOrFail($studentId);
    $classroom = Classroom::findOrFail($classroomId);

    // Ambil Data Nilai
    $courses = Course::with(['subject', 'grades' => function ($q) use ($studentId) {
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

    // --- Setting Rapor dari Database ---
    $classroom = Classroom::with('level.stage')->findOrFail($classroomId);

    // --- LOGIC BARU: AMBIL SETTING DARI DB ---
    
    // 1. Cari jenjang kelas ini (SD/SMP/SMA?)
    $stageId = $classroom->level->stage_id;
    $academicYearId = $classroom->academic_year_id;

    // 2. Ambil setting rapor yang sesuai
    $setting = \App\Models\ReportSetting::where('academic_year_id', $academicYearId)
                ->where('stage_id', $stageId)
                ->first();

    // 3. Fallback (Jaga-jaga jika admin lupa setting, biar gak error)
    $reportDate = $setting ? $setting->report_date->translatedFormat('d F Y') : now()->translatedFormat('d F Y');
    $reportCity = $setting ? $setting->city : 'Kota Santri';
    $headmaster = $setting ? $setting->headmaster_name : '..........................';
    $headmasterNip = $setting ? $setting->headmaster_nip : '-';

    // 4. Kirim ke View PDF
    $pdf = Pdf::loadView('academic.grading.exports.report-card-pdf', compact(
        'student', 
        'classroom', 
        'courses', 
        'reportCard',
        'reportDate', 
        'reportCity', // Variable baru
        'headmaster', 
        'headmasterNip',
        'isPdf'
    ));

    // Set ukuran kertas F4 atau A4
    $pdf->setPaper('A4', 'portrait');

    return $pdf->stream('Rapor_' . $student->name . '.pdf');
  }

  public function preview($studentId, $classroomId)
  {
    $isPdf = false;

    $student = Student::findOrFail($studentId);
    $classroom = Classroom::findOrFail($classroomId);

    // Ambil Data Nilai
    $courses = Course::with(['subject', 'grades' => function ($q) use ($studentId) {
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

    // --- Setting Rapor dari Database ---
    $classroom = Classroom::with('level.stage')->findOrFail($classroomId);

    // --- LOGIC BARU: AMBIL SETTING DARI DB ---
    
    // 1. Cari jenjang kelas ini (SD/SMP/SMA?)
    $stageId = $classroom->level->stage_id;
    $academicYearId = $classroom->academic_year_id;

    // 2. Ambil setting rapor yang sesuai
    $setting = \App\Models\ReportSetting::where('academic_year_id', $academicYearId)
                ->where('stage_id', $stageId)
                ->first();

    // 3. Fallback (Jaga-jaga jika admin lupa setting, biar gak error)
    $reportDate = $setting ? $setting->report_date->translatedFormat('d F Y') : now()->translatedFormat('d F Y');
    $reportCity = $setting ? $setting->city : 'Kota Santri';
    $headmaster = $setting ? $setting->headmaster_name : '..........................';
    $headmasterNip = $setting ? $setting->headmaster_nip : '-';

    // 4. Kirim ke View PDF
    $pdf = Pdf::loadView('academic.grading.exports.report-card-pdf', compact(
        'student', 
        'classroom', 
        'courses', 
        'reportCard',
        'reportDate', 
        'reportCity', // Variable baru
        'headmaster', 
        'headmasterNip',
        'isPdf'
    ));

    return view('academic.grading.exports.report-card-pdf', compact('student', 'classroom', 'courses', 'reportCard', 'reportDate', 'reportCity', 'headmaster', 'headmasterNip', 'isPdf'));
  }
}
