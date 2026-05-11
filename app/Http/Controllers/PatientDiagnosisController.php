<?php

namespace App\Http\Controllers;

use App\Models\PatientDiagnosis;
use Illuminate\Http\Request;

class PatientDiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $query = PatientDiagnosis::with(['patient', 'professional']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('cie10')) {
            $query->where('cie10_code', 'like', $request->cie10 . '%');
        }

        return response()->json($query->orderByDesc('diagnosed_at')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'diagnosis'    => 'required|string',
            'cie10_code'   => ['nullable', 'string', 'max:10', 'regex:/^[A-Z][0-9]{2}(\.[0-9]{1,2})?$/'],
            'cie10_label'  => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
            'diagnosed_at' => 'nullable|date',
        ]);

        
        $validated['user_id']      = auth()->id();
        $validated['diagnosed_at'] = $validated['diagnosed_at'] ?? now()->toDateString();

        $diagnosis = PatientDiagnosis::create($validated);

        return response()->json([
            'message' => 'Diagnóstico registrado',
            'data'    => $diagnosis->load(['patient', 'professional']),
        ], 201);
    }

    public function show(PatientDiagnosis $patientDiagnosis)
    {
        return response()->json(
            $patientDiagnosis->load(['patient', 'professional'])
        );
    }

    public function update(Request $request, PatientDiagnosis $patientDiagnosis)
    {
        $validated = $request->validate([
            'diagnosis'    => 'sometimes|string',
            'cie10_code'   => ['nullable', 'string', 'max:10', 'regex:/^[A-Z][0-9]{2}(\.[0-9]{1,2})?$/'],
            'cie10_label'  => 'nullable|string|max:255',
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