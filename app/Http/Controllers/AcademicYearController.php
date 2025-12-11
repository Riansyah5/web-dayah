<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    
    public function index(){
        // Urutkan dari yang paling baru
        $years = AcademicYear::latest()->get();
        return view('academic_years.index', compact('years'));
    }

    public function create(){
        return view('academic_years.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string', // Contoh: 2025/2026
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        // LOGIC PENTING: Jika user memilih tahun ini sebagai AKTIF
        if($request->has('is_active')){
            // Set semua tahun akademik lain menjadi non-aktif
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create([
            'name' => $request->name,
            'semester' => $request->semester,
            // Jika checkbox dicentang nilainya 1 (true), jika tidak 0 (false)
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('academic-years.index')->with('success', 'Tahun Ajaran berhasil dibuat.');
    }

    public function edit(AcademicYear $academicYear){
        return view('academic_years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear){
        $request->validate([
            'name' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        // LOGIC PENTING: Switch Active
        if($request->has('is_active')){
            // Matikan yang lain
            AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
            $isActive = true;
        } else {
            // Kita cegah user mematikan tahun aktif tanpa mengaktifkan yang lain
            if($academicYear->is_active){
                return back()->with('error', 'Tidak bisa menonaktifkan tahun ajaran aktif. Silakan aktifkan tahun ajaran lain, maka yang ini otomatis nonaktif.');
            }
            $isActive = false;
        }

        $academicYear->update([
            'name' => $request->name,
            'semester' => $request->semester,
            'is_active' => $isActive,
        ]);

        return redirect()->route('academic-years.index')->with('success', 'Tahun ajaran diperbarui!');
    }
}
