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
            'professional',
            'schedules',
        ]);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('active')) {
            $query->where('is_active', (bool) $request->active);
        }

        return response()->json($query->orderByDesc('created_at')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'    => 'required|exists:patients,id',
            'medication_id' => 'required|exists:medications,id',
            // user_id siempre desde auth, no del request
            'dose'          => 'required|string|max:100',
            'frequency'     => 'required|string|max:255',
            'route'         => 'nullable|string|max:100',
            'instructions'  => 'nullable|string',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'is_active'     => 'boolean',
            
            'schedules'                  => 'nullable|array',
            'schedules.*.scheduled_time' => 'required_with:schedules|date_format:H:i',
            'schedules.*.label'          => 'nullable|string|max:100',
        ]);

        $schedules = $validated['schedules'] ?? [];
        unset($validated['schedules']);

        $validated['user_id'] = auth()->id();
        $prescription = MedicalPrescription::create($validated);

        if (! empty($schedules)) {
            $prescription->schedules()->createMany($schedules);
        }

        return response()->json([
            'message' => 'Prescripción registrada',
            'data'    => $prescription->load(['patient', 'medication', 'professional', 'schedules']),
        ], 201);
    }

    public function show(MedicalPrescription $medicalPrescription)
    {
        return response()->json(
            $medicalPrescription->load(['patient', 'medication', 'professional', 'schedules'])
        );
    }

    public function update(Request $request, MedicalPrescription $medicalPrescription)
    {
        $validated = $request->validate([
            'dose'         => 'sometimes|string|max:100',
            'frequency'    => 'sometimes|string|max:255',
            'route'        => 'nullable|string|max:100',
            'instructions' => 'nullable|string',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'is_active'    => 'boolean',
        ]);

        $medicalPrescription->update($validated);

        return response()->json([
            'message' => 'Prescripción actualizada',
            'data'    => $medicalPrescription->load(['medication', 'schedules']),
        ]);
    }

    
    public function suspend(MedicalPrescription $medicalPrescription)
    {
        $medicalPrescription->update(['is_active' => false]);

        return response()->json([
            'message' => 'Prescripción suspendida',
            'data'    => $medicalPrescription,
        ]);
    }

   
    public function reactivate(MedicalPrescription $medicalPrescription)
    {
        $medicalPrescription->update(['is_active' => true]);

        return response()->json([
            'message' => 'Prescripción reactivada',
            'data'    => $medicalPrescription,
        ]);
    }

    public function destroy(MedicalPrescription $medicalPrescription)
    {
        $medicalPrescription->delete();

        return response()->json(['message' => 'Prescripción eliminada']);
    }
}