<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    protected Builder $query;
    protected int $rowNumber = 0;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'Tahun Ajaran',
            'Jenjang Tujuan',
            'Nama Lengkap',
            'L/P',
            'NIK',
            'NISN',
            'No. KK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Anak Ke',
            'Asal Sekolah',
            'Desa / Kelurahan',
            'Kecamatan',
            'Kabupaten / Kota',
            'Provinsi',
            'Nama Ayah',
            'No. HP Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'No. HP Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'No. HP Wali',
            'Pengirim Transfer',
            'Bank',
            'Nominal Transfer',
            'Tanggal Transfer',
            'Status Pembayaran',
            'Status Pendaftaran',
            'Tanggal Daftar',
        ];
    }

    public function map($registration): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $registration->registration_number,
            $registration->academicYear?->name ?? '-',
            $registration->target_stage,
            $registration->name,
            $registration->gender,
            $registration->nik ? (string) $registration->nik : '-',
            $registration->nisn ? (string) $registration->nisn : '-',
            $registration->family_card_number ? (string) $registration->family_card_number : '-',
            $registration->birth_place,
            $registration->birth_date ? $registration->birth_date->format('Y-m-d') : '-',
            $registration->child_order ?? '-',
            $registration->previous_school ?? '-',
            $registration->village ?? '-',
            $registration->district ?? '-',
            $registration->regency ?? '-',
            $registration->province ?? '-',
            $registration->father_name ?? '-',
            $registration->father_phone ? (string) $registration->father_phone : '-',
            $registration->father_occupation ?? '-',
            $registration->mother_name ?? '-',
            $registration->mother_phone ? (string) $registration->mother_phone : '-',
            $registration->mother_occupation ?? '-',
            $registration->guardian_name ?? '-',
            $registration->guardian_phone ? (string) $registration->guardian_phone : '-',
            $registration->payment_sender_name ?? '-',
            $registration->payment_bank_name ?? '-',
            $registration->payment_amount ?? 0,
            $registration->payment_date ? $registration->payment_date->format('Y-m-d') : '-',
            $registration->payment_status_label,
            $registration->status_label,
            $registration->created_at ? $registration->created_at->format('Y-m-d H:i') : '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_TEXT, // NIK
            'H' => NumberFormat::FORMAT_TEXT, // NISN
            'I' => NumberFormat::FORMAT_TEXT, // No. KK
            'S' => NumberFormat::FORMAT_TEXT, // No. HP Ayah
            'V' => NumberFormat::FORMAT_TEXT, // No. HP Ibu
            'Y' => NumberFormat::FORMAT_TEXT, // No. HP Wali
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
