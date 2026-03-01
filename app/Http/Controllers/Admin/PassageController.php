<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passage;
use Illuminate\Http\Request;

class PassageController extends Controller
{
    public function index()
    {
        return Passage::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $passage = Passage::create($data);

        return response()->json($passage, 201);
    }

    public function show($id)
    {
        $passage = Passage::with('questions.options')->findOrFail($id);
        return $passage;
    }

    public function update(Request $request, $id)
    {
        $passage = Passage::findOrFail($id);
        $data = $request->validate([
            'title' => 'sometimes|string',
            'content' => 'sometimes|string',
        ]);

        $passage->update($data);

        return response()->json($passage->refresh());
    }

    public function destroy($id)
    {
        $passage = Passage::findOrFail($id);
        $passage->delete();

        return response()->json(['message' => 'Passages Deleted']);
    }
}