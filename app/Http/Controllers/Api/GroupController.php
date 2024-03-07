<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function index()
    {
        $data = Group::with('generation', 'major')->get();
        return response()->json([
            'message' => 'Get Data successful',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = Group::where('id', $id)->whit('generation', 'major')->get();
        if (!$id) {
            return response()->json(['message' => 'Group no found']);
        }
        return response()->json([
            'message' => 'get successfully',
            'data' => $data
        ]);

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group_name' => 'required',
            'gen_id'=> 'required',
            'major_id' => 'required'
        ]);

        $group = Group::create($data);
        return response()->json([
            'message' => 'create successfully',
            'data' => $group
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'group_name' => 'required',
            'gen_id'=> 'required',
            'major_id' => 'required'
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
    public function getGroupByMG($gen_id, $major_id){
        $data = Group::where('major_id', $major_id)
        ->where('gen_id', $gen_id)
        ->with('generation', 'major')
        ->get();

        return response()->json([
            'message' => 'Get Successfully',
            'data' => $data
        ]);

    }
}
