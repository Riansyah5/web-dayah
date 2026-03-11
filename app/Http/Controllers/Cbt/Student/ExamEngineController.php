<?php

namespace App\Http\Controllers\Cbt\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamEngineController extends Controller
{
    // 1. DASHBOARD CBT SANTRI
    public function dashboard()
    {
        $now = Carbon::now();
        $accountId = Auth::guard('cbt')->user()->id;

        // Cari ujian yang sedang aktif saat ini
        $availableExams = CbtExam::where('is_active', true)
                            ->where('start_time', '<=', $now)
                            ->where('end_time', '>=', $now)
                            ->get();

        // Cari ujian yang sudah/sedang dikerjakan santri ini
        $myExams = CbtStudentExam::where('cbt_account_id', $accountId)->get()->keyBy('cbt_exam_id');

        return view('cbt.student.dashboard', compact('availableExams', 'myExams'));
    }

    // 2. VERIFIKASI TOKEN & MULAI UJIAN
    public function startExam(Request $request, CbtExam $exam)
    {
        $request->validate(['token' => 'required|string']);

        if (strtoupper($request->token) !== $exam->token) {
            return back()->with('error', 'Token Ujian tidak valid atau sudah diganti pengawas.');
        }

        $accountId = Auth::guard('cbt')->user()->id;

        // Cek apakah sudah pernah klik mulai sebelumnya
        $studentExam = CbtStudentExam::where('cbt_account_id', $accountId)
                                     ->where('cbt_exam_id', $exam->id)
                                     ->first();

        // Jika Belum Pernah Sama Sekali -> Inisialisasi Soal
        if (!$studentExam) {
            $studentExam = CbtStudentExam::create([
                'cbt_account_id' => $accountId,
                'cbt_exam_id' => $exam->id,
                'started_at' => Carbon::now(),
                'status' => 'working'
            ]);

            // Ambil pertanyaan dari bank soal
            $questions = $exam->questionBank->questions;
            
            // Acak urutan jika diset di pengaturan
            if ($exam->randomize_questions) {
                $questions = $questions->shuffle();
            }

            // Simpan ke lembar jawaban (Kosong)
            $order = 1;
            foreach ($questions as $q) {
                CbtStudentAnswer::create([
                    'cbt_student_exam_id' => $studentExam->id,
                    'cbt_question_id' => $q->id,
                    'question_order' => $order++
                ]);
            }
        }

        return redirect()->route('cbt.engine.show', $studentExam->id);
    }

    // 3. TAMPILAN MESIN UJIAN (LAYAR PENGERJAAN)
    public function showEngine($studentExamId, Request $request)
    {
        $studentExam = CbtStudentExam::with(['exam.questionBank', 'answers.question.options'])->findOrFail($studentExamId);
        
        // Keamanan: Pastikan ini milik dia
        if ($studentExam->cbt_account_id !== Auth::guard('cbt')->user()->id) {
            abort(403);
        }

        // Redirect jika sudah selesai
        if ($studentExam->status == 'finished') {
            return redirect()->route('cbt.dashboard')->with('success', 'Anda sudah menyelesaikan ujian ini.');
        }

        // Kalkulasi Sisa Waktu (Durasi habis atau Waktu End_time habis, ambil yang paling cepat)
        $timeLimitByDuration = $studentExam->started_at->copy()->addMinutes($studentExam->exam->duration);
        $timeLimitBySchedule = Carbon::parse($studentExam->exam->end_time);
        
        $deadline = $timeLimitByDuration->lessThan($timeLimitBySchedule) ? $timeLimitByDuration : $timeLimitBySchedule;
        
        $now = Carbon::now();
        $remainingSeconds = $now->diffInSeconds($deadline, false);

        // Jika waktu habis, paksa selesai
        if ($remainingSeconds <= 0) {
            return $this->finishExam($studentExam->id);
        }

        // Pagination Manual (1 Soal per Halaman)
        $page = $request->query('no', 1);
        $totalQuestions = $studentExam->answers->count();
        $currentAnswer = $studentExam->answers->where('question_order', $page)->first();

        // Jika opsi harus diacak (A,B,C,D nya diputar)
        if ($currentAnswer && $currentAnswer->question->type == 'multiple_choice' && $studentExam->exam->randomize_options) {
            // Kita shuffle options-nya di level collection, tidak merubah database opsi asli
            $currentAnswer->question->setRelation('options', $currentAnswer->question->options->shuffle());
        }

        return view('cbt.student.engine', compact('studentExam', 'currentAnswer', 'totalQuestions', 'page', 'remainingSeconds'));
    }

    // [UPDATE] Pastikan autosave juga memperbarui last_active_at
    public function autosave(Request $request, $answerId)
    {
        $answer = CbtStudentAnswer::findOrFail($answerId);
        $exam = CbtStudentExam::findOrFail($answer->cbt_student_exam_id);

        if ($exam->status == 'finished') return response()->json(['status' => 'error', 'msg' => 'Ujian telah ditutup']);

        if ($request->has('option_id')) {
            $answer->cbt_option_id = $request->option_id;
        } elseif ($request->has('essay_text')) {
            $answer->essay_answer = $request->essay_text;
        }
        $answer->save();

        // Update detak jantung karena santri beraktivitas
        $exam->update(['last_active_at' => Carbon::now()]);

        return response()->json(['status' => 'success']);
    }

    // 5. SELESAI UJIAN (TOMBOL KUMPULKAN)
    public function finishExam($studentExamId)
    {
        $studentExam = CbtStudentExam::with('answers.question')->findOrFail($studentExamId);
        
        // Cek Pilihan Ganda dan Hitung Skor (Essay butuh guru)
        $totalScore = 0;
        $maxScore = 0;

        foreach ($studentExam->answers as $ans) {
            $maxScore += $ans->question->score_weight;

            if ($ans->question->type == 'multiple_choice' && $ans->cbt_option_id) {
                // Cari opsi yang dipilih
                $option = \App\Models\CbtOption::find($ans->cbt_option_id);
                if ($option && $option->is_correct) {
                    $ans->update(['is_correct' => true]);
                    $totalScore += $ans->question->score_weight;
                } else {
                    $ans->update(['is_correct' => false]);
                }
            }
        }

        // Hitung Nilai Akhir (Skala 100) -> Khusus PG. Jika ada essay, ini nilai sementara.
        $finalScore = ($maxScore > 0) ? ($totalScore / $maxScore) * 100 : 0;

        $studentExam->update([
            'status' => 'finished',
            'finished_at' => Carbon::now(),
            'score' => $finalScore
        ]);

        return redirect()->route('cbt.dashboard')->with('success', 'Alhamdulillah, ujian telah selesai dan jawaban Anda berhasil dikirim.');
    }

    // [BARU] Menerima sinyal detak jantung dari browser santri
    public function heartbeat($studentExamId)
    {
        $studentExam = CbtStudentExam::find($studentExamId);
        if ($studentExam && $studentExam->status == 'working') {
            $studentExam->update(['last_active_at' => Carbon::now()]);
            return response()->json(['status' => 'alive']);
        }
        return response()->json(['status' => 'dead_or_finished']);
    }
}