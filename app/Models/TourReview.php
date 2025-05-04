<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourReview extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tour_id',
        'user_id',
        'rating',
        'comment',
        'status',
        'is_visible',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
        'likes_count' => 'integer',
    ];

    /**
     * Get the tour that owns the review.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the user that wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the replies for this review.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TourReviewReply::class, 'review_id');
    }

    /**
     * Get the likes for this review.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(TourReviewLike::class, 'review_id');
    }

    /**
     * Check if the given user has liked this review.
     *
     * @param int|null $userId
     * @return bool
     */
    public function isLikedByUser(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
