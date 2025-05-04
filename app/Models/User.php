<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'address',
        'gender',
        'birthdate',
        'profile_photo',
        'role_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        // Varsayılan olarak user (id=1) rolünü ata
        $this->attributes['role_id'] = 1;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin()
    {
        return $this->role_id === Role::ADMIN;
    }

    /**
     * Get the favorites for the user.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the tours that the user has favorited.
     */
    public function favoriteTours()
    {
        return $this->belongsToMany(Tour::class, 'favorites', 'user_id', 'tour_id')
            ->withTimestamps();
    }

    /**
     * Kullanıcının tur ID'sine göre favori durumunu kontrol eder
     * @param int $tourId
     * @return bool
     */
    public function hasFavorited($tourId)
    {
        // Gerekli kontroller
        if (!$tourId || !is_numeric($tourId)) {
            return false;
        }
        
        try {
            // Kullanıcının bu tur için favorisi var mı?
            return $this->favorites()
                ->where('tour_id', $tourId)
                ->exists();
        } catch (\Exception $e) {
            // Hata durumunda log oluştur ve false döndür
            \Log::error('Favori kontrolü sırasında hata: ' . $e->getMessage());
            return false;
        }
    }
}
