<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Test users untuk setiap role
        $testUsers = [
            // Admin Users
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@test.com',
                'password' => Hash::make('password'),
                'role_id' => 4, // admin
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'System Admin',
                'email' => 'systemadmin@test.com',
                'password' => Hash::make('password'),
                'role_id' => 4, // admin
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Staff Users
            [
                'name' => 'John Staff',
                'email' => 'john.staff@test.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // staff
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sarah Staff',
                'email' => 'sarah.staff@test.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // staff
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mike Staff',
                'email' => 'mike.staff@test.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // staff
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Manager Users
            [
                'name' => 'David Manager',
                'email' => 'david.manager@test.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // manager
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lisa Manager',
                'email' => 'lisa.manager@test.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // manager
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Section Head Users
            [
                'name' => 'Robert Section Head',
                'email' => 'robert.sectionhead@test.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // section_head
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Emma Section Head',
                'email' => 'emma.sectionhead@test.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // section_head
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('users')->insert($testUsers);
        
        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: superadmin@test.com / systemadmin@test.com');
        $this->command->info('Staff: john.staff@test.com / sarah.staff@test.com / mike.staff@test.com');
        $this->command->info('Manager: david.manager@test.com / lisa.manager@test.com');
        $this->command->info('Section Head: robert.sectionhead@test.com / emma.sectionhead@test.com');
        $this->command->info('Password for all users: password');
    }
} 