<?php

namespace App\Http\Controllers\Api;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;

class TeacherController extends Controller
{
    public function index()
    {
        return Teacher::with('user')->get();
    }

    public function show($id)
    {
        return Teacher::with('user')->findOrFail($id);
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'user_id' => 'required',
    //         'title' => 'required',
    //         'f_name' => 'required',
    //         'l_name' => 'required',
    //         'gender' => 'required',
    //         'dob' => 'required',
    //         'phone_num' => 'required',
    //     ]);

    //     return Teacher::create($data);
    // }

    public function store(Request $request)
    {
        $userData = $request->validate([
            // 'name' => 'string',
            'email' => 'required',
            // 'password' => 'required',
            // 'role' => 'string',
        ]);

        $teacherData = $request->validate([
            'title' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'phone_num' => 'required',
        ]);

        $name = $teacherData['f_name'] . ' ' . $teacherData['l_name'];

        // Create a new user
        $user = User::create([
            'name' => $name,
            'email' => $userData['email'],
            'password' => bcrypt($name),
            'role' => 'teacher',
        ]);

        // Add the user_id to the teacherData array
        $teacherData['user_id'] = $user->id;

        // Create a new teacher
        $teacher = Teacher::create($teacherData);

        return response()->json(['user' => $user, 'teacher' => $teacher]);
    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            // 'user_id' => 'required',
            'title' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'phone_num' => 'required',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($data);

        return $teacher;
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $courses = Course::where('teacher_id', $id)->get();
            if($courses!=null){
                foreach ($courses as $course){
                    $schedules = Schedule::where('course_id', $course->id)->get();
                    if($schedules!=null){
                        foreach ($schedules as $schedule) {
                            $schedule->delete();
                        }
                    }
                    $course->delete();
                }
            }
            $teacher->delete();
            return response()->json([
                'message' => 'delete success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    // Course::where('teacher_id', $id)->delete();
    // $teacher = Teacher::findOrFail($id);
    // $teacher->delete();
    // return response()->json(['message' => 'Teacher deleted successfully']);
}
