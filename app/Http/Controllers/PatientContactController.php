<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientContact;
use Illuminate\Http\Request;

class PatientContactController extends Controller
{
    public function index(Request $request)
    {
        $query = PatientContact::with('patient');

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'name'         => 'required|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'dni'          => 'nullable|string|unique:patient_contacts,dni',
            'relationship' => 'nullable|string|max:255',
            'phone'        => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string|max:255',
            'is_primary'   => 'boolean',
        ]);

        
        if (! empty($validated['is_primary'])) {
            PatientContact::where('patient_id', $validated['patient_id'])
                ->update(['is_primary' => false]);
        }

        $contact = PatientContact::create($validated);

        return response()->json([
            'message' => 'Contacto creado',
            'data'    => $contact,
        ], 201);
    }

    public function show(PatientContact $patientContact)
    {
        return response()->json($patientContact->load('patient'));
    }

    public function update(Request $request, PatientContact $patientContact)
    {
        $validated = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'dni'          => 'nullable|string|unique:patient_contacts,dni,' . $patientContact->id,
            'relationship' => 'nullable|string|max:255',
            'phone'        => 'sometimes|string|max:255',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string|max:255',
            'is_primary'   => 'boolean',
        ]);

        if (! empty($validated['is_primary'])) {
            PatientContact::where('patient_id', $patientContact->patient_id)
                ->where('id', '!=', $patientContact->id)
                ->update(['is_primary' => false]);
        }

        $patientContact->update($validated);

        return response()->json([
            'message' => 'Contacto actualizado',
            'data'    => $patientContact,
        ]);
    }

    
    public function setPrimary(PatientContact $patientContact)
    {
        
        PatientContact::where('patient_id', $patientContact->patient_id)
            ->update(['is_primary' => false]);

        $patientContact->update(['is_primary' => true]);

        return response()->json([
            'message' => 'Contacto marcado como responsable principal',
            'data'    => $patientContact,
        ]);
    }

    public function destroy(PatientContact $patientContact)
    {
        $patientContact->delete();

        return response()->json(['message' => 'Contacto eliminado']);
    }
}
