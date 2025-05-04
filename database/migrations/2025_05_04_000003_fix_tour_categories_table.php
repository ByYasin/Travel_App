<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Tour categories tablosunun varlığını kontrol et
            if (!Schema::hasTable('tour_categories')) {
                Schema::create('tour_categories', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug')->unique();
                    $table->text('description')->nullable();
                    $table->string('icon')->nullable();
                    $table->string('image')->nullable();
                    $table->boolean('is_active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
                
                // Örnek kategoriler ekle
                $categories = [
                    [
                        'name' => 'Kültür Turları',
                        'slug' => 'kultur-turlari',
                        'description' => 'Tarihi ve kültürel değerleri keşfedin',
                        'icon' => 'museum',
                        'is_active' => true,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Doğa Turları',
                        'slug' => 'doga-turlari',
                        'description' => 'Doğa ile baş başa kalmak isteyenler için turlar',
                        'icon' => 'tree',
                        'is_active' => true,
                        'sort_order' => 2
                    ],
                    [
                        'name' => 'Macera Turları',
                        'slug' => 'macera-turlari',
                        'description' => 'Adrenalin tutkunları için macera dolu deneyimler',
                        'icon' => 'hiking',
                        'is_active' => true,
                        'sort_order' => 3
                    ],
                    [
                        'name' => 'Deniz Turları',
                        'slug' => 'deniz-turlari',
                        'description' => 'Mavi yolculuk ve deniz keyfi',
                        'icon' => 'ship',
                        'is_active' => true,
                        'sort_order' => 4
                    ]
                ];
                
                foreach ($categories as $category) {
                    DB::table('tour_categories')->insert(array_merge($category, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
                
                Log::info('Tour categories tablosu başarıyla oluşturuldu ve örnek veriler eklendi');
            } else {
                // Tablo varsa gerekli sütunları kontrol et
                $requiredColumns = [
                    'name', 'slug', 'description', 'icon', 'image', 'is_active', 'sort_order'
                ];
                
                foreach ($requiredColumns as $column) {
                    if (!Schema::hasColumn('tour_categories', $column)) {
                        Schema::table('tour_categories', function (Blueprint $table) use ($column) {
                            switch ($column) {
                                case 'name':
                                    $table->string('name');
                                    break;
                                case 'slug':
                                    $table->string('slug')->unique();
                                    break;
                                case 'description':
                                    $table->text('description')->nullable();
                                    break;
                                case 'icon':
                                    $table->string('icon')->nullable();
                                    break;
                                case 'image':
                                    $table->string('image')->nullable();
                                    break;
                                case 'is_active':
                                    $table->boolean('is_active')->default(true);
                                    break;
                                case 'sort_order':
                                    $table->integer('sort_order')->default(0);
                                    break;
                            }
                        });
                        
                        Log::info("Tour categories tablosuna '{$column}' sütunu eklendi");
                    }
                }
                
                // Tours tablosunda category_id ilişkisini kontrol et
                if (Schema::hasTable('tours') && !Schema::hasColumn('tours', 'category_id')) {
                    Schema::table('tours', function (Blueprint $table) {
                        $table->foreignId('category_id')->nullable()->constrained('tour_categories')->onDelete('set null');
                    });
                    
                    Log::info("Tours tablosuna category_id sütunu eklendi");
                }
                
                // Kategori sayısı kontrol et ve gerekirse örnek veriler ekle
                $categoryCount = DB::table('tour_categories')->count();
                
                if ($categoryCount === 0) {
                    // Örnek kategoriler ekle
                    $categories = [
                        [
                            'name' => 'Kültür Turları',
                            'slug' => 'kultur-turlari',
                            'description' => 'Tarihi ve kültürel değerleri keşfedin',
                            'icon' => 'museum',
                            'is_active' => true,
                            'sort_order' => 1
                        ],
                        [
                            'name' => 'Doğa Turları',
                            'slug' => 'doga-turlari',
                            'description' => 'Doğa ile baş başa kalmak isteyenler için turlar',
                            'icon' => 'tree',
                            'is_active' => true,
                            'sort_order' => 2
                        ],
                        [
                            'name' => 'Macera Turları',
                            'slug' => 'macera-turlari',
                            'description' => 'Adrenalin tutkunları için macera dolu deneyimler',
                            'icon' => 'hiking',
                            'is_active' => true,
                            'sort_order' => 3
                        ],
                        [
                            'name' => 'Deniz Turları',
                            'slug' => 'deniz-turlari',
                            'description' => 'Mavi yolculuk ve deniz keyfi',
                            'icon' => 'ship',
                            'is_active' => true,
                            'sort_order' => 4
                        ]
                    ];
                    
                    foreach ($categories as $category) {
                        DB::table('tour_categories')->insert(array_merge($category, [
                            'created_at' => now(),
                            'updated_at' => now()
                        ]));
                    }
                    
                    Log::info('Tour categories tablosuna örnek veriler eklendi');
                }
                
                Log::info('Tour categories tablosu kontrol edildi ve güncellendi');
            }
            
            // Tour tablosundaki category sütunu ile category_id sütunu arasında uyumluluğu sağla
            if (Schema::hasTable('tours') && 
                Schema::hasColumn('tours', 'category') && 
                Schema::hasColumn('tours', 'category_id')) {
                
                // Category string değerlerini category_id değerlerine dönüştür
                $tours = DB::table('tours')
                            ->whereNull('category_id')
                            ->whereNotNull('category')
                            ->get();
                
                foreach ($tours as $tour) {
                    if (!empty($tour->category)) {
                        // Benzer kategori adını bul
                        $matchingCategory = DB::table('tour_categories')
                                            ->where('name', 'like', '%' . $tour->category . '%')
                                            ->first();
                        
                        if ($matchingCategory) {
                            DB::table('tours')
                                ->where('id', $tour->id)
                                ->update(['category_id' => $matchingCategory->id]);
                            
                            Log::info("Tour ID: {$tour->id} için kategori güncellemesi yapıldı: {$tour->category} -> {$matchingCategory->id}");
                        }
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Tour categories tablosu oluşturulurken veya güncellenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration düzeltme amaçlı olduğu için down metodu yoktur
    }
}; 