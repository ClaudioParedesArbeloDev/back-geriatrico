<?php

namespace App\Http\Controllers;

use App\Models\PatientEvolution;
use Illuminate\Http\Request;

class PatientEvolutionController extends Controller
{
    public function index(Request $request)
    {
        $query = PatientEvolution::with([
            'patient',
            'professional.specialties',
        ])->latest();

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'user_id'    => 'required|exists:users,id',
            'evolution'  => 'required|string',
        ]);

        $validated['recorded_at'] = now();

        $evolution = PatientEvolution::create($validated);

        return response()->json([
            'message' => 'Evolución registrada',
            'data'    => $evolution->load(['patient', 'professional.specialties']),
        ], 201);
    }

    public function show(PatientEvolution $patientEvolution)
    {
        return response()->json(
            $patientEvolution->load(['patient', 'professional.specialties'])
        );
    }

    public function update(Request $request, PatientEvolution $patientEvolution)
    {
        $validated = $request->validate([
            'evolution' => 'required|string',
        ]);

        $patientEvolution->update($validated);

        return response()->json([
            'message' => 'Evolución actualizada',
            'data'    => $patientEvolution,
        ]);
    }

    public function destroy(PatientEvolution $patientEvolution)
    {
        $patientEvolution->delete();

        return response()->json(['message' => 'Evolución eliminada']);
    }
}