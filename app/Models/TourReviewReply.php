<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourReviewReply extends Model
{
    use HasFactory, SoftDeletes;

    /**

     *
     * @var array<int, string>
     */
    protected $fillable = [
        'review_id',
        'user_id',
        'content',
        'status',
        'is_visible',
    ];

    /**

     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
        'likes_count' => 'integer',
    ];

    /**

     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(TourReview::class, 'review_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function likes(): HasMany
    {
        return $this->hasMany(TourReviewReplyLike::class, 'reply_id');
    }

    /**
     * Check if the given user has liked this reply.
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
