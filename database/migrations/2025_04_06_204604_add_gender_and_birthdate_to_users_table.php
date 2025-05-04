<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Debugger ekleyelim
            Log::info('Migration çalıştırılıyor: add_gender_and_birthdate_to_users_table');
            
            // Sütunların var olup olmadığını kontrol edelim
            if (!Schema::hasColumn('users', 'gender')) {
                // Cinsiyet alanı - 'male', 'female', 'other' değerleri alabilir
                $table->string('gender')->nullable()->after('email');
                Log::info('gender sütunu eklendi');
            } else {
                Log::info('gender sütunu zaten var');
            }
            
            if (!Schema::hasColumn('users', 'birthdate')) {
                // Doğum tarihi alanı
                $table->date('birthdate')->nullable()->after('gender');
                Log::info('birthdate sütunu eklendi');
            } else {
                Log::info('birthdate sütunu zaten var');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('birthdate');
        });
    }
};
