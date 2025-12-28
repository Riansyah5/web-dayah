<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Agar baris 1 dianggap Header
use Maatwebsite\Excel\Concerns\WithValidation; // Untuk validasi baris Excel
use PhpOffice\PhpSpreadsheet\Shared\Date; // Untuk convert tanggal Excel
use Carbon\Carbon;

class StudentImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Mapping data dari Excel ke Database
     */
    public function model(array $row)
    {
        // Normalisasi Gender (jika di excel tulis Laki-laki jadi L)
        $gender = strtoupper(substr($row['jenis_kelamin'], 0, 1));

        return new Student([
            // ID otomatis di-generate oleh Model (ULID)

            // Biodata
            'nis'         => $row['nis'],
            'nisn'        => $row['nisn'] ?? null,
            'name'        => $row['nama_lengkap'],
            'gender'      => $gender,
            'birth_place' => $row['tempat_lahir'],

            // Handle Tanggal Excel (Excel menyimpan tanggal sebagai angka)
            // 'birth_date'  => isset($row['tanggal_lahir']) ? Date::excelToDateTimeObject($row['tanggal_lahir']) : null,
            'tanggal_lahir' => $row['tanggal_lahir'],
            'birth_date' => isset($row['tanggal_lahir'])
                ? (
                    is_numeric($row['tanggal_lahir'])
                    ? Date::excelToDateTimeObject($row['tanggal_lahir'])
                    : Carbon::parse($row['tanggal_lahir'])
                )
                : null,
            // Orang Tua
            'father_name' => $row['nama_ayah'] ?? null,
            'mother_name' => $row['nama_ibu'] ?? null,

            // Alamat
            'village'     => $row['desa'] ?? null,
            'district'    => $row['kecamatan'] ?? null,
            'regency'     => $row['kabupaten'] ?? null,

            // Default Value
            'status'      => 'active',
        ]);
    }

    /**
     * Rules Validasi per baris Excel
     */
    public function rules(): array
    {
        return [
            'nis' => 'required|unique:students,nis',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
        ];
    }
}
