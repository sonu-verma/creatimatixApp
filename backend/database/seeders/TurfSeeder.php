<?php

namespace Database\Seeders;

use App\Models\Turf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TurfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Turf::factory()->count(100)->create();
    }
}
