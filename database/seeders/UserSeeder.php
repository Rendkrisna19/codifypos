<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user dengan data dummy
        \App\Models\User::factory()->create([
            'name' => 'admin codifyhub',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'superadmin',
        ]);
    }
}
