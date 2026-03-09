<?php

namespace App\Http\Controllers\Cbt\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    // 1. Tampilkan daftar jadwal ujian milik guru ini
    public function index()
    {
        $user = Auth::user();
        $teacher = Teacher::where('name', $user->name)->first();

        if (!$teacher && !in_array($user->role, ['Admin', 'Superadmin'])) {
            abort(403, 'Akun Anda tidak terhubung dengan data guru.');
        }
        
        $query = CbtExam::with('questionBank')
                    ->withCount('studentExams') // Hitung jumlah santri yang mengerjakan
                    ->orderBy('start_time', 'desc');

        if (!in_array($user->role, ['Admin', 'Superadmin'])) {
            $query->whereHas('questionBank', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            });
        }

        $exams = $query->get();

        return view('cbt.teacher.results.index', compact('exams'));
    }

    // 2. Tampilkan daftar santri yang mengerjakan ujian tertentu
    public function show(CbtExam $exam)
    {
        $user = Auth::user();
        $teacher = Teacher::where('name', $user->name)->first();

        if (!$teacher && !in_array($user->role, ['Admin', 'Superadmin'])) {
            abort(403, 'Akun Anda tidak terhubung dengan data guru.');
        }

        // Pastikan ujian ini milik guru yang bersangkutan
        if (!in_array($user->role, ['Admin', 'Superadmin'])) {
            if ($exam->questionBank->teacher_id !== $teacher->id) {
                abort(403);
            }
        }

        $studentExams = CbtStudentExam::where('cbt_exam_id', $exam->id)
                        ->with('cbtAccount.student')
                        ->orderBy('score', 'desc')
                        ->get();

        return view('cbt.teacher.results.show', compact('exam', 'studentExams'));
    }

    // 3. Form Koreksi Essay Santri
    public function correct($studentExamId)
    {
        $studentExam = CbtStudentExam::with(['exam.questionBank', 'cbtAccount.student', 'answers.question'])->findOrFail($studentExamId);
        
        // Ambil HANYA jawaban essay
        $essayAnswers = $studentExam->answers->filter(function($ans) {
            return $ans->question->type == 'essay';
        });

        // Hitung nilai PG saat ini (sebagai info untuk guru)
        $pgAnswers = $studentExam->answers->filter(function($ans) {
            return $ans->question->type == 'multiple_choice' && $ans->is_correct;
        });
        
        $pgScore = $pgAnswers->sum(function($ans) { return $ans->question->score_weight; });

        return view('cbt.teacher.results.correct', compact('studentExam', 'essayAnswers', 'pgScore'));
    }

    // 4. Simpan Nilai Essay & Rekalkulasi Nilai Akhir
    public function storeCorrection(Request $request, $studentExamId)
    {
        $studentExam = CbtStudentExam::with('answers.question')->findOrFail($studentExamId);

        DB::transaction(function() use ($request, $studentExam) {
            
            $totalEarnedPoints = 0;
            $maxPossiblePoints = 0;

            // Loop semua jawaban untuk menghitung nilai akhir
            foreach ($studentExam->answers as $ans) {
                $maxPossiblePoints += $ans->question->score_weight;

                if ($ans->question->type == 'multiple_choice') {
                    // Pilihan Ganda: Ambil skor jika benar
                    if ($ans->is_correct) {
                        $totalEarnedPoints += $ans->question->score_weight;
                        $ans->update(['score' => $ans->question->score_weight]);
                    } else {
                        $ans->update(['score' => 0]);
                    }
                } 
                elseif ($ans->question->type == 'essay') {
                    // Essay: Ambil skor dari form input Guru
                    $inputScore = $request->scores[$ans->id] ?? 0;
                    
                    // Pastikan guru tidak kasih nilai melebihi bobot maksimal soal tersebut
                    $inputScore = min($inputScore, $ans->question->score_weight);
                    
                    $totalEarnedPoints += $inputScore;
                    $ans->update(['score' => $inputScore, 'is_correct' => ($inputScore > 0)]);
                }
            }

            // Hitung Nilai Akhir (Skala 100)
            $finalScore = ($maxPossiblePoints > 0) ? ($totalEarnedPoints / $maxPossiblePoints) * 100 : 0;

            // Update Lembar Ujian Santri
            $studentExam->update([
                'score' => $finalScore,
                // Status finished dipastikan agar nilai fix
                'status' => 'finished' 
            ]);
        });

        return redirect()->route('teacher.cbt.results.show', $studentExam->cbt_exam_id)
                         ->with('success', 'Koreksi essay berhasil disimpan. Nilai akhir santri telah diperbarui.');
    }
}