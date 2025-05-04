<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourCategory;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TourCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Veritabanındaki tüm kategorileri al ve tour sayılarını hesapla
            // is_active=true filtresi uygula (istenirse query string ile override edilebilir)
            $onlyActive = request()->has('active') ? request()->boolean('active') : false;
            
            $query = TourCategory::withCount('tours');
            
            // Eğer sadece aktif kategoriler isteniyorsa filtrele
            if ($onlyActive) {
                $query->where('is_active', true);
            }
            
            $categories = $query->get();
            
            // API yanıtı için basit bir dizi oluştur
            $result = [];
            foreach ($categories as $category) {
                $result[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image,
                    'is_active' => $category->is_active,
                    'tours_count' => $category->tours_count  // withCount ile eklenen alan
                ];
            }
            
            return response()->json($result);
        } catch (\Exception $e) {
            // Hata detaylarını döndür
            return response()->json([
                'message' => 'Kategoriler yüklenirken bir hata oluştu: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|string|max:255',
                'is_active' => 'nullable'
            ]);

            // Checkbox kontrolü - is_active değerini doğru şekilde işle
            if ($request->has('is_active')) {
                $isActive = $request->input('is_active');
                // "on" stringi veya "1" gibi değerleri boolean true'ya dönüştür
                $validated['is_active'] = in_array($isActive, ['on', '1', 1, 'true', true], true);
            } else {
                $validated['is_active'] = false;
            }

            // Debug bilgisi
            \Log::info('Kategori ekleme verileri:', $validated);

            // Slug oluştur
            $validated['slug'] = Str::slug($validated['name']);
            
            // Kategori oluştur
            $category = TourCategory::create($validated);
            
            // Önbelleği temizle
            Cache::forget('tour_categories');
            
            return response()->json([
                'message' => 'Kategori başarıyla oluşturuldu',
                'category' => $category
            ], 201);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = TourCategory::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = TourCategory::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|string|max:255',
                'is_active' => 'nullable'
            ]);
            
            // Checkbox kontrolü - is_active değerini doğru şekilde işle
            if ($request->has('is_active')) {
                $isActive = $request->input('is_active');
                // "on" stringi veya "1" gibi değerleri boolean true'ya dönüştür
                $validated['is_active'] = in_array($isActive, ['on', '1', 1, 'true', true], true);
            } else {
                $validated['is_active'] = false;
            }
            
            // Debug bilgisi
            \Log::info('Kategori güncelleme verileri:', $validated);
            
            // Eğer isim değiştiyse, slug'ı da güncelle
            if (isset($validated['name']) && $validated['name'] !== $category->name) {
                $validated['slug'] = Str::slug($validated['name']);
            }
            
            // Kategori güncelle
            $category->update($validated);
            
            // Önbelleği temizle
            Cache::forget('tour_categories');
            if ($category->slug) {
                Cache::forget("tours.category.{$category->slug}");
            }
            
            return response()->json([
                'message' => 'Kategori başarıyla güncellendi',
                'category' => $category
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = TourCategory::findOrFail($id);
        
        // Kategoriye ait turların kategori_id'sini null yap
        Tour::where('category_id', $id)->update(['category_id' => null]);
        
        // Kategoriyi tamamen sil
        $category->forceDelete();
        
        // Önbelleği temizle
        Cache::forget('tour_categories');
        if ($category->slug) {
            Cache::forget("tours.category.{$category->slug}");
        }
        
        return response()->json([
            'message' => 'Kategori başarıyla silindi'
        ]);
    }
    
    /**
     * Turları kategoriye atama
     */
    public function assignToursToCategory(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:tour_categories,id',
            'tour_ids' => 'required|array',
            'tour_ids.*' => 'exists:tours,id'
        ]);
        
        // Turları güncelle
        Tour::whereIn('id', $validated['tour_ids'])
            ->update(['category_id' => $validated['category_id']]);
            
        // Kategoriyi al
        $category = TourCategory::find($validated['category_id']);
        
        // Önbelleği temizle
        Cache::forget('tours');
        if ($category->slug) {
            Cache::forget("tours.category.{$category->slug}");
        }
        
        return response()->json([
            'message' => 'Turlar kategoriye başarıyla atandı'
        ]);
    }
}
