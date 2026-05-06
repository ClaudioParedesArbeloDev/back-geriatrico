<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\PatientBedAssignment;
use Illuminate\Http\Request;

class PatientBedAssignmentController extends Controller
{
    public function index()
    {
        return response()->json(
            PatientBedAssignment::with([
                'patient',
                'bed.room'
            ])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'required|exists:beds,id'
        ]);

        $bed = Bed::findOrFail(
            $validated['bed_id']
        );

        if ($bed->status !== 'available') {
            return response()->json([
                'message' => 'La cama no está disponible'
            ], 422);
        }

        $assignment = PatientBedAssignment::create([
            'patient_id' => $validated['patient_id'],
            'bed_id' => $validated['bed_id'],
            'assigned_at' => now()
        ]);

        $bed->update([
            'status' => 'occupied'
        ]);

        return response()->json([
            'message' => 'Paciente asignado',
            'data' => $assignment
        ], 201);
    }

    public function show(
        PatientBedAssignment $patientBedAssignment
    ) {
        return response()->json(
            $patientBedAssignment->load([
                'patient',
                'bed.room'
            ])
        );
    }

    public function destroy(
        PatientBedAssignment $patientBedAssignment
    ) {
        $bed = $patientBedAssignment->bed;

        $patientBedAssignment->update([
            'released_at' => now()
        ]);

        $bed->update([
            'status' => 'available'
        ]);

        return response()->json([
            'message' => 'Paciente dado de alta de la cama'
        ]);
    }
}