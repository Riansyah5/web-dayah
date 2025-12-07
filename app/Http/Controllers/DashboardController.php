<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Pegawai::count();
        return view('dashboard', compact('total'));
    }
}
