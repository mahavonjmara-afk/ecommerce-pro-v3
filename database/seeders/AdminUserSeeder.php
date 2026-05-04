<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Vérifier si l'admin existe déjà pour éviter doublon
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            User::create([
                'name' => 'Administrateur',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'),
                'role' => 'admin',
            ]);
            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already exists, skipping.');
        }
    }
}