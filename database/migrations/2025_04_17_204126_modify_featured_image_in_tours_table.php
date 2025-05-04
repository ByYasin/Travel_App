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
        Schema::table('tours', function (Blueprint $table) {
                if (Schema::hasColumn('tours', 'featured_image')) {
                    $table->text('featured_image')->nullable()->change();
                }
        });
            Log::info('The featured_image column in tours table has been modified to text and nullable.');
        } catch (\Exception $e) {
            Log::error('Error modifying featured_image column: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to reverse this change
    }
};
