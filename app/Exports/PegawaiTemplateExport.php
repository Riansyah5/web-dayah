<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PegawaiTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    public function array(): array
    {
        return [
            [
                '1108010101900001',
                'Budi Santoso',
                'Laki-laki',
                'Menikah',
                'Lhokseumawe',
                Date::dateTimeToExcel(new \DateTime('1990-01-01')),
                '1108010101950002',
                '081234567890',
                'Uteun Bayi',
                'Banda Sakti',
                'Kota Lhokseumawe',
                'Aceh',
                'TETAP',
                'Aktif',
                'Guru Matematika',
                Date::dateTimeToExcel(new \DateTime('2015-07-15')),
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'nik',
            'nama',
            'jenis_kelamin', // Laki-laki / Perempuan
            'status_perkawinan', // Menikah / Belum Menikah
            'tempat_lahir',
            'tanggal_lahir', // Format: YYYY-MM-DD (Contoh: 1990-12-31)
            'no_kk',
            'no_hp',
            'desa',
            'kecamatan',
            'kabupaten',
            'provinsi',
            'kategori_pegawai', // Sesuai data master kategori
            'status_pegawai', // Aktif, Non-Aktif, Cuti, Keluar
            'jabatan', // Sesuai data master jabatan
            'terhitung_mulai_tanggal', // Format: YYYY-MM-DD
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // nik
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD, // tanggal_lahir
            'G' => NumberFormat::FORMAT_TEXT, // no_kk
            'H' => NumberFormat::FORMAT_TEXT, // no_hp
            'P' => NumberFormat::FORMAT_DATE_YYYYMMDD, // terhitung_mulai_tanggal
        ];
    }
}
