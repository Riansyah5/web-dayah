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
        $rooms = Room::with(['dorm', 'warden'])->latest()->paginate(10);
        $dorms = Dorm::all();
        $wardens = Pegawai::all();
        return view('rooms.index', compact('rooms', 'dorms', 'wardens'));
    }

    public function create(){
        // Kita butuh data Gedung untuk dipilih di dropdown
        $dorms = Dorm::all();
        $wardens = Pegawai::all()->sortBy('nama'); // Urutkan pegawai berdasarkan nama untuk memudahkan pencarian
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

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dorm_id' => 'required|exists:dorms,id',
            'capacity' => 'required|integer|min:1',
            'warden_id' => 'nullable|exists:pegawais,id',
        ]);

        $room->update($request->all());
        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil dihapus.');
    }
}
