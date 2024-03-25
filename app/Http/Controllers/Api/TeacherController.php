<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            'data' => $data,
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

        return response()->json(['user' => $user, 'teacher' => $teacher], 201);
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
                'message' => 'delete success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    // public function import_teacher(Request $request)
    // {

    // //     // Validate the uploaded file
    // //     $validator = Validator::make($request->all(), [
    // //         'file' => 'required|file|mimes:csv,xlsx',
    // //     ]);

    // //     if ($validator->fails()) {
    // //         return response()->json(['error' => $validator->errors()], 400);
    // //     }

    //     // Retrieve the uploaded file
    //     $file = $request->file('file');

    //     // Read CSV file and process data
    //     $data = array_map('str_getcsv', file($file));
    //     $headers = array_shift($data); // Assuming the first row contains headers

    //     // Array to store the imported teachers
    //     $importedTeachers = [];
    //     $importUsers=[];
    //     // Iterate over rows and create teachers
    //     foreach ($data as $row) {
    //         // Create a new user
    //         $user = User::create([
    //             'name' => $row['FirstName'] . ' ' . $row['LastName'],
    //             'email' => $row['Email'], // Assuming Email column is present
    //             'password' => bcrypt($row['FirstName'] . $row['LastName']), // You can generate a random password if needed
    //             'role' => 'teacher',
    //         ]);

    //         // Create a new teacher and associate with the user
    //         $teacher = new Teacher();
    //         $teacher->title = $row['Title'];
    //         $teacher->f_name = $row['FirstName'];
    //         $teacher->l_name = $row['LastName'];
    //         $teacher->gender = $row['Gender'];
    //         $teacher->dob = $row['DateOfBith']; // Fix typo here
    //         $teacher->phone_num = $row['Phone_Number'];
    //         $teacher->user_id = $user->id; // Associate teacher with user
    //         $teacher->save();

    //         // Add the created teacher to the array
    //         $importedTeachers[] = $teacher;
    //         $importUsers[]=$user;
    //     }

    //     // Return response with imported teachers
    //     return response()->json(['message' => 'Teachers imported successfully', 'teachers' => $importedTeachers,'users'=>$importUsers]);
    // }

    public function import_teacher(Request $request)
{
    // Retrieve the uploaded file
    $file = $request->file('file');

    // Check if file is uploaded successfully
    if (!$file) {
        return response()->json(['error' => 'File not uploaded.'], 400);
    }
    $dateFormats = [
        'Y-m-d',   // yyyy-mm-dd
        'd/m/Y',   // dd/mm/yyyy
        // Add more formats as needed
    ];

    // Read CSV file and process data
    $data = array_map('str_getcsv', file($file));
    $headers = array_shift($data);
    $dataToImport = array_slice($data, 0, count($data));

    // Array to store the imported teachers
    $importedTeachers = [];
    $importUsers = [];

    // Define a mapping between CSV headers and database column names
    $columnMapping = [
        'FirstName' => 'f_name',
        'LastName' => 'l_name',
        'Email' => 'email',
        'Title' => 'title',
        'Gender' => 'gender',
        'DateOfBirth' => 'dob', // Corrected typo here
        'Phone_Number' => 'phone_num',
        // Add more mappings for other columns as needed
    ];

    // Iterate over rows and create teachers
    foreach ($dataToImport as $row) {
        // Skip empty rows
        if (array_filter($row)) {
            // Map CSV headers to database column names
            $teacherData = [];
            foreach ($headers as $index => $header) {
                if (isset($columnMapping[$header])) {
                    $teacherData[$columnMapping[$header]] = $row[$index];
                }
            }
            if (isset($teacherData['DateOfBirth'])) {
                $dateOfBirth = null;
                foreach ($dateFormats as $format) {
                    try {
                        // Attempt to parse the date using the current format
                        $dateOfBirth = DateTime::createFromFormat($format, $teacherData['DateOfBirth']);
                        if ($dateOfBirth !== false) {
                            // Date parsed successfully, break the loop
                            break;
                        }
                    } catch (\Exception $e) {
                        // Handle parsing errors (optional)
                        // You can log the error or display a message to the user
                        Log::error("Error parsing date for " . $row[array_search('FirstName', $headers)] . ": " . $e->getMessage());
                    }
                }

                if ($dateOfBirth !== null) {
                    // Format the date as 'yyyy-mm-dd' for database insertion
                    $teacherData['DateOfBirth'] = $dateOfBirth->format('Y-m-d');
                } else {
                    // If none of the formats succeeded, handle the error or skip this row
                    // For example, you can log an error message or display a notification to the user
                    Log::error("Unable to parse date for " . $row[array_search('FirstName', $headers)]);
                    continue; // Skip this row and proceed to the next one
                }
            }

            // Create a new user
            $user = User::create([
                'name' => $row[array_search('FirstName', $headers)] . ' ' . $row[array_search('LastName', $headers)],
                'email' => $row[array_search('Email', $headers)], // Assuming Email column is present
                'password' => bcrypt($row[array_search('FirstName', $headers)] . $row[array_search('LastName', $headers)]), // You can generate a random password if needed
                'role' => 'teacher',
            ]);

            // Create a new teacher and associate with the user
            $teacher = new Teacher();
            $teacher->fill($teacherData);
            $teacher->user_id = $user->id; // Associate teacher with user
            $teacher->save();

            // Add the created teacher to the array
            $importedTeachers[] = $teacher;
            $importUsers[] = $user;
        }
    }

    // Return response with imported teachers
    return response()->json(['message' => 'Teachers imported successfully', 'teachers' => $importedTeachers, 'users' => $importUsers]);
}



}
