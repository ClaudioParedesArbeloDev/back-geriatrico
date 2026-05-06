<?php

namespace App\Http\Controllers;

use App\Models\PatientDiagnosis;
use Illuminate\Http\Request;

class PatientDiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $query = PatientDiagnosis::with([
            'patient',
            'professional.specialties',
        ]);

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'user_id'      => 'required|exists:users,id',
            'diagnosis'    => 'required|string',
            'notes'        => 'nullable|string',
            'diagnosed_at' => 'required|date',
        ]);

        $diagnosis = PatientDiagnosis::create($validated);

        return response()->json([
            'message' => 'Diagnóstico registrado',
            'data'    => $diagnosis->load(['patient', 'professional.specialties']),
        ], 201);
    }

    public function show(PatientDiagnosis $patientDiagnosis)
    {
        return response()->json(
            $patientDiagnosis->load(['patient', 'professional.specialties'])
        );
    }

    public function update(Request $request, PatientDiagnosis $patientDiagnosis)
    {
        $validated = $request->validate([
            'diagnosis'    => 'sometimes|string',
            'notes'        => 'nullable|string',
            'diagnosed_at' => 'sometimes|date',
        ]);

        $patientDiagnosis->update($validated);

        return response()->json([
            'message' => 'Diagnóstico actualizado',
            'data'    => $patientDiagnosis,
        ]);
    }

    public function destroy(PatientDiagnosis $patientDiagnosis)
    {
        $patientDiagnosis->delete();

        return response()->json(['message' => 'Diagnóstico eliminado']);
    }
}