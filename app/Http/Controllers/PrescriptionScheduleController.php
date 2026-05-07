<?php

namespace App\Http\Controllers;

use App\Models\MedicalPrescription;
use App\Models\PrescriptionSchedule;
use Illuminate\Http\Request;

class PrescriptionScheduleController extends Controller
{
    
    public function index(MedicalPrescription $medicalPrescription)
    {
        return response()->json(
            $medicalPrescription->schedules
        );
    }

    
    public function store(Request $request, MedicalPrescription $medicalPrescription)
    {
        $validated = $request->validate([
            'scheduled_time' => 'required|date_format:H:i',
            'label'          => 'nullable|string|max:100',
        ]);

        $schedule = $medicalPrescription->schedules()->create($validated);

        return response()->json([
            'message' => 'Horario agregado',
            'data'    => $schedule,
        ], 201);
    }

    
    public function sync(Request $request, MedicalPrescription $medicalPrescription)
    {
        $validated = $request->validate([
            'schedules'                => 'required|array|min:1',
            'schedules.*.scheduled_time' => 'required|date_format:H:i',
            'schedules.*.label'          => 'nullable|string|max:100',
        ]);

        
        $medicalPrescription->schedules()->delete();

        $created = $medicalPrescription->schedules()->createMany($validated['schedules']);

        return response()->json([
            'message' => 'Horarios actualizados',
            'data'    => $created,
        ]);
    }

    
    public function destroy(MedicalPrescription $medicalPrescription, PrescriptionSchedule $schedule)
    {
        
        if ($schedule->medical_prescription_id !== $medicalPrescription->id) {
            return response()->json(['message' => 'El horario no pertenece a esta prescripción'], 422);
        }

        $schedule->delete();

        return response()->json(['message' => 'Horario eliminado']);
    }
}
