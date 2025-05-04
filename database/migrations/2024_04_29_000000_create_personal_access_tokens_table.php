<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bu migration önceden oluşturulmuş olduğu için boş bırakılmıştır.
     */
    public function up(): void
    {
        // personal_access_tokens tablosu 2025_04_07_183221_create_personal_access_tokens_table.php
        // içerisinde zaten oluşturuldu, duplikasyonu önlemek için boş bırakıldı
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // İlk migration down'da tabloyu silecek olduğu için burada bir şey yapmıyoruz
    }
}; 