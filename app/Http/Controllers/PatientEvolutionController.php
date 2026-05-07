<?php

namespace App\Http\Controllers;

use App\Models\PatientEvolution;
use Illuminate\Http\Request;

class PatientEvolutionController extends Controller
{
    
    private const ROLE_TYPES = [
        'doctor'        => ['medical', 'general'],
        'nurse'         => ['nursing', 'general'],
        'kinesiologist' => ['kinesiology', 'general'],
        'nutritionist'  => ['nutrition', 'general'],
        'social_worker' => ['social', 'general'],
    ];

    public function index(Request $request)
    {
        $query = PatientEvolution::with(['patient', 'professional'])
            ->orderByDesc('recorded_at');

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'user_id'     => 'required|exists:users,id',
            'type'        => 'required|in:medical,nursing,kinesiology,nutrition,social,general',
            'evolution'   => 'required|string',
            'recorded_at' => 'nullable|date',
        ]);

        
        $authUser = $request->user();
        $authUser->loadMissing('roles');

        if (! $authUser->hasRole('admin')) {
            $allowedTypes = $this->getAllowedTypesForUser($authUser);

            if (! in_array($validated['type'], $allowedTypes)) {
                return response()->json([
                    'message' => "Tu rol no puede registrar evoluciones de tipo '{$validated['type']}'",
                    'allowed' => $allowedTypes,
                ], 403);
            }
        }

        $validated['recorded_at'] ??= now();

        $evolution = PatientEvolution::create($validated);

        return response()->json([
            'message' => 'Evolución registrada',
            'data'    => $evolution->load(['patient', 'professional']),
        ], 201);
    }

    public function show(PatientEvolution $patientEvolution)
    {
        return response()->json(
            $patientEvolution->load(['patient', 'professional'])
        );
    }

    public function update(Request $request, PatientEvolution $patientEvolution)
    {
        
        $authUser = $request->user();

        if (! $authUser->hasRole('admin') && $patientEvolution->user_id !== $authUser->id) {
            return response()->json([
                'message' => 'Solo podés editar tus propias evoluciones',
            ], 403);
        }

        $validated = $request->validate([
            'type'      => 'sometimes|in:medical,nursing,kinesiology,nutrition,social,general',
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
        
        $authUser = request()->user();

        if (! $authUser->hasRole('admin') && $patientEvolution->user_id !== $authUser->id) {
            return response()->json([
                'message' => 'Solo podés eliminar tus propias evoluciones',
            ], 403);
        }

        $patientEvolution->delete();

        return response()->json(['message' => 'Evolución eliminada']);
    }

    
    private function getAllowedTypesForUser($user): array
    {
        foreach (self::ROLE_TYPES as $role => $types) {
            if ($user->hasRole($role)) {
                return $types;
            }
        }

       
        return ['general'];
    }
}
