<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**

     */
    public function getRealTimeData(Request $request)
    {
        
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();
        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();
        
        
        $todayReservations = $this->getReservationCount($todayStart, $todayEnd);
        $todayIncome = $this->getTotalIncome($todayStart, $todayEnd);
        
        
        $yesterdayReservations = $this->getReservationCount($yesterdayStart, $yesterdayEnd);
        $yesterdayIncome = $this->getTotalIncome($yesterdayStart, $yesterdayEnd);
        
        
        $reservationChange = $yesterdayReservations > 0
            ? (($todayReservations - $yesterdayReservations) / $yesterdayReservations) * 100
            : 0;
            
        $incomeChange = $yesterdayIncome > 0
            ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100
            : 0;
        
        
        $activeUsers = rand(5, 50);
        
        
        $pendingCarts = DB::table('reservations')
            ->where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subHours(1))
            ->count();
            
        
        $previousHourPendingCarts = DB::table('reservations')
            ->where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subHours(2))
            ->where('created_at', '<', Carbon::now()->subHours(1))
            ->count();
            
        
        $pendingCartsChange = $previousHourPendingCarts > 0
            ? (($pendingCarts - $previousHourPendingCarts) / $previousHourPendingCarts) * 100
            : 0;
            
        
        $hourlyTraffic = $this->getHourlyTrafficData();
        
        
        $conversionRates = $this->getConversionRates();
        
       
        return response()->json([
            'today' => [
                'reservations' => $todayReservations,
                'reservationChange' => $reservationChange,
                'income' => $todayIncome,
                'incomeChange' => $incomeChange,
            ],
            'realtime' => [
                'activeUsers' => $activeUsers,
                'pendingCarts' => $pendingCarts,
                'pendingCartsChange' => $pendingCartsChange,
            ],
            'hourly_traffic' => $hourlyTraffic,
            'conversion_rates' => $conversionRates
        ]);
    }
    
    /**

     */
    public function getRecentReservations(Request $request)
    {
        
        $reservations = DB::table('reservations as r')
            ->join('users as u', 'r.user_id', '=', 'u.id')
            ->join('tours as t', 'r.tour_id', '=', 't.id')
            ->select(
                'r.id',
                'r.total_price',
                'r.status',
                'r.created_at',
                DB::raw("CONCAT(u.name, ' ', u.surname) as customer_name"),
                't.name as tour_name'
            )
            ->orderBy('r.created_at', 'desc')
            ->limit(20)
            ->get();
            
        return response()->json($reservations);
    }
    
    /**

     */
    public function getActiveUsers(Request $request)
    {

        
        $demoUsers = [];
        $userCount = rand(3, 15);
        
        $pages = [
            '/' => 'Ana Sayfa',
            '/tours' => 'Turlar Sayfası',
            '/tour/1' => 'Kapadokya Turu Detayı',
            '/tour/2' => 'Pamukkale Turu Detayı',
            '/about' => 'Hakkımızda',
            '/contact' => 'İletişim',
            '/profile' => 'Profil',
            '/login' => 'Giriş Sayfası',
            '/register' => 'Kayıt Sayfası',
            '/payment/1' => 'Ödeme Sayfası'
        ];
        
        $referrers = [
            'Google',
            'Facebook',
            'Instagram',
            'Twitter',
            'Direct',
            'Bing',
            'Yandex',
            null
        ];
        
        $devices = [
            'iPhone',
            'Android',
            'Desktop Chrome',
            'Desktop Firefox',
            'Desktop Safari',
            'iPad',
            'Samsung Galaxy'
        ];
        
        for ($i = 0; $i < $userCount; $i++) {
            $isLoggedIn = rand(0, 1) == 1;
            $minutesAgo = rand(0, 5);
            
            $demoUsers[] = [
                'id' => $i + 1,
                'name' => $isLoggedIn ? 'Kullanıcı ' . ($i + 1) : null,
                'email' => $isLoggedIn ? 'kullanici' . ($i + 1) . '@example.com' : null,
                'last_activity' => Carbon::now()->subMinutes($minutesAgo)->toIso8601String(),
                'current_page' => array_rand($pages),
                'referrer' => $referrers[array_rand($referrers)],
                'device' => $devices[array_rand($devices)]
            ];
        }
        
        return response()->json($demoUsers);
    }
    
    /**

     */
    private function getReservationCount(Carbon $startDate, Carbon $endDate)
    {
        return DB::table('reservations')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }
    
    /**

     */
    private function getTotalIncome(Carbon $startDate, Carbon $endDate)
    {
        return DB::table('reservations')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_price');
    }
    
    /**

     */
    private function getHourlyTrafficData()
    {
        $labels = [];
        $values = [];
        

        for ($i = 23; $i >= 0; $i--) {
            $hour = Carbon::now()->subHours($i);
            $labels[] = $hour->format('H:00');
            $values[] = rand(10, 200);
        }
        
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }
    

    private function getConversionRates()
    {
        // Ziyaretçilerin % kaçı ürün sayfasına gidiyor, 
        // % kaçı sepete ekliyor, % kaçı ödeme yapıyor ve % kaçı tamamlıyor.
        // Bu veriler analitik servislerinden alınabilir.
        return [100, rand(40, 60), rand(20, 40), rand(10, 30), rand(5, 20)];
    }
} 