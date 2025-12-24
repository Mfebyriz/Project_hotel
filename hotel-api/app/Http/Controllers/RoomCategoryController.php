<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RoomCategory;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class RoomCategoryController extends Controller
{
    public function index()
    {
        $categories = RoomCategory::withCount('rooms')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function show($id)
    {
        $category = RoomCategory::with('rooms')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:8',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
        ]);

        $category = RoomCategory::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Room category created successfully',
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = RoomCategory::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|striing',
            'capacity' => 'sometimes|integer|min:1',
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Room category updated successfully',
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = RoomCategory::findOrFail($id);

        // Check if there are rooms using this category
        if ($category->rooms()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with exixting rooms',
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Room category deleted successfully',
        ]);
    }
}
