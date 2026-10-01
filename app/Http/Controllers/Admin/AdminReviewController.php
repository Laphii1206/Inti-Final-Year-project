<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['booking.user', 'booking.service'])
            ->when($request->rating, fn($q) => $q->where('rating', $request->rating))
            ->latest()
            ->paginate(20);

        $analytics = [
            'avg_rating'      => Review::avg('rating'),
            'total_reviews'   => Review::count(),
            'by_rating'       => Review::selectRaw('rating, COUNT(*) as count')->groupBy('rating')->pluck('count', 'rating'),
            'recent_trend'    => Review::where('created_at', '>=', now()->subDays(30))->avg('rating'),
        ];

        return view('admin.reviews.index', compact('reviews', 'analytics'));
    }

    public function toggleVisibility(Review $review)
    {
        $review->update(['is_visible' => !$review->is_visible]);
        return back()->with('success', 'Review visibility updated.');
    }
}