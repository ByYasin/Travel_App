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
        Schema::create('tour_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->comment('1-5 rating');
            $table->text('comment');
            $table->string('status')->default('approved')->comment('pending, approved, rejected');
            $table->boolean('is_visible')->default(true);
            $table->integer('likes_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Her kullanıcı bir tura sadece bir değerlendirme yapabilir
            $table->unique(['tour_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_reviews');
    }
};
