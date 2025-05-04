<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin kullanıcı zaten varsa oluşturmuyoruz
        $existingAdmin = User::where('email', 'admin@example.com')->first();
        
        if (!$existingAdmin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role_id' => Role::ADMIN // Admin rolü (ID=0)
            ]);
            
            $this->command->info('Admin kullanıcısı oluşturuldu: ' . $admin->email);
        } else {
            $this->command->info('Admin kullanıcısı zaten mevcut: ' . $existingAdmin->email);
        }
    }
} 