<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class PegawaiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Helper untuk konversi tanggal excel
        $tglLahir = $this->transformDate($row['tanggal_lahir']);
        $tmt = $this->transformDate($row['terhitung_mulai_tanggal']);

        return new Pegawai([
            'nik'               => $row['nik'],
            'nama'              => $row['nama'],
            'jenis_kelamin'     => $row['jenis_kelamin'],
            'status_perkawinan' => $row['status_perkawinan'],
            'tempat_lahir'      => $row['tempat_lahir'],
            'tanggal_lahir'     => $tglLahir,
            'no_kk'             => $row['no_kk'],
            'no_hp'             => $row['no_hp'],
            'desa'              => $row['desa'],
            'kecamatan'         => $row['kecamatan'],
            'kabupaten'         => $row['kabupaten'],
            'provinsi'          => $row['provinsi'],
            'kategori_pegawai'  => $row['kategori_pegawai'],
            'status_pegawai'    => $row['status_pegawai'],
            'jabatan'           => $row['jabatan'],
            'terhitung_mulai_tanggal' => $tmt,
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|unique:pegawais,nik',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'kategori_pegawai' => 'required',
            'jabatan' => 'required',
        ];
    }

    private function transformDate($value)
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
