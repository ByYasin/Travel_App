<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Roles tablosunu oluştur - bu işlem artık 2024_04_03_000001_create_roles_table.php'de yapılıyor
        // Burada tekrar oluşturmuyoruz

        // Users tablosunu güncelle
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('gender');
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('birthdate');
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });

        // Tur kategorileri tablosunu oluştur
        if (!Schema::hasTable('tour_categories')) {
            Schema::create('tour_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('parent_id')->references('id')->on('tour_categories')->onDelete('set null');
            });
        }

        // Tour tablosunu güncelle
        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                if (!Schema::hasColumn('tours', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('id');
                    $table->foreign('category_id')->references('id')->on('tour_categories')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('tours', 'slug')) {
                    $table->string('slug')->unique()->after('title');
                }
                
                if (Schema::hasColumn('tours', 'featured_image')) {
                    // change metodu kullanımı dikkatlice yapılmalı
                    // mevcut verileri koruyabilmek için nullable olarak ayarlayalım
                    try {
                        $table->string('featured_image')->nullable()->change();
                    } catch (\Exception $e) {
                        // Hata olursa atlayalım, kolon zaten nullable olabilir
                    }
                }
                
                if (!Schema::hasColumn('tours', 'gallery')) {
                    $table->json('gallery')->nullable()->after('featured_image');
                }
                
                if (!Schema::hasColumn('tours', 'included')) {
                    $table->json('included')->nullable()->after('gallery');
                }
                
                if (!Schema::hasColumn('tours', 'not_included')) {
                    $table->json('not_included')->nullable()->after('included');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tablo varsa silme işlemlerini gerçekleştir
        if (Schema::hasTable('tours')) {
            Schema::table('tours', function (Blueprint $table) {
                if (Schema::hasColumn('tours', 'category_id')) {
                    $table->dropForeign(['category_id']);
                    $table->dropColumn('category_id');
                }
                
                $columnsToCheck = ['slug', 'gallery', 'included', 'not_included'];
                foreach ($columnsToCheck as $column) {
                    if (Schema::hasColumn('tours', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('tour_categories');
        
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columnsToCheck = ['gender', 'birthdate', 'phone', 'address'];
                foreach ($columnsToCheck as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}; 