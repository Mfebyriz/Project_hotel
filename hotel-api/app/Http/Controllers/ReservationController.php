<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Payment;
use App\Models\Notification;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'room', 'payment']);

        // reservation
        if ($request->user()->isCustomer()) {
            $query->where('user_id', $request->user()->id);
        }

        //Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $reservations,
        ]);
    }

    public function show($id)
    {
        $reservation = Reservation::with(['user', 'room', 'payment'])->findOrFail($id);

        // check authorization
        if (request()->user()->isCustomer() && $reservation->user_id !== request()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $reservation,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:room,id',
            'check_in_date' => 'required|date|after_pr_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'notes' => 'nullable|string',
        ]);

        $room = Room::findOrFail($request->room_id);

        if (!$room->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Room is not available',
            ], 400);
        }

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $totalNights = $checkOut->diffInDays($checkIn);
        $totalPrice = $totalNights * $room->price;

        $reservation = Reservation::create([
            'user_id' => $request->user()->id,
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'total_nights' => $totalNights,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'notes' => $request->notes,
        ]);

        // payment record
        Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $totalPrice,
            'payment_status' => 'pending',
        ]);

        // update room status
        $room->update(['status' => 'occupied']);

        // notification for customer
        Notification::create([
            'user_id' => $request->user()->id,
            'title' => 'Reservasi Berhasil',
            'message' => "Reservasi kamar {$room->room_number} berhasil dibuat untuk tanggal {$checkIn->format('d/m/Y')}",
            'type' => 'info',
        ]);

        return response()->json([
            'succsess' => true,
            'message' => 'Reservation created successfully',
            'data' => $reservation->load(['room', 'payment']),
        ], 201);
    }

    public function checkIn($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Reservation must be confirmed before check-in',
            ], 400);
        }

        $reservation->update([
            'status' => 'checked_in',
            'actual_check_in' => now(),
        ]);

        Notification::create([
            'user_id' => $reservation->user_id,
            'title' => 'Check-in Berhasil',
            'message' => "Anda telah check-in di kamar {$reservation->room->room_number}",
            'type' => 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'data' => $reservation,
        ]);
    }

    public function checkOut(Request $request, $id)
    {
        $reservation = Reservation::with('payment')->findOrFail($id);

        if ($reservation->status !== 'check_in') {
            return response()->json([
                'success' => false,
                'message' => 'Reservation must be checked-in before check-out',
            ], 400);
        }

        $actualCheckOut = now();
        $lateFee = 0;


        // Calculate late fee if checkout is late
        if ($actualCheckOut->greaterThan($reservation->check_out_date)) {
            $hoursLate = $actualCheckOut->diffInHours($reservation->check_out_date);
            $lateFee = $hoursLate * ($reservation->room->price / 24); // hourly rate
        }

        $reservation->update([
            'status' => 'checked_out',
            'actual_check_out' => $actualCheckOut,
        ]);

        // Update payment with late fee
        $reservation->payment->update([
            'late_fee' => $lateFee,
        ]);

        // Update room status to available
        $reservation->room->update(['status' => 'available']);

        $message = 'Check-out berhasil';
        if ($lateFee > 0) {
            $message .= ". Denda keterlambatan: Rp " . number_format($lateFee, 0, ',', '.');
        }

        Notification::create([
            'user_id' => $reservation->user_id,
            'title' => 'Check-out Berhasil',
            'message' => $message,
            'type' => $lateFee > 0 ? 'warning' : 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful',
            'data' => $reservation->load('payment'),
        ]);
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'checked_out') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel completed reservation',
            ], 400);
        }

        $reservation->update(['status' => 'cancelled']);
        $reservation->room->update(['status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => 'Reservation cancelled successfully',
        ]);
    }
}
