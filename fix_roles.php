<?php
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Veritabanı migration işlemleri başlatılıyor..." . PHP_EOL;

echo "Configuration önbelleği temizleniyor..." . PHP_EOL;
system('php artisan config:clear');

echo "View önbelleği temizleniyor..." . PHP_EOL;
system('php artisan view:clear');

echo "Route önbelleği temizleniyor..." . PHP_EOL;
system('php artisan route:clear');

echo "Migration'lar sıfırdan çalıştırılıyor..." . PHP_EOL;
system('php artisan migrate:fresh');

echo "İşlem tamamlandı!" . PHP_EOL;

try {
    echo "Roles tablosunu kontrol ediyorum...\n";
    
    if (!Schema::hasTable('roles')) {
        echo "roles tablosu bulunamadı. Önce migration'ları çalıştırın.\n";
        exit(1);
    }
    
    $hasDescriptionColumn = Schema::hasColumn('roles', 'description');
    
    if ($hasDescriptionColumn) {
        DB::table('roles')->updateOrInsert(
            ['id' => 0],
            [
                'name' => 'admin',
                'description' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'user',
                'description' => 'User',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    } else {
        DB::table('roles')->updateOrInsert(
            ['id' => 0],
            [
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'user',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
    
    echo "Roller başarıyla eklendi veya güncellendi!\n";
    
    $roles = DB::table('roles')->get();
    
    echo "Roller tablosu içeriği:\n";
    foreach ($roles as $role) {
        echo "ID: {$role->id}, Name: {$role->name}\n";
    }
    
} catch (\Exception $e) {
    echo "Hata oluştu: " . $e->getMessage() . "\n";
    exit(1);
}