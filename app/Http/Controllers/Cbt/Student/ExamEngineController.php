<?php

namespace App\Http\Controllers\Cbt\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamEngineController extends Controller
{
    // 1. DASHBOARD CBT SANTRI
    public function dashboard()
    {
        $now = Carbon::now();
        $accountId = Auth::guard('cbt')->user()->id;
        $student = Auth::guard('cbt')->user()->student;

        $availableExams = CbtExam::where('is_active', true)
                            ->where('start_time', '<=', $now)
                            ->where('end_time', '>=', $now)
                            ->get();

        $myExams = CbtStudentExam::where('cbt_account_id', $accountId)->get()->keyBy('cbt_exam_id');

        return view('cbt.student.dashboard', compact('availableExams', 'myExams', 'student'));
    }

    // 2. VERIFIKASI TOKEN & MULAI UJIAN
    public function startExam(Request $request, CbtExam $exam)
    {
        $request->validate(['token' => 'required|string']);

        if (strtoupper($request->token) !== $exam->token) {
            return back()->with('error', 'Token Ujian tidak valid atau sudah diganti pengawas.');
        }

        $accountId = Auth::guard('cbt')->user()->id;

        $studentExam = CbtStudentExam::where('cbt_account_id', $accountId)
                                     ->where('cbt_exam_id', $exam->id)
                                     ->first();

        if (!$studentExam) {
            $studentExam = CbtStudentExam::create([
                'cbt_account_id' => $accountId,
                'cbt_exam_id' => $exam->id,
                'started_at' => Carbon::now(),
                'status' => 'working'
            ]);

            $questions = $exam->questionBank->questions;
            if ($exam->randomize_questions) $questions = $questions->shuffle();

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
        
        if ($studentExam->cbt_account_id !== Auth::guard('cbt')->user()->id) {
            abort(403);
        }

        if ($studentExam->status == 'finished') {
            return redirect()->route('cbt.dashboard')->with('success', 'Anda sudah menyelesaikan ujian ini.');
        }

        $now = Carbon::now();
        $lastActive = $studentExam->last_active_at ? Carbon::parse($studentExam->last_active_at) : Carbon::parse($studentExam->started_at);
        
        // Memaksa hasil selisih waktu menjadi Integer Positif Mutlak
        $offlineSeconds = abs((int) $now->diffInSeconds($lastActive));

        // ==== OPSI NUKLIR: BYPASS ELOQUENT ====
        if ($offlineSeconds > 60) {
            $newStartedAt = Carbon::parse($studentExam->started_at)->addSeconds($offlineSeconds);
            
            DB::table('cbt_student_exams')->where('id', $studentExam->id)->update([
                'started_at' => $newStartedAt->format('Y-m-d H:i:s'),
                'last_active_at' => $now->format('Y-m-d H:i:s')
            ]);
            
            $studentExam = $studentExam->fresh(); 
        } else {
            DB::table('cbt_student_exams')->where('id', $studentExam->id)->update([
                'last_active_at' => $now->format('Y-m-d H:i:s')
            ]);
        }

        $timeLimitByDuration = Carbon::parse($studentExam->started_at)->addMinutes($studentExam->exam->duration);
        $timeLimitBySchedule = Carbon::parse($studentExam->exam->end_time);
        
        $deadline = $timeLimitByDuration->lessThan($timeLimitBySchedule) ? $timeLimitByDuration : $timeLimitBySchedule;
        $remainingSeconds = $now->diffInSeconds($deadline, false);

        if ($remainingSeconds <= 0) return $this->finishExam($studentExam->id);

        $page = $request->query('no', 1);
        $totalQuestions = $studentExam->answers->count();
        $currentAnswer = $studentExam->answers->where('question_order', $page)->first();

        if ($currentAnswer && $currentAnswer->question->type == 'multiple_choice' && $studentExam->exam->randomize_options) {
            $currentAnswer->question->setRelation('options', $currentAnswer->question->options->shuffle());
        }

        return view('cbt.student.engine', compact('studentExam', 'currentAnswer', 'totalQuestions', 'page', 'remainingSeconds'));
    }

    // 4. AUTOSAVE SAAT SISWA MEMILIH JAWABAN
    public function autosave(Request $request, $answerId)
    {
        $answer = CbtStudentAnswer::findOrFail($answerId);
        $exam = CbtStudentExam::findOrFail($answer->cbt_student_exam_id);

        if ($exam->status == 'finished') return response()->json(['status' => 'error', 'msg' => 'Ujian telah ditutup']);

        $now = Carbon::now();
        $lastActive = $exam->last_active_at ? Carbon::parse($exam->last_active_at) : Carbon::parse($exam->started_at);
        
        // Memaksa hasil selisih waktu menjadi Integer Positif Mutlak
        $offlineSeconds = abs((int) $now->diffInSeconds($lastActive));

        // ==== OPSI NUKLIR: BYPASS ELOQUENT ====
        if ($offlineSeconds > 60) {
            $newStartedAt = Carbon::parse($exam->started_at)->addSeconds($offlineSeconds);
            DB::table('cbt_student_exams')->where('id', $exam->id)->update([
                'started_at' => $newStartedAt->format('Y-m-d H:i:s'),
                'last_active_at' => $now->format('Y-m-d H:i:s')
            ]);
        } else {
            DB::table('cbt_student_exams')->where('id', $exam->id)->update([
                'last_active_at' => $now->format('Y-m-d H:i:s')
            ]);
        }

        if ($request->has('option_id')) $answer->cbt_option_id = $request->option_id;
        elseif ($request->has('essay_text')) $answer->essay_answer = $request->essay_text;
        $answer->save();

        return response()->json(['status' => 'success']);
    }

    // 5. SELESAI UJIAN (TOMBOL KUMPULKAN)
    public function finishExam($studentExamId)
    {
        $studentExam = CbtStudentExam::with('answers.question')->findOrFail($studentExamId);
        $totalScore = 0; $maxScore = 0;

        foreach ($studentExam->answers as $ans) {
            $maxScore += $ans->question->score_weight;
            if ($ans->question->type == 'multiple_choice' && $ans->cbt_option_id) {
                $option = \App\Models\CbtOption::find($ans->cbt_option_id);
                if ($option && $option->is_correct) {
                    $ans->update(['is_correct' => true]);
                    $totalScore += $ans->question->score_weight;
                } else {
                    $ans->update(['is_correct' => false]);
                }
            }
        }

        $finalScore = ($maxScore > 0) ? ($totalScore / $maxScore) * 100 : 0;
        $studentExam->update([
            'status' => 'finished',
            'finished_at' => Carbon::now(),
            'score' => $finalScore
        ]);

        return redirect()->route('cbt.dashboard')->with('success', 'Alhamdulillah, ujian telah selesai dan jawaban Anda berhasil dikirim.');
    }

    // 6. HEARTBEAT DARI BROWSER SANTRI
    public function heartbeat($studentExamId)
    {
        $studentExam = CbtStudentExam::with('exam')
            ->where('id', $studentExamId)
            ->where('cbt_account_id', Auth::guard('cbt')->user()->id)
            ->first();

        if ($studentExam && $studentExam->status == 'working') {
            $message = $studentExam->warning_message;
            if ($message) DB::table('cbt_student_exams')->where('id', $studentExamId)->update(['warning_message' => null]);

            $now = Carbon::now();
            $lastActive = $studentExam->last_active_at ? Carbon::parse($studentExam->last_active_at) : Carbon::parse($studentExam->started_at);
            
            // Memaksa hasil selisih waktu menjadi Integer Positif Mutlak
            $offlineSeconds = abs((int) $now->diffInSeconds($lastActive));

            // ==== OPSI NUKLIR: BYPASS ELOQUENT ====
            if ($offlineSeconds > 60) {
                $newStartedAt = Carbon::parse($studentExam->started_at)->addSeconds($offlineSeconds);
                DB::table('cbt_student_exams')->where('id', $studentExam->id)->update([
                    'started_at' => $newStartedAt->format('Y-m-d H:i:s'),
                    'last_active_at' => $now->format('Y-m-d H:i:s')
                ]);
                $studentExam = $studentExam->fresh();
            } else {
                DB::table('cbt_student_exams')->where('id', $studentExam->id)->update([
                    'last_active_at' => $now->format('Y-m-d H:i:s')
                ]);
            }

            $timeLimitByDuration = Carbon::parse($studentExam->started_at)->addMinutes($studentExam->exam->duration);
            $timeLimitBySchedule = Carbon::parse($studentExam->exam->end_time);
            $deadline = $timeLimitByDuration->lessThan($timeLimitBySchedule) ? $timeLimitByDuration : $timeLimitBySchedule;

            if ($now->greaterThanOrEqualTo($deadline)) {
                return response()->json(['status' => 'dead_or_finished', 'msg' => 'Waktu ujian telah habis']);
            }

            return response()->json(['status' => 'alive', 'warning_message' => $message]);
        }
        return response()->json(['status' => 'dead_or_finished']);
    }
}