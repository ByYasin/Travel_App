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
        // Tur kategorileri tablosunu oluştur - tablo zaten önceki migration'larda oluşturuldu
        if (!Schema::hasTable('tour_categories')) {
            Schema::create('tour_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });

            // Self-referencing foreign key ekle
            Schema::table('tour_categories', function (Blueprint $table) {
                $table->foreign('parent_id')->references('id')->on('tour_categories')->onDelete('set null');
            });
        }

        // Tours tablosuna kategori ilişkisi ekle
        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                if (!Schema::hasColumn('tours', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('id');
                    $table->foreign('category_id')->references('id')->on('tour_categories')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('tours', 'slug')) {
                    $table->string('slug')->unique()->after('title')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'a özgü olarak gerçekleştirilen değişiklikleri geri al
        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                if (Schema::hasColumn('tours', 'category_id')) {
                    $table->dropForeign(['category_id']);
                    $table->dropColumn('category_id');
                }
                
                if (Schema::hasColumn('tours', 'slug')) {
                    $table->dropColumn('slug');
                }
            });
        }

        // Bu tablo önceki migrationlarda da oluşturuluyor/siliniyor
        // Bu nedenle burada silme işlemini yapmıyoruz
    }
}; 