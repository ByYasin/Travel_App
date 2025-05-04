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
        Schema::table('users', function (Blueprint $table) {
            // Önce gender sütunu ekle
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('email');
            }
            
            // Sonra birthdate sütunu ekle
            if (!Schema::hasColumn('users', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Sütunları kaldır
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
            
            if (Schema::hasColumn('users', 'birthdate')) {
                $table->dropColumn('birthdate');
            }
        });
    }
};
