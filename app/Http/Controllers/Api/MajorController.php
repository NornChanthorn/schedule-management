<?php

// app/Http/Controllers/Api/MajorController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Course;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $majors = Major::all();
        return response()->json($majors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $major = Major::create($validatedData);

        return response()->json($major, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */

    public function show(Request $req, $id){
        $major = Major::where('id', $id)->get();
        if(!$id){
            return response()->json(['message'=> 'Major no found']);
        }
        return response()->json([
            'message'=> 'gettt successfully',
            'data' => $major
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Major $major)
    {
        $validatedData = $request->validate([
            'name' => 'string',
        ]);

        $major->update($validatedData);

        return response()->json($major, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $major = Major::findOrFail($id);
            $courses = Course::where('major_id', $id)->get();
            $students = Student::where('major_id', $id)->get();
            $groups = Group::where('major_id', $id)->get();
            if($courses!=null){
                foreach ($courses as $course){
                    $schedules = Schedule::where('major_id', $course->id)->get();
                    if($schedules !=null){
                        foreach($schedules as $schedule){
                            $schedule->delete();
                        }

                    }
                    $course->delete();
                }
            }
            if($students!=null){
                foreach($students as $student){
                    $student->delete();
                }
            }
            if($groups!=null){
                foreach($groups as $group){
                    $group->delete();
                }
            }
            $major->delete();
            return response()->json([
                'message'=> 'delete successfully'
            ]);

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}

