<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Ana raporlama verilerini getir
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        try {
            
            $dateRange = $this->getDateRange($request->input('period', 'this-month'));
            $startDate = $dateRange['start'];
            $endDate = $dateRange['end'];
            
         
            $totalIncome = Reservation::where('status', 'confirmed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_price');
            
           
            $totalReservations = Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->count();
                
            
            $averageSale = $totalReservations > 0 
                ? $totalIncome / $totalReservations 
                : 0;
                
            
            $previousRange = $this->getPreviousPeriod($dateRange);
            $prevStartDate = $previousRange['start'];
            $prevEndDate = $previousRange['end'];
            
            
            $prevTotalIncome = Reservation::where('status', 'confirmed')
                ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
                ->sum('total_price');
                
           
            $prevTotalReservations = Reservation::whereBetween('created_at', [$prevStartDate, $prevEndDate])
                ->count();
                
           
            $incomeChange = $prevTotalIncome > 0 
                ? (($totalIncome - $prevTotalIncome) / $prevTotalIncome) * 100 
                : 100;
                
            $reservationsChange = $prevTotalReservations > 0 
                ? (($totalReservations - $prevTotalReservations) / $prevTotalReservations) * 100 
                : 100;
                
            
            $prevAverageSale = $prevTotalReservations > 0 
                ? $prevTotalIncome / $prevTotalReservations 
                : 0;
                
            $averageSaleChange = $prevAverageSale > 0 
                ? (($averageSale - $prevAverageSale) / $prevAverageSale) * 100 
                : 100;
            
            return response()->json([
                'period' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'label' => $this->getPeriodLabel($request->input('period', 'this-month'))
                ],
                'summary' => [
                    'totalIncome' => $totalIncome,
                    'totalReservations' => $totalReservations,
                    'averageSale' => $averageSale,
                    'incomeChange' => round($incomeChange, 2),
                    'reservationsChange' => round($reservationsChange, 2),
                    'averageSaleChange' => round($averageSaleChange, 2)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rapor özeti alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Aylık gelir grafiği için verileri getir
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyIncome(Request $request)
    {
        try {
            
            $dateRange = $this->getDateRange($request->input('period', 'this-month'));
            $startDate = $dateRange['start'];
            $endDate = $dateRange['end'];
            
            
            $result = [];
            
           
            if ($startDate->diffInMonths($endDate) > 6) {
                $result = DB::table('reservations')
                    ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(total_price) as total'))
                    ->where('status', 'confirmed')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) {
                        $date = Carbon::createFromFormat('Y-m', $item->month);
                        return [
                            'label' => $date->format('M Y'),
                            'value' => (float) $item->total
                        ];
                    });
            } else {
              
                $result = DB::table('reservations')
                    ->select(DB::raw('YEAR(created_at) as year, WEEK(created_at) as week'), DB::raw('SUM(total_price) as total'))
                    ->where('status', 'confirmed')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year', 'week')
                    ->orderBy('year')
                    ->orderBy('week')
                    ->get()
                    ->map(function ($item) {
                        $date = Carbon::now()->setISODate($item->year, $item->week);
                        return [
                            'label' => $date->startOfWeek()->format('d M') . ' - ' . $date->endOfWeek()->format('d M'),
                            'value' => (float) $item->total
                        ];
                    });
            }
            
            return response()->json([
                'labels' => $result->pluck('label'),
                'data' => $result->pluck('value')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Aylık gelir verisi alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * En çok satan turların listesini getir
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopTours(Request $request)
    {
        try {
           
            $dateRange = $this->getDateRange($request->input('period', 'this-month'));
            $startDate = $dateRange['start'];
            $endDate = $dateRange['end'];
            
            
            $limit = $request->input('limit', 10);
            
            
            $topTours = Tour::select('tours.*')
                ->selectRaw('COUNT(reservations.id) as reservation_count')
                ->selectRaw('SUM(reservations.total_price) as total_sales')
                ->leftJoin('reservations', 'tours.id', '=', 'reservations.tour_id')
                ->where('reservations.status', 'confirmed')
                ->whereBetween('reservations.created_at', [$startDate, $endDate])
                ->groupBy('tours.id')
                ->orderByDesc('total_sales')
                ->limit($limit)
                ->get();
                
            
            $previousRange = $this->getPreviousPeriod($dateRange);
            $prevStartDate = $previousRange['start'];
            $prevEndDate = $previousRange['end'];
            
            $result = $topTours->map(function ($tour) use ($prevStartDate, $prevEndDate) {
                
                $prevSales = Reservation::where('tour_id', $tour->id)
                    ->where('status', 'confirmed')
                    ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
                    ->sum('total_price');
                
               
                $trend = 'stable';
                if ($prevSales > 0) {
                    $change = (($tour->total_sales - $prevSales) / $prevSales) * 100;
                    if ($change > 10) {
                        $trend = 'up';
                    } elseif ($change < -10) {
                        $trend = 'down';
                    }
                }
                
                
                $rating = $tour->reviews()->where('status', 'approved')->avg('rating') ?: 0;
                
                return [
                    'id' => $tour->id,
                    'name' => $tour->title,
                    'image' => $tour->featured_image,
                    'totalSales' => (float) $tour->total_sales,
                    'reservationCount' => $tour->reservation_count,
                    'rating' => round($rating, 1),
                    'trend' => $trend
                ];
            });
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'En çok satan turlar alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDemographics(Request $request)
    {
        try {
           
            $ageGroups = DB::table('users')
                ->select(DB::raw('
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18 THEN "18 altı"
                        WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 24 THEN "18-24"
                        WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 25 AND 34 THEN "25-34"
                        WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 35 AND 44 THEN "35-44"
                        WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 45 AND 54 THEN "45-54"
                        WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 55 AND 64 THEN "55-64"
                        ELSE "65+" 
                    END as age_group
                '))
                ->selectRaw('COUNT(*) as count')
                ->whereNotNull('birthdate')
                ->groupBy('age_group')
                ->get();
                
            
            $genderDistribution = DB::table('users')
                ->selectRaw('gender, COUNT(*) as count')
                ->whereNotNull('gender')
                ->groupBy('gender')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->gender => $item->count];
                });
                
            return response()->json([
                'ageGroups' => $ageGroups,
                'genderDistribution' => $genderDistribution
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kullanıcı demografik verileri alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
    
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getParticipantStats(Request $request)
    {
        try {
            
            $dateRange = $this->getDateRange($request->input('period', 'this-month'));
            $startDate = $dateRange['start'];
            $endDate = $dateRange['end'];
            
            
            $participantDistribution = DB::table('reservations')
                ->selectRaw('participant_count, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'confirmed')
                ->groupBy('participant_count')
                ->orderBy('participant_count')
                ->get();
                
            
            $avgParticipants = Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'confirmed')
                ->avg('participant_count') ?: 0;
                
            return response()->json([
                'participantDistribution' => $participantDistribution,
                'averageParticipants' => round($avgParticipants, 2)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Katılımcı istatistikleri alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 
     * 
     * @param string $period
     * @return array
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'yesterday':
                return [
                    'start' => $now->copy()->subDay()->startOfDay(),
                    'end' => $now->copy()->subDay()->endOfDay()
                ];
            case 'this-week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'last-week':
                return [
                    'start' => $now->copy()->subWeek()->startOfWeek(),
                    'end' => $now->copy()->subWeek()->endOfWeek()
                ];
            case 'last-month':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth()
                ];
            case 'last-3-months':
                return [
                    'start' => $now->copy()->subMonths(3)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'last-6-months':
                return [
                    'start' => $now->copy()->subMonths(6)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'this-year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'last-year':
                return [
                    'start' => $now->copy()->subYear()->startOfYear(),
                    'end' => $now->copy()->subYear()->endOfYear()
                ];
            case 'all-time':
                return [
                    'start' => Carbon::createFromDate(2000, 1, 1),
                    'end' => $now->copy()
                ];
            case 'this-month':
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }
    
    /**
     * 
     * 
     * @param array $currentRange
     * @return array
     */
    private function getPreviousPeriod($currentRange)
    {
        $start = Carbon::parse($currentRange['start']);
        $end = Carbon::parse($currentRange['end']);
        
        $durationInDays = $start->diffInDays($end);
        
        return [
            'start' => $start->copy()->subDays($durationInDays + 1),
            'end' => $start->copy()->subDay()
        ];
    }
    
    /**
     * -
     * 
     * @param string $period
     * @return string
     */
    private function getPeriodLabel($period)
    {
        $labels = [
            'today' => 'Bugün',
            'yesterday' => 'Dün',
            'this-week' => 'Bu Hafta',
            'last-week' => 'Geçen Hafta',
            'this-month' => 'Bu Ay',
            'last-month' => 'Geçen Ay',
            'last-3-months' => 'Son 3 Ay',
            'last-6-months' => 'Son 6 Ay',
            'this-year' => 'Bu Yıl',
            'last-year' => 'Geçen Yıl',
            'all-time' => 'Tüm Zamanlar'
        ];
        
        return $labels[$period] ?? 'Özel Aralık';
    }
    
    /**
     * Raporu Excel veya PDF olarak dışa aktar
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportReport(Request $request)
    {
        // Excel veya PDF oluşturma ve indirme işlemleri burada olacak
        // İleride maatwebsite/excel veya dompdf gibi paketlerle implementasyon yapacagım
        
        return response()->json([
            'message' => 'Dışa aktarma özelliği yakında eklenecek.'
        ]);
    }
} 