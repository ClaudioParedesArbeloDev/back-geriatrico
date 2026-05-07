<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    public function index()
    {
        return response()->json(Allergy::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:allergies,name',
            'description' => 'nullable|string',
        ]);

        $allergy = Allergy::create($validated);

        return response()->json([
            'message' => 'Alergia creada',
            'data'    => $allergy,
        ], 201);
    }

    public function show(Allergy $allergy)
    {
        return response()->json($allergy);
    }

    public function update(Request $request, Allergy $allergy)
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255|unique:allergies,name,' . $allergy->id,
            'description' => 'nullable|string',
        ]);

        $allergy->update($validated);

        return response()->json([
            'message' => 'Alergia actualizada',
            'data'    => $allergy,
        ]);
    }

    public function destroy(Allergy $allergy)
    {
        $allergy->delete();

        return response()->json(['message' => 'Alergia eliminada']);
    }
}
