<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        // Search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        //Filter by room_type
        if ($request->has('room_type')) {
            $query->where('room_tpe', $request->room_type);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $rooms = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $room,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'room_type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $room = Room::created($request->all());

        return response()->json([
            'succsess' => true,
            'message' => 'Room created successfully',
            'data' => $room,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_number' => 'sometimes|string|unique:rooms,room_number,' . $id,
            'room_type' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'status' => 'sometimes|in:available,occupied,maintenance',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $room->update($request->all());

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
