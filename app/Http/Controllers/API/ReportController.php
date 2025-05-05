<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**

     */
    public function getSummary(Request $request)
    {
        $period = $request->input('period', 'this-month');
        $dateRange = $this->getDateRange($period, $request->input('start_date'), $request->input('end_date'));
        
   
        $previousDateRange = $this->getPreviousPeriodRange($period, $dateRange);
        
   
        $currentData = $this->getPeriodData($dateRange['start'], $dateRange['end']);
        
    
        $previousData = $this->getPeriodData($previousDateRange['start'], $previousDateRange['end']);
        
     
        $incomeChange = $previousData['totalIncome'] > 0 
            ? round((($currentData['totalIncome'] - $previousData['totalIncome']) / $previousData['totalIncome']) * 100, 2)
            : 100;
            
        $reservationsChange = $previousData['totalReservations'] > 0
            ? round((($currentData['totalReservations'] - $previousData['totalReservations']) / $previousData['totalReservations']) * 100, 2)
            : 100;
            
        $averageSaleChange = $previousData['averageSale'] > 0
            ? round((($currentData['averageSale'] - $previousData['averageSale']) / $previousData['averageSale']) * 100, 2)
            : 100;
        
        return response()->json([
            'summary' => [
                'totalIncome' => $currentData['totalIncome'],
                'totalReservations' => $currentData['totalReservations'],
                'averageSale' => $currentData['averageSale'],
                'incomeChange' => $incomeChange,
                'reservationsChange' => $reservationsChange,
                'averageSaleChange' => $averageSaleChange,
                'period' => $period,
                'dateRange' => $dateRange
            ]
        ]);
    }
    
    /**

     */
    public function getMonthlyIncome(Request $request)
    {
        $period = $request->input('period', 'this-month');
        $dateRange = $this->getDateRange($period, $request->input('start_date'), $request->input('end_date'));
        
   
        $groupBy = 'day'; 
        $format = 'd M'; 
        
        if (in_array($period, ['this-year', 'last-year'])) {
            $groupBy = 'month';
            $format = 'F';
        } elseif (in_array($period, ['last-3-months', 'last-6-months'])) {
            $groupBy = 'week';
            $format = 'W. Hafta'; 
        }
        
       
        $query = DB::table('reservations')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"),
                DB::raw('SUM(total_price) as total')
            )
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'confirmed')
            ->groupBy('date')
            ->orderBy('date');
            
        $results = $query->get();
        
        
        $labels = [];
        $data = [];
        
        foreach ($results as $row) {
            $date = Carbon::parse($row->date);
            $labels[] = $date->format($format);
            $data[] = $row->total;
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
    
    /**
 
     */
    public function getTopTours(Request $request)
    {
        $period = $request->input('period', 'this-month');
        $dateRange = $this->getDateRange($period, $request->input('start_date'), $request->input('end_date'));
        
        $topTours = DB::table('reservations')
            ->join('tours', 'reservations.tour_id', '=', 'tours.id')
            ->select(
                'tours.id',
                'tours.name',
                'tours.image',
                DB::raw('COUNT(reservations.id) as reservationCount'),
                DB::raw('SUM(reservations.total_price) as totalSales'),
                DB::raw('AVG(reservations.total_price) as avgSale'),
                DB::raw('(SELECT AVG(rating) FROM tour_reviews WHERE tour_id = tours.id) as rating')
            )
            ->whereBetween('reservations.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('reservations.status', 'confirmed')
            ->groupBy('tours.id', 'tours.name', 'tours.image')
            ->orderByDesc('totalSales')
            ->limit(10)
            ->get();
            
        
        foreach ($topTours as $tour) {
            
            $trend = rand(0, 2); 
            $tour->trend = ['down', 'stable', 'up'][$trend];
        }
        
        return response()->json($topTours);
    }
    
    /**
     
     */
    public function getUserDemographics()
    {
        
        $ageGroups = DB::table('users')
            ->select(
                DB::raw('CASE
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18 THEN "0-17"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 24 THEN "18-24"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 25 AND 34 THEN "25-34"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 35 AND 44 THEN "35-44"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 45 AND 54 THEN "45-54"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 55 AND 64 THEN "55-64"
                    ELSE "65+" END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('birthdate')
            ->groupBy('age_group')
            ->orderBy(DB::raw('MIN(TIMESTAMPDIFF(YEAR, birthdate, CURDATE()))'))
            ->get();
            
       
        $genderDistribution = DB::table('users')
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();
            
        return response()->json([
            'ageGroups' => $ageGroups,
            'genderDistribution' => $genderDistribution
        ]);
    }
    
    /**

     */
    public function getParticipantStats(Request $request)
    {
        $period = $request->input('period', 'this-month');
        $dateRange = $this->getDateRange($period, $request->input('start_date'), $request->input('end_date'));
        
  
        $participantDistribution = DB::table('reservations')
            ->select('participant_count', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('participant_count')
            ->groupBy('participant_count')
            ->orderBy('participant_count')
            ->get();
            

        $averageParticipants = DB::table('reservations')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('participant_count')
            ->avg('participant_count') ?? 0;
            
        return response()->json([
            'participantDistribution' => $participantDistribution,
            'averageParticipants' => round($averageParticipants, 1)
        ]);
    }
    
    /**
     * Rapor dışa aktarma
     */
    public function exportReport(Request $request)
    {
        $format = $request->input('format', 'pdf');
        $period = $request->input('period', 'this-month');
        
        try {
            
            $dateRange = $this->getDateRange($period, $request->input('start_date'), $request->input('end_date'));
            
            
            $summaryData = $this->getPeriodData($dateRange['start'], $dateRange['end']);
            
            
            $reportData = [
                'period' => $period,
                'start_date' => $dateRange['start']->format('d.m.Y'),
                'end_date' => $dateRange['end']->format('d.m.Y'),
                'total_income' => $summaryData['totalIncome'],
                'total_reservations' => $summaryData['totalReservations'],
                'average_sale' => $summaryData['averageSale'],
                'generated_at' => now()->format('d.m.Y H:i')
            ];
            
            
            $topTours = DB::table('reservations')
                ->join('tours', 'reservations.tour_id', '=', 'tours.id')
                ->select(
                    'tours.id',
                    'tours.name',
                    DB::raw('COUNT(reservations.id) as reservationCount'),
                    DB::raw('SUM(reservations.total_price) as totalSales')
                )
                ->whereBetween('reservations.created_at', [$dateRange['start'], $dateRange['end']])
                ->where('reservations.status', 'confirmed')
                ->groupBy('tours.id', 'tours.name')
                ->orderByDesc('totalSales')
                ->limit(10)
                ->get();
                
            $reportData['top_tours'] = $topTours;
            
            
            if ($format === 'pdf') {
  
                $content = "Tur Raporu\n\n";
                $content .= "Dönem: {$reportData['start_date']} - {$reportData['end_date']}\n";
                $content .= "Toplam Gelir: {$reportData['total_income']} TL\n";
                $content .= "Toplam Rezervasyon: {$reportData['total_reservations']}\n";
                $content .= "Ortalama Satış: {$reportData['average_sale']} TL\n\n";
                $content .= "En Çok Satan Turlar:\n";
                
                foreach ($topTours as $tour) {
                    $content .= "- {$tour->name}: {$tour->totalSales} TL ({$tour->reservationCount} rezervasyon)\n";
                }
                
                
                $headers = [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "attachment; filename=tur-raporu-{$period}.pdf",
                ];
                
                
                return response($content, 200, $headers);
            } 
            // Excel formatı için
            else if ($format === 'excel') {
 
                
                
                $content = "Dönem,Toplam Gelir,Toplam Rezervasyon,Ortalama Satış\n";
                $content .= "{$reportData['start_date']} - {$reportData['end_date']},{$reportData['total_income']},{$reportData['total_reservations']},{$reportData['average_sale']}\n\n";
                $content .= "Tur Adı,Toplam Satış,Rezervasyon Sayısı\n";
                
                foreach ($topTours as $tour) {
                    $content .= "{$tour->name},{$tour->totalSales},{$tour->reservationCount}\n";
                }
                
                
                $headers = [
                    'Content-Type' => 'application/vnd.ms-excel',
                    'Content-Disposition' => "attachment; filename=tur-raporu-{$period}.xlsx",
                ];
                
                return response($content, 200, $headers);
            }
            
            return response()->json([
                'error' => 'Desteklenmeyen format',
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Rapor dışa aktarılırken bir hata oluştu',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**

     */
    private function getPeriodData($startDate, $endDate)
    {
        
        $totalIncome = DB::table('reservations')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'confirmed')
            ->sum('total_price') ?? 0;
            
        
        $totalReservations = DB::table('reservations')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'confirmed')
            ->count();
            
       
        $averageSale = $totalReservations > 0 ? ($totalIncome / $totalReservations) : 0;
        
        return [
            'totalIncome' => $totalIncome,
            'totalReservations' => $totalReservations,
            'averageSale' => $averageSale
        ];
    }
    
    /**

     */
    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();
        
   
        if ($period === 'custom' && $startDate && $endDate) {
            return [
                'start' => Carbon::parse($startDate)->startOfDay(),
                'end' => Carbon::parse($endDate)->endOfDay()
            ];
        }
        
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
                
            case 'this-month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
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
                    'end' => $now
                ];
                
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }
    
    /**

     */
    private function getPreviousPeriodRange($period, $currentRange = null)
    {
        if (!$currentRange) {
            $currentRange = $this->getDateRange($period);
        }
        
        $currentStart = Carbon::parse($currentRange['start']);
        $currentEnd = Carbon::parse($currentRange['end']);
        

        $diff = $currentStart->diffInDays($currentEnd) + 1;
        
    
        return [
            'start' => $currentStart->copy()->subDays($diff),
            'end' => $currentEnd->copy()->subDays($diff)
        ];
    }
} 