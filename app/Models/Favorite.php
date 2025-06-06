<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**

     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tour_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
