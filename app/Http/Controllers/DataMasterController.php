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
        // Jika set aktif, nonaktifkan yang lain
        if ($r->has('is_active')) {
            AcademicYear::query()->update(['is_active' => false]);
        }
        AcademicYear::create($r->merge(['is_active' => $r->has('is_active')])->all());
        return back()->with('success', 'Tahun Ajaran ditambah')->with('active_tab', 'tab-years');
    }

    // Update Status Aktif Tahun Ajaran
    public function activateYear($id)
    {
        AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::where('id', $id)->update(['is_active' => true]);
        return back()->with('success', 'Tahun Ajaran diaktifkan')->with('active_tab', 'tab-years');
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
            'code' => 'required|unique:subjects,code',
            'group' => 'required',
            'stages' => 'required|array' // Wajib pilih minimal 1 jenjang
        ]);

        // 1. Simpan Mapel
        $subject = Subject::create($request->only(['name', 'code', 'group']));

        // 2. Hubungkan ke Jenjang yang dipilih
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
