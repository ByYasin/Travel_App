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
        // Önce kolonların mevcut durumunu kontrol et
        $hasGender = Schema::hasColumn('users', 'gender');
        $hasBirthdate = Schema::hasColumn('users', 'birthdate');
        
        Schema::table('users', function (Blueprint $table) use ($hasGender, $hasBirthdate) {
            // Eğer kolonlar varsa, önce kaldır (tam olarak yeniden oluşturmak için)
            if ($hasGender) {
                $table->dropColumn('gender');
            }
            
            if ($hasBirthdate) {
                $table->dropColumn('birthdate');
            }
        });
        
        // Kısa bir gecikme ekle
        sleep(1);
        
        // Kolonları doğru şekilde ekle
        Schema::table('users', function (Blueprint $table) {
            // Kolonları doğru veri tipleriyle ekle
            $table->string('gender')->nullable()->after('email');
            $table->date('birthdate')->nullable()->after('gender');
        });
        
        // Tablo yapısını logla
        $columns = DB::select('SHOW COLUMNS FROM users');
        $columnNames = array_map(function($column) {
            return "{$column->Field} ({$column->Type})";
        }, $columns);
        
        DB::listen(function($query) {
            \Illuminate\Support\Facades\Log::info("[SQL] {$query->sql}", ['bindings' => $query->bindings, 'time' => $query->time]);
        });
        
        // Bilgilendirme mesajı
        \Illuminate\Support\Facades\Log::info('Users tablosu gender ve birthdate kolonları başarıyla düzeltildi', [
            'columns' => $columnNames
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert işlemi yok
    }
};
