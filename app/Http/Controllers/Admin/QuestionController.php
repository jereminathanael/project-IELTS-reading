<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return Question::with('options')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'passage_id' => 'required|exists:passages,id',
            'question_text' => 'required|string',
            'question_type' => 'required|string',
        ]);

        $question = Question::create($data);

        return response()->json($question, 201);
    }

    public function show($id)
    {
        return Question::with('options')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $data = $request->validate([
            'passage_id' => 'sometimes|exists:passages,id',
            'question_text' => 'sometimes|string',
            'question_type' => 'sometimes|string',
        ]);

        $question->update($data);

        return response()->json($question->refresh());
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Question Deleted']);
    }
}