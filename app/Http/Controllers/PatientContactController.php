<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientContact;

class PatientContactController extends Controller
{
    public function index(Request $request)
    {
        $query = PatientContact::with('patient');

        // FIX (nuevo): permite filtrar por paciente: GET /patientcontacts?patient_id=5
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
            'last_name'    => 'required|string|max:255',
            // FIX (del commit anterior): unique apunta a patient_contacts, NO a patients
            'dni'          => 'required|string|unique:patient_contacts,dni',
            'relationship' => 'required|string|max:255',
            'phone'        => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'address'      => 'required|string|max:255',
        ]);

        $contact = PatientContact::create($validated);

        return response()->json([
            'message' => 'Contacto creado correctamente',
            'contact' => $contact,
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
            'last_name'    => 'sometimes|string|max:255',
            'dni'          => 'sometimes|string|unique:patient_contacts,dni,' . $patientContact->id,
            'relationship' => 'sometimes|string|max:255',
            'phone'        => 'sometimes|string|max:255',
            'email'        => 'nullable|email|max:255',
            'address'      => 'sometimes|string|max:255',
        ]);

        $patientContact->update($validated);

        return response()->json([
            'message' => 'Contacto actualizado',
            'contact' => $patientContact,
        ]);
    }

    public function destroy(PatientContact $patientContact)
    {
        $patientContact->delete();
        return response()->json(['message' => 'Contacto eliminado']);
    }
}