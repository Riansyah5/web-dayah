<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\RoomHistory;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Exports\StudentsExport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Menampilkan daftar santri.
     */
    public function index(Request $request)
    {
        $students = Student::all();

        return view('students.index', compact('students'));
    }

    /**
     * Menampilkan form tambah santri.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Menyimpan data santri baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // --- Main Biodata ---
            'nis' => 'required|string|unique:students,nis|max:255',
            'nisn' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'child_order' => 'nullable|integer',

            // --- Address ---
            'nik' => 'nullable|string|size:16', // Biasanya NIK 16 digit
            'family_card_number' => 'nullable|string|size:16',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'regency' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',

            // --- Father ---
            'father_name' => 'nullable|string|max:255',
            'father_nik' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_occupation_detail' => 'nullable|string|max:255',
            'father_education' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',

            // --- Mother ---
            'mother_name' => 'nullable|string|max:255',
            'mother_nik' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_occupation_detail' => 'nullable|string|max:255',
            'mother_education' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',

            // --- Guardian ---
            'guardian_name' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_occupation_detail' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',

            // --- Academic ---
            'education_level' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'class_group' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'acceptance_date' => 'nullable|date',
            'accepted_in_grade' => 'nullable|string|max:255',
            'status' => 'required|in:active,graduated,moved,suspended',

            // --- Boarding ---
            'dormitory' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
        ]);

        Student::create($validatedData);

        return redirect()->route('students.index')->with('success', 'Data Santri berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail santri.
     */
    public function show(Student $student)
    {
        $all_rooms = \App\Models\Room::with('dorm')->withCount('assignments')->get();
        return view('students.show', compact('student', 'all_rooms'));
    }

    /**
     * Menampilkan form edit santri.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Mengupdate data santri.
     */
    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            // --- Validasi NIS (Ignore ID saat ini agar tidak error "sudah ada") ---
            'nis' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'nisn' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'child_order' => 'nullable|integer',

            // --- Address ---
            'nik' => 'nullable|string|max:16',
            'family_card_number' => 'nullable|string|max:16',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'regency' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',

            // --- Father ---
            'father_name' => 'nullable|string|max:255',
            'father_nik' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_occupation_detail' => 'nullable|string|max:255',
            'father_education' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',

            // --- Mother ---
            'mother_name' => 'nullable|string|max:255',
            'mother_nik' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_occupation_detail' => 'nullable|string|max:255',
            'mother_education' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',

            // --- Guardian ---
            'guardian_name' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_occupation_detail' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',

            // --- Academic ---
            'education_level' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'class_group' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'acceptance_date' => 'nullable|date',
            'accepted_in_grade' => 'nullable|string|max:255',
            'status' => 'required|in:active,graduated,moved,suspended',

            // --- Boarding ---
            'dormitory' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
        ]);

        $student->update($validatedData);

        return redirect()->route('students.index')->with('success', 'Data Santri berhasil diperbarui.');
    }

    /**
     * Menghapus data santri.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Data Santri berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new StudentImport, $request->file('file'));
            return redirect()->route('students.index')->with('success', 'Data santri berhasil diimpor dari Excel.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            // Bisa redirect kembali dengan pesan error spesifik jika mau
            return redirect()->back()->with('error', 'Gagal Impor. Cek baris: ' . $failures[0]->row() . '. Error: ' . $failures[0]->errors()[0]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan format Excel. Pastikan gunakan Template.');
        }
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'xlsx');
        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $writerType = $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;

        return Excel::download(new StudentsExport, 'data_santri_full.' . $extension, $writerType);
    }

    public function downloadTemplate()
    {
        // Cara sederhana download template tanpa buat file fisik
        // Kita buat file excel on-the-fly berisi header saja
        $headers = ['nis', 'nisn', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'nama_ayah', 'nama_ibu', 'desa', 'kecamatan', 'kabupaten'];

        return response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            // Contoh Data Dummy (Opsional)
            fputcsv($file, ['12345', '00123', 'Ahmad Santri', 'L', 'Surabaya', '2010-05-20', 'Budi', 'Siti', 'Sukolilo', 'Sukolilo', 'Surabaya']);
            fclose($file);
        }, 'template_import_santri.csv');
    }

    public function rooms()
    {
        // Ambil data santri aktif yang memiliki asrama dan kamar
        $dormitories = \App\Models\Student::where('status', 'active')
            ->whereNotNull('dormitory')
            ->whereNotNull('room')
            ->where('dormitory', '!=', '') // Pastikan tidak string kosong
            ->orderBy('dormitory')
            ->orderBy('room') // Urutkan nomor kamar
            ->get()
            ->groupBy(['dormitory', 'room']);
        // Hasil grouping: ['Asrama A' => ['Kamar 1' => [Student1, Student2], 'Kamar 2' => ...]]

        return view('students.rooms', compact('dormitories'));
    }

    public function moveRoom(Request $request, Student $student)
    {
        $request->validate([
            'new_room_id' => 'required|exists:rooms,id',
            'move_date'   => 'required|date',
            'reason'      => 'required|string'
        ]);

        // Cari assignment aktif untuk santri ini
        // Kita gunakan relasi currentRoomAssignment yang ada di model Student
        $assignment = $student->currentRoomAssignment;

        if (!$assignment) {
            return back()->with('error', 'Santri belum memiliki penempatan kamar aktif. Silakan lakukan penempatan awal.');
        }

        // Cek apakah kamar baru sama dengan kamar lama
        if ($assignment->room_id == $request->new_room_id) {
            return back()->with('error', 'Santri sudah berada di kamar tersebut.');
        }

        // Set properti sementara agar bisa dibaca oleh Model Event (RoomAssignment)
        $assignment->move_reason = $request->reason;
        $assignment->move_date = $request->move_date;

        // Update room_id (Ini akan memicu event 'updated' di model RoomAssignment)
        $assignment->update(['room_id' => $request->new_room_id]);

        return back()->with('success', 'Santri berhasil dipindahkan.');
    }
}
