<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bu migration artık gereksiz, çünkü uygun ID'lere sahip roller
        // 2024_04_03_000001_create_roles_table migration'ında oluşturuluyor
        // Bu nedenle burada bir şey yapmıyoruz
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'da bir şey yapmadığımız için, geri alma işlemi de yok
    }
}; 