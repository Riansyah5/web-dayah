<?php

namespace App\Http\Controllers;

use App\Models\Dorm;
use App\Models\Room;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(){
        // Kita gunakan 'with' untuk Eager Loading agar query lebih ringan
        // Mengambil data kamar beserta data gedungnya
        $rooms = Room::with('dorm')->latest()->paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    public function create(){
        // Kita butuh data Gedung untuk dipilih di dropdown
        $dorms = Dorm::all();
        $wardens = Pegawai::all();
        return view('rooms.create', compact('dorms', 'wardens'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'dorm_id' => 'required|exists:dorms,id',
            'capacity' => 'required|integer',
        ]);

        Room::create($request->all());
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }
}
