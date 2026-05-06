<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(
            Room::with('beds')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|unique:rooms,number',
            'name' => 'nullable|string|max:255',
            'floor' => 'nullable|integer',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:private,shared',
            'status' => 'required|in:available,maintenance,inactive'
        ]);

        $room = Room::create($validated);

        return response()->json([
            'message' => 'Habitación creada',
            'data' => $room
        ], 201);
    }

    public function show(Room $room)
    {
        return response()->json(
            $room->load('beds')
        );
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'number' => 'sometimes|unique:rooms,number,' . $room->id,
            'name' => 'nullable|string|max:255',
            'floor' => 'nullable|integer',
            'capacity' => 'sometimes|integer|min:1',
            'type' => 'sometimes|in:private,shared',
            'status' => 'sometimes|in:available,maintenance,inactive'
        ]);

        $room->update($validated);

        return response()->json($room);
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return response()->json([
            'message' => 'Habitación eliminada'
        ]);
    }
}