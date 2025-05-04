<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourReviewReplyLike extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reply_id',
        'user_id',
    ];

    /**
     * Get the reply that was liked.
     */
    public function reply(): BelongsTo
    {
        return $this->belongsTo(TourReviewReply::class, 'reply_id');
    }

    /**
     * Get the user that liked the reply.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
