<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Passage;
use App\Models\Question;
use App\Models\Option;
use App\Models\UserAnswer;
use App\Models\UserScore;
use Illuminate\Http\Request;

class IELTSController extends Controller
{
    // Ambil semua passage dengan questions dan options
    public function index()
    {
        return Passage::with('questions.options')->get();
    }

    // Ambil passage lengkap dengan soal & opsi
    public function show($id)
    {
        $passage = Passage::with('questions.options')->findOrFail($id);
        return $passage;
    }

    // Submit jawaban user
    public function submitAnswer(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'passage_id' => 'required|exists:passages,id',
            'answers' => 'required|array',
            'answers.*.selected_option_id' => 'required|exists:options,id',
        ]);

        // Simpan semua jawaban
        foreach ($request->answers as $answer) {
            $option = Option::findOrFail($answer['selected_option_id']);

            UserAnswer::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'question_id' => $option->question_id
                ],
                [
                    'selected_option_id' => $option->id,
                    'is_correct' => $option->is_correct
                ]
            );
        }

        // Hitung skor
        $passageId = $request->passage_id;
        $total = Question::where('passage_id', $passageId)->count();
        $correct = UserAnswer::where('user_id', $user->id)
            ->whereHas('question', function ($q) use ($passageId) {
                $q->where('passage_id', $passageId);
            })
            ->where('is_correct', true)
            ->count();

        // Simpan skor
        UserScore::updateOrCreate(
            ['user_id' => $user->id, 'passage_id' => $passageId],
            ['total_questions' => $total, 'correct_answers' => $correct, 'score' => $correct]
        );

        return response()->json([
            'message' => 'Jawaban berhasil dikirim',
            'total_questions' => $total,
            'correct_answers' => $correct,
            'score' => $correct
        ], 201);
    }
}