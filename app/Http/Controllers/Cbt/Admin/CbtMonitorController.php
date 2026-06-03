<?php

namespace App\Http\Controllers\Cbt\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CbtMonitorController extends Controller
{
    // 1. Tampilkan Halaman HTML Kosong (Kerangkanya saja)
    public function index(CbtExam $exam)
    {
        return view('cbt.admin.exams.monitor', compact('exam'));
    }

    // 2. API Endpoint: Menyuplai Data JSON ke Halaman Monitoring
    public function apiData(CbtExam $exam)
    {
        $startTime = microtime(true);

        $studentExams = CbtStudentExam::with(['cbtAccount.student', 'answers'])
            ->where('cbt_exam_id', $exam->id)
            ->get();

        $data = [];
        $stats = [
            'total' => $studentExams->count(), 
            'online' => 0, 
            'offline' => 0, 
            'finished' => 0
        ];

        $now = Carbon::now();

        foreach ($studentExams as $se) {
            $answeredCount = $se->answers->whereNotNull('cbt_option_id')->count() + $se->answers->whereNotNull('essay_answer')->count();
            $totalQuestions = $se->answers->count();
            $progress = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0;

            $isOffline = false;
            $statusText = 'Mengerjakan';
            $statusColor = 'primary';

            // --- PERBAIKAN LOGIKA OFFLINE UNTUK MONITORING ---
            $lastActive = $se->last_active_at ? Carbon::parse($se->last_active_at) : Carbon::parse($se->started_at);
            
            // Gunakan abs() persis seperti di ExamEngineController
            $offlineSeconds = abs((int) $now->diffInSeconds($lastActive));

            if ($se->status == 'finished') {
                $stats['finished']++;
                $statusText = 'Selesai';
                $statusColor = 'success';
            } else {
                // Samakan threshold dengan engine siswa (60 detik)
                if ($offlineSeconds > 60) {
                    $isOffline = true;
                    $stats['offline']++;
                    $statusText = 'Koneksi Terputus';
                    $statusColor = 'danger';
                } else {
                    $stats['online']++;
                    $statusText = 'Online (Aktif)';
                    $statusColor = 'info';
                }
            }

            $data[] = [
                'id' => $se->id,
                'name' => $se->cbtAccount->student->name,
                'username' => $se->cbtAccount->username,
                'status' => $se->status,
                'is_offline' => $isOffline,
                'status_text' => $statusText,
                'status_color' => $statusColor,
                'progress' => $progress,
                'answered' => $answeredCount,
                'total_q' => $totalQuestions,
                // Tampilkan pesan yang lebih akurat jika offline
                'last_active' => $isOffline ? 'Offline sejak ' . floor($offlineSeconds / 60) . ' mnt lalu' : 'Baru saja'
            ];
        }

        $latency = round((microtime(true) - $startTime) * 1000); 
        $memory = round(memory_get_usage() / 1024 / 1024, 2); 

        return response()->json([
            'stats' => $stats,
            'students' => $data,
            'server' => [
                'latency' => $latency,
                'memory' => $memory
            ]
        ]);
    }

    // 3. Aksi Darurat: Paksa Selesai
    public function forceFinish(CbtExam $exam, $studentExamId)
    {
        $studentExam = CbtStudentExam::with('answers.question.options')->findOrFail($studentExamId);

        DB::transaction(function () use ($studentExam) {
            $totalEarnedPoints = 0;
            $maxPossiblePoints = 0;

            foreach ($studentExam->answers as $ans) {
                if (!$ans->question) {
                    continue;
                }

                $maxPossiblePoints += $ans->question->score_weight;

                if ($ans->question->type == 'multiple_choice') {
                    $isCorrect = false;
                    if ($ans->cbt_option_id) {
                        // Find the selected option within the already loaded options
                        $selectedOption = $ans->question->options->find($ans->cbt_option_id);
                        if ($selectedOption && $selectedOption->is_correct) {
                            $isCorrect = true;
                        }
                    }

                    if ($isCorrect) {
                        $earnedPoints = $ans->question->score_weight;
                        $totalEarnedPoints += $earnedPoints;
                        $ans->update(['is_correct' => true, 'score' => $earnedPoints]);
                    } else {
                        $ans->update(['is_correct' => false, 'score' => 0]);
                    }
                }
            }

            // Hitung Nilai Akhir (Skala 100)
            $finalScore = ($maxPossiblePoints > 0) ? round(($totalEarnedPoints / $maxPossiblePoints) * 100, 2) : 0;

            $studentExam->update([
                'status' => 'finished',
                'finished_at' => Carbon::now(),
                'score' => $finalScore
            ]);
        });

        return back()->with('success', 'Ujian santri berhasil diakhiri paksa. Nilai Pilihan Ganda telah dihitung.');
    }

    // 4. Aksi Darurat: Kirim Pesan Teguran
    public function sendMessage(Request $request, CbtExam $exam, $studentExamId)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $se = CbtStudentExam::findOrFail($studentExamId);
        
        // Simpan pesan ke database
        $se->update([
            'warning_message' => $request->message
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => 'Pesan berhasil dikirim ke layar santri.'
        ]);
    }
}