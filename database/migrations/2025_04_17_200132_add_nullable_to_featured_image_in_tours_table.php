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
            // featured_image alanının adını kontrol et
            $columnExists = false;
            $columnName = '';
            
            if (Schema::hasColumn('tours', 'featured_image')) {
                $columnExists = true;
                $columnName = 'featured_image';
            } elseif (Schema::hasColumn('tours', 'image')) {
                $columnExists = true;
                $columnName = 'image';
            }
            
            if ($columnExists) {
                Schema::table('tours', function (Blueprint $table) use ($columnName) {
                    // Sütunu nullable olarak güncelle
                    $table->string($columnName)->nullable()->change();
                });
                Log::info('Tours tablosundaki ' . $columnName . ' alanı nullable olarak güncellendi');
            } else {
                // Eğer sütun yoksa ekle
                Schema::table('tours', function (Blueprint $table) {
                    $table->string('featured_image')->nullable()->after('price');
                });
                Log::info('Tours tablosuna featured_image alanı nullable olarak eklendi');
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
