<?php

namespace App\Http\Controllers;

use App\Models\MedicalPrescription;
use Illuminate\Http\Request;

class MedicalPrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalPrescription::with([
            'patient',
            'medication',
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
            'patient_id'    => 'required|exists:patients,id',
            'medication_id' => 'required|exists:medications,id',
            'user_id'       => 'required|exists:users,id',
            'dose'          => 'required|string',
            'frequency'     => 'required|string',
            'route'         => 'nullable|string',
            'instructions'  => 'nullable|string',
        ]);

        $prescription = MedicalPrescription::create($validated);

        return response()->json([
            'message' => 'Prescripción registrada',
            'data'    => $prescription->load(['patient', 'medication', 'professional.specialties']),
        ], 201);
    }

    public function show(MedicalPrescription $medicalPrescription)
    {
        return response()->json(
            $medicalPrescription->load(['patient', 'medication', 'professional.specialties'])
        );
    }

    public function update(Request $request, MedicalPrescription $medicalPrescription)
    {
        $validated = $request->validate([
            'dose'         => 'sometimes|string',
            'frequency'    => 'sometimes|string',
            'route'        => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $medicalPrescription->update($validated);

        return response()->json([
            'message' => 'Prescripción actualizada',
            'data'    => $medicalPrescription,
        ]);
    }

    public function destroy(MedicalPrescription $medicalPrescription)
    {
        $medicalPrescription->delete();

        return response()->json(['message' => 'Prescripción eliminada']);
    }
}