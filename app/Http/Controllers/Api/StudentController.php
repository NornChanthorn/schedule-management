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
        'Email' => 'email',
        'Gender' => 'gender',
        'DateOfBith' => 'dob', // Corrected typo here
        'Generation'=>'gen',
        'Group'=>'group_name',
        'Major'=>'name'
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
                'name' => $studentData['FirstName'] . ' ' . $studentData['LastName'],
                'email' => $studentData['Email'],
                'password' => bcrypt($studentData['FirstName'] . $studentData['LastName']),
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
            $importedGenerations[]=$generation;
            $importedMajors[]=$major;
            $importedGroups[]=$group;
        }
    }

    // Return response with imported students
    return response()->json(['message' => 'Students imported successfully', 'students' => $importedStudents, 'users' => $importedUsers,'generations'=>$importedGenerations,'majors'=>$importedMajors,'groups'=>$importedGroups]);
}


    // public function import_student(Request $request)
    // {
    //     // Retrieve the uploaded file
    //     $file = $request->file('file');
    
    //     // Check if file is uploaded successfully
    //     if (!$file) {
    //         return response()->json(['error' => 'File not uploaded.'], 400);
    //     }
    
    //     // Read CSV file and process data
    //     $data = array_map('str_getcsv', file($file));
    //     $headers = array_shift($data);
    //     $dataToImport = array_slice($data, 0, count($data));
    
    //     // Array to store the imported teachers
    //     $importedStudents = [];
    //     $importedGenerations =[];
    //     $importedGroups=[];
    //     $importedMajors = [];
    //     $importedUsers = [];
    
    //     // Define a mapping between CSV headers and database column names
    //     $columnMapping = [
    //         'ID' =>'student_id',
    //         'FirstName' => 'f_name',
    //         'LastName' => 'l_name',
    //         'Gender' => 'gender',
    //         'DateOfBirth' => 'dob', // Corrected typo here
    //         'Generation' =>'gen',
    //         'Group' =>'group_name',
    //         'Major' =>'name',
    //         'Email' =>'email',
    //         // Add more mappings for other columns as needed
    //     ];
    
    //     // Iterate over rows and create teachers
    //     foreach ($dataToImport as $row) {
    //         // Skip empty rows
    //         if (array_filter($row)) {
    //             // Map CSV headers to database column names
    //             $studentData = [];
    //             foreach ($headers as $index => $header) {
    //                 if (isset($columnMapping[$header])) {
    //                     $studentData[$columnMapping[$header]] = $row[$index];
    //                 }
    //             }

    //             //Create new generation
    //             $generation = Generation::updateOrCreate([
    //                 'gen' =>$row[array_search('Generation', $headers)],
    //             ]);
                
    //              // Create new major
    //              $major = Major::updateOrcreate([
    //                 'name'=>$row[array_search('Major', $headers)],
    //             ]);
    
            
    //                  // Create or update group
    //             $group = Group::updateOrCreate(
    //                 [
    //                     'group_name' => $row[array_search('Group', $headers)],
    //                     'gen_id' => $generation->id,
    //                     'major_id' => $major->id,
    //                 ]
    //             );

    //             // Create a new user
    //             $user = User::create([
    //                 'name' => $row[array_search('FirstName', $headers)] . ' ' . $row[array_search('LastName', $headers)],
    //                 'email' => $row[array_search('Email', $headers)], // Assuming Email column is present
    //                 'password' => bcrypt($row[array_search('FirstName', $headers)] . $row[array_search('LastName', $headers)]), // You can generate a random password if needed
    //                 'role' => 'student',
    //             ]);
    
    //             // Create a new student and associate with the user
    //             $student = new Student();
    //             $student->fill($studentData);
    //             $student->user_id = $user->id; // Associate teacher with user
    //             $student->generation_id =$generation->id;
    //             $student->major_id =$major->id;
    //             $student->group_id =$group->id;
           
    //             $student->save();
    
    //             // Add the created teacher to the array
    //             $importedStudents[] = $student;
    //             $importedGenerations[]=$generation;
    //             $importedMajors[]=$major;
    //             $importedGroups[] = $group;

    //         }
    //     }
    
    //     // Return response with imported teachers
    //     return response()->json(['message' => 'Students imported successfully', 'students' => $importedStudents, 'users' => $importedUsers,'generations'=>$importedGenerations,'majors'=>$importedMajors,'groups'=>$importedGroups]);
    // }
    

}