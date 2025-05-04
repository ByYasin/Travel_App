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
        $users = DB::table('users')->select('id', 'phone', 'address')->get();
        
        // Sütunları kaldıralım
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
        });
        
        // Sütunları doğru sırada yeniden ekleyelim
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('birthdate');
            $table->text('address')->nullable()->after('phone');
        });
        
        // Verileri geri yükleyelim
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'phone' => $user->phone,
                    'address' => $user->address
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mevcut verileri saklayalım
        $users = DB::table('users')->select('id', 'phone', 'address')->get();
        
        // Sütunları kaldıralım
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address']);
        });
        
        // Sütunları tekrar ekleyelim (orijinal konuma)
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
        });
        
        // Verileri geri yükleyelim
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'phone' => $user->phone,
                    'address' => $user->address
                ]);
        }
    }
};
