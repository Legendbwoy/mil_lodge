<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['name' => 'Wi-Fi', 'icon' => 'wifi'],
            ['name' => 'Air Conditioning', 'icon' => 'snowflake'],
            ['name' => 'Heating', 'icon' => 'thermometer'],
            ['name' => 'TV', 'icon' => 'tv'],
            ['name' => 'Kitchen', 'icon' => 'utensils'],
            ['name' => 'Free Parking', 'icon' => 'parking'],
            ['name' => 'Swimming Pool', 'icon' => 'swimming-pool'],
            ['name' => 'Gym', 'icon' => 'dumbbell'],
            ['name' => 'Hot Tub', 'icon' => 'hot-tub'],
            ['name' => 'Breakfast', 'icon' => 'coffee'],
            ['name' => 'Laundry', 'icon' => 'tshirt'],
            ['name' => 'Pet Friendly', 'icon' => 'paw'],
            ['name' => 'Family Rooms', 'icon' => 'home'],
            ['name' => 'Non-smoking Rooms', 'icon' => 'smoking-ban'],
            ['name' => 'Room Service', 'icon' => 'concierge-bell'],
            ['name' => 'Bar', 'icon' => 'glass-martini-alt'],
            ['name' => 'Garden', 'icon' => 'tree'],
            ['name' => 'Terrace', 'icon' => 'umbrella-beach'],
            ['name' => 'Balcony', 'icon' => 'door-open'],
            ['name' => 'Ocean View', 'icon' => 'water'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}