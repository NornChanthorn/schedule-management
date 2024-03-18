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

    public function getteacherbyuserid($user_id)
    {
        $data = Teacher::whereHas('user', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        })->with('user')->get();

        return response()->json([
            'message' => 'Successfull',
            'data' => $data
        ]);
    }

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


    public function update(Request $request, Teacher $teacher)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'phone_num' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->user->id,
        ]);

        // Update teacher data
        $teacher->update($validatedData);

        // Update user email and name (if f_name, l_name, or email is changed)
        $teacher->user->update([
            'name' => $validatedData['f_name'] . ' ' . $validatedData['l_name'],
            'email' => $validatedData['email'],
        ]);

        return response()->json(['message' => 'Teacher and user updated successfully.', 'teacher' => $teacher->fresh()]);
    }


    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $user_id = $teacher->user_id;

            $courses = Course::where('teacher_id', $id)->get();
            if ($courses != null) {
                foreach ($courses as $course) {
                    $schedules = Schedule::where('course_id', $course->id)->get();
                    if ($schedules != null) {
                        foreach ($schedules as $schedule) {
                            $schedule->delete();
                        }
                    }
                    $course->delete();
                }
            }

            $teacher->delete();

            // Delete the associated user
            User::destroy($user_id);

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
