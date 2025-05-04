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
                    $table->string('featured_image')->nullable()->default(null)->change();
                    Log::info('Tours tablosundaki featured_image alanı nullable olarak güncellendi');
                }
            });
        } catch (\Exception $e) {
            Log::error('Migration hatası: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi gerekli değil
    }
};
