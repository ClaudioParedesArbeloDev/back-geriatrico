<?php

namespace App\Http\Controllers;

use App\Models\Cie10Code;
use Illuminate\Http\Request;

class Cie10Controller extends Controller
{
    
    public function index(Request $request)
    {
        $q = $request->get('q', '');

        $results = Cie10Code::when(
            $q !== '',
            fn($query) => $query->search($q)
        )
        ->orderBy('code')
        ->limit(10)
        ->get();

        return response()->json($results);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => [
                'required',
                'string',
                'max:10',
                'unique:cie10_codes,code',
                'regex:/^[A-Z][0-9]{2}(\.[0-9]{1,2})?$/',
            ],
            'description' => 'required|string|max:255',
        ]);

       
        $validated['code'] = strtoupper($validated['code']);

        $code = Cie10Code::create($validated);

        return response()->json([
            'message' => 'Código CIE-10 creado',
            'data'    => $code,
        ], 201);
    }

   
    public function update(Request $request, Cie10Code $cie10)
    {
        $validated = $request->validate([
            'code'        => [
                'sometimes',
                'string',
                'max:10',
                'unique:cie10_codes,code,' . $cie10->id,
                'regex:/^[A-Z][0-9]{2}(\.[0-9]{1,2})?$/',
            ],
            'description' => 'sometimes|string|max:255',
        ]);

        if (isset($validated['code'])) {
            $validated['code'] = strtoupper($validated['code']);
        }

        $cie10->update($validated);

        return response()->json([
            'message' => 'Código CIE-10 actualizado',
            'data'    => $cie10,
        ]);
    }

    
    public function destroy(Cie10Code $cie10)
    {
        $cie10->delete();

        return response()->json(['message' => 'Código eliminado']);
    }
}