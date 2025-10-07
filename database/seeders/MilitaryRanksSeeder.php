<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MilitaryRanksSeeder extends Seeder
{
    public function run()
    {
        $ranks = [
            // Army Ranks with titles
            ['rank' => 'private', 'title' => 'Private', 'branch' => 'army', 'order' => 1],
            ['rank' => 'private_first_class', 'title' => 'Private First Class', 'branch' => 'army', 'order' => 2],
            ['rank' => 'specialist', 'title' => 'Specialist', 'branch' => 'army', 'order' => 3],
            ['rank' => 'corporal', 'title' => 'Corporal', 'branch' => 'army', 'order' => 4],
            ['rank' => 'sergeant', 'title' => 'Sergeant', 'branch' => 'army', 'order' => 5],
            ['rank' => 'staff_sergeant', 'title' => 'Staff Sergeant', 'branch' => 'army', 'order' => 6],
            ['rank' => 'sergeant_first_class', 'title' => 'Sergeant First Class', 'branch' => 'army', 'order' => 7],
            ['rank' => 'master_sergeant', 'title' => 'Master Sergeant', 'branch' => 'army', 'order' => 8],
            ['rank' => 'first_sergeant', 'title' => 'First Sergeant', 'branch' => 'army', 'order' => 9],
            ['rank' => 'sergeant_major', 'title' => 'Sergeant Major', 'branch' => 'army', 'order' => 10],
            ['rank' => 'command_sergeant_major', 'title' => 'Command Sergeant Major', 'branch' => 'army', 'order' => 11],
            ['rank' => 'sergeant_major_of_the_army', 'title' => 'SMA', 'branch' => 'army', 'order' => 12],
            
            // Officer Ranks
            ['rank' => 'second_lieutenant', 'title' => '2nd Lieutenant', 'branch' => 'army', 'order' => 13],
            ['rank' => 'lieutenant', 'title' => 'Lieutenant', 'branch' => 'army', 'order' => 14],
            ['rank' => 'captain', 'title' => 'Captain', 'branch' => 'army', 'order' => 15],
            ['rank' => 'major', 'title' => 'Major', 'branch' => 'army', 'order' => 16],
            ['rank' => 'lieutenant_colonel', 'title' => 'Lieutenant Colonel', 'branch' => 'army', 'order' => 17],
            ['rank' => 'colonel', 'title' => 'Colonel', 'branch' => 'army', 'order' => 18],
            ['rank' => 'brigadier_general', 'title' => 'Brigadier General', 'branch' => 'army', 'order' => 19],
            ['rank' => 'major_general', 'title' => 'Major General', 'branch' => 'army', 'order' => 20],
            ['rank' => 'lieutenant_general', 'title' => 'Lieutenant General', 'branch' => 'army', 'order' => 21],
            ['rank' => 'general', 'title' => 'General', 'branch' => 'army', 'order' => 22],
        ];

        foreach ($ranks as $rank) {
            DB::table('military_ranks')->insert($rank);
        }
    }
}