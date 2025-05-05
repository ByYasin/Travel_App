<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourReview;
use App\Models\TourReviewLike;
use App\Models\TourReviewReply;
use App\Models\TourReviewReplyLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TourReviewController extends Controller
{

    public function __construct()
    {
    }

    /**

     *
     * @param int $tourId
     * @return JsonResponse
     */
    public function index(int $tourId): JsonResponse
    {
        $tour = Tour::findOrFail($tourId);
        
        $reviews = TourReview::with(['user:id,name,email', 'replies.user:id,name,email'])
            ->where('tour_id', $tour->id)
            ->where('status', 'approved')
            ->where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($review) {
                $review->userLiked = $review->isLikedByUser(Auth::id());
                
                $review->replies->map(function ($reply) {
                    $reply->userLiked = $reply->isLikedByUser(Auth::id());
                    return $reply;
                });
                
                return $review;
            });
            
        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviews,
                'average_rating' => $tour->average_rating,
                'review_count' => $tour->review_count
            ]
        ]);
    }

    /**

     *
     * @param Request $request
     * @param int $tourId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, int $tourId): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        $tour = Tour::findOrFail($tourId);
        
        $existingReview = TourReview::where('tour_id', $tour->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Bu tura daha önce değerlendirme yaptınız. Lütfen mevcut değerlendirmenizi güncelleyin.'
            ], 422);
        }
        
        $review = TourReview::create([
            'tour_id' => $tour->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'approved',
        ]);
        
        $review->load('user:id,name,email');
        
        return response()->json([
            'success' => true,
            'message' => 'Değerlendirmeniz başarıyla kaydedildi.',
            'data' => $review
        ], 201);
    }

    /**
     * Mevcut bir tur değerlendirmesini günceller
     *
     * @param Request $request
     * @param int $reviewId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $reviewId): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        $review = TourReview::findOrFail($reviewId);
        
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu değerlendirmeyi düzenleme yetkiniz yok.'
            ], 403);
        }
        
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Değerlendirmeniz başarıyla güncellendi.',
            'data' => $review
        ]);
    }

    /**
     * Bir tur değerlendirmesini sil
     *
     * @param int $reviewId
     * @return JsonResponse
     */
    public function destroy(int $reviewId): JsonResponse
    {
        $review = TourReview::findOrFail($reviewId);
        
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu değerlendirmeyi silme yetkiniz yok.'
            ], 403);
        }
        
        $review->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Değerlendirme başarıyla silindi.'
        ]);
    }

    /**
     * Bir değerlendirmeyi beğen/beğenme
     *
     * @param int $reviewId
     * @return JsonResponse
     */
    public function toggleLike(int $reviewId): JsonResponse
    {
        $review = TourReview::findOrFail($reviewId);
        
        $existingLike = TourReviewLike::where('review_id', $review->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingLike) {
            $existingLike->delete();
            $review->decrement('likes_count');
            $userLiked = false;
            $message = 'Değerlendirme beğenisi kaldırıldı.';
        } else {
            TourReviewLike::create([
                'review_id' => $review->id,
                'user_id' => Auth::id()
            ]);
            $review->increment('likes_count');
            $userLiked = true;
            $message = 'Değerlendirme beğenildi.';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'userLiked' => $userLiked,
                'likesCount' => $review->likes_count
            ]
        ]);
    }

    /**
     * Bir değerlendirmeye yanıt ekle
     *
     * @param Request $request
     * @param int $reviewId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addReply(Request $request, int $reviewId): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $review = TourReview::findOrFail($reviewId);
        
        $reply = TourReviewReply::create([
            'review_id' => $review->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'status' => 'approved',
        ]);
        
        $reply->load('user:id,name,email');
        $reply->userLiked = false;
        
        return response()->json([
            'success' => true,
            'message' => 'Yanıtınız başarıyla eklendi.',
            'data' => $reply
        ], 201);
    }

    /**
     * Bir değerlendirme yanıtını beğen/beğenme
     *
     * @param int $replyId
     * @return JsonResponse
     */
    public function toggleReplyLike(int $replyId): JsonResponse
    {
        $reply = TourReviewReply::findOrFail($replyId);
        
        $existingLike = TourReviewReplyLike::where('reply_id', $reply->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingLike) {
            // Beğeniyi kaldır
            $existingLike->delete();
            $reply->decrement('likes_count');
            $userLiked = false;
            $message = 'Yanıt beğenisi kaldırıldı.';
        } else {
            // Beğen
            TourReviewReplyLike::create([
                'reply_id' => $reply->id,
                'user_id' => Auth::id()
            ]);
            $reply->increment('likes_count');
            $userLiked = true;
            $message = 'Yanıt beğenildi.';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'userLiked' => $userLiked,
                'likesCount' => $reply->likes_count
            ]
        ]);
    }
}
