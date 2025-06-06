<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserReservationController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');
        // Bu satırı kaldırıyorum çünkü Controller sınıfından türetilen sınıflar
        // middleware() metodunu doğrudan çağıramaz. Routes dosyası içinde tanımlanmalı ondan dolayı.
    }
    
    /**

     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        
        try {

            $reservations = DB::table('reservations')
                ->leftJoin('tours', 'reservations.tour_id', '=', 'tours.id')
                ->leftJoin('tour_categories', 'tours.category_id', '=', 'tour_categories.id')
                ->select(
                    'reservations.id',
                    'reservations.user_id',
                    'reservations.tour_id',
                    'reservations.reservation_date',
                    'reservations.participant_count',
                    'reservations.total_price',
                    'reservations.status',
                    'reservations.payment_method',
                    'reservations.special_requests',
                    'reservations.created_at',
                    'tours.title as tour_title',
                    'tours.price as tour_price',
                    'tours.description as tour_description',
                    'tours.featured_image as tour_image',
                    'tour_categories.name as category_name'
                )
                ->where('reservations.user_id', $user->id)
                ->orderBy('reservations.created_at', 'desc')
                ->get();
                

            \Log::info('UserReservationController@index: Rezervasyonlar yüklendi', [
                'user_id' => $user->id,
                'count' => $reservations->count()
            ]);
                
           
            $reservations = $reservations->map(function ($reservation) {
                
                if (!$reservation->tour_title) {
                    $reservation->tour_title = 'Tur #' . $reservation->tour_id;
                }
                
               
                if (empty($reservation->tour_image)) {
                    $reservation->tour_image = '/images/tours/default.jpg';
                    \Log::debug('UserReservationController: Tour resmi eksik, varsayılan oluşturuldu', [
                        'reservation_id' => $reservation->id,
                        'tour_id' => $reservation->tour_id
                    ]);
                } else {
                    
                    if (!str_starts_with($reservation->tour_image, 'http') && !str_starts_with($reservation->tour_image, '/')) {
                        $reservation->tour_image = '/' . $reservation->tour_image;
                    }
                    
                    \Log::debug('UserReservationController: Tour resmi bulundu', [
                        'reservation_id' => $reservation->id,
                        'tour_id' => $reservation->tour_id,
                        'image' => $reservation->tour_image
                    ]);
                }
                
                
                if (!$reservation->participant_count) {
                    $reservation->participant_count = 1;
                }
                
                
                if (empty($reservation->transaction_id)) {
                    $reservation->transaction_id = 'TR' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT);
                }
                
                return $reservation;
            });
            
            return response()->json([
                'success' => true,
                'data' => $reservations
            ]);
        } catch (\Exception $e) {
            \Log::error('Kullanıcı rezervasyonları alınırken hata: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            
           
            return response()->json([
                'success' => false,
                'message' => 'Rezervasyonlar yüklenirken bir hata oluştu: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**

     *
     * @param int $id Rezervasyon ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::user();
        
        try {

            $reservation = DB::table('reservations')
                ->leftJoin('tours', 'reservations.tour_id', '=', 'tours.id')
                ->leftJoin('tour_categories', 'tours.category_id', '=', 'tour_categories.id')
                ->select(
                    'reservations.id',
                    'reservations.user_id',
                    'reservations.tour_id',
                    'reservations.reservation_date',
                    'reservations.participant_count',
                    'reservations.total_price',
                    'reservations.status',
                    'reservations.payment_method',
                    'reservations.special_requests',
                    'reservations.created_at',
                    'tours.title as tour_title',
                    'tours.price as tour_price',
                    'tours.description as tour_description',
                    'tours.featured_image as tour_image',
                    'tour_categories.name as category_name'
                )
                ->where('reservations.user_id', $user->id)
                ->where('reservations.id', $id)
                ->first();
                
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rezervasyon bulunamadı'
                ], 404);
            }
                

            if (empty($reservation->tour_image)) {
                $reservation->tour_image = '/images/tours/default.jpg';
            } else {
  
                if (!str_starts_with($reservation->tour_image, 'http') && !str_starts_with($reservation->tour_image, '/')) {
                    $reservation->tour_image = '/' . $reservation->tour_image;
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rezervasyon bulunamadı',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    /**

     *
     * @param int $id Rezervasyon ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        $user = Auth::user();
        
        try {
            
            $reservation = Reservation::where('user_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();
            
            
            if ($reservation->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu rezervasyon zaten iptal edilmiş.'
                ], 400);
            }
            
            
            $reservationDate = \Carbon\Carbon::parse($reservation->reservation_date);
            $now = \Carbon\Carbon::now();
            
            
            if ($now->diffInHours($reservationDate) < 48) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rezervasyon tarihine 48 saatten az kaldığı için iptal işlemi yapılamaz.'
                ], 400);
            }
            
           
            $reservation->status = 'cancelled';
            $reservation->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Rezervasyonunuz başarıyla iptal edildi.',
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rezervasyon iptal edilirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
