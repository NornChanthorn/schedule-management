<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// api
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TermController;
use App\Http\Controllers\Api\MajorController;
use App\Http\Controllers\Api\GenerationController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\DayOfWeekController;
use App\Http\Controllers\Api\ClassTypeController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\NotificationController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:sanctum')->group(function () {
//     // Your authenticated routes go here
//     Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
// });



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// routes/api.php

Route::apiResource('rooms', RoomController::class);
Route::apiResource('terms', TermController::class);
Route::apiResource('majors', MajorController::class);
Route::apiResource('generations', GenerationController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('class-types', ClassTypeController::class);
Route::apiResource('days-of-week', DayOfWeekController::class);
Route::apiResource('groups', GroupController::class);
Route::apiResource('teachers', TeacherController::class);
Route::get('/notifications/{user_id}', [NotificationController::class, 'index']);

// Schedule
Route::get('/schedules', [ScheduleController::class, 'get']);
Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
Route::get('/teacher_schedule/{teacher_id}', [ScheduleController::class, 'getSchedule_Teacher']);
Route::get('/schedule/{major}/{gen}/{term}', [ScheduleController::class, 'getSchedule_MGT']);
Route::post('/schedule',[ScheduleController::class, 'store'] );
Route::put('/schedule/{id}',[ScheduleController::class, 'update'] );
Route::delete('/schedule/{id}',[ScheduleController::class, 'delete'] );
Route::get('/courses/{courseId}/schedules', [CourseController::class, 'getSchedulesByCourse']);
Route::get('/course/{majorId}/{genId}/{termId}', [CourseController::class, 'getCourseByMGT']);
Route::get('courseMajor/{majorId}', [CourseController::class, 'getCourseByMajor']);
Route::get('course_teacher/{id}', [CourseController::class, 'getCourse_Teacher']);
Route::get('teacher_user/{id}', [TeacherController::class, 'getteacherbyuserid']);

//additional route
Route::get('student/{major}', [StudentController::class, 'getStudentByMajor']);

Route::post('students/import', [StudentController::class, 'importStudents']);


Route::post('send-email-notification', [CourseController::class, 'sendEmailNotification']);


