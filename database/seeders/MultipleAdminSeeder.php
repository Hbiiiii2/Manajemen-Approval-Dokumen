<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class MultipleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role admin sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Data admin yang akan dibuat
        $adminData = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@softui.com',
                'password' => 'password',
            ],
            [
                'name' => 'System Administrator',
                'email' => 'systemadmin@softui.com',
                'password' => 'password',
            ],
            [
                'name' => 'IT Administrator',
                'email' => 'itadmin@softui.com',
                'password' => 'password',
            ],
        ];

        $createdCount = 0;
        $existingCount = 0;

        foreach ($adminData as $admin) {
            // Cek apakah admin sudah ada
            $existingAdmin = User::where('email', $admin['email'])->first();

            if (!$existingAdmin) {
                // Buat akun admin
                User::create([
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                    'password' => Hash::make($admin['password']),
                    'role_id' => $adminRole->id,
                ]);

                $this->command->info("✅ Admin created: {$admin['email']}");
                $createdCount++;
            } else {
                $this->command->info("ℹ️  Admin exists: {$admin['email']}");
                $existingCount++;
            }
        }

        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info("✅ Created: {$createdCount} admin(s)");
        $this->command->info("ℹ️  Existing: {$existingCount} admin(s)");
        $this->command->info("🔑 Default password for all accounts: password");
    }
} 