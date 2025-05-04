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
        // Eğer reservations tablosu yoksa işlem yapmayalım
        if (!Schema::hasTable('reservations')) {
            return;
        }
        
        // MySQL'de enum sütununu değiştirmek için ALTER TABLE kullanmalıyız
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eğer reservations tablosu yoksa işlem yapmayalım
        if (!Schema::hasTable('reservations')) {
            return;
        }
        
        // Eski enum değerlerine döndür
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'");
    }
};
