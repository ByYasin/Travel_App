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
            // Reservations tablosunun varlığını kontrol et
            if (!Schema::hasTable('reservations')) {
                // Tablo yoksa oluştur
                Schema::create('reservations', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->foreignId('tour_id')->constrained()->onDelete('cascade');
                    $table->date('reservation_date');
                    $table->integer('participant_count')->default(1);
                    $table->decimal('total_price', 10, 2);
                    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
                    $table->string('payment_method')->nullable();
                    $table->string('payment_id')->nullable();
                    $table->text('special_requests')->nullable();
                    $table->timestamps();
                    
                    // İndeksler
                    $table->index('user_id');
                    $table->index('tour_id');
                    $table->index('status');
                    $table->index('reservation_date');
                });
                
                Log::info('Reservations tablosu başarıyla oluşturuldu');
            } else {
                // Tablo varsa gerekli sütunların varlığını kontrol et
                $requiredColumns = [
                    'user_id', 'tour_id', 'reservation_date', 'participant_count', 
                    'total_price', 'status', 'payment_method', 'payment_id', 'special_requests'
                ];
                
                foreach ($requiredColumns as $column) {
                    if (!Schema::hasColumn('reservations', $column)) {
                        Schema::table('reservations', function (Blueprint $table) use ($column) {
                            switch ($column) {
                                case 'user_id':
                                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                                    break;
                                case 'tour_id':
                                    $table->foreignId('tour_id')->constrained()->onDelete('cascade');
                                    break;
                                case 'reservation_date':
                                    $table->date('reservation_date');
                                    break;
                                case 'participant_count':
                                    $table->integer('participant_count')->default(1);
                                    break;
                                case 'total_price':
                                    $table->decimal('total_price', 10, 2);
                                    break;
                                case 'status':
                                    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
                                    break;
                                case 'payment_method':
                                    $table->string('payment_method')->nullable();
                                    break;
                                case 'payment_id':
                                    $table->string('payment_id')->nullable();
                                    break;
                                case 'special_requests':
                                    $table->text('special_requests')->nullable();
                                    break;
                            }
                        });
                        
                        Log::info("Reservations tablosuna '{$column}' sütunu eklendi");
                    }
                }
                
                // Status sütununun enum değerlerini kontrol et
                try {
                    $statusValues = DB::select("SHOW COLUMNS FROM reservations WHERE Field = 'status'");
                    if (isset($statusValues[0]->Type)) {
                        $currentType = $statusValues[0]->Type;
                        // Eğer mevcut durum değerleri güncel değilse güncelle
                        if (strpos($currentType, 'confirmed') === false || 
                            strpos($currentType, 'completed') === false || 
                            strpos($currentType, 'cancelled') === false) {
                            
                            DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending'");
                            Log::info("Reservations tablosundaki status sütunu enum değerleri güncellendi");
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Status sütunu kontrol edilirken hata: " . $e->getMessage());
                }
                
                // İndeksleri kontrol et ve eksik olanları ekle
                $indexes = ['user_id', 'tour_id', 'status', 'reservation_date'];
                foreach ($indexes as $index) {
                    // İndeks var mı kontrol et
                    $indexExists = DB::select("
                        SHOW INDEX FROM reservations 
                        WHERE Column_name = '{$index}'
                    ");
                    
                    if (empty($indexExists)) {
                        DB::statement("CREATE INDEX idx_{$index} ON reservations({$index})");
                        Log::info("Reservations tablosuna '{$index}' için indeks eklendi");
                    }
                }
                
                Log::info('Reservations tablosu kontrol edildi ve güncellendi');
            }
        } catch (\Exception $e) {
            Log::error('Reservations tablosu oluşturulurken veya güncellenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration düzeltme amaçlı olduğu için down metodu yoktur
    }
}; 