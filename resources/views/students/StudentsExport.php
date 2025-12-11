<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::all();
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->nis,
            $student->nisn,
            $student->name,
            $student->gender,
            $student->birth_place,
            $student->birth_date ? $student->birth_date->format('Y-m-d') : null,
            $student->child_order,
            $student->nik,
            $student->family_card_number,
            $student->village,
            $student->district,
            $student->regency,
            $student->province,
            $student->father_name,
            $student->father_nik,
            $student->father_occupation,
            $student->father_occupation_detail,
            $student->father_education,
            $student->father_phone,
            $student->mother_name,
            $student->mother_nik,
            $student->mother_occupation,
            $student->mother_occupation_detail,
            $student->mother_education,
            $student->mother_phone,
            $student->guardian_name,
            $student->guardian_occupation,
            $student->guardian_occupation_detail,
            $student->guardian_phone,
            $student->education_level,
            $student->major,
            $student->class_group,
            $student->previous_school,
            $student->acceptance_date ? $student->acceptance_date->format('Y-m-d') : null,
            $student->accepted_in_grade,
            $student->status,
            $student->dormitory,
            $student->room,
            $student->created_at,
            $student->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIS',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Anak Ke',
            'NIK',
            'No KK',
            'Desa',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
            'Nama Ayah',
            'NIK Ayah',
            'Pekerjaan Ayah',
            'Detail Pekerjaan Ayah',
            'Pendidikan Ayah',
            'No HP Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Pekerjaan Ibu',
            'Detail Pekerjaan Ibu',
            'Pendidikan Ibu',
            'No HP Ibu',
            'Nama Wali',
            'Pekerjaan Wali',
            'Detail Pekerjaan Wali',
            'No HP Wali',
            'Jenjang Pendidikan',
            'Jurusan',
            'Rombel/Kelas',
            'Sekolah Asal',
            'Tanggal Masuk',
            'Diterima di Kelas',
            'Status',
            'Asrama',
            'Kamar',
            'Created At',
            'Updated At',
        ];
    }
}
