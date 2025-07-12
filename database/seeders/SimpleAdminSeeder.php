<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SimpleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role admin sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Data admin
        $adminData = [
            'name' => 'Administrator',
            'email' => 'admin@softui.com',
            'password' => 'password',
        ];

        // Cek apakah admin sudah ada
        $existingAdmin = User::where('email', $adminData['email'])->first();

        if (!$existingAdmin) {
            // Buat akun admin
            User::create([
                'name' => $adminData['name'],
                'email' => $adminData['email'],
                'password' => Hash::make($adminData['password']),
                'role_id' => $adminRole->id,
            ]);

            $this->command->info('✅ Admin account created successfully!');
            $this->command->info("📧 Email: {$adminData['email']}");
            $this->command->info("🔑 Password: {$adminData['password']}");
        } else {
            $this->command->info('ℹ️  Admin account already exists!');
            $this->command->info("📧 Email: {$adminData['email']}");
            $this->command->info("🔑 Password: {$adminData['password']}");
        }
    }
} 