<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    // Halaman Setting Silabus per Mapel
    public function index(Subject $subject)
    {
        // Ambil Level yang diurutkan per Jenjang (SD -> SMP -> SMA)
        $levels = Level::with('stage')
            ->orderBy('stage_id')
            ->orderBy('alias')
            ->get();

        // Ambil data silabus yang sudah tersimpan untuk mapel ini
        // Kita group by level_id biar mudah diakses di View
        $existingSyllabi = Syllabus::where('subject_id', $subject->id)
            ->get()
            ->groupBy('level_id');

        return view('academic.syllabus.index', compact('subject', 'levels', 'existingSyllabi'));
    }

    // Simpan Data
    public function store(Request $request, Subject $subject)
    {
        // Format Input: syllabus[level_id][Ganjil] = "Materi..."
        $data = $request->input('syllabus');

        foreach ($data as $levelId => $semesters) {
            foreach ($semesters as $semesterName => $topic) {

                // Jika kosong, hapus data lama (reset)
                if (trim($topic) == '') {
                    Syllabus::where([
                        'subject_id' => $subject->id,
                        'level_id' => $levelId,
                        'semester' => $semesterName
                    ])->delete();
                    continue;
                }

                // Update atau Create baru
                Syllabus::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'level_id' => $levelId,
                        'semester' => $semesterName
                    ],
                    [
                        'topics' => $topic
                    ]
                );
            }
        }

        return back()->with('success', 'Data Silabus/Materi berhasil disimpan.');
    }
}
