<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Medication::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('controlled')) {
            $query->where('controlled', (bool) $request->controlled);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'               => 'nullable|string|max:50',
            'name'               => 'required|string|max:255',
            'generic_name'       => 'nullable|string|max:255',
            'laboratory'         => 'nullable|string|max:255',
            'presentation'       => 'nullable|string|max:255',
            'concentration'      => 'nullable|string|max:100',
            'drug_form'          => 'nullable|in:tablet,capsule,syrup,injectable,drops,cream,patch,suppository,inhaler,other',
            'contraindications'  => 'nullable|string',
            'controlled'         => 'boolean',
        ]);

        $medication = Medication::create($validated);

        return response()->json([
            'message' => 'Medicamento creado',
            'data'    => $medication,
        ], 201);
    }

    public function show(Medication $medication)
    {
        return response()->json(
            $medication->load(['prescriptions.patient'])
        );
    }

    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'code'               => 'sometimes|string|max:50',
            'name'               => 'sometimes|string|max:255',
            'generic_name'       => 'nullable|string|max:255',
            'laboratory'         => 'nullable|string|max:255',
            'presentation'       => 'nullable|string|max:255',
            'concentration'      => 'nullable|string|max:100',
            'drug_form'          => 'nullable|in:tablet,capsule,syrup,injectable,drops,cream,patch,suppository,inhaler,other',
            'contraindications'  => 'nullable|string',
            'controlled'         => 'boolean',
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
