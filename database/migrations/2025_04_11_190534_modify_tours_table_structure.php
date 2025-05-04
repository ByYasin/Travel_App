<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Eski sütunları nullable olarak ayarla
            if (Schema::hasColumn('tours', 'start_date')) {
                $table->date('start_date')->nullable()->change();
            }
            
            if (Schema::hasColumn('tours', 'end_date')) {
                $table->date('end_date')->nullable()->change();
            }
            
            if (Schema::hasColumn('tours', 'itinerary')) {
                $table->text('itinerary')->nullable()->change();
            }
            
            // Eğer category sütunu varsa ve string türünde ise, bunu kaldır (category_id kullanacağız)
            if (Schema::hasColumn('tours', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Geri alma işlemi yok
        });
    }
};
