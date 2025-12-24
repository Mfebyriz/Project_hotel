<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomCategory;
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

        // Create Room Categories
        $categories = [
            [
                'name' => 'Standard',
                'price' => 300000,
                'description' => 'Kamar standard dengan fasilitas lengkap',
                'capacity' => 2,
                'image_url' => 'https://via.placeholder.com/400x300?text=Standard+Room',
            ],
            [
                'name' => 'Deluxe',
                'price' => 500000,
                'description' => 'Kamar deluxe dengan pemandangan kota',
                'capacity' => 2,
                'image_url' => 'https://via.placeholder.com/400x300?text=Deluxe+Room',
            ],
            [
                'name' => 'Suite',
                'price' => 800000,
                'description' => 'Kamar suite mewah dengan ruang tamu',
                'capacity' => 4,
                'image_url' => 'https://via.placeholder.com/400x300?text=Suite+Room',
            ],
            [
                'name' => 'Family',
                'price' => 1000000,
                'description' => 'Kamar keluarga luas dengan 2 kamar tidur',
                'capacity' => 6,
                'image_url' => 'https://via.placeholder.com/400x300?text=Family+Room',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = RoomCategory::create($categoryData);

            // Create 5 rooms for each category
            for ($i = 1; $i <= 5; $i++) {
                $floor = array_search($categoryData['name'], array_column($categories, 'name')) + 1;
                $roomNumber = $floor . str_pad($i, 2, '0', STR_PAD_LEFT);

                Room::create([
                    'room_category_id' => $category->id,
                    'room_number' => $roomNumber,
                    'status' => 'available',
                ]);
            }
        }
    }
}
