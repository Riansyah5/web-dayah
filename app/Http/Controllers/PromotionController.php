<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    /**
     * Halaman Form Migrasi / Kenaikan Kelas
     */
    public function index()
    {
        $years = AcademicYear::orderBy('name', 'desc')->get();
        return view('academic.promotion.index', compact('years'));
    }

    /**
     * Proses Migrasi / Kenaikan Kelas
     */
    public function process(Request $request)
    {
        $request->validate([
            'from_year_id' => 'required|exists:academic_years,id',
            'to_year_id'   => 'required|exists:academic_years,id|different:from_year_id',
            'type'         => 'required|in:copy,promote',
        ]);

        $fromYear = AcademicYear::findOrFail($request->from_year_id);
        $toYear   = AcademicYear::findOrFail($request->to_year_id);

        // Ambil seluruh kelas tahun asal beserta siswa & level
        $oldClasses = Classroom::with(['students', 'level'])
            ->where('academic_year_id', $fromYear->id)
            ->get();

        if ($oldClasses->isEmpty()) {
            return back()->with('error', 'Tidak ada data kelas pada tahun ajaran sumber.');
        }

        DB::beginTransaction();

        try {
            $countClasses  = 0;
            $countStudents = 0;

            foreach ($oldClasses as $oldClass) {

                $newLevelId = $oldClass->level_id;
                $newName    = $oldClass->name;

                /**
                 * MODE PROMOTE (Naik Tingkat)
                 */
                if ($request->type === 'promote') {

                    $currentAlias = (int) $oldClass->level->alias;

                    $nextLevel = Level::where('stage_id', $oldClass->level->stage_id)
                        ->where('alias', (string) ($currentAlias + 1))
                        ->first();

                    // Jika tidak ada level lanjutan (kelas akhir → lulus)
                    if (!$nextLevel) {
                        foreach ($oldClass->students as $student) {
                            $student->update(['status' => 'graduated']);
                            $countStudents++;
                        }
                        continue;
                    }

                    $newLevelId = $nextLevel->id;
                    $newName    = str_replace(
                        $oldClass->level->alias,
                        $nextLevel->alias,
                        $oldClass->name
                    );
                }

                /**
                 * 1. Buat / Ambil Kelas Baru di Tahun Tujuan
                 */
                $newClass = Classroom::firstOrCreate(
                    [
                        'academic_year_id' => $toYear->id,
                        'name'             => $newName,
                        'level_id'         => $newLevelId,
                        'major_id'         => $oldClass->major_id,
                    ],
                    [
                        'homeroom_teacher' => $oldClass->homeroom_teacher,
                        'capacity'         => $oldClass->capacity,
                    ]
                );

                $countClasses++;

                /**
                 * 2. Salin / Pindahkan Siswa (ULID Pivot Safe)
                 */
                foreach ($oldClass->students as $student) {

                    $exists = DB::table('classroom_student')
                        ->where('classroom_id', $newClass->id)
                        ->where('student_id', $student->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('classroom_student')->insert([
                            'id'            => (string) Str::ulid(),
                            'classroom_id'  => $newClass->id,
                            'student_id'    => $student->id,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);

                        // Update string kelas siswa (jika promote / tahun aktif)
                        if ($request->type === 'promote' || $toYear->is_active) {
                            $student->update([
                                'class_group' => $newClass->name,
                            ]);
                        }

                        $countStudents++;
                    }
                }
            }

            DB::commit();

            return back()->with(
                'success',
                "Sukses! {$countClasses} kelas berhasil dibuat dan {$countStudents} siswa berhasil diproses."
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }
}
