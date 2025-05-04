<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Gerçek zamanlı analiz verilerini döndürür
     */
    public function getRealTimeData(Request $request)
    {
        // Bugün ve dün için tarih aralıkları
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();
        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();
        
        // Bugünkü rezervasyon ve gelir verilerini al
        $todayReservations = $this->getReservationCount($todayStart, $todayEnd);
        $todayIncome = $this->getTotalIncome($todayStart, $todayEnd);
        
        // Dünkü rezervasyon ve gelir verilerini al
        $yesterdayReservations = $this->getReservationCount($yesterdayStart, $yesterdayEnd);
        $yesterdayIncome = $this->getTotalIncome($yesterdayStart, $yesterdayEnd);
        
        // Değişim yüzdesini hesapla
        $reservationChange = $yesterdayReservations > 0
            ? (($todayReservations - $yesterdayReservations) / $yesterdayReservations) * 100
            : 0;
            
        $incomeChange = $yesterdayIncome > 0
            ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100
            : 0;
        
        // Aktif kullanıcı sayısını simüle et (gerçek uygulamada bu veri farklı bir kaynaktan gelebilir)
        $activeUsers = rand(5, 50);
        
        // Bekleyen sepet sayısını al (tamamlanmamış rezervasyonlar)
        $pendingCarts = DB::table('reservations')
            ->where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subHours(1))
            ->count();
            
        // Bir saat önceki bekleyen sepet sayısını al
        $previousHourPendingCarts = DB::table('reservations')
            ->where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subHours(2))
            ->where('created_at', '<', Carbon::now()->subHours(1))
            ->count();
            
        // Bekleyen sepet değişim yüzdesini hesapla
        $pendingCartsChange = $previousHourPendingCarts > 0
            ? (($pendingCarts - $previousHourPendingCarts) / $previousHourPendingCarts) * 100
            : 0;
            
        // Son 24 saat için saatlik ziyaretçi verilerini oluştur (demo için rastgele veriler)
        $hourlyTraffic = $this->getHourlyTrafficData();
        
        // Dönüşüm oranlarını oluştur (demo için rastgele veriler)
        $conversionRates = $this->getConversionRates();
        
        // Tüm verileri birleştir ve döndür
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
     * Son rezervasyonları döndürür
     */
    public function getRecentReservations(Request $request)
    {
        // Son 20 rezervasyonu al
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
     * Aktif kullanıcıları döndürür
     */
    public function getActiveUsers(Request $request)
    {
        // Gerçek bir uygulamada bu veri, oturum tablosundan veya bir analitik 
        // servisinden alınabilir. Burada demo için örnek veri oluşturuyoruz.
        
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
     * Belli bir tarih aralığındaki toplam rezervasyon sayısını döndürür
     */
    private function getReservationCount(Carbon $startDate, Carbon $endDate)
    {
        return DB::table('reservations')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }
    
    /**
     * Belli bir tarih aralığındaki toplam geliri döndürür
     */
    private function getTotalIncome(Carbon $startDate, Carbon $endDate)
    {
        return DB::table('reservations')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_price');
    }
    
    /**
     * Saatlik trafik verilerini oluşturur (demo)
     */
    private function getHourlyTrafficData()
    {
        $labels = [];
        $values = [];
        
        // Son 24 saat için etiketleri oluştur ve her saat için rastgele ziyaretçi sayısı ata
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
    
    /**
     * Dönüşüm oranlarını oluşturur (demo)
     */
    private function getConversionRates()
    {
        // Ziyaretçilerin % kaçı ürün sayfasına gidiyor, 
        // % kaçı sepete ekliyor, % kaçı ödeme yapıyor ve % kaçı tamamlıyor.
        // Gerçek bir uygulamada bu veriler analitik servislerinden alınabilir.
        return [100, rand(40, 60), rand(20, 40), rand(10, 30), rand(5, 20)];
    }
} 