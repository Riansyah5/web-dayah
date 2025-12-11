<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\RoomAssignment;

class RoomAssignmentController extends Controller
{
    public function create(){
        // 1. Ambil Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Belum ada Tahun Ajaran aktif!');
        }

        // 2. Ambil Santri yang BELUM punya kamar di tahun ajaran ini
        // Kita pakai fitur "whereDoesntHave" milik Eloquent
        $students = Student::whereDoesntHave('roomAssignments', function($query) use ($activeYear){
            $query->where('academic_year_id', $activeYear->id);
        })->where('status', 'active')
          ->orderBy('class_group') // Urutkan biar rapi saat grouping
          ->orderBy('name')->get();

        // 3. Ambil Kamar yang masih tersedia (Opsional: filter by capacity)
        $rooms = Room::with('dorm')->get();
        return view('assignments.create', compact('students', 'rooms', 'activeYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        // Cek kapasitas kamar manual (Opsional tapi bagus)
        $room = Room::withCount(['assignments' => function($q) use ($request) {
            $q->where('academic_year_id', $request->academic_year_id);
        }])->find($request->room_id);

        $countSelected = count($request->student_ids);
        $remaining = $room->capacity - $room->assignments_count;

        if ($countSelected > $remaining) {
            return back()->with('error', "Kapasitas tidak cukup! Sisa: $remaining, Dipilih: $countSelected");
        }

        // Simpan Data
        foreach ($request->student_ids as $studentId) {
            RoomAssignment::create([
                'student_id' => $studentId,
                'room_id' => $request->room_id,
                'academic_year_id' => $request->academic_year_id,
            ]);
        }

        return redirect()->route('students.index')->with('success', "$countSelected Santri berhasil ditempatkan di kamar!");
    }
}
