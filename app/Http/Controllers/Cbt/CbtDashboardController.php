<?php

namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CbtDashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('cbt')->user()->student; // Mengambil relasi data siswa asli
        return view('cbt.dashboard', compact('student'));
    }
}