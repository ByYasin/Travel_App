<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Migration tablosunu kontrol et
            if (Schema::hasTable('migrations')) {
                // Devre dışı bırakılacak migration listeleri
                $duplicatedMigrations = [
                    // Tekrar eden roles migrationları
                    '2024_04_27_000000_create_roles_and_add_role_id',
                    '2025_04_06_000001_create_user_admin_roles',
                    '2024_04_30_000000_create_only_roles_table',
                    '2024_05_01_000000_recreate_roles_table',
                    
                    // Tekrar eden tour_categories migrationları
                    '2024_04_10_000000_create_tour_categories_table',
                    '2024_04_10_000000_create_tour_categories_and_update_users',
                    '2024_04_20_000000_add_tour_categories',
                    
                    // Tekrar eden reservations migrationları
                    '2025_05_04_123046_create_reservations_table',
                    '2025_05_04_122852_create_reservations_table',
                    
                    // Personal access tokens tekrarları
                    '2024_04_29_000000_create_personal_access_tokens_table',
                    '2025_04_06_145337_create_personal_access_tokens_table',
                    '2025_04_03_205237_create_personal_access_tokens_table',
                    '2025_04_13_151309_create_personal_access_tokens_table',
                    
                    // Featured image tekrar eden migrationlar
                    '2025_04_28_191217_modify_featured_image_to_longtext_in_tours_table',
                    '2025_04_28_191133_modify_featured_image_to_longtext_in_tours_table',
                    '2025_04_28_191046_modify_featured_image_to_longtext_in_tours_table',
                    '2025_04_28_190657_modify_featured_image_to_longtext_in_tours_table',
                    
                    // Diğer çakışan migrationlar
                    '2025_05_03_200000_update_role_ids',
                    '2025_05_03_210000_fix_role_ids_with_direct_sql',
                ];
                
                // Bu migrationları devre dışı bırak (migration tablosundan sil)
                foreach ($duplicatedMigrations as $migration) {
                    $exists = DB::table('migrations')->where('migration', $migration)->exists();
                    
                    if ($exists) {
                        DB::table('migrations')->where('migration', $migration)->delete();
                        Log::info("Migration başarıyla devre dışı bırakıldı: {$migration}");
                    }
                }
                
                Log::info('Çakışan migration dosyaları devre dışı bırakıldı');
            }
        } catch (\Exception $e) {
            Log::error('Migration temizleme işlemi sırasında hata: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration temizleme amaçlı olduğu için down metodunda bir işlem yapmıyoruz
    }
}; 