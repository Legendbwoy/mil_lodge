<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accommodation;

class AccommodationSeeder extends Seeder
{
    public function run()
    {
        // Akafia Lodge - 4 beds per room
        $akafiaBlocks = ['Block A', 'Block B', 'Block C'];
        $akafiaRoomCounts = [24, 32, 24];

        foreach ($akafiaBlocks as $index => $block) {
            for ($room = 1; $room <= $akafiaRoomCounts[$index]; $room++) {
                Accommodation::create([
                    'name' => "Akafia Lodge - {$block} - Room " . str_pad($room, 2, '0', STR_PAD_LEFT),
                    'description' => "Comfortable accommodation in {$block} of Akafia Lodge with 4 beds",
                    'location' => 'Akafia Lodge',
                    'price_per_night' => 50.00, // Price per bed
                    'max_guests' => 4,
                    'bedrooms' => 1,
                    'bathrooms' => 1,
                    'is_available' => true,
                    'total_beds' => 4,
                    'available_beds' => 4,
                    'block_name' => $block,
                    'lodge_name' => 'Akafia Lodge',
                    'images' => ['default/accommodation.jpg'],
                ]);
            }
        }

        // Oppong Peprah Lodge - 3 beds per room
        $oppongTerraces = ['Upper Terrace', 'Lower Terrace'];
        $oppongRoomCount = 24;

        foreach ($oppongTerraces as $terrace) {
            for ($room = 1; $room <= $oppongRoomCount; $room++) {
                Accommodation::create([
                    'name' => "Oppong Peprah Lodge - {$terrace} - Room " . str_pad($room, 2, '0', STR_PAD_LEFT),
                    'description' => "Comfortable accommodation in {$terrace} of Oppong Peprah Lodge with 3 beds",
                    'location' => 'Oppong Peprah Lodge',
                    'price_per_night' => 45.00, // Price per bed
                    'max_guests' => 3,
                    'bedrooms' => 1,
                    'bathrooms' => 1,
                    'is_available' => true,
                    'total_beds' => 3,
                    'available_beds' => 3,
                    'block_name' => $terrace,
                    'lodge_name' => 'Oppong Peprah Lodge',
                    'images' => ['default/accommodation.jpg'],
                ]);
            }
        }
    }
}