<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Pegawai $pegawai)
    {
        return view('user.tambah-akun', compact('pegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Pegawai $pegawai)
    {
        // validasi request 
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'password' => 'required',
            'role' => 'required|in:admin,guru',
            'status' => 'required|in:aktif,nonaktif',
            'updated_by' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $pegawai) {
            // buat user baru
            $user = User::create($request->all());

            // update pegawai dengan user id
            $pegawai->update([
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('pegawai.show', $pegawai->id)->with('success', 'Akun berhasil dibuat!');




    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
