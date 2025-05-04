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
            // slug sütununu ekle
            if (!Schema::hasColumn('tours', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            
            // Kolon adlarını kontrol et ve güncelle
            if (Schema::hasColumn('tours', 'image') && !Schema::hasColumn('tours', 'featured_image')) {
                $table->renameColumn('image', 'featured_image');
            } elseif (!Schema::hasColumn('tours', 'featured_image')) {
                $table->string('featured_image')->nullable()->after('price');
            }
            
            if (Schema::hasColumn('tours', 'start_location') && !Schema::hasColumn('tours', 'location')) {
                $table->renameColumn('start_location', 'location');
            } elseif (!Schema::hasColumn('tours', 'location')) {
                $table->string('location')->nullable()->after('duration');
            }
            
            if (!Schema::hasColumn('tours', 'status')) {
                $table->string('status')->default('active')->after('max_participants');
            }
            
            // Diğer sütunları kontrol et ve güncelle
            if (Schema::hasColumn('tours', 'includes') && !Schema::hasColumn('tours', 'included')) {
                $table->renameColumn('includes', 'included');
            }
            
            if (Schema::hasColumn('tours', 'excludes') && !Schema::hasColumn('tours', 'not_included')) {
                $table->renameColumn('excludes', 'not_included');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
