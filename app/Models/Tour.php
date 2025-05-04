<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'duration',
        'location',
        'category',
        'max_participants',
        'featured_image',
        'gallery',
        'included',
        'not_included',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'max_participants' => 'integer',
        'gallery' => 'array',
        'included' => 'array',
        'not_included' => 'array',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Tura ait rezervasyonlar
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Tur durumunu kontrol et (aktif)
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Fiyat özelliğinin formatlanmış halini döndürür.
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price === null) {
            return '₺0';
        }
        
        return '₺' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Tur durumunun metin karşılığını döndürür.
     */
    public function getStatusTextAttribute()
    {
        $statusMap = [
            'active' => 'Aktif',
            'inactive' => 'Pasif',
            'pending' => 'Onay Bekliyor'
        ];
        
        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * En çok tercih edilen turlar
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->withCount(['reservations' => function ($query) {
            $query->where('status', 'confirmed');
        }])
        ->orderBy('reservations_count', 'desc')
        ->limit($limit);
    }

    /**
     * En son eklenen turlar
     */
    public function scopeLatest($query, $limit = 5)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Öne çıkarılan turlar
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Tura ait kategori
     */
    public function category()
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    /**
     * Kategoriye göre tur filtreleme
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Fiyat aralığına göre tur filtreleme
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Lokasyona göre tur filtreleme
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Arama sorgusu
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Sadece aktif turları getir
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the reviews for this tour.
     */
    public function reviews()
    {
        return $this->hasMany(TourReview::class);
    }

    /**
     * Değerlendirmelerin ortalama puanını döndürür.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Toplam değerlendirme sayısını döndürür.
     */
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Get the users who favorited this tour.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'tour_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the favorites for this tour.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
    /**
     * Get the count of favorites for this tour.
     */
    public function getFavoriteCountAttribute()
    {
        return $this->favorites()->count();
    }

    /**
     * Tour modelini toArray metodunu override ederek JSON çıktıda "category" alanını kategori adıyla döndür
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        // Eğer kategori bilgisi yüklenmişse, category_name alanını ekle
        if ($this->relationLoaded('category') && $this->category) {
            $array['category_name'] = $this->category->name;
            $array['category'] = $this->category->name;
        }
        
        return $array;
    }
} 