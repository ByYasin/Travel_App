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

     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
        'likes_count' => 'integer',
    ];


    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function replies(): HasMany
    {
        return $this->hasMany(TourReviewReply::class, 'review_id');
    }


    public function likes(): HasMany
    {
        return $this->hasMany(TourReviewLike::class, 'review_id');
    }

    /**

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
