<?php

namespace App\Http\Controllers\Academic\Schedule;

use App\Http\Controllers\Controller;
use App\Models\LessonSchedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class LessonScheduleController extends Controller
{
    // Halaman Utama: Pilih Kelas Dulu
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        
        // Ambil kelas beserta jumlah jam pelajaran yang sudah terisi
        $classrooms = Classroom::where('academic_year_id', $activeYear->id)
                        ->withCount('lessonSchedules')
                        ->orderBy('name')
                        ->get();

        return view('academic.schedule.index', compact('classrooms', 'activeYear'));
    }

    // Halaman Atur Jadwal Per Kelas (Roster View)
    public function show(Classroom $classroom)
    {
        // Load jadwal urutkan berdasarkan Hari lalu Jam Mulai
        $schedules = LessonSchedule::where('classroom_id', $classroom->id)
                        ->with(['subject', 'teacher'])
                        ->orderBy('day_of_week')
                        ->orderBy('start_time')
                        ->get()
                        ->groupBy('day_of_week'); // Grouping biar mudah di view

        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        return view('academic.schedule.show', compact('classroom', 'schedules', 'subjects', 'teachers'));
    }

    // Simpan Jadwal Baru
    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'day_of_week' => 'required|integer|min:1|max:7',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
        ]);
        
        // TODO: Tambahkan validasi bentrok jadwal guru (Opsional tapi penting)
        // Cek apakah guru ini sudah mengajar di kelas lain pada jam yang sama?

        $activeYear = AcademicYear::where('is_active', true)->first();

        LessonSchedule::create([
            'academic_year_id' => $activeYear->id,
            'classroom_id' => $classroom->id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(LessonSchedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }
}