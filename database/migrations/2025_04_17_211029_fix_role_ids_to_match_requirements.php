<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Foreign key kontrolünü geçici olarak devre dışı bırak
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // ID değerlerini manuel belirleyebilmek için AUTO_INCREMENT'i kapatıyoruz
            DB::statement('SET @@SESSION.sql_mode = ""');
            
            // Roles tablosunu temizle
            DB::table('roles')->delete();
            
            // Direkt SQL komutu ile ID'leri 0 ve 1 olarak ekle
            DB::statement("INSERT INTO roles (id, name, description, created_at, updated_at) VALUES 
                (0, 'admin', 'Administrator', NOW(), NOW()),
                (1, 'user', 'User', NOW(), NOW())
            ");
            
            // Bu değişiklikle ilgili bilgi logla
            Log::info('Roller doğrudan SQL ile düzenlendi: Admin (ID=0), User (ID=1)');
            
            // Tüm kullanıcıları düzelt
            // Admin olması gerekenleri admin yap (Adında "admin" kelimesi olanlar)
            $adminUpdated = DB::table('users')
                ->where('email', 'like', '%admin%')
                ->orWhere('name', 'like', '%admin%')
                ->update(['role_id' => 0]);
                
            Log::info($adminUpdated . ' admin kullanıcısı güncellendi (ID=0)');
            
            // Geri kalan kullanıcıları user yap
            $userUpdated = DB::table('users')
                ->where('role_id', '!=', 0)
                ->update(['role_id' => 1]);
                
            Log::info($userUpdated . ' kullanıcı user rolüne atandı (ID=1)');
            
            // Foreign key kontrolünü tekrar etkinleştir
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Exception $e) {
            // Hata durumunda foreign key kontrolünü geri aç
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            Log::error('Role ID düzenlemesi hatası: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri dönüş işlemi yok, çünkü otomatik artan ID sistemi
        // ile çakışmayı önlemek için bu değişiklik kalıcı olmalı
        Log::info('Role ID düzenlemesi geri alınamaz.');
    }
};
