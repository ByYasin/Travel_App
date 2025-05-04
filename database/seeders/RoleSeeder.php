<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Foreign key kontrolünü geçici olarak devre dışı bırak
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Roles tablosunu tamamen temizle
            DB::table('roles')->delete();
            
            // AUTO_INCREMENT sıfırla ve id değerini 0 olarak ayarlamak için SQL_MODE'u ayarla
            DB::statement('ALTER TABLE roles AUTO_INCREMENT = 0');
            DB::statement('SET @@SESSION.sql_mode = ""');
            
            // Roles tablosunda description sütununun varlığını kontrol et
            $hasDescriptionColumn = Schema::hasColumn('roles', 'description');
            
            // Description sütunu varsa
            if ($hasDescriptionColumn) {
                DB::statement("
                    INSERT INTO roles (id, name, description, created_at, updated_at) VALUES 
                    (0, 'admin', 'Administrator', NOW(), NOW()),
                    (1, 'user', 'User', NOW(), NOW())
                ");
            } else {
                // Description sütunu yoksa
                DB::statement("
                    INSERT INTO roles (id, name, created_at, updated_at) VALUES
                    (0, 'admin', NOW(), NOW()),
                    (1, 'user', NOW(), NOW())
                ");
            }
            
            // Foreign key kontrolünü tekrar etkinleştir
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->command->info('Roller başarıyla eklendi: admin(0), user(1)');
        } catch (\Exception $e) {
            // Hata durumunda kısıtlamaları geri aç
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            Log::error('Role Seeder hatası: ' . $e->getMessage());
            $this->command->error('Rol ekleme hatası: ' . $e->getMessage());
            
            // Ek olarak, basit bir insert denemesi yap
            try {
                // Roller henüz eklenmemişse ekle
                DB::table('roles')->updateOrInsert(
                    ['id' => 0],
                    ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()]
                );
                
                DB::table('roles')->updateOrInsert(
                    ['id' => 1],
                    ['name' => 'user', 'created_at' => now(), 'updated_at' => now()]
                );
                
                echo "Rol ekleme başarılı (updateOrInsert ile).\n";
            } catch (\Exception $innerE) {
                echo "İkinci rol ekleme denemesi başarısız: " . $innerE->getMessage() . "\n";
            }
        }
    }
}
