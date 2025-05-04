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
        // Roles tablosunu yeniden oluşturmak yerine, var olan rolleri güncelleyelim
        // Admin ve user rolleri zaten var olmalı, sadece isimlerini kontrol edelim
        
        // Admin rolünü güncelle (id = 1)
        DB::table('roles')
            ->where('id', 1)
            ->update(['name' => 'admin']);
        
        // User rolünü güncelle (id = 2)
        DB::table('roles')
            ->where('id', 2)
            ->update(['name' => 'user']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'da hiçbir şey yapmıyoruz
    }
}; 