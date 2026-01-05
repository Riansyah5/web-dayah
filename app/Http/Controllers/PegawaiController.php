<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Kategori;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawais = Pegawai::all();
        return view('pegawai.pegawai', compact('pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $jabatans = Jabatan::all();
        return view('pegawai.tambah-data', compact('kategoris', 'jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pegawais,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'status_perkawinan' => 'required|in:Menikah,Belum Menikah', // Anda bisa gunakan 'in:Menikah,Belum Menikah...'
            'no_kk' => 'nullable|string|max:16',
            'no_hp' => 'nullable|string|max:20',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'status_pegawai' => 'required|string|max:255',
            'kategori_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'terhitung_mulai_tanggal' => 'required|date',
        ]);

        Pegawai::create($request->all());
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        // Laravel sudah otomatis mencari produk berdasarkan ID di URL.
        // Jika tidak ketemu, Laravel otomatis 404.
        // Variabel $produk sudah berisi data produk yang dicari.

        // Langsung kirim ke view
        return view('pegawai.detail-data-pegawai', compact('pegawai'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        $kategoris = Kategori::all();
        $jabatans = Jabatan::all();
        return view('pegawai.edit-data-pegawai', compact('pegawai', 'kategoris', 'jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pegawais,nik,' . $pegawai->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'status_perkawinan' => 'required|in:Menikah,Belum Menikah', // Anda bisa gunakan 'in:Menikah,Belum Menikah...'
            'no_kk' => 'nullable|string|max:16',
            'no_hp' => 'nullable|string|max:20',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'status_pegawai' => 'required|string|max:255',
            'kategori_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'terhitung_mulai_tanggal' => 'required|date',
        ]);

        $pegawai->update($request->all());
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        // Hapus akun user yang terkait jika ada
        if ($pegawai->user) {
            $pegawai->user->delete();
        }

        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai dan akun terkait berhasil dihapus.');
    }
}
