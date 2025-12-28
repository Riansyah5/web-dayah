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



class StudentTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
  public function array(): array
  {
    // Data contoh (dummy) agar pengguna paham format isian
    return [
      [
        '12345', 
        '0012345678', 
        'Ahmad Santri', 
        'L', 
        'Surabaya', 
        Date::dateTimeToExcel(new \DateTime('2010-05-20')), 
        'Budi', 
        'Siti', 
        'Sukolilo', 
        'Sukolilo', 
        'Surabaya'
        ]
    ];
  }

  public function headings(): array
  {
    return [
      'nis',
      'nisn',
      'nama_lengkap',
      'jenis_kelamin',
      'tempat_lahir',
      'tanggal_lahir',
      'nama_ayah',
      'nama_ibu',
      'desa',
      'kecamatan',
      'kabupaten'
    ];
  }

  public function styles(Worksheet $sheet)
  {
    // Membuat baris pertama (Header) menjadi Bold
    return [
      1 => ['font' => ['bold' => true]],
    ];
  }

  public function columnFormats(): array
  {
    return [
      'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
    ];
  }
}
