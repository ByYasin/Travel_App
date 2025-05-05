<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{

    public function index(Request $request)
    {
        try {
            $query = Reservation::with(['user', 'tour']);

           
            if ($request->has('user_id') && $request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('tour_id') && $request->tour_id) {
                $query->where('tour_id', $request->tour_id);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('tour', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            }

           
            $sortField = $request->input('sort_field', 'created_at');
            $sortDirection = $request->input('sort_direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            
            $perPage = $request->input('per_page', 10);
            $reservations = $query->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $reservations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rezervasyonlar listelenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function list(Request $request)
    {
        try {
            $query = Reservation::with(['user', 'tour']);

            
            if ($request->has('user_id') && $request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('tour_id') && $request->tour_id) {
                $query->where('tour_id', $request->tour_id);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('tour', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            }

            
            if ($request->has('sort_by') && $request->sort_by) {
                $sortParts = explode('-', $request->sort_by);
                if (count($sortParts) == 2) {
                    $sortField = $sortParts[0];
                    $sortDirection = $sortParts[1];
                    $query->orderBy($sortField, $sortDirection);
                }
            } else {
                
                $query->orderBy('created_at', 'desc');
            }

            
            $perPage = $request->input('per_page', 10);
            $reservations = $query->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $reservations->items(),
                'total' => $reservations->total(),
                'per_page' => $reservations->perPage(),
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rezervasyonlar listelenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $reservation = Reservation::with(['user', 'tour'])->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rezervasyon bulunamadı',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'tour_id' => 'required|exists:tours,id',
                'reservation_date' => 'required|date',
                'participant_count' => 'required|integer|min:1',
                'status' => 'required|in:pending,confirmed,cancelled,completed',
                'special_requests' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }

  
            $tour = Tour::findOrFail($request->tour_id);
            $totalPrice = $tour->price * $request->participant_count;

            $reservation = Reservation::create([
                'user_id' => $request->user_id,
                'tour_id' => $request->tour_id,
                'reservation_date' => $request->reservation_date,
                'participant_count' => $request->participant_count,
                'total_price' => $totalPrice,
                'status' => $request->status,
                'special_requests' => $request->special_requests,
                'payment_method' => $request->payment_method ?? 'credit_card',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Rezervasyon başarıyla oluşturuldu',
                'data' => $reservation
            ], 201);
        } catch (\Exception $e) {
            Log::error('Rezervasyon oluşturma hatası: ' . $e->getMessage(), [
                'request' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Rezervasyon oluşturulurken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            
            $reservation = Reservation::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'sometimes|required|exists:users,id',
                'tour_id' => 'sometimes|required|exists:tours,id',
                'reservation_date' => 'sometimes|required|date',
                'participant_count' => 'sometimes|required|integer|min:1',
                'status' => 'sometimes|required|in:pending,confirmed,cancelled,completed',
                'special_requests' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }

            
            $oldStatus = $reservation->status;

            
            $reservation->user_id = $request->user_id ?? $reservation->user_id;
            $reservation->tour_id = $request->tour_id ?? $reservation->tour_id;
            $reservation->reservation_date = $request->reservation_date ?? $reservation->reservation_date;
            $reservation->participant_count = $request->participant_count ?? $reservation->participant_count;
            
            
            if ($request->has('status')) {
                $reservation->status = $request->status;
            }
            
            $reservation->special_requests = $request->special_requests ?? $reservation->special_requests;

            
            if ($request->has('participant_count') || $request->has('tour_id')) {
                $tourId = $request->tour_id ?? $reservation->tour_id;
                $tour = Tour::findOrFail($tourId);
                $participants = $request->participant_count ?? $reservation->participant_count;
                $reservation->total_price = $this->calculateTotalPrice($tour->price, $participants);
            }

            
            $reservation->save();

            
            $user = User::find($reservation->user_id);
            if ($user) {
                $this->sendStatusNotification($user, $reservation, 'updated');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Rezervasyon başarıyla güncellendi',
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rezervasyon güncellenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

 
    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Rezervasyon başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rezervasyon silinirken bir hata oluştu',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,cancelled,completed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }

            $reservation = Reservation::findOrFail($id);
            $oldStatus = $reservation->status;
            
            
            $reservation->status = $request->status;
            $reservation->save();

            
            $user = User::find($reservation->user_id);
            if ($user) {
                $this->sendStatusNotification($user, $reservation, 'status_changed');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Rezervasyon durumu başarıyla güncellendi',
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rezervasyon durumu güncellenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }


    public function getUsers()
    {
        try {
            $users = User::select('id', 'name', 'email')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kullanıcılar listelenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getTours()
    {
        try {
            $tours = Tour::select('id', 'title', 'price', 'duration')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $tours
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Turlar listelenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     * 
     * @param float $price
     * @param int $participants
     * @return float
     */
    private function calculateTotalPrice($price, $participants)
    {
        
        $totalPrice = $price * $participants;
        

        return round($totalPrice, 2);
    }

    /**
     * 
     * @param \App\Models\User $user - Tek bir User nesnesi olmalı bug fix (Collection değil :))
     * @param \App\Models\Reservation $reservation
     * @param string $action
     * @return void
     */
    private function sendStatusNotification($user, $reservation, $action)
    {
        try {
            
            if ($user instanceof \Illuminate\Database\Eloquent\Collection) {
                
                $user = $user->first();
                if (!$user) {
                    Log::error("Bildirim gönderilemedi: Geçerli kullanıcı bulunamadı");
                    return;
                }
            }
            
            
            Log::info("Rezervasyon bildirimi gönderildi", [
                'user_id' => $user->id,
                'reservation_id' => $reservation->id,
                'action' => $action,
                'status' => $reservation->status,
                'participant_count' => $reservation->participant_count
            ]);
            
            
            $message = '';
            switch ($reservation->status) {
                case 'confirmed':
                    $message = 'Rezervasyonunuz onaylandı.';
                    break;
                case 'cancelled':
                    $message = 'Rezervasyonunuz iptal edildi.';
                    break;
                case 'completed':
                    $message = 'Rezervasyonunuz tamamlandı.';
                    break;
                default:
                    $message = 'Rezervasyon durumunuz güncellendi.';
            }
            
            
            if (class_exists('\App\Notifications\ReservationStatusChanged')) {
                Notification::send($user, new \App\Notifications\ReservationStatusChanged($reservation, $action, $message));
            }
            
            
            Log::info("Bildirim içeriği: {$message}", [
                'user_id' => $user->id,
                'reservation_id' => $reservation->id
            ]);
        } catch (\Exception $e) {
            Log::error("Rezervasyon bildirimi gönderilemedi", [
                'error' => $e->getMessage(),
                'user_id' => isset($user->id) ? $user->id : 'unknown',
                'reservation_id' => $reservation->id
            ]);
        }
    }
}