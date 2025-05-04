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
        // Mevcut verileri saklayalım
        $users = DB::table('users')->select('id', 'role_id')->get();
        
        // Doğrudan SQL kullanarak Foreign Key constraint'i kaldıralım
        try {
            DB::statement('ALTER TABLE users DROP FOREIGN KEY users_role_id_foreign');
        } catch (\Exception $e) {
            // Foreign key constraint bulunamadıysa devam et
        }
        
        // role_id sütununu kaldıralım
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role_id');
            });
        }
        
        // role_id sütununu password'dan sonra ekleyelim (Foreign key olmadan)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('password')->default(1);
        });
        
        // Verileri geri yükleyelim
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'role_id' => $user->role_id
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mevcut verileri saklayalım
        $users = DB::table('users')->select('id', 'role_id')->get();
        
        // role_id sütununu kaldıralım
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
        
        // role_id sütununu eski yerine ekleyelim (orijinal konumda)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->default(1);
        });
        
        // Verileri geri yükleyelim
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'role_id' => $user->role_id
                ]);
        }
    }
};
