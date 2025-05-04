<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // 1. Roles tablosunun kontrolü
            if (!Schema::hasTable('roles')) {
                // Roles tablosu yoksa oluştur
                Schema::create('roles', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->timestamps();
                });
                
                // Rolleri ekle
                DB::table('roles')->insert([
                    ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 2, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
                ]);
                
                Log::info('Roles tablosu başarıyla oluşturuldu');
            } else {
                // Roles tablosu varsa, rollerin doğru ID'lerle tanımlandığından emin ol
                $adminExists = DB::table('roles')->where('id', 1)->where('name', 'admin')->exists();
                $userExists = DB::table('roles')->where('id', 2)->where('name', 'user')->exists();
                
                if (!$adminExists) {
                    DB::table('roles')->updateOrInsert(
                        ['id' => 1],
                        ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()]
                    );
                }
                
                if (!$userExists) {
                    DB::table('roles')->updateOrInsert(
                        ['id' => 2],
                        ['name' => 'user', 'created_at' => now(), 'updated_at' => now()]
                    );
                }
                
                Log::info('Roles tablosu kontrol edildi ve gerekli düzeltmeler yapıldı');
            }
            
            // 2. Users tablosunun role_id sütunu kontrolü
            if (Schema::hasTable('users')) {
                if (!Schema::hasColumn('users', 'role_id')) {
                    Schema::table('users', function (Blueprint $table) {
                        $table->unsignedBigInteger('role_id')->default(2)->after('email');
                        
                        // Roles tablosu varsa foreign key ekle
                        if (Schema::hasTable('roles')) {
                            $table->foreign('role_id')->references('id')->on('roles');
                        }
                    });
                    
                    Log::info('Users tablosuna role_id sütunu eklendi');
                } else {
                    // role_id sütunu zaten var, foreign key kontrolü yap
                    $foreignKeys = DB::select("
                        SELECT *
                        FROM information_schema.KEY_COLUMN_USAGE
                        WHERE REFERENCED_TABLE_NAME = 'roles'
                        AND TABLE_NAME = 'users'
                        AND COLUMN_NAME = 'role_id'
                    ");
                    
                    if (empty($foreignKeys)) {
                        // Foreign key ekle
                        try {
                            Schema::table('users', function (Blueprint $table) {
                                $table->foreign('role_id')->references('id')->on('roles');
                            });
                            Log::info('Users tablosuna role_id foreign key eklendi');
                        } catch (\Exception $e) {
                            Log::error('Foreign key eklenirken hata: ' . $e->getMessage());
                        }
                    }
                    
                    // Tüm kullanıcıların geçerli bir role_id değerine sahip olduğundan emin ol
                    DB::statement('UPDATE users SET role_id = 2 WHERE role_id IS NULL OR role_id NOT IN (SELECT id FROM roles)');
                    Log::info('Kullanıcı rolleri kontrol edildi ve gerekli düzeltmeler yapıldı');
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Migration hatası: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu dosya sadece düzeltmeler içerdiği için down metodunda
        // herhangi bir işlem yapmıyoruz, mevcut durumu koruyoruz.
    }
}; 