<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**

     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Rezervasyon listesini getir (filtreleme ve sayfalama ile)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        
        if (!auth()->user()->isAdmin() && !$request->has('user_id')) {
            return response()->json([
                'message' => 'Sadece kendi rezervasyonlarınızı görebilirsiniz'
            ], 403);
        }

        try {
            $query = Reservation::with(['user', 'tour']);
            
            
            if (!auth()->user()->isAdmin()) {
                $query->where('user_id', auth()->id());
            } elseif ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            
            if ($request->has('tour_id')) {
                $query->where('tour_id', $request->tour_id);
            }
            
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('reservation_date', [$request->start_date, $request->end_date]);
            }
            
           
            if ($search = $request->input('search')) {
                $query->whereHas('tour', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            
            $orderBy = $request->input('order_by', 'created_at');
            $direction = $request->input('direction', 'desc');
            
            
            $allowedOrderFields = ['created_at', 'reservation_date', 'total_price', 'status'];
            if (!in_array($orderBy, $allowedOrderFields)) {
                $orderBy = 'created_at';
            }
            
            $query->orderBy($orderBy, $direction);
            
            
            $reservations = $query->paginate($request->input('per_page', 10));
            
            return response()->json($reservations);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon listesi alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rezervasyon detaylarını getir
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $reservation = Reservation::with(['user', 'tour'])->findOrFail($id);
            
            
            if (!auth()->user()->isAdmin() && $reservation->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'Bu rezervasyonu görüntüleme yetkiniz yok'
                ], 403);
            }
            
            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon bulunamadı',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function store(Request $request)
    {
        try {
            
            $validated = $request->validate([
                'tour_id' => 'required|exists:tours,id',
                'total_price' => 'required|numeric|min:0',
                'reservation_date' => 'required|date',
                'status' => 'required|string|in:pending,confirmed,completed,cancelled',
                'payment_method' => 'nullable|string',
                'transaction_id' => 'nullable|string',
                'installment' => 'nullable|integer|min:1'
            ]);

           
            $user_id = auth()->id();
            
            
            $reservation = new Reservation();
            $reservation->user_id = $user_id;
            $reservation->tour_id = $validated['tour_id'];
            $reservation->reservation_date = $validated['reservation_date'];
            $reservation->total_price = $validated['total_price'];
            $reservation->status = $validated['status'];
            $reservation->payment_method = $validated['payment_method'] ?? 'credit_card';
            $reservation->transaction_id = $validated['transaction_id'] ?? null;
            $reservation->participant_count = $request->input('participant_count', 1);
            $reservation->notes = $request->input('notes', '');
            
            
            $reservation->save();
            
            
            $reservation->load('tour');
            
            
            return response()->json([
                'success' => true,
                'message' => 'Rezervasyon başarıyla kaydedildi',
                'reservation' => $reservation
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
           
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
           
            \Log::error('Rezervasyon kaydederken hata: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Rezervasyon kaydedilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        
        if (!auth()->user()->isAdmin() && $reservation->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Bu rezervasyonu güncelleme yetkiniz yok'
            ], 403);
        }
        
        
        $rules = [
            'special_requests' => 'nullable|string|max:500',
        ];
        
        
        if (auth()->user()->isAdmin()) {
            $rules = array_merge($rules, [
                'user_id' => 'sometimes|exists:users,id',
                'tour_id' => 'sometimes|exists:tours,id',
                'reservation_date' => 'sometimes|date|after_or_equal:today',
                'participant_count' => 'sometimes|integer|min:1',
                'status' => 'sometimes|in:pending,confirmed,cancelled',
                'payment_method' => 'nullable|string|max:50',
            ]);
        } else {
            
            $rules = array_merge($rules, [
                'status' => 'sometimes|in:cancelled',
            ]);
            
           
            if ($reservation->status === 'cancelled') {
                return response()->json([
                    'message' => 'İptal edilmiş rezervasyonları güncelleyemezsiniz'
                ], 403);
            }
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasyon hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
           
            $oldStatus = $reservation->status;
            
            
            if ((auth()->user()->isAdmin() && ($request->has('tour_id') || $request->has('participant_count')))) {
                $tour = Tour::findOrFail($request->tour_id ?? $reservation->tour_id);
                $participants = $request->participant_count ?? $reservation->participant_count;
                $reservation->total_price = $tour->price * $participants;
                
                if ($request->has('tour_id')) {
                    $reservation->tour_id = $request->tour_id;
                }
                
                if ($request->has('participant_count')) {
                    $reservation->participant_count = $request->participant_count;
                }
            }
            
            
            if (auth()->user()->isAdmin()) {
                if ($request->has('user_id')) {
                    $reservation->user_id = $request->user_id;
                }
                
                if ($request->has('reservation_date')) {
                    $reservation->reservation_date = $request->reservation_date;
                }
                
                if ($request->has('payment_method')) {
                    $reservation->payment_method = $request->payment_method;
                }
            }
            
            if ($request->has('status')) {
                $reservation->status = $request->status;
            }
            
            if ($request->has('special_requests')) {
                $reservation->special_requests = $request->special_requests;
            }
            
            $reservation->save();
            
            
            $reservation->load(['user', 'tour']);
            
            // Durum değiştiyse e-posta gönder
            if ($oldStatus !== $reservation->status) {
                // TODO: Durum değişikliği e-posta bildirimi gönder
            }
            
            return response()->json([
                'message' => 'Rezervasyon başarıyla güncellendi',
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon güncellenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            
            if (!auth()->user()->isAdmin()) {
                return response()->json([
                    'message' => 'Bu rezervasyonu silme yetkiniz yok'
                ], 403);
            }
            
            $reservation->delete();
            
            return response()->json([
                'message' => 'Rezervasyon başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon silinirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**

     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        // Admin kontrolü
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'Bu bilgileri görüntüleme yetkiniz yok'
            ], 403);
        }
        
        try {
            
            $totalReservations = Reservation::count();
            
           
            $confirmedCount = Reservation::where('status', 'confirmed')->count();
            $pendingCount = Reservation::where('status', 'pending')->count();
            $cancelledCount = Reservation::where('status', 'cancelled')->count();
            
            
            $totalIncome = Reservation::where('status', 'confirmed')->sum('total_price');
            
            
            $monthlyCounts = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $startOfMonth = $month->copy()->startOfMonth();
                $endOfMonth = $month->copy()->endOfMonth();
                
                $count = Reservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                
                $monthlyCounts[] = [
                    'month' => $month->format('M Y'),
                    'count' => $count
                ];
            }
            
            
            $topTours = Tour::withCount(['reservations' => function ($query) {
                $query->where('status', 'confirmed');
            }])
            ->orderBy('reservations_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($tour) {
                return [
                    'id' => $tour->id,
                    'title' => $tour->title,
                    'reservations_count' => $tour->reservations_count
                ];
            });
            
            return response()->json([
                'totalReservations' => $totalReservations,
                'statusCounts' => [
                    'confirmed' => $confirmedCount,
                    'pending' => $pendingCount,
                    'cancelled' => $cancelledCount
                ],
                'totalIncome' => $totalIncome,
                'monthlyCounts' => $monthlyCounts,
                'topTours' => $topTours
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'İstatistikler alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminIndex(Request $request)
    {
        try {
            $query = Reservation::with(['user', 'tour']);
            
           
            if ($request->has('tour_id')) {
                $query->where('tour_id', $request->tour_id);
            }
            
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            
            if ($search = $request->input('search')) {
                $query->whereHas('tour', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            
            $orderBy = $request->input('order_by', 'created_at');
            $direction = $request->input('direction', 'desc');
            
            
            $allowedOrderFields = ['created_at', 'reservation_date', 'total_price', 'status'];
            if (!in_array($orderBy, $allowedOrderFields)) {
                $orderBy = 'created_at';
            }
            
            $query->orderBy($orderBy, $direction);
            
            
            $reservations = $query->paginate($request->input('per_page', 10));
            
            return response()->json($reservations);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon listesi alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tour_id' => 'required|exists:tours,id',
            'reservation_date' => 'required|date',
            'participant_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled',
            'payment_method' => 'nullable|string|max:50',
            'special_requests' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasyon hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            
            $tour = Tour::findOrFail($request->tour_id);
            $totalPrice = $tour->price * $request->participant_count;
            
            $reservation = Reservation::create([
                'user_id' => $request->user_id,
                'tour_id' => $request->tour_id,
                'reservation_date' => $request->reservation_date,
                'participant_count' => $request->participant_count,
                'total_price' => $totalPrice,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'special_requests' => $request->special_requests,
            ]);
            
            
            $reservation->load(['user', 'tour']);
            
            return response()->json([
                'message' => 'Rezervasyon başarıyla oluşturuldu',
                'reservation' => $reservation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon oluşturulurken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUpdate(Request $request, $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'user_id' => 'sometimes|exists:users,id',
                'tour_id' => 'sometimes|exists:tours,id',
                'reservation_date' => 'sometimes|date',
                'participant_count' => 'sometimes|integer|min:1',
                'status' => 'sometimes|in:pending,confirmed,cancelled',
                'payment_method' => 'nullable|string|max:50',
                'special_requests' => 'nullable|string|max:500',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
  
            if ($request->has('tour_id') || $request->has('participant_count')) {
                $tourId = $request->has('tour_id') ? $request->tour_id : $reservation->tour_id;
                $participantCount = $request->has('participant_count') ? $request->participant_count : $reservation->participant_count;
                
                $tour = Tour::findOrFail($tourId);
                $totalPrice = $tour->price * $participantCount;
                
                $reservation->total_price = $totalPrice;
            }
            
           
            if ($request->has('user_id')) $reservation->user_id = $request->user_id;
            if ($request->has('tour_id')) $reservation->tour_id = $request->tour_id;
            if ($request->has('reservation_date')) $reservation->reservation_date = $request->reservation_date;
            if ($request->has('participant_count')) $reservation->participant_count = $request->participant_count;
            if ($request->has('status')) $reservation->status = $request->status;
            if ($request->has('payment_method')) $reservation->payment_method = $request->payment_method;
            if ($request->has('special_requests')) $reservation->special_requests = $request->special_requests;
            
            $reservation->save();
            
            
            $reservation->load(['user', 'tour']);
            
            return response()->json([
                'message' => 'Rezervasyon başarıyla güncellendi',
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon güncellenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminDestroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();
            
            return response()->json([
                'message' => 'Rezervasyon başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon silinirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**

     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminShow($id)
    {
        try {
            $reservation = Reservation::with(['user', 'tour'])->findOrFail($id);
            
            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon bulunamadı',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**

     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,cancelled,completed',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasyon hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $reservation->status = $request->status;
            $reservation->save();
            
            return response()->json([
                'message' => 'Rezervasyon durumu başarıyla güncellendi',
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rezervasyon durumu güncellenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 