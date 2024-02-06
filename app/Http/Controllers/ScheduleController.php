<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

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
}
