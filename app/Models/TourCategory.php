<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**

     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'is_active',
        'parent_id',
        'order',
    ];

    /**

     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'category_id');
    }
    

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'parent_id');
    }
    

    public function children(): HasMany
    {
        return $this->hasMany(TourCategory::class, 'parent_id');
    }


    public function isActive(): bool
    {
        return $this->is_active;
    }
    

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
