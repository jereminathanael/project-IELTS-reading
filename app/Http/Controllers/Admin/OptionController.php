<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        return Option::with('question')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_label' => 'required|string',
            'option_text' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        $option = Option::create($data);

        return response()->json($option, 201);
    }

    public function show($id)
    {
        return Option::with('question')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        $data = $request->validate([
            'question_id' => 'sometimes|exists:questions,id',
            'option_label' => 'sometimes|string',
            'option_text' => 'sometimes|string',
            'is_correct' => 'sometimes|boolean',
        ]);

        $option->update($data);

        return response()->json($option->refresh());
    }

    public function destroy($id)
    {
        $option = Option::findOrFail($id);
        $option->delete();

        return response()->json(['message' => 'Option Deleted']);
    }
}