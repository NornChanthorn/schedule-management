<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:sanctum')->group(function () {
//     // Your authenticated routes go here
//     Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
// });

Route::get('/schedules', [ScheduleController::class, 'get']);
Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
Route::get('/teacher_schedule/{teacher_id}', [ScheduleController::class, 'getSchedule_Teacher']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
