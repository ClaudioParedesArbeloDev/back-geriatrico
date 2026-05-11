<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Medication::query();

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('porcentaje')) {
            $query->porcentaje((int) $request->porcentaje);
        }

        if ($request->filled('seccion')) {
            $query->seccion($request->seccion);
        }

        if ($request->filled('laboratorio')) {
            $query->where('laboratorio', 'like', '%' . $request->laboratorio . '%');
        }

        $perPage = min((int) ($request->per_page ?? 20), 100);

        return response()->json(
            $query->orderBy('principio_activo')->orderBy('nombre_comercial')->paginate($perPage)
        );
    }

    
    public function show(Medication $medication)
    {
        return response()->json(
            $medication->load('prescriptions.patient')
        );
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_comercial'     => 'required|string|max:250',
            'presentacion'         => 'nullable|string|max:250',
            'accion_farmacologica' => 'nullable|string|max:250',
            'principio_activo'     => 'nullable|string|max:250',
            'laboratorio'          => 'nullable|string|max:150',
            'porcentaje'           => 'required|integer|in:40,70,100',
            'seccion'              => 'nullable|string|max:100',
        ]);

        $medication = Medication::create($validated);

        return response()->json([
            'message' => 'Medicamento creado',
            'data'    => $medication,
        ], 201);
    }

    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'nombre_comercial'     => 'sometimes|string|max:250',
            'presentacion'         => 'nullable|string|max:250',
            'accion_farmacologica' => 'nullable|string|max:250',
            'principio_activo'     => 'nullable|string|max:250',
            'laboratorio'          => 'nullable|string|max:150',
            'porcentaje'           => 'sometimes|integer|in:40,70,100',
            'seccion'              => 'nullable|string|max:100',
        ]);

        $medication->update($validated);

        return response()->json([
            'message' => 'Medicamento actualizado',
            'data'    => $medication,
        ]);
    }

    
    public function destroy(Medication $medication)
    {
        $medication->delete();

        return response()->json(['message' => 'Medicamento eliminado']);
    }
}