<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Reservation;

class TourController extends Controller
{
    public function index(Request $request)
    {
    
        \Log::info('Tours Index çağrıldı', [
            'request' => $request->all(),
            'headers' => $request->headers->all()
        ]);
        
       
        $wantsJson = $request->expectsJson() || 
                     $request->is('api/*') || 
                     $request->wantsJson() || 
                     $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        \Log::info('İstek türü: ' . ($wantsJson ? 'JSON' : 'HTML'));
        
       
        if (!$wantsJson) {
            \Log::info('HTML yanıtı gönderiliyor');
            return view('app');
        }
        
       
        $tourCount = Tour::count();
        
        
        if ($tourCount === 0) {
            \Log::info('Tours tablosunda hiç kayıt bulunamadı, demo veriler ekleniyor');
            $this->createDemoTours();
        }
        
        
        $page = max(1, (int)$request->input('page', 1));
        $perPage = (int)$request->input('per_page', 9);
        
       
        $cacheKey = "tours_page_{$page}_perpage_{$perPage}_"
            . md5(json_encode($request->except(['page', 'per_page'])));
        
        
        if (Cache::has($cacheKey) && !$request->has('no_cache')) {
            $tours = Cache::get($cacheKey);
            \Log::info('Tours önbellekten alındı');
            return response()->json($tours);
        }
        
       
        $query = Tour::with(['category'])
            ->where('status', 'active');
        
 
        if ($request->has('type')) {
            $type = $request->input('type');
            $query->where('type', $type);
        }
        
        
        if ($request->has('category_id')) {
            $categoryId = $request->input('category_id');
            $query->where('category_id', $categoryId);
        }
        
       
        if ($request->has('region')) {
            $region = $request->input('region');
            $query->where('location', 'like', "%{$region}%");
        }
        
        
        if ($request->has('duration')) {
            $duration = $request->input('duration');
            if ($duration === '1 Gün') {
                $query->where('duration', 'like', "%1 Gün%");
            } else if ($duration === '2-3 Gün') {
                $query->where(function ($q) {
                    $q->where('duration', 'like', "%2 Gün%")
                      ->orWhere('duration', 'like', "%3 Gün%");
                });
            } else if ($duration === '4-7 Gün') {
                $query->where(function ($q) {
                    $q->where('duration', 'like', "%4 Gün%")
                      ->orWhere('duration', 'like', "%5 Gün%")
                      ->orWhere('duration', 'like', "%6 Gün%")
                      ->orWhere('duration', 'like', "%7 Gün%");
                });
            } else if ($duration === '8+ Gün') {
                $query->where(function ($q) {
                    $q->where('duration', 'like', "%8 Gün%")
                      ->orWhere('duration', 'like', "%9 Gün%")
                      ->orWhere('duration', 'like', "%10 Gün%")
                      ->orWhere('duration', 'like', "%11 Gün%")
                      ->orWhere('duration', 'like', "%12 Gün%")
                      ->orWhere('duration', 'not like', "%1 Gün%")
                      ->orWhere('duration', 'not like', "%2 Gün%")
                      ->orWhere('duration', 'not like', "%3 Gün%")
                      ->orWhere('duration', 'not like', "%4 Gün%")
                      ->orWhere('duration', 'not like', "%5 Gün%")
                      ->orWhere('duration', 'not like', "%6 Gün%")
                      ->orWhere('duration', 'not like', "%7 Gün%");
                });
            }
        }
        
        
        if ($request->has('price')) {
            $priceRange = $request->input('price');
            if ($priceRange === '0-500') {
                $query->whereBetween('price', [0, 500]);
            } else if ($priceRange === '501-1000') {
                $query->whereBetween('price', [501, 1000]);
            } else if ($priceRange === '1001-2000') {
                $query->whereBetween('price', [1001, 2000]);
            } else if ($priceRange === '2000+') {
                $query->where('price', '>', 2000);
            }
        }
        
        
        $sort = $request->input('sort', 'newest');
        if ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } else if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else if ($sort === 'price-low') {
            $query->orderBy('price', 'asc');
        } else if ($sort === 'price-high') {
            $query->orderBy('price', 'desc');
        } else if ($sort === 'popularity') {

            $query->orderBy('id', 'desc');
        } else if ($sort === 'rating') {

            $query->orderBy('id', 'desc');
        }
        
        try {

            $tours = $query->paginate($perPage, ['*'], 'page', $page);
            

            $tours->getCollection()->transform(function ($tour) {
                $tour->average_rating = $tour->getAverageRatingAttribute();
                $tour->review_count = $tour->getReviewCountAttribute();
                

                if (!empty($tour->gallery) && is_array($tour->gallery) && count($tour->gallery) > 5) {
                    $tour->gallery = array_slice($tour->gallery, 0, 5);
                }
                

                unset($tour->created_at, $tour->updated_at);
                
                return $tour;
            });
            

            Cache::put($cacheKey, $tours, 3600);
            
            \Log::info('Tours başarıyla yüklendi', [
                'count' => $tours->total(),
                'current_page' => $tours->currentPage(),
                'per_page' => $tours->perPage()
            ]);
            
            return response()->json($tours);
        } catch (\Exception $e) {
            \Log::error('Tour index hata:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Turlar yüklenirken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id, Request $request)
    {
        try {
            
            $wantsJson = $request->expectsJson() || 
                        $request->is('api/*') || 
                        $request->wantsJson() || 
                        $request->header('X-Requested-With') === 'XMLHttpRequest';
            
            
            if (!$wantsJson) {
                return view('app');
            }
            
            
            $cacheKey = "tour.{$id}";
            
           
            $tour = Cache::remember($cacheKey, 3600, function () use ($id) {
                return Tour::with('category')
                    ->findOrFail($id);
            });
            
            
            $tour->average_rating = $tour->getAverageRatingAttribute();
            $tour->review_count = $tour->getReviewCountAttribute();
            
            
            $includedArray = [];
            $notIncludedArray = [];
            
           
            if (!empty($tour->included)) {
                if (is_string($tour->included)) {
                    try {
                        $includedArray = json_decode($tour->included, true) ?: [];
                    } catch (\Exception $e) {
                        $includedArray = [];
                    }
                } elseif (is_array($tour->included)) {
                    $includedArray = $tour->included;
                }
            }
            
            
            if (!empty($tour->not_included)) {
                if (is_string($tour->not_included)) {
                    try {
                        $notIncludedArray = json_decode($tour->not_included, true) ?: [];
                    } catch (\Exception $e) {
                        $notIncludedArray = [];
                    }
                } elseif (is_array($tour->not_included)) {
                    $notIncludedArray = $tour->not_included;
                }
            }
            
            
            $tourData = [
                'id' => $tour->id,
                'title' => $tour->title ?? 'İsimsiz Tur',
                'slug' => $tour->slug ?? Str::slug($tour->title ?? 'isimsiz-tur'),
                'description' => $tour->description ?? '',
                'price' => (float)($tour->price ?? 0),
                'formatted_price' => $tour->getFormattedPriceAttribute(),
                'duration' => $tour->duration ?? '',
                'location' => $tour->location ?? '',
                'category_id' => $tour->category_id,
                'category_name' => $tour->category ? $tour->category->name : 'Kategorisiz',
                'max_participants' => $tour->max_participants ?? 0,
                'featured_image' => $tour->featured_image ?? '/images/tours/default.jpg',
                'gallery' => is_array($tour->gallery) ? $tour->gallery : [],
                'included' => $includedArray,
                'not_included' => $notIncludedArray,
                'status' => $tour->status ?? 'inactive',
                'status_text' => $tour->getStatusTextAttribute() ?? 'Pasif',
                'average_rating' => $tour->average_rating,
                'review_count' => $tour->review_count
            ];
            
            return response()->json($tourData);
        } catch (\Exception $e) {
            \Log::error('Tour show hata:', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Tur bilgileri alınırken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if (empty($query)) {
            return $this->index($request);
        }
        
        $cacheKey = "tours_search_" . md5($query);
        
        if (Cache::has($cacheKey) && !$request->has('no_cache')) {
            return response()->json(Cache::get($cacheKey));
        }
        
        $tours = Tour::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            })
            ->with(['category'])
            ->limit(24)
            ->get();
        
        
        $tours->transform(function ($tour) {
            $tour->average_rating = $tour->getAverageRatingAttribute();
            $tour->review_count = $tour->getReviewCountAttribute();
            
            
            unset($tour->created_at, $tour->updated_at);
            
            return $tour;
        });
        
        
        Cache::put($cacheKey, $tours, 1800);
        
        return response()->json($tours);
    }

    public function reservation($id)
    {
        $tour = Tour::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $tour
        ]);
    }

    public function createReservation(Request $request)
    {
        try {
            $validated = $request->validate([
                'tour_id' => 'required|exists:tours,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'participant_count' => 'required|integer|min:1',
                'date' => 'required|date|after:today',
                'notes' => 'nullable|string'
            ]);

            
            $tour = Tour::findOrFail($validated['tour_id']);
            
            
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'password' => Hash::make(Str::random(12)),
                    'phone' => $validated['phone'] ?? null
                ]
            );
            
           
            $totalPrice = $tour->price * $validated['participant_count'];
            
            
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'tour_id' => $validated['tour_id'],
                'reservation_date' => $validated['date'],
                'participant_count' => $validated['participant_count'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'special_requests' => $validated['notes'] ?? null,
            ]);
            
            
            $reservation->load(['user', 'tour']);
            
            return response()->json([
                'message' => 'Rezervasyon başarıyla oluşturuldu',
                'reservation' => $reservation
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasyon hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Rezervasyon oluşturma hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'message' => 'Rezervasyon oluşturulurken bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getCategories()
    {
        $categories = Cache::remember('tour_categories', 3600, function () {
            return TourCategory::where('is_active', true)->get();
        });

        return response()->json($categories);
    }


    public function getByCategory($categorySlug)
    {
        $category = TourCategory::where('slug', $categorySlug)->firstOrFail();
        
        $tours = Cache::remember("tours.category.{$categorySlug}", 3600, function () use ($category) {
            return Tour::where('category_id', $category->id)
                ->where('is_active', true)
                ->get();
        });

        return response()->json([
            'category' => $category,
            'tours' => $tours
        ]);
    }


    private function createDemoTours()
    {
 
        $kulturKategori = TourCategory::firstOrCreate(
            ['slug' => 'kultur-turu'],
            [
                'name' => 'Kültür Turu',
                'description' => 'Kültürel zenginlikleri keşfedin',
                'is_active' => true
            ]
        );
        
        $dogaKategori = TourCategory::firstOrCreate(
            ['slug' => 'doga-turu'],
            [
                'name' => 'Doğa Turu',
                'description' => 'Doğal güzellikleri keşfedin',
                'is_active' => true
            ]
        );
        

        Tour::create([
            'title' => 'İstanbul Turu',
            'slug' => 'istanbul-turu',
            'description' => 'İstanbul\'un tarihi ve doğal güzelliklerini keşfedin.',
            'price' => 1500,
            'duration' => '2 Gün',
            'location' => 'İstanbul',
            'category_id' => $kulturKategori->id,
            'type' => 'Kültür Turu',
            'status' => 'active',
            'max_participants' => 15,
            'included' => json_encode(['Konaklama', 'Rehber', 'Kahvaltı']),
            'not_included' => json_encode(['Alkollü İçecekler', 'Ekstra Aktiviteler']),
            'featured_image' => '/images/tours/default.jpg'
        ]);
        
        Tour::create([
            'title' => 'Kapadokya Turu',
            'slug' => 'kapadokya-turu',
            'description' => 'Peri bacaları ve balon turlarıyla Kapadokya\'yı keşfedin.',
            'price' => 2500,
            'duration' => '3 Gün',
            'location' => 'Nevşehir',
            'category_id' => $dogaKategori->id,
            'type' => 'Doğa Turu',
            'status' => 'active',
            'max_participants' => 12,
            'included' => json_encode(['Konaklama', 'Ulaşım', 'Kahvaltı']),
            'not_included' => json_encode(['Balon Turu', 'Öğle Yemeği']),
            'featured_image' => '/images/tours/default.jpg'
        ]);
        
        \Log::info('2 adet demo tur eklendi');
    }
} 