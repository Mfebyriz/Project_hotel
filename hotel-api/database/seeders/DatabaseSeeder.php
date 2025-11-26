<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Hotel',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'role' => 'admin',
        ]);

        // Create Customer Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567891',
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567892',
            'role' => 'customer',
        ]);

        // Create Rooms
        $roomTypes = [
            ['type' => 'Standard', 'price' => 300000, 'capacity' => 2],
            ['type' => 'Deluxe', 'price' => 500000, 'capacity' => 2],
            ['type' => 'Suite', 'price' => 800000, 'capacity' => 4],
            ['type' => 'Family', 'price' => 1000000, 'capacity' => 6],
        ];

        foreach ($roomTypes as $index => $roomType) {
            for ($i = 1; $i <= 5; $i++) {
                $roomNumber = ($index + 1) . str_pad($i, 2, '0', STR_PAD_LEFT);

                Room::create([
                    'room_number' => $roomNumber,
                    'room_type' => $roomType['type'],
                    'price' => $roomType['price'],
                    'description' => "Kamar {$roomType['type']} dengan fasilitas lengkap",
                    'image_url' => "https://via.placeholder.com/400x300?text=Room+{$roomNumber}",
                    'status' => 'available',
                    'capacity' => $roomType['capacity'],
                ]);
            }
        }
    }
}
