<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Schema;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::all();
        return response()->json($rooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'size' => 'required|integer',
            'type' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $room = Room::create($validatedData);

        return response()->json($room, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        return response()->json($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'size' => 'required|integer',
            'type' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $room->update($validatedData);

        return response()->json($room, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $id)
    {
        $room = Room::findOrFail($id);
        if(Schema::hasTable('schedules') && Schema::hasForeignKey(['room_id'])){
            $schedules = Schedule::where('room_id', $room->id)->get();
            foreach ($schedules as $schedule) {
                $schedule->delete();
            }
        }
        // Schedule::where('room_id', $id)->delete();

        $room->delete();

        return response()->json(null, 204);
    }
}

