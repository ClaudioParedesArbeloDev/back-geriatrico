<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientAllergyController extends Controller
{
    
    public function index(Patient $patient)
    {
        return response()->json(
            $patient->allergies()->withPivot(['severity', 'reaction'])->get()
        );
    }

    
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'allergy_id' => 'required|exists:allergies,id',
            'severity'   => 'required|in:mild,moderate,severe',
            'reaction'   => 'nullable|string',
        ]);

        
        $patient->allergies()->syncWithoutDetaching([
            $validated['allergy_id'] => [
                'severity' => $validated['severity'],
                'reaction' => $validated['reaction'] ?? null,
            ],
        ]);

        return response()->json([
            'message' => 'Alergia asignada al paciente',
            'data'    => $patient->allergies()->withPivot(['severity', 'reaction'])->get(),
        ], 201);
    }

   
    public function update(Request $request, Patient $patient, int $allergyId)
    {
        $validated = $request->validate([
            'severity' => 'sometimes|in:mild,moderate,severe',
            'reaction' => 'nullable|string',
        ]);

        $patient->allergies()->updateExistingPivot($allergyId, $validated);

        return response()->json([
            'message' => 'Alergia actualizada',
            'data'    => $patient->allergies()->withPivot(['severity', 'reaction'])->get(),
        ]);
    }

   
    public function destroy(Patient $patient, int $allergyId)
    {
        $patient->allergies()->detach($allergyId);

        return response()->json(['message' => 'Alergia removida del paciente']);
    }
}
