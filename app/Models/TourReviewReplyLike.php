<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourReviewReplyLike extends Model
{
    use HasFactory;

    /**

     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reply_id',
        'user_id',
    ];


    public function reply(): BelongsTo
    {
        return $this->belongsTo(TourReviewReply::class, 'reply_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
