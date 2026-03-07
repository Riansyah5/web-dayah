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

    // Simpan soal dan pilihan ganda beserta Media (Gambar/Audio)
    public function store(Request $request, CbtQuestionBank $bank)
    {
        $request->validate([
            'type' => 'required|in:multiple_choice,essay',
            'question_text' => 'required|string',
            'score_weight' => 'required|integer|min:1',
            // Validasi File Media (Opsional)
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'audio_file' => 'nullable|mimes:mp3,wav|max:5120', // Max 5MB
            'option_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request, $bank) {
            
            // 1. Upload Media Pertanyaan (Jika Ada)
            $imagePath = $request->hasFile('image_file') 
                ? $request->file('image_file')->store('cbt/questions/images', 'public') 
                : null;
                
            $audioPath = $request->hasFile('audio_file') 
                ? $request->file('audio_file')->store('cbt/questions/audio', 'public') 
                : null;

            // 2. Simpan Soal
            $question = CbtQuestion::create([
                'cbt_question_bank_id' => $bank->id,
                'type' => $request->type,
                'question_text' => $request->question_text,
                'score_weight' => $request->score_weight,
                'image_file' => $imagePath,
                'audio_file' => $audioPath,
            ]);

            // 3. Jika Pilihan Ganda, simpan Opsi (A, B, C, D) + Gambar Opsi jika ada
            if ($request->type == 'multiple_choice') {
                $options = $request->options;
                $correctOption = $request->correct_option;

                foreach ($options as $index => $optionText) {
                    // Cek apakah opsi ini teksnya diisi ATAU gambarnya diisi
                    // (Karena bisa jadi jawabannya hanya berupa gambar tanpa teks)
                    $hasText = !empty($optionText);
                    $hasImage = $request->hasFile("option_images.$index");

                    if ($hasText || $hasImage) {
                        
                        // Upload Gambar Opsi (Jika Ada)
                        $optImagePath = $hasImage 
                            ? $request->file("option_images.$index")->store('cbt/options/images', 'public') 
                            : null;

                        CbtOption::create([
                            'cbt_question_id' => $question->id,
                            'option_text' => $optionText ?? '', // Kosongkan string jika hanya gambar
                            'image_file' => $optImagePath,
                            'is_correct' => ($index == $correctOption) ? true : false,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('teacher.cbt.banks.show', $bank->id)
                         ->with('success', 'Soal dan lampiran media berhasil ditambahkan.');
    }

    // Hapus soal
    public function destroy(CbtQuestion $question)
    {
        $question->delete(); // Opsi (cbt_options) akan ikut terhapus karena cascadeOnDelete di database
        return back()->with('success', 'Soal berhasil dihapus.');
    }
}