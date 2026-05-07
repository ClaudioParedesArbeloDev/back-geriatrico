<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\MedicalPrescriptionController;
use App\Http\Controllers\MedicalStudyController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PatientBedAssignmentController;
use App\Http\Controllers\PatientContactController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientDiagnosisController;
use App\Http\Controllers\PatientEvolutionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\UserSpecialtyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::apiResource('employees', EmployeeController::class);
Route::get('/roles', [RoleController::class, 'index']);

    Route::apiResource('patients', PatientController::class);

Route::apiResource('patientcontacts', PatientContactController::class);

    Route::apiResource('rooms', RoomController::class);
Route::apiResource('beds', BedController::class);
Route::apiResource('patient-bed-assignments', PatientBedAssignmentController::class);

    Route::apiResource('specialties', SpecialtyController::class);
Route::post('users/{user}/specialties', [UserSpecialtyController::class, 'assign']);
Route::put('users/{user}/specialties', [UserSpecialtyController::class, 'replace']);
Route::delete('users/{user}/specialties/{specialtyId}', [UserSpecialtyController::class, 'remove']);

    Route::apiResource('medications', MedicationController::class);
Route::apiResource('medical-prescriptions', MedicalPrescriptionController::class);
Route::apiResource('patient-diagnoses', PatientDiagnosisController::class);
Route::apiResource('medical-studies', MedicalStudyController::class);
Route::apiResource('patient-evolutions', PatientEvolutionController::class);

// Rutas protegidas (requieren token Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});
