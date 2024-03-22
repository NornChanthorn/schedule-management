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
use Generator;
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
            'generation_id' => 'required',
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
    
        // Array to store the imported teachers
        $importedStudents = [];
        $importUsers = [];
        $importedGenerations=[];
        $importedMajors=[];
        $importedGroups=[];
    
        // Define a mapping between CSV headers and database column names
        $columnMapping = [
            'ID'=>'student_id',
            'FirstName' => 'f_name',
            'LastName' => 'l_name',
            'Email' => 'email',
            'Gender' => 'gender',
            'DateOfBirth' => 'dob', // Corrected typo here
            'Generation' => 'gen', // Assuming 'Generation' contains the ID of the generation
            'Major' => 'name',
            'Group' => 'group_name'
        
            // Add more mappings for other columns as needed
        ];
    
        // Iterate over rows and create teachers
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

                $generation = Generation::updateOrCreate(['gen' => $row[array_search('Generation', $headers)]]);
                $major = Major::updateOrCreate(['name' => $row[array_search('Major', $headers)]]);
                $groupData = [
                    'group_name' => $row[array_search('Group', $headers)],
                    'gen_id' => $generation->id,
                    'major_id' => $major->id
                ];
                $group = Group::updateOrCreate(['group_name' => $groupData['group_name']], $groupData);
    

                // Create a new user
                $user = User::create([
                    'name' => $row[array_search('FirstName', $headers)] . ' ' . $row[array_search('LastName', $headers)],
                    'email' => $row[array_search('Email', $headers)], // Assuming Email column is present
                    'password' => bcrypt($row[array_search('FirstName', $headers)] . $row[array_search('LastName', $headers)]), // You can generate a random password if needed
                    'role' => 'student',
                ]);

    
                // Create a new teacher and associate with the user
                $student = new Student();
                $student->fill($studentData);
                $student->user_id = $user->id; // Associate teacher with user
                $student->generation_id = $generation->id; 
                $student->major_id = $major->id; 
                $student->group_id = $group->id; 
                $student->save();
    
                // Add the created teacher to the array
                $importedStudent[] = $student;
                $importUsers[] = $user;
                $importedGenerations[]=$generation;
                $importedMajors[]=$major;
                $importedGroups[]=$group;
            }
        }
    
        // Return response with imported teachers
        return response()->json(['message' => 'Students imported successfully', 'students' => $importedStudents, 'users' => $importUsers,'generations'=>$importedGenerations,'majors'=>$importedMajors,'groups'=>$importedGroups]);
    }
    

//     public function import_student(Request $request)
// {
//     $file = $request->file('file');

//     // Check if file is uploaded successfully
//     if (!$file) {
//         return response()->json(['error' => 'File not uploaded.'], 400);
//     }

//     // Read CSV file and process data
//     $data = array_map('str_getcsv', file($file));
//     $headers = array_shift($data);
//     $dataToImport = array_slice($data, 0, count($data));

//     // Array to store the imported students
//     $importedStudents = [];
//     $importUsers = [];
//     $columnMapping = [
//         'FirstName' => 'f_name',
//         'LastName' => 'l_name',
//         'ID'=>'student_id',
//         'Email' => 'email',
//         'Gender' => 'gender',
//         'DateOfBirth' => 'dob', // Corrected typo here
//         // Add more mappings for other columns as needed
//     ];
//     // Iterate over rows and create students
//     foreach ($data as $row) {
//         // Skip empty rows
//         if (array_filter($row)) {
//              // Assuming you have a way to get generationName and majorName from the row
//         $generationName = $row['Generation'];
//         $majorName = $row['Major'];
//         $groupName = $row['Group'];

//         // Fetch IDs for generation, major, and group
//         $generationId = $this->fetchGenerationId($generationName);
//         $majorId = $this->fetchMajorId($majorName);
//         $groupId = $this->fetchGroupId($groupName, $majorId, $generationId);

//         $studentData = [];
//             foreach ($headers as $index => $header) {
//                 if (isset($columnMapping[$header])) {
//                     $studentData[$columnMapping[$header]] = $row[$index];
//                 }
//             }

//             // Create a new user
//             $user = User::create([
//                 'name' => $row[array_search('FirstName', $headers)] . ' ' . $row[array_search('LastName', $headers)],
//                 'email' => $row[array_search('Email', $headers)], // Assuming Email column is present
//                 'password' => bcrypt($row[array_search('FirstName', $headers)] . $row[array_search('LastName', $headers)]), // You can generate a random password if needed
//                 'role' => 'student',
//             ]);

//             // Create a new student
//             $student = new Student();
//             $student->fill($studentData);  // $student->f_name = $row['FirstName'];
//             // $student->l_name = $row['LastName'];
//             // $student->student_id = $row['ID'];
//             // $student->gender = $row['Gender'];
//             // $student->dob = $row['DateOfBith'];
//             // $student->email = $row['Email'];
//             $student->generation_id = $generationId;
//             $student->major_id = $majorId;
//             $student->group_id = $groupId;
//             $student->save();

//             // Add the created student to the array
//             $importedStudents[] = $student;
//             $importUsers[]=$user;
//         }
//     }

//     // Return response with imported students
//     return response()->json(['message' => 'Students imported successfully', 'students' => $importedStudents,'users'=>$importUsers]);
// }

// private function fetchGenerationId($generationName)
// {
//     // Retrieve generation by name
//     $generation = Generation::where('gen', $generationName)->first();

//     // If generation exists, return its ID; otherwise, create a new generation and return its ID
//     if ($generation) {
//         return $generation->id;
//     } else {
//         $newGeneration = Generation::create(['gen' => $generationName]);
//         return $newGeneration->id;
//     }
// }

// private function fetchMajorId($majorName)
// {
//     // Retrieve major by name
//     $major = Major::where('name', $majorName)->first();

//     // If major exists, return its ID; otherwise, create a new major and return its ID
//     if ($major) {
//         return $major->id;
//     } else {
//         $newMajor = Major::create(['name' => $majorName]);
//         return $newMajor->id;
//     }
// }
// private function fetchGroupId($groupName, $majorId, $generationId)
// {
//     // Retrieve group by name, major_id, and generation_id
//     $group = Group::where('group_name', $groupName)
//                    ->where('major_id', $majorId)
//                    ->where('gen_id', $generationId)
//                    ->first();

//     // If group exists, return its ID; otherwise, create a new group and return its ID
//     if ($group) {
//         return $group->id;
//     } else {
//         $newGroup = Group::create([
//             'group_name' => $groupName,
//             'major_id' => $majorId,
//             'gen_id' => $generationId
//         ]);
//         return $newGroup->id;
//     }

// }

// public function import(Request $request)
// {
//     $file = $request->file('file');
//     if (!$file) {
//         return response()->json(['error' => 'File not uploaded.'], 400);
//     }

//     $data = array_map('str_getcsv', file($file));
//     $headers = array_shift($data);
//     $importedStudents = [];

//     foreach ($data as $row) {
//         if (array_filter($row)) {
//             $generationId = $this->fetchGenerationId($row['Generation']);
//             $majorId = $this->fetchMajorId($row['Major']);
//             $groupId = $this->fetchGroupId($row['Group'], $majorId, $generationId);

//             // Your student creation logic goes here
//              // Create user data
//              $user = new User();
//              $user->name = $row['FirstName'] . ' ' . $row['LastName']; // Concatenate first name and last name for user name
//              $user->email = $row['Email']; // Assuming email is provided
//              $user->password =  bcrypt($row[array_search('FirstName', $headers)] . $row[array_search('LastName', $headers)]); // Set a default password for now, change as needed
//              $user->role = 'student'; // Assuming the role is 'student' for imported users
//              $user->save();

             
//                 // Create student data
//                 $student = new Student();
//                 $student->f_name = $row['FirstName'];
//                 $student->l_name = $row['LastName'];
//                 $student->student_id = $row['ID'];
//                 $student->gender = $row['Gender'];
//                 $student->dob = $row['DateOfBirth'];
//                 $student->email = $row['Email'];
//                 $student->generation_id = $generationId;
//                 $student->major_id = $majorId;
//                 $student->group_id = $groupId;
//                 $student->user_id = $user->id; // Assign the user ID to the student
//                 $student->save();
                
//             $importedStudents[] = $student;
//         }
//     }

//     return response()->json(['message' => 'Students imported successfully', 'students' => $importedStudents]);
// }

// private function fetchGenerationId($generationName)
// {
//     $generation = Generation::where('gen', $generationName)->first();

//     if ($generation) {
//         return $generation->id;
//     } else {
//         $newGeneration = Generation::create(['gen' => $generationName]);
//         return $newGeneration->id;
//     }
// }

// private function fetchMajorId($majorName)
// {
//     $major = Major::where('name', $majorName)->first();

//     if ($major) {
//         return $major->id;
//     } else {
//         $newMajor = Major::create(['name' => $majorName]);
//         return $newMajor->id;
//     }
// }

// private function fetchGroupId($groupName, $majorId, $generationId)
// {
//     $group = Group::where('group_name', $groupName)
//         ->first();

//     if ($group) {
//         return $group->id;
//     } else {
//         $newGroup = Group::create([
//             'group_name' => $groupName,
//             'major_id' => $majorId,
//             'gen_id' => $generationId
//         ]);
//         return $newGroup->id;
//     }
// }
}