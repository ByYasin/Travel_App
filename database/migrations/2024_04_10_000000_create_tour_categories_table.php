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
        // Eğer tour_categories tablosu henüz oluşturulmamışsa oluştur
        // Bu tablo 2024_04_10_000000_create_tour_categories_and_update_users.php'de de oluşturuluyor
        // Bu nedenle kontrol ekliyoruz
        if (!Schema::hasTable('tour_categories')) {
            Schema::create('tour_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('parent_id')->references('id')->on('tour_categories')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'a özgü olarak bir şey yapmıyoruz
        // Tablo diğer migration'larda zaten silinecek
    }
};
