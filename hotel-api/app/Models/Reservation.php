<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'actual_check_in_date',
        'actual_check_out_date',
        'total_nights',
        'total_price',
        'status',
        'noted',
    ];

    protected $casts = [
        'check_in_date' => 'datetime',
        'check_out_date' => 'datetime',
        'actual_check_in_date' => 'datetime',
        'actual_check_out_date' => 'datetime',
        'total_price' => 'decimal:2',
        'total_nights' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(rooms::class);
    }

    public function payment()
    {
        return $this->hasOne(payments::class);
    }

    public function calculateTotalNights()
    {
        $checkIn = Carbon::parse($this->check_in_date);
        $checkOut = Carbon::parse($this->check_out_date);
        return $checkIn->diffInDays($checkOut);
    }

    public function calculateTotalPrice($pricePerNight)
    {
        return $this->total_nights * $pricePerNight;
    }

    public function isLateCheckOut()
    {
        if ($this->actual_check_out_date) {
            return Carbon::now()->greaterThan($this->check_out_date);
        }
        return Carbon::parse($this->actual_check_out_date)->greaterThan($this->check_out_date);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }
}
