<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $revenue = Payment::where('payment_status', 'paid')
            ->whereBetween('payment_status', [$startDate, $endDate])
            ->selectRaw('SUM(amount + late_fee) as total_revenue')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $revenue->total_revenue ?? 0,
                'period' => [
                    'start' => $startDate,
                    'end' => $endDate,
                ],
            ],
        ]);
    }

    public function occupancy(Request $request)
    {
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_rooms' => $totalRooms,
                'occupied_rooms' => $occupiedRooms,
                'available_rooms' => $totalRooms - $occupiedRooms,
                'occupancy_rate' => round($occupancyRate, 2),
            ],
        ]);
    }

    public function reservations(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $reservations = Reservation::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }
}
