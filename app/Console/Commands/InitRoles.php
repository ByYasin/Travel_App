<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class InitRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:init {--force : Zorla sıfırla ve yeniden oluştur}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rolleri varsayılan değerlere göre oluşturur veya günceller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $force = $this->option('force');
            
            if ($force) {
                // Foreign key constraint korumasını geçici olarak devre dışı bırakalım
                $this->info('Foreign key constraints devre dışı bırakılıyor...');
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                
                // Rolleri tamamen temizle
                $this->info('Tüm roller siliniyor...');
                DB::table('roles')->delete();
                
                // ID Auto increment değerini sıfırlayalım
                DB::statement('ALTER TABLE roles AUTO_INCREMENT = 0');
                
                // Şimdi admin (id=0) ve user (id=1) rollerini oluştur
                $this->info('Varsayılan rolleri ekliyorum...');
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
                
                // Foreign key constraint'i tekrar aktif edelim
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } else {
                // Normal mod - sadece eksik rolleri ekle, var olanları güncelle
                $this->info('Roller kontrol ediliyor...');
                
                // Admin rolü (id=0)
                $adminRole = DB::table('roles')->where('id', 0)->first();
                if (!$adminRole) {
                    $this->info('Admin rolü (id=0) ekleniyor...');
                    DB::table('roles')->insert([
                        'id' => 0,
                        'name' => 'admin',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $this->info('Admin rolü zaten var, güncelleniyor...');
                    DB::table('roles')->where('id', 0)->update([
                        'name' => 'admin',
                        'updated_at' => now()
                    ]);
                }
                
                // User rolü (id=1)
                $userRole = DB::table('roles')->where('id', 1)->first();
                if (!$userRole) {
                    $this->info('User rolü (id=1) ekleniyor...');
                    DB::table('roles')->insert([
                        'id' => 1,
                        'name' => 'user',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $this->info('User rolü zaten var, güncelleniyor...');
                    DB::table('roles')->where('id', 1)->update([
                        'name' => 'user',
                        'updated_at' => now()
                    ]);
                }
                
                // Diğer roller varsa sil (ID'si 0 veya 1 olmayan roller)
                $deleteCount = DB::table('roles')->whereNotIn('id', [0, 1])->delete();
                if ($deleteCount > 0) {
                    $this->info("{$deleteCount} fazla rol temizlendi.");
                }
            }
            
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
            return 1;
        }
    }
} 