<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Api\CourseController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:sanctum')->group(function () {
//     // Your authenticated routes go here
//     Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
// });

// Schedule
Route::get('/schedules', [ScheduleController::class, 'get']);
Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
Route::get('/teacher_schedule/{teacher_id}', [ScheduleController::class, 'getSchedule_Teacher']);
Route::get('/schedule/{major}/{gen}/{term}', [ScheduleController::class, 'getSchedule_MGT']);
Route::post('/schedule',[ScheduleController::class, 'store'] );
Route::put('/schedule/{id}',[ScheduleController::class, 'update'] );
Route::delete('/schedule/{id}',[ScheduleController::class, 'delete'] );
Route::get('/courses/{courseId}/schedules', [CourseController::class, 'getSchedulesByCourse']);

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
Route::apiResource('courses', CourseController::class);
