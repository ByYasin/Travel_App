<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    /**

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


    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }


    public function isActive()
    {
        return $this->status === 'active';
    }


    public function getFormattedPriceAttribute()
    {
        if ($this->price === null) {
            return '₺0';
        }
        
        return '₺' . number_format($this->price, 0, ',', '.');
    }


    public function getStatusTextAttribute()
    {
        $statusMap = [
            'active' => 'Aktif',
            'inactive' => 'Pasif',
            'pending' => 'Onay Bekliyor'
        ];
        
        return $statusMap[$this->status] ?? $this->status;
    }


    public function scopePopular($query, $limit = 5)
    {
        return $query->withCount(['reservations' => function ($query) {
            $query->where('status', 'confirmed');
        }])
        ->orderBy('reservations_count', 'desc')
        ->limit($limit);
    }


    public function scopeLatest($query, $limit = 5)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }


    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }


    public function category()
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }


    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }


    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }


    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }


    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
        });
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    public function reviews()
    {
        return $this->hasMany(TourReview::class);
    }


    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }


    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }


    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'tour_id', 'user_id')
            ->withTimestamps();
    }


    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    

    public function getFavoriteCountAttribute()
    {
        return $this->favorites()->count();
    }


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