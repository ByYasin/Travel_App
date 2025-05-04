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
        // Users tablosunun şemasını değiştirerek role_id'nin yerini değiştiriyoruz
        Schema::table('users', function (Blueprint $table) {
            // role_id'yi geçici olarak kaldır (veriyi koruyarak)
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        // role_id'yi password'den sonra ve name'den önce ekle
        Schema::table('users', function (Blueprint $table) {
            // Doğru sıralama için after metodu kullanılıyor
            $table->foreignId('role_id')->after('password')->default(1)->constrained('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri almak gerekirse orijinal yapıya dön
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::table('users', function (Blueprint $table) {
            // Orjinal yere ekle - fillable array'deki sıralamaya göre name'den sonra
            $table->foreignId('role_id')->after('name')->default(1)->constrained('roles');
        });
    }
}; 