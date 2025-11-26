<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'amount',
        'late_fee',
        'payment_method',
        'payment_status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function reservations()
    {
        return $this->belongsTo(reservations::class);
    }

    public function getTotalAmount()
    {
        return $this->amount + $this->late_fee;
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
