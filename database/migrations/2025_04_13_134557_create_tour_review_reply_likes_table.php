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
        Schema::create('tour_review_reply_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reply_id')->constrained('tour_review_replies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Bir kullanıcı bir yanıtı sadece bir kere beğenebilir
            $table->unique(['reply_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_review_reply_likes');
    }
};
