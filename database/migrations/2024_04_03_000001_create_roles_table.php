<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Varsayılan rolleri ekle (ADMIN=0, USER=1 olarak ayarla)
        DB::table('roles')->insert([
            ['id' => 0, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 1, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}; 