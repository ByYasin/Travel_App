<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eğer reservations tablosu yoksa, bu migration'ı çalıştırmayalım
        if (!Schema::hasTable('reservations')) {
            return;
        }
        
        Schema::table('reservations', function (Blueprint $table) {
            // participants sütunu hali hazırda varsa, participant_count sütununu ekle
            if (!Schema::hasColumn('reservations', 'participant_count')) {
                $table->integer('participant_count')->default(1)->after('reservation_date');
            }
            
            // Eğer participants sütunu varsa, değerleri participant_count'a taşı ve participants sütununu kaldır
            if (Schema::hasColumn('reservations', 'participants')) {
                // Şemaları doğrudan değiştiremeyiz, bunu raw SQL ile yapmalıyız
                \DB::statement('UPDATE reservations SET participant_count = participants');
                $table->dropColumn('participants');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eğer reservations tablosu yoksa, bu migration'ı geri almayalım
        if (!Schema::hasTable('reservations')) {
            return;
        }
        
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'participant_count')) {
                $table->dropColumn('participant_count');
            }
        });
    }
}; 