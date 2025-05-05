<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TourController extends Controller
{

    public function index()
    {
        try {
            
            $format = request()->input('format', 'json');
            
            
            $tours = Tour::with('category')->get();
            
            
            $result = [];
            foreach ($tours as $tour) {
                try {
                   
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
                    
                    $result[] = [
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
                        'featured_image' => $tour->featured_image ?? '/build/assets/default-tour-image.jpg',
                        'gallery' => is_array($tour->gallery) ? $tour->gallery : [],
                        'included' => $includedArray,
                        'not_included' => $notIncludedArray,
                        'status' => $tour->status ?? 'inactive',
                        'status_text' => $tour->getStatusTextAttribute() ?? 'Pasif',
                        'average_rating' => $tour->getAverageRatingAttribute() ?? 0,
                        'review_count' => $tour->getReviewCountAttribute() ?? 0,
                        'created_at' => $tour->created_at?->format('Y-m-d H:i:s'),
                        'updated_at' => $tour->updated_at?->format('Y-m-d H:i:s')
                    ];
                } catch (\Exception $innerException) {
                    \Log::error('Tur dönüştürme hatası: ' . $innerException->getMessage(), [
                        'tour_id' => $tour->id,
                        'exception' => $innerException
                    ]);
                    
                   
                    $result[] = [
                        'id' => $tour->id ?? 0,
                        'title' => 'Hatalı Tur Kaydı',
                        'slug' => 'hata',
                        'description' => 'Bu tur verilerinde bir hata var',
                        'price' => 0,
                        'formatted_price' => '₺0',
                        'status' => 'inactive',
                        'error' => true
                    ];
                }
            }
            
            
            if ($format === 'debug') {
                return response()->json([
                    'count' => count($result),
                    'tours' => $result,
                    'meta' => [
                        'time' => now()->toDateTimeString(),
                        'memory_usage' => memory_get_usage(true)
                    ]
                ]);
            }
            
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Turlar listelenirken hata: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            
            return response()->json([
                'message' => 'Turlar yüklenirken bir hata oluştu. Teknik ekip bilgilendirildi.',
                'error' => true,
                'error_code' => 'TOURS_LIST_ERROR',
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|string|max:100',
                'location' => 'required|string|max:255',
                'category_id' => 'nullable|exists:tour_categories,id',
                'max_participants' => 'nullable|integer|min:1',
                'featured_image' => 'nullable|string',
                'gallery' => 'nullable|json',
                'included' => 'nullable|json',
                'not_included' => 'nullable|json',
                'status' => 'required|in:active,inactive,pending'
            ]);

            
            $validated['slug'] = Str::slug($validated['title']);

            
            if (!isset($validated['featured_image']) || empty($validated['featured_image'])) {
                $validated['featured_image'] = 'https://via.placeholder.com/800x600?text=Tur+Görseli';
            }
            
            
            if (!isset($validated['included']) || (is_string($validated['included']) && empty($validated['included']))) {
                $validated['included'] = '[]';
            }
            
            if (!isset($validated['not_included']) || (is_string($validated['not_included']) && empty($validated['not_included']))) {
                $validated['not_included'] = '[]';
            }
            
            
            if (isset($validated['gallery']) && is_string($validated['gallery'])) {
                $validated['gallery'] = json_decode($validated['gallery'], true);
            }
            
            if (isset($validated['included']) && is_string($validated['included'])) {
                $validated['included'] = json_decode($validated['included'], true);
            }
            
            if (isset($validated['not_included']) && is_string($validated['not_included'])) {
                $validated['not_included'] = json_decode($validated['not_included'], true);
            }
            
            
            \Log::info('Tur ekleme verileri:', $validated);
            
            
            $tour = Tour::create($validated);
            
           
            Cache::forget('tours');
            if ($tour->category_id) {
                $category = TourCategory::find($tour->category_id);
                if ($category && $category->slug) {
                    Cache::forget("tours.category.{$category->slug}");
                }
            }
            
            return response()->json([
                'message' => 'Tur başarıyla oluşturuldu',
                'tour' => $tour
            ], 201);
        } catch (\Exception $e) {
            
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $tour = Tour::with('category')->findOrFail($id);
            return response()->json($tour);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tur bulunamadı: ' . $e->getMessage()
            ], 404);
        }
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        try {
            $tour = Tour::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric|min:0',
                'duration' => 'sometimes|required|string|max:100',
                'location' => 'sometimes|required|string|max:255',
                'category_id' => 'nullable|exists:tour_categories,id',
                'max_participants' => 'nullable|integer|min:1',
                'featured_image' => 'nullable|string',
                'gallery' => 'nullable|json',
                'included' => 'nullable|json',
                'not_included' => 'nullable|json',
                'status' => 'sometimes|required|in:active,inactive,pending'
            ]);
            
           
            if (isset($validated['title']) && $validated['title'] !== $tour->title) {
                $validated['slug'] = Str::slug($validated['title']);
            }
            
           
            if (isset($validated['gallery']) && is_string($validated['gallery'])) {
                $validated['gallery'] = json_decode($validated['gallery'], true);
            }
            
            if (isset($validated['included']) && is_string($validated['included'])) {
                $validated['included'] = json_decode($validated['included'], true);
            }
            
            if (isset($validated['not_included']) && is_string($validated['not_included'])) {
                $validated['not_included'] = json_decode($validated['not_included'], true);
            }
            
           
            \Log::info('Tur güncelleme verileri:', $validated);
            
            
            $tour->update($validated);
            
            
            Cache::forget('tours');
            if ($tour->category_id) {
                $category = TourCategory::find($tour->category_id);
                if ($category && $category->slug) {
                    Cache::forget("tours.category.{$category->slug}");
                }
            }
            
            return response()->json([
                'message' => 'Tur başarıyla güncellendi',
                'tour' => $tour
            ]);
        } catch (\Exception $e) {
            // Hata durumunda detaylı bilgi dön
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function destroy(string $id)
    {
        try {
            
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'message' => 'Geçersiz tur ID değeri'
                ], 400);
            }
            
            
            $tour = Tour::find($id);
            
            
            if (!$tour) {
                return response()->json([
                    'message' => 'Silmeye çalıştığınız tur bulunamadı. Muhtemelen daha önce silinmiş veya hiç var olmamış.',
                    'tour_id' => $id
                ], 404);
            }
            
          
            if ($tour->category_id) {
                $category = TourCategory::find($tour->category_id);
                if ($category && $category->slug) {
                    Cache::forget("tours.category.{$category->slug}");
                }
            }
            
           
            $tour->delete();
            
            
            Cache::forget('tours');
            
            return response()->json([
                'message' => 'Tur başarıyla silindi',
                'tour_id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tur silinirken hata oluştu: ' . $e->getMessage(),
                'tour_id' => $id,
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
    
    /**
     * Tur durumunu değiştir (Aktif/Pasif/Bekliyor)
     */
    public function changeStatus(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,inactive,pending'
            ]);
            
            $tour = Tour::findOrFail($id);
            $tour->status = $validated['status'];
            $tour->save();
            
            return response()->json([
                'message' => 'Tur durumu başarıyla güncellendi',
                'status' => $tour->status,
                'status_text' => $tour->getStatusTextAttribute()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tur durumu güncellenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
} 