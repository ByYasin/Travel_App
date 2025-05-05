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

    public function index()
    {
        try {
 
            $onlyActive = request()->has('active') ? request()->boolean('active') : false;
            
            $query = TourCategory::withCount('tours');
            
            
            if ($onlyActive) {
                $query->where('is_active', true);
            }
            
            $categories = $query->get();
            
            
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


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|string|max:255',
                'is_active' => 'nullable'
            ]);


            if ($request->has('is_active')) {
                $isActive = $request->input('is_active');
               
                $validated['is_active'] = in_array($isActive, ['on', '1', 1, 'true', true], true);
            } else {
                $validated['is_active'] = false;
            }

           
            \Log::info('Kategori ekleme verileri:', $validated);

           
            $validated['slug'] = Str::slug($validated['name']);
            
            
            $category = TourCategory::create($validated);
            
           
            Cache::forget('tour_categories');
            
            return response()->json([
                'message' => 'Kategori başarıyla oluşturuldu',
                'category' => $category
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
        $category = TourCategory::findOrFail($id);
        return response()->json($category);
    }


    public function edit(string $id)
    {
        //
    }


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
            
            
            if ($request->has('is_active')) {
                $isActive = $request->input('is_active');
                
                $validated['is_active'] = in_array($isActive, ['on', '1', 1, 'true', true], true);
            } else {
                $validated['is_active'] = false;
            }
            
           
            \Log::info('Kategori güncelleme verileri:', $validated);
            
            
            if (isset($validated['name']) && $validated['name'] !== $category->name) {
                $validated['slug'] = Str::slug($validated['name']);
            }
            
           
            $category->update($validated);
            
            
            Cache::forget('tour_categories');
            if ($category->slug) {
                Cache::forget("tours.category.{$category->slug}");
            }
            
            return response()->json([
                'message' => 'Kategori başarıyla güncellendi',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            
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
        $category = TourCategory::findOrFail($id);
        
        Tour::where('category_id', $id)->update(['category_id' => null]);
        
        
        $category->forceDelete();
        
        
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
        
        
        Tour::whereIn('id', $validated['tour_ids'])
            ->update(['category_id' => $validated['category_id']]);
            
        
        $category = TourCategory::find($validated['category_id']);
        
        
        Cache::forget('tours');
        if ($category->slug) {
            Cache::forget("tours.category.{$category->slug}");
        }
        
        return response()->json([
            'message' => 'Turlar kategoriye başarıyla atandı'
        ]);
    }
}
