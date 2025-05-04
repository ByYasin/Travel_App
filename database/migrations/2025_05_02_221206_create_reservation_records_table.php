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
        // Bu migration'ı varlık kontrolü nedeniyle uygulamıyoruz
        // Örnekleri eklemek için başka bir yol tercih edilebilir
        
        // Not: Rezervasyon eklerken, referans verilen user_id ve tour_id değerlerinin gerçekten
        // veritabanında var olduğundan emin olmak gerekir (foreign key constraint ihlallerini önlemek için)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'da bir şey yapmadığımız için geri alma işlemi de yok
    }
};
