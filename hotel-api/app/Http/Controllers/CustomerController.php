<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->withCount('reservations')
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    public function show($id)
    {
        $customer = User::where('role', 'customer')
            ->with('reservations.room')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ]);
    }

    public function destroy($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully',
        ]);
    }
}
