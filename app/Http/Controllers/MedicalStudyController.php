<?php

namespace App\Http\Controllers;

use App\Models\MedicalStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalStudyController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalStudy::with(['patient', 'professional']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('study_type')) {
            $query->where('study_type', $request->study_type);
        }

        return response()->json($query->orderByDesc('performed_at')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'user_id'      => 'nullable|exists:users,id',
            'study_type'   => 'required|string|max:255',
            'conclusion'   => 'nullable|string',
            'performed_at' => 'required|date',
            'file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store(
                "studies/{$validated['patient_id']}",
                'private'
            );
        }

        unset($validated['file']);
        $validated['file_path'] = $filePath;

        $study = MedicalStudy::create($validated);

        return response()->json([
            'message' => 'Estudio registrado',
            'data'    => $study->load(['patient', 'professional']),
        ], 201);
    }

    public function show(MedicalStudy $medicalStudy)
    {
        return response()->json(
            $medicalStudy->load(['patient', 'professional'])
        );
    }

    public function update(Request $request, MedicalStudy $medicalStudy)
    {
        $validated = $request->validate([
            'study_type'   => 'sometimes|string|max:255',
            'conclusion'   => 'nullable|string',
            'performed_at' => 'sometimes|date',
            'file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($medicalStudy->file_path) {
                Storage::disk('local')->delete($medicalStudy->file_path);
            }
            $validated['file_path'] = $request->file('file')->store(
                "studies/{$medicalStudy->patient_id}",
                'private'
            );
        }

        unset($validated['file']);
        $medicalStudy->update($validated);

        return response()->json([
            'message' => 'Estudio actualizado',
            'data'    => $medicalStudy,
        ]);
    }

   
    public function download(MedicalStudy $medicalStudy)
    {
        if (! $medicalStudy->file_path || ! Storage::disk('local')->exists($medicalStudy->file_path)) {
            return response()->json(['message' => 'El estudio no tiene archivo adjunto'], 404);
        }

        return Storage::disk('local')->download($medicalStudy->file_path);
    }

    public function destroy(MedicalStudy $medicalStudy)
    {
        if ($medicalStudy->file_path) {
            Storage::disk('local')->delete($medicalStudy->file_path);
        }

        $medicalStudy->delete();

        return response()->json(['message' => 'Estudio eliminado']);
    }
}
