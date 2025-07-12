<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role admin sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Cek apakah admin sudah ada
        $existingAdmin = User::where('email', 'admin@softui.com')->first();

        if (!$existingAdmin) {
            // Buat akun admin
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@softui.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]);

            $this->command->info('Admin account created successfully!');
            $this->command->info('Email: admin@softui.com');
            $this->command->info('Password: password');
        } else {
            $this->command->info('Admin account already exists!');
            $this->command->info('Email: admin@softui.com');
            $this->command->info('Password: password');
        }
    }
} 