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
        // Roles tablosunu yeniden oluşturmak yerine, var olan tabloya ek roller ekleyelim
        // Önce bu rollerin var olup olmadığını kontrol edelim
        $managerRoleExists = DB::table('roles')->where('id', 3)->exists();
        $editorRoleExists = DB::table('roles')->where('id', 4)->exists();
        
        if (!$managerRoleExists) {
            DB::table('roles')->insert([
                'id' => 3, 
                'name' => 'manager', 
                'created_at' => now(), 
                'updated_at' => now()
            ]);
        }
        
        if (!$editorRoleExists) {
            DB::table('roles')->insert([
                'id' => 4, 
                'name' => 'editor', 
                'created_at' => now(), 
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sadece eklediğimiz rolleri silelim
        DB::table('roles')->whereIn('id', [3, 4])->delete();
    }
}; 