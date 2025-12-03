<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            'username' => 'required|string|max:20',
            'password' => 'required',
            'role' => 'required|in:Admin,Guru',
            'status' => 'required|in:Aktif,Nonaktif',
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
    public function edit(User $user)
    {
        return view('user.edit-akun', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // validasi request
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20',
            'password' => 'nullable',
            'role' => 'required|in:Admin,Guru',
            'status' => 'required|in:Aktif,Nonaktif',
            'updated_by' => 'required|string',
        ]);
        // ambil data request kecuali password
        $data = $request->except('password');

        // jika password diisi maka di hash
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        // update user
        $user->update($data);
        
        // Mengarahkan kembali ke halaman daftar pengguna dengan pesan sukses.
        // Ini lebih andal daripada menggunakan back().
        return redirect()->route('user.index')->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // DB::transaction(function () use ($user) {
        //     // Cari pegawai yang terkait dengan user ini dan set user_id menjadi null.
        //     // Ini untuk melepaskan foreign key constraint.
        //     if ($user->pegawai) {
        //         $user->pegawai->update(['user_id' => null]);
        //     }
        //     // Setelah relasi dilepaskan, baru hapus user.
        //     $user->delete();
        // });
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Akun berhasil dihapus!');
    }

    public function updateStatus(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'Status pengguna berhasil diperbarui.']);
    }
}
