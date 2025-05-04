<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Foreign key constraint'i devre dışı bırak
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Önce id=0 için yer açmak üzere AUTO_INCREMENT değerini değiştir
        DB::statement('INSERT INTO roles (id, name, created_at, updated_at) VALUES (0, \'admin\', NOW(), NOW()) ON DUPLICATE KEY UPDATE name=\'admin\', updated_at=NOW()');
        
        // Foreign key constraint'i tekrar aktif et
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Sonuçları göster
        $this->command->info('Admin rolü (id=0) eklendi veya güncellendi.');
        $roles = DB::table('roles')->orderBy('id')->get();
        foreach ($roles as $role) {
            $this->command->info("ID: {$role->id}, Name: {$role->name}");
        }
    }
} 