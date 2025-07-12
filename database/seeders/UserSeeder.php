<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Models\DivisionRole;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $roles = Role::all();
        $divisions = Division::all();
        
        // Get role IDs
        $staffRole = $roles->where('name', 'staff')->first();
        $sectionHeadRole = $roles->where('name', 'section_head')->first();
        $deptHeadRole = $roles->where('name', 'depthead')->first();
        $adminRole = $roles->where('name', 'admin')->first();
        
        if (!$staffRole || !$sectionHeadRole || !$deptHeadRole || !$adminRole) {
            echo "Error: Required roles not found!\n";
            return;
        }
        
        // Create admin users (multi-divisi)
        $adminUsers = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@test.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'division_id' => $divisions->first()->id,
            ],
            [
                'name' => 'System Admin',
                'email' => 'systemadmin@test.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'division_id' => $divisions->first()->id,
            ]
        ];
        
        foreach ($adminUsers as $adminData) {
            $admin = User::create($adminData);
            
            // Admin bisa akses semua divisi
            foreach ($divisions as $division) {
                DivisionRole::create([
                    'user_id' => $admin->id,
                    'division_id' => $division->id,
                    'role_id' => $adminRole->id,
                    'is_primary' => $division->id === $admin->division_id,
                ]);
            }
        }
        
        echo "Created " . count($adminUsers) . " admin users\n";
        
        // Create users for each division
        foreach ($divisions as $division) {
            echo "Creating users for division: {$division->name}\n";
            
            // 1 Dept Head per division
            $deptHead = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role_id' => $deptHeadRole->id,
                'division_id' => $division->id,
            ]);
            
            // Dept Head bisa akses semua divisi (multi-divisi)
            foreach ($divisions as $div) {
                DivisionRole::create([
                    'user_id' => $deptHead->id,
                    'division_id' => $div->id,
                    'role_id' => $deptHeadRole->id,
                    'is_primary' => $div->id === $division->id,
                ]);
            }
            
            // 5 Section Head per division
            for ($i = 1; $i <= 5; $i++) {
                $sectionHead = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),
                    'role_id' => $sectionHeadRole->id,
                    'division_id' => $division->id,
                ]);
                
                // Section Head hanya akses divisinya sendiri
                DivisionRole::create([
                    'user_id' => $sectionHead->id,
                    'division_id' => $division->id,
                    'role_id' => $sectionHeadRole->id,
                    'is_primary' => true,
                ]);
            }
            
            // 50 Staff per division
            for ($i = 1; $i <= 50; $i++) {
                $staff = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),
                    'role_id' => $staffRole->id,
                    'division_id' => $division->id,
                ]);
                
                // Staff hanya akses divisinya sendiri
                DivisionRole::create([
                    'user_id' => $staff->id,
                    'division_id' => $division->id,
                    'role_id' => $staffRole->id,
                    'is_primary' => true,
                ]);
            }
            
            echo "Created 1 dept head, 5 section heads, and 50 staff for {$division->name}\n";
        }
        
        $totalUsers = User::count();
        echo "Total users created: {$totalUsers}\n";
        echo "Breakdown:\n";
        echo "- Admin: " . User::where('role_id', $adminRole->id)->count() . "\n";
        echo "- Dept Head: " . User::where('role_id', $deptHeadRole->id)->count() . "\n";
        echo "- Section Head: " . User::where('role_id', $sectionHeadRole->id)->count() . "\n";
        echo "- Staff: " . User::where('role_id', $staffRole->id)->count() . "\n";
    }
}
