<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\{Stage, Level, Major, AcademicYear};

class DataMasterController extends Controller
{
    public function index()
    {
        $stages = Stage::all();
        $levels = Level::with('stage')->orderBy('stage_id')->get();
        $majors = Major::all();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        $subjects = Subject::orderBy('group')->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();


        return view('admin.master_data.index', compact('stages', 'levels', 'majors', 'academicYears', 'subjects', 'teachers'));
    }

    // --- GENERIC STORE FUNCTION (Untuk ringkas) ---
    public function storeStage(Request $r)
    {
        Stage::create($r->all());
        return back()->with('success', 'Jenjang ditambah');
    }
    public function storeLevel(Request $r)
    {
        Level::create($r->all());
        return back()->with('success', 'Tingkat ditambah');
    }
    public function storeMajor(Request $r)
    {
        Major::create($r->all());
        return back()->with('success', 'Jurusan ditambah');
    }

    public function storeAcademicYear(Request $r)
    {
        // Jika set aktif, nonaktifkan yang lain
        if ($r->has('is_active')) {
            AcademicYear::query()->update(['is_active' => false]);
        }
        AcademicYear::create($r->merge(['is_active' => $r->has('is_active')])->all());
        return back()->with('success', 'Tahun Ajaran ditambah');
    }

    // Update Status Aktif Tahun Ajaran
    public function activateYear($id)
    {
        AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::where('id', $id)->update(['is_active' => true]);
        return back()->with('success', 'Tahun Ajaran diaktifkan');
    }

    // Tambahkan method destroy/update sesuai kebutuhan...
    // --- HAPUS JENJANG ---
    public function destroyStage(Stage $stage)
    {
        try {
            $stage->delete();
            return back()->with('success', 'Jenjang berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Jenjang ini masih memiliki Tingkat Kelas.');
        }
    }

    // --- HAPUS TINGKAT ---
    public function destroyLevel(Level $level)
    {
        try {
            $level->delete();
            return back()->with('success', 'Tingkat berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Tingkat ini sedang digunakan oleh Kelas/Rombel.');
        }
    }

    // --- HAPUS JURUSAN ---
    public function destroyMajor(Major $major)
    {
        try {
            $major->delete();
            return back()->with('success', 'Jurusan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Jurusan ini sedang digunakan oleh Kelas.');
        }
    }

    // --- HAPUS TAHUN AJARAN ---
    public function destroyAcademicYear(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return back()->with('error', 'Tahun Ajaran AKTIF tidak boleh dihapus. Aktifkan tahun lain terlebih dahulu.');
        }

        try {
            $academicYear->delete();
            return back()->with('success', 'Tahun Ajaran berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Tahun ajaran ini memiliki data kelas/siswa.');
        }
    }

    // Tambahkan Method Store & Destroy
    public function storeSubject(Request $r)
    {
        Subject::create($r->all());
        return back()->with('success', 'Mapel ditambah');
    }
    public function destroySubject(Subject $s)
    {
        $s->delete();
        return back()->with('success', 'Mapel dihapus');
    }

    public function storeTeacher(Request $r)
    {
        Teacher::create($r->all());
        return back()->with('success', 'Guru ditambah');
    }
    public function destroyTeacher(Teacher $t)
    {
        $t->delete();
        return back()->with('success', 'Guru dihapus');
    }
}
