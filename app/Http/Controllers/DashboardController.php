<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Student;
use App\Models\StudentExit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::where('status_pegawai', 'Aktif')->count();
        $pegawaiLaki = Pegawai::where('jenis_kelamin', 'Laki-laki')->count();
        $pegawaiPerempuan = Pegawai::where('jenis_kelamin', 'Perempuan')->count();
        $totalSantri = Student::where('status', 'active')->count();
        $santriLaki = Student::where('gender', 'L')->count();
        $santriPerempuan = Student::where('gender', 'P')->count();
        $santriSMP = Student::where(['education_level' => 'WUSTHA', 'status' => 'active'])->count();
        $santriSMPlaki = Student::where(['education_level' => 'WUSTHA', 'gender' => 'L', 'status' => 'active'])->count();
        $santriSMPperempuan = Student::where(['education_level' => 'WUSTHA', 'gender' => 'P', 'status' => 'active'])->count();
        $santriSMA = Student::where(['education_level' => 'ULYA', 'status' => 'active'])->count();
        $santriSMAlaki = Student::where(['education_level' => 'ULYA', 'gender' => 'L', 'status' => 'active'])->count();
        $santriSMAperempuan = Student::where(['education_level' => 'ULYA', 'gender' => 'P', 'status' => 'active'])->count();
        $totalAlumni = StudentExit::count();

        $totalStudents = Student::count();
        return view('dashboard', 
        compact('totalPegawai', 'pegawaiLaki', 'pegawaiPerempuan', 'totalStudents', 'totalSantri', 'santriLaki', 'santriPerempuan', 'santriSMP', 'santriSMPlaki', 'santriSMPperempuan', 'santriSMA', 'santriSMAlaki', 'santriSMAperempuan', 'totalAlumni'));
    }
}
