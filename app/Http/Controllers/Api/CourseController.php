<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Notification;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::with('term', 'generation', 'teacher')->get();
        return response()->json([
            'message' => 'Get Successfully',
            'data' => $course
        ]);
    }
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'term_id' => 'required',
            'gen_id' => 'required',
            'major_id' => 'required',
            'teacher_id' => 'required',
            'duration' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }
    
        $teacher = Teacher::find($req->teacher_id);
    
        if (!$teacher) {
            return response()->json([
                'error' => 'Teacher not found'
            ], 404);
        }
    
        $course = Course::create($validator->validated());
    
        // Create notification
        $notification = new \App\Models\Notification();
        $notification->user_id = $teacher->user_id;
        $notification->message = 'A new course has been created';
        $notification->save();
    
        // Send email notification
        Notification::route('mail', $teacher->user->email)
            ->notify(new SendEmailNotification());
    
        return response()->json([
            'message' => 'Create successfully',
            'data' => $course
        ]);
    }
    
    public function show(Request $req, $id)
    {
        $course = Course::where('id', $id)->get();
        if (!$id) {
            return response()->json(['message' => 'Course no found']);
        }
        return response()->json([
            'message' => 'gettt successfully',
            'data' => $course
        ]);
    }
    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'term_id' => 'required',
            'gen_id' => 'required',
            'major_id' => 'required',
            'teacher_id' => 'required',
            'duration' => 'required'
        ]);
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'message' => 'Course not found'
            ]);
        }
        $course->update($validator->validated());
        return response()->json([
            'message' => 'Update successfully',
            'data' => $course
        ]);
    }
    public function destroy($id)
    {
        Schedule::where('course_id', $id)->delete();
        $course = Course::find($id);
        $course->delete();
        return response()->json([
            'message' => 'Delete successfully'
        ]);
    }
    public function getSchedulesByCourse($courseId)
    {
        $course = Course::findOrFail($courseId);
        $schedules = $course->schedule;
        return response()->json([
            'course' => $course,
            'schedules' => $schedules,
        ]);
    }
    public function getCourseByMGT($major_id, $gen_id, $term_id)
    {
        $course = Course::where('major_id', $major_id)
            ->where('gen_id', $gen_id)
            ->where('term_id', $term_id)
            ->with('term', 'generation', 'major', 'teacher')
            ->get();

        return response()->json([
            'message' => 'Get Successfully',
            'data' => $course
        ]);
    }
    public function getCourseByMajor($major_id)
    {
        $course = Course::where('major_id', $major_id)
            ->with('term', 'generation', 'major', 'teacher')
            ->get();

        return response()->json([
            'message' => 'Get Successfully',
            'data' => $course
        ]);
    }

    public function sendEmailNotification()
    {
        // Send the email notification
        Notification::route('mail', 'chichhorngkoem@gmail.com')
            ->notify(new SendEmailNotification());

        return response()->json(['message' => 'Email notification sent successfully'], 200);
    }


    public function getCourse_Teacher($teacher_id)
    {
        $data = Course::whereHas('teacher', function ($query) use ($teacher_id) {
            $query->where('id', $teacher_id);
        })->with('term', 'teacher', 'generation', 'major')->get();
        return response()->json([
            'message' => 'Successfull',
            'data' => $data
        ]);
    }

    public function getCourseByTMGT($teacher_id,$major_id, $gen_id, $term_id)
    {
        $course = Course::where('major_id', $major_id)
            ->where('gen_id', $gen_id)
            ->where('term_id', $term_id)
            ->where('teacher_id', $teacher_id)
            ->with('term', 'generation', 'major', 'teacher')
            ->get();

        return response()->json([
            'message' => 'Get Successfully',
            'data' => $course
        ]);
    }
}
