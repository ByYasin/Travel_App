<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitRolesAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Admin (id=0) ve user (id=1) rollerini düzenler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Foreign key constraints devre dışı bırak
            $this->info('Foreign key constraints devre dışı bırakılıyor...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // İlk olarak tüm tabloyu temizle
            $this->info('Roles tablosu temizleniyor...');
            DB::table('roles')->delete();
            
            // Auto increment değerini sıfırla
            DB::statement('ALTER TABLE roles AUTO_INCREMENT = 0');
            
            // Admin ve user rollerini doğrudan ekle
            $this->info('Admin ve user rolleri ekleniyor...');
            DB::table('roles')->insert([
                [
                    'id' => 0,
                    'name' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => 1,
                    'name' => 'user',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
            
            // Foreign key constraints tekrar aktif et
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            // Sonuçları göster
            $this->info('İşlem tamamlandı. Roller:');
            $roles = DB::table('roles')->orderBy('id')->get();
            
            $headers = ['ID', 'Name'];
            $data = [];
            
            foreach ($roles as $role) {
                $data[] = [$role->id, $role->name];
            }
            
            $this->table($headers, $data);
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Hata oluştu: ' . $e->getMessage());
            
            // Foreign key checks'i tekrar aktifleştirmeyi dene
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $e) {
                $this->error('Foreign key constraints tekrar aktifleştirilemedi.');
            }
            
            return 1;
        }
    }
} 