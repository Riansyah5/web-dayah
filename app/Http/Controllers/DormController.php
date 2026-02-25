<?php

namespace App\Http\Controllers;

use App\Models\Dorm;
use Illuminate\Http\Request;

class DormController extends Controller
{
    //
    public function index()
    {
        $dorms = Dorm::all();
        return view('dorms.index', compact('dorms'));
    }

    public function create(){
        return view('dorms.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'gender' => 'required|in:L,P',
        ]);
        Dorm::create($request->all());
        return redirect()->route('dorms.index')->with('success', 'Asrama berhasil ditambahkan.');
    }

    public function update(Request $request, Dorm $dorm)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
        ]);

        $dorm->update($request->all());
        return redirect()->route('dorms.index')->with('success', 'Data asrama berhasil diperbarui.');
    }

    public function destroy(Dorm $dorm)
    {
        $dorm->delete();
        return redirect()->route('dorms.index')->with('success', 'Data asrama berhasil dihapus.');
    }
}
