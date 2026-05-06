<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserSpecialtyController extends Controller
{
    public function assign(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([
            'specialty_ids' => 'required|array',
            'specialty_ids.*' => 'exists:specialties,id'
        ]);

        $user->specialties()->syncWithoutDetaching(
            $validated['specialty_ids']
        );

        return response()->json([
            'message' => 'Especialidades asignadas',
            'data' => $user->load(
                'specialties'
            )
        ]);
    }

    public function replace(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([
            'specialty_ids' => 'required|array',
            'specialty_ids.*' => 'exists:specialties,id'
        ]);

        $user->specialties()->sync(
            $validated['specialty_ids']
        );

        return response()->json([
            'message' => 'Especialidades actualizadas',
            'data' => $user->load(
                'specialties'
            )
        ]);
    }

    public function remove(
        User $user,
        int $specialtyId
    ) {
        $user->specialties()->detach(
            $specialtyId
        );

        return response()->json([
            'message' => 'Especialidad removida',
            'data' => $user->load(
                'specialties'
            )
        ]);
    }
}