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
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:sanctum')->group(function () {
//     // Your authenticated routes go here
//     Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
// });


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);


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
Route::get('/groups/{gen_id}/{major_id}', [GroupController::class, 'getGroupByMG']);
Route::get('/groupsByMajor/{major_id}', [GroupController::class, 'getByMajor']);
Route::apiResource('teachers', TeacherController::class);
Route::get('/notifications/{user_id}', [NotificationController::class, 'index']);

// Schedule
Route::get('/schedules', [ScheduleController::class, 'get']);
Route::get('/schedule/{term_id}', [ScheduleController::class, 'getScheduleByTermId']);
Route::get('/teacher_schedule/{teacher_id}', [ScheduleController::class, 'getSchedule_Teacher']);
Route::get('/schedule/{major}/{gen}/{term}', [ScheduleController::class, 'getSchedule_MGT']);
Route::get('/schedule_group/{major}/{gen}/{term}/{group}', [ScheduleController::class, 'getSchedule_MGTG']);
Route::post('/schedule',[ScheduleController::class, 'store'] );
Route::put('/schedule/{id}',[ScheduleController::class, 'update'] );
Route::delete('/schedule/{id}',[ScheduleController::class, 'delete'] );
Route::get('/courses/{courseId}/schedules', [CourseController::class, 'getSchedulesByCourse']);
Route::get('/course/{majorId}/{genId}/{termId}', [CourseController::class, 'getCourseByMGT']);
Route::get('/courseMajor/{majorId}', [CourseController::class, 'getCourseByMajor']);
Route::get('course_teacher/{id}', [CourseController::class, 'getCourse_Teacher']);
Route::get('teacher_user/{id}', [TeacherController::class, 'getteacherbyuserid']);
Route::get('/course/{teacherid}/{majorId}/{genId}/{termId}', [CourseController::class, 'getCourseByTMGT']);

//additional route
Route::get('student/{major}', [StudentController::class, 'getStudentByMajor']);
Route::post('students/import', [StudentController::class, 'importStudents']);


Route::post('send-email-notification', [CourseController::class, 'sendEmailNotification']);
Route::post('teachers/import', [TeacherController::class, 'import']);
