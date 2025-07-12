<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@softui.com',
                'password' => Hash::make('password'),
                'role_id' => 4, // admin
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@softui.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // staff
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@softui.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // manager
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Section Head User',
                'email' => 'sectionhead@softui.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // section_head
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
