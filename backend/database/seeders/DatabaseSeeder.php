<?php

namespace Database\Seeders;

use App\Models\Sport;
use App\Models\Turf;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            Turf::truncate();
            Sport::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        User::factory(10)->create();
        $this->call([SportTypesTableSeeder::class, TurfSeeder::class, SportSeeder::class]);
        $hashedPassword = Hash::make('password');
        User::factory()->create([
            'name' => 'Sonu Verma',
            'email' => 'sonu@gmail.com',
            'phone' => '9545577850',
            'role' => 'admin',
            'gender' => 'male',
            'short_desc' => 'software engineer, i am trying to develop a web application for sports',
            'password' => $hashedPassword
        ]);
    }
}
