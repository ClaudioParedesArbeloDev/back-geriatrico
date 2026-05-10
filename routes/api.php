<?php

use App\Http\Controllers\AllergyController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\MedicalPrescriptionController;
use App\Http\Controllers\MedicalStudyController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PatientAllergyController;
use App\Http\Controllers\PatientBedAssignmentController;
use App\Http\Controllers\PatientContactController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientDiagnosisController;
use App\Http\Controllers\PatientEvolutionController;
use App\Http\Controllers\PrescriptionScheduleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\UserSpecialtyController;
use App\Http\Controllers\VitalSignController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login',           [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password',  [AuthController::class, 'resetPassword']);



Route::middleware('auth:sanctum')->group(function () {

    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user()->load(['roles', 'specialties']));


   
    Route::middleware('role:admin')->group(function () {

       
        Route::apiResource('employees', EmployeeController::class);
        Route::get('roles', [RoleController::class, 'index']);

        
        Route::apiResource('specialties', SpecialtyController::class);
        Route::post('users/{user}/specialties',                         [UserSpecialtyController::class, 'assign']);
        Route::put('users/{user}/specialties',                          [UserSpecialtyController::class, 'replace']);
        Route::delete('users/{user}/specialties/{specialtyId}',         [UserSpecialtyController::class, 'remove']);

        
        Route::apiResource('rooms', RoomController::class);
        Route::apiResource('beds',  BedController::class);
        Route::apiResource('patient-bed-assignments', PatientBedAssignmentController::class)->except(['update']);
    });


    
    Route::middleware('role:doctor')->group(function () {

        
        Route::apiResource('patients', PatientController::class)->except(['index', 'show']);

        
        Route::apiResource('patient-contacts', PatientContactController::class)->except(['index', 'show']);
        Route::patch('patient-contacts/{patientContact}/set-primary', [PatientContactController::class, 'setPrimary']);

        
        Route::apiResource('allergies', AllergyController::class)->except(['index', 'show']);
        Route::post('patients/{patient}/allergies',                   [PatientAllergyController::class, 'store']);
        Route::put('patients/{patient}/allergies/{allergyId}',        [PatientAllergyController::class, 'update']);
        Route::delete('patients/{patient}/allergies/{allergyId}',     [PatientAllergyController::class, 'destroy']);

        
        Route::apiResource('medications', MedicationController::class)->except(['index', 'show']);

        
        Route::apiResource('medical-prescriptions', MedicalPrescriptionController::class)->except(['index', 'show']);
        Route::patch('medical-prescriptions/{medicalPrescription}/suspend',    [MedicalPrescriptionController::class, 'suspend']);
        Route::patch('medical-prescriptions/{medicalPrescription}/reactivate', [MedicalPrescriptionController::class, 'reactivate']);

        
        Route::post('medical-prescriptions/{medicalPrescription}/schedules',              [PrescriptionScheduleController::class, 'store']);
        Route::put('medical-prescriptions/{medicalPrescription}/schedules',               [PrescriptionScheduleController::class, 'sync']);
        Route::delete('medical-prescriptions/{medicalPrescription}/schedules/{schedule}', [PrescriptionScheduleController::class, 'destroy']);

       
        Route::apiResource('patient-diagnoses', PatientDiagnosisController::class)->except(['index', 'show']);

        
        Route::apiResource('medical-studies', MedicalStudyController::class)->except(['index', 'show']);
    });


   
    Route::middleware('role:doctor,nurse')->group(function () {
        Route::apiResource('vital-signs', VitalSignController::class)->except(['index', 'show']);
    });


    
    Route::middleware('role:doctor,nurse,kinesiologist,nutritionist,social_worker')->group(function () {

        
        Route::apiResource('patients', PatientController::class)->only(['index', 'show']);

        
        Route::apiResource('patient-contacts',       PatientContactController::class)->only(['index', 'show']);
        Route::apiResource('allergies',              AllergyController::class)->only(['index', 'show']);
        Route::apiResource('medications',            MedicationController::class)->only(['index', 'show']);
        Route::apiResource('medical-prescriptions',  MedicalPrescriptionController::class)->only(['index', 'show']);
        Route::apiResource('patient-diagnoses',      PatientDiagnosisController::class)->only(['index', 'show']);
        Route::apiResource('medical-studies',        MedicalStudyController::class)->only(['index', 'show']);
        Route::apiResource('vital-signs',            VitalSignController::class)->only(['index', 'show']);

       
        Route::get('medical-prescriptions/{medicalPrescription}/schedules', [PrescriptionScheduleController::class, 'index']);

       
        Route::get('patients/{patient}/allergies', [PatientAllergyController::class, 'index']);

       
        Route::get('medical-studies/{medicalStudy}/download', [MedicalStudyController::class, 'download']);

        
        Route::apiResource('rooms', RoomController::class)->only(['index', 'show']);
        Route::apiResource('beds',  BedController::class)->only(['index', 'show']);

        
        Route::apiResource('patient-evolutions', PatientEvolutionController::class);
    });
});
