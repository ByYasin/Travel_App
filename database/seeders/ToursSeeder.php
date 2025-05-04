<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;

class ToursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eğer tours tablosunda zaten veri varsa silme
        if (Tour::count() > 0) {
            $this->command->info('Önceki tur verileri temizleniyor...');
            // Foreign key constraints'i devre dışı bırakarak silme
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Tour::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('Örnek tur verileri ekleniyor...');

        // Örnek tur verileri
        $tours = [
            [
                'title' => 'İstanbul Tarihi Yarımada Turu',
                'slug' => 'istanbul-tarihi-yarimada-turu',
                'description' => 'İstanbul\'un en önemli tarihi noktalarını keşfedin. Ayasofya, Sultanahmet Camii, Yerebatan Sarnıcı ve daha fazlası sizi bekliyor.',
                'price' => 1200,
                'featured_image' => '/images/tours/istanbul.jpg',
                'duration' => '1 Gün',
                'location' => 'İstanbul',
                'category' => 'Kültür Turu',
                'max_participants' => 20,
                'included' => json_encode(['Rehber', 'Öğle Yemeği', 'Müze Girişleri']),
                'not_included' => json_encode(['Ekstra Yemekler', 'Özel Fotoğraf Çekimi']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Kapadokya Balon Turu',
                'slug' => 'kapadokya-balon-turu',
                'description' => 'Güneşin doğuşuyla birlikte balonla Kapadokya\'nın peri bacalarının üzerinde unutulmaz bir deneyim yaşayın.',
                'price' => 3500,
                'featured_image' => '/images/tours/cappadocia.jpg',
                'duration' => '2-3 Gün',
                'location' => 'Nevşehir',
                'category' => 'Doğa Turu',
                'max_participants' => 16,
                'included' => json_encode(['Konaklama', 'Kahvaltı', 'Balon Uçuşu', 'Transfer']),
                'not_included' => json_encode(['Öğle ve Akşam Yemekleri', 'Ekstra Aktiviteler']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Efes Antik Kenti Turu',
                'slug' => 'efes-antik-kenti-turu',
                'description' => 'Antik dünyanın en iyi korunmuş kentlerinden biri olan Efes\'i keşfedin. Roma döneminden kalma yapılar ve muhteşem Celcius Kütüphanesi sizi zamanda yolculuğa çıkaracak.',
                'price' => 950,
                'featured_image' => '/images/tours/efes.jpg',
                'duration' => '1 Gün',
                'location' => 'İzmir',
                'category' => 'Kültür Turu',
                'max_participants' => 25,
                'included' => json_encode(['Rehber', 'Müze Girişleri', 'Ulaşım']),
                'not_included' => json_encode(['Yemekler', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pamukkale & Hierapolis Turu',
                'slug' => 'pamukkale-hierapolis-turu',
                'description' => 'Travertenleri ve antik kenti ile doğal ve tarihi güzellikleri keşfedin. Pamukkale\'nin beyaz terasları ve Hierapolis\'in tarihi kalıntıları sizi bekliyor.',
                'price' => 1450,
                'featured_image' => '/images/tours/pamukkale.jpg',
                'duration' => '2 Gün',
                'location' => 'Denizli',
                'category' => 'Doğa Turu',
                'max_participants' => 20,
                'included' => json_encode(['Konaklama', 'Kahvaltı', 'Rehber', 'Müze Girişleri']),
                'not_included' => json_encode(['Öğle ve Akşam Yemekleri', 'Ekstra Aktiviteler']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Fethiye Mavi Tur',
                'slug' => 'fethiye-mavi-tur',
                'description' => 'Türkiye\'nin en güzel koylarını tekne ile gezin. Berrak sularda yüzme, güneşlenme ve doğayla baş başa bir deneyim.',
                'price' => 2800,
                'featured_image' => '/images/tours/fethiye.jpg',
                'duration' => '4-7 Gün',
                'location' => 'Muğla',
                'category' => 'Deniz Turu',
                'max_participants' => 12,
                'included' => json_encode(['Tekne Konaklaması', 'Kahvaltı, Öğle ve Akşam Yemekleri', 'Çay-Kahve İkramları']),
                'not_included' => json_encode(['Alkollü İçecekler', 'Dalış Ekipmanları']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Karadeniz Yaylaları Turu',
                'slug' => 'karadeniz-yaylalari-turu',
                'description' => 'Yeşilin bin bir tonunu görebileceğiniz Karadeniz yaylaları sizleri bekliyor. Ayder, Uzungöl ve daha birçok doğal güzellik.',
                'price' => 3200,
                'featured_image' => '/images/tours/blacksea.jpg',
                'duration' => '4-7 Gün',
                'location' => 'Rize',
                'category' => 'Doğa Turu',
                'max_participants' => 15,
                'included' => json_encode(['Konaklama', 'Sabah, Öğle ve Akşam Yemekleri', 'Ulaşım', 'Rehber']),
                'not_included' => json_encode(['Ekstra Aktiviteler', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Gaziantep Lezzet Turu',
                'slug' => 'gaziantep-lezzet-turu',
                'description' => 'UNESCO Yaratıcı Şehirler Ağı\'na dahil olan Gastronomi şehri Gaziantep\'te eşsiz lezzetleri tadın ve tarihi mekanları keşfedin.',
                'price' => 1700,
                'featured_image' => '/images/tours/gaziantep.jpg',
                'duration' => '2-3 Gün',
                'location' => 'Gaziantep',
                'category' => 'Yemek Turu',
                'max_participants' => 12,
                'included' => json_encode(['Konaklama', 'Yemek Tadımları', 'Rehber', 'Müze Girişleri']),
                'not_included' => json_encode(['Ekstra Yemekler', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sümela Manastırı Turu',
                'slug' => 'sumela-manastiri-turu',
                'description' => 'Trabzon\'un Maçka ilçesindeki Altındere Vadisi\'nde bulunan ve tarihi M.S. 4. yüzyıla dayanan Sümela Manastırı\'nı keşfedin.',
                'price' => 850,
                'featured_image' => '/images/tours/sumela.jpg',
                'duration' => '1 Gün',
                'location' => 'Trabzon',
                'category' => 'Kültür Turu',
                'max_participants' => 20,
                'included' => json_encode(['Rehber', 'Müze Girişleri', 'Ulaşım']),
                'not_included' => json_encode(['Yemekler', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Göbeklitepe & Şanlıurfa Turu',
                'slug' => 'gobeklitepe-sanliurfa-turu',
                'description' => 'İnsanlık tarihinin bilinen en eski inanç merkezi Göbeklitepe ve Şanlıurfa\'nın tarihi ve kültürel zenginliklerini keşfedin.',
                'price' => 2150,
                'featured_image' => '/images/tours/gobeklitepe.jpg',
                'duration' => '2-3 Gün',
                'location' => 'Şanlıurfa',
                'category' => 'Kültür Turu',
                'max_participants' => 18,
                'included' => json_encode(['Konaklama', 'Kahvaltı', 'Rehber', 'Müze Girişleri']),
                'not_included' => json_encode(['Öğle ve Akşam Yemekleri', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bodrum Tekne Turu',
                'slug' => 'bodrum-tekne-turu',
                'description' => 'Bodrum\'un eşsiz koylarını ve plajlarını tekne ile keşfedin. Masmavi sularda yüzme, güneşlenme ve ferahlatıcı bir deneyim.',
                'price' => 950,
                'featured_image' => '/images/tours/bodrum.jpg',
                'duration' => '1 Gün',
                'location' => 'Muğla',
                'category' => 'Deniz Turu',
                'max_participants' => 30,
                'included' => json_encode(['Tekne Turu', 'Öğle Yemeği', 'İçecekler (Çay, Kahve, Su)']),
                'not_included' => json_encode(['Alkollü İçecekler', 'Dalış Ekipmanları']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Safranbolu Evleri Turu',
                'slug' => 'safranbolu-evleri-turu',
                'description' => 'UNESCO Dünya Miras Listesi\'nde yer alan Safranbolu\'nun tarihi evlerini ve yaşam kültürünü keşfedin.',
                'price' => 1100,
                'featured_image' => '/images/tours/safranbolu.jpg',
                'duration' => '2 Gün',
                'location' => 'Karabük',
                'category' => 'Kültür Turu',
                'max_participants' => 15,
                'included' => json_encode(['Konaklama', 'Kahvaltı', 'Rehber', 'Müze Girişleri']),
                'not_included' => json_encode(['Öğle ve Akşam Yemekleri', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Kayseri Erciyes Kayak Turu',
                'slug' => 'kayseri-erciyes-kayak-turu',
                'description' => 'Kayseri Erciyes\'te kayak yapın ve kış sporlarının keyfini çıkarın. Profesyonel eğitmenler eşliğinde kayak ve snowboard dersleri.',
                'price' => 3500,
                'featured_image' => '/images/tours/erciyes.jpg',
                'duration' => '2-3 Gün',
                'location' => 'Kayseri',
                'category' => 'Doğa Turu',
                'max_participants' => 10,
                'included' => json_encode(['Konaklama', 'Kahvaltı', 'Kayak Ekipmanları', 'Kayak Eğitmeni']),
                'not_included' => json_encode(['Öğle ve Akşam Yemekleri', 'Kişisel Harcamalar']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Toplu veri ekleme
        Tour::insert($tours);

        $this->command->info('Toplam ' . count($tours) . ' tur verisi başarıyla eklendi.');
    }
}
