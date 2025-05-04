<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class FixRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Veritabanındaki rolleri düzeltir: admin(0) ve user(1) olarak ayarlar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Foreign key kontrolünü geçici olarak devre dışı bırak
            $this->info('Foreign key kontrolü devre dışı bırakılıyor...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Rolleri mevcut ID'lerini kontrol et
            $roles = DB::table('roles')->get();
            foreach ($roles as $role) {
                $this->info("Mevcut rol: ID={$role->id}, Name={$role->name}");
            }
            
            // Tabloyu tamamen sil ve yeniden oluştur
            $this->info('Roles tablosu yeniden oluşturuluyor...');
            Schema::dropIfExists('roles');
            
            Schema::create('roles', function ($table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('name');
                $table->string('description')->nullable();
                $table->timestamps();
            });
            
            // AUTO_INCREMENT sıfırla ve id değerini 0 olarak ayarlamak için SQL_MODE'u ayarla
            $this->info('AUTO_INCREMENT sıfırlanıyor...');
            DB::statement('ALTER TABLE roles AUTO_INCREMENT = 0');
            DB::statement('SET @@SESSION.sql_mode = ""');
            
            // Rolleri teker teker ekle
            $this->info('Admin rolü ekleniyor...');
            try {
                DB::table('roles')->insert([
                    'id' => 0, 
                    'name' => 'admin', 
                    'description' => 'Administrator', 
                    'created_at' => now(), 
                    'updated_at' => now()
                ]);
                $this->info('Admin rolü eklendi (ID=0)');
            } catch (\Exception $e) {
                $this->error('Admin rolü eklenirken hata: ' . $e->getMessage());
            }
            
            $this->info('User rolü ekleniyor...');
            try {
                DB::table('roles')->insert([
                    'id' => 1, 
                    'name' => 'user', 
                    'description' => 'User', 
                    'created_at' => now(), 
                    'updated_at' => now()
                ]);
                $this->info('User rolü eklendi (ID=1)');
            } catch (\Exception $e) {
                $this->error('User rolü eklenirken hata: ' . $e->getMessage());
            }
            
            // Admin olması gereken kullanıcıları admin rolüne ata
            $this->info('Admin kullanıcıları güncelleniyor...');
            $adminUpdated = DB::table('users')
                ->where('email', 'like', '%admin%')
                ->orWhere('name', 'like', '%admin%')
                ->update(['role_id' => 0]);
                
            $this->info($adminUpdated . ' admin kullanıcısı güncellendi (ID=0)');
            
            // Geri kalan kullanıcıları user rolüne ata
            $this->info('Kullanıcı rolleri güncelleniyor...');
            $userUpdated = DB::table('users')
                ->where('role_id', '!=', 0)
                ->update(['role_id' => 1]);
                
            $this->info($userUpdated . ' kullanıcı user rolüne atandı (ID=1)');
            
            // Rolleri kontrol et ve göster
            $this->info('Roller kontrol ediliyor...');
            $roles = DB::table('roles')->get();
            
            $this->table(
                ['ID', 'Name', 'Description'],
                $roles->map(function ($role) {
                    return [$role->id, $role->name, $role->description];
                })
            );
            
            // Foreign key kontrolünü tekrar etkinleştir
            $this->info('Foreign key kontrolü tekrar etkinleştiriliyor...');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->info('İşlem başarıyla tamamlandı.');
            return 0;
        } catch (\Exception $e) {
            // Hata durumunda kısıtlamaları geri aç
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            Log::error('Role düzenleme hatası: ' . $e->getMessage());
            $this->error('Rol düzenleme hatası: ' . $e->getMessage());
            
            return 1;
        }
    }
}
