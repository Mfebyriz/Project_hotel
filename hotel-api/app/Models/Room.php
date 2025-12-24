<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_category_id',
        'room_number',
        'status',
    ];

    public function category() {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function reservations()
    {
        return $this->hasMany(reservation::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    // Helper untuk akses data kategori
    public function getRoomTypeAttribute()
    {
        return $this->category->name ?? '-';
    }

    public function getPriceAttribute()
    {
        return $this->category->price ?? 0;
    }

    public function getDescriptionAttribute()
    {
        return $this->category->description ?? '';
    }

    public function getImageUrlAttribute()
    {
        return $this->category->image_url ?? null;
    }

    public function getCapacityAttribute()
    {
        return $this->category->capacity ?? 2;
    }
}
