<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        // Önce tabloyu ve mevcut sütunları kontrol edelim
        if (Schema::hasTable('users')) {
            // Gender ve birthdate kolonları varsa tiplerini değiştirelim
            if (Schema::hasColumn('users', 'gender')) {
                DB::statement('ALTER TABLE users MODIFY gender VARCHAR(20) NULL');
            } 
            // Eğer gender kolonu yoksa ekleyelim
            else {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('gender', 20)->nullable()->after('email');
                });
            }
            
            // Birthdate kolonu varsa tipini değiştirelim
            if (Schema::hasColumn('users', 'birthdate')) {
                DB::statement('ALTER TABLE users MODIFY birthdate DATE NULL');
            } 
            // Eğer birthdate kolonu yoksa ekleyelim
            else {
                Schema::table('users', function (Blueprint $table) {
                    $table->date('birthdate')->nullable()->after('gender');
                });
            }
        }
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        // Rollback işlemi gerekmez
    }
}; 