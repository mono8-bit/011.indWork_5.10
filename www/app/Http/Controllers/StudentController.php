<?php


namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return Student::with('room')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
        ]);

        $student = Student::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'room_id'    => null,
        ]);
        return response()->json($student, 201);
    }


    public function settle(Request $request, Student $student)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $roomId = $request->room_id;
        $room = Room::findOrFail($roomId);


        $currentCount = $room->students()->count();

        if ($currentCount >= $room->capacity) {
            return response()->json([
                'message' => 'В этой комнате больше нет свободных мест',
            ], 422);
        }

        $student->update(['room_id' => $roomId]);
        return response()->json($student->fresh()->with('room'), 200);
    }
}
