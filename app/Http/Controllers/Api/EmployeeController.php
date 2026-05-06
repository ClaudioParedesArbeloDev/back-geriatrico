<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

   public function index()
    {
        $employees = User::with(['roles', 'specialties'])->get();

        return response()->json($employees);
    }


    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'dni' => $request->dni,
            'birth_date' => $request->birth_date,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'employee_code' => $request->employee_code,
            'license_number' => $request->license_number,
            'hire_date' => $request->hire_date,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->sync($request->roles ?? []);

        return response()->json([
            'message' => 'Empleado creado',
            'user' => $user->load(['roles', 'specialties'])
        ], 201);
    }


    public function show(User $employee)
    {
        return response()->json(
            $employee->load(['roles', 'specialties'])
        );
    }

    public function update(Request $request, User $employee)
    {
        $employee->update($request->except('roles'));

        if ($request->has('roles')) {
            $employee->roles()->sync($request->roles);
        }

        return response()->json([
            'message' => 'Empleado actualizado',
            'user' => $employee->load(['roles', 'specialties'])
        ]);
    }


    public function destroy(User $employee)
    {
        $employee->delete();

        return response()->json([
            'message' => 'Empleado eliminado'
        ]);
    }
}