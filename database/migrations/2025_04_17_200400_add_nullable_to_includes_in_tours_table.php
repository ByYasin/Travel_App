<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // 'includes' veya 'included' kolonunun varlığını kontrol et
            if (Schema::hasColumn('tours', 'includes')) {
                Schema::table('tours', function (Blueprint $table) {
                    // Sütunu nullable olarak güncelle
                    $table->json('includes')->nullable()->change();
                });
                Log::info('Tours tablosundaki includes alanı nullable olarak güncellendi');
            } 
            elseif (Schema::hasColumn('tours', 'included')) {
                Schema::table('tours', function (Blueprint $table) {
                    // Sütunu nullable olarak güncelle
                    $table->json('included')->nullable()->change();
                });
                Log::info('Tours tablosundaki included alanı nullable olarak güncellendi');
            }
            else {
                // Eğer sütun yoksa ekle
                Schema::table('tours', function (Blueprint $table) {
                    $table->json('included')->nullable()->after('featured_image');
                });
                Log::info('Tours tablosuna included alanı nullable olarak eklendi');
            }
        } catch (\Exception $e) {
            Log::error('Migration hatası: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi - önceki haline döndürmek için 
        // nullable olmayan duruma geri döndürmek mantıklı olmayacağından
        // özel bir işlem yapmıyoruz
    }
};
