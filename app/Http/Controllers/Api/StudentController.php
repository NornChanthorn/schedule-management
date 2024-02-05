<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(){
        $student = Student::all();
        return response()->json($student);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'student_id' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'user_id' => 'required',
            'generation_id' => 'required',
            'group_id' => 'required',
            'major_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=> $validator->errors()
            ], 422);
        }
        $student = Student::create($validator->validated());
        return response()->json([
            'message'=> 'Create successfully',
            'data'=> $student
        ]);

    }
    public function update(Request $req, $id){
        $validator = Validator::make($req->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'student_id' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'user_id' => 'required',
            'generation_id' => 'required',
            'group_id' => 'required',
            'major_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=> $validator->errors()
            ], 422);
        }
        $student->update($validator->validated());
        return response()->json([
            'message' => 'Update successfully',
            'data' => $student
        ]);

    }
    public function delete($id){
        $student = Student::find($id);
        $student->delete();
        return response()->json([
            'message'=> 'Delete successfully',
        ]);
    }
}
