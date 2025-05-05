<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'tour_id',
        'reservation_date',
        'participant_count',
        'total_price',
        'status',
        'special_requests',
        'payment_method',
        'transaction_id',
        'notes'
    ];


    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**

     *
     * @var array<string, string>
     */
    protected $casts = [
        'reservation_date' => 'date',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**

     */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**

     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**

     */
    public function getStatusTextAttribute()
    {
        $statusMap = [
            self::STATUS_PENDING => 'Beklemede',
            self::STATUS_CONFIRMED => 'Onaylandı',
            self::STATUS_COMPLETED => 'Tamamlandı',
            self::STATUS_CANCELLED => 'İptal Edildi'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    /**

     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**

     */
    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', now()->format('Y-m-d'));
    }

    /**

     */
    public function scopePast($query)
    {
        return $query->where('reservation_date', '<', now()->format('Y-m-d'));
    }

    /**

     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**

     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**

     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**

     */
    public function cancel()
    {
        $this->status = 'cancelled';
        return $this->save();
    }

    /**

     */
    public function confirm()
    {
        $this->status = 'confirmed';
        return $this->save();
    }

    /**

     */
    public function getFormattedPriceAttribute()
    {
        return '₺' . number_format($this->total_price, 2, ',', '.');
    }

    /**

     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
} 