<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        // withCount подсчитывает студентов в комнате
        return Room::withCount('students')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms',
            'floor'       => 'required|integer',
            'capacity'    => 'required|integer|min:1',
        ]);

        $room = Room::create($request->only(['room_number', 'floor', 'capacity']));
        return response()->json($room, 201);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->noContent();
    }
}