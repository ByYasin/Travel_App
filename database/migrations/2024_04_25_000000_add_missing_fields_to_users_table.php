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
            // Gender alanı ekle (zaten yoksa)
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('email');
            }
            
            // Birthdate alanı ekle (zaten yoksa)
            if (!Schema::hasColumn('users', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('gender');
            }
            
            // Phone alanı ekle (zaten yoksa)
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('birthdate');
            }
            
            // Address alanı ekle (zaten yoksa)
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eklenen alanları kaldır
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
            
            if (Schema::hasColumn('users', 'birthdate')) {
                $table->dropColumn('birthdate');
            }
            
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
}; 