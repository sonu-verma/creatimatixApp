<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Get reviews for a specific turf
     */
    public function getTurfReviews(Request $request, $turfId)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $reviews = $this->reviewService->getTurfReviews($turfId, $perPage);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Reviews retrieved successfully',
                data: $reviews
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Get all reviews for a turf (including pending) - Admin only
     */
    public function getAllTurfReviews(Request $request, $turfId)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $reviews = $this->reviewService->getAllTurfReviews($turfId, $perPage);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'All reviews retrieved successfully',
                data: $reviews
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Create a new review
     */
    public function store(Request $request)
    {
        try {
            $review = $this->reviewService->createReview($request);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Review created successfully',
                data: $review
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, $reviewId)
    {
        try {
            $review = $this->reviewService->updateReview($request, $reviewId);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Review updated successfully',
                data: $review
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Delete a review
     */
    public function destroy($reviewId)
    {
        try {
            $this->reviewService->deleteReview($reviewId);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Review deleted successfully'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Get user's reviews
     */
    public function getUserReviews(Request $request, $userId = null)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $reviews = $this->reviewService->getUserReviews($userId, $perPage);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'User reviews retrieved successfully',
                data: $reviews
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Get review statistics for a turf
     */
    public function getTurfReviewStats($turfId)
    {
        try {
            $stats = $this->reviewService->getTurfReviewStats($turfId);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Review statistics retrieved successfully',
                data: $stats
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Toggle review status (Admin function)
     */
    public function toggleReviewStatus(Request $request, $reviewId)
    {
        try {
            $request->validate([
                'status' => 'required|boolean'
            ]);

            $review = $this->reviewService->toggleReviewStatus($reviewId, $request->status);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Review status updated successfully',
                data: $review
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Get all pending reviews (Admin function)
     */
    public function getPendingReviews(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $reviews = $this->reviewService->getPendingReviews($perPage);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Pending reviews retrieved successfully',
                data: $reviews
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }

    /**
     * Get my reviews (current user)
     */
    public function getMyReviews(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $reviews = $this->reviewService->getUserReviews(null, $perPage);
            
            return ResponseHelper::success(
                status: 'success',
                message: 'Your reviews retrieved successfully',
                data: $reviews
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                status: 'error',
                message: $e->getMessage()
            );
        }
    }
}
