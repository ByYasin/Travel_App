<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
        Schema::table('tours', function (Blueprint $table) {
                if (Schema::hasColumn('tours', 'featured_image')) {
                    // featured_image alanını LONGTEXT veri tipine güncelleyelim
                    $table->longText('featured_image')->nullable()->change();
                    Log::info('Tours tablosundaki featured_image sütunu LONGTEXT olarak başarıyla güncellendi.');
                } else {
                    Log::warning('featured_image sütunu tours tablosunda bulunamadı.');
                }
            });
        } catch (\Exception $e) {
            Log::error('Tours tablosundaki featured_image sütununu güncellerken hata: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration için geri alma işlemi gerekmiyor
        // Çünkü LONGTEXT'ten TEXT veya başka bir türe dönüştürürsek veri kaybı olabilir
    }
};
