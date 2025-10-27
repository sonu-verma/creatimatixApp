<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Turf;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some turfs and users for seeding
        $turfs = Turf::limit(3)->get();
        $users = User::limit(5)->get();

        if ($turfs->isEmpty() || $users->isEmpty()) {
            $this->command->info('No turfs or users found. Please seed turfs and users first.');
            return;
        }

        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Excellent turf with great facilities and well-maintained grounds. Highly recommended!',
                'status' => true
            ],
            [
                'rating' => 4,
                'comment' => 'Good turf overall. The facilities are clean and the staff is helpful.',
                'status' => true
            ],
            [
                'rating' => 5,
                'comment' => 'Amazing experience! The turf is in perfect condition and the booking process was smooth.',
                'status' => true
            ],
            [
                'rating' => 3,
                'comment' => 'Decent turf but could use some improvements in the changing rooms.',
                'status' => true
            ],
            [
                'rating' => 4,
                'comment' => 'Great location and good facilities. Will definitely come back.',
                'status' => true
            ],
            [
                'rating' => 5,
                'comment' => 'Outstanding turf! Best in the area. The lighting is perfect for evening games.',
                'status' => true
            ],
            [
                'rating' => 2,
                'comment' => 'The turf needs maintenance. Grass is uneven in some areas.',
                'status' => true
            ],
            [
                'rating' => 4,
                'comment' => 'Good value for money. The turf is well-maintained and the staff is friendly.',
                'status' => true
            ],
            [
                'rating' => 5,
                'comment' => 'Perfect turf for football. The surface is excellent and the goals are properly maintained.',
                'status' => true
            ],
            [
                'rating' => 3,
                'comment' => 'Average turf. Nothing special but gets the job done.',
                'status' => true
            ]
        ];

        $reviewCount = 0;
        $usedCombinations = [];

        foreach ($turfs as $turf) {
            // Create 3-5 reviews per turf
            $reviewsPerTurf = rand(3, 5);
            
            for ($i = 0; $i < $reviewsPerTurf; $i++) {
                $user = $users->random();
                $reviewData = $reviews[array_rand($reviews)];
                
                // Check if this user has already reviewed this turf
                $combination = $turf->id . '_' . $user->id;
                if (in_array($combination, $usedCombinations)) {
                    continue;
                }
                
                $usedCombinations[] = $combination;
                
                Review::create([
                    'turf_id' => $turf->id,
                    'user_id' => $user->id,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'],
                    'status' => $reviewData['status']
                ]);
                
                $reviewCount++;
            }
        }

        $this->command->info("Created {$reviewCount} reviews for {$turfs->count()} turfs.");
    }
}
