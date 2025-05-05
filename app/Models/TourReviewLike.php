<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourReviewLike extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'review_id',
        'user_id',
    ];


    public function review(): BelongsTo
    {
        return $this->belongsTo(TourReview::class, 'review_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
