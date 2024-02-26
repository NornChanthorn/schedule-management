<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;


class ScheduleController extends Controller

{
    public function get(){
        $schedule = Schedule::with('term', 'day')->get();
        return response()->json([
            'message' => 'Successfully',
            'data' => $schedule
        ]);
    }
    public function getScheduleByTermId($term_id){
        $data = Schedule::where('term_id', $term_id)->with('term', 'day', 'course.teacher', 'group', 'room')->get()->groupBy('term');
        return response()->json($data);
    }
    public function getSchedule_Teacher($teacher_id){
        $data = Schedule::whereHas('course.teacher', function($query) use ($teacher_id){
            $query->where ('id', $teacher_id);

        })->with('term', 'day', 'course.teacher', 'group', 'room')->get();
        return response()->json([
            'message' => 'Successfull',
            'data'=> $data
        ]);
    }
    // public function getSchedule_MGT($major_id, $gen_id, $term_id){
    //     $schedule = Schedule::where('major_id', $major_id)
    //     ->where('gen_id', $gen_id)
    //     ->where('term_id', $term_id)
    //     ->with('term', 'course', 'generation', 'major')
    //     ->get();

    //     return response()->json([
    //         'message'=> 'Get Successfully',
    //         'data' => $schedule
    //     ]);

    // }

    public function getSchedule_MGT($major_id, $gen_id, $term_id){
        $schedules = Schedule::where('major_id', $major_id)
            ->where('gen_id', $gen_id)
            ->where('term_id', $term_id)
            ->with('term', 'day', 'course.teacher', 'group', 'room', 'generation', 'major')
            ->get();
    
        return response()->json([
            'message' => 'Get Successfully',
            'data' => $schedules
        ]);
    }
    
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'time_start' => 'required',
            'time_end' => 'required',
            'day_id'  => 'required',
            'term_id'  => 'required',
            'course_id'  => 'required',
            'room_id'  => 'required',
            'group_id'  => 'required',
            'major_id'  => 'required',
            'gen_id'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedule = Schedule::create($validator->validated());
        return response()->json([
            'message' => 'Schedule create successfuly',
            'data' => $schedule
        ]);
        // return response()->json(['test' => 'Hello'], 200);

    }
    public function update(Request $req, $id){
        $validator = Validator::make($req->all(), [
            'time_start' => 'required',
            'time_end' => 'required',
            'day_id'  => 'required',
            'term_id'  => 'required',
            'course_id'  => 'required',
            'room_id'  => 'required',
            'group_id'  => 'required',
            'gen_id'  => 'required',
            'major_id'  => 'required',
        ]);
        $schedule = Schedule::find($id);
        if(!$schedule){
            return response()->json(['message' => 'Item not found'], 404);
        }
        $schedule->update($validator->validated());
        return response()->json([
            'message' => 'Update successfully',
            'data' => $schedule
        ]);
    }
    public function delete($id){
        $schedule = Schedule::find($id);
        $schedule->delete();
        return response()->json([
            'message'=> 'Delete successfully',
        ]);
    }
}
