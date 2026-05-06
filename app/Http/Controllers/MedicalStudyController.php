<?php

namespace App\Http\Controllers;

use App\Models\MedicalStudy;
use Illuminate\Http\Request;

class MedicalStudyController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalStudy::with([
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
            'user_id'      => 'nullable|exists:users,id',
            'study_type'   => 'required|string',
            'conclusion'   => 'required|string',
            'performed_at' => 'required|date',
        ]);

        $study = MedicalStudy::create($validated);

        return response()->json([
            'message' => 'Estudio registrado',
            'data'    => $study->load(['patient', 'professional.specialties']),
        ], 201);
    }

    public function show(MedicalStudy $medicalStudy)
    {
        return response()->json(
            $medicalStudy->load(['patient', 'professional.specialties'])
        );
    }

    public function update(Request $request, MedicalStudy $medicalStudy)
    {
        $validated = $request->validate([
            'study_type'   => 'sometimes|string',
            'conclusion'   => 'sometimes|string',
            'performed_at' => 'sometimes|date',
        ]);

        $medicalStudy->update($validated);

        return response()->json([
            'message' => 'Estudio actualizado',
            'data'    => $medicalStudy,
        ]);
    }

    public function destroy(MedicalStudy $medicalStudy)
    {
        $medicalStudy->delete();

        return response()->json(['message' => 'Estudio eliminado']);
    }
}