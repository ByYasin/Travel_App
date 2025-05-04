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
        // Mevcut foreign key kısıtlamasını kaldır (eğer varsa)
        $this->dropForeignKeyIfExists('users', 'users_role_id_foreign');
        
        // role_id sütununun tipini kontrol et ve INT UNSIGNED olarak değiştir
        if (Schema::hasColumn('users', 'role_id')) {
            // Sütun tipini değiştir
            DB::statement('ALTER TABLE users MODIFY role_id INT UNSIGNED DEFAULT 1');
        }
        
        // Foreign key oluştur
        Schema::table('users', function (Blueprint $table) {
            // Eğer sütun yoksa ekle
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedInteger('role_id')->default(1)->after('password');
            }
            
            // Foreign key oluştur
            $table->foreign('role_id')
                  ->references('id')->on('roles')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Foreign key kısıtlamasını kaldır
        $this->dropForeignKeyIfExists('users', 'users_role_id_foreign');
    }
    
    /**
     * Varsa foreign key kısıtlamasını kaldır
     */
    private function dropForeignKeyIfExists($table, $foreignKey)
    {
        // Foreign key varsa kaldır
        $foreignKeys = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'", 
            [$table]
        );
        
        // Tüm foreign key'leri döngüde kontrol et
        foreach ($foreignKeys as $key) {
            if ($key->CONSTRAINT_NAME === $foreignKey) {
                Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                    $table->dropForeign($foreignKey);
                });
                break;
            }
        }
    }
}; 