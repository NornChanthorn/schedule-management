<?php

namespace App\Http\Controllers\Api;

use Papa;
use App\Models\User;
use App\Models\Student;
use App\Models\Generation;
use App\Models\Group;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['generation', 'group', 'major','user'])->get();
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
            'generation_id' => 'required',
            'group_id' => 'required',
            'major_id' => 'required',
            'email' => 'required|email|unique:users,email,'.$student->user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student->update($validator->validated());

        // Update user email and name (if f_name, l_name, or email is changed)
        $student->user->update([
            'name' => $request->f_name . ' ' . $request->l_name,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'Updated successfully', 'data' => $student]);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $user = $student->user;
        $student->delete();
        $user->delete();

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
    public function import_student(Request $request)
{
    // Retrieve the uploaded file
    $file = $request->file('file');

    // Check if file is uploaded successfully
    if (!$file) {
        return response()->json(['error' => 'File not uploaded.'], 400);
    }

    // Read CSV file and process data
    $data = array_map('str_getcsv', file($file));
    $headers = array_shift($data);
    $dataToImport = array_slice($data, 0, count($data));

    // Array to store the imported students
    $importedStudents = [];
    $importedGenerations =[];
    $importedGroups=[];
    $importedMajors = [];
    $importedUsers = [];

    $columnMapping = [
        'ID'=>'student_id',
        'FirstName' => 'f_name',
        'LastName' => 'l_name',

        'Gender' => 'gender',
        'DateOfBith' => 'dob', // Corrected typo here
        'Generation'=>'gen_id',
        'Group'=>'group_id',
        'Major'=>'major_id',
        'Email' => 'email',
        // Add more mappings for other columns as needed
    ];


    // Iterate over rows and create students
    foreach ($dataToImport as $row) {
        // Skip empty rows
        if (array_filter($row)) {
            // Map CSV headers to database column names
            $studentData = [];
            foreach ($headers as $index => $header) {
                if (isset($columnMapping[$header])) {
                    $studentData[$columnMapping[$header]] = $row[$index];
                }
            }

            // Validate required fields
            if (!isset($studentData['f_name'], $studentData['l_name'], $studentData['email'])) {
                return response()->json(['error' => 'Missing required fields in CSV.'], 400);
            }

            // Validate email address
            if (!filter_var($studentData['email'], FILTER_VALIDATE_EMAIL)) {
                return response()->json(['error' => 'Invalid email address: ' . $studentData['email']], 400);
            }

            // Check if the generation exists, otherwise create it
            $generation = Generation::firstOrCreate(['gen' => $studentData['Generation']]);

            // Check if the major exists, otherwise create it
            $major = Major::firstOrCreate(['name' => $studentData['Major']]);

            // Check if the group exists, otherwise create it
            $group = Group::firstOrCreate([
                'group_name' => $studentData['Group'],
                'gen_id' => $generation->id,
                'major_id' => $major->id,
            ]);

            // Create a new user
            $user = User::create([
                'name' => $studentData['f_name'] . ' ' . $studentData['l_name'],
                'email' => $studentData['email'],
                'password' => bcrypt($studentData['f_name'] . $studentData['l_name']), // You can generate a random password if needed
                'role' => 'student',
            ]);

            // Create a new student and associate with the user, generation, major, and group
            $student = new Student();
            $student->fill($studentData);
            $student->user_id = $user->id;
            $student->generation_id = $generation->id;
            $student->major_id = $major->id;
            $student->group_id = $group->id;
            $student->save();

            // Add the created student to the array
            $importedStudents[] = $student;
        }
    }

    // Return response with imported students
    return response()->json(['message' => 'Students imported successfully', 'students' => $importedStudents, 'users' => $importedUsers]);
}




}
