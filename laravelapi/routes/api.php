<?php

use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/add-student', [StudentController::class, 'store']);
Route::get('/edit-student/{id}', [StudentController::class, 'edit']);
Route::put('/update-student/{id}', [StudentController::class, 'update']);
Route::get('students', [StudentController::class, 'index']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
