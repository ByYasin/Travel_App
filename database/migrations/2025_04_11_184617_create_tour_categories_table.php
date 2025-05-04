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
        // Tablo zaten var olduğunda tekrar oluşturmayalım
        if (!Schema::hasTable('tour_categories')) {
            Schema::create('tour_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('parent_id')->nullable()->constrained('tour_categories')->nullOnDelete();
                $table->integer('order')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'a özgü olarak bir şey yapmayalım
        // Tablo daha önceki migrationlarda yönetiliyor
    }
};
