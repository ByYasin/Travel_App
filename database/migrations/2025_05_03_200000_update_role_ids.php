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
        // 1) Önce tüm kullanıcılardaki role_id referanslarını geçici olarak güvenli bir değere güncelle
        DB::table('users')->update(['role_id' => 999]);
        
        // 2) Mevcut rolleri temizle
        DB::table('roles')->truncate();
        
        // 3) Doğru ID'lerle rolleri ekle (admin=0, user=1)
        DB::table('roles')->insert([
            ['id' => 0, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 1, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // 4) Kullanıcı rollerini güncelle
        // Önce tüm kullanıcıları regular user yap
        DB::table('users')->update(['role_id' => 1]);
        
        // Admin e-postası olan kullanıcıları admin rolüne yükselt
        DB::table('users')
            ->where('email', 'like', '%admin%')
            ->update(['role_id' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Önceki duruma geri dönme adımları
        DB::table('users')->update(['role_id' => 999]); // Güvenli değer
        DB::table('roles')->truncate();
        
        // Önceki düzeni geri yükle (admin=1, user=2)
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Kullanıcı rollerini güncelle
        DB::table('users')->update(['role_id' => 2]); // Herkesi user yap
        DB::table('users')
            ->where('email', 'like', '%admin%')
            ->update(['role_id' => 1]); // Admin e-postalıları admin yap
    }
}; 