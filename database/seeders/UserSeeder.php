<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin kullanıcısı
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Standart kullanıcı
        User::create([
            'name' => 'Standard User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Bir başka standart kullanıcı
        User::create([
            'name' => 'Another User',
            'email' => 'anotheruser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}
