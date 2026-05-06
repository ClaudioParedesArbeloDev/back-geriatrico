<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        return response()->json(
            Specialty::with('users')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name',
            'description' => 'nullable|string',
        ]);

        $specialty = Specialty::create(
            $validated
        );

        return response()->json([
            'message' => 'Especialidad creada',
            'data' => $specialty,
        ], 201);
    }

    public function show(
        Specialty $specialty
    ) {
        return response()->json(
            $specialty->load('users')
        );
    }

    public function update(
        Request $request,
        Specialty $specialty
    ) {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:specialties,name,'.$specialty->id,
            'description' => 'nullable|string',
        ]);

        $specialty->update(
            $validated
        );

        return response()->json([
            'message' => 'Especialidad actualizada',
            'data' => $specialty,
        ]);
    }

    public function destroy(
        Specialty $specialty
    ) {
        $specialty->delete();

        return response()->json([
            'message' => 'Especialidad eliminada',
        ]);
    }
}
