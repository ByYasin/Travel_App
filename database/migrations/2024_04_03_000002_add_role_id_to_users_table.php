<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Eğer users tablosunda role_id sütunu yoksa ekle
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id')->default(1); // Default olarak user (1)
                $table->foreign('role_id')->references('id')->on('roles');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Foreign key'in var olup olmadığını kontrol et
            $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('users');
            $foreignKeyExists = false;
            
            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getName() === 'users_role_id_foreign') {
                    $foreignKeyExists = true;
                    break;
                }
            }
            
            if ($foreignKeyExists) {
                $table->dropForeign(['role_id']);
            }
            
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropColumn('role_id');
            }
        });
    }
}; 