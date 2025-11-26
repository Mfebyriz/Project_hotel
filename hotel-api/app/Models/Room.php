<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'price',
        'description',
        'image_url',
        'status',
        'capacity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
    ];

    public function reservations()
    {
        return $this->hasMany(reservations::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function scopeAvailable($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('room_number', 'Like', "%{$search}%")
              ->orWhere('room_type', 'Like', "%{$search}%")
              ->orWhere('description', 'Like', "%{$search}%");
        });
    }
}
