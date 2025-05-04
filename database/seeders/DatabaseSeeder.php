<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            TourCategorySeeder::class, // Tur kategorilerini ekle
            TourSeeder::class, // Örnek turları ekle
            AdminSeeder::class, // Admin kullanıcılar
        ]);
        
        // Örnek bir kullanıcı oluştur (isteğe bağlı)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}