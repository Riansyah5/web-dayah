<?php

namespace App\Http\Controllers\Academic\Schedule;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\LessonSchedule;
use Illuminate\Http\Request;

class SchedulePrintController extends Controller
{
    public function printAll(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 1. Ambil Semua Kelas di Tahun Aktif (Urutkan nama)
        $query = Classroom::where('academic_year_id', $activeYear->id);

        if ($request->has('stage') && $request->stage) {
            $query->whereHas('level.stage', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->stage . '%');
            });
        }

        $classrooms = $query->orderBy('name')->get();

        // 2. Ambil Semua Jadwal, Eager Load relasi
        $schedules = LessonSchedule::where('academic_year_id', $activeYear->id)
                        ->whereIn('classroom_id', $classrooms->pluck('id'))
                        ->with(['classroom', 'subject', 'teacher'])
                        ->get();

        // 3. Struktur Data untuk View (Matrix)
        // Kita butuh daftar 'Jam Mulai' yang unik untuk menentukan baris tabel
        $timeSlots = $schedules->pluck('start_time')
                        ->unique()
                        ->sort()
                        ->values();

        // Grouping data jadwal biar mudah dipanggil di view: $matrix[hari][jam][kelas_id]
        $matrix = [];
        foreach ($schedules as $sched) {
            // Format jam agar key array konsisten (misal "07:00:00")
            $timeKey = $sched->start_time; 
            $matrix[$sched->day_of_week][$timeKey][$sched->classroom_id] = $sched;
        }

        // Mapping Nama Hari
        $days = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
        ];

        return view('academic.schedule.print_all', compact('classrooms', 'timeSlots', 'matrix', 'days', 'activeYear'));
    }
}