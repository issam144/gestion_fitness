<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
    \App\Models\User::create([
        'name' => 'Admin Pro',
        'email' => 'admin@fitness.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);
}
    
}
