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
        // Önce roles tablosunun var olduğundan emin olalım
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });

            // Temel rolleri ekle
            DB::table('roles')->insert([
                ['id' => 0, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 1, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Şimdi users tablosuna role_id sütununu ekleyelim
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->default(1)->after('email');
                $table->foreign('role_id')->references('id')->on('roles');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
        });
    }
}; 