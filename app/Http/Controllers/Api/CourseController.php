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
            'gen_id' => 'required',
            'major_id' => 'required',
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
    public function show(Request $req, $id){
        $course = Course::where('id', $id)->get();
        if(!$id){
            return response()->json(['message'=> 'Course no found']);
        }
        return response()->json([
            'message'=> 'gettt successfully',
            'data' => $course
        ]);
    }
    public function update(Request $req, $id){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'term_id' => 'required',
            'gen_id' => 'required',
            'major_id' => 'required',
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
    public function destroy($id){
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
    public function getCourseByMGT($major_id,$gen_id, $term_id ){
        $course = Course::where('major_id', $major_id)
        ->where('gen_id', $gen_id)
        ->where('term_id', $term_id)
        ->with('term', 'generation', 'major')
        ->get();

        return response()->json([
            'message'=> 'Get Successfully',
            'data' => $course
        ]);

    }
    public function getCourseByMajor($major_id){
        $course = Course::where('major_id', $major_id)
        ->with('term', 'generation', 'major')
        ->get();

        return response()->json([
            'message'=> 'Get Successfully',
            'data' => $course
        ]);

    }


}
