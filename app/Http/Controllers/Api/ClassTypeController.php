<?php

namespace App\Http\Controllers\Api;

use App\Models\ClassType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassTypeController extends Controller
{
    public function index()
    {
        return ClassType::all();
    }

    public function show($id)
    {
        return ClassType::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
        ]);

        return ClassType::create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'type' => 'required',
        ]);

        $classType = ClassType::findOrFail($id);
        $classType->update($data);

        return $classType;
    }

    public function destroy($id)
    {
        $classType = ClassType::findOrFail($id);
        $classType->delete();
        return response()->json(['message' => 'ClassType deleted successfully']);
    }
}
