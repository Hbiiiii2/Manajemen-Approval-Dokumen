<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DemoOrgSeeder;

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
            ApprovalLevelSeeder::class,
            SimpleAdminSeeder::class,
            DocumentTypeSeeder::class,
        ]);
    }
}
