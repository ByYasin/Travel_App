<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bu migration'ı, örnek verilerle tabloları doldurma işlemini devre dışı bırakıyoruz
        // Çünkü tours tablosunda 'category' sütunu yok, category_id var
        // Ayrıca örnekleri eklemek için foreign key constraint'leri dikkate almak gerekir
        
        // Not: Gerçek uygulamada, burada örnek kategoriler oluşturup sonra o kategorilere
        // ait tur kayıtları eklenebilir. Ancak migration'larda karmaşıklığı azaltmak için
        // bu işlemler genellikle Database Seeder'lar üzerinden yapılır.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'da bir şey yapmadığımız için geri alma işlemi de yok
    }
};
