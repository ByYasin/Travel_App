<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Kullanıcının favorilerini listeler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        
        // Kullanıcının favori turlarını tur ilişkisiyle birlikte getir
        $favorites = $user->favoriteTours()
            ->with(['category'])
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    /**
     * Tur favori durumunu kontrol eder
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $user = Auth::user();
        $tourId = $request->query('tour_id');
        
        if (!$tourId) {
            return response()->json([
                'success' => false,
                'message' => 'Tur ID parametresi gereklidir'
            ], 400);
        }
        
        $isFavorited = $user->favorites()->where('tour_id', $tourId)->exists();
        
        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited
        ]);
    }

    /**
     * Favori durumunu değiştirir (ekler/çıkarır)
     *
     * @param int $tourId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle($tourId)
    {
        try {
            $user = Auth::user();
            
            // Tur ID'sini kontrol et
            $tour = Tour::find($tourId);
            if (!$tour) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tur bulunamadı'
                ], 404);
            }
            
            // Eğer zaten favorilerde varsa kaldır, yoksa ekle
            $existingFavorite = $user->favorites()->where('tour_id', $tourId)->first();
            
            if ($existingFavorite) {
                $existingFavorite->delete();
                $message = 'Tur favorilerinizden çıkarıldı';
                $isFavorited = false;
            } else {
                $favorite = new Favorite();
                $favorite->user_id = $user->id;
                $favorite->tour_id = $tourId;
                $favorite->save();
                $message = 'Tur favorilerinize eklendi';
                $isFavorited = true;
            }
            
            return response()->json([
                'success' => true, 
                'is_favorited' => $isFavorited,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error('Favori toggle hatası: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'tour_id' => $tourId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Favori işlemi sırasında bir hata oluştu'
            ], 500);
        }
    }
    
    /**
     * Favoriyi kaldırır
     *
     * @param int $tourId
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($tourId)
    {
        $user = Auth::user();
        
        // İlgili favoriyi bul
        $favorite = $user->favorites()->where('tour_id', $tourId)->first();
        
        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Favori bulunamadı'
            ], 404);
        }
        
        // Favoriyi sil
        $favorite->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Tur favorilerinizden çıkarıldı'
        ]);
    }
}
