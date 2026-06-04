<?php

namespace App\Http\Controllers\Cbt\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtExam;
use App\Models\CbtQuestionBank;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CbtExamController extends Controller
{
    public function index()
    {
        $exams = CbtExam::with('questionBank.teacher')->orderBy('start_time', 'desc')->get();
        // Tambahkan query banks untuk kebutuhan dropdown di Modal Edit
        $banks = CbtQuestionBank::where('is_active', true)->withCount('questions')->get();

        return view('cbt.admin.exams.index', compact('exams', 'banks'));
    }

    public function create()
    {
        // Ambil bank soal yang aktif
        $banks = CbtQuestionBank::where('is_active', true)->withCount('questions')->get();
        return view('cbt.admin.exams.create', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cbt_question_bank_id' => 'required|exists:cbt_question_banks,id',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|integer|min:10',
        ]);

        CbtExam::create([
            'cbt_question_bank_id' => $request->cbt_question_bank_id,
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $request->duration,
            'token' => strtoupper(Str::random(5)), // Generate 5 karakter acak
            'randomize_questions' => $request->has('randomize_questions'),
            'randomize_options' => $request->has('randomize_options'),
            'show_result' => $request->has('show_result'),
            'is_active' => true,
        ]);

        return redirect()->route('admin.cbt.exams.index')->with('success', 'Jadwal ujian berhasil dibuat dan Token telah diterbitkan.');
    }

    // Tambahkan method update
    public function update(Request $request, CbtExam $exam)
    {
        $request->validate([
            'cbt_question_bank_id' => 'required|exists:cbt_question_banks,id',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|integer|min:10',
        ]);

        $exam->update([
            'cbt_question_bank_id' => $request->cbt_question_bank_id,
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $request->duration,
            'randomize_questions' => $request->has('randomize_questions'),
            'randomize_options' => $request->has('randomize_options'),
            'show_result' => $request->has('show_result'),
        ]);

        return back()->with('success', 'Jadwal ujian berhasil diperbarui.');
    }

    // Refresh Token (Sangat penting agar Token lama hangus jika dicurigai bocor)
    public function refreshToken(CbtExam $exam)
    {
        $exam->update([
            'token' => strtoupper(Str::random(5))
        ]);

        return back()->with('success', 'Token Ujian berhasil diperbarui!');
    }

    public function destroy(CbtExam $exam)
    {
        $exam->delete();
        return back()->with('success', 'Jadwal ujian berhasil dihapus.');
    }
}