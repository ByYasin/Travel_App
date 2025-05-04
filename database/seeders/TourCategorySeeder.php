<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TourCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut kategori sayısını kontrol et
        $categoryCount = DB::table('tour_categories')->count();
        
        // Eğer hiç kategori yoksa veya sayı belirli bir eşiğin altındaysa ekle
        if ($categoryCount < 4) {
            $this->createSampleCategories();
        }
    }
    
    /**
     * Örnek kategorileri veritabanına ekle
     */
    private function createSampleCategories(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Şehir Turları',
                'slug' => 'sehir-turlari',
                'description' => 'Türkiye\'nin ve dünyanın en güzel şehirlerini keşfedin.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Doğa Turları',
                'slug' => 'doga-turlari',
                'description' => 'Doğal güzellikleri keşfedin, dağları ve ormanları ziyaret edin.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Tarih ve Kültür Turları',
                'slug' => 'tarih-kultur-turlari',
                'description' => 'Antik kentler, müzeler ve tarihi yerler hakkında bilgi edinin.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'Deniz Turları',
                'slug' => 'deniz-turlari',
                'description' => 'Tekne turları, mavi yolculuk ve dalış deneyimleri yaşayın.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        // Tüm kategorileri veritabanına ekle
        foreach ($categories as $category) {
            DB::table('tour_categories')->updateOrInsert(
                ['id' => $category['id']],
                $category
            );
        }
        
        $this->command->info('Örnek kategoriler başarıyla eklendi.');
    }
}
