<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
            'username' => 'required|string|max:20|unique:users,username',
            'password' => 'required',
            'role' => 'required|in:Admin,Guru',
            'email' => 'nullable',
            'status' => 'required|in:Aktif,Nonaktif',
            'updated_by' => 'required|string',
        ], [
            'username.unique' => 'Username sudah terdaftar, silakan gunakan username lain.',
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
    public function show(User $user)
    {
        return view('user.account-setting', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->username === 'superadmin') {
            return abort(403, 'Super Admin tidak dapat dimodifikasi');
        }

        return view('user.edit-akun', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->username === 'superadmin') {
            return abort(403, 'Super Admin tidak dapat dimodifikasi');
        }
        // validasi request
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users,username,' . $user->id,
            'password' => 'nullable',
            'role' => 'required|in:Admin,Guru',
            'email' => 'nullable',
            'status' => 'required|in:Aktif,Nonaktif',
            'updated_by' => 'required|string',
        ], [
            'username.unique' => 'Username sudah terdaftar, silakan gunakan username lain.',
        ]);
        // ambil data request kecuali password
        $data = $request->except(['password', 'source']);

        // jika password diisi maka di hash
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        // update user
        $user->update($data);

        if ($request->source == 'profile') {
            return back()->with('success', 'Profil berhasil diperbarui!');
        }

        // Mengarahkan kembali ke halaman daftar pengguna dengan pesan sukses.
        // Ini lebih andal daripada menggunakan back().
        return redirect()->route('user.index')->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->username === 'superadmin') {
            return abort(403, 'Super Admin tidak dapat dimodifikasi');
        }

        DB::transaction(function () use ($user) {
            // Cari pegawai yang terkait dengan user ini dan set user_id menjadi null.
            // Ini untuk melepaskan foreign key constraint.
            if ($user->pegawai) {
                $user->pegawai->update(['user_id' => null]);
            }
            // Setelah relasi dilepaskan, baru hapus user.
            $user->delete();
        });
        return redirect()->route('user.index')->with('success', 'Akun berhasil dihapus!');
    }

    public function updateStatus(Request $request, User $user)
    {
        if ($user->username === 'superadmin') {
            return response()->json([
                'message' => 'Status Super Admin tidak dapat diubah'
            ], 403);
        }

        // Validasi request
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

    public function editRole(User $user)
    {
        $roles = Role::where('name', '!=', 'Superadmin')->get();
        $permissions = Permission::all();
        
        $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        // 1. Definisikan Peta Kelompok Modul (Berdasarkan Seeder Anda)
        $mappedGroups = [
            'Pengaturan & Master Data' => ['kelola-pengaturan-sistem', 'kelola-master-data', 'kelola-user-pegawai'],
            'Kesiswaan & Asrama' => ['lihat-data-santri', 'kelola-data-santri', 'lihat-asrama', 'kelola-asrama-kamar', 'kelola-pelanggaran', 'kelola-perizinan-santri'],
            'Akademik & KBM' => ['lihat-jadwal-pelajaran', 'kelola-jadwal-pelajaran', 'kelola-piket-badal', 'ajukan-izin-guru', 'isi-jurnal-guru', 'lihat-kelas', 'kelola-kelas'],
            'Rapor & Penilaian' => ['isi-nilai-mapel', 'kelola-leger-rapor', 'kelola-setting-rapor'],
            'Modul Tahfizh' => ['kelola-jadwal-tahfizh', 'pantau-tahfizh-admin', 'lihat-halaqah', 'kelola-halaqah', 'isi-jurnal-tahfizh', 'isi-setoran-tahfizh', 'kelola-rapor-tahfizh'],
            'Computer Based Test (CBT)' => ['kelola-akun-cbt', 'kelola-jadwal-ujian-cbt', 'pantau-ujian-cbt', 'kelola-bank-soal', 'koreksi-hasil-ujian'],
        ];

        // 2. Proses Pengelompokan Data
        $groupedPermissions = [];
        foreach ($mappedGroups as $group => $names) {
            $groupedPermissions[$group] = $permissions->whereIn('name', $names);
        }

        // 3. Tangkap permission yang belum dipetakan (Fallback jika ada penambahan baru di masa depan)
        $mappedNames = collect($mappedGroups)->flatten()->toArray();
        $ungrouped = $permissions->whereNotIn('name', $mappedNames);
        if ($ungrouped->count() > 0) {
            $groupedPermissions['Modul Lainnya'] = $ungrouped;
        }

        // Pass $groupedPermissions ke view
        return view('user.edit-role', compact('user', 'roles', 'groupedPermissions', 'rolePermissions', 'directPermissions'));
    }

    // Fungsi AJAX untuk ganti Role utama
    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|string']);
        
        try {
            // Update role via Spatie
            $user->syncRoles([$request->role]);
            // Update field role di tabel users (jika Anda masih memakainya)
            $user->update(['role' => $request->role]);

            return response()->json([
                'status' => 'success', 
                'message' => 'Role berhasil diubah!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Fungsi AJAX untuk toggle Direct Permission
    public function togglePermission(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|string',
            'state' => 'required|boolean'
        ]);

        try {
            if ($request->state) {
                // Berikan hak akses
                $user->givePermissionTo($request->permission);
            } else {
                // Cabut hak akses
                $user->revokePermissionTo($request->permission);
            }
            
            return response()->json([
                'status' => 'success', 
                'message' => 'Hak akses diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
