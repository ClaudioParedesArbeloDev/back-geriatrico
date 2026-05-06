<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Patient::query();

    
        if ($request->filled('dni')) {
            $query->where('dni', $request->dni);
        }

    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'dni'              => 'required|string|unique:patients,dni',
            'birth_date'       => 'required|date',
            'gender'           => 'required|in:male,female,other',
            'blood_type'       => 'nullable|string|max:10',
            'admission_date'   => 'required|date',
            'mobility_status'  => 'nullable|in:normal,reduced,wheelchair,bedridden',
            'dependency_level' => 'nullable|in:low,medium,high',
            'emergency_phone'  => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
            'status'           => 'nullable|in:active,inactive,deceased',
        ]);

        $patient = Patient::create($validated);

        return response()->json([
            'message' => 'Paciente creado correctamente',
            'patient' => $patient,
        ], 201);
    }

    
    public function show(string $id)
    {
        $patient = Patient::findOrFail($id);

        return response()->json($patient);
    }

    
    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'first_name'       => 'sometimes|string|max:255',
            'last_name'        => 'sometimes|string|max:255',
            'dni'              => 'sometimes|string|unique:patients,dni,' . $patient->id,
            'birth_date'       => 'sometimes|date',
            'gender'           => 'sometimes|in:male,female,other',
            'blood_type'       => 'nullable|string|max:10',
            'admission_date'   => 'sometimes|date',
            'mobility_status'  => 'nullable|in:normal,reduced,wheelchair,bedridden',
            'dependency_level' => 'nullable|in:low,medium,high',
            'emergency_phone'  => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
            'status'           => 'nullable|in:active,inactive,deceased',
        ]);

        $patient->update($validated);

        return response()->json([
            'message' => 'Paciente actualizado',
            'patient' => $patient,
        ]);
    }

    
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Paciente eliminado']);
    }
}