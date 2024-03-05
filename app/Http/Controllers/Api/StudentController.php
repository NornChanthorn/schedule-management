<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Papa;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['generation', 'group', 'major'])->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'student_id' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'generation_id' => 'required|exists:generations,id',
            'group_id' => 'required|exists:groups,id',
            'major_id' => 'required|exists:majors,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $name = $request->f_name . ' ' . $request->l_name;

        // Create a new user
        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => bcrypt($name),
            'role' => 'student',
        ]);

        // Add the user_id to the request data
        $requestData = $request->all();
        $requestData['user_id'] = $user->id;

        $student = Student::create($requestData);
        return response()->json(['message' => 'Created successfully', 'data' => $student]);
    }


    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student->update($validator->validated());
        return response()->json(['message' => 'Updated successfully', 'data' => $student]);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
    public function getStudentByMajor($majorId){
        $data = Student::where('major_id', $majorId)->with('major', 'generation', 'group')->get();
        return response()->json($data);
    }
    private function validateStudentData($studentData)
    {
        $validator = Validator::make($studentData, [
            // Define validation rules for each column in your student schema
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'major_id' => 'required',
            'gen_id' => 'required',
            'group_id' => 'required',
            'student_id' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            // ... other columns and validation rules ...
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        return $studentData;
    }

    public function importStudents(Request $request)
    {
        // Validate file presence
        if (!$request->hasFile('studentCsv')) {
            return response()->json(['message' => 'No CSV file uploaded!'], 400);
        }

        $csvFile = $request->file('studentCsv');

        // Read CSV content
        $csvData = file_get_contents($csvFile);

        // Parse CSV data
        $parsedData = Papa::parse($csvData, [
            'header' => true,
        ]);

        // Validate student data
        $validatedData = $this->validateStudentData($parsedData['data']);

        // Save students to the database
        try {
            Student::create($validatedData); // Adjust table name accordingly
            return response()->json(['message' => 'Students imported successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error importing students!', 'error' => $e->getMessage()], 500);
        }
    }


}
