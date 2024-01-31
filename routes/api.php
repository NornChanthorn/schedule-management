<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



// routes/api.php

use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\MajorController;
use App\Http\Controllers\Api\GenerationController;

Route::apiResource('rooms', RoomController::class);
Route::apiResource('terms', TermController::class);
Route::apiResource('majors', MajorController::class);
Route::apiResource('generations', GenerationController::class);
