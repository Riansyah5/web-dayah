<?php

namespace App\Http\Controllers\Cbt\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtQuestionBank;
use App\Models\CbtQuestion;
use App\Models\CbtOption;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    // Form tambah soal
    public function create(CbtQuestionBank $bank)
    {
        return view('cbt.teacher.questions.create', compact('bank'));
    }

    // Simpan soal dan pilihan ganda
    public function store(Request $request, CbtQuestionBank $bank)
    {
        $request->validate([
            'type' => 'required|in:multiple_choice,essay',
            'question_text' => 'required|string',
            'score_weight' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $bank) {
            // 1. Simpan Soal
            $question = CbtQuestion::create([
                'cbt_question_bank_id' => $bank->id,
                'type' => $request->type,
                'question_text' => $request->question_text,
                'score_weight' => $request->score_weight,
            ]);

            // 2. Jika Pilihan Ganda, simpan Opsi A, B, C, D
            if ($request->type == 'multiple_choice') {
                $options = $request->options; // Array dari input opsi
                $correctOption = $request->correct_option; // Index opsi yang benar (0, 1, 2, 3)

                foreach ($options as $index => $optionText) {
                    if (!empty($optionText)) {
                        CbtOption::create([
                            'cbt_question_id' => $question->id,
                            'option_text' => $optionText,
                            'is_correct' => ($index == $correctOption) ? true : false,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('teacher.cbt.banks.show', $bank->id)
                         ->with('success', 'Soal berhasil ditambahkan.');
    }

    // Hapus soal
    public function destroy(CbtQuestion $question)
    {
        $question->delete(); // Opsi (cbt_options) akan ikut terhapus karena cascadeOnDelete di database
        return back()->with('success', 'Soal berhasil dihapus.');
    }
}