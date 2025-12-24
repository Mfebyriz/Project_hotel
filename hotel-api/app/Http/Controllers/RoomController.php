<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('category');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('room_category_id', $request->category_id);
        }

        // Search by room number
        if ($request->has('search')) {
            $query->where('room_number', 'like', "%{$request->search}%");
        }

        $rooms = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }

    public function show($id)
    {
        $room = Room::with('category')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $room,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_category_id' => 'required|exists:room_categories,id',
            'room_number' => 'required|string|unique:rooms',
        ]);

        $room = Room::create([
            'room_category_id' => $request->room_category_id,
            'room_number' => $request->room_number,
            'status' => 'available',
        ]);

        $room->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Room created successfully',
            'data' => $room,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_number' => 'sometimes|string|unique:rooms,room_number,' . $id,
            'status' => 'sometimes|in:available,occupied,maintenance',
            'room_category_id' => 'sometimes|exists:room_categories,id',
        ]);

        $room->update($request->all());
        $room->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Room updated successfully',
            'data' => $room
        ]);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Romm deleted successfully',
        ]);
    }
}
