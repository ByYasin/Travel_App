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
        // Bu migration artık gereksiz, çünkü role_id ve foreign key'i
        // zaten daha önceki migration'larda ayarladık
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration'da bir şey yapmadığımız için geri alma işlemi de yok
    }
};
