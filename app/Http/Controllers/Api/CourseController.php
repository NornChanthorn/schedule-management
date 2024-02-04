<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(){
        $course = Course::all();
        return response()->json([
            'message'=> 'Get Successfully',
            'data'=> $course
        ]);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'term_id' => 'required',
            'teacher_id' => 'required',
            'duration' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'error'=>$validator->errors()
            ], 422);
        }
        $course = Course::create($validator->validated());
        return response()->json([
            'message'=> 'Create successfully',
            'data' => $course
        ]);
    }
    public function update(Request $req, $id){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'term_id' => 'required',
            'teacher_id' => 'required',
            'duration' => 'required'
        ]);
        $course= Course::find($id);
        if(!$course){
            return response()->json([
                'message' => 'Course not found'
            ]);
        }
        $course->update($validator->validated());
        return response()->json([
            'message'=> 'Update successfully',
            'data'=> $course
        ]);

    }
    public function delete($id){
        $course = Course::find($id);
        $course->delete();
        return response()->json([
            'message'=> 'Delete successfully'
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
}
