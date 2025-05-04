<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Roles tablosunu kontrol et
        if (!Schema::hasTable('roles')) {
            // Eğer roles tablosu yoksa oluştur
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        } else {
            // Roles tablosu varsa içeriğini temizle
            DB::table('roles')->truncate();
        }
        
        // Varsayılan rolleri ekle
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Tüm kullanıcıların role_id değerlerini güncelle
        DB::table('users')->update(['role_id' => 2]); // Önce herkesi normal kullanıcı yap
        
        // Admin email'i ile kullanıcıları bul ve admin rolüne yükselt
        DB::table('users')
            ->where('email', 'like', '%admin%')
            ->update(['role_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rolleri temizle
        DB::table('roles')->truncate();
    }
}; 