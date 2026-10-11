<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\{Stage, Level, Major, AcademicYear, Pegawai};

class DataMasterController extends Controller
{
    public function index()
    {
        $stages = Stage::all();
        $levels = Level::with('stage')->orderBy('stage_id')->get();
        $majors = Major::all();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $pegawais = Pegawai::orderBy('nama')->get();

        $subjects = Subject::orderBy('group')->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();


        return view('admin.master_data.index', compact('stages', 'levels', 'majors', 'academicYears', 'subjects', 'teachers', 'pegawais'));
    }

    // --- GENERIC STORE FUNCTION (Untuk ringkas) ---
    public function storeStage(Request $r)
    {
        Stage::create($r->all());
        return back()->with('success', 'Jenjang ditambah')->with('active_tab', 'tab-stages');
    }

    public function updateStage(Request $request, Stage $stage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        $stage->update($request->all());
        return back()->with('success', 'Jenjang berhasil diperbarui')->with('active_tab', 'tab-stages');
    }

    public function storeLevel(Request $r)
    {
        Level::create($r->all());
        return back()->with('success', 'Tingkat ditambah')->with('active_tab', 'tab-levels');
    }
    public function storeMajor(Request $r)
    {
        Major::create($r->all());
        return back()->with('success', 'Jurusan ditambah')->with('active_tab', 'tab-majors');
    }

    public function storeAcademicYear(Request $r)
    {
        $oldActive = null;
        if ($r->has('is_active')) {
            $oldActive = AcademicYear::where('is_active', true)->first();
            AcademicYear::query()->update(['is_active' => false]);
        }
        
        $newYear = AcademicYear::create($r->merge(['is_active' => $r->has('is_active')])->all());
        
        // Salin penempatan kamar jika langsung diaktifkan
        if ($r->has('is_active') && $oldActive && $oldActive->id != $newYear->id) {
            $this->copyRoomAssignments($oldActive->id, $newYear->id);
        }
        
        return back()->with('success', 'Tahun Ajaran ditambah')->with('active_tab', 'tab-years');
    }

    // Update Status Aktif Tahun Ajaran
    public function activateYear($id)
    {
        $oldActive = AcademicYear::where('is_active', true)->first();
        AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::where('id', $id)->update(['is_active' => true]);

        if ($oldActive && $oldActive->id != $id) {
            $this->copyRoomAssignments($oldActive->id, $id);
        }

        return back()->with('success', 'Tahun Ajaran diaktifkan. Penempatan kamar otomatis disalin.')->with('active_tab', 'tab-years');
    }

    private function copyRoomAssignments($oldYearId, $newYearId)
    {
        $oldAssignments = \App\Models\RoomAssignment::where('academic_year_id', $oldYearId)->get();
        $newAssignments = [];
        $activeStudents = \App\Models\Student::where('status', 'active')->pluck('id')->toArray();

        foreach ($oldAssignments as $assignment) {
            if (in_array($assignment->student_id, $activeStudents)) {
                $exists = \App\Models\RoomAssignment::where('academic_year_id', $newYearId)
                            ->where('student_id', $assignment->student_id)
                            ->exists();
                
                if (!$exists) {
                    $newAssignments[] = [
                        'id' => (string) \Illuminate\Support\Str::ulid(),
                        'student_id' => $assignment->student_id,
                        'room_id' => $assignment->room_id,
                        'academic_year_id' => $newYearId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($newAssignments)) {
            \App\Models\RoomAssignment::insert($newAssignments);
        }
    }

    // Tambahkan method destroy/update sesuai kebutuhan...
    // --- HAPUS JENJANG ---
    public function destroyStage(Stage $stage)
    {
        try {
            $stage->delete();
            return back()->with('success', 'Jenjang berhasil dihapus.')->with('active_tab', 'tab-stages');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Jenjang ini masih memiliki Tingkat Kelas.')->with('active_tab', 'tab-stages');
        }
    }

    // --- HAPUS TINGKAT ---
    public function destroyLevel(Level $level)
    {
        try {
            $level->delete();
            return back()->with('success', 'Tingkat berhasil dihapus.')->with('active_tab', 'tab-levels');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Tingkat ini sedang digunakan oleh Kelas/Rombel.')->with('active_tab', 'tab-levels');
        }
    }

    // --- HAPUS JURUSAN ---
    public function destroyMajor(Major $major)
    {
        try {
            $major->delete();
            return back()->with('success', 'Jurusan berhasil dihapus.')->with('active_tab', 'tab-majors');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Jurusan ini sedang digunakan oleh Kelas.')->with('active_tab', 'tab-majors');
        }
    }

    // --- HAPUS TAHUN AJARAN ---
    public function destroyAcademicYear(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return back()->with('error', 'Tahun Ajaran AKTIF tidak boleh dihapus. Aktifkan tahun lain terlebih dahulu.')->with('active_tab', 'tab-years');
        }

        try {
            $academicYear->delete();
            return back()->with('success', 'Tahun Ajaran berhasil dihapus.')->with('active_tab', 'tab-years');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Tahun ajaran ini memiliki data kelas/siswa.')->with('active_tab', 'tab-years');
        }
    }

    // Tambahkan Method Store & Destroy
    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'group' => 'required',
            'stages' => 'required|array' // Wajib pilih minimal 1 jenjang
        ]);

        $subject = Subject::where('code', $request->code)->first();

        if ($subject) {
            // Cek apakah jenjang yang dipilih sudah ada di mapel ini
            $existingStageIds = $subject->stages()->pluck('stages.id')->toArray();
            $hasDuplicate = !empty(array_intersect($existingStageIds, $request->stages));

            if ($hasDuplicate) {
                return back()->with('error', "Gagal! Mapel dengan kode {$request->code} sudah ada untuk jenjang tersebut.")->with('active_tab', 'tab-subjects');
            }

            $subject->stages()->attach($request->stages);
            return back()->with('success', 'Mapel yang sama ditambahkan jenjang baru.')->with('active_tab', 'tab-subjects');
        }

        // Jika belum ada, buat baru
        $subject = Subject::create($request->only(['name', 'code', 'group']));
        $subject->stages()->sync($request->stages);

        return back()->with('success', 'Mapel berhasil ditambah dan dihubungkan ke jenjang.')->with('active_tab', 'tab-subjects');
    }

    public function destroySubject(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Mapel dihapus')->with('active_tab', 'tab-subjects');
    }

    public function storeTeacher(Request $r)
    {
        Teacher::create($r->all());
        return back()->with('success', 'Guru ditambah')->with('active_tab', 'tab-teachers');
    }
    public function destroyTeacher(Teacher $teacher)
    {
        $teacher->delete();
        return back()->with('success', 'Guru dihapus')->with('active_tab', 'tab-teachers');
    }
}
