<?php

namespace App\Http\Controllers;

use App\Models\VitalSign;
use Illuminate\Http\Request;

class VitalSignController extends Controller
{
   
    public function index(Request $request)
    {
        $query = VitalSign::with(['patient', 'registeredBy'])
            ->orderByDesc('recorded_at');

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        
        if ($request->filled('limit')) {
            $query->limit((int) $request->limit);
        }

        return response()->json($query->get());
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'                => 'required|exists:patients,id',
            'user_id'                   => 'required|exists:users,id',
            'blood_pressure_systolic'   => 'nullable|integer|min:50|max:300',
            'blood_pressure_diastolic'  => 'nullable|integer|min:20|max:200',
            'heart_rate'                => 'nullable|integer|min:20|max:300',
            'temperature'               => 'nullable|numeric|min:30|max:45',
            'oxygen_saturation'         => 'nullable|numeric|min:50|max:100',
            'blood_glucose'             => 'nullable|integer|min:20|max:800',
            'weight'                    => 'nullable|numeric|min:10|max:300',
            'respiratory_rate'          => 'nullable|integer|min:5|max:60',
            'notes'                     => 'nullable|string',
            'recorded_at'               => 'nullable|date',
        ]);

        
        $validated['recorded_at'] ??= now();

        $vitalSign = VitalSign::create($validated);

        return response()->json([
            'message' => 'Signos vitales registrados',
            'data'    => $vitalSign->load(['patient', 'registeredBy']),
        ], 201);
    }

    
    public function show(VitalSign $vitalSign)
    {
        return response()->json(
            $vitalSign->load(['patient', 'registeredBy'])
        );
    }

    
    public function update(Request $request, VitalSign $vitalSign)
    {
        $validated = $request->validate([
            'blood_pressure_systolic'   => 'nullable|integer|min:50|max:300',
            'blood_pressure_diastolic'  => 'nullable|integer|min:20|max:200',
            'heart_rate'                => 'nullable|integer|min:20|max:300',
            'temperature'               => 'nullable|numeric|min:30|max:45',
            'oxygen_saturation'         => 'nullable|numeric|min:50|max:100',
            'blood_glucose'             => 'nullable|integer|min:20|max:800',
            'weight'                    => 'nullable|numeric|min:10|max:300',
            'respiratory_rate'          => 'nullable|integer|min:5|max:60',
            'notes'                     => 'nullable|string',
            'recorded_at'               => 'nullable|date',
        ]);

        $vitalSign->update($validated);

        return response()->json([
            'message' => 'Registro actualizado',
            'data'    => $vitalSign,
        ]);
    }

    
    public function destroy(VitalSign $vitalSign)
    {
        $vitalSign->delete();

        return response()->json(['message' => 'Registro eliminado']);
    }
}
