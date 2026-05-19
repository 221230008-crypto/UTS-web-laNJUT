<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\EquipmentController;

// Public routes (tanpa login)
Route::get('/incidents', [IncidentController::class, 'index']);
Route::get('/incidents/user', [IncidentController::class, 'getUserReports']);
Route::post('/incidents', [IncidentController::class, 'store']);
Route::get('/geocode/reverse', [IncidentController::class, 'reverseGeocode']);

// Admin routes
Route::put('/incidents/{id}', [IncidentController::class, 'update']);
Route::delete('/incidents/{id}', [IncidentController::class, 'destroy']);

Route::get('/volunteers', [VolunteerController::class, 'index']);
Route::post('/volunteers', [VolunteerController::class, 'store']);
Route::put('/volunteers/{id}', [VolunteerController::class, 'update']);
Route::delete('/volunteers/{id}', [VolunteerController::class, 'destroy']);

Route::get('/equipment', [EquipmentController::class, 'index']);
Route::post('/equipment', [EquipmentController::class, 'store']);
Route::put('/equipment/{id}', [EquipmentController::class, 'update']);
Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy']);