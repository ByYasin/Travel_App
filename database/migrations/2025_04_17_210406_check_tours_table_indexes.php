<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('tours', function (Blueprint $table) {
                // Indexleri kontrol et ve ekle
                $this->addIndexIfNotExists('tours', 'slug');
                $this->addIndexIfNotExists('tours', 'category_id');
                $this->addIndexIfNotExists('tours', 'status');
            });
        } catch (\Exception $e) {
            Log::error('Migration hatası: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('tours', function (Blueprint $table) {
                // İndexleri kaldır
                $this->dropIndexIfExists('tours', 'slug');
                $this->dropIndexIfExists('tours', 'category_id');
                $this->dropIndexIfExists('tours', 'status');
            });
            Log::info('Removed indexes from tours table');
        } catch (\Exception $e) {
            Log::error('Migration down hatası: ' . $e->getMessage());
        }
    }

    /**
     * İndeks varsa kontrol et ve ekle
     */
    private function addIndexIfNotExists($table, $column)
    {
        try {
            // İndex adını oluştur
            $indexName = $table . '_' . $column . '_index';
            
            // İndeks var mı kontrol et
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Column_name = '{$column}'");
            
            if (empty($indexes)) {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->index($column);
                });
                Log::info("Added index to {$column} column in {$table} table");
            } else {
                Log::info("Index already exists for {$column} column in {$table} table");
            }
        } catch (\Exception $e) {
            Log::error("Error adding index on {$column}: " . $e->getMessage());
        }
    }
    
    /**
     * İndeks varsa kaldır
     */
    private function dropIndexIfExists($table, $column)
    {
        try {
            // İndex adını oluştur
            $indexName = $table . '_' . $column . '_index';
            
            // İndeks var mı kontrol et
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Column_name = '{$column}'");
            
            if (!empty($indexes)) {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->dropIndex([$column]);
                });
                Log::info("Dropped index from {$column} column in {$table} table");
            }
        } catch (\Exception $e) {
            Log::error("Error dropping index on {$column}: " . $e->getMessage());
        }
    }
};
