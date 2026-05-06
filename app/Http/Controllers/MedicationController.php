<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index()
    {
        return response()->json(
            Medication::all()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'laboratory' => 'nullable|string|max:255',
            'presentation' => 'nullable|string|max:255'
        ]);

        $medication = Medication::create(
            $validated
        );

        return response()->json([
            'message' => 'Medicamento creado',
            'data' => $medication
        ], 201);
    }

    public function show(
        Medication $medication
    ) {
        return response()->json(
            $medication
        );
    }

    public function update(
        Request $request,
        Medication $medication
    ) {
        $validated = $request->validate([
            'code' => 'sometimes|string|max:50',
            'name' => 'sometimes|string|max:255',
            'generic_name' => 'sometimes|string|max:255',
            'laboratory' => 'sometimes|string|max:255',
            'presentation' => 'sometimes|string|max:255'
        ]);

        $medication->update(
            $validated
        );

        return response()->json([
            'message' => 'Medicamento actualizado',
            'data' => $medication
        ]);
    }

    public function destroy(
        Medication $medication
    ) {
        $medication->delete();

        return response()->json([
            'message' => 'Medicamento eliminado'
        ]);
    }
}