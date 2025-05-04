<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Route dosyasında middleware zaten tanımlandığı için bu satırları kaldırın
        // $this->middleware('auth');
        // $this->middleware('admin');
    }

    /**
     * Admin dashboard verilerini getir
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            
            $totalUsers = User::count();
            $totalTours = Tour::count();
            $totalReservations = Reservation::count();
            
            
            $totalIncome = Reservation::where('status', 'confirmed')->sum('total_price') ?? 0;
            
            
            $recentReservations = Reservation::with(['user', 'tour'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($reservation) {
                    return [
                        'id' => $reservation->id,
                        'customer' => $reservation->user->name,
                        'tour' => $reservation->tour->title,
                        'date' => $reservation->reservation_date,
                        'status' => $reservation->status,
                        'total_price' => $reservation->total_price,
                        'created_at' => $reservation->created_at->format('d.m.Y H:i')
                    ];
                });
            
            
            $monthlyIncome = $this->getMonthlyIncome();
            
            return response()->json([
                'stats' => [
                    'totalUsers' => $totalUsers,
                    'totalTours' => $totalTours,
                    'totalReservations' => $totalReservations,
                    'totalIncome' => $totalIncome
                ],
                'recentReservations' => $recentReservations,
                'monthlyIncome' => $monthlyIncome
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Dashboard verileri alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Son 6 ayın aylık gelir verilerini getir
     *
     * @return array
     */
    private function getMonthlyIncome()
    {
        $result = [];
        
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            
            $income = Reservation::where('status', 'confirmed')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('total_price') ?? 0;
            
            $result[] = [
                'month' => $month->format('M Y'),
                'income' => $income
            ];
        }
        
        return $result;
    }
}
