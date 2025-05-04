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
        // Roles tablosunu temizleyelim
        DB::table('roles')->delete();
        
        // ID sütununu unsignedInteger olarak değiştirelim ve auto_increment'i kaldıralım
        // Bu işlem için tabloyu yeniden oluşturmak gerekiyor
        Schema::dropIfExists('roles');
        
        Schema::create('roles', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name');
            $table->timestamps();
        });
        
        // Admin ve User rollerini ekleyelim
        DB::table('roles')->insert([
            ['id' => 0, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 1, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Roles tablosunu eski haline getirelim
        Schema::dropIfExists('roles');
        
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // BIGINT unsigned auto_increment
            $table->string('name');
            $table->timestamps();
        });
        
        // Rolleri tekrar ekleyelim
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
};
