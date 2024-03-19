<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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

        return response()->json(['user' => $user, 'teacher' => $teacher],201);
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

    // public function import(Request $request)
    // {
    //     // Validate the request
    //     $validator = Validator::make($request->all(), [
    //         'file' => 'required|file|mimes:csv,txt',
    //     ]);

    //     // Return error response if validation fails
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // Get the uploaded file
    //     $file = $request->file('file');

    //     // Parse the CSV file and create teachers
    //     try {
    //         $handle = fopen($file, 'r');

    //         // Skip the header row
    //         fgetcsv($handle);

    //         while (($row = fgetcsv($handle, 1000, ',')) !== false) {
    //             // Extract data from the CSV row
    //             $id=$row[0];
    //             $title = $row[0];
    //             $f_name = $row[1];
    //             $l_name = $row[2];
    //             $gender = $row[3];
    //             $dob = $row[4];
    //             $phone_num = $row[5];
    //             $email = $row[6]; // Assuming email is in the 7th column

    //             // Create user
    //             $user = User::create([
    //                 'name' => $f_name . ' ' . $l_name,
    //                 'email' => $email,
    //                 'password' => bcrypt($f_name . $l_name), // You might want to generate a random password here
    //                 'role' => 'teacher',
    //             ]);

    //             // Create teacher
    //             Teacher::create([
    //                 'title' => $title,
    //                 'f_name' => $f_name,
    //                 'l_name' => $l_name,
    //                 'gender' => $gender,
    //                 'dob' => $dob,
    //                 'phone_num' => $phone_num,
    //                 'user_id' => $user->id,
    //             ]);
    //         }

    //         fclose($handle);

    //         return response()->json(['message' => 'Teachers imported successfully'], 200);
    //     } catch (\Exception $e) {
    //         // Handle any exceptions
    //         return response()->json(['message' => 'Failed to import teachers'], 500);
    //     }
    // }

    
    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:csv,xlsx', // Validate file type
    //     ]);

    //     $file = $request->file('file');

    //     // Read CSV file and process data
    //     $data = array_map('str_getcsv', file($file));
    //     $headers = array_shift($data); // Assuming the first row contains headers

    //     $createdTeachers = [];
    //     foreach ($data as $row) {
    //         $teacherData = [
    //             'title' => $row[0], // Adjust column index based on your CSV structure
    //             'f_name' => $row[1],
    //             'l_name' => $row[2],
    //             'gender' => $row[3],
    //             'dob' => $row[4],
    //             'phone_num' => $row[5],
    //             // You can add more fields as needed
    //         ];

    //         // Create a new user for each teacher
    //         $user = User::create([
    //             'name' => $teacherData['f_name'] . ' ' . $teacherData['l_name'],
    //             'email' => '', // You can leave email blank or handle it differently
    //             'password' => '', // You can generate a random password if needed
    //             'role' => 'teacher',
    //         ]);

    //         // Add the user_id to the teacherData array
    //         $teacherData['user_id'] = $user->id;

    //         // Create the teacher
    //         $teacher = Teacher::create($teacherData);

    //         $createdTeachers[] = $teacher;
    //     }
    //     return response()->json(['message' => 'Teachers imported successfully', 'teachers' => $createdTeachers]);
    //     // return response()->json(['user' => $user, 'teacher' => $teacher]);
    // }

    // public function import(Request $request)
    // {
    //     // Assuming CSV file upload, adjust this based on your actual data format
    //     $request->validate([
    //         'file' => 'required|file|mimes:csv,txt', // Validate file type
    //     ]);

    //     $file = $request->file('file');

    //     // Read CSV file and process data
    //     $data = array_map('str_getcsv', file($file));
    //     $headers = array_shift($data); // Assuming the first row contains headers

    //     // Iterate over rows and create teachers
    //     $createdTeachers = [];
    //     foreach ($data as $row) {
    //         $teacherData = [
    //             'id' =>$row['NO'] ?? '',
    //             'title' => $row['Title'] ?? '', // Add default value if column is empty
    //             'f_name' => $row['FirstName'] ?? '',
    //             'l_name' => $row['LastName'] ?? '',
    //             'gender' => $row['Gender'] ?? '',
    //             'dob' => $row['DateOfBith'] ?? '', // Fix typo in column name
    //             'phone_num' => $row['Phone_Number'] ?? '',
    //         ];
    //         $userData = $request->validate([
    //             // 'name' => 'string',
    //             'email' => 'required',
    //             // 'password' => 'required',
    //             // 'role' => 'string',
    //         ]);
    
    //         $name = $teacherData['f_name'] . ' ' . $teacherData['l_name'];
    //         // Create a new user for each teacher
    //         $user = User::create([
    //             'name' => $name,
    //             'email' => $row['Email'] ?? '', // Add default value if column is empty
    //             'password' => bcrypt($name), // You can generate a random password if needed
    //             'role' => 'teacher',
    //         ]);

    //         // Add the user_id to the teacherData array
    //         $teacherData['user_id'] = $user->id;

    //         // Create the teacher
    //         $teacher = Teacher::create($teacherData);

    //         $createdTeachers[] = $teacher;
    //     }

    //     return response()->json(['message' => 'Teachers imported successfully', 'teachers' => $createdTeachers]);
    // }

//     public function import(Request $request)
// {
//     // Validate the uploaded file
//      // Validate the request
//      $validator = Validator::make($request->all(), [
//         'file' => 'required|file|mimes:csv,txt',
//     ]);
    
//      // Return error response if validation fails
//      if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     // Retrieve the uploaded file
//     $file = $request->file('file');

//     // Read CSV file and process data
//     $data = array_map('str_getcsv', file($file));
//     $headers = array_shift($data); // Assuming the first row contains headers

//     // Array to store the created teachers
//     $createdTeachers = [];

//     // Iterate over rows and create teachers
//     foreach ($data as $row) {
//         $teacherData = [
//             'title' => $row['Title'] ?? '', // Add default value if column is empty
//             'f_name' => $row['FirstName'] ?? '',
//             'l_name' => $row['LastName'] ?? '',
//             'gender' => $row['Gender'] ?? '',
//             'dob' => $row['DateOfBith'] ?? '', // Fix typo in column name
//             'phone_num' => $row['Phone_Number'] ?? '',
//         ];

//         // Validate email (optional)
//         $userData = $request->validate([
//             'email' => 'required|email',
//         ]);

//         $name = $teacherData['f_name'] . ' ' . $teacherData['l_name'];

//         // Create a new user for each teacher
//         $user = User::create([
//             'name' => $name,
//             'email' => $userData['email'], // Use the provided email
//             'password' => bcrypt($name), // You can generate a random password if needed
//             'role' => 'teacher',
//         ]);

//         // Add the user_id to the teacherData array
//         $teacherData['user_id'] = $user->id;

//         // Create the teacher
//         $teacher = Teacher::create($teacherData);

//         $createdTeachers[] = $teacher;
//     }

//     return response()->json(['message' => 'Teachers imported successfully', 'teachers' => $createdTeachers]);
// }


// public function import(Request $request)
// {
//     // Validate the request
//     $request->validate([
//         'file' => 'required|file|mimes:csv,xlsx',
//     ]);

//     // Retrieve the uploaded file
//     $file = $request->file('file');

//     // Read CSV file and process data
//     $data = array_map('str_getcsv', file($file));
//     $headers = array_shift($data); // Assuming the first row contains headers

//     // Array to store the created teachers
//     $importedTeachers = [];

//     // Iterate over rows and create teachers
//     foreach ($data as $row) {
//         // Extract user data from CSV row
//         $userData = [
//             'name' => $row['FirstName'] . ' ' . $row['LastName'],
//             'email' => $row['Email'], // Assuming Email column is present
//             'password' => bcrypt($row['FirstName'] . $row['LastName']), // You can generate a random password if needed
//             'role' => 'teacher',
//         ];

//         // Create the user
//         // $user = User::create($userData);
//         $user = User::firstOrCreate(['email' => $userData['email']], $userData);

//         // Extract teacher data from CSV row
//         $teacherData = [
//             'title' => $row['Title'],
//             'f_name' => $row['FirstName'],
//             'l_name' => $row['LastName'],
//             'gender' => $row['Gender'],
//             'dob' => $row['DateOfBith'], // Fix typo in column name
//             'phone_num' => $row['Phone_Number'],
//             'user_id' => $user->id,
//         ];

//         // Create the teacher
//         $teacher = Teacher::create($teacherData);

//         // Add the created teacher to the array
//         $importedTeachers[] = $teacher;
//     }

//     // Return response with imported teachers
//     return response()->json(['message' => 'Teachers imported successfully', 'teachers' => $importedTeachers]);
// }
public function import(Request $request)
{
   
    
    // Validate the uploaded file
    $validator = Validator::make($request->all(), [
        'file' => 'required|file|mimes:csv',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Retrieve the uploaded file
    $file = $request->file('file');

    // Read CSV file and process data
    $data = array_map('str_getcsv', file($file));
    $headers = array_shift($data); // Assuming the first row contains headers

    // Array to store the imported teachers
    $importedTeachers = [];
    $importUsers=[];
    // Iterate over rows and create teachers
    foreach ($data as $row) {
        // Create a new user
        $user = User::create([
            'name' => $row['FirstName'] . ' ' . $row['LastName'],
            'email' => $row['Email'], // Assuming Email column is present
            'password' => bcrypt($row['FirstName'] . $row['LastName']), // You can generate a random password if needed
            'role' => 'teacher',
        ]);
       
        // Create a new teacher and associate with the user
        $teacher = new Teacher();
        $teacher->title = $row['Title'];
        $teacher->f_name = $row['FirstName'];
        $teacher->l_name = $row['LastName'];
        $teacher->gender = $row['Gender'];
        $teacher->dob = $row['DateOfBith']; // Fix typo here
        $teacher->phone_num = $row['Phone_Number'];
        $teacher->user_id = $user->id; // Associate teacher with user
        $teacher->save();

        // Add the created teacher to the array
        $importedTeachers[] = $teacher;
        $importUsers[]=$user;
    }

    // Return response with imported teachers
    return response()->json(['message' => 'Teachers imported successfully', 'teachers' => $importedTeachers,'users'=>$importUsers]);
}


}