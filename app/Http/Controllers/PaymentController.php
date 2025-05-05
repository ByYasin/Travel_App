<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Ödeme işlemini başlatıyorum ve simüle ediyorum simdilik
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        try {
            
            $validated = $request->validate([
                'reservation_id' => 'nullable|exists:reservations,id',
                'tour_id' => 'required|exists:tours,id',
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:credit_card,bank_transfer,cash',
                'installment' => 'nullable|integer|min:1',
                'card_number' => 'required_if:payment_method,credit_card',
                'card_holder' => 'required_if:payment_method,credit_card',
                'expiry_date' => 'required_if:payment_method,credit_card',
                'cvv' => 'required_if:payment_method,credit_card',
                'reservation_date' => 'required|date',
                'participant_count' => 'required|integer|min:1',
            ]);

            
            $transactionId = 'TR' . date('Ymd') . strtoupper(Str::random(6));

            
            $paymentSuccess = true;
            $paymentDetails = [];

            
            if ($request->payment_method === 'credit_card') {
                
                $maskedCardNumber = substr($request->card_number, 0, 4) . ' **** **** ' . substr($request->card_number, -4);
                
                $paymentDetails = [
                    'card_number' => $maskedCardNumber,
                    'card_holder' => $request->card_holder,
                    'transaction_id' => $transactionId,
                    'payment_date' => now()->format('Y-m-d H:i:s'),
                    'installment' => $request->installment ?? 1
                ];
                
                // Rastgele hata simülasyonu (10% olasılık)
                if (rand(1, 100) <= 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ödeme işlemi sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
                        'error_code' => 'PAYMENT_FAILED',
                    ], 400);
                }
            } 
            
            else if ($request->payment_method === 'bank_transfer') {
                $paymentDetails = [
                    'bank_reference' => $transactionId,
                    'account_details' => 'TR12 3456 7890 1234 5678 9012 34',
                    'instructions' => 'Lütfen açıklama kısmına rezervasyon ID numaranızı yazınız.'
                ];
            }
            
            else if ($request->payment_method === 'cash') {
                $paymentDetails = [
                    'reference' => $transactionId,
                    'instructions' => 'Tur başlangıcında rehbere nakit olarak ödeme yapabilirsiniz.'
                ];
            }

            
            $reservation = null;
            if (!$request->reservation_id && $paymentSuccess) {
                $reservation = new Reservation();
                $reservation->user_id = auth()->id();
                $reservation->tour_id = $request->tour_id;
                $reservation->reservation_date = $request->reservation_date;
                $reservation->participant_count = $request->participant_count;
                $reservation->total_price = $request->amount;
                $reservation->status = 'confirmed';
                $reservation->payment_method = $request->payment_method;
                $reservation->payment_id = $transactionId;
                $reservation->save();
            } 
          
            else if ($request->reservation_id && $paymentSuccess) {
                $reservation = Reservation::findOrFail($request->reservation_id);
                $reservation->status = 'confirmed';
                $reservation->payment_method = $request->payment_method;
                $reservation->payment_id = $transactionId;
                $reservation->save();
            }

            
            if ($reservation) {
                $reservation->load('tour');
            }

            
            return response()->json([
                'success' => true,
                'message' => 'Ödeme başarıyla tamamlandı',
                'transaction_id' => $transactionId,
                'payment_details' => $paymentDetails,
                'reservation' => $reservation
            ], 200);
        } catch (\Exception $e) {
            Log::error('Ödeme işlemi sırasında hata: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ödeme işleminde bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ödeme işleminin durumunu kontrol et
     *
     * @param  string  $transactionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            
            $reservation = Reservation::where('payment_id', $transactionId)->first();
            
            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'İşlem bulunamadı'
                ], 404);
            }

            
            $reservation->load(['tour', 'user']);
            
            return response()->json([
                'success' => true,
                'message' => 'Ödeme durumu başarıyla alındı',
                'status' => $reservation->status,
                'payment_method' => $reservation->payment_method,
                'transaction_id' => $reservation->payment_id,
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ödeme durumu kontrol edilirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * İptal et ve geri ödeme simüle et
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'reservation_id' => 'required|exists:reservations,id',
                'reason' => 'nullable|string|max:255',
            ]);

            $reservation = Reservation::findOrFail($request->reservation_id);
            
            
            if ($reservation->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Sadece onaylanmış rezervasyonlar iptal edilebilir'
                ], 400);
            }
            
            
            $reservation->status = 'cancelled';
            $reservation->save();
            
          
            $refundId = 'RF' . date('Ymd') . strtoupper(Str::random(6));
            
            return response()->json([
                'success' => true,
                'message' => 'Rezervasyon iptal edildi ve geri ödeme işlemi başlatıldı',
                'refund_id' => $refundId,
                'refund_amount' => $reservation->total_price,
                'refund_status' => 'processing',
                'estimated_completion_date' => now()->addDays(3)->format('Y-m-d')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geri ödeme işlemi sırasında bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 