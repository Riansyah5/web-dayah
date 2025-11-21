<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('pegawai.jabatan', compact('jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $jabatan = Jabatan::create($request->all());

        // Mengembalikan response JSON yang berisi data baru untuk request AJAX
        return response()->json([
            'message' => 'Jabatan baru berhasil ditambahkan.',
            'data'    => $jabatan
        ], 201); // 201 Created status
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jabatan $jabatan)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $jabatan->update($request->all());

        // Mengembalikan response JSON untuk request AJAX
        return response()->json([
            'message' => 'Data jabatan berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        // Mengembalikan response JSON untuk request AJAX
        return response()->json([
            'message' => 'Data jabatan berhasil dihapus.'
        ]);
    }
}
