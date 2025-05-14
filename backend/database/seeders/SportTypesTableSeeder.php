<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SportTypesTableSeeder extends Seeder
{
    public function run()
    {
        $sports = [
            'Cricket',
            'Football',
            'Hockey',
            'Tennis',
            'Rugby',
            'Baseball',
            'Lacrosse',
            'Kabaddi',
            'Softball',
            'Futsal',
            'Handball',
            'Badminton',
            'Volleyball',
        ];

        foreach ($sports as $sport) {
            DB::table('sport_types')->insert([
                'name' => $sport,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
