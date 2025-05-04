<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mevcut tur sayısını kontrol et
        $tourCount = DB::table('tours')->count();
        
        // Eğer hiç tur yoksa veya sayı belirli bir eşiğin altındaysa ekle
        if ($tourCount < 5) {
            $this->createSampleTours();
        }
    }
    
    /**
     * Örnek turları veritabanına ekle
     */
    private function createSampleTours(): void
    {
        $tours = [
            [
                'title' => 'İstanbul Turu',
                'slug' => 'istanbul-turu',
                'description' => 'İstanbul\'un tarihî yerlerini keşfedin. Ayasofya, Topkapı Sarayı, Sultanahmet Camii ve çok daha fazlası...',
                'price' => 1200,
                'duration' => '1 Gün',
                'location' => 'İstanbul',
                'category_id' => 1, // Şehir Turları kategorisi
                'max_participants' => 20,
                'featured_image' => '/images/tours/istanbul.jpg',
                'included' => json_encode(['Rehberlik Hizmeti', 'Öğle Yemeği', 'Müze Giriş Ücretleri']),
                'not_included' => json_encode(['Akşam Yemeği', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Kapadokya Balon Turu',
                'slug' => 'kapadokya-balon-turu',
                'description' => 'Eşsiz Kapadokya manzarasını gökyüzünden keşfedin. Muhteşem peri bacaları ve vadilerin üzerinde unutulmaz bir deneyim yaşayın.',
                'price' => 3500,
                'duration' => '3 Gün',
                'location' => 'Nevşehir, Kapadokya',
                'category_id' => 2, // Doğa Turları kategorisi
                'max_participants' => 15,
                'featured_image' => '/images/tours/kapadokya.jpg',
                'included' => json_encode(['Balon Turu', 'Konaklama', 'Kahvaltı', 'Ulaşım']),
                'not_included' => json_encode(['Akşam Yemekleri', 'İçecekler', 'Bahşişler']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Efes Antik Kenti Turu',
                'slug' => 'efes-antik-kenti-turu',
                'description' => 'Dünyanın en iyi korunmuş antik kentlerinden olan Efes\'i keşfedin. Roma dönemi yapıları, tapınaklar ve muhteşem mimarisiyle tarih yolculuğuna çıkın.',
                'price' => 850,
                'duration' => '1 Gün',
                'location' => 'İzmir, Selçuk',
                'category_id' => 3, // Tarih ve Kültür Turları kategorisi
                'max_participants' => 25,
                'featured_image' => '/images/tours/efes.jpg',
                'included' => json_encode(['Rehberlik Hizmeti', 'Giriş Ücretleri', 'Öğle Yemeği']),
                'not_included' => json_encode(['Ulaşım', 'Kişisel Harcamalar', 'İçecekler']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Pamukkale ve Hierapolis Turu',
                'slug' => 'pamukkale-hierapolis-turu',
                'description' => 'UNESCO Dünya Mirası Listesi\'nde yer alan Pamukkale\'nin beyaz travertenlerini ve antik Hierapolis kentini ziyaret edin.',
                'price' => 950,
                'duration' => '1 Gün',
                'location' => 'Denizli',
                'category_id' => 3, // Tarih ve Kültür Turları kategorisi
                'max_participants' => 20,
                'featured_image' => '/images/tours/pamukkale.jpg',
                'included' => json_encode(['Rehberlik Hizmeti', 'Giriş Ücretleri', 'Öğle Yemeği']),
                'not_included' => json_encode(['Ulaşım', 'Kişisel Harcamalar', 'İçecekler']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Fethiye Tekne Turu',
                'slug' => 'fethiye-tekne-turu',
                'description' => 'Türkiye\'nin en güzel koylarını keşfedin. Ölüdeniz, Kelebekler Vadisi ve Gemiler Adası\'nı içeren unutulmaz bir tekne turu.',
                'price' => 1800,
                'duration' => '1 Gün',
                'location' => 'Fethiye',
                'category_id' => 4, // Deniz Turları kategorisi
                'max_participants' => 30,
                'featured_image' => '/images/tours/fethiye.jpg',
                'included' => json_encode(['Tekne Turu', 'Öğle Yemeği', 'Çay ve Kahve']),
                'not_included' => json_encode(['Alkollü İçecekler', 'Kişisel Harcamalar', 'Dalış Ekipmanları']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        // Tüm turları veritabanına ekle
        foreach ($tours as $tour) {
            DB::table('tours')->insert($tour);
        }
        
        $this->command->info('Örnek turlar başarıyla eklendi.');
    }
} 