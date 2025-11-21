<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = ['nik', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_perkawinan', 'no_kk', 'no_hp', 'desa', 'kecamatan', 'kabupaten', 'provinsi', 'status_pegawai', 'jabatan', 'terhitung_mulai_tanggal'];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'terhitung_mulai_tanggal' => 'date',
    ];
}
