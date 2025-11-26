<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public - List rooms (tanpa auth)
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{id}', [RoomController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Notifications (All authenticated users)
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // Reservations (Customer & Admin)
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);

    // Admin only routes
    Route::middleware('admin')->group(function () {

        // Room Management
        Route::post('/rooms', [RoomController::class, 'store']);
        Route::put('/rooms/{id}', [RoomController::class, 'update']);
        Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

        // Check-in & Check-out
        Route::post('/reservations/{id}/check-in', [ReservationController::class, 'checkIn']);
        Route::post('/reservations/{id}/check-out', [ReservationController::class, 'checkOut']);

        // Customer Management
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/{id}', [CustomerController::class, 'show']);
        Route::put('/customers/{id}', [CustomerController::class, 'update']);
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

        // Payment Management
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);
        Route::post('/payments/{id}/process', [PaymentController::class, 'processPayment']);

        // Reports
        Route::get('/reports/revenue', [ReportController::class, 'revenue']);
        Route::get('/reports/occupancy', [ReportController::class, 'occupancy']);
        Route::get('/reports/reservations', [ReportController::class, 'reservations']);
    });
});
