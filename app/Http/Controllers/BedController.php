<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use Illuminate\Http\Request;

class BedController extends Controller
{
    public function index()
    {
        return response()->json(
            Bed::with('room')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'bed_number' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,maintenance'
        ]);

        $bed = Bed::create($validated);

        return response()->json([
            'message' => 'Cama creada',
            'data' => $bed
        ], 201);
    }

    public function show(Bed $bed)
    {
        return response()->json(
            $bed->load('room')
        );
    }

    public function update(Request $request, Bed $bed)
    {
        $validated = $request->validate([
            'bed_number' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:available,occupied,maintenance'
        ]);

        $bed->update($validated);

        return response()->json($bed);
    }

    public function destroy(Bed $bed)
    {
        $bed->delete();

        return response()->json([
            'message' => 'Cama eliminada'
        ]);
    }
}