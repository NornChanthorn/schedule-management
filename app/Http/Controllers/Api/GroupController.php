<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function index()
    {
        return Group::all();
    }

    public function show($id)
    {
        return Group::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group_name' => 'required',
        ]);

        return Group::create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'group_name' => 'required',
        ]);

        $group = Group::findOrFail($id);
        $group->update($data);

        return $group;
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json(['message' => 'Group deleted successfully']);
    }
}
