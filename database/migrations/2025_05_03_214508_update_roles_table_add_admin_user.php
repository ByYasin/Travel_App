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
        // Roles tablosunda ID 0 ve ID 1 kayıtları yoksa ekleyelim
        $adminExists = DB::table('roles')->where('id', 0)->exists();
        $userExists = DB::table('roles')->where('id', 1)->exists();

        // Önce roles tablosunun yapısını kontrol et
        $columns = Schema::getColumnListing('roles');
        $hasDescription = in_array('description', $columns);

        if (!$adminExists) {
            $data = [
                'id' => 0,
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // description sütunu varsa ekle
            if ($hasDescription) {
                $data['description'] = 'Administrator';
            }
            
            DB::table('roles')->insert($data);
        }

        if (!$userExists) {
            $data = [
                'id' => 1,
                'name' => 'user',
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // description sütunu varsa ekle
            if ($hasDescription) {
                $data['description'] = 'Regular User';
            }
            
            DB::table('roles')->insert($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migration'ı geri aldığımızda bu kayıtları silmiyoruz
        // Çünkü başka kayıtlarla ilişkileri olabilir
    }
};
