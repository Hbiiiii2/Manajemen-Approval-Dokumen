<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DivisionSeeder::class,
            ApprovalLevelSeeder::class,
            UserSeeder::class,
            DocumentTypeSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
