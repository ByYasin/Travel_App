<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    /**
     * Kullanıcının favorilerini görüntüle
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oturum açmanız gerekiyor'
                ], 401, [], JSON_UNESCAPED_UNICODE);
            }
            
            \Log::info('Kullanıcı favorilerini getirme işlemi başladı', [
                'user_id' => $user->id,
                'method' => 'FavoriteController@index'
            ]);
            
           
            $favoriteTours = $user->favoriteTours()->with('category')->get();
            
            
            $favorites = [];
            
            foreach ($favoriteTours as $tour) {
               
                try {
                    $favorites[] = [
                        'id' => $tour->id,
                        'title' => $tour->title ?? '',
                        'description' => $tour->description 
                            ? (mb_strlen($tour->description) > 100 
                                ? mb_substr($tour->description, 0, 100) . '...' 
                                : $tour->description) 
                            : '',
                        'price' => $tour->price ?? 0,
                        'formatted_price' => number_format(($tour->price ?? 0), 2, ',', '.') . ' ₺',
                        'image' => $tour->featured_image ?: '/images/tours/default.jpg',
                        'start_date' => $tour->start_date,
                        'end_date' => $tour->end_date,
                        'capacity' => $tour->max_participants,
                        'category_name' => $tour->category ? $tour->category->name : null
                    ];
                } catch (\Exception $tourError) {
                    \Log::warning('Tur verisi işlenirken hata', [
                        'tour_id' => $tour->id ?? 'unknown',
                        'error' => $tourError->getMessage()
                    ]);
                    
                    continue;
                }
            }
            
            
            \Log::info('Favoriler başarıyla getirildi', [
                'user_id' => $user->id,
                'favorites_count' => count($favorites)
            ]);
            
            
            return response()->json($favorites, 200, [], JSON_UNESCAPED_UNICODE);
            
        } catch (\Exception $e) {
            \Log::error('Favorileri getirme hatası: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Favoriler yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**

     *
     * @param int $tourId Tur ID
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggle($tourId)
    {
        try {
            $user = Auth::user();
            
            
            $tour = Tour::find($tourId);
            if (!$tour) {
                return back()->with('error', 'Tur bulunamadı');
            }
            
            
            $existingFavorite = $user->favorites()->where('tour_id', $tourId)->first();
            
            if ($existingFavorite) {
                $existingFavorite->delete();
                $message = 'Tur favorilerinizden çıkarıldı';
            } else {
                $favorite = new Favorite();
                $favorite->user_id = $user->id;
                $favorite->tour_id = $tourId;
                $favorite->save();
                $message = 'Tur favorilerinize eklendi';
            }
            
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'is_favorited' => !$existingFavorite,
                    'message' => $message
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
            
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Favori toggle hatası: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'tour_id' => $tourId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Favori işlemi sırasında bir hata oluştu'
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
            
            
            return back()->with('error', 'Favori işlemi sırasında bir hata oluştu');
        }
    }
    
    /**

     *
     * @param int $tourId Tur ID
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function remove($tourId)
    {
        try {
            $user = Auth::user();
            
            // İlgili favoriyi bul
            $favorite = $user->favorites()->where('tour_id', $tourId)->first();
            
            if (!$favorite) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Favori bulunamadı'
                    ], 404, [], JSON_UNESCAPED_UNICODE);
                }
                
                return back()->with('error', 'Favori bulunamadı');
            }
            
            
            $favorite->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tur favorilerinizden çıkarıldı'
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
            
            return back()->with('success', 'Tur favorilerinizden çıkarıldı');
        } catch (\Exception $e) {
            \Log::error('Favori silme hatası: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'tour_id' => $tourId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Favori işlemi sırasında bir hata oluştu'
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
            
            return back()->with('error', 'Favori işlemi sırasında bir hata oluştu');
        }
    }
} 