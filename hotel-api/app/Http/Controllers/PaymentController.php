<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['reservation.user', 'reservation.room']);

        if ($request->has('status')) {
            $query->where('payment_status', $request->status);
        }

        $payments = $query->latest()->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    public function show($id)
    {
        $payment = Payment::with(['resrvation.user', 'reservation.room'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }

    public function processPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'payment_method' => 'required|in:cash,transfer,card',
        ]);

        $payment->update([
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        Notification::create([
            'user_id' => $payment->reservation->user_id,
            'title' => 'Pembayaran Berhasil',
            'message' => "Pembayaran untuk reservasi #{$payment->resevation->id} telah berhasil diproses",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => $payment,
        ]);
    }
}
