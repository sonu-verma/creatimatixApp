<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Turf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    /**
     * Get reviews for a specific turf
     */
    public function getTurfReviews($turfId, $perPage = 10)
    {
        return Review::with(['user:id,name,profile'])
            ->where('turf_id', $turfId)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all reviews for a turf (including pending)
     */
    public function getAllTurfReviews($turfId, $perPage = 10)
    {
        return Review::with(['user:id,name,profile'])
            ->where('turf_id', $turfId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create a new review
     */
    public function createReview(Request $request)
    {
        $request->validate([
            'turf_id' => 'required|exists:turfs,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $userId = Auth::id();
        
        // Check if user already reviewed this turf
        $existingReview = Review::where('turf_id', $request->turf_id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            throw new \Exception('You have already reviewed this turf.');
        }

        // Check if user has booked this turf (optional business rule)
        // You can add this validation if needed

        $review = Review::create([
            'turf_id' => $request->turf_id,
            'user_id' => $userId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => true // Auto-approve reviews, or set to false for manual approval
        ]);

        return $review->load('user:id,name,profile');
    }

    /**
     * Update an existing review
     */
    public function updateReview(Request $request, $reviewId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review = Review::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$review) {
            throw new \Exception('Review not found or you are not authorized to update it.');
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return $review->load('user:id,name,profile');
    }

    /**
     * Delete a review
     */
    public function deleteReview($reviewId)
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$review) {
            throw new \Exception('Review not found or you are not authorized to delete it.');
        }

        $review->delete();
        return true;
    }

    /**
     * Get user's reviews
     */
    public function getUserReviews($userId = null, $perPage = 10)
    {
        $userId = $userId ?? Auth::id();
        
        return Review::with(['turf:id,name,slug,location'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get review statistics for a turf
     */
    public function getTurfReviewStats($turfId)
    {
        $stats = Review::where('turf_id', $turfId)
            ->where('status', true)
            ->selectRaw('
                COUNT(*) as total_reviews,
                AVG(rating) as average_rating,
                COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
                COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
                COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
                COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
                COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
            ')
            ->first();

        return [
            'total_reviews' => $stats->total_reviews ?? 0,
            'average_rating' => round($stats->average_rating ?? 0, 2),
            'rating_distribution' => [
                '5' => $stats->five_star ?? 0,
                '4' => $stats->four_star ?? 0,
                '3' => $stats->three_star ?? 0,
                '2' => $stats->two_star ?? 0,
                '1' => $stats->one_star ?? 0,
            ]
        ];
    }

    /**
     * Approve/Disapprove a review (Admin function)
     */
    public function toggleReviewStatus($reviewId, $status)
    {
        $review = Review::findOrFail($reviewId);
        $review->update(['status' => $status]);
        return $review;
    }

    /**
     * Get all pending reviews (Admin function)
     */
    public function getPendingReviews($perPage = 10)
    {
        return Review::with(['user:id,name,profile', 'turf:id,name,slug'])
            ->where('status', false)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
