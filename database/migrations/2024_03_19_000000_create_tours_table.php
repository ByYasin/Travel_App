<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            if (!Schema::hasTable('tours')) {
                Schema::create('tours', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->string('slug')->nullable();
                    $table->text('description')->nullable();
                    $table->decimal('price', 10, 2)->default(0);
                    $table->string('featured_image')->nullable();
                    $table->json('gallery')->nullable();
                    $table->string('duration')->nullable();
                    $table->string('location')->nullable();
                    $table->string('category')->nullable();
                    $table->integer('max_participants')->nullable();
                    $table->json('includes')->nullable();
                    $table->json('excludes')->nullable();
                    $table->json('itinerary')->nullable();
                    $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
                    $table->timestamps();
                });
                Log::info('Tours table created successfully');
            } else {
                Log::info('Tours table already exists');
            }
        } catch (\Exception $e) {
            Log::error('Error creating tours table: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::dropIfExists('tours');
            Log::info('Tours table dropped successfully');
        } catch (\Exception $e) {
            Log::error('Error dropping tours table: ' . $e->getMessage());
        }
    }
}; 