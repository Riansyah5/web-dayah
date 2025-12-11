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

}
