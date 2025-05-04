<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Toplu atama yapılabilecek alanlar
     */
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

    /**
     * Status için mümkün değerler
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that should be cast.
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
     * Turu getiren ilişki
     */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Kullanıcıyı getiren ilişki
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Durum metnini getir
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
     * Kullanıcıya ait rezervasyonları getiren scope
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Yaklaşan rezervasyonları getiren scope
     */
    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', now()->format('Y-m-d'));
    }

    /**
     * Geçmiş rezervasyonları getiren scope
     */
    public function scopePast($query)
    {
        return $query->where('reservation_date', '<', now()->format('Y-m-d'));
    }

    /**
     * Reservasyon durumunu kontrol et (onaylı)
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Reservasyon durumunu kontrol et (beklemede)
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Reservasyon durumunu kontrol et (iptal edilmiş)
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Reservasyonu iptal et
     */
    public function cancel()
    {
        $this->status = 'cancelled';
        return $this->save();
    }

    /**
     * Reservasyonu onayla
     */
    public function confirm()
    {
        $this->status = 'confirmed';
        return $this->save();
    }

    /**
     * Toplam fiyatı insan tarafından okunabilir formata dönüştür
     */
    public function getFormattedPriceAttribute()
    {
        return '₺' . number_format($this->total_price, 2, ',', '.');
    }

    /**
     * Durum filtreleri için scope'lar
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