<?php

namespace App\Http\Controllers\Api;

use App\Models\DayOfWeek;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DayOfWeekController extends Controller
{
    public function index()
    {
        return DayOfWeek::all();
    }

    public function show($id)
    {
        return DayOfWeek::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'day' => 'required',
        ]);

        return DayOfWeek::create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'day' => 'required',
        ]);

        $dayOfWeek = DayOfWeek::findOrFail($id);
        $dayOfWeek->update($data);

        return $dayOfWeek;
    }

    public function destroy($id)
    {
        $dayOfWeek = DayOfWeek::findOrFail($id);
        $dayOfWeek->delete();

        return response()->json(['message' => 'DayOfWeek deleted successfully']);
    }
}
