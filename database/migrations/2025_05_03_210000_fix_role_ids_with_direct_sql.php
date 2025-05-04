<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Önce kullanıcıların rollerini güncelle (foreign key hatası almamak için)
        DB::statement('UPDATE users SET role_id = 999 WHERE role_id IN (1, 2)');
        
        // Roles tablosunu drop et ve yeniden oluştur
        Schema::dropIfExists('roles');
        
        // Yeni roles tablosu oluştur (id INT olarak tanımla, auto_increment olmayacak)
        DB::statement('
            CREATE TABLE roles (
                id INT UNSIGNED NOT NULL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            )
        ');
        
        // Yeni rolleri ekle
        DB::statement("
            INSERT INTO roles (id, name, created_at, updated_at) VALUES
            (0, 'admin', NOW(), NOW()),
            (1, 'user', NOW(), NOW())
        ");
        
        // Kullanıcı rollerini güncelle
        DB::statement('UPDATE users SET role_id = 1'); // Önce herkesi user yap
        DB::statement("UPDATE users SET role_id = 0 WHERE email LIKE '%admin%'"); // Adminleri güncelle
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri almak için yapılacak işlemler (isteğe bağlı)
        DB::statement('UPDATE users SET role_id = 999'); // Güvenli değer
        
        Schema::dropIfExists('roles');
        
        // Eski yapıya geri dönüş
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Auto increment id
            $table->string('name');
            $table->timestamps();
        });
        
        // Eski rolleri ekle
        DB::statement("
            INSERT INTO roles (id, name, created_at, updated_at) VALUES
            (1, 'admin', NOW(), NOW()),
            (2, 'user', NOW(), NOW())
        ");
        
        // Kullanıcı rollerini güncelle
        DB::statement('UPDATE users SET role_id = 2'); // Önce herkesi user yap
        DB::statement("UPDATE users SET role_id = 1 WHERE email LIKE '%admin%'"); // Adminleri güncelle
    }
}; 